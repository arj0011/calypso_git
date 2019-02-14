<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Common_Controller {

    public $data = array();
    public $file_data = "";

    public function __construct() {
        parent::__construct();
        $this->is_auth_admin();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        //p($_POST);
        $this->data['parent'] = "User";
        $option = array(
            'table' => USERS,
            'where' => array('user_type'=>'USER'),
            'order' => array('id' => 'DESC'),
        );
        
        if($this->input->post('statusfilter') != ''):
            $option['where']['active'] = ($this->input->post('statusfilter') == 1) ? 1 : 0;
            $this->data['statusfilter'] = ($this->input->post('statusfilter') == 1) ? 1 : 2;
        endif;

        $this->data['list'] = $this->common_model->customGet($option);
        $this->data['title'] = "Users";
        $this->load->admin_render('list', $this->data, 'inner_script');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model() {
        $this->data['title'] = lang("add_user");
       
        $this->load->view('add', $this->data);
    }

    

   

    /**
     * @method users_add
     * @description add dynamic rows
     * @return array
     */
    public function users_add() {
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;
        // validate form input
        $this->form_validation->set_rules('full_name', lang('full_name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('user_email', lang('user_email'), 'required|trim|xss_clean|is_unique[users.email]');
        $this->form_validation->set_rules('password', lang('password'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == true) {

            $this->filedata['status'] = 1;
            $image = "";
            if (!empty($_FILES['user_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'users', 'user_image');
                if ($this->filedata['status'] == 1) {
                    $image = 'uploads/users/' . $this->filedata['upload_data']['file_name'];
                }
            }
            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {
                $email = strtolower($this->input->post('user_email'));
                $password = $this->input->post('password');
              
                $additional_data = array(
                    'full_name' => $this->input->post('full_name'),
                    'phone' => $this->input->post('phone_no'),
                    'email_verify' => 0,
                    'active'=> 1,
                    'user_image'=>$image,
                    'created_on' => datetime()
                );
                $insert_id = $this->userauth->signup($email, $password, $additional_data);
                if ($insert_id) {
                   
                    $response = array('status' => 1, 'message' => lang('user_success'), 'url' => base_url('users'));
                } else {
                    $response = array('status' => 0, 'message' => lang('user_failed'));
                }
            }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method user_edit
     * @description edit dynamic rows
     * @return array
     */
    public function user_edit() {
       $this->data['title'] = lang("edit_user");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array(
                'table' => USERS,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('users');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('users');
        }
    }

    /**
     * @method user_update
     * @description update dynamic rows
     * @return array
     */
    public function user_update() {

        $this->form_validation->set_rules('full_name', lang('full_name'), 'required|trim|xss_clean');
         $pass = $this->input->post('password');
        if ($pass != "") {
            // $this->form_validation->set_rules('password', lang('password'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|is_natural');
        }
      
        $where_id = $this->input->post('id');

        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
            $this->filedata['status'] = 1;
            $image = $this->input->post('exists_image');

            if (!empty($_FILES['user_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'users', 'user_image');

                if ($this->filedata['status'] == 1) {
                    $image = 'uploads/users/' . $this->filedata['upload_data']['file_name'];
                    unlink_file($this->input->post('exists_image'), FCPATH);
                }
            }
            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {
                  
                     
                     $options_data = array(
                        'full_name' => $this->input->post('full_name'),
                        'phone' => $this->input->post('phone_no'),
                        'user_image'=>$image
                     );
                      if ($pass != "") {
                        $options_data['password'] = md5($pass); 
                 }
                    $option = array(
                        'table' => USERS,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                
                $this->common_model->customUpdate($option);
              
                $response = array('status' => 1, 'message' => lang('user_success_update'), 'url' => base_url('users'));
            }
        endif;

        echo json_encode($response);
    }

    public function export_user() {

        $option = array(
            'table' => USERS,
            'select' => '*'
        );
        $users = $this->common_model->customGet($option);

        // $userslist = $this->Common_model->getAll(USERS,'name','ASC');
        $print_array = array();
        $i = 1;
        foreach ($users as $value) {


            $print_array[] = array('s_no' => $i, 'name' => $value->name, 'email' => $value->email);
            $i++;
        }

        $filename = "user_email_csv.csv";
        $fp = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        fputcsv($fp, array('S.no', 'User Name', 'Email'));

        foreach ($print_array as $row) {
            fputcsv($fp, $row);
        }
    }

    public function reset_password() {
        $user_id_encode = $this->uri->segment(3);

        $data['id_user_encode'] = $user_id_encode;

        if (!empty($_POST) && isset($_POST)) {

            $user_id_encode = $_POST['user_id'];

            if (!empty($user_id_encode)) {

                $user_id = base64_decode(base64_decode(base64_decode(base64_decode($user_id_encode))));


                $this->form_validation->set_rules('new_password', 'Password', 'required');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('reset_password', $data);
                } else {


                    $user_pass = $_POST['new_password'];

                    $data1 = array('password' => md5($user_pass));
                    $where = array('id' => $user_id);

                    $out = $this->common_model->updateFields(USERS, $data1, $where);



                    if ($out) {

                        $this->session->set_flashdata('passupdate', 'Password Successfully Changed.');
                        $data['success'] = 1;
                        $this->load->view('reset_password', $data);
                    } else {

                        $this->session->set_flashdata('error_passupdate', 'Password Already Changed.');
                        $this->load->view('reset_password', $data);
                    }
                }
            } else {

                $this->session->set_flashdata('error_passupdate', 'Unable to Change Password, Authentication Failed.');
                $this->load->view('reset_password');
            }
        } else {
            $this->load->view('reset_password', $data);
        }
    }

    
   
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Common_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth'));
        $this->load->library(array('userauth'));
        $this->load->helper(array('language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

   

     public function index() {
        $this->data['parent'] = "Dashboard";
        if (!$this->userauth->logged_in()) {
             //$this->session->set_flashdata('message', 'Your session has been expired');
             redirect('admin/login', 'refresh');
        } else {
             $id = $this->session->userdata('id');
             if($id == 1){
                $this->data['title'] = "Dashboard";
                $this->data['total_users'] = $this->common_model->getcount(USERS,array('user_type'=>'USER'));
                $this->data['total_offers'] = $this->common_model->getcount(BILLINGOFFER);
                $this->data['total_orders'] = $this->common_model->getcount(ORDER,array('status'=>6));
                $this->load->admin_render('dashboard', $this->data, 'inner_script');
            }else{
                $this->session->set_flashdata('message', 'You are not authorised to access administration');
                redirect('admin/login', 'refresh');
            }
        }
    }

    /**
     * @method login
     * @description login authentication
     * @return array
     */
   

    public function login() {
        $this->data['title'] = $this->lang->line('login_heading');
        $this->form_validation->set_rules('email', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        if (strtolower(getConfig('google_captcha')) == 'on') {
            $this->form_validation->set_rules('g-recaptcha-response', 'Google recaptcha', 'required');
        }

        if ($this->form_validation->run() == true) {

            $is_captcha = true;
            if (strtolower(getConfig('google_captcha')) == 'on') {
                if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                    $secret = getConfig('secret_key');
                    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
                    $responseData = json_decode($verifyResponse);
                    $is_captcha = $responseData->success;
                }
            }

            if ($is_captcha) {

                $remember = $this->input->post('remember');
               
              $is_login = $this->userauth->login($this->input->post('email'), $this->input->post('password'),$remember,'admin');
              
                 if ($is_login==1) {
                    
                    $this->session->set_flashdata('message', 'login successfull');
                    redirect('admin', 'refresh');
                } else {
                     $json['result']=json_decode($is_login);
                     $message= $json['result']->error_message;
                      
                    if($message){
                         $this->session->set_flashdata('message', $message);
                        redirect('admin/login', 'refresh');
                   
                    }
               
                  else
                  {
                     $this->session->set_flashdata('message', 'Username or Password is incorrect');
                    redirect('admin/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
                  } 
                   
                }
            } else {
                $this->session->set_flashdata('message', "Robot verification failed, please try again");
                redirect('admin/login', 'refresh');
            }
        } else {

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            
            $this->data['identity'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
                'placeholder' => 'Email'
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'placeholder' => 'Password',
                'autocomplete'=> "off",
                'onpaste' => "return false"
            );
            $this->load->view('login', $this->data);
        }
    }

    /**
     * @method logout
     * @description logout
     * @return array
     */
    public function logout() {
        $this->data['title'] = "Logout";
        $logout = $this->userauth->logout();
        
        $this->session->set_flashdata('message', 'user logged out successfully');
         $response = array('status' => 1, 'message' => 'user logged out successfully');
        echo json_encode($response);
    }

    /**
     * @method profile
     * @description profile display
     * @return array
     */
    public function profile() {
        $this->data['parent'] = "Profile";
        $this->adminAuth();
        $option = array(
            'table' => 'users',
            'where' => array('id' => $this->session->userdata('id')),
            'single' => true
        );
        $this->data['user'] = $this->common_model->customGet($option);
        $this->data['title'] = "Profile";
        $this->load->admin_render('profile', $this->data);
    }

    /**
     * @method updateProfile
     * @description user profile update
     * @return array
     */
    public function updateProfile() {
        $this->adminAuth();
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        if ($this->form_validation->run() == true) {

            $additional_data = array(
                'full_name' => $this->input->post('full_name'),
            );
            $option = array(
                        'table' => USERS,
                        'data' => $additional_data,
                        'where' => array('id' => $this->session->userdata('id'))
                    );
            if($this->common_model->customUpdate($option))
            {
                $this->session->set_flashdata('message', 'your profile account has been updated successfully');
                redirect('admin/profile');
            } else {
                $this->session->set_flashdata('message', 'your profile account has been updated successfully');
                redirect('admin/profile');
            }
        } else {
            $requireds = strip_tags($this->form_validation->error_string());
            $result = explode("\n", trim($requireds, "\n"));
            $this->session->set_flashdata('error', $result);
            redirect('admin/profile/');
        }
    }

    /**
     * @method password
     * @description change password dispaly
     * @return array
     */
    public function password() {
        $this->data['parent'] = "Password";
        $this->adminAuth();
        $this->data['error'] = "";
        $this->data['message'] = "";
        $this->data['min_password_length'] = 6;
        $this->data['old_password'] = array(
            'name' => 'old',
            'id' => 'old',
            'type' => 'password',
            'class' => 'form-control'
        );
        $this->data['new_password'] = array(
            'name' => 'new',
            'id' => 'new',
            'type' => 'password',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            'class' => 'form-control'
        );
        $this->data['new_password_confirm'] = array(
            'name' => 'new_confirm',
            'id' => 'new_confirm',
            'type' => 'password',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            'class' => 'form-control'
        );
        $this->data['user_id'] = array(
            'name' => 'user_id',
            'id' => 'user_id',
            'type' => 'hidden',
            'value' => $this->session->userdata('id'),
        );
        $this->data['title'] = "Password";
        $this->load->admin_render('changePassword', $this->data);
    }

    /**
     * @method change_password
     * @description change password
     * @return array
     */
    public function change_password() {
        $data['parent'] = "Password";
        $this->adminAuth();

        $data['title'] = "Password";
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'trim|required|xss_clean|matches[new]');
        

        if (!$this->userauth->logged_in()) {
            redirect('admin/login', 'refresh');
        }


        if ($this->form_validation->run() == false) {

            $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $data['min_password_length'] = 6;
            $data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
                'class' => 'form-control'
            );
            $data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
                'class' => 'form-control'
            );
            $data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
                'class' => 'form-control'
            );
            $data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $this->session->userdata('id'),
            );
            $this->load->admin_render('changePassword', $data);
        } else {

            $email = $this->session->userdata('email');

            $change = $this->userauth->change_password($email, $this->input->post('old'), $this->input->post('new'));

            if ($change==1) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', "The new password has been saved successfully.");
                redirect('admin/password');
            } else {
                $this->session->set_flashdata('error', "The old password you entered was incorrect");
                redirect('admin/change_password');
            }
        }
    }

    /**
     * @method forgot_password
     * @description forgot password
     * @return array
     */
    public function forgot_password() {
        $this->data['parent'] = "Forgot Password";
        $this->form_validation->set_rules('email', "Email", 'required|valid_email|xss_clean');
       
        if ($this->form_validation->run() == false) {
            $this->data['type'] = 'email';
           
            $this->data['identity'] = array('name' => 'email',
                'id' => 'email',
                'placeholder' => 'Email',
                'class' => 'form-control',
            );

            $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');


            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->load->view('forgot_password', $this->data);
        } else {
            
           $forgotten = $this->userauth->forgot_password($this->input->post('email'),'admin');
          
          
            if ($forgotten!=1) {
                 $json['result']=json_decode($forgotten);
                 $message= $json['result']->error_message;
                if($message){
                    $this->session->set_flashdata('message', $message);
                redirect("admin/forgot_password", 'refresh');
               
                }
            
        }else{
            $this->session->set_flashdata('message', 'email has been sent to your mail');
                redirect("admin/login", 'refresh'); //we should display a confirmation 
        }
      }
    }

    /**
     * @method reset_password
     * @description reset password
     * @return array
     */
    public function reset_password() {
        $code =$this->uri->segment(3);
        
        if (!$code) {
            show_404();
        }

        $user = $this->forgotten_password_check($code);
        if ($user) {

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'trim|required|xss_clean|matches[new]');

            if ($this->form_validation->run() == false) {

                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = 6;
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'placeholder' => 'New Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'placeholder' => 'Confirm Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;


                $this->load->view('admin/reset_password', $this->data);
            } else {

                // if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {


                //     $this->common_model->clear_forgotten_password_code($code);

                //     show_error($this->lang->line('error_csrf'));
                // } else {

                    $email = $user->email;

                    $change = $this->userauth->reset_password($email, $this->input->post('new'));

                    if ($change==1) {

                        $this->session->set_flashdata('message', 'Password changed successfully');
                        redirect("admin/login", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', 'Password change unsuccessful');
                        redirect('admin/reset_password/' . $code, 'refresh');
                    }
                }
            //}
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', 'Token has been expired');
            redirect("admin/forgot_password", 'refresh');
        }
    }


    public function forgotten_password_check($code)
    {
        $profile = $this->common_model->customGet(array('table' => USERS ,'where' =>array('forgotten_password_code' => $code),'single'=>true)); //pass the code to profile
      
        if (!($profile))
        {
           $this->session->set_flashdata('message', 'Token has been expired');
            return FALSE;
        }
        else
        {

              $exp_time = 172800000;
            if ($exp_time > 0) {
                //Make sure it isn't expired
                 if (time() - $profile->forgotten_password_time > $exp_time) {
                    //it has expired
                    $this->common_model->clear_forgotten_password_code($code);
                    $this->session->set_flashdata('message', 'Token has been expired');
                    return FALSE;
                }
             }
            return $profile;
        }
    }



    /**
     * @method _get_csrf_nonce
     * @description generate csrf
     * @return array
     */
    public function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    /**
     * @method _valid_csrf_nonce
     * @description valid csrf
     * @return array
     */
    public function _valid_csrf_nonce() {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

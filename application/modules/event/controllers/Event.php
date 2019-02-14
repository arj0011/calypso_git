<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends Common_Controller {

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
        $this->data['parent'] = "Event";
        $this->data['title'] = "Event";
        $option = array(
            'table' => EVENT,
            'select' => '*',
        );
        $this->data['list'] = $this->common_model->customGet($option);
        $this->load->admin_render('list', $this->data, 'inner_script');
    }


    function open_model() {
      $this->data['title'] = lang("event");
      $this->data['parent'] = lang("event");  
      $this->load->view('add', $this->data);
    }

    public function event_add() {
      $this->form_validation->set_rules('redirect_url', lang('event_redirect_url'), 'required|trim');

      if($this->form_validation->run() == true) {
        $this->filedata['status'] = 1;
        $image = "";
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'event', 'image');
          if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
          }
        }

        if ($this->filedata['status'] == 0) {
          $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{
          $redirect_url = $this->input->post('redirect_url');
          $redirect_url   = $this->security->xss_clean($redirect_url);

          $options_data = array(
            'redirect_url' => $redirect_url,
            'image'       => $image,
            'status'      => 1
          );
          
          $option = array('table' => EVENT, 'data' => $options_data);
          $insert = $this->common_model->customInsert($option); 
          if($insert) {
            $response = array('status' => 1, 'message' => lang('event_success'), 'url' => base_url('event'));
          }else {
            $response = array('status' => 0, 'message' => lang('event_failed'));
          }
          
        }
      } else {
        $messages = (validation_errors()) ? validation_errors() : '';
        $response = array('status' => 0, 'message' => $messages);
      }
      echo json_encode($response);
    }

    function del() {
        $response = 400;
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        
        if(!empty($table) && !empty($id) && !empty($id_name)) { 
            $option = array(
                    'table' => $table,
                    'where' => array($id_name => $id)
            );
            $delete = $this->common_model->customDelete($option);
            if($delete){
              $response = 200;
            }
             
        }
        echo $response;
    }

    //function is not in use
    //start
    public function index_old() {
        $this->data['parent'] = "Event";
        $this->data['title'] = "Event";
        $option = array('table' => EVENT,
                'select' => '*',
                'single' => true
            );
        $key_value = $this->common_model->customGet($option);
        $this->load->admin_render('add', $this->data, 'inner_script');
    }

    public function addrow(){
        $count = $this->input->post('count');
        $this->data['count'] = $count + 1;
        $this->load->view('row',$this->data);
    }
    //end

}

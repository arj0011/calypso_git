<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Offer extends Common_Controller { 
  public $data = array();
  public $file_data = "";
  public $element = array();
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
      $this->data['parent'] = "Offer";
      $this->data['title'] = "Offer";
      $option = array('table' => OFFERS .' as offer',
        'select' => '*',
        'order' => array('offer.id' => 'DESC'),
        );
      if ($_POST):
        if($this->input->post('statusfilter') != ''):

        $option['where']['status'] = ($this->input->post('statusfilter') == 1) ? 1 : 0;
        $this->data['statusfilter'] = ($this->input->post('statusfilter') == 1) ? 1 : 2;
        endif;

        if($this->input->post('start_date') != '' && $this->input->post('end_date') != ''):
        $option['where'] = array(
          'DATE(created_date) >= '=>date('Y-m-d',strtotime($this->input->post('start_date'))),
          'DATE(created_date) <='=>date('Y-m-d',strtotime($this->input->post('end_date')))
          );
        $this->data['start_date'] = $this->input->post('start_date');
        $this->data['end_date'] = $this->input->post('end_date');
        endif;  
      endif;  
        
      $this->data['list'] = $this->common_model->customGet($option);

     
      $this->load->admin_render('list', $this->data, 'inner_script');
    }
   
    /**
     * @method open_model
     * @description load model box
     * @return array
     */

    function open_model() {
      $this->data['title'] = lang("add_offer");
       $option =array(
        'table' => USERS,
        'select'=> '*',
        'where' => array('id!='=>1)
        );
      $this->data['users']= $this->common_model->customGet($option);
      $this->load->view('add', $this->data);
    }

    

    /**
     * @method cms_add
     * @description add dynamic rows
     * @return array
     */
    public function offer_add() {
      $this->form_validation->set_rules('type', lang('type'), 'required|trim');
      $this->form_validation->set_rules('status', lang('status'), 'required|trim');
      $this->form_validation->set_rules('date', 'Date', 'required|trim');
  
      if ($this->form_validation->run() == true) {
        $offer_text = $this->input->post('offer_text');
        $type = $this->input->post('type');
        $status = $this->input->post('status');
        $show_front = $this->input->post('shon_front');
        $date = $this->input->post('date');

        $this->filedata['status'] = 1;
        $image = "";
        if($offer_text == '' && empty($_FILES['image']['name'])){
          $response = array('status' => 0, 'message' => lang('text_img_required'));  
        }else{
          
          if (!empty($_FILES['image']['name'])) {
            $this->filedata = $this->commonUploadImage($_POST, 'offer', 'image');
            if ($this->filedata['status'] == 1) {
              $image = $this->filedata['upload_data']['file_name'];
            }
          }
          
          if($offer_text == ''):
            if ($this->filedata['status'] == 0) {
              $response = array('status' => 0, 'message' => $this->filedata['error']);die;
            }
          endif;

          $offer_text = $this->security->xss_clean($offer_text);
          $date = $this->security->xss_clean($date);
          $date = date('Y-m-d',strtotime($date));
          if(isset($_POST['show_front'])){
            $show_front = $_POST['show_front'];
          }else{
            $show_front = 0;
          }
          $options_data = array(

            'offer_text'    => $offer_text,
            'offer_image'   => $image,
            'type'          => $type,
            'status'        => $status,
            'show_front'    => $show_front,
            'created_date'  => $date,
          );

          $option = array('table' => OFFERS, 'data' => $options_data);
          $offer_id = $this->common_model->customInsert($option);
          if($offer_id) {
            $response = array('status' => 1, 'message' => lang('offer_success'), 'url' => base_url('offer'));
          }else {
            $response = array('status' => 0, 'message' => lang('offer_failed'));
          }
        }  
      }else {
        $messages = (validation_errors()) ? validation_errors() : '';
        $response = array('status' => 0, 'message' => $messages);
    }
    echo json_encode($response);
  }

    /**
     * @method cms_edit
     * @description edit dynamic rows
     * @return array
     */
    public function offer_edit() {
      $this->data['title'] = lang("edit_offer");
      $id = decoding($this->input->post('id'));
      if (!empty($id)) {

        $option = array(
          'table' => OFFERS,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
          $this->load->view('edit', $this->data);
        } else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('offer');
        }
      } else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('offer');
      }
    }

    /**
     * @method cms_update
     * @description update dynamic rows
     * @return array
     */
    public function offer_update() {

      $this->form_validation->set_rules('type', lang('type'), 'required|trim');
      $this->form_validation->set_rules('status', lang('status'), 'required|trim');
      $this->form_validation->set_rules('date', 'Date', 'required|trim');

      $where_id = $this->input->post('id');
      if ($this->form_validation->run() == FALSE):
        $messages = (validation_errors()) ? validation_errors() : '';
        $response = array('status' => 0, 'message' => $messages);
      else:
        $this->filedata['status'] = 1;
        $image = $this->input->post('exists_image');
        $offer_text  = $this->input->post('offer_text');
        $date  = $this->input->post('date');
        if($offer_text == '' && empty($_FILES['offer_image']['name']) && $_POST['exists_image'] == ''){
          $response = array('status' => 0, 'message' => lang('text_img_required'));   
        }else{
          if (!empty($_FILES['offer_image']['name'])) {
            $this->filedata = $this->commonUploadImage($_POST, 'offer', 'offer_image');

            if ($this->filedata['status'] == 1) {
             $image = $this->filedata['upload_data']['file_name'];
             delete_file($this->input->post('exists_image'), FCPATH."uploads/offer/");
            }
          }

          if($offer_text == ''):
            if ($this->filedata['status'] == 0) {
              $response = array('status' => 0, 'message' => $this->filedata['error']);die;
            }
          endif;
          
          $type = $this->input->post('type');
          $status = $this->input->post('status');
          $show_front = $this->input->post('shon_front');
          
          $offer_text  = $this->security->xss_clean($offer_text);
          $date = $this->security->xss_clean($date);
          $date = date('Y-m-d',strtotime($date));
          if(isset($_POST['show_front'])){
            $show_front = $_POST['show_front'];
          }else{
            $show_front = 0;
          }
          
          $options_data = array(
            'offer_text'    => $offer_text,
            'offer_image'   => $image,
            'type'          => $type,
            'status'        => $status,
            'show_front'    => $show_front,
            'created_date'  => $date
          );

          $option = array(
            'table' => OFFERS,
            'data' => $options_data,
            'where' => array('id' => $where_id)
          );
          $update = $this->common_model->customUpdate($option);
          $response = array('status' => 1, 'message' => lang('offer_success_update'), 'url' => base_url('offer'));
        }
      endif;
      echo json_encode($response);
  }

  function del() {
        $response = "";
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        if (!empty($table) && !empty($id) && !empty($id_name)) { 

            $option = array(
                'table' => $table,
                'where' => array($id_name => $id)
            );
            $delete = $this->common_model->customDelete($option);
            if ($delete) {
                $response = 200;
            } else
                $response = 400;
        }else {
            $response = 400;
        }
        echo $response;
    }



}

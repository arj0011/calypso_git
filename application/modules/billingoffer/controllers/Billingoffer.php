<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Billingoffer extends Common_Controller { 
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
     
      $this->data['parent'] = "Billing Offer";
      $this->data['title'] = "Billing Offer";
      $option = array(
        'table'=>BILLINGOFFER,
        'select'=>'*',
        'order'=>array('id'=>'DESC')
        ); 
      if ($_POST):
        if($this->input->post('statusfilter') != ''):
        $option['where']['status'] = ($this->input->post('statusfilter') == 1) ? 1 : 0;
        $this->data['statusfilter'] = ($this->input->post('statusfilter') == 1) ? 1 : 2;
        endif;

        if($this->input->post('start_date') != '' && $this->input->post('end_date') != ''):
        $option['where'] = array(
          'DATE(created) >= '=>date('Y-m-d',strtotime($this->input->post('start_date'))),
          'DATE(created) <='=>date('Y-m-d',strtotime($this->input->post('end_date')))
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
      $this->data['title'] = "Offer";
       $this->data['parent'] = "Offer";
      $this->load->view('add', $this->data);  
    
    }

    public function hierarchicalRenderer($list,$level=0){ 
      $element = [];
      if($list==''){
        return false;
      }else{
        foreach($list as $i=>$v){ 
          $temp = clone $v;
          if($level>0){ 
            $levelShower = '';
            for($i=0;$i<$level;$i++){
              $levelShower .= '- ';
            }
            $temp->category_name = $levelShower.''.$temp->category_name;
          }  
          unset($temp->childern);
          $this->element[] = $temp;
          if(isset($v->childern)){ 
            $this->hierarchicalRenderer($v->childern,$level+1);
          }
        }
        return $this->element;
      } 
    }


    /**
     * @method cms_add
     * @description add dynamic rows
     * @return array
     */
    public function offer_add() {
      $this->form_validation->set_rules('type', lang('type'), 'required|trim');
      $this->form_validation->set_rules('amount', lang('amount'), 'required|trim');
      $this->form_validation->set_rules('discount', lang('discount'), 'required|trim');
      $this->form_validation->set_rules('date', 'Date', 'required|trim');
      if(empty($_FILES['image']['name'])){
        $this->form_validation->set_rules('image', 'image', 'required');
      }
      if($this->form_validation->run() == true) {
        $this->filedata['status'] = 1;
        $image = "";
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'billingoffer', 'image');
          if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
          }
        }

        if ($this->filedata['status'] == 0) {
          $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{
          $current_date=date('Y-m-d');
          $user_id =array();
          $type = $this->input->post('type');
          $amount = $this->input->post('amount');
          $discount = $this->input->post('discount');
          $date = $this->input->post('date');
         
          if(($amount <= 0 && $discount <= 0) || ($amount <= 0.00 && $discount <= 0.00)){
            $response = array('status' => 0, 'message' => 'Amount and discount wont be zero.','url' => base_url('billingoffer'));
            echo json_encode($response);die;
          }


          $type   = $this->security->xss_clean($type);
          $amount = $this->security->xss_clean($amount);
          $discount   = $this->security->xss_clean($discount);
          $date   = $this->security->xss_clean($date);
          $date   = date('Y-m-d',strtotime($date));
      
          $options_data = array(
            'type'     => $type,
            'amount'   => $amount,
            'discount' => $discount,
            'image'    => $image,
            'created'  => $date,
            'status'   => 1,
          );
          
          $option = array('table' => BILLINGOFFER, 'data' => $options_data);
          $offer_id = $this->common_model->customInsert($option); 
          if($offer_id) {

            // Get all users
            $option = array(
              'table' => USERS,
              'select' => 'id',
              'where'=>array('user_type'=>'USER')
            );
            $user_id = $this->common_model->customGet($option);

            foreach ($user_id as $key => $value) {
              $all_users[] = $value->id;
            }

            // Insert Notification in admin notification table   
            if($type == 1){
              $typstr = 'Al-a Cart';
            }else if($type == 2){
              $typstr = 'Foodparcel';
            }else if($type == 3){
              $typstr = 'Partypackage';
            }
            $offer_name = $discount."% OFF on ".$typstr." Bill";


            $notification_arr = array(
              'message' => $offer_name,
              'title' => 'Offer',
              'type_id' => $offer_id,
              'user_ids' => serialize($all_users),
              'notification_type' => 2,
              'type'=>$type,
              'sent_time' => datetime()
            );
            $lid = $this->common_model->insertData(ADMIN_NOTIFICATION,$notification_arr);

            // Insert Notifications in user notification table
             
            $user_notifications = array();
            for($i=0;$i<count($all_users);$i++){
              
              $insertArray = array(
                  'type_id' => $offer_id,
                  'sender_id' => ADMIN_ID,
                  'reciever_id' => $all_users[$i],
                  'notification_type' => 'Offer',
                  'type'=>$type,
                  'title' => 'Offer',
                  'notification_parent_id' => $lid,
                  'message' => $offer_name,
                  'is_read' => 0,
                  'is_send' => 0,
                  'sent_time' => date('Y-m-d H:i:s'),
              );
              
              array_push($user_notifications, $insertArray);
            }

            if(!empty($user_notifications)){
              $this->common_model->insertBulkData(USER_NOTIFICATION,$user_notifications);
            }

            $response = array('status' => 1, 'message' => 'Offer added successfully', 'url' => base_url('billingoffer'));
          }else {
            $response = array('status' => 0, 'message' => 'Offer insertion failed','url' => base_url('billingoffer'));
          }
          
        }
      } else {
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
      $this->data['title'] = "Offer";
      $id = decoding($this->input->post('id'));
      if (!empty($id)) {
        $option = array(
          'table' => BILLINGOFFER,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
          $this->load->view('edit', $this->data);    
        }else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('allacart');
        }  
      }else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('allacart');
      }
    }

    /**
     * @method cms_update
     * @description update dynamic rows
     * @return array
     */
    public function offer_update() {

      $image = $this->input->post('exists_image');
      $this->form_validation->set_rules('type', lang('type'), 'required|trim');
      $this->form_validation->set_rules('amount', lang('amount'), 'required|trim');
      $this->form_validation->set_rules('discount', lang('discount'), 'required|trim');
      $this->form_validation->set_rules('date', 'Date', 'required|trim');
      if(empty($image)){
        if (empty($_FILES['image']['name'])) {
          $this->form_validation->set_rules('image', 'image', 'required');
        }

      }


      $where_id = $this->input->post('id');
      if ($this->form_validation->run() == FALSE):
        $messages = (validation_errors()) ? validation_errors() : '';
        $response = array('status' => 0, 'message' => $messages);
      else:
        $this->filedata['status'] = 1;
        
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'billingoffer', 'image');
          if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
            delete_file($this->input->post('exists_image'), FCPATH."uploads/billingoffer/");
          }
        }
        if ($this->filedata['status'] == 0) {
          $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{
          $type = $this->input->post('type');
          $amount = $this->input->post('amount');
          $discount = $this->input->post('discount');
          $date = $this->input->post('date');
          $type   = $this->security->xss_clean($type);
          $amount = $this->security->xss_clean($amount);
          $discount   = $this->security->xss_clean($discount);
          $date   = $this->security->xss_clean($date);
          $date   = date('Y-m-d');
          
          $options_data = array(
            'type'    => $type,
            'amount'  => $amount,
            'discount'=> $discount,
            'image'   => $image,
            'created'  => $date,
          );

          $option = array(
            'table' => BILLINGOFFER,
            'data' => $options_data,
            'where' => array('id' => $where_id)
            );
          
          $update = $this->common_model->customUpdate($option);
          if($update){
            $response = array('status' => 1, 'message' => "Offer updated successfully", 'url' => base_url('billingoffer'));
          }else{
            $response = array('status' => 0, 'message' => "You haven'\t change any fields", 'url' => base_url('billingoffer'));
          }
          
        }
      endif;
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

}

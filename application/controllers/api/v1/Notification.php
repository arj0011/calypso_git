<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for notification
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun Choudhary
 */
class Notification extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }

  
/**
     * Function Name: notification_list
     * Description:   To Get Notification list
  */

  public function notification_list_post(){

    $data = $this->input->post();

     $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric');
     $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

    if ($this->form_validation->run() == FALSE) {
      $error = $this->form_validation->rest_first_error_string();
      echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
    } else {

              $page_no    = extract_value($data,'page_no',1);  
              $offset     = get_offsets($page_no);
              $user_id    = extract_value($data, 'user_id', '');
              is_deactive($user_id);
              $options = array(
                'table' => USER_NOTIFICATION,
                'select' => '*',
                'where' => array('reciever_id'=>$user_id), 
                'order' => array('id' => 'desc'),
                'limit' => array(10 => $offset)
              );

              $notifications = $this->common_model->customGet($options);

              $total_requested = (int) $page_no * 10;

              $option1 = array(
               'table' => USER_NOTIFICATION,
               'select' => 'id',
               'where' => array('reciever_id'=>$user_id)
               );
             $total_records = $this->common_model->customCount($option1);
             
              if($total_records > $total_requested){                      
                $has_next = TRUE;                    
              }else{                        
                $has_next = FALSE;                    
              }
           
              if(!empty($notifications)){
                  foreach ($notifications as $rows):  
                    $temp['id']                = null_checker($rows->id);
                    $temp['type_id']           = null_checker($rows->type_id);
                    $temp['type']              = null_checker($rows->type);
                    $temp['notification_type'] = null_checker($rows->notification_type);
                    $temp['message']           = null_checker($rows->message);
                    $temp['is_read']           = null_checker($rows->is_read);
                    $temp['sent_time']         = null_checker(convertDate($rows->sent_time));
                    $eachArr[] = $temp; 
                  endforeach;
                echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Notification List found successfully']);
            }else{
              echo $this->jsonresponse->geneate_response(1, 0,'Notification not found',[]);
            }
    }

  } 

  public  function clear_notifi_post(){
      $data = $this->input->post();

    $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric');

    if ($this->form_validation->run() == FALSE) {
      $error = $this->form_validation->rest_first_error_string();
      echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
    } else {
      $user_id = extract_value($data, 'user_id', '');
      is_deactive($user_id);
      $option = array(
        'table' => USER_NOTIFICATION,
        'where' => array('reciever_id'=>$user_id),
      );
      $delete = $this->common_model->customDelete($option);
      if($delete){
        echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Notification clear successfully']);
      }else{
        echo $this->jsonresponse->geneate_response(1, 0,'Notification not cleared',[]);
      }
    }

  }
  

}


/* End of file Notification.php */
/* Location: ./application/controllers/api/v1/Notification.php */
?>
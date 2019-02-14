<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Parceldelivery extends Common_Controller { 
  public $data = array();
  public $file_data = "";
  public $element = array();
  public function __construct() {
    $this->load->library(array('wallet'));
    parent::__construct();
    $this->is_auth_admin();
  }

     /**
     * @method index
     * @description listing display
     * @return array
     */
     public function index() {
      $this->data['parent'] = "Parceldelivery";
      $this->data['title'] = "Parceldelivery";
      $option = array('table' => ORDER .' as o',
        'select' => 'o.id,u.full_name, u.phone,o.created,o.delivery_date,delivery_time,o.status,o.type,o.net_amount,o. unique_order_id,o.redeemption_code,o. payment_status',
        // 'join' => array(array(USERS.' as u'=>'u.id=o.user_id'),array(USERADDRESS.' as ad'=>'ad.id=o.address_id')),
        'join'=> array(USERS.' as u'=>'u.id=o.user_id'),
        'where' => array(
          'o.status != '=>4,
          //'o.type'=>2,
          //'o.delivery_date >= ' => date('Y-m-d'),
          //'o.delivery_time >= '=>date('H:i:s')
          'o.payment_status != ' => 1 
          ),
		    'order' => array('o.id' => 'DESC'),
		  );

      $this->data['list'] = $this->common_model->customGet($option);
      $this->load->admin_render('list', $this->data, 'inner_script');
    }
   
    public function alacart() {
      $this->data['parent'] = "Parceldelivery";
      $this->data['title'] = "Parceldelivery";
      $option = array('table' => ORDER .' as o',
        'select' => 'o.*,u.full_name, u.phone',
        'join'=> array(USERS.' as u'=>'u.id=o.user_id'),
        'where' => array('o.type'=>1),
        'order' => array('o.id' => 'DESC'),
      );
      if ($_POST) {
        if($this->input->post('statusfilter') != ''):
            $option['where']['status'] = $this->input->post('statusfilter');
            $this->data['statusfilter'] = $this->input->post('statusfilter');
            endif;  
      }
      
      $this->data['list'] = $this->common_model->customGet($option);
      $this->load->admin_render('list', $this->data, 'inner_script');
    }

    public function foodparcel() {
      $this->data['parent'] = "Parceldelivery";
      $this->data['title'] = "Parceldelivery";
      $option = array('table' => ORDER .' as o',
        'select' => 'o.*,u.full_name, u.phone',
        'join'=> array(USERS.' as u'=>'u.id=o.user_id'),
        'where' => array('o.type'=>2),
        'order' => array('o.id' => 'DESC'),
      );
      if ($_POST):
        if($this->input->post('statusfilter') != ''):
            $option['where']['status'] = $this->input->post('statusfilter');
            $this->data['statusfilter'] = $this->input->post('statusfilter');
            endif;
      endif;
      $this->data['list'] = $this->common_model->customGet($option);
      $this->load->admin_render('list', $this->data, 'inner_script');
    }

    public function partypackage() {
      $this->data['parent'] = "Parceldelivery";
      $this->data['title'] = "Parceldelivery";
      $option = array('table' => ORDER .' as o',
        'select' => 'o.*,u.full_name, u.phone',
        'join'=> array(USERS.' as u'=>'u.id=o.user_id'),
        'where' => array('o.type'=>3,'o.status != '=>1),
        'order' => array('o.id' => 'DESC'),
      );
      if($_POST):
        if($this->input->post('statusfilter') != ''):
            $option['where']['status'] = $this->input->post('statusfilter');
            $this->data['statusfilter'] = $this->input->post('statusfilter');
            endif;
      endif;
      $this->data['list'] = $this->common_model->customGet($option);
      $this->load->admin_render('list', $this->data, 'inner_script');
    }
   
    function view_order() {
      $this->data['title'] = 'allacart';
      $order_id = decoding($this->uri->segment(3));
      $option = array('table' => ORDER .' as o',
        'select' => 'o.*,u.full_name,u.email, u.phone,c.item_name,c.item_image,c.male,c.female,c.quanity,c.price',
        'join' => array(array(USERS.' as u'=>'u.id=o.user_id'),
                  array(CART. ' as c' =>'c.order_id=o.id')),
        'where' => array('o.id'=>$order_id)
    );

      $this->data['order'] = $this->common_model->customGet($option);
      $this->load->admin_render('view', $this->data,'inner_script');
    }

    public function order_edit() {
      $this->data['title'] = lang("edit_order");
      $id = decoding($this->input->post('id'));
      if (!empty($id)) {

    
        $option = array(
          'table' => ORDER,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
          $this->load->view('edit', $this->data);
        } else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('parceldelivery');
        }
      } else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('parceldelivery');
      }
    }

    /**
     * @method cms_edit
     * @description edit dynamic rows
     * @return array
     */
    public function edit_status() {
      $this->data['title'] = lang("edit_order");
      $id     = decoding($this->input->post('id'));
      $type   = $this->input->post('type');
      $status = $this->input->post('status');
      // status = 1-pending,2-confirm,3-process,4-complete,5-cancelled,6-deliver
      if (!empty($id)) {
        $orderdata = $this->db->get_where(ORDER,array('id'=>$id))->row_array();
        
        if(!empty($orderdata)){
          $net_amount = $orderdata['net_amount'];
          $paid_amount = $orderdata['paid_amount'];
          $pending_amount = $orderdata['pending_amount'];
          $order_status = $orderdata['status'];
          $delivery_date = $orderdata['delivery_date'];
          $delivery_time = $orderdata['delivery_time'];
          $payment_status = $orderdata['payment_status'];
          $user_id = $orderdata['user_id'];
          $desc = '';
          if($status == 1 || $status == 2 || $status == 3){
            
            $options_data = array('status'=> $status);  

          }else if($status == 4 || $status == 6){
            $options_data = array('status'=> $status);
            if(isset($_POST['amount']) && $_POST['amount'] > 0){
              $paid_amount = $paid_amount + $_POST['amount'];
              $options_data['paid_amount'] = $paid_amount;
              $options_data['pending_amount'] = $pending_amount - $_POST['amount'];
             }

            // if($net_amount != $paid_amount){
            //   $response = array('status' => 0, 'message' => 'Order can\'t be completed! Payment is Remaining.', 'url' => base_url('parceldelivery/'.$type));
            //   echo json_encode($response);die;  
            // }
              
          }else if($status == 5){
           
            // check order can be cancel or not using time policy
            // Al-a cart and Foodparcel - Before 4 Hours
            // Party Package - Before 24 hours
            
            // if customer has done payment
            //if($payment_status == 1){
            
              /*if($type == 'alacart'){
                $alacart_cancel_time = getConfig('alacart_cancel_time');
                $alacart_cancel_time = $alacart_cancel_time * 3600;  
                $deliverytimestamp = $deliverytimestamp - $alacart_cancel_time;
              }elseif($type == 'foodparcel'){
                $foodparcel_cancel_time = getConfig('foodparcel_cancel_time');
                $foodparcel_cancel_time = $foodparcel_cancel_time * 3600;
                $deliverytimestamp = $deliverytimestamp - $foodparcel_cancel_time;
              }
              else{
                $partypackage_cancel_time = getConfig('partypackage_cancel_time');
                $partypackage_cancel_time = $partypackage_cancel_time * 3600; 
                $deliverytimestamp = $deliverytimestamp - $partypackage_cancel_time;
              }*/
              
              $difference = 0;
              $current_datetime = date('Y-m-d H:i:s');
              $delivery_datetime = date('Y-m-d H:i:s', strtotime("$delivery_date $delivery_time"));
              $deliverytimestamp = strtotime($delivery_datetime);
              $currenttimestamp = strtotime($current_datetime);

              $difference = round(abs($currenttimestamp - $deliverytimestamp) / 3600,2);
              if($type == 'alacart'){
                $cancel_time = getConfig('alacart_cancel_time');
              }elseif($type == 'foodparcel'){
                $cancel_time = getConfig('foodparcel_cancel_time');
              }
              else{
                $cancel_time = getConfig('partypackage_cancel_time');
              }



              //if cancel time limit is over
              if($difference > $cancel_time){
                $response = array('status' => 0, 'message' => 'Can not cancel order! Time Over', 'url' => base_url('parceldelivery/'.$type));
                echo json_encode($response);die;
              }else{
                
                // if time limit not over then check who is cancel the order whether Admin or User
                // if admin then NO cancellation charge apply
                // if User then cancellation charg apply
                
                $remaining_amount = 0;
                $person = '';
                if(isset($_POST['person'])){
                  $person = $_POST['person'];

                  // person - 1 = user , 2- admin
                  if($person == 2){
                    // $options_data = array('status'=> $status);
                    $remaining_amount = $paid_amount;
                    $desc = "Admin cancel order due to some reasons";
                  }else{
                    $desc = "User wants to cancel his order";
                    if($type == 'alacart'){
                      $cancel_percent = getConfig('alacart_cancel_percent');
                    }elseif($type == 'foodparcel'){
                      $cancel_percent = getConfig('foodparcel_cancel_percent');
                    }elseif($type == 'partypackage'){
                      $cancel_percent = getConfig('partypackage_cancel_percent');
                    }
                  
                    //calculate cancel amount
                    $cancel_amount = ($paid_amount * $cancel_percent) / 100;
                    $remaining_amount = $paid_amount - $cancel_amount;
                    $options_data['cancel_amount'] = $cancel_amount;
                  }  
                  
                  //get user wallet amount
                  $wallet_amount = $this->common_model->user_wallet_amount($user_id);
                  // Update user wallet(transfer remaining amount in user wallet)
                  if($wallet_amount){
                    $wallet_amount = $wallet_amount + $remaining_amount;
                    $this->db->where('user_id',$user_id);
                    $this->db->update(USERWALLET,array('amount'=>$wallet_amount));  
                  }else{
                    $wallet_amount = $wallet_amount + $remaining_amount;
                    $this->db->insert(USERWALLET,array('user_id'=>$user_id,'amount'=>$wallet_amount));  
                  }
                    
                  $options_data['cancel_return_amount'] = $remaining_amount;  
                  $options_data['status'] = $status;  
                  
                }else{
                  // Order Cancellation case but person is not define(user/admin)
                  $response = array('status' => 0, 'message' => 'Opps! something went wrong', 'url' => base_url('parceldelivery/'.$type));
                }
              }  
            //}
            // else{
            //   //There is no payment done like COD
            //   $options_data = array('status'=>$status); 
            // } 
          }
          
          if(!empty($options_data)){
            $option = array(
            'table' => ORDER,
            'data' => $options_data,
            'where' => array('id' => $id)
            );
            $update = $this->common_model->customUpdate($option);  
          }
          
          if($update){

            // Insert details in wallet table 
            $wallet_id = '';
            // if($status == 5 && $payment_status == 1 && $remaining_amount > 0){
            if($status == 5 && $remaining_amount > 0){
              $wallet_detail = array(
                'user_id'=>$user_id,
                'order_id'=>$id,
                'transaction_type'=>'CREDIT',
                'description'=>$desc,
                'amount'=>$remaining_amount,
                'transcation_user_type'=>$person,
                'date'=>date('Y-m-d H:i:s')
              );
              $opt_wallet = array(
                'table'=>WALLET,
                'data'=>$wallet_detail
              );  

              $wallet_id = $this->common_model->customInsert($opt_wallet);
            }
            
            // send Push notification
            
            // noti_type :- 1 - status change, 2 - offer, 3 - best offer
            if($type == 'alacart'){
              $typeval = 1;
            }elseif($type == 'foodparcel'){
              $typeval = 2;
            }elseif($type == 'partypackage'){
              $typeval = 3;
            }

            // Insert Notifications in user notification table
             $message = '';
              if($status == 1){
                $message = "Your order is pending";
              }else if($status == 2){
                $message = "Your order is confirm";
              }else if($status == 3){
                $message = "Your order is in progress";
              }else if($status == 4){
                $message = "Your order is completed";
              }else if($status == 5){
                $message = "Your order is cancelled";
              }else if($status == 6){
                $message = "Your order is delivered";
              }

              $insertArray = array(
                  'type_id' => $id, // order id
                  'sender_id' => ADMIN_ID,
                  'reciever_id' => $user_id,
                  'notification_type' => 'Order',
                  'type'=> $typeval,
                  'title' => 'Order',
                  'message' => $message,
                  'is_read' => 0,
                  'is_send' => 0,
                  'sent_time' => date('Y-m-d H:i:s'),
              );
             
            $opt = array(
              'table'=>USER_NOTIFICATION,
              'data'=>$insertArray
            );  
            $unoti_id = $this->common_model->customInsert($opt);

            if($unoti_id):
              $params = array('order_id'=>$id,'type'=>$typeval,'status'=>$order_status,'noti_type'=>1);
              send_push_notifications($message,$user_id,$params);


              //send notification for order cancellation wallet amount credit in user wallet.
              if($wallet_id):
                $trans_type = "credit";
                $this->common_model->send_wallet_notification($id,$user_id,$typeval,$remaining_amount,$trans_type);
              endif;

            endif;
            //end

            $response = array('status' => 1, 'message' => 'Status updated successfully', 'url' => base_url('parceldelivery/'.$type));
          }else{
            $response = array('status' => 0, 'message' => 'Status not changed', 'url' => base_url('parceldelivery/'.$type));
          }
        }else{
          $response = array('status' => 0, 'message' => 'Order not found', 'url' => base_url('parceldelivery/'.$type));
        }
      }else{
        $response = array('status' => 0, 'message' => 'Order not found', 'url' => base_url('parceldelivery/'.$type));
      }
      echo json_encode($response);
    }    


    public function update_remain_amount(){
      $id     = decoding($this->input->post('id'));
      $type   = $this->input->post('type');
      $status = $this->input->post('status');
      $amount = $this->input->post('amount');
      if (!empty($id)) {
          
          $update = false;
          $orderdata = $this->db->get_where(ORDER,array('id'=>$id))->row_array();
          
          if(!empty($orderdata)){
            $net_amount = $orderdata['net_amount'];
            $paid_amount = $orderdata['paid_amount'];
            $pending_amount = $orderdata['pending_amount'];
            $user_id = $orderdata['user_id'];

            $pending_amount = $paid_amount + $amount;
            
            if($net_amount == $pending_amount){
              
              $paid_amount = $pending_amount;
              
              $options_data = array(
                'status'=>$status,
                'pending_amount'=>0,
                'paid_amount'=>$paid_amount
              );
              
              $option = array(
                'table' => ORDER,
                'data' => $options_data,
                'where' => array('id' => $id)
              );

              $update = $this->common_model->customUpdate($option);
            
            }
            
            if($update){
              $response = array('status' => 1, 'message' => 'Status updated successfully', 'url' => base_url('parceldelivery/'.$type));
            }else{
              $response = array('status' => 0, 'message' => 'Status not changed', 'url' => base_url('parceldelivery/'.$type));
            }

          }else{
            $response = array('status' => 0, 'message' => 'Order not found', 'url' => base_url('parceldelivery/'.$type));
          }
      }else{
        $response = array('status' => 0, 'message' => 'Order not found', 'url' => base_url('parceldelivery/'.$type));
      }

      echo json_encode($response);

    }




}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Common_Controller { 
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
      $this->data['parent'] = "Order";
      $this->data['title'] = "Order";
      $option = array('table' => ORDERS .' as o',
        'select' => 'o.id,u.full_name, u.phone, o.order_products,o.total_price,o.order_date,o.delivered_on,o.order_priority,o.estimated_delivery_date,o.status,o.remaining_amount',
        'join' => array(USERS.' as u'=>'u.id=o.user_id'),
        'where' => array('o.order_via'=>'app'),
		    'order' => array('o.id' => 'DESC'),
		 );

      $this->data['list'] = $this->common_model->customGet($option);
	
      $this->load->admin_render('list', $this->data, 'inner_script');
    }
   
    /**
     * @method open_model
     * @description load model box
     * @return array
     */

   

    function add() {
      $this->data['title'] = lang("add_product");
       $option =array(
        'table' => USERS,
        'select'=> '*',
        'where' => array('id!='=>1)
        );
      $this->data['users']= $this->common_model->customGet($option);
      $this->load->admin_render('add', $this->data,'inner_script');
    }

    function view_order() {
      $this->data['title'] = lang("view_purchase");
      $order_id = decoding($this->uri->segment(3));
     $option = array('table' => ORDERS .' as o',
        'select' => 'o.id,u.full_name,u.email, u.phone, o.order_products,o.total_price,o.order_date,o.delivered_on,o.order_priority,o.estimated_delivery_date,o.status,ord_meta.product_id,ord_meta.product_price,ord_meta.product_qty,ord_meta.product_total,product.product_name,o.order_via,o.remaining_amount,o.wallet_payment,o.cash_payment,o.status',
        'join' => array(array(USERS.' as u'=>'u.id=o.user_id'),
                  array(ORDER_META. ' as ord_meta' =>'ord_meta.order_id=o.id'),
                  array(PRODUCTS.' as product'=>'product.id=ord_meta.product_id')),
        'where' => array('o.id'=>$order_id),
        
    );

      $this->data['order'] = $this->common_model->customGet($option);
     
      $this->load->admin_render('view', $this->data,'inner_script');
    }


    /**
     * @method cms_edit
     * @description edit dynamic rows
     * @return array
     */
    public function order_edit() {
      $this->data['title'] = lang("edit_order");
      $id = decoding($this->input->post('id'));
      if (!empty($id)) {

    
        $option = array(
          'table' => ORDERS,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
          $this->load->view('edit', $this->data);
        } else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('order');
        }
      } else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('order');
      }
    }
    
    public function order_update() {

      $this->form_validation->set_rules('cash_payment', lang('cash_payment'), 'required|trim');


      $where_id = $this->input->post('id');
      if ($this->form_validation->run() == FALSE):
        $messages = (validation_errors()) ? validation_errors() : '';
      $response = array('status' => 0, 'message' => $messages);
      else:

        $current_date = date('Y-m-d');
        $current_date_expiry_check = date('Y-m-d H:i:s',strtotime('-1 day'));
       
        $cash_payment = $this->input->post('cash_payment');
        $remaining_amount = 0;
        $user_id = $this->input->post('user_id');
      
        $options_data = array(

        'cash_payment'       => $cash_payment,
        'remaining_amount'   => $remaining_amount,
        'cash_payment_date'  =>  $current_date,
        'payment_via'        => 'cash',
        'last_updated_date'  => datetime(),
        'delivered_on'       => $current_date,
        //'estimated_delivery_date'  => $current_date,
        'status'             => 1

        );
        $option = array(
          'table' => ORDERS,
          'data' => $options_data,
          'where' => array('id' => $where_id)
          );
        $update = $this->common_model->customUpdate($option);
        $option = array('table' => USER_MEMBERSHIP,
            'select' => '*',
            'where' => array('user_id'=>$user_id),
            'single' =>true
            );
           $list = $this->common_model->customGet($option);
           if(!empty($list))
          {
            $member_expiry = $list->subscription_expiry_date;

             if($member_expiry!='' && $member_expiry!='NULL'){
               if($current_date_expiry_check > $member_expiry)
               {
                 $sub_expiry = true;
               }else{
                 $sub_expiry = false;
               }
             }else{
              $sub_expiry = false;
            }

            $membership = $list->membership_type;
            if($membership=='PREMIUM' && $sub_expiry==false)
            {
              
              $membership_type = 'PREMIUM';
            }else{
              
              $membership_type = 'BASIC';
            }
           
          }else{
            $membership_type = 'BASIC';
          }
         $reward_point = $this->wallet->calculate_wallet_point($membership_type,$user_id,$cash_payment);

      $this->wallet->add_wallet_log('CREDIT',$cash_payment,$current_date,$where_id,$user_id,'USER',$membership_type,"Earned ". $reward_point ." Points on Order No. ". $where_id ."");
        
       

          $options = array(
                'table' => ORDERS . ' as order',
                'select' => 'order.id,users.device_id,users.device_type,users.device_token,users.badges,users.email,order.user_id',
                'join' => array(USERS . ' as users' => 'users.id=order.user_id'),
                'where' => array('order.id' => $where_id),
                'single' => true
            );
            $users_device = $this->common_model->customGet($options);
             if ($users_device->device_type == 'ANDROID') {
                    $user_badges = $users_device->badges + 1;
                    
                    $data_array = array(
                        'title' => "Reward Points",
                        'body' => "Earned ". $reward_point ." Points on Order No.". $where_id ."",
                        'type' => 'Wallet',
                        'order_id' => $where_id,
                        'badges' => $user_badges,
                    );
                       $noti_data = array('body' => 
                        "Earned ". $reward_point ." Points on Order No.". $where_id ."",'params' => $data_array);
                     $status = send_android_notification($noti_data, $users_device->device_token, $user_badges,$users_device->user_id);
                   
                        $user_notifications = array(
                            'type_id' => $where_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device->user_id,
                            'notification_type' => 'Wallet',
                            'title' => 'Reward Points',
                            'notification_parent_id' => 0,
                            'message' => "Earned ". $reward_point ." Points on Order No.". $where_id ."",
                            'is_read' => 0,
                            'is_send' => 1,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        $this->common_model->insertData(USER_NOTIFICATION, $user_notifications);
                    
                }

                if ($users_device->device_type == 'IOS') {
                    $user_badges = $users_device->badges + 1;
                    $params = array(
                        'title' => "Reward Points",
                        'type' => "Wallet",
                        'order_id' => $where_id
                    );
                    $status = send_ios_notification($users_device->device_token, "Earned ". $reward_point ." Points on Order No.". $where_id ."", $params, $user_badges,$users_device->user_id);
                    
                        $user_notifications = array(
                            'type_id' => $where_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device->user_id,
                            'notification_type' => 'Wallet',
                            'title' => 'Reward Points',
                            'notification_parent_id' => 0,
                            'message' => "Earned ". $reward_point ." Points on Order No.". $where_id ."",
                            'is_read' => 0,
                            'is_send' => 1,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        $this->common_model->insertData(USER_NOTIFICATION, $user_notifications);

                }

              $response = array('status' => 1, 'message' => 'order status updated successfully', 'url' => base_url('order'));
   


    endif;

    echo json_encode($response);
  }


    public function changestatus() {
        $where_id = $this->input->post('rid');

        $options_data = array(
            'status' => 3
        );
        $option = array(
            'table' => ORDERS,
            'data' => $options_data,
            'where' => array('id' => $where_id)
        );
        if ($this->common_model->customUpdate($option)) {
           $options = array(
                'table' => ORDERS . ' as order',
                'select' => 'order.id,users.device_id,users.device_type,users.device_token,users.badges,users.email,order.user_id',
                'join' => array(USERS . ' as users' => 'users.id=order.user_id'),
                'where' => array('order.id' => $where_id),
                'single' => true
            );
            $users_device = $this->common_model->customGet($options);
             if ($users_device->device_type == 'ANDROID') {
                    $user_badges = $users_device->badges + 1;
                    $data_array = array(
                        'title' => "Order Ready Status",
                        'body' => 'Your Order is ready',
                        'type' => 'Order',
                        'order_id' => $where_id,
                        'badges' => $user_badges,
                    );
                       $noti_data = array('body' => 
                        'Your Order is ready','params' => $data_array);
                   $status = send_android_notification($noti_data, $users_device->device_token, $user_badges,$users_device->user_id);

                        $user_notifications = array(
                            'type_id' => $where_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device->user_id,
                            'notification_type' => 'Order',
                            'title' => 'Order Ready Status',
                            'notification_parent_id' => 0,
                            'message' => 'Your Order is ready',
                            'is_read' => 0,
                            'is_send' => 1,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        $this->common_model->insertData(USER_NOTIFICATION, $user_notifications);
                   
                }

                if ($users_device->device_type == 'IOS') {
                    $user_badges = $users_device->badges + 1;
                    $params = array(
                        'title' => "Order Ready Status",
                        'type' => "Order",
                        'order_id' => $where_id
                    );
                    $status = send_ios_notification($users_device->device_token, 'Your Order is ready', $params, $user_badges,$users_device->user_id);
                   
                        $user_notifications = array(
                            'type_id' => $where_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device->user_id,
                            'notification_type' => 'Order',
                            'title' => 'Order Ready Status',
                            'notification_parent_id' => 0,
                            'message' => 'Your Order is ready',
                            'is_read' => 0,
                            'is_send' => 1,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        $this->common_model->insertData(USER_NOTIFICATION, $user_notifications);
                   
                }

            echo 1;
        } else {
            echo 0;
        }
    }
	

}

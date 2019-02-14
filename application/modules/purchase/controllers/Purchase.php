<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends Common_Controller { 
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
      $this->data['parent'] = "Purchase";
      $this->data['title'] = "Purchase (Offline)";
      $option = array('table' => ORDERS .' as o',
        'select' => 'o.id,u.full_name, u.phone, o.order_products,o.total_price,o.order_date,o.delivered_on,o.order_priority,o.estimated_delivery_date,o.status',
        'join' => array(USERS.' as u'=>'u.id=o.user_id'),
        'where' => array('o.order_via'=>'Shop'),
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
        'select' => 'o.id,u.full_name,u.email, u.phone, o.order_products,o.total_price,o.order_date,o.delivered_on,o.order_priority,o.estimated_delivery_date,o.status,ord_meta.product_id,ord_meta.product_price,ord_meta.product_qty,ord_meta.product_total,product.product_name',
        'join' => array(array(USERS.' as u'=>'u.id=o.user_id'),
                  array(ORDER_META. ' as ord_meta' =>'ord_meta.order_id=o.id'),
                  array(PRODUCTS.' as product'=>'product.id=ord_meta.product_id')),
        'where' => array('o.id'=>$order_id),
        
    );

      $this->data['order'] = $this->common_model->customGet($option);
      
   $option =array(
      'table' => ORDER_META.' as ord_meta',
      'select'=> 'ord_meta.product_id,ord_meta.product_qty,product.product_name,ord_meta.product_price',
      'join' => array(PRODUCTS .' as product'=>'product.id=ord_meta.product_id'),
      'where'=> array('ord_meta.order_id'=>$order_id)
    );
     $this->data['product'] = $this->common_model->customGet($option);

      $this->load->admin_render('view', $this->data,'inner_script');
    }
	
	public function userSuggest(){
	  $key = $this->input->post('key');
	  
	  $result = $this->common_model->customQuery('select * from users where full_name LIKE \'%'.$key.'%\'');
	  $response = [];
	  if(!empty($result))
	  {
		  foreach($result as $i=>$v)
		  {
		    array_push($response,['id'=>$v->id,'email'=>$v->email,'phone'=>$v->phone,'value'=>$v->full_name,'label'=>$v->full_name.' | '.$v->phone.' | '.$v->email]);	  
		  }
	  }
	  echo json_encode($response); die();
		
	}
    public function productSuggest(){
	  $key = $this->input->post('key');
	  
	  $result = $this->common_model->customQuery('select id,product_name,price from products where product_name LIKE \'%'.$key.'%\'');
	  $response = [];
	  if(!empty($result))
	  {
		  foreach($result as $i=>$v)
		  {
		    array_push($response,['id'=>$v->id,'value'=>$v->product_name,'price'=>$v->price]);	  
		  }
	  }
	  echo json_encode($response); die();
		
	}
	
	public function hierarchicalRenderer($list,$level=0)
    { $element = [];
      if($list=='')
      {
       return false;
     }
     else{
      foreach($list as $i=>$v)
      { 
       $temp = clone $v;
       if($level>0)
         { $levelShower = '';
       for($i=0;$i<$level;$i++)
       {
         $levelShower .= '- ';

       }
       $temp->category_name = $levelShower.''.$temp->category_name;
     }  
     unset($temp->childern);
     $this->element[] = $temp;
     if(isset($v->childern))
     { 
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
    public function product_add() {
    
      $this->form_validation->set_rules('product_name', lang('product_name'), 'required|trim');
      $this->form_validation->set_rules('category_id', lang('select_category'), 'required|trim');
      $this->form_validation->set_rules('price', lang('price'), 'required|trim');

      if ($this->form_validation->run() == true) {


        $this->filedata['status'] = 1;
        $image = "";
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'product', 'image');
          if ($this->filedata['status'] == 1) {
           $image = $this->filedata['upload_data']['file_name'];

         }

       }

       if ($this->filedata['status'] == 0) {
         $response = array('status' => 0, 'message' => $this->filedata['error']);  
       }else{

        $category_id = $this->input->post('category_id');
        $product_name = $this->input->post('product_name');
        $price = $this->input->post('price');
        $description = $this->input->post('description');
        $category_id   = $this->security->xss_clean($category_id);
        $product_name = $this->security->xss_clean($product_name);
        $price   = $this->security->xss_clean($price);
        $description = $this->security->xss_clean($description);
        
        
         $option = array('table' => PRODUCTS,
           'select' => 'product_name',
           'where' => array('product_name'=> $product_name)
        );

        $category = $this->common_model->customGet($option);
        if(empty($category))
       {
        $options_data = array(

          'product_name'  => $product_name,
          'price'         => $price,
          'description'   => $description,
          'category_id'   => $category_id,
          'image'         => $image,
          'created_date'  => datetime(),
          'status'        => 1,
          );

        $option = array('table' => PRODUCTS, 'data' => $options_data);
        if ($this->common_model->customInsert($option)) {


          $response = array('status' => 1, 'message' => lang('product_success'), 'url' => base_url('product'));

        }else {
          $response = array('status' => 0, 'message' => lang('product_failed'));
        } 
      }else{
          $response = array('status' => 0, 'message' => lang('product_exist'));
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
    public function product_edit() {
      $this->data['title'] = lang("edit_product");
      $id = decoding($this->input->post('id'));
      if (!empty($id)) {

        $query = "select * from category_management where id NOT IN(select parent_id from category_management)";
       $this->data['category'] =  $this->common_model->customQuery($query);

        $option = array(
          'table' => PRODUCTS,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
          $this->load->view('edit', $this->data);
        } else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('product');
        }
      } else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('product');
      }
    }

    /**
     * @method cms_update
     * @description update dynamic rows
     * @return array
     */
	public function add_process(){
         $this->data['parent'] = "Purchase";
         $this->data['title'] = "Purchase (Offline)";
        $this->form_validation->set_rules('order_name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('order_email', 'Email', 'required|trim');
        
       if ($this->form_validation->run() == false) {
           $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
             $this->load->admin_render('add', $this->data,'inner_script');

       }else{
        $current_date = date('Y-m-d H:i:s',strtotime('-1 day'));
        $order_name = $this->input->post('order_name');
        $order_email = $this->input->post('order_email');
        $order_phone = $this->input->post('order_phone');
        $order_name_id = $this->input->post('order_name_id');
        $order_via = $this->input->post('order_via');
        $order_date = date('Y-m-d',strtotime($this->input->post('order_date')));
        $product_qty = $this->input->post('product_qty');
        $product_total   = $this->input->post('product_total');
        $product_price   = $this->input->post('product_price');
        $product_name   = $this->input->post('product_name');
        $product_id   = $this->input->post('product_id');
       if(empty($order_name_id))
       {

        $characters = 'abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ123456789';

         $string = '';

         for ($i = 0; $i < 9; $i++) {
          $string .= $characters[rand(0, strlen($characters) - 1)];
        }


           $options_data = array(

          'full_name'         => $order_name,
          'email'             => $order_email,
          'password'          => md5($string),
          'phone'             => $order_phone,
          'created_on'        => datetime(),
          'active'            =>  1,
          'email_verify'       => 1   
          );

        $option = array('table' => USERS, 'data' => $options_data);
        $order_name_id =  $this->common_model->customInsert($option);

        
        $from = FROM_EMAIL;
        $subject = "User Password";
        $data['content'] = "You are registered successfully and your login password is : " .$string;
        $data['user'] = ucwords($order_name);

        $message = $this->load->view('email_template', $data, true);

        $title = "User Password";

        /* send mail */
        $sent_mail = send_mail($message, $subject, $order_email, $from, $title);
       }else{
          $order_name_id = $this->input->post('order_name_id');
       }

         $total_product = count($this->input->post('product_name'));

           $total_price = array_sum($product_total);

           $option = array('table' => USER_MEMBERSHIP,
            'select' => '*',
            'where' => array('user_id'=>$order_name_id),
            'single' =>true
            );
           $list = $this->common_model->customGet($option);
           if(!empty($list))
          {
              $member_expiry = $list->subscription_expiry_date;

             if($member_expiry!='' && $member_expiry!='NULL'){
               if($current_date > $member_expiry)
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
              $order_priority = 'HIGH';
              $membership_type = 'PREMIUM';
            }else{
              $order_priority = 'NORMAL';
              $membership_type = 'BASIC';
            }
          }else{
            $membership_type = 'BASIC';
            $order_priority = 'NORMAL';
          }

          $options_data1 = array(

          'user_id'            => $order_name_id,
          'order_via'          => $order_via,
          'order_products'     => $total_product,
          'total_price'        => $total_price,
          'order_date'         => $order_date,
          'delivered_on'       => $order_date,
          'cash_payment_date'  => $order_date,
          'cash_payment'       => $total_price,
          'estimated_delivery_date'=> $order_date,
          'payment_via'        => 'cash', 
          'created_date'       => datetime(),
          'status'             => 1,
          'order_priority'     => $order_priority,
          'receive_type'       => 'TAKE AWAY' 
          );
        
        $option1 = array('table' => ORDERS, 'data' => $options_data1);
        $order =  $this->common_model->customInsert($option1);

        $reward_point = $this->wallet->calculate_wallet_point($membership_type,$order_name_id,$total_price);
        
        $this->wallet->add_wallet_log('CREDIT',$total_price,$order_date,$order,$order_name_id,'USER',$membership_type,"Earned ". $reward_point ." Points on Order No. ". $order ."");
        
       for($i=0;$i<count($product_id);$i++)
      {  
       $options_data = array(

          'order_id'        => $order,
          'product_id'      => $product_id[$i],
          'product_price'   => $product_price[$i],
          'product_qty'     => $product_qty[$i],
          'product_total'   => $product_total[$i],
          'status'          => 1,
          );
    
       $option = array('table' => 'order_meta', 'data' => $options_data);
        $this->common_model->customInsert($option);
      }
      
          $options = array(
                'table' => ORDERS . ' as order',
                'select' => 'order.id,users.device_id,users.device_type,users.device_token,users.badges,users.email,order.user_id',
                'join' => array(USERS . ' as users' => 'users.id=order.user_id'),
                'where' => array('order.id' => $order),
                'single' => true
            );
            $users_device = $this->common_model->customGet($options);
             if ($users_device->device_type == 'ANDROID') {
                    $user_badges = $users_device->badges + 1;
                    
                    $data_array = array(
                        'title' => "Reward Points",
                        'body' => "Earned ". $reward_point ." Points on Order No. ". $order ."",
                        'type' => 'Wallet',
                        'order_id' => $order,
                        'badges' => $user_badges,
                    );
                       $noti_data = array('body' => 
                        "Earned ". $reward_point ." Points on Order No. ". $order ."",'params' => $data_array);
                     $status = send_android_notification($noti_data, $users_device->device_token, $user_badges,$users_device->user_id);
                   
                        $user_notifications = array(
                            'type_id' => $order,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device->user_id,
                            'notification_type' => 'Wallet',
                            'title' => 'Reward Points',
                            'notification_parent_id' => 0,
                            'message' => "Earned ". $reward_point ." Points on Order No. ". $order ."",
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
                        'order_id' => $order
                    );
                    $status = send_ios_notification($users_device->device_token, "Earned ". $reward_point ." Points on Order No. ". $order ."", $params, $user_badges,$users_device->user_id);
                    
                        $user_notifications = array(
                            'type_id' => $order,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device->user_id,
                            'notification_type' => 'Wallet',
                            'title' => 'Reward Points',
                            'notification_parent_id' => 0,
                            'message' => "Earned ". $reward_point ." Points on Order No. ". $order ."",
                            'is_read' => 0,
                            'is_send' => 1,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        $this->common_model->insertData(USER_NOTIFICATION, $user_notifications);

                }
 

      if($order)
         {

           $this->session->set_flashdata('success', 'Product Ordered successfully');
           redirect('purchase/add');

        }else {
           $this->session->set_flashdata('error', 'Order failed');
           redirect('purchase/add');
        } 

     }   
     
    
	} 
    public function product_update() {

      $this->form_validation->set_rules('product_name', lang('product_name'), 'required|trim');
      $this->form_validation->set_rules('category_id', lang('select_category'), 'required|trim');
      $this->form_validation->set_rules('price', lang('price'), 'required|trim');


      $where_id = $this->input->post('id');
      if ($this->form_validation->run() == FALSE):
        $messages = (validation_errors()) ? validation_errors() : '';
      $response = array('status' => 0, 'message' => $messages);
      else:
        $this->filedata['status'] = 1;
      $image = $this->input->post('exists_image');

      if (!empty($_FILES['image']['name'])) {
        $this->filedata = $this->commonUploadImage($_POST, 'product', 'image');

        if ($this->filedata['status'] == 1) {
         $image = $this->filedata['upload_data']['file_name'];
         delete_file($this->input->post('exists_image'), FCPATH."uploads/product/");


       }

     }

     if ($this->filedata['status'] == 0) {
      $response = array('status' => 0, 'message' => $this->filedata['error']);  
    }else{
        $category_id = $this->input->post('category_id');
        $product_name = $this->input->post('product_name');
        $price = $this->input->post('price');
        $description = $this->input->post('description');
        $category_id   = $this->security->xss_clean($category_id);
        $product_name = $this->security->xss_clean($product_name);
        $price   = $this->security->xss_clean($price);
        $description = $this->security->xss_clean($description);

      
       $option = array('table' => PRODUCTS,
           'select' => 'product_name',
           'where' => array('id !='=>$where_id ,'product_name'=> $product_name)
        );

        $category = $this->common_model->customGet($option);
        if(empty($category))
       {
        $options_data = array(

        'product_name' => $product_name,
        'price'         => $price,
        'description'   =>  $description,
        'image'         => $image,
        'category_id'     => $category_id

        );
      $option = array(
        'table' => PRODUCTS,
        'data' => $options_data,
        'where' => array('id' => $where_id)
        );
      $update = $this->common_model->customUpdate($option);
      $response = array('status' => 1, 'message' => lang('product_success_update'), 'url' => base_url('product'));
    }  
   else{
      $response = array('status' => 0, 'message' => lang('product_exist'));
  }
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

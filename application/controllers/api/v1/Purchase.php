<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for cms
 * @package   CodeIgniter
 * @category  Controller
 * @author    Preeti Birle
 */
class Purchase extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }


    /**
     * Function Name: checkout
     * Description:   To checkout Products
     */

    function checkout_post()
    {

      $data = $this->input->post();

      $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {
       $eachArr = array();
       $user_id       =  extract_value($data, 'user_id', ''); 
       $json_product  = json_decode($_POST['product'],TRUE);
       $product_id    = array();
       $product_price = array();
       foreach ($json_product as $product) {
         array_push($product_id, $product['product_id']);
       }

       $id =implode(',',$product_id);
      
       foreach ($json_product as $price) {
         array_push($product_price, $price['price']);

       }

       $total = array_sum($product_price);

       /* To Get Premium Products */
       $special_products = $this->common_model->customQuery("SELECT `id`,`product_name`,`price` FROM `products`  WHERE `id` IN ($id) AND `product_type`=1");
    
       $options = array('table' => USER_MEMBERSHIP,
        'select' => '*',
        'where'=> array('user_id'=> $user_id),
        'single' => true

        );
       $membership = $this->common_model->customGet($options);
       $expired =0;
       if(!empty($membership)){
         $membership_type   = $membership->membership_type;
         $membership_expiry = $membership->subscription_expiry_date;
         $current_date      = date('Y-m-d H:i:s',strtotime('-1 day'));
         /* To Check Membership Expiry */
         if($membership_type == 'PREMIUM')
         {
          $order_priority = 'HIGH';
          if($current_date > $membership_expiry)
          {
           $expired =1;
         }
         else
         {
           $expired =0;
         }
       }
       else{
         $membership_type   ="BASIC";
         $membership_expiry ='';
         $order_priority = 'NORMAL';

       }
      }
        else{
         $membership_type ="BASIC";
         $membership_expiry ='';
         $order_priority = 'NORMAL';


       }
       /* To Get Removed Products */
     if(!empty($special_products))
     {
         
      if($expired ==1)
      {
       foreach($special_products as $removed)
       {
        $temp['product_id']       = null_checker($removed->id);
        $temp['product_name']     = null_checker($removed->product_name);
        $temp['price']            = null_checker($removed->price);

        $eachArr[] = $temp;
       
      }
      
    }
  }
     $cartAdd = array();
  $options = array('table' => CART_PRODUCT . ' as product',
    'select' => 'product.product_id,product.name,product.price,product.user_id',
    'where'=> array('product.user_id'=> $user_id,
      ),

    );

  /* To get Cart Add Products*/
  $get_product = $this->common_model->customGet($options);
  if(empty($get_product))
  {

    foreach ($json_product as $product) {
      $temp1['product_id'] = $product['product_id'];
      $temp1['name'] = $product['name'];
      $temp1['price'] = $product['price'];
      $temp1['qty'] = $product['qty'];
      $temp1['user_id'] = $user_id;

      $this->common_model->insertData(CART_PRODUCT,$temp1);

    }
      /* To Delete Removed products from table*/
     if(!empty($special_products)){
    if($expired ==1)
    {
       foreach($special_products as $removed)
       {
          $option = array(
           'table' => CART_PRODUCT,
           'where' => array('product_id' => $removed->id)
        );

       $this->common_model->customDelete($option);
       }
    }
  }


  }else{
    $options = array('table' => CART_PRODUCT . ' as product',
      'select' => '*',
      'where'=> array('product.user_id'=> $user_id,
        ),

      );

    $product_list = $this->common_model->customGet($options);
    if (count($product_list) > 0) {
      $option = array(
        'table' => CART_PRODUCT,
        'where' => array('user_id' => $user_id)
        );

      $this->common_model->customDelete($option);
    }

    foreach ($json_product as $product) {
      $temp1['product_id'] = $product['product_id'];
      $temp1['name'] = $product['name'];
      $temp1['price'] = $product['price'];
      $temp1['qty'] = $product['qty'];
      $temp1['user_id'] = $user_id;

      $this->common_model->insertData(CART_PRODUCT,$temp1);

    }
    if(!empty($special_products)){
    if($expired ==1)
    {
       foreach($special_products as $removed)
       {
          $option = array(
           'table' => CART_PRODUCT,
           'where' => array('product_id' => $removed->id)
        );

       $this->common_model->customDelete($option);
       }
    }
  }

  }

  $options = array('table' => CART_PRODUCT . ' as product',
    'select' => 'product.product_id,product.name,product.price,product.user_id,product.qty',
    'where'=> array('product.user_id'=> $user_id,
      ),
    );

  $cart_product = $this->common_model->customGet($options);
  /* To get Cart Add Products*/
  foreach($cart_product as $cart)
  {
    $temp2['product_id']       = null_checker($cart->product_id);
    $temp2['product_name']     = null_checker($cart->name);
    $temp2['price']            = null_checker($cart->price);
    $temp2['qty']              = null_checker($cart->qty);

    $cartAdd[] = $temp2;
  } 
  $option1 = array('table' => USERS,
    'select' => '*',
    'where'=> array('id'=> $user_id,
      ),
    'single' => true
    );

  $wallet = $this->common_model->customGet($option1); 
   /* To get Wallet Balance */
  if(!empty($wallet))
  {
    $wallet_balance = $wallet->current_wallet_balance;
    if(empty($wallet_balance) || ($wallet_balance==0) || ($wallet_balance=='null'))
    {
      $balance = 0;
    }else{
      $balance = $wallet_balance;
    }

  }else{
    $balance = 0;
  }

  $response['cart_add_product'] = $cartAdd;
  $response['remove_product']   = $eachArr;
  $response['order_priority']   = $order_priority;
  $response['user_type']        = $membership_type;
  $response['membership_expiry'] = $membership_expiry;
  $response['wallet_balance']    = $balance;
  $response['total_billing_amount'] = $total;

    echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'Cart Details found successfully']);

}
}

/**
     * Function Name: purchase
     * Description:   To Purchase Products
*/

public function purchase_post()
{
  $data = $this->input->post();

  $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric');
  $this->form_validation->set_rules('total_amount', 'Total Amount', 'trim|required');
  $this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required');
  $this->form_validation->set_rules('receive_type', 'Receive Type', 'trim|required');
  $this->form_validation->set_rules('order_priority', 'Order Priority', 'trim|required');
  if ($this->form_validation->run() == FALSE) {
    $error = $this->form_validation->rest_first_error_string();
    echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
  } else {
    $current_date   = date('Y-m-d');
   $user_id         =  extract_value($data, 'user_id', '');
   $total_amount    =  extract_value($data, 'total_amount', '');
   $order_priority  =  extract_value($data, 'order_priority', '');
   $receive_type    =  extract_value($data, 'receive_type', '');
   $payment_type    =  extract_value($data, 'payment_type', '');
   $wallet_point    =  extract_value($data, 'wallet_point', ''); 
   $shippping_address    =  extract_value($data, 'shippping_address', '');  
   $json_product    = json_decode($_POST['product'],TRUE);
   $current_date    = datetime();
      $product_total=array();
      $product_count=array();
      foreach ($json_product as $total) {
         array_push($product_total, $total['product_id']);

       }
         $total_products = count($product_total);
     

   $options_data1 = array(

    'order_via'         => 'app',
    'user_id'           => $user_id,
    'order_products'    => $total_products,
    'order_priority'    => $order_priority,
    'total_price'       => $total_amount,
    'estimated_delivery_date'=>date('Y-m-d',strtotime('+5 days')),
    'delivered_on'      => date('Y-m-d',strtotime('+10 days')),
    'payment_via'       => $payment_type,
    'shipping_address'  => $shippping_address,
    'receive_type'      => $receive_type, 
    'order_date'        => datetime(),
    'created_date'      => datetime(),
    'wallet_payment_date' => datetime(),
    'status'            => 2,
    );

   $option1 = array('table' => ORDERS, 'data' => $options_data1);
   $order =  $this->common_model->customInsert($option1);


   foreach ($json_product as $product) {
    $temp1['order_id']      = $order;
    $temp1['product_id']    = $product['product_id'];
    $temp1['product_price'] = $product['product_price'];
    $temp1['product_total'] = $product['product_price']*$product['product_qty'];
    $temp1['product_qty']   = $product['product_qty'];
    $temp1['status']        = 1;
                      

    $this->common_model->insertData(ORDER_META,$temp1);

  }

  if($payment_type=='wallet')
  {
     
  
     //  if($wallet_point>=$total_amount)
     //  {
     //    $this->wallet->add_wallet_log('DEBIT',$wallet_point,$current_date,$order,$user_id,'USER','');

     
     //   $Update['wallet_payment'] = $total_amount;
     //   $Update['payment_via'] = 'wallet';
     //   $Update['wallet_payment_date'] = datetime();

     //   $this->common_model->updateFields(ORDERS, $Update, array('user_id' => $user_id));

     // }
    // else if($wallet_point<=$total_amount)
     //{

       $this->wallet->add_wallet_log('DEBIT',$wallet_point,$current_date,$order,$user_id,'USER','',"Used ". $wallet_point ." Points on Order No. ". $order ."");
       /* add wallet history */

       $wallet_amount = $total_amount-$wallet_point;
     
       $Update['remaining_amount'] = $wallet_amount;
       $Update['wallet_payment'] = $wallet_point;
       $Update['payment_via'] = 'wallet';

       $this->common_model->updateFields(ORDERS, $Update, array('user_id' => $user_id,'id'=>$order));
    // }
   }
 
 else
 {

  $Update['payment_via']      = 'cash';
  $Update['remaining_amount'] = $total_amount;
  

  $this->common_model->updateFields(ORDERS, $Update, array('user_id' => $user_id,'id'=>$order));
} 

    $options = array('table' => ORDERS,
      'select' => '*',
      'where'=> array('user_id'=> $user_id,
       'id'=>$order
       ),
      'single'=>true
      );

    $user_order = $this->common_model->customGet($options);
    /* To Get Order Details */

$response['order_id'] = null_checker($order);
$response['estimated_delivery_date'] = null_checker(convertDate($user_order->estimated_delivery_date)); 

if($order)
{

  echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'Purchase Successful!']);

}
else
{
  echo $this->jsonresponse->geneate_response(1, 0,'Purchase unsuccessful!',[]);
}

}
}

/**
     * Function Name: order_history
     * Description:   To Get Order History
*/

public function order_history_post()
{
  $data = $this->input->post();

   $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric');
   $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

  if ($this->form_validation->run() == FALSE) {
    $error = $this->form_validation->rest_first_error_string();
    echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
  } else {

   $user_id   =  extract_value($data, 'user_id', '');
   $page_no    = extract_value($data,'page_no',1);  
   $offset     = get_offsets($page_no);
   $upload_url = base_url().'uploads/product/';

   $options = array('table' => ORDERS. ' as order',
     'select' => 'order.estimated_delivery_date,order.order_date,order.total_price,order.status,order.id',
     'where'=> array('order.user_id'=> $user_id),
     'order' => array('order.id' => 'DESC'),
     'limit' => array(10 => $offset)

     );

   $user_order = $this->common_model->customGet($options);
   if(!empty($user_order))
   {
    
    $eachArr = array();

    $total_requested = (int) $page_no * 10; 

    /* Get total records */  
    $total_records = getAllCount(ORDERS,array('user_id'=>$user_id));

    if($total_records > $total_requested){                      
      $has_next = TRUE;                    
    }else{                        
      $has_next = FALSE;                    
    }
    foreach($user_order as $order)
    {
     if($order->status==1)
     {
      $order_status = 'Delivered';
     }else if($order->status==2)
     {
       $order_status = 'In Process';
     }else{
      $order_status = 'Ready';
     }

    $temp['order_id']                = null_checker($order->id);
    $temp['estimated_delivery_date'] = null_checker(convertDate($order->estimated_delivery_date));
    $temp['order_date']              = null_checker(convertDate($order->order_date));
    $temp['order_status']            = $order_status;
    $temp['total_cost']              = null_checker($order->total_price);

     $eachArr[] = $temp;
  }

  echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Order History found successfully']);

}else{
  echo $this->jsonresponse->geneate_response(1, 0,'Order History not found',[]);
}


 }
}

 /**
     * Function Name: order_details
     * Description:   To Get Order Details
*/

  function order_details_post() {
        $data = $this->input->post();
      

      $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {
        
        $temp = array();
        $eachArr = array();
        $order_id   = extract_value($data, 'order_id', '');
        $upload_url = base_url().'uploads/product/';
       
         $options = array('table' => ORDERS. ' as order',
           'select' => 'order.estimated_delivery_date,order.order_date,order.total_price,order.status,order.id,order.remaining_amount,order.wallet_payment',
           'where'=> array('order.id'=> $order_id),
           'single'=>true

          );
        
        /* To get order list from orders table */
        $list = $this->common_model->customGet($options);
      
       if(!empty($list)) {
         $options= array('table' => ORDER_META.' as order_meta',
                  'select'=> 'order_meta.product_id,product.product_name,product.image,order_meta.product_qty,order_meta.product_price',
                  'join'=> array(PRODUCTS. ' as product'=>'product.id=order_meta.product_id'),
                  'where'=> array('order_meta.order_id'=>$order_id)
               
            );
           $order_meta = $this->common_model->customGet($options);
       
            if($list->status==1)
             {
              $order_status = 'Delivered';
             }else if($list->status==2)
             {
               $order_status = 'In Process';
             }else{
              $order_status = 'Ready';
             }

            $temp['order_id']               = null_checker($list->id);
            $temp['total_cost']             = null_checker($list->total_price);
            $temp['used_amount']            = null_checker($list->wallet_payment);
            $temp['remaining_amount']       = null_checker($list->remaining_amount);
            $temp['order_date']             = null_checker(convertDate($list->order_date));
            $temp['estimated_delivery_date'] = null_checker(convertDate($list->estimated_delivery_date));
            $temp['order_status']            = $order_status;
      
             foreach($order_meta as $order)
           {
             /* check for image empty or not */
             if(!empty($order->image))
             {
              $image = $upload_url.$order->image;
             }
            else
            {
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
             $temp1['product_id']        = null_checker($order->product_id);
             $temp1['product_name']      = null_checker($order->product_name);
             $temp1['product_qty']       = null_checker($order->product_qty);
             $temp1['product_price']     = null_checker($order->product_price);
             $temp1['product_image']     = $image;
             $eachArr[]=$temp1;
            
          }
          $temp['ordered_products'] = $eachArr;
            /* return success response*/

            echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$temp,'message'=>'Order Details found successfully']);
            
          }else {
           echo $this->jsonresponse->geneate_response(1, 0,'Order Details not found',[]);
         }
       }
       
     }

  /**
     * Function Name: wallet_history
     * Description:   To Get Wallet History
 */

  public function wallet_history_post()
 {
    $data = $this->input->post();

   $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric');
   $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

  if ($this->form_validation->run() == FALSE) {
    $error = $this->form_validation->rest_first_error_string();
    echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
  } else {
   $eachArr = array();
   $used_point =array();
   $earned_point =array();
   $response =array();
   $user_id   =  extract_value($data, 'user_id', '');
   $page_no    = extract_value($data,'page_no',1);  
   $offset     = get_offsets($page_no);
   $upload_url = base_url().'uploads/product/';

    $options = array('table' => USERS,
     'select'=> '*',
     'where' => array('id'=> $user_id),
     'single' => true
     );

   $user = $this->common_model->customGet($options);
   if(!empty($user))
   {
     $wallet_balance = $user->current_wallet_balance;
    if(empty($wallet_balance) || ($wallet_balance==0) || ($wallet_balance=='null'))
    {
      $total_balance = 0;
    }else{
      $total_balance = $wallet_balance;
    }

    $options = array('table' => WALLET,
     'select'=> '*',
     'where' => array('user_id'=> $user_id,'transaction_type'=>'CREDIT'),
     );

    $wallet_history_earned = $this->common_model->customGet($options);

    if(!empty($wallet_history_earned)){
    foreach($wallet_history_earned as $earned)
    {
       array_push($earned_point, $earned->amount);
      
    }
     $total_earned = array_sum($earned_point);
     
    }else{
      $total_earned = 0;
    }
    $options = array('table' => WALLET,
     'select'=> '*',
     'where' => array('user_id'=> $user_id,'transaction_type'=>'DEBIT'),
     );

    $wallet_history_used = $this->common_model->customGet($options);
    if(!empty($wallet_history_used))
   {
    foreach($wallet_history_used as $used)
    {
       array_push($used_point, $used->amount);
      
    }
    $total_used = array_sum($used_point);
   }else{
    $total_used = 0;
   }
    $options = array('table' => WALLET,
     'select'=> '*',
     'where' => array('user_id'=> $user_id),
     'order' => array('id' => 'DESC'),
     'limit' => array(10 => $offset)
     );

    $wallet_history = $this->common_model->customGet($options);
 
    $total_requested = (int) $page_no * 10; 
    /* Get total records */  
    $total_records = getAllCount(WALLET,array('user_id'=>$user_id));
  
    if($total_records > $total_requested){                      
      $has_next = TRUE;                    
    }else{                        
      $has_next = FALSE;                    
    }
    foreach($wallet_history as $history)
    {
   
    $temp['order_id']           = null_checker($history->order_id);
    $temp['date']               = null_checker(convertDate($history->date));
    $temp['transaction_type']   = null_checker($history->transaction_type);
    $temp['description']        = null_checker($history->description);
    $temp['amount']             = null_checker($history->amount);
    $eachArr[] = $temp;
  }
    $response['wallet_history']     = $eachArr;
    $response['total_earned_point'] = $total_earned;
    $response['total_used_point']   = $total_used;
    $response['total_available_balance']   = $total_balance;
  
  echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'has_next'=>$has_next,'message'=>'Wallet History found successfully']);

}else{
  echo $this->jsonresponse->geneate_response(1, 0,'User not found',[]);
}


 }
}
  
  

}


/* End of file Category.php */
/* Location: ./application/controllers/api/v1/Category.php */
?>
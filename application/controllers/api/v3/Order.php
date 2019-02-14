<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for category
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun Choudhary
 */
class Order extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }


    /**
     * Function Name: exclusive_offer_list
     * Description:   To Get all exclusive offer
    */

    function order_post() {
    	$data = $this->input->post();

      	$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
      	$this->form_validation->set_rules('type', 'Type', 'trim|required');
        if($_POST['type'] == 2):
        $this->form_validation->set_rules('delivery_date', 'Delivery date', 'trim|required');
        $this->form_validation->set_rules('delivery_time', 'Delivery time', 'trim|required');
        endif;
        $this->form_validation->set_rules('total_amount', 'Total amount', 'trim|required|numeric');
        $this->form_validation->set_rules('net_amount', 'Net amount', 'trim|required|numeric');
        $this->form_validation->set_rules('gst', 'GST amount', 'trim|required|numeric');
      	// $this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required');
      	$this->form_validation->set_rules('cart_detail', 'Cart Data', 'trim|required');

      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	} else {
      		  $response = array();
          	$user_id = extract_value($data,'user_id',''); 
            is_deactive($user_id);
            $delivery_date = extract_value($data,'delivery_date',''); 
            $delivery_time = extract_value($data,'delivery_time','');
            //check delivery datetime is greater than current datetime
            // $bool = check_current_datetime($delivery_date,$delivery_time);
            // if($bool == 0){
            //   echo $this->jsonresponse->geneate_response(1, 0,'Delivery date is less than current date');die;
            // }else if($bool == -1){
            //   echo $this->jsonresponse->geneate_response(1, 0,'Delivery time is less than current time');die;
            // }

            $OrderArr['delivery_date']    = date('Y-m-d',strtotime($delivery_date));  
            $OrderArr['delivery_time']    = date('H:i:s',strtotime($delivery_time));
            
            //for foodparcel
            if($_POST['type'] == 2):
              $cdt  = date('Y-m-d H:i:s');
              $dldt = date('Y-m-d H:i:s',strtotime("$delivery_date $delivery_time"));

              $start = strtotime("$dldt");
              $end   = strtotime("$cdt");
              $diff = ($end-$start)/3600;
              $diff = number_format((float)$diff, 2, '.', '');
              if($diff < 1){
                $OrderArr['delivery_time'] = strtotime($delivery_time) + 60*60;  
                $OrderArr['delivery_time'] = date('H:i:s',$OrderArr['delivery_time']);  
              }
              
            	$delivery_address_id = extract_value($data,'delivery_address_id',''); 
            	$delivery_address = extract_value($data,'delivery_address',''); 
            	if($delivery_address_id == ''){
            		$insertAddrss = array('user_id'=>$user_id,'address'=>$delivery_address);
            		$opt = array(
            			'table'	=> USERADDRESS,
            			'data'	=> $insertAddrss
            		);
            		$address = $this->common_model->customInsert($opt);
            	}else{
            		$address = $delivery_address_id;	
            	}
              $OrderArr['address_id']     = $address;   
            endif;


          	$OrderArr['user_id'] 		      = $user_id; 
          	$OrderArr['total_amount'] 	  = extract_value($data,'total_amount',''); 
          	$OrderArr['gst'] 			        = extract_value($data,'gst',''); 
            $OrderArr['net_amount']       = extract_value($data,'net_amount','');
            $OrderArr['paid_amount']      = extract_value($data,'paid_amount','');  
            $OrderArr['pending_amount']   = extract_value($data,'remaining_amount','');  
            $OrderArr['partial_payment']  = extract_value($data,'partial_payment','');  
            $OrderArr['type']             = extract_value($data,'type',''); 
            $is_wallet                    = extract_value($data,'is_wallet',''); 
          	$wallet_amount 			          = extract_value($data,'wallet_amount',''); 
            $OrderArr['created']          = date('Y-m-d H:i:s');
            $OrderArr['unique_order_id']  = getUniqueNumber();
            if($is_wallet == 1):
              $OrderArr['wallet_amount']  = $wallet_amount; 
              endif;
            // 1 - Ala Cart, 2 - Food Parcel, 3 - Party Package
            
            if($data['type'] == 1){
              
              $OrderArr['redeemption_code'] = generateRandomString(13);
              $response['redeemption_code'] = $OrderArr['redeemption_code'];
              
              //payment type :- 1 - COD , 2 - Online
              
              //if($data['payment_type'] == 2 && $data['payment_id'] != ''){
              if($data['payment_type'] == 2){
                $OrderArr['payment_id']     = $data['payment_id'];
                $OrderArr['payment_type']   = 2;  //Online
                $OrderArr['status']         = 4;  //Complete
                $OrderArr['payment_status'] = 1;  //Done

                $message = "Congratulation! Your order is placed successfully.Your redeemption code is ".$OrderArr['redeemption_code'];

              }else{
                $OrderArr['payment_type'] = 1;  //Pay at outlet
                $OrderArr['status']       = 2;  //Confirm

                $message = "Congratulation! Your order is confirm.Your redeemption code is ".$OrderArr['redeemption_code'];
              }

            }else if($data['type'] == 2){

              $OrderArr['status']       = 1;  //Pending
              // $OrderArr['payment_type'] = 1;  //COD
              $response['order_id']     = $OrderArr['unique_order_id'];

            }else if($data['type'] == 3){
              
              //if($data['payment_type'] == 2 && $data['payment_id'] != ''){
              if($data['payment_type'] == 2){
                
                $OrderArr['payment_id']     = $data['payment_id'];
                $OrderArr['payment_type']   = 2;  //Online
                
                if($data['is_fullpayment'] == 1){
                  $OrderArr['status']         = 4;  //Complete
                  $OrderArr['payment_status'] = 1;  //Done
                  $OrderArr['discount']       = $data['discount']; //On Full Payment(%) 
                  $OrderArr['is_fullpayment'] = $data['is_fullpayment']; 
                  $OrderArr['redeemption_code'] = generateRandomString(13);
                  $response['redeemption_code'] = $OrderArr['redeemption_code'];

                  $message = "Congratulation! Your order is placed successfully.Your redeemption code is ".$OrderArr['redeemption_code'];

                }else{
                  $OrderArr['payment_status'] = 1;  //Done
                  $OrderArr['status']         = 3;  //In- Process
                  $response['remain_amount']  = $OrderArr['pending_amount'];
                  $OrderArr['redeemption_code'] = generateRandomString(13);
                  $response['redeemption_code'] = $OrderArr['redeemption_code'];

                  $message = "Congratulation! Your order is in progress.Your redeemption code is ".$OrderArr['redeemption_code'];     
                }

              }

            }

          	$opts = array('table'=>ORDER,'data'=>$OrderArr);
          	$order = $this->common_model->customInsert($opts);
          	if($order){
              if($is_wallet == 1){
                
                // get user wallet amount
                $user_wallet_amount = $this->common_model->user_wallet_amount($user_id);
                
                //update user wallet
                $final_wallet_amount = $user_wallet_amount - $wallet_amount;
                $this->db->where('user_id',$user_id);
                $this->db->update(USERWALLET,array('amount'=>$final_wallet_amount));

                //insert into wallet(detail) table
                $desc = "User use ".$wallet_amount." for payment.";
                $wallet_detail = array(
                  'user_id'=>$user_id,
                  'order_id'=>$order,
                  'transaction_type'=>'DEBIT',
                  'description'=>$desc,
                  'amount'=>$wallet_amount,
                  'transcation_user_type'=>1,
                  'date'=>date('Y-m-d H:i:s'),
                );
                $opt_wallet = array(
                  'table'=>WALLET,
                  'data'=>$wallet_detail
                );
                $wallet_id = $this->common_model->customInsert($opt_wallet);
                
                if($wallet_id):
                  $trans_type = 'debit';
                $this->common_model->send_wallet_notification($order,$user_id,$data['type'],$wallet_amount,$trans_type);
                endif;
              }



              $male      = extract_value($data,'male',''); 
              $female    = extract_value($data,'female',''); 
              $children  = extract_value($data,'children','');
              $min_age   = extract_value($data,'min_age','');
              $max_age   = extract_value($data,'max_age','');
              $cart_data = extract_value($data,'cart_detail','');
          		$cart_data = json_decode($cart_data);
          		$batchArr  = array();
          		foreach($cart_data as $cart){
          			$tempimagArr = explode('/uploads/',$cart->item_image);
                $itemimage  = 'uploads/';
                $itemimage .= end($tempimagArr);
                
                // What price comes from json data is single item price in all 3 case
                // We have to multiply by Qty to get actual price
                
                // $price = $cart->qty * $cart->price;
                
                $batchArr = array(
          				'item_id'	  => $cart->item_id,
                  'item_name' => $cart->item_name,
                  'item_image'=> $itemimage,
          				'quanity'	  => $cart->qty,
          				'price'		  => $cart->price, //This is a single item price in all 3 cases
                  'order_id'  => $order,
          				'created'	  => date('Y-m-d H:i:s')
          			);
                if($data['type'] == 1){
                  $batchArr['male']     = $male;
                  $batchArr['female']   = $female;
                }else if($data['type'] == 3){
                  $batchArr['male']     = $male;
                  $batchArr['female']   = $female;
                  $batchArr['children'] = $children;
                  $batchArr['min_age']  = $min_age;
                  $batchArr['max_age']  = $max_age;
                  $batchArr['party_data']  = extract_value($data,'party_data','');
                }
                
                $insertopts = array('table'=>CART,'data'=>$batchArr);
                $this->common_model->customInsert($insertopts);
                $batchArr = array();
                $itemimage = '';
          		}

              if($data['type'] == 1){
                //send redeemption code on mobile and email
                send_rdumption($user_id, $response['redeemption_code']);
              }
          		

              //send push notification
              
              // $noti_type = 'Order';
              // $title     = 'Order';
              // $params = array('order_id'=>$order,'type'=>$data['type'],'status'=>$OrderArr['status'],'noti_type'=>$noti_type);
              // if($data['type'] == 1 || $data['type'] == 3){
              //   $res = save_user_notification($order,$user_id,$noti_type,$data['type'],$message,$title);
              //   if($res):
              //     send_push_notifications($message,$user_id,$params);
              //   endif;               
              // }
              
              // notification end

          		echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'Order add successfully']);
          	}else{
          		echo $this->jsonresponse->geneate_response(1, 0,'',['response'=>$response,'message'=>'Order insertion failed']);
          	}	
        }
    }

    function place_order_post(){
    	$data = $this->input->post();

      	$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
      	$this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric');
      	$this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required');
      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	}else{
          
      		$user_id = extract_value($data,'user_id',''); 
          is_deactive($user_id);
      		$order_id = extract_value($data,'order_id',''); 
          $payment_type = extract_value($data,'payment_type',''); 
          $payment_id = extract_value($data,'payment_id',''); 
          $paid_amount = extract_value($data,'paid_amount','');
          $is_wallet = extract_value($data,'is_wallet','');
          $wallet_amount = extract_value($data,'wallet_amount','');
          $remaining_amount = extract_value($data,'remaining_amount','');

      		$redeemption_code = generateRandomString(13);
          
          $updateArr = array('redeemption_code'=>$redeemption_code);
          if($payment_type == 1){
            $updateArr['payment_type'] = 1;
            $updateArr['status'] = 3;
            $updateArr['pending_amount'] = $remaining_amount;
            $updateArr['paid_amount'] = $paid_amount;
          // }else if($payment_type == 2 && $payment_id != '' && $paid_amount != ''){
      		}else if($payment_type == 2 && $paid_amount != ''){
            $updateArr['pending_amount'] = $remaining_amount;
            $updateArr['paid_amount']    = $paid_amount;
            $updateArr['payment_id']     = $payment_id;
            $updateArr['payment_type']   = 2;
            $updateArr['status']         = 4;
            $updateArr['payment_status'] = 1;
          }

          if($is_wallet == 1):
            $updateArr['wallet_amount'] = $wallet_amount; 
          endif; 


          $op = array(
            'table'=>ORDER,
            'data'=>$updateArr,
            'where'=>array('unique_order_id'=>$order_id,'user_id'=>$user_id)
          );
          $update = $this->common_model->customUpdate($op);
      		
          $response = array();
      		
          if($update){
      			$orderdata = $this->db->get_where(ORDER,array('user_id'=>$user_id,'unique_order_id'=>$order_id))->row_array();
            $response['redeemption_code'] = $orderdata['redeemption_code'];
      			$order_id = $orderdata['id'];
            
            //send redeemption code 
            send_rdumption($user_id, $response['redeemption_code']);

            if($is_wallet == 1){
                
                // get user wallet amount
                $user_wallet_amount = $this->common_model->user_wallet_amount($user_id);
                
                //update user wallet
                $final_wallet_amount = $user_wallet_amount - $wallet_amount;
                $this->db->where('user_id',$user_id);
                $this->db->update(USERWALLET,array('amount'=>$final_wallet_amount));

                //insert into wallet(detail) table
                $desc = "User use ".$wallet_amount." for payment.";
                $wallet_detail = array(
                  'user_id'=>$user_id,
                  'order_id'=>$order_id,
                  'transaction_type'=>'DEBIT',
                  'description'=>$desc,
                  'amount'=>$wallet_amount,
                  'transcation_user_type'=>1,
                  'date'=>date('Y-m-d H:i:s'),
                );
                $opt_wallet = array(
                  'table'=>WALLET,
                  'data'=>$wallet_detail
                );
                $wallet_id = $this->common_model->customInsert($opt_wallet);
                
                if($wallet_id):
                  $trans_type = 'debit';
                  $typ = 2;
                $this->common_model->send_wallet_notification($order_id,$user_id,$typ,$wallet_amount,$trans_type);
                endif;
              }

            //send push notification
            // $noti_type = 'Order';
            // $type = 2;
            // $title = 'Order';
            // $params = array('order_id'=>$order_id,'type'=>$type,'status'=>$updateArr['status'],'noti_type'=>$noti_type);
            // if($updateArr['status'] == 3){
            //   $message = "Congratulation! Your order is in progress.Your redeemption code is ".$redeemption_code;
            // }else if($updateArr['status'] == 4){
            //   $message = "Congratulation! Your order is placed successfully.Your redeemption code is ".$redeemption_code;
            // }
            

            // $res = save_user_notification($order_id,$user_id,$noti_type,$type,$message,$title);
            // if($res):
            //   send_push_notifications($message,$user_id,$params);
            // endif;
            // notification end

      			echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'Your booking has been confirmed']);
      		}else{
      			echo $this->jsonresponse->geneate_response(1, 0,'',['response'=>$response,'message'=>'booking failed']);
      		}

      	}
    }

    public function order_history_post(){
      $data = $this->input->post();

      $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
      $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      }else{
        $date   = '';
        $time   = '';
        $status = '';
        $where  = '';
        $user_id = extract_value($data,'user_id','');

        is_deactive($user_id);

        $start_date = extract_value($data,'start_date','');
        $end_date = extract_value($data,'end_date','');
        $status = extract_value($data,'status','');
        $time = extract_value($data,'time','');
        $page_no = extract_value($data,'page_no',1);
        $offset     = get_offsets($page_no);



        // if($start_date != '' && $end_date != ''){
          if($start_date != '' && $end_date == ''){
            $date = 'AND o.delivery_date >= "'.$start_date.'"';
          }else if($start_date == '' && $end_date != ''){
            $date = 'AND o.delivery_date <= "'.$end_date.'"';
          }else if($start_date != '' && $end_date != ''){
            $date = 'AND o.delivery_date BETWEEN "'.$start_date.'" AND "'.$end_date.'"';
          }
        // }

        if($time != ''){
          $time = 'AND o.delivery_time >="'.$time.'"';
        }

        if($status != ''){
          $status = 'AND o.status ="'.$status.'"';
        }else{
          $status = 'AND o.status != 1';
        }

        /* To get order list from order table */
        
        $where = ''.$date.' '.$time.' '.$status.'';
        
        $sql = "SELECT `o`.`id`, `o`.`net_amount`, `o`.`unique_order_id`,`o`.`redeemption_code`, `o`.`status`, `o`.`delivery_date`, `o`.`delivery_time`,`o`.`type`, COUNT(c.id) AS qty_count
                FROM `order` AS `o`
                JOIN `cart` AS `c` ON `c`.`order_id` = `o`.`id`
                WHERE `o`.`user_id` = $user_id $where
                GROUP BY `c`.`order_id`
                ORDER BY `o`.`id` DESC
                LIMIT $offset,10";
        $list = $this->common_model->customQuery($sql);        
        

        /* check for image empty or not */

        if (!empty($list)) {

            $eachArr = array();

            $total_requested = (int) $page_no * 10; 

            /* Get total records */  
            // $total_records = getAllCount(ORDER,array('user_id'=>$user_id));
            $total_records = 0;
            $sqlCount = "SELECT `o`.`id`, `o`.`net_amount`, `o`.`unique_order_id`,`o`.`redeemption_code`, `o`.`status`, `o`.`delivery_date`, `o`.`delivery_time`,`o`.`type`, COUNT(c.id) AS qty_count
                FROM `order` AS `o`
                JOIN `cart` AS `c` ON `c`.`order_id` = `o`.`id`
                WHERE `o`.`user_id` = $user_id $where
                GROUP BY `c`.`order_id`";

            $listCount = $this->common_model->customQuery($sqlCount);            
            if(!empty($listCount)):
              $total_records =  count($listCount);             
              endif;

            if($total_records > $total_requested){                      
              $has_next = TRUE;                    
            }else{                        
              $has_next = FALSE;                    
            }

            foreach ($list as $rows):
              $temp['id']               = (int)null_checker($rows->id);
              $temp['order_no']         = null_checker($rows->unique_order_id);
              $temp['redeemption_code'] = null_checker($rows->redeemption_code);
              $temp['qty']              = null_checker($rows->qty_count);
              $temp['net_amount']       = (int) null_checker($rows->net_amount);
              $temp['status']           = null_checker($rows->status);
              $temp['delivery_date']    = null_checker(convertDate($rows->delivery_date));
              $temp['delivery_time']    = $rows->delivery_time;
              $temp['type']             = (int)$rows->type;

              $eachArr[] = $temp;
            endforeach;
            /* return success response*/

            echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Order found successfully']);
      }else{
        echo $this->jsonresponse->geneate_response(1, 0,'No order found',[]);
      }
    }
  }

  public function order_detail_post(){
    $data = $this->input->post();
    $return['code'] = 200;
    // $return['response'] = new stdClass();
    $data = $this->input->post();

      $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
      $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric');
      $this->form_validation->set_rules('type', 'Type', 'trim|required');
      
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      }else{
        $user_id = extract_value($data,'user_id','');

        is_deactive($user_id);
         
        $order_id = extract_value($data,'order_id',''); 
        $type = extract_value($data,'type',''); 
        $option = array(
          'table'=>ORDER. ' AS o',
          'select'=>'o.id,o.user_id,o.address_id,o.delivery_date,o.delivery_time,o.gst,o.total_amount,o.net_amount,o.partial_payment,o.pending_amount,o.paid_amount,o.payment_type,o.status,o.type,o.redeemption_code,o.wallet_amount',
          'where'=>array('id'=>$order_id,'user_id'=>$user_id),
          'order'=>array('o.id' =>'DESC'),
          'single'=>true
        );
        $orderdata = $this->common_model->customGet($option); 

        if(!empty($orderdata)){
          $response['order'] = $orderdata;
          $address = '';
          $response['order']->address = $address;
            
          $options = array(
            'table'=>CART,
            'select'=>'item_id,item_name,item_image,price,quanity',
            'where'=>array('order_id'=>$order_id)
          );
          $cartdata = $this->common_model->customGet($options); 
          
          $cdata = array();
          $partyItem = array();
          $partyData = array();
          if(!empty($cartdata)){
            $parceldata = array(); 
            foreach ($cartdata as $value) {
              $image = base_url().$value->item_image;
              $value->item_image = $image;
              
              //Food Parcel
              if($type == 2):
                
                //for delivery address
                $addressdata = $this->db->get_where(USERADDRESS,array('id'=>$orderdata->address_id))->row_array();
                $address = $addressdata['address'];
                $response['order']->address = $address;

                //for parcel items details
                $opt = array(
                  'table'=>ALLACART.' AS a',
                  'select'=>'a.item_name,pi.item_limit',
                  'join'=>array(PARCELITEMS.' AS pi'=>'pi.item_id = a.id'),
                  'where'=>array('parcel_id'=>$value->item_id)
                );
                $parceldata = $this->common_model->customGet($opt);
              endif;
              $value->parcel_item = $parceldata;
              
              //party package
              if($type == 3):
                $opt = array(
                  'table'=>PACKAGECATEGORY.' AS pc',
                  'select'=>'pc.items_id,pc.item_limit',
                  'where'=>array('package_id'=>$value->item_id)
                );
                $partydata = $this->common_model->customGet($opt);
                if(!empty($partydata)){
                  foreach ($partydata as $val) {
                    $limit = $val->item_limit;
                    $itemsstr = $val->items_id;
                    $sql = "SELECT item_name FROM ".ALLACART." WHERE id IN (".$itemsstr.")";
                    $query = $this->db->query($sql);
                    $item_data = $query->result_array();
                    $partyItem['limit'] = $limit;
                    $partyItem['packagedata'] = array_column($item_data, 'item_name');
                    $partyData[] = $partyItem;
                    $partyItem = array();
                  }
                }
              endif;
              $value->parcel_item = $partyData;
              $cdata[] = $value;
            }
          }    
          $response['order']->cartdetail = $cdata;
          echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'Order found successfully']); 
          
        }else{
          echo $this->jsonresponse->geneate_response(1, 0,'Order not found',[]);
        }

      } 
  }

  public function test_post()
  {
    $data = $this->input->post();
    $user_id = extract_value($data,'user_id',''); 
    $message = "hello";
    $params = array('order_id'=>1,'type'=>1,'status'=>4,'noti_type'=>1);
    send_push_notifications($message,$user_id,$params);

  }

}


   /* End of file Order.php */
   /* Location: ./application/controllers/api/v1/Order.php */
   ?>
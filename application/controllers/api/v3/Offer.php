<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for category
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun Choudhary
 */
class Offer extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }


    /**
     * Function Name: exclusive_offer_list
     * Description: To Get all exclusive offer
     * NOTE:- This offer are those which show on home screen and section wise as banner.
     *        comes from offers table. 
    */

    function exclusive_offer_list_post() {
    	$data = $this->input->post();

      	$this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
        // $this->form_validation->set_rules('type', 'Type', 'trim|required');
      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	} else {

          $type         = extract_value($data,'type','');  
        	$page_no      = extract_value($data,'page_no',1);  
        	$offset       = get_offsets($page_no);
      
        	$upload_url   = base_url().'uploads/offer/';
        	$current_date = datetime('Y-m-d');

        	$options = array(
        		'table'  => OFFERS,
          	'select' => '*',
            'where'  => array('status'=>1),
          	'order'  => array('id' => 'desc'),
          	'limit'  => array(10 => $offset)
          );
          if($type != 'all' && $type != ''):
            $options['where']['type'] = $type;
          endif;
        	/* To get offer list from offer table */
        	$list = $this->common_model->customGet($options);
         
        	/* check for image empty or not */

        	if (!empty($list)) {

          		$eachArr = array();

          		$total_requested = (int) $page_no * 10; 

          		/* Get total records */  
          		$total_records = getAllCount(OFFERS,array('status'=>1));

          		if($total_records > $total_requested){                      
            		$has_next = TRUE;                    
          		}else{                        
            		$has_next = FALSE;                    
          		}

          		foreach ($list as $rows):
            		if(!empty($rows->offer_image)){
              			$image = $upload_url.$rows->offer_image;
            		} else{
              			/* set default image if empty */
              			$image = base_url().DEFAULT_NO_IMG_PATH;
            		}
		            $temp['id']             = null_checker($rows->id);
		            $temp['offer_text']     = null_checker($rows->offer_text);
		            $temp['type']      		= null_checker($rows->type);
		            $temp['status']         = ($rows->status == 1) ? 'active' : 'inactive';
		            $temp['show_front']     = ($rows->show_front == 1) ? 'yes' : 'no';
		            $temp['created_date']   = null_checker(convertDate($rows->created_date));
		            $temp['image']       	= $image;

            		$eachArr[] = $temp;
            	endforeach;
            	/* return success response*/

            	echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Exclusive Offer found successfully']);

          	}else {
           		echo $this->jsonresponse->geneate_response(1, 0,'Exclusive Offer not found',[]);
         	}
       	}

    }


    /**
     * Function Name: offer_list
     * Description: To Get all offer
     * NOTE:- This offer are those which apply by date and time and quantity.
     *        comes from item_dates,item_dates_day,item_dates_day tables.
    */

   function offer_list_post() {
      $data = $this->input->post();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

        if ($this->form_validation->run() == FALSE) {
          $error = $this->form_validation->rest_first_error_string();
          echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {
          
          $page_no    = extract_value($data,'page_no',1);  
          $type       = extract_value($data,'type','');  
          $offset     = get_offsets($page_no);
          $cdate      = date('Y-m-d H:i:s');

          $finalArr = array(); 

          $option = array(
            'table'   => ITEMDATES.' AS idt',
            'select'  => 'id,offer_title,min_qty,start_date,end_date,idt.item_id,idt.type',
            'where'   => array('idt.end_date >= '=>date('Y-m-d'))
          );
          $typstr = '';
          if($type == 1){
            $option['where']['type'] = 'allacart';
            $typstr = 'allacart';
          }elseif ($type == 2) {
            $option['where']['type'] = 'foodparcel';
            $typstr = 'foodparcel';
          }elseif($type == 3){
            $option['where']['type'] = 'partypackage';
            $typstr = 'partypackage';
          }

          $detail_data = $this->common_model->customGet($option);
          
          if (!empty($detail_data)) {
            $eachArr = array();

            $total_requested = (int) $page_no * 10; 

            /* Get total records */  
            if($typstr != ''){
              $total_records = $this->common_model->getcount(ITEMDATES,array('end_date >= '=>date('Y-m-d'),'type'=>$typstr));
            }else{
              $total_records = $this->common_model->getcount(ITEMDATES,array('end_date >= '=>date('Y-m-d')));
            }
            

            if($total_records > $total_requested){                      
              $has_next = TRUE;                    
            }else{                        
              $has_next = FALSE;                    
            }
            // p($detail_data);
            foreach ($detail_data as $value) {
              $opt = array(
                'table'   => ITEMSDATESDAY.' AS idy',
                'select'  => 'idy.day,idyp.start_time,idyp.end_time,idyp.price',
                'join'    => array(ITEMSDATESDAYSPRICE.' as idyp'=>'idyp.item_dates_day_id = idy.id'),
                'where'   => array('idy.item_dates_id' => $value->id)
              );
              
              $detailed_data = $this->common_model->customGet($opt);  
              // p($detailed_data);
              
              if($value->type == 'allacart'){
                $table = ALLACART;
              }else if($value->type == 'foodparcel'){
                $table = FOODPARCEL;
              }else if($value->type == 'partypackage'){
                $table = PARTYPACKAGE;
              }
              $op = array(
                'table'=>$table,
                'select'=>'id,item_name,image,price',
                'where'=>array('id'=>$value->item_id,'status'=>1),
                'single'=>true
              );
              
              $productData = $this->common_model->customGet($op);
              
              // if product (item,foodparcel etc is deactive then there is no offer)
              if(!empty($productData)){

                $productData->image = base_url().str_replace('_','',$table).'/'.$productData->image;
                if(!empty($detailed_data)){
                  foreach($detailed_data as $tymprice){
                    // $finalArr[$tymprice->day][] = $tymprice;
                    
                    $temp['offer_id'] = $value->id;
                    $temp['type'] = $value->type;
                    $temp['offer_title'] = $value->offer_title;
                    $temp['min_qty'] = $value->min_qty;
                    $temp['start_date'] = $value->start_date;
                    $temp['end_date'] = $value->end_date;
                    $temp['day'] = $tymprice->day;
                    $temp['start_time'] = $tymprice->start_time;
                    $temp['end_time'] = $tymprice->end_time;
                    $temp['price'] = $tymprice->price;
                    $temp['product_details'] = $productData;


                    /*Satrt*/
                    $dArr = array();
                    $dtArr = array();
                    $sd = $value->start_date;  
                    $nd = $value->start_date;  
                    $ed = $value->end_date;  
                    $dArr[0]['date'] = $sd;
                    $dArr[0]['day'] = date('l',strtotime($sd));
                    $i = 1;
                    while($ed > $nd){
                      $time = strtotime($nd);
                      $tomorrow = date("Y-m-d", $time + 86400);
                      $dtArr['date'] = $tomorrow;
                      $dtArr['day'] = date('l',strtotime($tomorrow));
                      $nd = $tomorrow;
                      $dArr[$i] = $dtArr; 
                      $i++;
                    }
                    // p($dArr);
                    foreach ($dArr as $v) {
                      if($v['day'] == $tymprice->day){
                        $temp['date'] = $v['date'];
                        break;                      
                      }
                    }
                    $dArr = array();  
                    /*End*/
                    
                    $d = strtotime($temp['date']);
                    $dt = date('Y-m-d');
                    $dt = strtotime($dt);
                    if($d >= $dt){
                      $dlDate = $temp['date'];
                      $dlt = $temp['end_time'];
                      
                      $cDateTime = date('Y-m-d H:i:s');
                      $ddDateTime = date('Y-m-d H:i:s',strtotime("$dlDate $dlt"));

                      $dateC = strtotime($cDateTime);
                      $dateD = strtotime($ddDateTime);

                      if($dateD >= $dateC){
                        $eachArr[] = $temp; 
                      }
                    }

                  }
                }
              }
              

            }
            if(!empty($eachArr)){
              echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Best Offers found successfully']);
            }else{
              echo $this->jsonresponse->geneate_response(1, 0,'Best Offers not found',[]);  
            }

          }else{
            echo $this->jsonresponse->geneate_response(1, 0,'Best Offers not found',[]);
          }   
          
        }

    }



     /**
     * Function Name: offer_confirm
     * Description: To Confirm offer
     * NOTE:- This method check whether offer is applying on item or not.
    */
    function offer_confirm_post() {
      $data = $this->input->post();

        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('cart_data', 'Cart Content', 'trim|required');
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
        $this->form_validation->set_rules('time', 'Time', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
          $error = $this->form_validation->rest_first_error_string();
          echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else { 
            
            $eachArr   = array();
            $discount  = 0;
            $discount_on_amount = 0;
            $type      = extract_value($data,'type','');
            $cart_data = extract_value($data,'cart_data','');
            $date = extract_value($data,'date','');
            $time = extract_value($data,'time','');
            $amount = extract_value($data,'amount','');
            $user_id = extract_value($data,'user_id','');
            $todaysdate = $date;
            $today = date('l',strtotime($todaysdate));
            $cart_data = json_decode($cart_data);
            
            is_deactive($user_id);
            
            $gst = getConfig('gst'); 
            $userwallet = $this->common_model->user_wallet_amount($user_id);
            //check whether any offer on apply on amount
            //$typ = ($type == 'allacart') ? 1 : ($type == 'foodparcel') ? 2 : 3;
            if($type == 'allacart'){
              $typ = 1;
            }elseif ($type == 'foodparcel') {
              $typ = 2;
            }else{
              $typ = 3;
            }
            $options = array(
              'table'  => BILLINGOFFER,
              'select' => '*',
              'where'  => array('status'=>1,'type'=>$typ,'amount <= '=>$amount,'created'=>$date)
            );
            $amountoffer = $this->common_model->customGet($options);
            
            if(!empty($amountoffer)){
              foreach ($amountoffer as $ac) {
                if($ac->amount <= $amount){
                  $discount = $ac->discount;
                  $discount_on_amount = $ac->amount;
                }  
              }
              
            }

            if(!empty($cart_data)){
              foreach ($cart_data as $key => $value) {
                $temp['qty'] = $value->qty;
                $temp['item_id'] = $value->item_id;
                $temp['price'] = 0;
                $temp['min_qty'] = 0;
                $opt = array(
                  'table'   =>ITEMDATES,
                  'select'  =>'id,min_qty,start_date,end_date',
                  'where'   =>array(
                    'type'        => $type,
                    'min_qty <='  => $value->qty,
                    'item_id'     => $value->item_id,
                    'end_date >= '=> $date
                  ),
                  //'single'        => true
                ); 
                $dateidArr = $this->common_model->customGet($opt);
                // lq();
                // p($dateidArr);
                if(!empty($dateidArr)){
                  foreach($dateidArr as $dtid){

                    $opts = array(
                      'table'  => ITEMSDATESDAY,
                      'select' => 'id',
                      'where'  => array('item_dates_id'=>$dtid->id,'day'=>$today),
                      'single' => true 
                    );  
                    $daysidArr = $this->common_model->customGet($opts);
                    // p($daysidArr);
                    if(!empty($daysidArr)){
                      $current_time = date('H:i:s');
                      $option = array(
                        'table'  => ITEMSDATESDAYSPRICE,
                        'select' => 'price',
                        'where'  => array(
                          'item_dates_id'     => $dtid->id,
                          'item_dates_day_id' => $daysidArr->id,
                          'start_time <= '    => $time, 
                          'end_time >= '      => $time, 
                        ),
                        'single' => true
                      );
                      
                      $priceArr = $this->common_model->customGet($option);
                      if(!empty($priceArr)){
                        $temp['price'] = (int)$priceArr->price;
                        $temp['min_qty'] = (int)$dtid->min_qty;
                        $priceArr = array();
                      }
                    }
                  } 
                }
                  // $temp['qty'] = $value->qty;
                  // $temp['item_id'] = $value->item_id;
                  $eachArr[] = $temp;
                  $temp = array();
              }
            }
            
            if(!empty($eachArr)){
              echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'billing_offer_discount'=>(int)$discount,'discount_on_amount'=>(float)$discount_on_amount,'GST'=>$gst,'wallet_amount'=>(float)$userwallet,'message'=>'Offer']);
            }else{
              echo $this->jsonresponse->geneate_response(1, 0,'No offer available',[]);
            }
        }
    }

    
    /**
     * Function Name: billing_offer_list
     * Description: To Get all offer
     * NOTE:- This offer are those which apply on amount wise.
     *        comes from billing_offer table. 
    */

    function billing_offer_list_post() {
      $data = $this->input->post();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
        
        if ($this->form_validation->run() == FALSE) {
          $error = $this->form_validation->rest_first_error_string();
          echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {

          $type         = extract_value($data,'type','');  
          $page_no      = extract_value($data,'page_no',1);  
          $offset       = get_offsets($page_no);
      
          $upload_url   = base_url().'uploads/billingoffer/';
          $current_date = datetime('Y-m-d');

          $options = array(
            'table'  => BILLINGOFFER,
            'select' => '*',
            'where'  => array('status'=>1,'created >='=>date('Y-m-d')),
            'order'  => array('id' => 'desc'),
            'limit'  => array(10 => $offset)
          );
          $countArr = array('status'=>1,'created >='=>date('Y-m-d'));
          if($type != ''):
            $options['where']['type'] = $type;
            $countArr = array('status'=>1,'created >='=>date('Y-m-d'),'type'=>$type);
          endif;
          /* To get offer list from offer table */
          $list = $this->common_model->customGet($options);

          /* check for image empty or not */

          if (!empty($list)) {

              $eachArr = array();

              $total_requested = (int) $page_no * 10; 

              /* Get total records */  
              $total_records = getAllCount(BILLINGOFFER,$countArr);

              if($total_records > $total_requested){                      
                $has_next = TRUE;                    
              }else{                        
                $has_next = FALSE;                    
              }

              foreach ($list as $rows):
                if(!empty($rows->image)){
                    $image = $upload_url.$rows->image;
                } else{
                    /* set default image if empty */
                    $image = base_url().DEFAULT_NO_IMG_PATH;
                }
                $temp['id']         = null_checker($rows->id);
                $temp['amount']     = null_checker($rows->amount);
                $temp['discount']   = null_checker($rows->discount);
                $temp['type']       = null_checker($rows->type);
                $temp['status']     = null_checker($rows->status);
                $temp['created']    = null_checker(convertDate($rows->created));
                $temp['image']      = $image;

                $eachArr[] = $temp;
              endforeach;
              /* return success response*/

              echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Offer found successfully']);

            }else {
              echo $this->jsonresponse->geneate_response(1, 0,'Offer not found',[]);
          }
        }

    }

}


   /* End of file Offer.php */
   /* Location: ./application/controllers/api/v1/Offer.php */
   ?>
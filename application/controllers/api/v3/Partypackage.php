<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for category
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun Choudhary
 */
class Partypackage extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }


    /**
     * Function Name: partypackage_list
     * Description:   To Get all partypackage list
    */

    function partypackage_list_post() {
      $data = $this->input->post();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

        if ($this->form_validation->run() == FALSE) {
          $error = $this->form_validation->rest_first_error_string();
          echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {

          $page_no    = extract_value($data,'page_no',1);  
          $offset     = get_offsets($page_no);

          $date       = extract_value($data,'date','');  
          $time       = extract_value($data,'time','');  
          
          $timestamp  = '';
          $todaysdate = '';
          $today      = ''; 
          if($date != '' && $time != ''){
            $timestamp  = strtotime($time) + 60*60;
            $time       = date('H:i:s', $timestamp);
            $todaysdate = $date;
            $today      = date('l',strtotime($todaysdate));
          }


      
          $upload_url = base_url().'uploads/partypackage/';
          $allcart_url = base_url().'uploads/allacart/';
          $current_date = datetime('Y-m-d');

          //Get All active party package
          $options = array(
            'table'  => PARTYPACKAGE.' AS pp',
              'select' => 'pp.*,GROUP_CONCAT(pc.items_id) as items_id,GROUP_CONCAT(pc.category_id) as category_id,pc.item_limit',
              'join'   =>array(PACKAGECATEGORY.' AS pc'=>'pc.package_id=pp.id'),
              'where'=>array('pp.status'=>1),
              'order'  => array('pp.item_name' => 'asc'),
              'limit'  => array(10 => $offset),
              'group_by'   => 'pp.id'
            );

          
          $list = $this->common_model->customGet($options);
          

          if (!empty($list)) {

              $eachArr = array();

              $total_requested = (int) $page_no * 10; 

               // Get total records   
              // $total_records = getAllCount(PARTYPACKAGE);
              $total_records = 0;
              unset($options['order']);  
              unset($options['limit']);
              $options['select'] = 'COUNT(pp.id) AS count';
              $options['single'] = true;
              $listCount = $this->common_model->customGet($options); 
              if(!empty($listCount)):
                $total_records = $listCount->count;
                endif;
                
              //For Pagination
              if($total_records > $total_requested){                      
                $has_next = TRUE;                    
              }else{                        
                $has_next = FALSE;                    
              }

              foreach ($list as $rows) {
                
                $categoryArr = explode(',',$rows->category_id);
                
                foreach($categoryArr as $cat){
                  // To get category Name 
                  $catnameArr = $this->db->get_where(CATEGORY_MANAGEMENT,array('id'=>$cat,'status'=>1))->row_array();

                  if(!empty($catnameArr)){
                      $category_Arr['name'] = $catnameArr['category_name'];
                      $category_Arr['category_id'] = $catnameArr['id'];
                      $category_Arr['category_image'] = base_url().'uploads/category/'.$catnameArr['image'];  
                    
                      //To Get item limit in package
                      // $pcategoryData = $this->db->get_where(PACKAGECATEGORY,array('category_id'=>$cat,'package_id'=>$rows->id))->row_array();

                      $op = array(
                        'table'=>PACKAGECATEGORY.' AS pc',
                        'select'=>'pc.item_limit,pc.items_id',
                        'join'=>array(CATEGORY_MANAGEMENT.' AS cm'=>'cm.id = pc.category_id'),
                        'where'=>array('category_id'=>$cat,'package_id'=>$rows->id,'cm.status'=>1),
                        'single'=>true
                      );
                      $pcategoryData = array();
                      $pcategoryData = $this->common_model->customGet($op);
                      
                      $category_Arr['limit'] = $pcategoryData->item_limit;
                    
                    
                      //to get selected items of category
                      $itemstr = $pcategoryData->items_id;
                      
                      $itemTempArr = explode(',',$itemstr);
                   
                    //to get item details with selected items 
                    $ctgryArr = $this->db->order_by('item_name', 'ASC')->get_where(ALLACART,array('category_id'=>$cat))->result_array();
                    foreach ($ctgryArr as $value) {
                    
                      //create array of category items
                      if(in_array($value['id'], $itemTempArr)){
                        $is_select = 0;
                        $value['image'] = $allcart_url.$value['image'];
                        $value['is_select'] = $is_select;
                        $category_Arr['items'][] =  $value;
                      }/*else{
                        $is_select = 0;
                      }*/
                      /*$value['image'] = $allcart_url.$value['image'];
                      $value['is_select'] = $is_select;
                      $category_Arr['items'][] =  $value;*/
    
                    }
                    $cateArr[] = $category_Arr;
                    $category_Arr = array();
                    
                  }
                }
                
                $categoryArr = array();
                if(!empty($rows->image)){
                    $image = $upload_url.$rows->image;
                } else{
                    /* set default image if empty */
                    $image = base_url().DEFAULT_NO_IMG_PATH;
                }

                //party package details
                $temp['id']               = (int)null_checker($rows->id);
                $temp['name']             = null_checker($rows->item_name);
                $temp['price']            = (int) null_checker($rows->price);
                $temp['strike_price']     = (int) null_checker($rows->strike_price);
                $temp['partial_payment']  = (int) null_checker($rows->partial_payment);
                $temp['discount']         = null_checker($rows->discount);
                $temp['description']      = null_checker($rows->description);
                $temp['gender_pref']      = null_checker($rows->gender_pref);
                $temp['min_age']          = (int)null_checker($rows->min_age);
                $temp['max_age']          = (int)null_checker($rows->max_age);
                $temp['min_person']       = (int)null_checker($rows->min_person);
                $temp['status']           = ($rows->status == 1) ? 'active' : 'inactive';
                $temp['created_date']     = null_checker(convertDate($rows->created));
                $temp['image']            = $image;
                $temp['categories']       = $cateArr;


                //start
                $temp['is_offer_available'] = 0;
                 
                $temp['offer_detail']['price'] = 0;
                $temp['offer_detail']['min_qty'] = 0;
                if($date != '' && $time != ''){
                  $opt = array(
                    'table'   =>ITEMDATES,
                    'select'  =>'id,min_qty,start_date,end_date',
                    'where'   =>array(
                      'type'        => 'partypackage',
                      'item_id'     => $rows->id,
                      'end_date >= '=> $date
                    ),
                  ); 
                  $dateidArr = $this->common_model->customGet($opt);
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
                          $temp['offer_detail']['price'] = (int)$priceArr->price;
                          $temp['offer_detail']['min_qty'] = (int)$dtid->min_qty;
                          $temp['is_offer_available'] = 1;
                          $priceArr = array();
                        }
                        $daysidArr = array();
                      }
                    } 
                    $dateidArr = array();
                  }
                }
                //end

                $eachArr[] = $temp;
                $cateArr = array();
                $temp = array();

              } 

              echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Party package found successfully']);

            }else {
              echo $this->jsonresponse->geneate_response(1, 0,'Party package not found',[]);
          }
        }

    }


}


   /* End of file Partypackage.php */
   /* Location: ./application/controllers/api/v1/Partypackage.php */
   ?>
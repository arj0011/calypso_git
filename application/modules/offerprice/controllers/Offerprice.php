<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Offerprice extends Common_Controller { 
  public $data = array();
  public $file_data = "";
  public $element = array();
  public function __construct() {
    parent::__construct();
    $this->is_auth_admin();
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

    /***********************OFFER PRICE RELATED METHODS*******************************/

    /**********Offer List*********/

    public function setpricevariation($type,$item_id=''){

      $this->data['parent'] = lang('food_items');
      $this->data['title'] = $type;

      $this->data['week'] = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
      $item_id = decoding($item_id);
      $min_person = '';
      if($type == 'partypackage'){
        $opt = array(
          'table'=>PARTYPACKAGE,
          'select'=>'min_person',
          'where'=>array('id'=>$item_id),
          'single'=>true
        );
        
        $pData = $this->common_model->customGet($opt);
        if(!empty($pData)):
          $min_person = $pData->min_person;
          endif;
      }

      $option = array(
        'table'   => ITEMDATES.' as idt',
        'select'  => 'idt.id,idt.offer_title,idt.min_qty,idt.type,start_date,end_date,item_id,idy.id as item_dates_day_id,idy.day',
        'join'    => array(ITEMSDATESDAY.' as idy'=>'idy.item_dates_id = idt.id'),
        'where'   => array('idt.item_id ='=>$item_id,'idt.end_date >= '=>date('Y-m-d')),
        'order'   => array('idt.start_date' => 'DESC'),
        'group_by'=> 'idt.id'
      );
      $this->data['datesData'] = $this->common_model->customGet($option);
      $this->data['min_person'] = $min_person;
      $this->load->admin_render('setprice', $this->data, 'inner_script');
    }

    /******Validate Start date and end date*******/
    public function validate_dates(){
      $start_date = date('Y-m-d',strtotime($this->input->post('start_date')));
      $end_date = date('Y-m-d',strtotime($this->input->post('end_date')));
      $item_id = $this->input->post('item_id');
      $item_id = decoding($item_id);
      $opt = array(
          'table'   => ITEMDATES,
          'select'  => 'id',
          'where'   => array('item_id'=>$item_id,'end_date >=' => $start_date,'start_date<='=>$end_date),
          'single'  =>true
        );
      $existdate = $this->common_model->customGet($opt);
      if(empty($existdate)){
        $response = array('status' => 1);
      }else{
        $response = array('status' => 0, 'message' => lang('price_exist_on_dates'));
      }
      echo json_encode($response);

    }



    /**********Add Offer Price*********/

    public function addprice(){
      
      $this->form_validation->set_rules('offer_title', lang('offer_title'), 'required|trim');
      $this->form_validation->set_rules('min_qty', lang('min_qty'), 'required|trim');
      $this->form_validation->set_rules('start_date', lang('start_date'), 'required|trim');
      $this->form_validation->set_rules('end_date', lang('end_date'), 'required|trim');
      
      $ctlr = $this->input->post('ctlr');
      $type = $ctlr;
      $item_id = $this->input->post('item_id');
        
      //keep it for redirect
      $itm_id = $item_id;
      
      if($this->form_validation->run() == true){
        $offer_title = $this->input->post('offer_title');
        $min_qty = $this->input->post('min_qty');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
         
        
        $offer_title  = $this->security->xss_clean($offer_title);
        $min_qty      = $this->security->xss_clean($min_qty);
        $type         = $this->security->xss_clean($type);
        $start_date   = $this->security->xss_clean($start_date);
        $end_date     = $this->security->xss_clean($end_date);

        $start_date   = date('Y-m-d',strtotime($start_date));
        $end_date     = date('Y-m-d',strtotime($end_date));        


        $item_id      = decoding($item_id);

        $bool = is_date_available($start_date,$end_date,$item_id);
        if($bool){
          $this->session->set_flashdata('error', lang('price_failed'));
          redirect('offerprice/setpricevariation/'.$ctlr.'/'.$itm_id);
        }
        
        
        if(!isset($_POST['day']) || empty($_POST['day'])){
          $this->session->set_flashdata('error', lang('price_failed'));
          redirect('offerprice/setpricevariation/'.$ctlr.'/'.$itm_id);
        }

        //check start date and end date is available or not.




        /********** Inser Data into ITEMDATES *********/
        $options_data = array(
              'offer_title' => $offer_title,
              'min_qty'     => $min_qty,
              'type'        => $type,
              'start_date'  => $start_date,
              'end_date'    => $end_date,
              'item_id'     => $item_id
        );
        $option = array('table' => ITEMDATES, 'data' => $options_data);
        $itemdates_id = $this->common_model->customInsert($option); 
        
        /********** Inser Data into ITEMSDATESDAY *********/
        
        /*$next_date = $start_date;
        $dateArr[0] = $start_date;
        $dayArr[0] = date('l',strtotime($start_date));
        $j = 1;
        while($end_date > $next_date){
          $time = strtotime($next_date);
          $tomorrow = date("Y-m-d", $time + 86400);
          $dateArr[$j] = $tomorrow; 
          $dayArr[$i] = date('l',strtotime($tomorrow));
          $next_date = $tomorrow;
          $j++;
        }*/

        if($itemdates_id){
          $dayArr = $this->input->post('day');
          if(!empty($dayArr)){
            foreach ($dayArr as $day) {
              $option_data = array(
                'item_dates_id' => $itemdates_id,
                'day'           => $day
              );    
              $options = array('table' => ITEMSDATESDAY, 'data' => $option_data);
              $itemdatesday_id = $this->common_model->customInsert($options);
                
        /******** Insert Data into ITEMSDATESDAYSPRICE *******/      
              if($itemdatesday_id){
                
                $count = count($_POST[$day.'_start_time']);
                
                for($i = 0;$i < $count;$i++){
                  
                  $start_time = $_POST[$day.'_start_time'][$i];
                  $end_time = $_POST[$day.'_end_time'][$i];
                  $price = $_POST[$day.'_price'][$i];
                  if($start_time != '' && $end_time != '' && $price != ''){
                    $existtime = array();  
                  
                    $opt = array(
                      'table'   => ITEMSDATESDAYSPRICE,
                      'select'  => 'id',
                      'where'   => array(
                        'item_dates_id'     => $itemdates_id,
                        'item_dates_day_id' => $itemdatesday_id,
                        'end_time > '       => $start_time,
                        'start_time < '     => $end_time
                        ),
                      'single'  =>true
                    );
                  
                    $existtime = $this->common_model->customGet($opt);
                  
                    if(empty($existtime)){
                      $opt_data = array(
                        'item_dates_id'     => $itemdates_id,
                        'item_dates_day_id' => $itemdatesday_id,
                        'start_time'        => $start_time, 
                        'end_time'          => $end_time,
                        'price'             => $price
                      );
                      $opts = array('table' => ITEMSDATESDAYSPRICE, 'data' => $opt_data);
                      $itemdatesdaytime_id = $this->common_model->customInsert($opts);
                    }  
                  
                  }
                }
              }  
            }



            // Save push notification 
            // Get all users
            $opt_usr = array(
              'table' => USERS,
              'select' => 'id',
              'where'=>array('user_type'=>'USER')
            );
            $user_id = $this->common_model->customGet($opt_usr);

            foreach ($user_id as $key => $value) {
              $all_users[] = $value->id;
            }

            // Insert Notification in admin notification table   
            
            $message = "New Offer on order of quantity ".$min_qty;

            if($type == 'allacart'){
              $typno = 1; 
            }else if($type == 'foodparcel'){
              $typno = 2; 
            }else if($type == 'partypackage'){
              $typno = 3; 
            }

            $notification_arr = array(
              'message' => $message,
              'title' => 'Best Offer',
              'type_id' => $itemdates_id, //offer id of items_dates table
              'user_ids' => serialize($all_users),
              'notification_type' => 3,//best offer
              'type'=>$typno,//sections
              'sent_time' => datetime()
            );
            $lid = $this->common_model->insertData(ADMIN_NOTIFICATION,$notification_arr);

            // Insert Notifications in user notification table
            
            $user_notifications = array();
            for($i=0;$i<count($all_users);$i++){
              
              $insertArray = array(
                  'type_id' => $itemdates_id,
                  'sender_id' => ADMIN_ID,
                  'reciever_id' => $all_users[$i],
                  'notification_type' => 'Best Offer',
                  'type' => $typno,
                  'title' => 'Best Offer',
                  'notification_parent_id' => $lid,
                  'message' => $message,
                  'is_read' => 0,
                  'is_send' => 0,
                  'sent_time' => date('Y-m-d H:i:s'),
              );
              
              array_push($user_notifications, $insertArray);
            }

            if(!empty($user_notifications)){
              $this->common_model->insertBulkData(USER_NOTIFICATION,$user_notifications);
            }

            //end

            $this->session->set_flashdata('success', lang('price_success'));
            redirect('offerprice/setpricevariation/'.$ctlr.'/'.$itm_id);
          } 
        }else{
            $this->session->set_flashdata('error', lang('price_failed'));
            redirect('offerprice/setpricevariation/'.$ctlr.'/'.$itm_id);
        }
        
      }else {
        $messages = (validation_errors()) ? validation_errors() : '';
        $this->session->set_flashdata('error', $messages);
        redirect('offerprice/setpricevariation/'.$ctlr.'/'.$itm_id);
      }  

    }

  /*Load accordian(dayaccordian.php) according to date difference and day*/

  public function getdatediff(){
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $item_id = $this->input->post('item_id');
    $item_id = decoding($item_id);

    $start_date = date('Y-m-d',strtotime($start_date));
    $end_date = date('Y-m-d',strtotime($end_date));


    $dayDatesArr = date_difference($start_date,$end_date);    
    if(!empty($dayDatesArr)){
      $this->data['week'] = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
      $this->data['daysArr'] = array_column($dayDatesArr,'day');
      $this->load->view('dayaccordian',$this->data);
    }
  }  

  /*Load Times Row (timesrow.php) inside accordian(dayaccordian.php) according to day*/
  public function addtimerow(){
    $this->data['day'] = $this->input->post('day');
    $this->data['button_count'] = $this->input->post('button_count');
    $this->load->view('timesrow',$this->data);
  }

   public function edittimesrow(){
    $this->data['day'] = $this->input->post('day');
    $this->data['button_count'] = $this->input->post('button_count') + 1;
    $this->load->view('edittimesrow',$this->data);
  }

  /******************** Open Offer Price Modal *******************/
  function open_daytime_modal() {
    $this->data['title'] = 'Food Items Prices';
    $item_dates_id = $this->input->post('item_dates_id');
    $option = array(
      'table'   => ITEMDATES.' AS idt',
      'select'  => 'idt.type,idt.offer_title,idy.id AS items_dates_day_id,idy.day',
      'join'    => array(ITEMSDATESDAY.' as idy'=>'idy.item_dates_id = idt.id'),
      'where'   => array('idt.id ='=>$item_dates_id)
    );
    $detail_data = $this->common_model->customGet($option);
    if(!empty($detail_data)){
      
      $type = $detail_data[0]->type;
      if($type == 'foodparcel'):
        $this->data['title'] = 'Food Parcel Prices';   
        endif;
      if($type == 'partypackage'):
        $this->data['title'] = 'Party Package Prices';   
        endif;  
      $this->data['offer_title'] = $detail_data[0]->offer_title; 
      
      $finalArr = array();
      foreach ($detail_data as $value) {
        $opt = array(
        'table'   => ITEMSDATESDAY.' AS idy',
        'select'  => 'idy.id AS items_days_id,idyp.start_time,idyp.end_time,idyp.price',
        'join'    => array(ITEMSDATESDAYSPRICE.' as idyp'=>'idyp.item_dates_day_id = idy.id'),
        'where'   => array('idy.id=' => $value->items_dates_day_id)
      );
        $detailed_data = $this->common_model->customGet($opt);       
        if(!empty($detailed_data)){
          foreach($detailed_data as $tymprice){
            $finalArr[$value->day][] = $tymprice;
          }
        }
      }
      $this->data['days_Data'] = $finalArr;  
    } 
    $this->load->view('datetimemodal', $this->data);  
  }

  /*********************** Edit Offer Price ***********************/
    public function offerprice_edit($ctlr,$item_dates_id,$item_id){
      $this->data['title'] = 'Edit Offer Price';
      $item_dates_id = decoding($item_dates_id);
      $item_id = decoding($item_id);
      // echo $item_id;die;
      if (!empty($item_dates_id)) {
        $finalArr = array(); 
        $option = array(
          'table'   => ITEMDATES.' AS idt',
          'select'  => 'idt.offer_title,idt.min_qty,idt.start_date,idt.end_date,idy.id AS items_dates_day_id,idy.day',
          'join'    => array(ITEMSDATESDAY.' as idy'=>'idy.item_dates_id = idt.id'),
          'where'   => array('idt.id ='=>$item_dates_id,'idt.end_date >= '=>date('Y-m-d'))
        );

        $detail_data = $this->common_model->customGet($option);
        if (!empty($detail_data)) {
          foreach ($detail_data as $value) {
            $opt = array(
              'table'   => ITEMSDATESDAY.' AS idy',
              'select'  => 'idy.id AS items_days_id,idyp.id AS items_dates_days_id,idyp.start_time,idyp.end_time,idyp.price',
              'join'    => array(ITEMSDATESDAYSPRICE.' as idyp'=>'idyp.item_dates_day_id = idy.id'),
              'where'   => array('idy.id=' => $value->items_dates_day_id)
            );
            $detailed_data = $this->common_model->customGet($opt);       
            if(!empty($detailed_data)){
              foreach($detailed_data as $tymprice){
                $finalArr[$value->day][] = $tymprice;
              }
            }
          }
          $this->data['ctlr'] = $ctlr;
          $this->data['item_id'] = encoding($item_id);
          $this->data['item_dates_id'] = encoding($item_dates_id);
          $this->data['offer_title'] = encoding($detail_data[0]->offer_title);
          $this->data['min_qty'] = encoding($detail_data[0]->min_qty);
          $this->data['start_date'] = encoding($detail_data[0]->start_date);
          $this->data['end_date'] = encoding($detail_data[0]->end_date);
          $this->data['days_Data'] = $finalArr;
          $this->load->admin_render('offerpriceedit', $this->data, 'inner_script');    
        }else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('offerprice/setpricevariation/'.$ctlr.'/'.$item_id);
        }  
      }else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('offerprice/setpricevariation/'.$ctlr.'/'.$item_id);
      }
    }


    /*Update Price - For particular start & end date*/

     public function offerprice_update(){
      
      if(!empty($this->input->post('days')) && (count($this->input->post('days')) > 1) && ($this->input->post('item_id') != '' && $this->input->post('item_dates_id') != '' && $this->input->post('ctlr') != '')){
        
        $item_id = $this->input->post('item_id');
        $itemdates_id = $this->input->post('item_dates_id');
        $ctlr = $this->input->post('ctlr');
        $flag = 0;
        
        // if(!empty($itemdates_id)){
          $item_dates_id = decoding($itemdates_id);
          
          $this->form_validation->set_rules('offer_title', lang('offer_title'), 'required|trim');
          $this->form_validation->set_rules('min_qty', lang('min_qty'), 'required|trim|numeric');
          $this->form_validation->set_rules('start_date', lang('start_date'), 'required|trim');
          $this->form_validation->set_rules('end_date', lang('end_date'), 'required|trim'); 
          
          if($this->form_validation->run() == false){
            $messages = (validation_errors()) ? validation_errors() : '';
            $this->session->set_flashdata('error', $messages);
            redirect('offerprice/offerprice_edit/'.$ctlr.'/'.$itemdates_id.'/'.$item_id);
          }else {
            
            $offer_title = $this->input->post('offer_title');
            $min_qty     = $this->input->post('min_qty');

            $offer_title = $this->security->xss_clean($offer_title);
            $min_qty     = $this->security->xss_clean($min_qty);

            $datesupdateArr = array('offer_title'=>$offer_title,'min_qty'=>$min_qty);
            $opts = array(
              'table' => ITEMDATES,
              'data'  => $datesupdateArr,
              'where' =>  array('id'=>$item_dates_id)
              );
              
            $update = $this->common_model->customUpdate($opts);

            $days = $this->input->post('days');

            $option = array(
              'table'   => ITEMSDATESDAY,
              'select'  => 'id',
              'where'   => array('item_dates_id'=>$item_dates_id)
              );
            $itemsdaysidArr = $this->common_model->customGet($option);
            if(!empty($itemsdaysidArr)){

              $daycount = count($itemsdaysidArr);

              for($i = 0;$i < $daycount;$i++){
            
                $entryCount = count($_POST[$days[$i].'_start_time']);
                $inputArr = array();
              
                for($j=0;$j<$entryCount;$j++){

                  $start_time = $_POST[$days[$i].'_start_time'][$j];
                  $end_time = $_POST[$days[$i].'_end_time'][$j];
                  $price = $_POST[$days[$i].'_price'][$j];
                
                  $itemdatesday_id = $itemsdaysidArr[$i]->id;
                  $existtime = array();
                  if($start_time != '' && $end_time != '' && $price != ''){
                    $opt = array(
                      'table'   => ITEMSDATESDAYSPRICE,
                      'select'  => 'id',
                      'where'   => array(
                        'item_dates_id'     => $item_dates_id,
                        'item_dates_day_id' => $itemdatesday_id,
                        'end_time > '       => $start_time,
                        'start_time < '     => $end_time
                        ),
                      'single'  =>true
                    );
                    $existtime = $this->common_model->customGet($opt);
                    if(empty($existtime)){
                      $opt_data = array(
                        'item_dates_id'     => $item_dates_id,
                        'item_dates_day_id' => $itemdatesday_id,
                        'start_time'        => $start_time, 
                        'end_time'          => $end_time,
                        'price'             => $price
                      );
                      $opts = array('table' => ITEMSDATESDAYSPRICE, 'data' => $opt_data);
                      $itemdatesdaytime_id = $this->common_model->customInsert($opts);
                    }else{
                      $flag = 1;
                    }  
                  }
                }
              }   
            }
            if($flag == 1){
              $this->session->set_flashdata('success',lang('success_offer_update'));
              redirect('offerprice/offerprice_edit/'.$ctlr.'/'.$itemdates_id.'/'.$item_id);
            }else if($itemdatesdaytime_id){
              $this->session->set_flashdata('success',lang('price_update_success'));
              redirect('offerprice/offerprice_edit/'.$ctlr.'/'.$itemdates_id.'/'.$item_id);
            }else{
              $this->session->set_flashdata('error',lang('price_update_failed'));
              redirect('offerprice/offerprice_edit/'.$ctlr.'/'.$itemdates_id.'/'.$item_id);
            }

          } 
      }else{
        $this->session->set_flashdata('success',lang('price_update_failed'));
        redirect('offerprice/offerprice_edit/'.$ctlr.'/'.$itemdates_id.'/'.$item_id);
      }
    }



    public function deletetimerow(){
        
        if($this->input->post('id') != ''){
          $id = decoding($this->input->post('id'));
          $option = array(
                'table' => ITEMSDATESDAYSPRICE,
                'where' => array('id' => $id)
            );
          $delete = $this->common_model->customDelete($option);
          if ($delete) {
            $response = 200;
          } else{
            $response = 400;
          }
        }else {
            $response = 400;
        }
        echo $response;
    }




}

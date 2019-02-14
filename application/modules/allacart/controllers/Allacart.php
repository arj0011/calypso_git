<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Allacart extends Common_Controller { 
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
     
      $this->data['parent'] = lang('food_items');
      $this->data['title'] = lang('alla_cart');
      // $this->db->select(ALLACART.'.*,'.CATEGORY_MANAGEMENT.'.category_name');  
      // $this->db->from(ALLACART);
      // $this->db->join(CATEGORY_MANAGEMENT,CATEGORY_MANAGEMENT.'.id='.ALLACART.'.category_id','left');
      // $this->db->order_by(ALLACART.'.id','DESC');
      // $this->data['list'] = $this->db->get()->result();

      $option = array(
        'table'=>ALLACART.' AS a',
        'select'=>'a.*,cm.category_name',
        'join'=>array(CATEGORY_MANAGEMENT.' AS cm'=>'cm.id = a.category_id'),
        'where'=>array('cm.status'=>1),
        'order'=>array('a.id'=>'DESC')
      );
      if ($_POST):
        if($this->input->post('statusfilter') != ''):

          $option['where']['a.status'] = ($this->input->post('statusfilter') == 1) ? 1 : 0;
          $this->data['statusfilter'] = ($this->input->post('statusfilter') == 1) ? 1 : 2;
          endif;

        if($this->input->post('start_date') != '' && $this->input->post('end_date') != ''):
          $option['where'] = array(
            'DATE(a.created_date) >= '=>date('Y-m-d',strtotime($this->input->post('start_date'))),
            'DATE(a.created_date) <='=>date('Y-m-d',strtotime($this->input->post('end_date')))
            );
          $this->data['start_date'] = $this->input->post('start_date');
          $this->data['end_date'] = $this->input->post('end_date');
          endif;
      endif;

      $this->data['list'] = $this->common_model->customGet($option);
      // lq();
      $this->load->admin_render('list', $this->data, 'inner_script');
    }


    /**
     * @method open_model
     * @description load model box
     * @return array
     */

    function open_model() {
      
      $query = "select * from category_management where status = 1";
      $this->data['category'] =  $this->common_model->customQuery($query);
      // p($this->data['category']);
      $this->data['title'] = lang("add_food");
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
    public function item_add() {
      $this->form_validation->set_rules('category_id', lang('select_category'), 'required|trim');
      $this->form_validation->set_rules('food_name', lang('food_name'), 'required|trim');
      $this->form_validation->set_rules('strike_price', lang('strike_price'), 'required|trim');
      $this->form_validation->set_rules('price', lang('price'), 'required|trim');
      $this->form_validation->set_rules('date', 'Date', 'required|trim');
      if(empty($_FILES['image']['name'])){
        $this->form_validation->set_rules('image', 'image', 'required');
      }
      if($this->form_validation->run() == true) {
        $this->filedata['status'] = 1;
        $image = "";
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'allacart', 'image');
          if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
          }
        }

        if ($this->filedata['status'] == 0 || $this->filedata['status'] = '') {
          $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{
          $current_date=date('Y-m-d');
          $user_id =array();
          $category_id = $this->input->post('category_id');
          $food_name = $this->input->post('food_name');
          $strike_price = $this->input->post('strike_price');
          $price = $this->input->post('price');
          $description = $this->input->post('description');
          $date = $this->input->post('date');
          $category_id   = $this->security->xss_clean($category_id);
          $food_name = $this->security->xss_clean($food_name);
          $price   = $this->security->xss_clean($price);
          $strike_price   = $this->security->xss_clean($strike_price);
          $description = $this->security->xss_clean($description);
          $date = $this->security->xss_clean($date);
          $date = date('Y-m-d',strtotime($date));
        
          $option = array('table' => ALLACART,
           'select' => 'item_name',
           'where' => array('item_name'=> $food_name)
          );

          $category = $this->common_model->customGet($option);
          if(empty($category)){  
              $options_data = array(
                'category_id' => $category_id,
                'item_name'   => $food_name,
                'price'       => $price,
                'strike_price'=> $strike_price,
                'description' => $description,
                'image'       => $image,
                'created_date'=> $date,
                'status'      => 1,
              );
              
              $option = array('table' => ALLACART, 'data' => $options_data);
              $foods = $this->common_model->customInsert($option); 
              if($foods) {
                $response = array('status' => 1, 'message' => lang('food_success'), 'url' => base_url('allacart'));
              }else {
                $response = array('status' => 0, 'message' => lang('food_failed'));
              }
          }else{
            $response = array('status' => 0, 'message' => lang('food_exist'));
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
    public function item_edit() {
      $this->data['title'] = lang("edit_food");
      $id = decoding($this->input->post('id'));
      if (!empty($id)) {
        $query = "select * from category_management where id NOT IN(select parent_id from category_management)";
        $this->data['category'] =  $this->common_model->customQuery($query);

        $option = array(
          'table' => ALLACART,
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
    public function item_update() {

      $image = $this->input->post('exists_image');
      // $this->form_validation->set_rules('category_id', lang('select_category'), 'required|trim');
      // $this->form_validation->set_rules('food_name', lang('food_name'), 'required|trim');
      $this->form_validation->set_rules('strike_price', lang('strike_price'), 'required|trim');
      $this->form_validation->set_rules('price', lang('price'), 'required|trim');
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
        $image = $this->input->post('exists_image');
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'allacart', 'image');
          if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
            delete_file($this->input->post('exists_image'), FCPATH."uploads/allacart/");
          }
        }
        if ($this->filedata['status'] == 0) {
          $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{
          // $category_id = $this->input->post('category_id');
          // $food_name = $this->input->post('food_name');
          $price = $this->input->post('price');
          $strike_price = $this->input->post('strike_price');
          $description = $this->input->post('description');
          $date = $this->input->post('date');
          // $category_id   = $this->security->xss_clean($category_id);
          // $food_name = $this->security->xss_clean($food_name);
          $price   = $this->security->xss_clean($price);
          $strike_price   = $this->security->xss_clean($strike_price);
          $description = $this->security->xss_clean($description);
          $date = $this->security->xss_clean($date);
          $date = date('Y-m-d',strtotime($date));
          // $option = array('table' => ALLACART,
          //   'select' => 'item_name',
          //   'where' => array('id !='=>$where_id ,'item_name'=> $food_name)
          // );
          // $foods = $this->common_model->customGet($option);
          // if(empty($foods)){
              $options_data = array(
                // 'category_id'    => $category_id,
                // 'item_name'      => $food_name,
                'price'          => $price,
                'strike_price'   => $strike_price,
                'description'    => $description,
                'image'          => $image,
                'created_date'   => $date
              );

              $option = array(
                'table' => ALLACART,
                'data' => $options_data,
                'where' => array('id' => $where_id)
                );
              
              $update = $this->common_model->customUpdate($option);
              $response = array('status' => 1, 'message' => lang('food_success_update'), 'url' => base_url('allacart'));
          // }else{
            // $response = array('status' => 0, 'message' => lang('product_exist'));
          // }
        }
      endif;
      echo json_encode($response);
    }


  /***********************************************************
  *                                                          * 
  * First Action                                             * 
  * Delete from Second and Third child using JOIN            * 
  * Second Child = ITEMSDATESDAY(items_dates_day)            * 
  * Third Child = ITEMSDATESDAYSPRICE(items_dates_days_price)* 
  *                                                          * 
  * Second Action                                            * 
  * Delete from First Child                                  *
  * First Child = ITEMDATES (item_dates)                     * 
  ************************************************************/  
  
  function del() {
        $response = 400;
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        
        if(!empty($table) && !empty($id) && !empty($id_name)) { 

            $itemDatesArr = $this->db->query('SELECT id FROM '.ITEMDATES.' WHERE item_id = '.$id)->result_array();
              
              if(!empty($itemDatesArr)){
                $itemDatesArr = array_column($itemDatesArr,'id');
                $itemDatesStr = implode(',',$itemDatesArr);
                $sql = 'DELETE '.ITEMSDATESDAY.', '.ITEMSDATESDAYSPRICE.'
                        FROM '.ITEMSDATESDAY.'
                        INNER JOIN '.ITEMSDATESDAYSPRICE.' ON '.ITEMSDATESDAYSPRICE.'.item_dates_day_id = '.ITEMSDATESDAY.'.id
                        WHERE '.ITEMSDATESDAY.'.item_dates_id IN('.$itemDatesStr.')';
                $del = $this->db->query($sql);
                if($del){
                  $op = array(
                    'table' => ITEMDATES,
                    'select'=>'id',
                    'where' => array('item_id' => $id)
                  );
                  $delte = $this->common_model->customDelete($op);
                  if($delte){
                      $option = array(
                        'table' => $table,
                        'where' => array($id_name => $id)
                      );
                    $delete = $this->common_model->customDelete($option);
                    if($delete){
                      $response = 200;
                    } 
                  }    
                }
              }else{
                $option = array(
                        'table' => $table,
                        'where' => array($id_name => $id)
                );
                $delete = $this->common_model->customDelete($option);
                if($delete){
                  $response = 200;
                }
              }
        }
        echo $response;
    }

    public function edit_status() {
        $response = "";
        $id = $this->input->post('id'); // delete id
        $status = $this->input->post('status'); 
        if($status == 1){
          $flag = 0;
        }
        if($status == 0){
          $flag = 1;
        }
        
        if (!empty($id)) { 
      
          $options_data = array('status' => $flag);
          $option = array(
              'table' => ALLACART,
              'data' => $options_data,
              'where' => array('id' => $id)
              );
          $update = $this->common_model->customUpdate($option);

          if($update) {
              $response = 200;
          } else
              $response = 400;
        }else {
            $response = 400;
        }
        echo $response;
    }

/***********************OFFER PRICE RELATED METHODS*******************************/

    /**********Offer List*********/

    public function setpricevariation($item_id=''){

      $this->data['parent'] = lang('food_items');
      $this->data['title'] = lang('allacart_offer_price');

      $this->data['week'] = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
      $item_id = decoding($item_id);
      $option = array(
        'table'   => ITEMDATES.' as idt',
        'select'  => 'idt.id,start_date,end_date,item_id,idy.id as item_dates_day_id,idy.day',
        'join'    => array(ITEMSDATESDAY.' as idy'=>'idy.item_dates_id = idt.id'),
        'where'   => array('idt.item_id ='=>$item_id,'idt.end_date >= '=>date('Y-m-d')),
        'order'   => array('idt.start_date' => 'DESC'),
        'group_by'=> 'idt.id'
      );
      $this->data['datesData'] = $this->common_model->customGet($option);
      $this->load->admin_render('setprice', $this->data, 'inner_script');
    }

    /******Validate Start date and end date*******/
    public function validate_dates(){
      $start_date = $this->input->post('start_date');
      $end_date = $this->input->post('end_date');
      $item_id = $this->input->post('item_id');
      $item_id = decoding($item_id);
      $opt = array(
          'table'   => ITEMDATES,
          'select'  => 'id',
          'where'   => array('item_id=' => $item_id,'end_date >=' => $start_date,'start_date<='=>$end_date),
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
      $this->form_validation->set_rules('start_date', lang('start_date'), 'required|trim');
      $this->form_validation->set_rules('end_date', lang('end_date'), 'required|trim');
      
      $item_id = $this->input->post('item_id');
        
      //keep it for redirect
      $itm_id = $item_id;
      
      if($this->form_validation->run() == true){
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
         
        
        $start_date   = $this->security->xss_clean($start_date);
        $end_date   = $this->security->xss_clean($end_date);
        $item_id = decoding($item_id);

        /********** Inser Data into ITEMDATES *********/
        $options_data = array(
              'item_id'     => $item_id,
              'start_date'  => $start_date,
              'end_date'    => $end_date
        );
        $option = array('table' => ITEMDATES, 'data' => $options_data);
        $itemdates_id = $this->common_model->customInsert($option); 
        
        /********** Inser Data into ITEMSDATESDAY *********/
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
            $this->session->set_flashdata('success', lang('price_success'));
            redirect('allacart/setpricevariation/'.$itm_id);
          } 
        }else{
            $this->session->set_flashdata('error', lang('price_failed'));
            redirect('allacart/setpricevariation/'.$itm_id);
        }
        
      }else {
        $messages = (validation_errors()) ? validation_errors() : '';
        $this->session->set_flashdata('error', $messages);
        redirect('allacart/setpricevariation/'.$itm_id);
      }  

    }

  /*Load accordian(dayaccordian.php) according to date difference and day*/

  public function getdatediff(){
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $item_id = $this->input->post('item_id');
    $item_id = decoding($item_id);
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
      'select'  => 'idy.id AS items_dates_day_id,idy.day',
      'join'    => array(ITEMSDATESDAY.' as idy'=>'idy.item_dates_id = idt.id'),
      'where'   => array('idt.id ='=>$item_dates_id)
    );
    $detail_data = $this->common_model->customGet($option);
    if(!empty($detail_data)){
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
    public function offerprice_edit($item_dates_id,$item_id){
      $this->data['title'] = 'Edit Offer Price';
      $item_dates_id = decoding($item_dates_id);
      $item_id = decoding($item_id);
      // echo $item_id;die;
      if (!empty($item_dates_id)) {
        $finalArr = array(); 
        $option = array(
          'table'   => ITEMDATES.' AS idt',
          'select'  => 'idt.start_date,idt.end_date,idy.id AS items_dates_day_id,idy.day',
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
          $this->data['item_id'] = encoding($item_id);
          $this->data['item_dates_id'] = encoding($item_dates_id);
          $this->data['start_date'] = encoding($detail_data[0]->start_date);
          $this->data['end_date'] = encoding($detail_data[0]->end_date);
          $this->data['days_Data'] = $finalArr;
          $this->load->admin_render('offerpriceedit', $this->data, 'inner_script');    
        }else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('allacart/setpricevariation/'.$item_id);
        }  
      }else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('allacart/setpricevariation/'.$item_id);
      }
    }


    /*Update Price - For particular start & end date*/

    public function offerprice_update(){

      if(!empty($this->input->post('days')) && (count($this->input->post('days')) > 1)){
        $item_id = $this->input->post('item_id');
        $itemdates_id = $this->input->post('item_dates_id');
        $flag = 0;
        if(!empty($itemdates_id)){
          $item_dates_id = decoding($itemdates_id);
          $days = $this->input->post('days');
          
          $option = array(
            'table'   => ITEMSDATESDAY,
            'select'  => 'id',
            'where'   => array('item_dates_id'=>$item_dates_id)
            );
          $itemsdaysidArr = $this->common_model->customGet($option);
          if(!empty($itemsdaysidArr)){
            $flag = 0;
            //Delete all time and price from ITEMSDATESDAYSPRICE
            /*$opt = array(
              'table'=>ITEMSDATESDAYSPRICE,
              'where'   => array('item_dates_id' => $item_dates_id)
              );
            $delete = $this->common_model->customDelete($opt);*/

            // $daycount = count($days);  
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
                        'end_time > '      => $start_time,
                        'start_time < '    => $end_time
                        // 'end_date >=' => $start_date,'start_date<='=>$end_date
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
                      $flag=1;      
                    }
                }
              }   
            }
              
          }
          if($flag == 1){
            $this->session->set_flashdata('success',lang('price_update_exist'));
            redirect('allacart/offerprice_edit/'.$itemdates_id.'/'.$item_id);
          }else if($itemdatesdaytime_id){
            $this->session->set_flashdata('success',lang('price_update_success'));
            redirect('allacart/offerprice_edit/'.$itemdates_id.'/'.$item_id);
          }else{
            $this->session->set_flashdata('success',lang('price_update_failed'));
            redirect('allacart/offerprice_edit/'.$itemdates_id.'/'.$item_id);
          }

        }
        
      }else{
        $this->session->set_flashdata('success',lang('price_update_failed'));
        redirect('allacart/offerprice_edit/'.$itemdates_id.'/'.$item_id);
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

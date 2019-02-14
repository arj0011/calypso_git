<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Foodparcel extends Common_Controller { 
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
     
      $this->data['parent'] = "Food Items";
      $this->data['title'] = "Food Parcel";
      // $this->db->select('*');  
      // $this->db->from(FOODPARCEL);
      // $this->db->order_by(FOODPARCEL.'.id','DESC');
      // $this->data['list'] = $this->db->get()->result();

      $option = array(
        'table'=>FOODPARCEL,
        'select'=>'*',
        'order'=>array('id'=>'DESC')
      );
      
      if ($_POST):
        if($this->input->post('statusfilter') != ''):

        $option['where']['status'] = ($this->input->post('statusfilter') == 1) ? 1 : 0;
        $this->data['statusfilter'] = ($this->input->post('statusfilter') == 1) ? 1 : 2;
        endif;

        if($this->input->post('start_date') != '' && $this->input->post('end_date') != ''):
        $option['where'] = array(
          'DATE(created_date) >= '=>date('Y-m-d',strtotime($this->input->post('start_date'))),
          'DATE(created_date) <='=>date('Y-m-d',strtotime($this->input->post('end_date')))
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

    // function open_model() {
    //   $this->data['title'] = lang("add_food_parcel");  
    //   $option =array(
    //     'table' => ALLACART,
    //     'select'=> '*',
    //     'where' => array('status'=>1)
    //     );
       
    //   $this->data['pack_items'] = $this->common_model->customGet($option);
    //   $this->load->view('add', $this->data);  
    // }

    function open_model() {
      $this->data['title'] = lang("add_food_parcel");  
      $option =array(
        'table' => ALLACART.' AS a',
        'select'=> 'a.*',
        'join'=>array(CATEGORY_MANAGEMENT.' AS cm'=>'cm.id = a.category_id'),
        'where' => array('cm.status'=>1,'a.status'=>1)
        );
       
      $this->data['pack_items'] = $this->common_model->customGet($option);
      $this->load->admin_render('add', $this->data, 'inner_script');
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

      $this->form_validation->set_rules('food_name', lang('food_name'), 'required|trim');
      $this->form_validation->set_rules('price', lang('price'), 'required|trim');
      $this->form_validation->set_rules('date', 'Date', 'required|trim');
      if(empty($_FILES['image']['name'])){
        $this->form_validation->set_rules('image', 'image', 'required');
      }
      if($this->form_validation->run() == true) {
        $this->filedata['status'] = 1;
        $image = "";
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'foodparcel', 'image');
          if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
          }
        }

        if ($this->filedata['status'] == 0) {
          $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{

          $current_date=date('Y-m-d');
          $user_id =array();
          
          $food_name = $this->input->post('food_name');
          $price = $this->input->post('price');
          $description = $this->input->post('description');
          $date = $this->input->post('date');
          
          $food_name = $this->security->xss_clean($food_name);
          $price   = $this->security->xss_clean($price);
          $description = $this->security->xss_clean($description);
          $date = $this->security->xss_clean($date);
          $date = date('Y-m-d',strtotime($date));
        
          $option = array('table' => FOODPARCEL,
           'select' => 'item_name',
           'where' => array('item_name'=> $food_name)
          );

          $category = $this->common_model->customGet($option);
          if(empty($category)){ 
              $options_data = array(
                'item_name'   => $food_name,
                'price'       => $price,
                'description' => $description,
                'image'       => $image,
                'created_date'=> $date,
                'status'      => 1,
              );


              
              /*if(!empty($this->input->post('pack_items'))){
                $parcel_items_id = implode(',',$this->input->post('pack_items'));  
                $options_data['food_items_id'] = $parcel_items_id;
              }*/

              $option = array('table' => FOODPARCEL, 'data' => $options_data);
              $foods = $this->common_model->customInsert($option); 

              if($foods) {

                if(isset($_POST['item']) && !empty($_POST['item'])){
                  $i=0;
                  foreach ($_POST['item'] as $value) {
                    $op = array(
                      'table'=>ALLACART,
                      'select'=>'category_id',
                      'where'=>array('id'=>$value),
                      'single'=>true
                    );
                    $catData = $this->common_model->customGet($op); 

                    $cate_id = $catData->category_id;
                    $parcelArr[] = array(
                      'parcel_id'=>$foods,
                      'item_id'=>$value,
                      'category_id'=>$cate_id,
                      'item_limit'=>$_POST['limit'][$i]
                    );
                    $i++;  
                    $catData = array();
                  }
                  $this->common_model->insertBulkData(PARCELITEMS,$parcelArr);
                    
                }

                $response = array('status' => 1, 'message' => lang('food_parcel_success'), 'url' => base_url('foodparcel'));
              }else{
                $response = array('status' => 0, 'message' => lang('food_parcel_failed'));
              }
          }else{
            $response = array('status' => 0, 'message' => lang('food_parcel_exist'));
          }
        }
      }else {
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
    // public function item_edit() {
    //   $this->data['title'] = lang("edit_food");
    //   $id = decoding($this->input->post('id'));
    //   if (!empty($id)) {
    //     $option = array(
    //       'table' => FOODPARCEL,
    //       'where' => array('id' => $id),
    //       'single' => true
    //       );
    //     $results_row = $this->common_model->customGet($option);
    //     if (!empty($results_row)) {
    //       $this->data['results'] = $results_row;
    //         $option =array(
    //           'table' => ALLACART,
    //           'select'=> '*',
    //           'where' => array('status'=>1)
    //           );
    //         $this->data['pack_items'] = $this->common_model->customGet($option);
    //         $this->load->view('edit', $this->data);  
          
    //     } else {
    //       $this->session->set_flashdata('error', lang('not_found'));
    //       redirect('foodparcel');
    //     }
    //   } else {
    //     $this->session->set_flashdata('error', lang('not_found'));
    //     redirect('foodparcel');
    //   }
    // }

    public function item_edit($id) {
      $this->data['title'] = lang("edit_food");
      $id = decoding($id);
      if (!empty($id)) {
        $option = array(
          'table' => FOODPARCEL,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
            $opt =array(
              'table' => ALLACART.' AS a',
              'select'=> 'a.*',
              'join'=>array(CATEGORY_MANAGEMENT.' AS cm'=>'cm.id = a.category_id'),
              'where' => array('cm.status'=>1,'a.status'=>1)
              );
            $itemsArr = $this->common_model->customGet($opt);

            $options =array(
                  'table' => PARCELITEMS.' AS pi',
                  'select'=> 'pi.item_id,pi.item_limit,a.item_name',
                  'join'=>array(array(ALLACART.' AS a'=>'a.id=pi.item_id'),array(CATEGORY_MANAGEMENT.' AS c'=>'c.id=pi.category_id')),
                  'where' => array('pi.parcel_id'=>$id,'c.status'=>1)
                );
            
            $this->data['pack_items'] = $this->common_model->customGet($options);
            $this->data['items_data'] = $itemsArr;
          
            $this->load->admin_render('edit', $this->data, 'inner_script');  
          
        } else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('foodparcel');
        }
      } else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('foodparcel');
      }
    }


    /**
     * @method cms_update
     * @description update dynamic rows
     * @return array
     */
    public function item_update() {
      $image = $this->input->post('exists_image');
      $this->form_validation->set_rules('food_name', lang('food_name'), 'required|trim');
      $this->form_validation->set_rules('price', lang('price'), 'required|trim');
      $this->form_validation->set_rules('date', 'Date', 'required|trim');
      if(empty($image)){
        if (empty($_FILES['image']['name'])) {
          $this->form_validation->set_rules('image', 'image', 'required');
        }

      }

      $where_id = decoding($this->input->post('id'));

      if ($this->form_validation->run() == FALSE):
        $messages = (validation_errors()) ? validation_errors() : '';
        $response = array('status' => 0, 'message' => $messages);
      else:
        $this->filedata['status'] = 1;
        $image = $this->input->post('exists_image');
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'foodparcel', 'image');
          if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
            delete_file($this->input->post('exists_image'), FCPATH."uploads/foodparcel/");
          }
        }
        if ($this->filedata['status'] == 0) {
          $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{

          $food_name = $this->input->post('food_name');
          $price = $this->input->post('price');
          $description = $this->input->post('description');
          $date = $this->input->post('date');
          
          $food_name = $this->security->xss_clean($food_name);
          $price   = $this->security->xss_clean($price);
          $description = $this->security->xss_clean($description);
          $date = $this->security->xss_clean($date);
          $date = date('Y-m-d',strtotime($date));;
          
          
          $option = array('table' => FOODPARCEL,
            'select' => 'item_name',
            'where' => array('id !='=>$where_id ,'item_name'=> $food_name)
          );
          $foods = $this->common_model->customGet($option);
          if(empty($foods)){
              $options_data = array(
                'item_name'      => $food_name,
                'price'          => $price,
                'description'    => $description,
                'image'          => $image,
                'created_date'   => $date

              );

              //in case of food parcel
              /*if(!empty($this->input->post('pack_items'))){
                $food_items_id = implode(',',$this->input->post('pack_items'));  
                $options_data['food_items_id'] = $food_items_id;
              }*/

              $option = array(
                'table' => FOODPARCEL,
                'data' => $options_data,
                'where' => array('id' => $where_id)
                );
              
              $update = $this->common_model->customUpdate($option);
              
              $opt = array(
                'table' => PARCELITEMS,
                'where' => array('parcel_id' => $where_id)
              );
              $this->common_model->customDelete($opt);

              $i=0;
              foreach ($_POST['item'] as $value) {
                $op = array(
                      'table'=>ALLACART,
                      'select'=>'category_id',
                      'where'=>array('id'=>$value),
                      'single'=>true
                    );
                $catData = $this->common_model->customGet($op);  
                $cate_id = $catData->category_id;
                
                $parcelArr[] = array(
                  'parcel_id'=>$where_id,
                  'item_id'=>$value,
                  'category_id'=>$cate_id,
                  'item_limit'=>$_POST['limit'][$i]
                );
                $i++;  
                $catData = array();
              }
              $this->common_model->insertBulkData(PARCELITEMS,$parcelArr);

              $response = array('status' => 1, 'message' => lang('food_success_update'), 'url' => base_url('foodparcel'));
          }else{
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
              
              $opt = array(
                'table' => PARCELITEMS,
                'where' => array('parcel_id' => $id)
              );
              $this->common_model->customDelete($opt);
              
              $response = 200;

            } else
                $response = 400;
        }else {
            $response = 400;
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
              'table' => FOODPARCEL,
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




    public function get_all_items(){
      $data = array();
      $this->data['count'] = $this->input->post('count');
      
      if(!empty($_POST['globalCatgoryArr'])):
        $str = '';
        foreach($_POST['globalCatgoryArr'] as $val):
          if($val != '' && $val != null){
            $str .= $val.',';
          }
          endforeach;
          $str = rtrim($str,',');  
      endif;
      $sql = "SELECT a.* 
              FROM ".ALLACART." AS a 
              JOIN ".CATEGORY_MANAGEMENT." AS c ON c.id = a.category_id
              WHERE a.id NOT IN(".$str.") AND c.status = 1 AND a.status = 1";
      $query = $this->db->query($sql);
      $this->data['pack_items'] = $query->result();
      $this->data['count'] = $this->input->post('count');
      if(!empty($this->data['pack_items'])){
        // $this->load->view('all_items_dropdown', $this->data); 
        $this->load->view('all_items_dropdown', $this->data); 
      }
    }

}

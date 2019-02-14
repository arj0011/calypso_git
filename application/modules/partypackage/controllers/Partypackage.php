<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Partypackage extends Common_Controller { 
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
      $this->data['title'] = "Party Package";
      $option = array('table'=>PARTYPACKAGE,'order'=>array('id'=>'DESC'));

      if ($_POST):
        if($this->input->post('statusfilter') != ''):

        $option['where']['status'] = ($this->input->post('statusfilter') == 1) ? 1 : 0;
        $this->data['statusfilter'] = ($this->input->post('statusfilter') == 1) ? 1 : 2;
        endif;

        if($this->input->post('start_date') != '' && $this->input->post('end_date') != ''):
        $option['where'] = array(
          'DATE(created) >= '=>date('Y-m-d',strtotime($this->input->post('start_date'))),
          'DATE(created) <='=>date('Y-m-d',strtotime($this->input->post('end_date')))
          );
        $this->data['start_date'] = $this->input->post('start_date');
        $this->data['end_date'] = $this->input->post('end_date');
        endif;
      endif;

      $results_details = $this->common_model->customGet($option);
      
      if(!empty($results_details)){
        foreach($results_details as $row){
          $sql = "SELECT cm.id,cm.category_name,pc.items_id,pc.item_limit
                  FROM ".CATEGORY_MANAGEMENT." AS cm
                  INNER JOIN ".PACKAGECATEGORY." AS pc ON pc.category_id = cm.id
                  WHERE pc.package_id = $row->id";
          $query = $this->db->query($sql);
          $details = $query->result();

          if(!empty($details)){
            foreach($details as $detail){
              $itemID = $detail->items_id;
              $q = "SELECT id,item_name FROM ".ALLACART." WHERE id IN($itemID)";
              $qr = $this->db->query($q);
              $itemdata = $qr->result_array();
              $detail->itemdata = $itemdata;
            }
          }
          $row->category_data[] = $details;
        } 
      }
      $this->data['list'] = $results_details;
     // p($this->data['list']);
      $this->load->admin_render('list', $this->data, 'inner_script');
    }


    /**
     * @method open_model
     * @description load model box
     * @return array
     */

    /*function open_model() {
      $this->data['title'] = lang("add_party_package");
      $this->data['category'] = $this->db->get(CATEGORY_MANAGEMENT)->result();
      $this->load->view('add', $this->data);  
    }*/

    public function open_modal()
    {
      $this->data['title'] = lang("add_party_package");
      $this->data['category'] = $this->db->get_where(CATEGORY_MANAGEMENT,array('status'=>1))->result();
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
    
      $this->form_validation->set_rules('food_name', lang('package_name'), 'required|trim');
      // $this->form_validation->set_rules('description', lang('package_description'), 'required|trim');
      $this->form_validation->set_rules('partial_payment', lang('partial_payment'), 'required|trim');
      $this->form_validation->set_rules('strike_price', lang('strike_price'), 'required|trim');
      $this->form_validation->set_rules('price', lang('package_price'), 'required|trim');
      $this->form_validation->set_rules('gender[]', lang('gender_pref'), 'required|trim');
      $this->form_validation->set_rules('min_age', lang('age_min'), 'required|trim|numeric');
      $this->form_validation->set_rules('max_age', lang('age_max'), 'required|trim|numeric');
      $this->form_validation->set_rules('min_person', lang('min_person'), 'required|trim|numeric');
      // $this->form_validation->set_rules('image','Image', 'required');
      $this->form_validation->set_rules('category[]','Category', 'required');
      $this->form_validation->set_rules('limit[]','Limit', 'required');
      $this->form_validation->set_rules('itemids[]','Items', 'required');
      if(empty($_FILES['image']['name'])){
        $this->form_validation->set_rules('image', 'image', 'required');
      }
      if($this->form_validation->run() == true) {
        
        $this->filedata['status'] = 1;
        $image = "";
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'partypackage', 'image');
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
          $strike_price = $this->input->post('strike_price');
          $partial_payment = $this->input->post('partial_payment');
          
          $price = $this->input->post('price');
          $description = $this->input->post('description');
          
          $genstr = implode(',', $this->input->post('gender'));
          
          $min_age = $this->input->post('min_age');
          $max_age = $this->input->post('max_age');
          $min_person = $this->input->post('min_person');
          
          $food_name = $this->security->xss_clean($food_name);
          $strike_price   = $this->security->xss_clean($strike_price);
          $partial_payment   = $this->security->xss_clean($partial_payment);
          $min_person   = $this->security->xss_clean($min_person);
          
          $price   = $this->security->xss_clean($price);
          $description = $this->security->xss_clean($description);
        
          $option = array('table' => PARTYPACKAGE,
           'select' => 'item_name',
           'where' => array('item_name'=> $food_name)
          );

          $packagedata = $this->common_model->customGet($option);
          if(empty($packagedata)){  
              $options_data = array(
                'item_name'   => $food_name,
                'strike_price'=> $strike_price,
                'partial_payment'=> $partial_payment,
                'price'       => $price,
                'description' => $description,
                'min_person'  => $min_person,
                'image'       => $image,
                'gender_pref' => $genstr,
                'min_age'     => $min_age,
                'max_age'     => $max_age,
                'created'=> datetime(),
                'status'      => 1,
              );
              
              if(isset($_POST['discount']) && $_POST['discount'] != ''):
                $discount = $this->input->post('discount');
                $discount   = $this->security->xss_clean($discount);
                $options_data['discount'] = $discount;
              endif; 

              $option = array('table' => PARTYPACKAGE, 'data' => $options_data);
              $package_id = $this->common_model->customInsert($option); 
              
              if($package_id) {
                /*Insert Data into package category table*/
                $category = $this->input->post('category');
                $limit = $this->input->post('limit');
                $itemids = $this->input->post('itemids');
               
                $i=0;
                if(!empty($category)){
                  foreach($category as $val){
                    $itmstr = '';
                    foreach($itemids[$val] as $v){
                      $itmstr .= $v.',';
                    }
                    $itemstr = rtrim($itmstr,',');
                    $insertArr[] = array(
                      'package_id'=>$package_id,
                      'category_id'=>$val,
                      'items_id'=> $itemstr,
                      'item_limit'=>$limit[$i]
                    );
                    $i++;
                  }
                  
                  $package_category = $this->common_model->insertBulkData(PACKAGECATEGORY,$insertArr);   
                }
                //end

                $response = array('status' => 1, 'message' => lang('party_package_success'), 'url' => base_url('partypackage'));
              }else {
                $response = array('status' => 0, 'message' => lang('party_package_failed'));
              }
          }else{
            $response = array('status' => 0, 'message' => lang('party_package_exist'));
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
    /*public function item_edit() {
      $this->data['title'] = lang("edit_party_package");
      $id = decoding($this->input->post('id'));
      if (!empty($id)) {

        $query = "select * from category_management where id NOT IN(select parent_id from category_management)";
       $this->data['category'] =  $this->common_model->customQuery($query);

        $option = array(
          'table' => PARTYPACKAGE,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
          $opt = array(
          'table' => PACKAGECATEGORY,
          'where' => array('package_id' => $id)
          );
          $category_detail = $this->common_model->customGet($opt);
          foreach ($category_detail as $cat) {
            $options = array(
              'select' => 'id as item_id,item_name',
              'table' => ALLACART,
              'where' => array('category_id'=>$cat->category_id,'status'=>1)
              );
            $cat_itemsArr = $this->common_model->customGet($options);
            $cat->item_array = $cat_itemsArr;
          }
          
          $this->data['category_detail'] = $category_detail;
          $this->data['category_count'] = count($category_detail);
          $this->load->view('edit', $this->data);  
        } else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('partypackage');
        }
      } else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('partypackage');
      }
    }*/


    public function item_edit($id) {

      $this->data['title'] = lang("edit_party_package");
      $id = decoding($id);
      if (!empty($id)) {

        $query = "select * from category_management where status = 1";
       $this->data['category'] =  $this->common_model->customQuery($query);

        $option = array(
          'table' => PARTYPACKAGE,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
          $opt = array(
          'table' => PACKAGECATEGORY.' AS pc',
          'select'=>'pc.*',
          'join'=>array(CATEGORY_MANAGEMENT.' AS cm'=>'cm.id = pc.category_id'),
          'where' => array('pc.package_id' => $id,'cm.status'=>1)
          );
          $category_detail = $this->common_model->customGet($opt);
         
          foreach ($category_detail as $cat) {
            $options = array(
              'select' => 'id as item_id,item_name',
              'table' => ALLACART,
              'where' => array('category_id'=>$cat->category_id,'status'=>1)
              );
            $cat_itemsArr = $this->common_model->customGet($options);
            $cat->item_array = $cat_itemsArr;
          }
          //p($category_detail);
          $this->data['package_id'] = encoding($id);
          $this->data['category_detail'] = $category_detail;
          $this->data['category_count'] = count($category_detail);
      
          $this->load->admin_render('edit', $this->data, 'inner_script');  
        } else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('partypackage');
        }
      } else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('partypackage');
      }
    }


    /**
     * @method cms_update
     * @description update dynamic rows
     * @return array
     */
    public function item_update() {
      $image = $this->input->post('exists_image');
      $this->form_validation->set_rules('food_name', lang('package_name'), 'required|trim');
      // $this->form_validation->set_rules('description', lang('package_description'), 'required|trim');
      $this->form_validation->set_rules('strike_price', lang('strike_price'), 'required|trim');
      $this->form_validation->set_rules('partial_payment', lang('partial_payment'), 'required|trim');
      $this->form_validation->set_rules('price', lang('package_price'), 'required|trim');
      $this->form_validation->set_rules('gender[]', lang('gender_pref'), 'required|trim');
      $this->form_validation->set_rules('min_age', lang('age_min'), 'required|trim|numeric');
      $this->form_validation->set_rules('max_age', lang('age_max'), 'required|trim|numeric');
      $this->form_validation->set_rules('min_person', lang('min_person'), 'required|trim|numeric');
      $this->form_validation->set_rules('category[]','Category', 'required');
      $this->form_validation->set_rules('limit[]','Limit', 'required');
      $this->form_validation->set_rules('itemids[]','Items', 'required');
      if(empty($image)){
        if (empty($_FILES['image']['name'])) {
          $this->form_validation->set_rules('image', 'image', 'required');
        }

      }

      $where_id = decoding($this->input->post('package_id'));
      
      
      if($this->form_validation->run() == true) {
        
        $this->filedata['status'] = 1;
        $image = $this->input->post('exists_image');
        if(!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'partypackage', 'image');
          if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
            delete_file($this->input->post('exists_image'), FCPATH."uploads/partypackage/");
          }
        }
        
        if ($this->filedata['status'] == 0) {
          $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{
          
          $food_name = $this->input->post('food_name');
          $description = $this->input->post('description');
          $strike_price = $this->input->post('strike_price');
          $partial_payment = $this->input->post('partial_payment');
          
          $price = $this->input->post('price');
          $gender = $this->input->post('gender');
          $min_age = $this->input->post('min_age');
          $max_age = $this->input->post('max_age');
          $min_person = $this->input->post('min_person');
          
          $food_name = $this->security->xss_clean($food_name);
          $description = $this->security->xss_clean($description);
          $strike_price   = $this->security->xss_clean($strike_price);
          $partial_payment   = $this->security->xss_clean($partial_payment);
          $price   = $this->security->xss_clean($price);
          $min_age   = $this->security->xss_clean($min_age);
          $max_age   = $this->security->xss_clean($max_age);
          $min_person   = $this->security->xss_clean($min_person);
          
          $option = array('table' => PARTYPACKAGE,
            'select' => 'item_name',
            'where' => array('id !='=>$where_id ,'item_name'=> $food_name)
          );
          $partypackageData = $this->common_model->customGet($option);
          if(empty($partypackageData)){
            $genderstr = implode(',',$gender);
            $options_data = array(
                'item_name'      => $food_name,
                'description'    => $description,
                'strike_price'   => $strike_price,
                'partial_payment'=> $partial_payment,
                'strike_price'   => $strike_price,
                'price'          => $price,
                'image'          => $image,
                'min_age'        => $min_age,
                'max_age'        => $max_age,
                'min_person'     => $min_person,
                'gender_pref'    => $genderstr
              );
            if(isset($_POST['discount']) && $_POST['discount'] != ''):
              $discount = $this->input->post('discount');
              $discount   = $this->security->xss_clean($discount);
              $options_data['discount'] = $discount;
            endif;  
            $option = array(
                'table' => PARTYPACKAGE,
                'data' => $options_data,
                'where' => array('id' => $where_id)
                );
              
            $update = $this->common_model->customUpdate($option);
            $package_category = 0;
              $category = $this->input->post('category');
                $limit = $this->input->post('limit');
                $itemids = $this->input->post('itemids');
               
                $i=0;
                if(!empty($category)){

                  foreach($category as $val){
                    $catDATA = array();

                    //check package and category exist in PACKAGECATEGORY table
                    $op = array('table' => PACKAGECATEGORY,
                      'select' => 'id',
                      'where' => array('package_id'=>$where_id ,'category_id'=> $val)
                    );

                    $catDATA = $this->common_model->customGet($op);

                    $itmstr = '';
                    if(!empty($itemids[$val])){
                      foreach($itemids[$val] as $v){
                        $itmstr .= $v.',';
                      }
                      $itemstr = rtrim($itmstr,',');
                      $updateArr = array();
                      $insertArr = array();
                      $opt = array();
                      //echo $itemstr;
                      //print_r($catDATA);die;
                      if(!empty($catDATA)){
                        if($itemstr != ''){
                          $updateArr = array(
                            'items_id'=> $itemstr,
                            'item_limit'=>$limit[$i]
                          );  
                          
                          $opt = array(
                            'table'=>PACKAGECATEGORY,
                            'data'=>$updateArr,
                            'where'=>array('package_id'=>$where_id,'category_id'=>$val)
                          );
                          $package_category = $this->common_model->customUpdate($opt);    
                        }
                        
                      }else{
                        if($itemstr != ''){
                          $insertArr = array(
                            'package_id'=>$where_id,
                            'category_id'=>$val,
                            'items_id'=> $itemstr,
                            'item_limit'=>$limit[$i]
                          );  
                          
                          $opt = array(
                            'table'=>PACKAGECATEGORY,
                            'data'=>$insertArr,
                          );
                          $package_category = $this->common_model->customInsert($opt);  
                        }
                          
                      }
                    }

                    $i++;
                  }
                  
                }
                //end
                if($package_category || $update){
                  $response = array('status' => 1, 'message' => lang('party_package_edit_success'), 'url' => base_url('partypackage'));
                }else{
                  $response = array('status' => 0, 'message' => lang('party_package_edit_failed'));    
                }
          }else{
            $response = array('status' => 0, 'message' => lang('party_package_edit_exist'));
          }
        }
      }else{
        $messages = (validation_errors()) ? validation_errors() : '';
        $response = array('status' => 0, 'message' => $messages);
      }
      
      echo json_encode($response);
    }

    //Delete package category only
    public function delpackagecategory(){
      $response = "";
        $package_id = $this->input->post('package_id'); 
        $category_id = $this->input->post('category_id'); 
        if (!empty($category_id) && !empty($package_id)) { 
            $package_id = decoding($package_id);
            $option = array(
                'table' => PACKAGECATEGORY,
                'where' => array('package_id' => $package_id,'category_id' => $category_id)
            );
            $delete = $this->common_model->customDelete($option);
            if ($delete) {
                $response = 200;
            }else{
              $response = 400;
            }
        }else {
          $response = 400;
        }
        echo $response;
    }




  //Complete delete party package with it's category  
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
                'table' => PACKAGECATEGORY,
                'where' => array('package_id' => $id)
            );
            $del = $this->common_model->customDelete($opt);  
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
              'table' => PARTYPACKAGE,
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


    public function get_items_by_catid(){
      $data = array();
      $category_id = $this->input->post('id');
      //print_r($category_id);die;
      $this->db->select('id,item_name');
      $data['data'] = $this->db->get_where(ALLACART,array('category_id'=>$category_id,'status'=>1))->result();
      if(!empty($data['data'])){
        $this->data['cat_items'] = $data['data'];
        $this->data['count'] = $this->input->post('count');
        $this->data['categoryID'] = $category_id;
        $this->load->view('cat_items_dropdown', $this->data); 
      }
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
      $sql = "SELECT * FROM ".CATEGORY_MANAGEMENT." WHERE id NOT IN(".$str.") AND status = 1";
      $query = $this->db->query($sql);
      $this->data['category'] = $query->result();
      $this->data['count'] = $this->input->post('count');
      if(!empty($this->data['category'])){
        // $this->load->view('all_items_dropdown', $this->data); 
        $this->load->view('all_items_dropdown', $this->data); 
      }
    }


}

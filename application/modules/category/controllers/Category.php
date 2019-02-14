<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Common_Controller { 
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
      $this->data['parent'] = "Category";
      $this->data['title'] = "Category";
      // p($_POST);
      $option = array(
        'table' => CATEGORY_MANAGEMENT,
        'select' => '*',
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
      $list = $this->data['list'];
      $hrList = $this->listParent($list);
      $this->data['list'] = $this->hierarchicalRenderer($hrList);
      $this->load->admin_render('list', $this->data, 'inner_script');
    }
    public function hierarchicalRenderer($list,$level=0)
    {	$element = [];
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
     * @method open_model
     * @description load model box
     * @return array
     */

    function open_model() {
      $this->data['title'] = lang("add_category");
      $option = array('table' => CATEGORY_MANAGEMENT,
        'select' => '*'
        );

      $this->data['results'] = $this->common_model->customGet($option);
      $list = $this->data['results'];
      $hrList = $this->listParent($list);
      $this->data['results'] = $this->hierarchicalRenderer($hrList);
      $this->load->view('add', $this->data);
    }


    /**
     * @method cms_add
     * @description add dynamic rows
     * @return array
     */
    public function category_add() {

      $this->form_validation->set_rules('category_name', lang('category_name'), 'required|trim');
      if(empty($_FILES['image']['name'])){
        $this->form_validation->set_rules('image', 'image', 'required');
      }
      if ($this->form_validation->run() == true) {

        
        $this->filedata['status'] = 1;
        $image = "";
        if (!empty($_FILES['image']['name'])) {
          $this->filedata = $this->commonUploadImage($_POST, 'category', 'image');
          if ($this->filedata['status'] == 1) {
           $image = $this->filedata['upload_data']['file_name'];

         }

       }

       if ($this->filedata['status'] == 0) {
         $response = array('status' => 0, 'message' => $this->filedata['error']);  
       }else{

        // $parent_id = $this->input->post('category_id');
        $category_name = $this->input->post('category_name');
        $date = $this->input->post('date');
        // $parent_id     = $this->security->xss_clean($parent_id);
        $category_name = $this->security->xss_clean($category_name);
        $date = $this->security->xss_clean($date);
        $date = date('Y-m-d',strtotime($date));

        // if(empty($parent_id))
        // {
        //   $parent_id =0;
        // }
         $option = array('table' => CATEGORY_MANAGEMENT,
           'select' => 'category_name',
           'where' => array('category_name'=> $category_name)
        );

        $category = $this->common_model->customGet($option);
        if(empty($category))
       {
        $options_data = array(

          'category_name'  => $this->input->post('category_name'),
          // 'parent_id'      => $parent_id,
          'image'          => $image,
          'created_date'   => $date,
          'status'      => 1,
          );

        $option = array('table' => CATEGORY_MANAGEMENT, 'data' => $options_data);
        if ($this->common_model->customInsert($option)) {


          $response = array('status' => 1, 'message' => lang('category_success'), 'url' => base_url('category'));

        }else {
          $response = array('status' => 0, 'message' => lang('category_failed'));
        } 
      }else{
          $response = array('status' => 0, 'message' => lang('category_exist'));
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
    public function category_edit() {
      $this->data['title'] = lang("edit_category");
      $id = decoding($this->input->post('id'));
      if (!empty($id)) {

        $option = array('table' => CATEGORY_MANAGEMENT,
          'select' => '*'
          );
        
        $this->data['category'] = $this->common_model->customGet($option);

         $list = $this->data['category'];
         $hrList = $this->listParent($list);
        $this->data['category'] = $this->hierarchicalRenderer($hrList);

        $option = array(
          'table' => CATEGORY_MANAGEMENT,
          'where' => array('id' => $id),
          'single' => true
          );
        $results_row = $this->common_model->customGet($option);
        if (!empty($results_row)) {
          $this->data['results'] = $results_row;
          $this->load->view('edit', $this->data);
        } else {
          $this->session->set_flashdata('error', lang('not_found'));
          redirect('category');
        }
      } else {
        $this->session->set_flashdata('error', lang('not_found'));
        redirect('category');
      }
    }

    /**
     * @method cms_update
     * @description update dynamic rows
     * @return array
     */
    public function category_update() {
      $image = $this->input->post('exists_image');
      $this->form_validation->set_rules('category_name', lang('category_name'), 'required|trim');
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
        $this->filedata = $this->commonUploadImage($_POST, 'category', 'image');

        if ($this->filedata['status'] == 1) {
         $image = $this->filedata['upload_data']['file_name'];
         delete_file($this->input->post('exists_image'), FCPATH."uploads/category/");


       }

     }

     if ($this->filedata['status'] == 0) {
      $response = array('status' => 0, 'message' => $this->filedata['error']);  
    }else{
      // $parent_id = $this->input->post('category_id');
      $category_name = $this->input->post('category_name');
      $date = $this->input->post('date');
      // $parent_id     = $this->security->xss_clean($parent_id);
      $category_name = $this->security->xss_clean($category_name);
      $date = $this->security->xss_clean($date);
      $date = date('Y-m-d',strtotime($date));

      // if(empty($parent_id))
      // {
      //   $parent_id =0;
      // }
       $option = array('table' => CATEGORY_MANAGEMENT,
           'select' => 'category_name',
           'where' => array('id !='=>$where_id ,'category_name'=> $category_name)
        );

        $category = $this->common_model->customGet($option);
        if(empty($category))
       {
        $options_data = array(

        'category_name' => $category_name,
        'image'         => $image,
        'created_date'  => $date,
        // 'parent_id'     => $parent_id

        );
      $option = array(
        'table' => CATEGORY_MANAGEMENT,
        'data' => $options_data,
        'where' => array('id' => $where_id)
        );
      $update = $this->common_model->customUpdate($option);
      $response = array('status' => 1, 'message' => lang('category_success_update'), 'url' => base_url('category'));
    }  
   else{
      $response = array('status' => 0, 'message' => lang('category_exist'));
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
                'table' => CATEGORY_MANAGEMENT,
                'where' => array('parent_id' => $id)
            );
            $parents =  $this->common_model->customGet($option);
          
           foreach($parents as $parent)
           {
             $parent_id = $parent->parent_id;
             $options_data = array(

               'parent_id' => 0,
        
            );
              $option = array(
                'table' => CATEGORY_MANAGEMENT,
                'data' => $options_data,
                'where' => array('parent_id' => $parent_id)
                );
              $update = $this->common_model->customUpdate($option);
           }

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

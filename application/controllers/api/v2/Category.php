<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for category
 * @package   CodeIgniter
 * @category  Controller
 * @author    Preeti Birle
 */
class Category extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }


    /**
     * Function Name: category_list
     * Description:   To Get all Categories
     */

    function category_list_post()
    {
      $cat =array();
      $data = $this->input->post();
      $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
      if($this->form_validation->run() == FALSE) {
          $error = $this->form_validation->rest_first_error_string();
          echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {
        $page_no    = extract_value($data,'page_no',1);  
        $offset     = get_offsets($page_no);
        $upload_url = base_url().'uploads/category/';
        $options = array(
            'table' => CATEGORY_MANAGEMENT,
            'select' => '*',
            'where'=>array('status'=>1),
            'order' => array('category_name' => 'asc'),
            'limit' => array(10 => $offset)
          );

         
        $list = $this->common_model->customGet($options);
        
        if(!empty($list)){
          $eachArr = array();
          $total_requested = (int) $page_no * 10; 

          // Get total records   
          $total_records = getAllCount(CATEGORY_MANAGEMENT,array('status'=>1));

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
            $temp['id']             = null_checker($rows->id);
            $temp['category_name']  = null_checker($rows->category_name);
            $temp['status']         = null_checker($rows->status);
            $temp['created_date']   = null_checker($rows->created_date);
            $temp['image']          = $image;

            $eachArr[] = $temp;
          endforeach;

          echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Category found successfully']);

        }
        else{
          echo $this->jsonresponse->geneate_response(1, 0,'Category not found',[]);
        }
      }
    }

  }


   /* End of file Category.php */
   /* Location: ./application/controllers/api/v1/Category.php */
   ?>
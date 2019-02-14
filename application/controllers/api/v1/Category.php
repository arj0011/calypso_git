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
     $upload_url = base_url().'uploads/category/';

      $select = 'select id,category_name,parent_id,CONCAT(\''.$upload_url.'\',image) as image,status,created_date from category_management';
      $list =  $this->common_model->customQuery($select);
     
     /* To Get all category hierarchy */
      $cat = $this->listParent($list);
      
      if(!empty($list))
      {

        echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$cat,'message'=>'Category found successfully']);

      }
      else{
        echo $this->jsonresponse->geneate_response(1, 0,'Category not found',[]);
      }
    }

    /**
     * Function Name: product_list
     * Description:   To Get all Products
     */

    function product_list_post() {
      $data = $this->input->post();


      $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
      $this->form_validation->set_rules('category_id', 'Category Id', 'trim|required');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {

        $page_no    = extract_value($data,'page_no',1);  
        $offset     = get_offsets($page_no);
        $category_id  = extract_value($data, 'category_id', '');
        $upload_url = base_url().'uploads/product/';
        $current_date = datetime('Y-m-d');

        $options = array('table' => PRODUCTS . ' as product',
          'select' => 'product.id,product.product_name,product.price,product.image,product.description,product.launching_date',
          'order' => array('product.id' => 'desc'),
          'where'=> array('category_id'=> $category_id,
                        'product.launching_date<='=>$current_date
                          ),
          'limit' => array(10 => $offset)

          );


        /* To get product list from products table */
        $list = $this->common_model->customGet($options);

        /* check for image empty or not */

        if (!empty($list)) {

          $eachArr = array();

          $total_requested = (int) $page_no * 10; 

          /* Get total records */  
          $total_records = getAllCount(PRODUCTS,array('category_id'=>$category_id,'launching_date<='=>$current_date));

          if($total_records > $total_requested){                      
            $has_next = TRUE;                    
          }else{                        
            $has_next = FALSE;                    
          }

          foreach ($list as $rows):
            if(!empty($rows->image))
            {
              $image = $upload_url.$rows->image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
            $temp['id']               = null_checker($rows->id);
            $temp['product_name']     = null_checker($rows->product_name);
            $temp['description']      = null_checker($rows->description);
            $temp['price']            = null_checker($rows->price);
            $temp['launching_date']   = null_checker(convertDate($rows->launching_date));
            $temp['image']       = $image;

            $eachArr[] = $temp;
            endforeach;
            /* return success response*/

            echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Products found successfully']);

          }else {
           echo $this->jsonresponse->geneate_response(1, 0,'Product not found',[]);
         }
       }

     }

    /**
     * Function Name: product_details
     * Description:   To Get Product Details
     */

     function product_details_post() {
        $data = $this->input->post();
      
        $product_id  = extract_value($data, 'product_id', '');
        $qr_code     = extract_value($data, 'qr_code', '');

        $upload_url = base_url().'uploads/product/';
        /* To Get Product details using product id */
        if(!empty($product_id))
        {
          $options = array('table' => PRODUCTS . ' as product',
          'select' => 'product.id,product.product_name,product.price,product.image,product.description,product.launching_date',
          'order' => array('product.id' => 'desc'),
          'where'=> array('product.id'=> $product_id),
          'single'=> true
          
          );
        }
        /* To Get Product details using product QR Code */
        else
        {
          $options = array('table' => PRODUCTS . ' as product',
          'select' => 'product.id,product.product_name,product.price,product.image,product.description,product.launching_date',
          'order' => array('product.id' => 'desc'),
          'where'=> array('product.qr_code'=> $qr_code),
          'single'=> true
          
          );
        }
        
        /* To get product from products table */
        $list = $this->common_model->customGet($options);
        
        /* check for image empty or not */
        
        if (!empty($list)) {

         
            if(!empty($list->image))
            {
              $image = $upload_url.$list->image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
            $temp['id']               = null_checker($list->id);
            $temp['product_name']     = null_checker($list->product_name);
            $temp['description']      = null_checker($list->description);
            $temp['price']            = null_checker($list->price);
            $temp['launching_date']   = null_checker(convertDate($list->launching_date));
            $temp['image']            = $image;
            
            /* return success response*/

            echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$temp,'message'=>'Product Details found successfully']);
            
          }else {
           echo $this->jsonresponse->geneate_response(1, 0,'Product Details not found',[]);
         }
       
     }

    /**
     * Function Name: prelaunch_product
     * Description:   To Get Prelaunch Products List
     */

     function prelaunch_product_post() {
       $data = $this->input->post();


      $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {

        $page_no    = extract_value($data,'page_no',1);  
        $offset     = get_offsets($page_no);
        $product_type  = extract_value($data, 'product_type', '');
        $upload_url = base_url().'uploads/product/';
        $current_date = datetime('Y-m-d');
       
        
        $options = array('table' => PRODUCTS . ' as product',
          'select' => 'product.id,product.product_name,product.price,product.image,product.description,product.launching_date,product.product_type,product.prelaunch_date',
          'order' => array('product.launching_date' => 'desc'),
          'where'=> array('product.launching_date >'=> $current_date),
          'limit' => array(10 => $offset)

          );


        /* To get prelaunch product list from products table */
        $list = $this->common_model->customGet($options);
        /* check for image empty or not */

        if (!empty($list)) {

          $eachArr = array();

          $total_requested = (int) $page_no * 10; 

          /* Get total records */  
          $total_records = getAllCount(PRODUCTS,array('launching_date >'=>$current_date));

          if($total_records > $total_requested){                      
            $has_next = TRUE;                    
          }else{                        
            $has_next = FALSE;                    
          }

          foreach ($list as $rows):
            if(!empty($rows->image))
            {
              $image = $upload_url.$rows->image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
            $temp['id']               = null_checker($rows->id);
            $temp['product_name']     = null_checker($rows->product_name);
            $temp['description']      = null_checker($rows->description);
            $temp['launching_date']   = null_checker(convertDate($rows->launching_date));
            $temp['price']            = null_checker($rows->price);
            $temp['image']       = $image;

            $eachArr[] = $temp;
            endforeach;
            /* return success response*/

            echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Products found successfully']);

          }else {
           echo $this->jsonresponse->geneate_response(1, 0,'Product not found',[]);
         }
       }

     }

    /**
     * Function Name: premium_specialProduct
     * Description:   To Get Premium Special Products List
     */

    function premium_specialProduct_post() {
       $data = $this->input->post();


      $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {

        $page_no    = extract_value($data,'page_no',1);  
        $offset     = get_offsets($page_no);
        $product_type  = extract_value($data, 'product_type', '');
        $upload_url = base_url().'uploads/product/';

        $options = array('table' => PRODUCTS . ' as product',
          'select' => 'product.id,product.product_name,product.price,product.image,product.description,product.launching_date,product.product_type',
          'order' => array('product.id' => 'desc'),
          'where'=> array('product.product_type'=> 1),
          'limit' => array(10 => $offset)

          );


        /* To get Premium Product list from products table */
        $list = $this->common_model->customGet($options);

        /* check for image empty or not */

        if (!empty($list)) {

          $eachArr = array();

          $total_requested = (int) $page_no * 10; 

          /* Get total records */  
          $total_records = getAllCount(PRODUCTS,array('product_type'=>1));

          if($total_records > $total_requested){                      
            $has_next = TRUE;                    
          }else{                        
            $has_next = FALSE;                    
          }

          foreach ($list as $rows):
            if(!empty($rows->image))
            {
              $image = $upload_url.$rows->image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
            $temp['id']               = null_checker($rows->id);
            $temp['product_name']     = null_checker($rows->product_name);
            $temp['description']      = null_checker($rows->description);
            $temp['price']            = null_checker($rows->price);
            $temp['launching_date']   = null_checker(convertDate($rows->launching_date));
            $temp['image']            = $image;

            $eachArr[] = $temp;
            endforeach;
            /* return success response*/

            echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Products found successfully']);

          }else {
           echo $this->jsonresponse->geneate_response(1, 0,'Product not found',[]);
         }
       }

     }

    /**
     * Function Name: latest_product
     * Description:   To Get Latest Products List
     */

     function latest_product_post() {
      $data = $this->input->post();

      $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {

        $page_no    = extract_value($data,'page_no',1);  
        $offset     = get_offsets($page_no);
        $upload_url = base_url().'uploads/product/';
        $before_date = date('Y-m-d',strtotime('-15 day'));
        $current_date = date('Y-m-d');

        $options = array('table' => PRODUCTS . ' as product',
          'select' => 'product.id,product.product_name,product.price,product.image,product.description,product.launching_date',
          'order' => array('product.id' => 'desc'),
          'where'=> array('product.launching_date>'=> $before_date,
                          'product.launching_date<='=> $current_date,
                           'product.product_type!='=>1),
          'limit' => array(10 => $offset)

          );


        /* To get Latest Product list from products table */
        $list = $this->common_model->customGet($options);
        /* check for image empty or not */
        if (!empty($list)) {

          $eachArr = array();

          $total_requested = (int) $page_no * 10; 

          /* Get total records */  
          $total_records = getAllCount(PRODUCTS,array('launching_date>'=>$before_date,'launching_date<='=> $current_date,'product_type!='=>1));

          if($total_records > $total_requested){                      
            $has_next = TRUE;                    
          }else{                        
            $has_next = FALSE;                    
          }

          foreach ($list as $rows):
            if(!empty($rows->image))
            {
              $image = $upload_url.$rows->image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
            $temp['id']               = null_checker($rows->id);
            $temp['product_name']     = null_checker($rows->product_name);
            $temp['description']      = null_checker($rows->description);
            $temp['price']            = null_checker($rows->price);
            $temp['launching_date']   = null_checker(convertDate($rows->launching_date));
            $temp['image']       = $image;

            $eachArr[] = $temp;
            endforeach;
            /* return success response*/

            echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Products found successfully']);

          }else {
           echo $this->jsonresponse->geneate_response(1, 0,'Product not found',[]);
         }
       }

     }

     /**
     * Function Name: trending
     * Description:   To Get Trending Product List
     */

    public function trending_post()
    {
      $data = $this->input->post();
      $upload_url = base_url().'uploads/product/';
      $options = array('table' => CONFIGURE_PRODUCTS . ' as config_product',
          'select' => 'product.id,product.product_name,product.price,product.image,product.description,product.launching_date',
          'join' => array(PRODUCTS.' as product'=> 'product.id=config_product.product_id')

          );
       $trending_products = $this->common_model->customGet($options);
       if(!empty($trending_products))
       {
         foreach($trending_products as $products)
         {

             if(!empty($products->image))
            {
              $image = $upload_url.$products->image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }

            $temp['id']               = null_checker($products->id);
            $temp['product_name']     = null_checker($products->product_name);
            $temp['description']      = null_checker($products->description);
            $temp['price']            = null_checker($products->price);
            $temp['launching_date']   = null_checker(convertDate($products->launching_date));
            $temp['image']            = $image;

            $eachArr[] = $temp;
          }

            echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'message'=>'Products found successfully']);
         
       }else{
           echo $this->jsonresponse->geneate_response(1, 0,'Product not found',[]);
       }

    }
  
   /**
     * Function Name: product_search
     * Description:   To Search products
    */

   public function product_search_post()
   {
         $data = $this->input->post();

      $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
      $this->form_validation->set_rules('search_key', 'Search Key', 'trim|required');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {

        $upload_url    = base_url().'uploads/product/';
        $page_no       = extract_value($data,'page_no',1);  
        $offset        = get_offsets($page_no); 

        $search_key    = extract_value($data,'search_key',''); 

        $query = "SELECT * FROM products WHERE product_name LIKE '%" . $search_key . "%'";

        $products = $this->common_model->customQuery($query);

        if(!empty($products))
        {

          $eachArr = array();

          $total_requested = (int) $page_no * 10; 

          /* Get total records */  
          $option1 = "SELECT count(*) FROM products WHERE product_name LIKE '%" . $search_key . "%'";
          $total_records = $option1;

          if($total_records > $total_requested){                      
            $has_next = TRUE;                    
          }else{                        
            $has_next = FALSE;                    
          }
          foreach($products as $product)
          {

             if(!empty($product->image))
            {
              $image = $upload_url.$product->image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }


            $temp['id']               = null_checker($product->id);
            $temp['product_name']     = null_checker($product->product_name);
            $temp['description']      = null_checker($product->description);
            $temp['price']            = null_checker($product->price);
            $temp['launching_date']   = null_checker(convertDate($product->launching_date));
            $temp['image']            = $image;

            $eachArr[] = $temp;
          }

           echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Products found successfully']); 

        }else{
          echo $this->jsonresponse->geneate_response(1, 0,'Product not found',[]);
        }


     }
   }


   }


   /* End of file Category.php */
   /* Location: ./application/controllers/api/v1/Category.php */
   ?>
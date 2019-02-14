<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Common_Controller {

    public $data = array();
    public $file_data = "";

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
        $this->data['parent'] = "Settings";
        $this->data['title'] = "Settings";
        $option = array('table' => KEY_CONFIGURATION,
                'select' => '*',
                'where'=> array('key_name'=> 'trending_type'),
                'single' => true,
            );
            $key_value = $this->common_model->customGet($option);
          if($key_value->key_value==1)
      {
        $option =array(
        'table' => PRODUCTS,
        'select'=> '*'
        );
        $this->data['products']= $this->common_model->customGet($option);
     }else{
        $sql ="SELECT order_meta.product_id as id,products.product_name, COUNT(`product_id`) AS `value_occurrence` FROM `order_meta` INNER JOIN `products` ON `products`.`id` = `order_meta`.`product_id` GROUP BY `product_id` ORDER BY `value_occurrence` DESC LIMIT 5";

        $this->data['products']= $this->common_model->customQuery($sql);
    }
        
        $this->load->admin_render('configuration', $this->data, 'inner_script');
    }

    /**
     * @method setting_add
     * @description add dynamic rows
     * @return array
     */
    public function setting_add() {

        $allOptions = is_options();
        $image = $this->input->post('site_logo_url');
        if (!empty($_FILES['user_image']['name'])) {
            $this->filedata = $this->commonUploadImage($_POST, 'app', 'user_image');
            if ($this->filedata['status'] == 1) {
                $image = 'uploads/app/' . $this->filedata['upload_data']['file_name'];
                delete_file($this->input->post('site_logo_url'), FCPATH);
            }
        }
        foreach ($allOptions as $rows) {
            $option = array('table' => SETTING,
                'where' => array('option_name' => $rows, 'status' => 1),
                'single' => true,
            );
            $is_value = $this->common_model->customGet($option);
            if (!empty($is_value)) {
                $options = array('table' => SETTING,
                    'data' => array(
                        'option_value' => (isset($_POST[$rows])) ? $_POST[$rows] : "",
                    ),
                    'where' => array('option_name' => $rows)
                );
                if (!empty($image) && $rows == 'site_logo') {
                    $options['data']['option_value'] = $image;
                }
                $this->common_model->customUpdate($options);
            } else {

                $options = array('table' => SETTING,
                    'data' => array(
                        'option_value' => (isset($_POST[$rows])) ? $_POST[$rows] : "",
                        'option_name' => $rows
                    )
                );
                if (!empty($image) && $rows == 'site_logo') {
                    $options['data']['option_value'] = $image;
                }
                $this->common_model->customInsert($options);
            }
        }
        $response = array('status' => 1, 'message' => lang('setting_success_message'), 'url' => "");
        echo json_encode($response);
    }

     /**
     * @method setting_add
     * @description add dynamic rows
     * @return array
     */
    public function set_configuration() {

        $allOptions = is_options();
          $image       = $this->input->post('site_logo_url');
          $product_id  = $this->input->post('top_products');
        if (!empty($_FILES['user_image']['name'])) {
            $this->filedata = $this->commonUploadImage($_POST, 'setting', 'user_image');
            if ($this->filedata['status'] == 1) {
                $image = 'uploads/setting/' . $this->filedata['upload_data']['file_name'];
                delete_file($this->input->post('site_logo_url'), FCPATH);
            }
        }
       
        foreach ($allOptions as $rows) {
            $option = array('table' => KEY_CONFIGURATION,
                'where' => array('key_name' => $rows, 'status' => 1),
                'single' => true,
            );
            $is_value = $this->common_model->customGet($option);
            if (!empty($is_value)) {
                $options = array('table' => KEY_CONFIGURATION,
                    'data' => array(
                        'key_value' => (isset($_POST[$rows])) ? $_POST[$rows] : "",
                    ),
                    'where' => array('key_name' => $rows)
                );
                
                if (!empty($image) && $rows == 'site_logo') {
                    $options['data']['key_value'] = $image;
                } 
               
                $this->common_model->customUpdate($options);
            } else {

                $options = array('table' => KEY_CONFIGURATION,
                    'data' => array(
                        'key_value' => (isset($_POST[$rows])) ? $_POST[$rows] : "",
                        'key_name' => $rows
                    )
                );
                
                if (!empty($image) && $rows == 'site_logo') {
                    $options['data']['key_value'] = $image;
                }
                $this->common_model->customInsert($options);
            }
         }
           $option = array('table' => KEY_CONFIGURATION,
                'select' => '*',
                'where'=> array('key_name'=> 'trending_type'),
                'single' => true,
            );
            $key_value = $this->common_model->customGet($option);

            $option = array('table' => CONFIGURE_PRODUCTS,
                'select' => '*',
            );
            $configure_value = $this->common_model->customGet($option);
            if(empty($configure_value))
            {
              for($i=0;$i<count($product_id);$i++) {

                    $option = array(
                        'table' => CONFIGURE_PRODUCTS,
                        'data' => array('key_id' => $key_value->id, 'product_id' => $product_id[$i],'key_value'=>$key_value->key_value)
                    );
                    $this->common_model->customInsert($option);
                }
            }else{
                 $option = array(
                    'table' => CONFIGURE_PRODUCTS,
                    'select'=> 'id',
                );
                $products = $this->common_model->customGet($option);
                if (count($products) > 0) {
                    $option = array(
                        'table' => CONFIGURE_PRODUCTS,
                        'where'=>array('key_id'=>$key_value->id)
                    );

                    $this->common_model->customDelete($option);
                }
              for($i=0;$i<count($product_id);$i++) {

                    $option = array(
                        'table' => CONFIGURE_PRODUCTS,
                        'data' => array('key_id' => $key_value->id, 'product_id' => $product_id[$i],'key_value' => $key_value->key_value)
                    );
                    $this->common_model->customInsert($option);

                }
            }


        $response = array('status' => 1, 'message' => lang('setting_success_message'), 'url' => "");
        echo json_encode($response);
    }


     public function getProducts($trending_type){
        
            $products = $this->input->post('trending_type');
            $sql ="SELECT order_meta.product_id,products.product_name, COUNT(`product_id`) AS `value_occurrence` FROM `order_meta` INNER JOIN `products` ON `products`.`id` = `order_meta`.`product_id` GROUP BY `product_id` ORDER BY `value_occurrence` DESC LIMIT 5";

            $results= $this->common_model->customQuery($sql);

        
         $opt='';
         foreach($results as $product){
            $opt .= "<option value='".$product->product_id."'>".$product->product_name."</option>";
         }
        echo $opt;
        exit;
    
    }

      public function getAllProducts($trending_type){
        
            $products = $this->input->post('trending_type');

            $option =array(
                'table' => PRODUCTS,
                'select'=> '*'
                );
            $results= $this->common_model->customGet($option);
           
          $opt='';
         foreach($results as $product){
            $opt .= "<option value='".$product->id."'>".$product->product_name."</option>";
         }
        echo $opt;
        exit;
    
    }

}

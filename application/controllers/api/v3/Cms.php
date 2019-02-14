<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for cms
 * @package   CodeIgniter
 * @category  Controller
 * @author    Preeti Birle
 */
class Cms extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    
    /**
     * Function Name: contact_support
     * Description:   To add contact details
     */
   function contact_support_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('question', 'Question', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {
            $insertArr = array();
           
            $insertArr['user_id']   = extract_value($data, 'user_id', '');
            $insertArr['full_name'] = extract_value($data, 'full_name', '');
            $insertArr['email']     = extract_value($data, 'email', '');
            $insertArr['query']  = extract_value($data, 'question', '');
            $insertArr['created_date']  = datetime(); 
            $options = array('table' => CONTACT_SUPPORT,
                'data' => $insertArr,
            );

            /* insert data into contacts table */
            $insert = $this->common_model->customInsert($options);
            if ($insert) {
                /* return success response */
                 echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Your Query has been submitted successfully!']);
            } else {
                echo $this->jsonresponse->geneate_response(1, 0,'Your Query submition failed!',[]);
            }
        }
        
    }

    /**
     * Function Name: about_us
     * Description:   To Get about us details
     */
    function about_us_post() {
        $data = $this->input->post();
        
            $options = array('table' => CMS,
                'select' => 'description,image,page_id',
                'where' => array('page_id'=>'about','active' =>1),
                'single' =>true,
              
            );
           
              /* To get page details from cms table */
            $list = $this->common_model->customGet($options);
           /* check for image empty or not */
                   if(!empty($list->image))
                {
                      $image = $upload_url.$list->image;
                 } else{
                     /* set default image if empty */
                      $image = base_url().'assets/img/no_image.jpg';
                }
            if (!empty($list)) {

                $eachArr = array();

                    $eachArr['description']   = null_checker($list->description);
                    
                /* return success response*/
                 echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'message'=>'Details found successfully!']);
            }else {
               echo $this->jsonresponse->geneate_response(1, 0,'Details not found!',[]);
            }
        
    }

    /**
     * Function Name: privacy_policy
     * Description:   To Get Privacy Policy details
     */
    function privacy_policy_post() {
        $data = $this->input->post();
        
            $options = array('table' => CMS,
                'select' => 'description,image,page_id',
                'where' => array('page_id'=>'privacy_policy','active' =>1),
                'single' =>true,
              
            );
           
              /* To get page details from cms table */
            $list = $this->common_model->customGet($options);
           /* check for image empty or not */
                   if(!empty($list->image))
                {
                      $image = $upload_url.$list->image;
                 } else{
                     /* set default image if empty */
                      $image = base_url().'assets/img/no_image.jpg';
                }
            if (!empty($list)) {

                $eachArr = array();

                    $eachArr['description']   = null_checker($list->description);
                    
                /* return success response*/
                 echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'message'=>'Details found successfully!']);
            }else {
               echo $this->jsonresponse->geneate_response(1, 0,'Details not found!',[]);
            }
        
    }

    /**
     * Function Name: terms_conditions
     * Description:   To Get Terms and Conditios details
     */
   
    function terms_conditions_post() {
        $data = $this->input->post();
        
            $options = array('table' => CMS,
                'select' => 'description,image,page_id',
                'where' => array('page_id'=>'terms_condition','active' =>1),
                'single' =>true,
              
            );
           
              /* To get page details from cms table */
            $list = $this->common_model->customGet($options);
           /* check for image empty or not */
                   if(!empty($list->image))
                {
                      $image = $upload_url.$list->image;
                 } else{
                     /* set default image if empty */
                      $image = base_url().'assets/img/no_image.jpg';
                }
            if (!empty($list)) {

                $eachArr = array();

                    $eachArr['description']   = null_checker($list->description);
                    
                /* return success response*/
                 echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'message'=>'Details found successfully!']);
            }else {
               echo $this->jsonresponse->geneate_response(1, 0,'Details not found!',[]);
            }
        
    }


}


/* End of file Cms.php */
/* Location: ./application/controllers/api/v1/Cms.php */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for category
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun Choudhary
 */
class Event extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }


    /**
     * Function Name: event_list
     * Description:   To Get all event
    */

    function event_list_post() {
    	$data = $this->input->post();

      	$this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	} else {

        	$page_no    = extract_value($data,'page_no',1);  
        	$offset     = get_offsets($page_no);
      
        	$upload_url = base_url().'uploads/event/';
        	$current_date = datetime('Y-m-d');

        	$options = array(
        		'table' => EVENT.' AS e',
          		'select' => 'e.*',
          		'where'=>array('e.status'=>1),
          		'order' => array('e.id' => 'desc'),
          		'limit' => array(10 => $offset)
          	);

        	/* To get event list from event table */
        	$list = $this->common_model->customGet($options);

        	/* check for image empty or not */

        	if (!empty($list)) {

          		$eachArr = array();

          		$total_requested = (int) $page_no * 10; 

          		/* Get total records */  
          		$total_records = getAllCount(EVENT,array('status'=>1));

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
		            $temp['redirect_url']   = null_checker($rows->redirect_url);
		            $temp['status']      	= null_checker($rows->status);
		            $temp['created']   		= null_checker(convertDate($rows->created));
		            $temp['image']       	= $image;

            		$eachArr[] = $temp;
            	endforeach;
            	/* return success response*/

            	echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Events found successfully']);

          	}else {
           		echo $this->jsonresponse->geneate_response(1, 0,'Events not found',[]);
         	}
       	}

    }

}


   /* End of file Event.php */
   /* Location: ./application/controllers/api/v1/Event.php */
   ?>
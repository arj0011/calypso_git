<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for category
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun Choudhary
 */
class Suggestionfeedback extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }


    /**
     * Function Name: suggestion_list
     * Description:   To Get all suggestion
    */

    function suggestion_list_post(){
    	$data = $this->input->post();
      $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
    	$this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required');

      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	} else {
          $page_no    = extract_value($data,'page_no',1);  
      		$user_id    = extract_value($data,'user_id','');

          is_deactive($user_id);

        	$offset     = get_offsets($page_no);
      
        	$upload_url = base_url().'uploads/allacart/';
        	$current_date = datetime('Y-m-d');

        	$options = array(
        		'table' => SUGGESTION_FEEDBACK,
          		'select' => 'id,title,suggestion,type',
          		'where'=> array('type'=>1,'user_id'=>$user_id,'status'=>1),
          		'order' => array('id' => 'desc'),
          		'limit' => array(10 => $offset)
          	);

        	/* To get offer list from offer table */
        	$list = $this->common_model->customGet($options);

        	/* check for image empty or not */

        	if (!empty($list)) {
          		
          		$total_requested = (int) $page_no * 10; 

          		/* Get total records */  
          		$total_records = getAllCount(SUGGESTION_FEEDBACK,array('type'=>1,'status'=>1));

          		if($total_records > $total_requested){                      
            		$has_next = TRUE;                    
          		}else{                        
            		$has_next = FALSE;                    
          		}

            	/* return success response*/

            	echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$list,'has_next'=>$has_next,'message'=>'Suggestion found successfully']);

          	}else {
           		echo $this->jsonresponse->geneate_response(1, 0,'Suggestion not found',[]);
         	}
      	}
    }

    /**
     * Function Name: feedback_list
     * Description:   To Get all feedback
    */

    function feedback_list_post(){
    	$data = $this->input->post();
    	$this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
      $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required');
      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	} else {
      		$page_no    = extract_value($data,'page_no',1); 
          $user_id    = extract_value($data,'user_id',''); 

          is_deactive($user_id);

        	$offset     = get_offsets($page_no);
      
        	$upload_url = base_url().'uploads/allacart/';
        	$current_date = datetime('Y-m-d');

        	$options = array(
        		'table' => SUGGESTION_FEEDBACK,
          		'select' => 'id,feedback,type',
          		'where'=> array('type'=>2,'user_id'=>$user_id,'status'=>1),
          		'order' => array('id' => 'desc'),
          		'limit' => array(10 => $offset)
          	);

        	/* To get offer list from offer table */
        	$list = $this->common_model->customGet($options);

        	/* check for image empty or not */

        	if (!empty($list)) {
          		
          		$total_requested = (int) $page_no * 10; 

          		/* Get total records */  
          		$total_records = getAllCount(SUGGESTION_FEEDBACK,array('type'=>2,'status'=>1));

          		if($total_records > $total_requested){                      
            		$has_next = TRUE;                    
          		}else{                        
            		$has_next = FALSE;                    
          		}

            	/* return success response*/

            	echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$list,'has_next'=>$has_next,'message'=>'Feedback found successfully']);

          	}else {
           		echo $this->jsonresponse->geneate_response(1, 0,'Feedback not found',[]);
         	}
      	}
    }


    function suggestion_post(){
    	$data = $this->input->post();
    	$this->form_validation->set_rules('title', 'Title', 'trim|required');
    	$this->form_validation->set_rules('suggestion', 'Suggestion', 'trim|required');
      $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required');
      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	} else {
      		$title    	= extract_value($data,'title','');  
          $suggestion = extract_value($data,'suggestion','');
      		$user_id    = extract_value($data,'user_id','');

          is_deactive($user_id);

      		$insertdata = array('user_id'=>$user_id,'title'=>$title,'suggestion'=>$suggestion,'type'=>1,'status'=>0);
      		$option = array('table'=>SUGGESTION_FEEDBACK,'data'=>$insertdata);
      		$insert = $this->common_model->customInsert($option);
      		if($insert){

            $opt = array(
              'table'=>USERS,
              'select'=>'full_name',
              'where'=>array('id'=>$user_id),
              'single'=>true
            );  
            $userdata = $this->common_model->customGet($opt);
            $username = $userdata->full_name;  
            $username = ucfirst($username); 

            $from = FROM_EMAIL;
            $email = TO_EMAIL;
            $subject = "Suggestion Received";
            
            $data['content'] = "User : ".$username."<br /> Title : ".$title."<br />"."Suggestion : ".$suggestion;

            // $data['user'] = ucwords($name);
            $data['user'] = 'Admin';

            $message = $this->load->view('email_template', $data, true);

            $title = "New User";

            /* send mail */
            send_mail($message, $subject, $email, $from, $title);

      			echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Suggestion added successfully']);
      		}else{
      			echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Suggestion insertion failed']);
      		}
      	}
    }

    function feedback_post(){
    	$data = $this->input->post();
    	$this->form_validation->set_rules('feedback', 'Feedback', 'trim|required');
      $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required');
      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	} else {
      		$feedback = extract_value($data,'feedback','');
          $user_id    = extract_value($data,'user_id','');

          is_deactive($user_id);
          
          
      		$insertdata = array('user_id'=>$user_id,'feedback' => $feedback,'type'=>2,'status'=>0);
      		$option = array('table'=>SUGGESTION_FEEDBACK,'data'=>$insertdata);
          $insert = $this->common_model->customInsert($option);
      		if($insert){

            $opt = array(
              'table'=>USERS,
              'select'=>'full_name',
              'where'=>array('id'=>$user_id),
              'single'=>true
            );  
            $userdata = $this->common_model->customGet($opt);
            $username = $userdata->full_name;  
            $username = ucfirst($username);  


            $from = FROM_EMAIL;
            $email = TO_EMAIL;
            $subject = "Feedback Received";
            
            $data['content'] = "User : ".$username."<br /> Feedback : ".$feedback;

            // $data['user'] = ucwords($name);
            $data['user'] = 'Admin';

            $message = $this->load->view('email_template', $data, true);

            $title = "New User";

            /* send mail */
            send_mail($message, $subject, $email, $from, $title);
            
      			echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Feedback added successfully']);
      		}else{
      			echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Feedback insertion failed']);
      		}	
      	}
    }

}


   /* End of file Suggestionfeedback.php */
   /* Location: ./application/controllers/api/v1/Suggestionfeedback.php */
   ?>
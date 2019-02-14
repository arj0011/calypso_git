<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Jsonresponse
{
  private $response;
  
  public function __construct(){
  	$this->response = [];
    $this->response['error'] = '';
    $this->response['status'] = '';
    $this->response['error_message'] = '';
    $this->response['data'] = (object)[];    
  }
  public function geneate_response($is_error,$status,$error_message='',$data=[]){
        
        if(!empty($is_error))
        { 
          if(is_string($is_error))
          {
            $this->response['error'] = true; 
            $this->response['error_message'] = 'Invalid Error Code';
            
          }
          else if($is_error==1)
          {
            $this->response['error'] = true; 
            $this->response['status'] = 0;
            if($error_message=='')
            {
              $this->response['error_message'] = 'Error is unknown';
              
            }
            else
            {
              $this->response['error_message'] = strip_tags($error_message);
              $this->response['status'] = $status;
            }
          } 
        }
        else if($is_error==0)
        { 
          if(empty($status)|| is_string($status))
          {
            $this->response['error'] = true;
            $this->status = 0;
            $this->response['error_message'] = 'Status must be numeric!';
          }
          elseif($status==1)
          { 
            if(empty($data) || !is_array($data) || count($data)<1  )
            {
              $this->response['error'] = true;
              $this->status = 0;
              $this->response['error_message'] = 'Provided Data format is invalid';
            }
            else
            {
              $this->response['error'] = false;
              $this->response['status'] = 1;
              $this->response['error_message'] = '';
              $this->response['data'] = $data;
            }
          }
          elseif($status!=1)
          {
              $this->response['error'] = false;
              $this->response['status'] = 1;
              $this->response['error_message'] = '';
              $this->response['data'] = $data;
          }
        }
        

      $this->output();
      return $this->response;

  }
  public function output()
  {
    $this->response = json_encode($this->response);
  }

}
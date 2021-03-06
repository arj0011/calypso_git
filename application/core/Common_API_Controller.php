<?php

/* Require Rest Controller Class */
require APPPATH.'/libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Common_API_Controller extends REST_Controller {

      public function __construct() {
      parent::__construct();
      $this->load->library(array('Jsonresponse'));
      $this->load->library(array('userauth'));
      $this->load->library(array('wallet'));
      $data = $this->input->post();
      $this->user_details = array();

      /* Validate login session key */
      if(isset($data['login_session_key']) && !empty($data['login_session_key']))
      {
        $this->user_details = $this->common_model->getsingle(USERS,array('login_session_key' => $data['login_session_key']));

        if(empty($this->user_details)){
          die($this->jsonresponse->geneate_response(1, 0,'Invalid login session key',[]));
            // $return['code'] = 200;
            // $return['response'] = array();
            // $return['status']   =   2; 
            // $return['message']  =   'Invalid login session key';
            // $this->response($return);exit;
        }else{
          if($this->user_details->is_blocked == 1){
            $return['code'] = 200;
            $return['response'] = array();
            $return['status']   =   2; 
            $return['message']  =   BLOCK_USER;
            $this->response($return);exit;
          }else if($this->user_details->active == 0){
            $return['code'] = 200;
            $return['response'] = array();
            $return['status']   =   2; 
            $return['message']  =   DEACTIVATE_USER;
            $this->response($return);exit;
          }
        }
      }
    }

    /**
     * Function Name: _check_value_exist
     * Description:   To check values exist or not into database
     */
    public function _check_value_exist($str,$field)
    {
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field,$msg);
        $rows = $this->db->limit(1)->get_where($table, array($field => $str))->num_rows();
        if($rows != 0){
            $this->form_validation->set_message('_check_value_exist', $msg);
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * Function Name: _validate_login_session_key
     * Description:   To validate user login session key
     */
    public function _validate_login_session_key($LoginSessionKey)
    {
        $ci =&get_instance();
        $result = $ci->common_model->getsingle(USERS,array('login_session_key' => $LoginSessionKey));
        if(!empty($result)){
            return TRUE;
        }else{
            $ci->form_validation->set_message('_validate_login_session_key', 'Invalid Login Session Key');
            return FALSE;
        }
    }

    /**
     * Function Name: pswd_regex_check
     * Description:   For Password Regular Expression
     */
    public function pswd_regex_check($str) {
        $ci = &get_instance();
        if (1 !== preg_match("/^(?=.*\d)[0-9a-zA-Z!@#$%^&*]{6,}$/", $str)) {
            $ci->form_validation->set_message('pswd_regex_check', 'Password must contain at least 6 characters and numbers');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Function Name: _pageno_min_value
     * Description:   For Check Page No Minmum Value
     */
    public function _pageno_min_value($val)
    {
        $ci =&get_instance();
        $min = 1;
        if ($min > $val)
        {
            $ci->form_validation->set_message('_pageno_min_value', 'Page No minimum value should be '.$min);
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    /**
     * Function Name: _validate_date_format
     * Description:   To validate date format
     */
    public function _validate_date_format($date)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
        {
            $DateObject        = strtotime($date);
            $CurrentDateObject = strtotime(date('Y-m-d'));

            // Date should be greater then or equal to current date
            if($CurrentDateObject > $DateObject){
                $this->form_validation->set_message('_validate_date_format', 'Date should be greater then or equal to current date');
                return FALSE;
            }
            return TRUE;
        }else{
            $this->form_validation->set_message('_validate_date_format', 'Invalid date format, should be (YYYY-MM-DD)');
            return FALSE;
        }
    }

     /**
     * Function Name: _validate_birthdate_format
     * Description:   To validate dateofbirth format
     */

     public function _validate_birthdate_format($date) {
        if (preg_match("/^[0-9]{4}-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            
            return TRUE;
        } else {
            $this->form_validation->set_message('_validate_birthdate_format', 'Invalid date format, should be (YYYY-MM-DD)');
            return FALSE;
        }
    }

    /**
     * Function Name: _validate_date_time_format
     * Description:   To validate datetime format
     */
    public function _validate_date_time_format($datetime)
    {
        $ci =&get_instance();
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/",$datetime))
        {
            $ci->form_validation->set_message('_validate_date_time_format', 'Invalid datetime format, should be (YYYY-MM-DD HH:II:SS)');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    /**
     * Function Name: _validate_latitude
     * Description:   To validate latitude
     */
    public function _validate_latitude($latitude)
    {
      if (preg_match("/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/", $latitude)) {
          return TRUE;
      } else {
          $ci->form_validation->set_message('_validate_latitude', 'Invalid latitude format');
          return FALSE;
      }
    }

    /**
     * Function Name: _validate_longitude
     * Description:   To validate longitude
     */
    public function _validate_longitude($longitude)
    {
      if(preg_match("/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}$/",$longitude)) {
          return TRUE;
      } else {
          $ci->form_validation->set_message('_validate_longitude', 'Invalid longitude format');
          return FALSE;
      }
    }

    /**
     * Function Name: _validate_username
     * Description:   To validate username
     */
    public function _validate_username($str)
    {
      if (preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $str)) {
          return TRUE;
      } else {
          $ci->form_validation->set_message('_validate_username', 'Invalid username format');
          return FALSE;
      }
    }

  public function listParent($list)
  {
    $_temp = $list;
    $nodes = [];

    foreach($list as $i=>$v){
      if($v->parent_id=='0')
      {   $e = $this->childernSearch($list,$v->id);
          if(!empty($e))
          {  $v->childern = $e;
          }
          array_push($nodes,$v);
        
      }
    }
    return $nodes;
  } 
  
  public function childernSearch($list,$id)
  {
    $element = [];
    $count = 1;
    foreach($list as $i=>$v){
          if($v->parent_id == $id)
         { array_push($element,$v);
           $e = $this->childernSearch($list,$v->id);
          if(!empty($e))
          {  $v->childern = $e;
          }
           $count++;
         }
       }
    
     return $element;
  }


    

}
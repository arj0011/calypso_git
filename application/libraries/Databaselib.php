<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Databaselib
{
  private $CI;  
  public function __construct()
  {
  	$this->load->library('Jsonresponse');
    $this->CI = & get_instance();
    $this->CI->load->database();
  }
  public function login()
  {

  }      
  

}
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Userauth
{
  private $CI;
  private $that;  
  public function __construct()
  { $this->CI = & get_instance();
    $this->CI->load->database();
    $this->CI->load->library('Jsonresponse');
    $this->CI->load->library('Security');
    $this->CI->load->library('Session');
  }

  public function login($user,$pwd,$remember="",$type=false)
  {

    $that = $this->CI;
    $user = $that->security->xss_clean($user);
    $pwd = $that->security->xss_clean($pwd);

    $check_email = $this->Is_email($user);
    if($check_email)
    {
      $data = array(
        "email like binary" => $user,
      );
    }
    else
    {
      $data = array(
        //"username" => $user,
        "phone" => $user,
      );
    }

    $query = $that->db->get_where("users", $data);

    if($query->num_rows() !== 1)
    {  

      if($type)  
        return $that->jsonresponse->geneate_response(1,0,'User is not registered!',[]);
      else 
        die($that->jsonresponse->geneate_response(1,0,'User is not registered!',[]));
    }
    else
    {
      if($check_email)
      {
        $that->db->select('*');
        $that->db->from('users');
        $that->db->where('email', $user);
        $query = $that->db->get()->row();
        $pass = $query->password;

        if($pass != md5($pwd))//Password didn't match
        {
          if($type)
            return $that->jsonresponse->geneate_response(1,0,'Incorrect Password!',[]);
          else  
            die($that->jsonresponse->geneate_response(1,0,'Incorrect Password!',[]));
        }
        else
        {
          
          $last_login = date("Y-m-d H-i-s");
          $update_data = array(
            "last_login" => $last_login
          );
          $that->db->update("users", $update_data, $data);
          $that->session->set_userdata("id", $query->id);
          $that->session->set_userdata("full_name", $query->full_name);
          $that->session->set_userdata("email", $query->email);
          $that->session->set_userdata('user_activity', time());
          
        if ($remember && $remember==1)
        {

         $that->input->set_cookie('email',$query->email,'864000000');
         $that->input->set_cookie('password',$pwd,'864000000');
         $that->input->set_cookie('remember',1,'864000000');

       }
       else
       {
         delete_cookie("email");
         delete_cookie("password");
         delete_cookie("remember");
       }
       return true;
     } 
   }
   else{
    $that->db->select('*');
    $that->db->from('users');
    // $that->db->where('username', $user);
    $that->db->where('phone', $user);
    $query = $that->db->get()->row();
    $pass = $query->password;

      if($pass != md5($pwd))//Password didn't match
      {
        if($type)
          return $that->jsonresponse->geneate_response(1,0,'Incorrect Password!',[]);
        else
          die($that->jsonresponse->geneate_response(1,0,'Incorrect Password!',[]));
      }
      else
      {
        $last_login = date("Y-m-d H-i-s");
        $update_data = array(
         "last_login" => $last_login
         );
        $that->db->update("users", $update_data, $data);
        $that->session->set_userdata("id", $query->id);
        $that->session->set_userdata("full_name", $query->full_name);
        $that->session->set_userdata("username", $query->username);
        $that->session->set_userdata("phone", $query->username);

        if ($remember && $remember=TRUE)
        {
         $that->input->set_cookie('email',$query->username,'864000000');
         $that->input->set_cookie('password',$pwd,'864000000');
         $that->input->set_cookie('remember',1,'864000000');
       }
       else
       {
         // delete_cookie("email");
         delete_cookie("phone");
         delete_cookie("password");
         delete_cookie("remember");
       }
       return true;
     }  
   }
 }

} 

function signup($email,$phone,$pwd, $data = array())
{
  $that = $this->CI;
  $email = $that->security->xss_clean($email);
  $pwd = $that->security->xss_clean($pwd);
  $array_data = array();
  $array_data['phone'] = $phone;
  $array_data['email'] = $email;
  if(!$this->user_exist_phone($phone))
  {
    die($that->jsonresponse->geneate_response(1,0,'Phone already exist!',[]));
  }else if($email != '')
  {
    if(!$this->user_exist($email))
    { 
      die($that->jsonresponse->geneate_response(1,0,'Email already exist!',[]));
    }
  }  

  $array_data['password'] = md5($pwd);  
  $user_data = array_merge($array_data, $data);
  $that->db->insert("users", $user_data);
  $id = $that->db->insert_id();
  return $id;




  // if($unique_type == 'email'){
  //   $array_data['email'] = $user;
  //   if(!$this->user_exist($user))
  //   { 
  //     die($that->jsonresponse->geneate_response(1,0,'Email already exist!',[]));
  //   } 
  // }else{
  //   $array_data['phone'] = $user;
  //   if(!$this->user_exist_phone($user))
  //   {
  //     die($that->jsonresponse->geneate_response(1,0,'Phone already exist!',[]));
  //   }
  // }

  // if($this->pswd_check($pwd)){
  //   die($that->jsonresponse->geneate_response(1,0,'Password field must contain at least 6 characters,1 uppercase and numbers!',[]));
  // }



  // if(!$this->user_exist($user))
  // {
  //   die($that->jsonresponse->geneate_response(1,0,'Email already exist!',[]));
  // } 
  // else if($this->pswd_check($pwd))
  // { 

  //   die($that->jsonresponse->geneate_response(1,0,'Password field must contain at least 6 characters,1 uppercase and numbers!',[]));
  // }
  
  //else 
  //{ 

    // $array_data = array(
    //   "email" => $user,
    //   "password" => md5($pwd)
    // );
    
  //}
}

public function user_exist($user)
{

 $that = $this->CI;
 $query = $that->db->get_where("users", array("email" => $user));

 return ($query->num_rows() < 1) ? true : false;
}

public function user_exist_phone($user)
{

 $that = $this->CI;
 $query = $that->db->get_where("users", array("phone" => $user));

 return ($query->num_rows() < 1) ? true : false;
}


public function valid_email($user)
{

 $that = $this->CI;
 if(preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",$user))
 {
   return TRUE;
 }
 else
 {
   return FALSE;
 }

 
}

public function pswd_check($pwd) {
  $that = $this->CI;
  // if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/", $pwd)) { 
  if (preg_match_all('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d$@$!%*#?&]{6,}$/', $pwd)){
    return FALSE;
  } else {
    return TRUE;
  }
}

// Forgot password using email(send reset link)
public function forgot_password($user,$type=false){
  $that = $this->CI;
  $user = $that->security->xss_clean($user);

  if($this->user_exist($user))
  {
    if($type)
      return $that->jsonresponse->geneate_response(1,0,'User is not registered!',[]);
    else
      die($that->jsonresponse->geneate_response(1,0,'User is not registered!',[]));

  }
  else
  {
    $active_code = $this->code_generation('',$user,'');

    $that->db->select('*');
    $that->db->from('users');
    $that->db->where('email', $user);
    $data = $that->db->get()->row();

    $user_id = $data->id;
    $name = $data->full_name;

    $forgotten_password_code = encoding($user . "-" . $user_id . "-" . time());
    $timestamp = strtotime(date('Y-m-d H:i:s'));
    $updateArr = array('forgotten_password_code' => $forgotten_password_code,'forgotten_password_time'=> $timestamp,'activation_code'=> $active_code);

    $update_status =  $that->db->update("users", $updateArr, array('email' => $user));

    $link = base_url() . 'admin/reset_password/'. $forgotten_password_code;
    $message = "";
    $message .= "<img style='width:200px' src='" . base_url() . "assets/img/logo.png' class='img-responsive'></br></br>";
    $message .= "<br><br> Hello, <br/><br/>";
    $message .= "Somebody (hopefully you) requested a new password for the " . SITE_NAME . " account for " . $name . ". No changes have been made to your account yet.<br/><br/>";
    $message .= "You can reset your password by clicking this <a href='" . $link . "'>link</a>  <br/><br/>";
    $message .= "We'll be here to help you every step of the way. <br/><br/>";
    $message .= "If you did not request a new password, let us know immediately by forwarding this email to " . SUPPORT_EMAIL . ". <br/><br/>";
    $message .= "Thanks, <br/>";
    $message .= "The " . SITE_NAME . " Team";
    $status= $this->send_mail($message, 'Reset your ' . SITE_NAME . ' password', $user, FROM_EMAIL);
    if($status)
    {
     return true;
    }
    else
    {
      if($type)
        return $that->jsonresponse->geneate_response(1,0,'Email sent failed!',[]);
      else
      die($that->jsonresponse->geneate_response(1,0,'Email sent failed!',[]));
    }
  }  
}

  
// Forgot password using email(send activation code)
public function forgot_password_app($user){
  $that = $this->CI;
  $user = $that->security->xss_clean($user);

  if($this->user_exist($user))
  {
    
      die($that->jsonresponse->geneate_response(1,0,'User is not registered!',[]));

  }
  else
  {
    $active_code = $this->code_generation('',$user,'');

    $that->db->select('*');
    $that->db->from('users');
    $that->db->where('email', $user);
    $data = $that->db->get()->row();

    $user_id = $data->id;
    $name = $data->full_name;

    $forgotten_password_code = encoding($user . "-" . $user_id . "-" . time());
    $timestamp = strtotime(date('Y-m-d H:i:s'));
    $updateArr = array('forgotten_password_code' => $forgotten_password_code,'forgotten_password_time'=> $timestamp,'activation_code'=> $active_code);

    $update_status =  $that->db->update("users", $updateArr, array('email' => $user));

   
    $message = "";
    $message .= "<img style='width:200px' src='" . base_url() . "uploads/setting/1507528995_1024X1024.jpg' class='img-responsive'></br></br>";
    $message .= "<br><br> Hello, <br/><br/>";
    $message .= "Somebody (hopefully you) requested a new password for the " . SITE_NAME . " account for " . $name . ". No changes have been made to your account yet.<br/><br/>";
   
    $message .= "Your Confirmation Code is : "  . $active_code .  " <br/><br/>";
    $message .= "We'll be here to help you every step of the way. <br/><br/>";
    $message .= "If you did not request a new password, let us know immediately by forwarding this email to " . SUPPORT_EMAIL . ". <br/><br/>";
    $message .= "Thanks, <br/>";
    $message .= "The " . SITE_NAME . " Team";
    $status= $this->send_mail($message, 'Reset your ' . SITE_NAME . ' password', $user, FROM_EMAIL);
    if($status)
    {
      return true;
    }
    else
    {
      die($that->jsonresponse->geneate_response(1,0,'Email sent failed!',[]));
    }
  }  
}

// Forgot password using phone(send activation code using OTP)
public function forgot_password_app_phone($user){
  $that = $this->CI;
  $user = $that->security->xss_clean($user);

  if($this->user_exist_phone($user))
  {
    die($that->jsonresponse->geneate_response(1,0,'User is not registered!',[]));
  }
  else
  {
    $active_code = $this->code_generation('',$user,'phone');

    $that->db->select('*');
    $that->db->from('users');
    $that->db->where('phone', $user);
    $data = $that->db->get()->row();

    $user_id = $data->id;
    $name = $data->full_name;

    $forgotten_password_code = encoding($user . "-" . $user_id . "-" . time());
    $timestamp = strtotime(date('Y-m-d H:i:s'));
    $updateArr = array(
      'forgotten_password_code' => $forgotten_password_code,
      'forgotten_password_time' => $timestamp,
      'activation_code'         => $active_code
    );

    $update_status =  $that->db->update("users", $updateArr, array('phone' => $user));

    $postfields = array(
      'dest'   => $user,
      'msg'    => FP_SMS_MSG.$active_code, 
      'send'   => OTP_SMS_SEND, 
      'concat' => 1, 
      'uname'  => OTP_SMS_UNAME, 
      'pass'   => OTP_SMS_PWD
    ); 
    $result = Execute_Curl_URL(OTP_SMS_URL,$postfields);
    if($result)
    {
      return true;
    }
    else
    {
      die($that->jsonresponse->geneate_response(1,0,'OTP sent failed!',[]));
    }
  }  
}
  

function send_mail($message, $subject, $to_email, $from_email = "", $attach = "") {

 $that = $this->CI;
 $config['mailtype'] = 'html';
 $that->email->initialize($config);
 if (!empty($from_email)) {
  $that->email->from($from_email, SITE_NAME);
} else {
  $that->email->from(FROM_EMAIL, SITE_NAME);
}
$that->email->to($to_email);
$that->email->subject($subject);
$that->email->message($message);
if (!empty($attach)) {
  $that->email->attach($attach);
}
if ($that->email->send()) {
  return true;
} else {
  return false;
}
}

public function change_password($user, $old_pass, $new_pass){
  $that = $this->CI;

  $that->db->select('password');
  $that->db->from('users');
  $that->db->where('email', $user);
  $query = $that->db->get()->row();
  $pass = $query->password;

  if($pass != md5($old_pass))
  {
    die($that->jsonresponse->geneate_response(1,0,'Your old password is incorrect!',[]));
  }
  else if($this->pswd_check($new_pass))
  {
    die($that->jsonresponse->geneate_response(1,0,'New Password must contain at least 6 characters and numbers!',[]));
 }
 else
 {
  $pswdArr = array('password' => md5($new_pass));
  $that->db->update("users", $pswdArr, array('email' => $user));
  return true;
}


}

public function reset_password($user,$new_pass){
  $that = $this->CI;

  $that->db->select('password');
  $that->db->from('users');
  $that->db->where('email', $user);
  $query = $that->db->get()->row();
  $pass = $query->password;

  if($this->user_exist($user))
  {
    return $that->jsonresponse->geneate_response(0,100,'',['message'=>'Invalid Email!']);
  }
  else if($this->pswd_check($new_pass))
  {
   return $that->jsonresponse->geneate_response(0,100,'',['message'=>'New Password must contain at least 6 characters and numbers!']);
 }
 else
 {

  $pswdArr = array('password' => md5($new_pass));
  $that->db->update("users", $pswdArr, array('email' => $user));
  return true;
}


}

public function logout()
{

  $that = $this->CI;
  $that->session->unset_userdata("id");
  return true;
}

public function logged_in()
{
 $that = $this->CI;
 $this->checkAdminSession();
 return ($that->session->userdata("id")) ? true : false;
}

 public function checkAdminSession() {
         $that = $this->CI;
        if ($that->session->userdata('id') == TRUE) {
            $activity = $that->session->userdata('user_activity');
            $max_time = 1800; // 30 minutes
            $current_time = time() - $activity;
            if ($current_time > $max_time) {
              $that->session->unset_userdata('id');
               
            } else {
                $that->session->set_userdata('user_activity', time());
            }
        } else {
            return false;
        }
    }

public function Is_email($user)

{

    //If the username is an e-mail, return true

  if(filter_var($user, FILTER_VALIDATE_EMAIL)) {

    return true;

  } else {

    return false;

  }

}

public function code_generation($type=false, $user, $userType=false)
{
  $that = $this->CI;
  $characters = '1234567890';
  $string = '';

  for ($i = 0; $i < 4; $i++) {
    $string .= $characters[rand(0, strlen($characters) - 1)];
  }

  // level 1 for signup 

  if($type){
    $activation_code = array('activation_code' => $string);
  }

  // level 2 for login and forgot password
  else{
    $activation_code = array('activation_code' => $string,
      'code_expiry' => time()+900
    );
  }
  
  if($userType){
    
    if($userType == 'phone'){
      $that->db->update("users", $activation_code, array('phone' => $user));
    }else{   
      $that->db->update("users", $activation_code, array('username' => $user));
    }
  }else{
    // $that->db->update("users", $activation_code, array('email' => $user));
    $that->db->update("users", $activation_code, array('email' => $user));
  }
  
  return $string;
}


public function code_verification($user, $code, $type=FALSE)
{
  $that = $this->CI;
  $that->db->select('*');
  $that->db->from('users');
  $that->db->where('email', $user);
  $query = $that->db->get()->row();
  $active_code = $query->activation_code;
  $expiry_time = $query->code_expiry;

  $data = array(
   'email_verify' => 1
   );
  if($type)
  { 
    if($active_code == $code)
    {
      $that->db->update("users", $data, array('email' => $user));
      return true;
    }
    else
    {
      die($that->jsonresponse->geneate_response(1, 0,'Incorrect Code',[]));
    }
  }
  else
  {
   if(time() > $expiry_time)
   {
     die($that->jsonresponse->geneate_response(1, 0,'Your Code has been expired,please regenerate code!',[]));
   }
   else
   {
    if($active_code == $code)
    {
      $that->db->update("users", $data, array('email' => $user));
      die($that->jsonresponse->geneate_response(0, 1,'',['message'=>'Your email has been verified']));
    }
    else
    {
      die($that->jsonresponse->geneate_response(1, 0,'Incorrect Code',[]));
    }
  }

}


}

//To Verify Activation Code(OTP)
  public function activecode_verification($user, $code, $type=FALSE){
    $that = $this->CI;
    $that->db->select('*');
    $that->db->from('users');
    $that->db->where('phone', $user);
    $query = $that->db->get()->row();
    if(!empty($query)){
      $active_code = $query->activation_code;
      $expiry_time = $query->code_expiry;

      $data = array('email_verify' => 1);
      if($type){ 
        if($active_code == $code){
          $that->db->update("users", $data, array('phone' => $user));
          return true;
        }else{
          die($that->jsonresponse->geneate_response(1, 0,'Incorrect Code',[]));
        }
      }else{
        if(time() > $expiry_time){
         die($that->jsonresponse->geneate_response(1, 0,'Your Code has been expired,please regenerate code!',[]));
        }else{
          if($active_code == $code){
            $that->db->update("users", $data, array('phone' => $user));
            return true;
            // die($that->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'Your Phone has been verified']));
          }else{
            die($that->jsonresponse->geneate_response(1, 0,'Incorrect Code',[]));
          }
        }
      }
    }else{
      die($that->jsonresponse->geneate_response(1, 0,'User not found',[]));
    }
    
  }

}
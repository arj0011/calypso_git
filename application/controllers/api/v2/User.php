<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for user
 * @package   CodeIgniter
 * @category  Controller
 * @author    Preeti Birle
 */
class User extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $tables = $this->config->item('tables', 'ion_auth');
    $this->lang->load('en', 'english');
  }

    /**
     * Function Name: signup
     * Description:   To User Registration
     */
    function signup_post() {

      $data = $this->input->post();
      
      $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
      $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|numeric|is_unique[users.phone]',
            array('is_unique'=>'Phone No. already exists.'));
      $this->form_validation->set_rules('email', 'Email Id', 'trim|valid_email');
      $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|is_natural');
      $this->form_validation->set_rules('dob', 'Date of birth', 'trim|required');
      $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
      $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
      $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
      $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
      if(!empty($data['social_type'])){
        $this->form_validation->set_rules('social_type', 'Social Type', 'in_list[FACEBOOK,TWITTER]|xss_clean');
        $email_verify = 0;
        $is_social_signup = 1;
        
      }else{
        $email_verify = 0;
        $is_social_signup = 0;
      }
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();

        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        
      } else {
        $social_type = extract_value($data, 'social_type', '');
        $social_id = extract_value($data, 'social_id', '');

        $dataArr = array();
        $email = extract_value($data, 'email', '');
        $password = extract_value($data, 'password', '');
        $email = strtolower(extract_value($data, 'email', ''));
        $phone = extract_value($data, 'phone_number', '');
        $name = extract_value($data, 'full_name', '');
        $dataArr['full_name'] = extract_value($data, 'full_name', '');
        $dataArr['email'] = extract_value($data, 'email', '');
        $dataArr['date_of_birth'] = extract_value($data, 'dob', '');
        $dataArr['gender'] = extract_value($data, 'gender', '');
        $dataArr['login_session_key'] = get_guid();
        $dataArr['device_token'] = extract_value($data, 'device_token', '');
        $dataArr['device_type'] = extract_value($data, 'device_type', '');
        $dataArr['device_id'] = extract_value($data, 'device_id', '');
        $dataArr['active'] = 1;
        $dataArr['created_on'] = datetime();
        // $dataArr['social_type'] = extract_value($data, 'social_type', '');
        $dataArr['social_type'] = NULL;
        $dataArr['social_id'] = extract_value($data, 'social_id', '');
        $dataArr['email_verify'] = $email_verify;
        $dataArr['is_social_signup'] = $is_social_signup;
        
        $this->common_model->deleteData(USERS_DEVICE_HISTORY,array('device_token'=>$dataArr['device_token']));

        $lid = $this->userauth->signup($email,$phone,$password, $dataArr);
        
        /* To Generate random 6 digit code for activation */
        // $active = $this->userauth->code_generation('signup',$email);
        $active = $this->userauth->code_generation('signup',$phone,'phone');
        
        //send OTP SMS
          $postfields = array(
            'dest'   => $phone,
            'msg'    => OTP_SMS_MSG.$active, 
            'send'   => OTP_SMS_SEND, 
            'concat' => 1, 
            'uname'  => OTP_SMS_UNAME, 
            'pass'   => OTP_SMS_PWD
          ); 
          $result = Execute_Curl_URL(OTP_SMS_URL,$postfields);

        // $from = FROM_EMAIL;
        // $subject = "New User has been Registered";
        
        // $data['content'] = "Congratulation! You were successfully registered.To verify your email address please click on verify link. <a href='".base_url()."Auth/verifyemail/".encoding($active)."'>Click me</a>";

        // $data['user'] = ucwords($name);

        // $message = $this->load->view('email_template', $data, true);

        // $title = "New User";

        // send mail
        // send_mail($message, $subject, $email, $from, $title);

        /* Insert User Data Into Users Table */

        if ($lid) {
          
          //Give Wallet amount on first installation
          if($lid != '' && $dataArr['device_id'] != ''){
            $devicehisroy = $this->db->get_where(DEVICEHISTORY,array('device_id'=>$dataArr['device_id']))->row_array();
            if(empty($devicehisroy)){

              /*Insert data in device history*/
              $DeviceHistory_ID = $this->db->insert(DEVICEHISTORY,array('device_id'=>$dataArr['device_id']));
              if($DeviceHistory_ID){
                $wallet_amount = getConfig('wallet_amount');

                /*Insert data in user wallet*/
                $wallet_id = $this->db->insert(USERWALLET,array('user_id'=>$lid,'amount'=>$wallet_amount)); 
                if($wallet_id){
                  $walletnotifi = "Congratulations! You have successfully registered . Please verify your mobile no. to get ".$wallet_amount."rs in wallet";

                  $walletMSG = "Congratulations! You have got ".$wallet_amount."rs on Calypso App installation";

                  /*Insert data in wallet for history*/
                  $walletHistoryData = array(
                    'user_id'=>$lid,
                    'order_id'=>0,
                    'transaction_type'=>'CREDIT',
                    'amount'=>$wallet_amount,
                    'description'=>$walletMSG,
                    'transcation_user_type'=>1,
                    'date'=>date('Y-m-d H:i:s')
                  );
                  $opt = array(
                    'table'=>WALLET,
                    'data'=>$walletHistoryData
                  );
                  $this->common_model->customInsert($opt);

                  /*Insert data in user notification*/
                  $notifiData = array(
                    'type_id'    => 0,
                    'sender_id'  => 1,
                    'type'       => 0,
                    'notification_type'=>'Installation Reward',
                    'reciever_id'=> $lid,
                    'title'      => "Installation Reward",
                    'message'    => $walletnotifi,
                    'is_read'    => 0,
                    'sent_time'  => date("Y-m-d H:i:s")
                  );
                  $op = array(
                    'table'=>USER_NOTIFICATION,
                    'data'=>$notifiData
                  );

                  $notiID = $this->common_model->customInsert($op);
                  
                  /*Update data in user for badges*/  
                  if($notiID){
                    $this->db->where('id',$lid);
                    $this->db->update(USERS,array('badges'=>1));
                  }
                }
              }
            }
          }


          /* Save user device history */
          save_user_device_history($lid, $dataArr['device_token'], $dataArr['device_type'], $dataArr['device_id']);

          /* Return success response */
          echo $this->jsonresponse->geneate_response(false,1,'',['message'=>'User registered successfully, please verify your Mobile  number.']);

        }else{
          echo $this->jsonresponse->geneate_response(true, 0,'Error is unknown!','');
        }
      }
    }


    /**
     * Function Name: login
     * Description:   To User Login
     */
    function login_post() {

      $data = $this->input->post();
      $this->form_validation->set_rules('email', 'Email Id', 'trim|valid_email');
      $this->form_validation->set_rules('phone', 'Phone', 'trim');
      $this->form_validation->set_rules('password', 'Password', 'trim|required');
      $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
      $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
      $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {
        $response = array();
        $dataArr  = array();
        $response = array();
        $eachArr  = array();
        $upload_url = base_url().'uploads/users/';
        $email    = extract_value($data, 'email', '');
        $phone    = extract_value($data, 'phone', '');
        $password = extract_value($data, 'password', '');
        if($email != ''){
          $unique_id = $email;
          $dataArr['email'] = extract_value($data, 'email', '');
        }else{
          $unique_id = $phone;
          $dataArr['phone'] = extract_value($data, 'phone', '');
        }
        $dataArr['password'] = md5(extract_value($data, 'password', ''));
        /* Get User Data From Users Table */
        if($unique_id){
          $Status = $this->userauth->login($unique_id, $password);
        }else{
          echo '{"error":true,"status":0,"error_message":"email or phone is required","data":{}}';die;
        }

        if ($Status) {
          $Status1 = $this->common_model->getsingle(USERS, $dataArr);
        }
        if (empty($Status1)) {

          echo $this->jsonresponse->geneate_response(1, 0,'Invalid Email-id or Password!',[]);

        } else if (!empty($Status1) && $Status1->email_verify == 0) {
          
          // echo $this->jsonresponse->geneate_response(1, -1,'Currently your profile is not verified, please verfiy your email id.',[]);die;
          
          $res['response'] = array('phone_number'=>$Status1->phone);
          echo '{"error":true,"status":-2,"error_message":"Currently your profile is not verified, please verfiy first.","data":'.json_encode($res).'}';

        } else if (!empty($Status1) && $Status1->is_blocked == 1) {

          echo $this->jsonresponse->geneate_response(1, -1,BLOCK_USER,[]);

        } else if (!empty($Status1) && $Status1->active == 0) {

         echo $this->jsonresponse->geneate_response(1, -1,DEACTIVATE_USER,[]);

       } else if ($Status1->email_verify == 1 && $Status1->is_blocked == 0 && $Status1->active == 1) {

        

        /* Update User Data */
        $UpdateData = array();
        $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
        $UpdateData['device_token'] = extract_value($data, 'device_token', NULL);
        $UpdateData['device_id'] = extract_value($data, 'device_id', NULL);
        $UpdateData['is_logged_out'] = 0;
        $UpdateData['login_session_key'] = get_guid();
        $UpdateData['last_login'] = datetime();
        $this->common_model->updateFields(USERS, $UpdateData, array('id' => $Status1->id));
        /* Save user device history */
        $this->common_model->deleteData(USERS_DEVICE_HISTORY,array('user_id' => $Status1->id));
        save_user_device_history($Status1->id, $UpdateData['device_token'], $UpdateData['device_type'], $UpdateData['device_id']);

        $user_wallet = 0;
        if($Status1->id != ''):
          $user_wallet = $this->common_model->user_wallet_amount($Status1->id);
        endif;


        if(!empty($Status1->user_image)){
          $image = base_url().$Status1->user_image;
        } else{
          /* set default image if empty */
          $image = base_url().DEFAULT_NO_IMG_PATH;
        }

        /* Return Response */
        $response['user_id']          = null_checker($Status1->id);
        $response['full_name']        = null_checker($Status1->full_name);
        $response['email']            = null_checker($Status1->email);
        $response['date_of_birth']    = null_checker(date_check_convertFormat(convertDate($Status1->date_of_birth)));
        $response['phone_number']     = null_checker($Status1->phone);
        $response['address']          = null_checker($Status1->address);
        $response['occupation']       = null_checker($Status1->occupation);
        $response['near_landmark']    = null_checker($Status1->near_landmark);
        $response['anniversary']      = null_checker(date_check_convertFormat(convertDate($Status1->anniversary)));
        $response['gender']           = null_checker($Status1->gender);
        $response['user_image']       = $image;
        $response['login_session_key']= $UpdateData['login_session_key'];
        // $response['badges']           = null_checker($Status1->badges);
        $response['badges']           = $this->common_model->get_user_badges($Status1->id);
        $response['wallet_amount']    = $user_wallet;
        
        echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'User logged in successfully']);
           
      }
    }

  }


     
     
    /**
     * Function Name: forgot_password
     * Description:   To User Forgot Password using Email
     */
    function forgot_password_post() {
      $data = $this->input->post();
      $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {
        $dataArr = array();
        $email= extract_value($data, 'email', '');
        $dataArr['email'] = extract_value($data, 'email', '');

        // Get User Data From Users Table 
        
        $result = $this->common_model->getsingle(USERS, $dataArr);

        if(empty($result)){
          echo $this->jsonresponse->geneate_response(1, 0,'User is not registered!',[]);
        }else if ($result->email_verify == 1) {
          $status = $this->userauth->forgot_password($email);
          if ($status) {
            echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'An email has been sent. Please check your inbox.']);
          } 
        } else {
          echo $this->jsonresponse->geneate_response(1, -1,'Currently your profile is not verified!',[]);
        }
      }
    }

    /**
     * Function Name: forgot_password
     * Description:   To User Forgot Password using Mobile No.
    */
    function forgot_password_phone_post() {
      $data = $this->input->post();
      $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {
        $dataArr = array();
        $phone= extract_value($data, 'phone', '');
        $dataArr['phone'] = extract_value($data, 'phone', '');

        /* Get User Data From Users Table */
        
        $result = $this->common_model->getsingle(USERS, $dataArr);

        if(empty($result)){
          echo $this->jsonresponse->geneate_response(1, 0,'User is not registered!',[]);
        }else if ($result->email_verify == 1) {

          $status = $this->userauth->forgot_password_app_phone($phone);
          if ($status) {
            echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'An OTP has been sent. Please check your phone inbox.']);
          } 
        } else {
          echo $this->jsonresponse->geneate_response(1, -2,'Currently your profile is not verified!',[]);
        }
      }
    }

    /**
     * Function Name: address_list
     * Description:   To get address
    */
    function address_list_post() {
        $data = $this->input->post();
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

        if ($this->form_validation->run() == FALSE) {
          $error = $this->form_validation->rest_first_error_string();
          echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {

          $user_id = extract_value($data,'user_id','');  
          $page_no = extract_value($data,'page_no',1);  
          $offset  = get_offsets($page_no);

          is_deactive($user_id);


          $options = array(
            'table' => USERADDRESS,
              'select' => '*',
              'where' => array('user_id'=>$user_id),
              'order' => array('id' => 'desc'),
              'limit' => array(10 => $offset)
            );

          /* To get offer list from offer table */
          $list = $this->common_model->customGet($options);
          /* check for image empty or not */

          if (!empty($list)) {

              $eachArr = array();

              $total_requested = (int) $page_no * 10; 

              /* Get total records */  
              $total_records = getAllCount(USERADDRESS);

              if($total_records > $total_requested){                      
                $has_next = TRUE;                    
              }else{                        
                $has_next = FALSE;                    
              }

              foreach ($list as $rows):
                $temp['id']       = null_checker($rows->id);
                $temp['user_id']  = null_checker($rows->user_id);
                $temp['address']  = null_checker($rows->address);

                $eachArr[] = $temp;
              endforeach;
              /* return success response*/

              echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Address found successfully']);

            }else {
              echo $this->jsonresponse->geneate_response(1, 0,'Address not found',[]);
            }
        }

    }


     /**
     * Function Name: phone_verify
     * Description:   To Verify phone number
    */
    function phone_verify_post()
    {
      $data = $this->input->post();
      $this->form_validation->set_rules('phone', 'Phone No', 'trim|required|numeric');
      $this->form_validation->set_rules('code', 'Code', 'trim|required');
      $this->form_validation->set_rules('device_token', 'Device token', 'trim|required');
      $this->form_validation->set_rules('device_type', 'Device type', 'trim|required');
      $this->form_validation->set_rules('device_id', 'Device id', 'trim|required');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      }else{
        $eachArr      = array();
        $phone        = extract_value($data, 'phone', '');
        $code         = extract_value($data, 'code', '');
        $device_token = extract_value($data, 'device_token', '');
        $device_type  = extract_value($data, 'device_type', '');
        $device_id    = extract_value($data, 'device_id', '');
        
         /* To verify activation code */
        $verify = $this->userauth->activecode_verification($phone,$code,'login');
        if($verify){
          $userdata = $this->common_model->getsingle(USERS, array('phone'=>$phone));
          if(!empty($userdata)){
            if (!empty($userdata) && $userdata->is_blocked == 1) {
              echo $this->jsonresponse->geneate_response(1, -1,BLOCK_USER,[]);
            } else if (!empty($userdata) && $userdata->active == 0) {
              echo $this->jsonresponse->geneate_response(1, -1,DEACTIVATE_USER,[]);
            } else if ($userdata->email_verify == 1 && $userdata->is_blocked == 0 && $userdata->active == 1){

              /* Save user device history */
              $this->common_model->deleteData(USERS_DEVICE_HISTORY,array('user_id' => $userdata->id));
              save_user_device_history($userdata->id, $device_token, $device_type, $device_id);

              if(!empty($userdata->user_image)){
                $image = base_url().$userdata->user_image;
              } else{
                /* set default image if empty */
                $image = base_url().DEFAULT_NO_IMG_PATH;
              }

              /* Return Response */
              $response['user_id']          = null_checker($userdata->id);
              $response['full_name']        = null_checker($userdata->full_name);
              $response['email']            = null_checker($userdata->email);
              $response['date_of_birth']    = null_checker(date_check_convertFormat(convertDate($userdata->date_of_birth)));
              $response['phone_number']     = null_checker($userdata->phone);
              $response['gender']     = null_checker($userdata->gender);
              $response['address']          = null_checker($userdata->address);
              $response['occupation']       = null_checker($userdata->occupation);
              $response['near_landmark']    = null_checker($userdata->near_landmark);
              $response['anniversary']      = null_checker(date_check_convertFormat(convertDate($userdata->anniversary)));
              $response['user_image']       = $image;
              $response['login_session_key']= get_guid();
              // $response['badges']           = null_checker($userdata->badges);
              $response['badges']           = $this->common_model->get_user_badges($userdata->id);

              echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response ,'message'=>'Your Phone no. has been verified.']);  
            }
          }else{
            echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);  
          }
        }else{
          echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
        }
      }
    }


    /**
     * Function Name: change_password
     * Description:   To User Change Password
    */

  function change_password_post() {
    $data = $this->input->post();
    
    $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
    $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
    $this->form_validation->set_rules('new_password', 'New Password', 'trim|required');
    
    if ($this->form_validation->run() == FALSE) {
      $error = $this->form_validation->rest_first_error_string();
      echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
    } else {
      $current_password = extract_value($data, 'current_password', "");
      $new_password = extract_value($data, 'new_password', "");
      

      /* To check user change password */
      $email = $this->user_details->email;
      $change = $this->userauth->change_password($email, $current_password, $new_password);
      if (!empty($change)) {
       echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'The new password has been saved successfully!']);
       
     } 
   }
   
 }
    /**
     * Function Name: reset_password
     * Description:   To User Reset Password using email
     */

 function reset_password_post() {
  $data = $this->input->post();
  
  $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
  $this->form_validation->set_rules('password', 'Password', 'trim|required');
  
  if ($this->form_validation->run() == FALSE) {
    $error = $this->form_validation->rest_first_error_string();
    echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
  } else {
    $email = extract_value($data, 'email', "");
    $password = extract_value($data, 'password', "");
    $result = $this->common_model->getsingle(USERS, array('email'=>$email));
    if(!empty($result)){
      /* To user update password */
      
      $UpdateData['password'] = md5($password);
      $update = $this->common_model->updateFields(USERS, $UpdateData, array('email' => $email));
      if (!empty($update)) {
       echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Password changed successfully!']);
       
     } else{
       echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Password changed successfully!']);
     }
   }else{
    echo $this->jsonresponse->geneate_response(1, 0,'Email id not found!',[]);
  }
}

}

/**
     * Function Name: reset_password
     * Description:   To User Reset Password using phone
     */
 function reset_password_phone_post() {
  $data = $this->input->post();
  
  $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
  $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|is_natural');
  
  if ($this->form_validation->run() == FALSE) {
    $error = $this->form_validation->rest_first_error_string();
    echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
  } else {
    $phone = extract_value($data, 'phone', "");
    $password = extract_value($data, 'password', "");
    $result = $this->common_model->getsingle(USERS, array('phone'=>$phone));
    if(!empty($result)){
      /* To user update password */
      
      $UpdateData['password'] = md5($password);
      $update = $this->common_model->updateFields(USERS, $UpdateData, array('phone' => $phone));
      if (!empty($update)) {
       echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Password changed successfully!']);
       
     } else{
       echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Password changed successfully!']);
     }
   }else{
    echo $this->jsonresponse->geneate_response(1, 0,'Phone not found!',[]);
  }
}

}

    /*
     * Function Name: logout
     * Description:   To User Logout
     */

    function logout_post() {
      $data = $this->input->post();
      $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
      $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
      $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
      $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else {
        /* User Data Array */

        $user_data                  = array();
        $user_id                    = extract_value($data, 'user_id', NULL);
        $user_data['device_type']   = extract_value($data, 'device_type', NULL);
        $user_data['device_id']     = extract_value($data, 'device_id', NULL);
        $user_data['device_token']  = extract_value($data, 'device_token', NULL);
        $user_data['user_id']       = extract_value($data, 'user_id', NULL);

        $result = $this->common_model->getsingle(USERS, array('id'=>$user_id));
        if(!empty($result)){
        /* Update User logout status */
        $this->common_model->updateFields(USERS, array('is_logged_out' => 1), array('id' => $user_id));

        /* Delete User Device History */
        $this->common_model->deleteData(USERS_DEVICE_HISTORY, $user_data);
        
         echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'User logged out successfully!']);
       }else{
        echo $this->jsonresponse->geneate_response(1, 0,'Invalid User!',[]);
       }
      }
      
    }

    /**
     * Function Name: clear_badges
     * Description:   To Clear Notification Badges
     */
    function clear_badges_post() {
        $data = $this->input->post();
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {

             $user_id   = extract_value($data, 'user_id', NULL);

             $result = $this->common_model->getsingle(USERS, array('id'=>$user_id));
            if(!empty($result)){
            $this->common_model->updateFields(USERS, array('badges' => 0), array('id' => $user_id));
             echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Badges cleared successfully!']);
          }else{
             echo $this->jsonresponse->geneate_response(1, 0,'Invalid user!',[]);
          }
        }
    }
    /**
     * Function Name: signup_codeRegenerate
     * Description:   To Rgenerate code for signup and forgot password
     */
    
    function signup_codeRegenerate_post(){
      $data = $this->input->post();
      $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
      $this->form_validation->set_rules('type', 'Type', 'trim|required');

      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else{
       $email= extract_value($data, 'email', '');
       $type= extract_value($data, 'type', '');
       $result = $this->common_model->getsingle(USERS, array('email'=>$email));
       if(!empty($result)){
         $name = $result->full_name;
         if($type =='Signup')
         {  
          /* To Generate random 6 digit code */
          $active = $this->userauth->code_generation('signup',$email,'');
          
          $from = FROM_EMAIL;
          $subject = "Activation code";
          $data['content'] = "Your activation code is :" .$active;
          $data['user'] = ucwords($name);

          $message = $this->load->view('email_template', $data, true);

          $title = "Activation code";

          /* send mail */
          $sent_mail = send_mail($message, $subject, $email, $from, $title);
        }

        else if($type =='Forgot Password')
        {
          /* To Generate random 6 digit code */
         $active = $this->userauth->code_generation('',$email,'');
         
         $from = FROM_EMAIL;
         $subject = "Confirmation Code";
         $data['content'] = "Your confirmation code is :" .$active. ".This code is active for 15 miniute.";
         $data['user'] = ucwords($name);

         $message = $this->load->view('email_template', $data, true);

         $title = "Confirmation Code";
         /* send mail */
         $sent_mail= send_mail($message, $subject, $email, $from, $title);
         
         
       }
       if($sent_mail)
       {
        echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Please check your email for Activation Code and proceed further.']);
      }
      
      else
      {
        echo $this->jsonresponse->geneate_response(1, 0,'Code send unsuccessful!',[]);
      }
    }else{
     echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
   }
 }

}

    function signup_codeRegeneratePhon_post(){
      $data = $this->input->post();
      $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|numeric');
      $this->form_validation->set_rules('type', 'Type', 'trim|required|numeric');
    
      //type 1- signup , 2- forgot password


      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      }else{
        $phone = extract_value($data, 'phone', '');
        $type = extract_value($data, 'type', '');
       
        $result = $this->common_model->getsingle(USERS, array('phone'=>$phone));
        if(!empty($result)){
          
          $email = $result->email;
          /* To Generate random 4 digit code for activation */
          if($type == 1){
            $msg = OTP_SMS_MSG;
            $message = 'Please check your phone inbox for Activation Code and proceed further.';
            $active = $this->userauth->code_generation('signup',$email);
          }else{
            $msg = FP_SMS_MSG;
            $message = 'Please check your phone inbox for Recovery Code and proceed further.';
            // $active = $this->userauth->code_generation('signup',$email);
            $active = $this->userauth->code_generation('',$phone,'phone');
          }
        
          
          //send OTP SMS
          $postfields = array(
            'dest' => $phone,
            'msg'    => $msg.$active, 
            'send'   => OTP_SMS_SEND, 
            'concat' => 1, 
            'uname'  => OTP_SMS_UNAME, 
            'pass'   => OTP_SMS_PWD
          ); 
          $result = Execute_Curl_URL(OTP_SMS_URL,$postfields);
          if($result){
            echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>$message]);
          }else{
            echo $this->jsonresponse->geneate_response(1, 0,'Code send unsuccessful!',[]);
          }
        }else{
            echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
        }
      }

    }
  
 /**
     * Function Name: email_verify
     * Description:   To Verify email
  */

function email_verify_post()
{
 $data = $this->input->post();
 $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
 $this->form_validation->set_rules('code', 'Code', 'trim|required');

 if ($this->form_validation->run() == FALSE) {
  $error = $this->form_validation->rest_first_error_string();
  echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
} 
else
{
  $eachArr  = array();
  $email= extract_value($data, 'email', '');
  $code= extract_value($data, 'code', '');
  $upload_url = base_url().'uploads/product/';
   /* To verify activation code */
  $verify = $this->userauth->code_verification($email,$code,'login');

  if($verify==1){

   $result = $this->common_model->getsingle(USERS, array('email'=>$email));
   

   if(!empty($result)){
      $userDetailsQuery = "select u.username, u.full_name,IF(mt.token!='','1','0')as hastoken,IF(o.id!='','1','0')as hasorder,IF(um.membership_type!='','1','0')as hasmembership,o.id as order_id,o.order_date,o.total_price,o.status,um.membership_type,um.subscription_expiry_date,mt.token,mt.expiry_date,u.current_wallet_balance as walletbalance from users u LEFT JOIN user_membership um on um.user_id = u.id LEFT JOIN orders o on o.user_id = u.id LEFT JOIN membership_token mt on mt.user_id = u.id where u.id = ".$result->id;

     $res = $this->common_model->customQuery($userDetailsQuery,true);
        /* Get Order Details from order table */
        $options= array('table' => ORDERS.' as order',
                  'select'=> 'order.id,order.status,order.order_date,order.total_price',
                  'where'=> array('order.user_id'=>$result->id),
                  'order' => array('id'=>'DESC'),
                  'limit' => 1,
                  'single'=>true
               
            );
           $order_details = $this->common_model->customGet($options);
      if(!empty($order_details)){

         if($order_details->status==1)
             {
              $order_status = 'Delivered';
             }else if($order_details->status==2)
             {
               $order_status = 'In Process';
             }else{
              $order_status = 'Ready';
             }
            /* Get Ordered Products from order_meta table */ 
         $options= array('table' => ORDER_META.' as order_meta',
                  'select'=> 'order_meta.product_id,product.product_name,product.image,order_meta.product_qty,order_meta.product_price',
                  'join'=> array(PRODUCTS. ' as product'=>'product.id=order_meta.product_id'),
                  'where'=> array('order_meta.order_id'=>$order_details->id)
               
            );
           $order_meta = $this->common_model->customGet($options);
          
            foreach($order_meta as $order)
           {
             /* check for image empty or not */
             if(!empty($order->image))
             {
              $image = $upload_url.$order->image;
             }
            else
            {
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
             $temp4['product_id']        = null_checker($order->product_id);
             $temp4['product_name']      = null_checker($order->product_name);
             $temp4['product_qty']       = null_checker($order->product_qty);
             $temp4['product_price']     = null_checker($order->product_price);
             $temp4['product_image']     = $image;
             $eachArr[]=$temp4;
            
          }
          $temp1['ordered_products'] = $eachArr;
       }
         if(!empty($result->user_image))
            {
              $image = base_url().$result->user_image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
     /* To check token expiry  */
     $token_expiry = $res->expiry_date;
     $current_date = date('Y-m-d H:i:s',strtotime('-1 day'));

     if($current_date > $token_expiry)
     {
       $expiry = true;
     }else{
       $expiry = false;
     }
     /* To check membership expiry  */
     $member_expiry = $res->subscription_expiry_date;
     if($member_expiry!='' && $member_expiry!='NULL'){
       if($current_date > $member_expiry)
       {
         $sub_expiry = true;
       }else{
         $sub_expiry = false;
       }
     }else{
      $sub_expiry = false;
    }
    
    if(!empty($res))
              {

               if($res->hastoken==="0")//check has token
            {

              $response['hastoken'] = false;
              $response['token'] = new stdClass();
            }
            else
            {

              $temp['token'] = null_checker($res->token);
              $temp['is_token_expire'] = $expiry;
              $response['hastoken'] = true;
              $response['token'] = $temp;

            }
            
              if($res->hasorder==="0")//check firstime on app
              {
                $response['hasorder'] = false;
                $response['order'] = new stdClass();
              }
              else
              {
                $temp1['order_id'] = null_checker($order_details->id);
                $temp1['total_cost'] = null_checker($order_details->total_price);
                $temp1['order_date'] = null_checker(convertDate($order_details->order_date));
                $temp1['order_status'] = $order_status;
                $response['hasorder'] = true;
                $response['order'] = $temp1;
                
              }
              if($res->hasmembership==="0")//check last transaction
              {
               $response['hasmembership'] = false;
               $response['membership'] = new stdClass();
               
             }
             else
             {
              $temp2['membership_type'] = null_checker($res->membership_type);
              $temp2['is_membership_expire'] = $sub_expiry;
              $response['hasmembership'] = true;

              $response['membership'] = $temp2;
            }
              if($res->walletbalance >= 10)//check wallet
              {
                
                $response['can_by_membership'] = true;
                
              }
              else
              {
               $response['can_by_membership'] = false;
               
             }
             if(!empty($res->walletbalance))
             {
               $temp3['walletbalance']     = null_checker($res->walletbalance);
               $response['haswallet'] = true;
               $response['wallet'] = $temp3;
             }
             else
             {
               $response['haswallet'] = false;
               $response['wallet'] = new stdClass();
             }

           }
           $response['user_id']      = null_checker($result->id);
           $response['full_name']    = null_checker($result->full_name);
           $response['email']        = null_checker($result->email);
           $response['date_of_birth'] = null_checker(date_check_convertFormat(convertDate($result->date_of_birth)));
           $response['phone_number'] = null_checker($result->phone);
           $response['address']      = null_checker($result->address);
           $response['country']      = null_checker($result->country);
           $response['city']         = null_checker($result->city);
           $response['user_image']   = $image;
           $response['login_session_key'] = $result->login_session_key;
           $response['badges'] = null_checker($result->badges);
           echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response ,'message'=>'Your email has been verified!']);
           
         }else{
          echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
        }
      }
      
    }
  }
   /**
     * Function Name: ForgotPassword_verify
     * Description:   To verify Forgot password code using email
   */
  function ForgotPassword_verify_post()
  {
    $data = $this->input->post();
    $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
    $this->form_validation->set_rules('code', 'Code', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $error = $this->form_validation->rest_first_error_string();
      echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
    } 
    else
    {
      $email= extract_value($data, 'email', '');
      $code= extract_value($data, 'code', '');

       // To verify code 
      $verify = $this->userauth->code_verification($email,$code,'');
      if($verify){
         echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Your email has been verified!']);
      }else{
         echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
      }

    }
  }

 /**
     * Function Name: ForgotPassword_verify
     * Description:   To verify Forgot password code using phone
   */
  function ForgotPassword_verify_phone_post()
  {
    $data = $this->input->post();
    $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
    $this->form_validation->set_rules('code', 'Code', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $error = $this->form_validation->rest_first_error_string();
      echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
    } 
    else
    {
      $phone= extract_value($data, 'phone', '');
      $code= extract_value($data, 'code', '');

       // To verify code 
      $this->userauth->activecode_verification($phone,$code,'');
       echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Your Phone no. has been verified.']);
    }
  }  

  /**
     * Function Name: tokenGenerate
     * Description:   To ReGenerate Token for premium users
   */

function tokenGenerate_post()
{
  $data = $this->input->post();
  $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
  
  if ($this->form_validation->run() == FALSE) {
    $error = $this->form_validation->rest_first_error_string();
    echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
  } 
  else
  {
   $email= extract_value($data, 'email', '');
   
   $result = $this->common_model->getsingle(USERS, array('email'=>$email));
   if(!empty($result)){
     $name = $result->full_name;

     /* To generate token */
     $characters = '1234567890';

     $string = '';

     for ($i = 0; $i < 6; $i++) {
      $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    $from = FROM_EMAIL;
    $subject = "Activation code";
    $data['content'] = "Your membership token is :" .$string ;
    $data['user'] = ucwords($name);

    $message = $this->load->view('email_template', $data, true);

    $title = "Activation code";
    /* send mail */
    $sent_mail= send_mail($message, $subject, $email, $from, $title);
    
    $UpdateData['token']= $string;
    $UpdateData['expiry_date']=  date('Y-m-d H:i:s', strtotime('+2 days')); 

     /* To update token into table  */
    $update = $this->common_model->updateFields(MEMBERSHIP_TOKEN, $UpdateData, array('user_id' => $result->id));          
    
    
    if($sent_mail)
    {
      echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Please check your email for Token and proceed further.']);
    }
    
    else
    {
      echo $this->jsonresponse->geneate_response(1, 0,'Token send unsuccessful!',[]);
    }
  }else{
   echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
 }
}

}

/**
     * Function Name: token_verify
     * Description:   To Verify Token
 */

function token_verify_post()
{
 $data = $this->input->post();
 $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
 $this->form_validation->set_rules('token', 'Token', 'trim|required');

 if ($this->form_validation->run() == FALSE) {
  $error = $this->form_validation->rest_first_error_string();
  echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
} 
else
{
  $user_id    = extract_value($data, 'user_id', '');
  $token      = extract_value($data, 'token', '');
  $upload_url = base_url().'uploads/product/';

  $result = $this->common_model->getsingle(MEMBERSHIP_TOKEN, array('user_id'=>$user_id));
  if(!empty($result)){
    $token_expiry = $result->expiry_date;
    $current_date = date('Y-m-d H:i:s',strtotime('-1 day'));
   /* To check token expiry */
    if($current_date > $token_expiry)
    {
     echo $this->jsonresponse->geneate_response(1, 0,'Your Token has been expired,please regenerate Token!',[]);
   }
   else
   {
     if($token ==  $result->token){
       $result = $this->common_model->getsingle(USER_MEMBERSHIP, array('user_id'=>$user_id));
       if(empty($result))
       {
         $options = array(
          'table' => USER_MEMBERSHIP,
          'data' => array(
            
            'membership_type' => 'PREMIUM' ,
            'membership_subscription_date' => date('Y-m-d'),
            'subscription_expiry_date' => date('Y-m-d', strtotime('+10 days')),
            'user_id' => $user_id
            )
          );
         $membership =  $this->common_model->customInsert($options);
          /* To Insert membership */
       }
       else
       {

        $options = array(
          'table' => USER_MEMBERSHIP,
          'data' => array(
            
            'membership_type' => 'PREMIUM' ,
            'membership_subscription_date' => date('Y-m-d'),
            'subscription_expiry_date' => date('Y-m-d', strtotime('+10 days'))
            ),
          'where' => array('user_id' => $user_id)
          );
        $membership =  $this->common_model->customUpdate($options);
        /* To Update membership */
      }
    
      $users = $this->common_model->getsingle(USERS, array('id'=>$user_id));
   

      $userDetailsQuery = "select u.username, u.full_name,IF(mt.token!='','1','0')as hastoken,IF(o.id!='','1','0')as hasorder,IF(um.membership_type!='','1','0')as hasmembership,o.id as order_id,o.order_date,o.total_price,o.status,um.membership_type,um.subscription_expiry_date,mt.token,mt.expiry_date,u.current_wallet_balance as walletbalance from users u LEFT JOIN user_membership um on um.user_id = u.id LEFT JOIN orders o on o.user_id = u.id LEFT JOIN membership_token mt on mt.user_id = u.id where u.id = ".$users->id;

     $res = $this->common_model->customQuery($userDetailsQuery,true);
        /* Get Order Details from order table */
        $options= array('table' => ORDERS.' as order',
                  'select'=> 'order.id,order.status,order.order_date,order.total_price',
                  'where'=> array('order.user_id'=>$users->id),
                  'order' => array('id'=>'DESC'),
                  'limit' => 1,
                  'single'=>true
               
            );
           $order_details = $this->common_model->customGet($options);
      if(!empty($order_details)){

         if($order_details->status==1)
             {
              $order_status = 'Delivered';
             }else if($order_details->status==2)
             {
               $order_status = 'In Process';
             }else{
              $order_status = 'Ready';
             }
            /* Get Ordered Products from order_meta table */ 
         $options= array('table' => ORDER_META.' as order_meta',
                  'select'=> 'order_meta.product_id,product.product_name,product.image,order_meta.product_qty,order_meta.product_price',
                  'join'=> array(PRODUCTS. ' as product'=>'product.id=order_meta.product_id'),
                  'where'=> array('order_meta.order_id'=>$order_details->id)
               
            );
           $order_meta = $this->common_model->customGet($options);
          
            foreach($order_meta as $order)
           {
             /* check for image empty or not */
             if(!empty($order->image))
             {
              $image = $upload_url.$order->image;
             }
            else
            {
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
             $temp4['product_id']        = null_checker($order->product_id);
             $temp4['product_name']      = null_checker($order->product_name);
             $temp4['product_qty']       = null_checker($order->product_qty);
             $temp4['product_price']     = null_checker($order->product_price);
             $temp4['product_image']     = $image;
             $eachArr[]=$temp4;
            
          }
          $temp1['ordered_products'] = $eachArr;
       }
         if(!empty($users->user_image))
            {
              $image = base_url().$users->user_image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
     /* To check token expiry  */
     $token_expiry = $res->expiry_date;
     $current_date = date('Y-m-d H:i:s',strtotime('-1 day'));

     if($current_date > $token_expiry)
     {
       $expiry = true;
     }else{
       $expiry = false;
     }
     /* To check membership expiry  */
     $member_expiry = $res->subscription_expiry_date;
     if($member_expiry!='' && $member_expiry!='NULL'){
       if($current_date > $member_expiry)
       {
         $sub_expiry = true;
       }else{
         $sub_expiry = false;
       }
     }else{
      $sub_expiry = false;
    }
    
    if(!empty($res))
              {

               if($res->hastoken==="0")//check has token
            {

              $response['hastoken'] = false;
              $response['token'] = new stdClass();
            }
            else
            {

              $temp['token'] = null_checker($res->token);
              $temp['is_token_expire'] = $expiry;
              $response['hastoken'] = true;
              $response['token'] = $temp;

            }
            
              if($res->hasorder==="0")//check firstime on app
              {
                $response['hasorder'] = false;
                $response['order'] = new stdClass();
              }
              else
              {
                $temp1['order_id'] = null_checker($order_details->id);
                $temp1['total_cost'] = null_checker($order_details->total_price);
                $temp1['order_date'] = null_checker(convertDate($order_details->order_date));
                $temp1['order_status'] = $order_status;
                $response['hasorder'] = true;
                $response['order'] = $temp1;
                
              }
              if($res->hasmembership==="0")//check last transaction
              {
               $response['hasmembership'] = false;
               $response['membership'] = new stdClass();
               
             }
             else
             {
              $temp2['membership_type'] = null_checker($res->membership_type);
              $temp2['is_membership_expire'] = $sub_expiry;
              $response['hasmembership'] = true;

              $response['membership'] = $temp2;
            }
              if($res->walletbalance >= 10)//check wallet
              {
                
                $response['can_by_membership'] = true;
                
              }
              else
              {
               $response['can_by_membership'] = false;
               
             }
             if(!empty($res->walletbalance))
             {
               $temp3['walletbalance']     = null_checker($res->walletbalance);
               $response['haswallet'] = true;
               $response['wallet'] = $temp3;
             }
             else
             {
               $response['haswallet'] = false;
               $response['wallet'] = new stdClass();
             }

           }
           $response['user_id']      = null_checker($users->id);
           $response['full_name']    = null_checker($users->full_name);
           $response['email']        = null_checker($users->email);
           $response['date_of_birth'] = null_checker(date_check_convertFormat(convertDate($users->date_of_birth)));
           $response['phone_number'] = null_checker($users->phone);
           $response['address']      = null_checker($users->address);
           $response['country']      = null_checker($users->country);
           $response['city']         = null_checker($users->city);
           $response['user_image']   = $image;
           $response['login_session_key'] = $users->login_session_key;
           $response['badges'] = null_checker($users->badges);
          
   
      if($membership) 
      {
        echo $this->jsonresponse->geneate_response(0, 1,'',['response'=> $response,'message'=>'Your membership updated successfully!']);
      }
      else
      {
        echo $this->jsonresponse->geneate_response(0, 1,'',['response'=> $response,'message'=>'Your membership updated successfully!']);
      }
    }else{
      echo $this->jsonresponse->geneate_response(1, 0,'Invalid Token!',[]);
    }        
  }
}else{
  echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
}

}
}

/**
     * Function Name: premium_tokenGenerate
     * Description:   To Generate Token for Premium users
 */

function premium_tokenGenerate_post()
{
  $data = $this->input->post();
  $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
  
  if ($this->form_validation->run() == FALSE) {
    $error = $this->form_validation->rest_first_error_string();
    echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
  } 
  else
  {
    $current_date   = date('Y-m-d');
   $email= extract_value($data, 'email', '');
   
   $result = $this->common_model->getsingle(USERS, array('email'=>$email));
   if(!empty($result)){
     $name = $result->full_name;
     /* To generate Token */
     $characters = '1234567890';

     $string = '';

     for ($i = 0; $i < 6; $i++) {
      $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    $from = FROM_EMAIL;
    $subject = "Membership Token";
    $data['content'] = "Your membership token is :" .$string ;
    $data['user'] = ucwords($name);

    $message = $this->load->view('email_template', $data, true);

    $title = "Membership Token";
    /* send mail */
    $sent_mail= send_mail($message, $subject, $email, $from, $title);

    $token_exist = $this->common_model->getsingle(MEMBERSHIP_TOKEN, array('user_id'=>$result->id));

    /* To check Token already exist */
    if(empty($token_exist))
    {
    
    $options = array(
      'table' => MEMBERSHIP_TOKEN,
      'data' => array(
        'user_id' => $result->id,
        'token' => $string ,
        'expiry_date' => date('Y-m-d H:i:s', strtotime('+2 days')),
        
        )
      );
    $membership_token =  $this->common_model->customInsert($options);
  }else{
     $options = array(
        'table' => MEMBERSHIP_TOKEN,
       'data' => array(
        'token' => $string ,
        'expiry_date' => date('Y-m-d H:i:s', strtotime('+2 days')),
        
        ),
        'where' => array('user_id' => $result->id)
        );
     $membership_token = $this->common_model->customUpdate($options); 
  }
    /* To Generate wallet history and debit 10$ from wallet to become premium member */
      $this->wallet->add_wallet_log('DEBIT',10,$current_date,0,$result->id,'USER','',"Used ". 10 ." Points on Upgrade Membership");

    
    if($sent_mail)
    {
      echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Please check your email for Token and proceed further.']);
    }
    
    else
    {
      echo $this->jsonresponse->geneate_response(1, 0,'Token send unsuccessful!',[]);
    }
  }else{
   echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
 }
}

}

/**
     * Function Name: delete_token_post
     * Description:   To Delete Token
 */


function delete_token_post(){

 $data = $this->input->post();
 $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        //$this->form_validation->set_rules('token', 'Token', 'trim|required');

 if ($this->form_validation->run() == FALSE) {
  $error = $this->form_validation->rest_first_error_string();
  echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
} 
else
{
 $token= extract_value($data, 'token', '');
 $email= extract_value($data, 'email', '');
 
 $result = $this->common_model->getsingle(USERS, array('email'=>$email));
 $name = $result->full_name;
 $from = FROM_EMAIL;
 $subject = "Delete Token";
 $data['content'] = "We are removing this Token";
 $data['user'] = ucwords($name);

 $message = $this->load->view('email_template', $data, true);

 $title = "Delete Token";
 /* send mail */
 $sent_mail= send_mail($message, $subject, $email, $from, $title);
 
 $UpdateData['token']= ''; 

 $update = $this->common_model->updateFields(MEMBERSHIP_TOKEN, $UpdateData, array('user_id' => $result->id)); 
 if($update)
 {
   echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Token has been removed successfully!']);
 }


}

}

/**
     * Function Name: basic_membership
     * Description:   To Update Basic membership
 */


function basic_membership_post(){

 $data = $this->input->post();
 $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');

 if ($this->form_validation->run() == FALSE) {
  $error = $this->form_validation->rest_first_error_string();
  echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
} 
else
{
 $email= extract_value($data, 'email', '');
 $result = $this->common_model->getsingle(USERS, array('email'=>$email));
 if(!empty($result)){

   $options = array(
    'table' => USER_MEMBERSHIP,
    'data' => array(
      'user_id' => $result->id,
      'membership_type' => 'BASIC' ,
      'membership_subscription_date' => date('Y-m-d'),
      
      )
    );
   $membership =  $this->common_model->customInsert($options);
   /* To Insert basic membership into table */
   if($membership) 
   {
    echo $this->jsonresponse->geneate_response(0, 1,'',['message'=>'Your membership Added successfully!']);
  }
  else
  {
    echo $this->jsonresponse->geneate_response(1, 0,'Not updated!',[]);
  }
}else{
  echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
}     

}

}


 /**
   * Function Name: social_email_check
   * Description:   To Social email check
   */
 public function social_login_post(){
  $data = $this->input->post();
  
  $this->form_validation->set_rules('social_id', 'Social id', 'trim|required|xss_clean');
  $this->form_validation->set_rules('social_type', 'Social Type', 'trim|required|xss_clean');
  $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
  $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required|xss_clean');
  $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]|xss_clean');
  $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required|xss_clean');
  if ($this->form_validation->run() == FALSE) {
    $error = $this->form_validation->rest_first_error_string();
    echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
  } 
  else
  {
     $social_email = array();
     $eachArr = array();
     $phone_number = extract_value($data, 'phone_number', '');
     $upload_url = base_url().'uploads/product/';

    $email        = extract_value($data, 'email', '');
    $name         = extract_value($data, 'full_name', '');
    $social_type  = extract_value($data, 'social_type', '');
    $social_id    = extract_value($data, 'social_id', '');
    $device_token = extract_value($data, 'device_token', '');
    $device_type  = extract_value($data, 'device_type', '');
    $device_id    = extract_value($data, 'device_id', '');



    $email_details = $this->common_model->getsingle(USERS,array('social_id'=> $social_id));
     

    $UpdateData = array();
    $response = array();
    // if(!empty($phone_number))
    //  {
      
    //    $UpdateData['phone']          = extract_value($data,'phone_number',NULL);
    //  }
        
    // $UpdateData['full_name']         = extract_value($data,'full_name',NULL);
    $UpdateData['social_id']         = extract_value($data,'social_id',NULL);
    $UpdateData['social_type']       = extract_value($data,'social_type',NULL);
    $UpdateData['device_type']       = extract_value($data,'device_type',NULL);
    $UpdateData['device_id']         = extract_value($data,'device_id',NULL);
    $UpdateData['device_token']      = extract_value($data,'device_token',NULL);
    $UpdateData['is_logged_out']     = 0;

    /* Save user device history */
    if(!empty($email_details)){
      $this->common_model->deleteData(USERS_DEVICE_HISTORY,array('device_token'=>$UpdateData['device_token']));
      $this->common_model->deleteData(USERS_DEVICE_HISTORY,array('user_id' => $email_details->id));

        shell_exec("/usr/bin/php pending_cron.php {$email_details->id} >> paging.log &");//for asynchronous call
        save_user_device_history($email_details->id,$device_token,$device_type,$device_id);

        /* Update User Data */
        
        $this->common_model->updateFields(USERS,$UpdateData,array('id' => $email_details->id));

        if(!empty($email_details->user_image))
            {
              $image = base_url().$email_details->user_image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }

        $response['user_id']           = null_checker($email_details->id);
        $response['full_name']         = null_checker($email_details->full_name);
        $response['email']             = null_checker($email_details->email);
        $response['date_of_birth']     = null_checker(date_check_convertFormat(convertDate($email_details->date_of_birth)));
        $response['phone_number']      = null_checker($email_details->phone);
        $response['address']           = null_checker($email_details->address);
        $response['country']           = null_checker($email_details->country);
        $response['city']              = null_checker($email_details->city);
        $response['user_image']        = $image;
        $response['login_session_key'] = $email_details->login_session_key;
        $response['badges']            = null_checker($email_details->badges);

       $userDetailsQuery = "select u.username, u.full_name,IF(mt.token!='','1','0')as hastoken,IF(o.id!='','1','0')as hasorder,IF(um.membership_type!='','1','0')as hasmembership,o.id as order_id,o.order_date,o.total_price,o.status,um.membership_type,um.subscription_expiry_date,mt.token,mt.expiry_date,u.current_wallet_balance as walletbalance from users u LEFT JOIN user_membership um on um.user_id = u.id LEFT JOIN orders o on o.user_id = u.id LEFT JOIN membership_token mt on mt.user_id = u.id where u.id = ".$email_details->id;
        $res = $this->common_model->customQuery($userDetailsQuery,true);

        $options= array('table' => ORDERS.' as order',
                  'select'=> 'order.id,order.status,order.order_date,order.total_price',
                  'where'=> array('order.user_id'=>$email_details->id),
                  'order' => array('id'=>'DESC'),
                  'limit' => 1,
                  'single'=>true
               
            );
           $order_details = $this->common_model->customGet($options);
           if(!empty($order_details))
           {
             if($order_details->status==1)
             {
              $order_status = 'Delivered';
             }else if($order_details->status==2)
             {
               $order_status = 'In Process';
             }else{
              $order_status = 'Ready';
             }
           $options= array('table' => ORDER_META.' as order_meta',
                  'select'=> 'order_meta.product_id,product.product_name,product.image,order_meta.product_qty,order_meta.product_price',
                  'join'=> array(PRODUCTS. ' as product'=>'product.id=order_meta.product_id'),
                  'where'=> array('order_meta.order_id'=>$order_details->id)
               
            );
           $order_meta = $this->common_model->customGet($options);
          
            foreach($order_meta as $order)
           {
             /* check for image empty or not */
             if(!empty($order->image))
             {
              $image = $upload_url.$order->image;
             }
            else
            {
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
             $temp4['product_id']        = null_checker($order->product_id);
             $temp4['product_name']      = null_checker($order->product_name);
             $temp4['product_qty']       = null_checker($order->product_qty);
             $temp4['product_price']     = null_checker($order->product_price);
             $temp4['product_image']     = $image;
             $eachArr[]=$temp4;
            
          }
          $temp1['ordered_products'] = $eachArr;
        }

        $token_expiry = $res->expiry_date;
        $current_date = date('Y-m-d H:i:s',strtotime('-1 day'));

        if($current_date > $token_expiry)
        {
         $expiry = true;
       }else{
         $expiry = false;
       }
       $member_expiry = $res->subscription_expiry_date;
       if($member_expiry!='' && $member_expiry!='NULL'){
         if($current_date > $member_expiry)
         {
           $sub_expiry = true;
         }else{
           $sub_expiry = false;
         }
       }else{
        $sub_expiry = false;
      }

      
      if(!empty($res))
              { if($res->hastoken==="0")//check has token
            {

              $response['hastoken'] = false;
              $response['token'] = new stdClass();
            }
            else
            {

              $temp['token'] = null_checker($res->token);
              $temp['is_token_expire'] = $expiry;
              $response['hastoken'] = true;
              $response['token'] = $temp;

            }
            
              if($res->hasorder==="0")//check firstime on app
              {
                $response['hasorder'] = false;
                $response['order'] = new stdClass();
              }
              else
              {
                $temp1['order_id'] = null_checker($order_details->id);
                $temp1['total_cost'] = null_checker($order_details->total_price);
                $temp1['order_date'] = null_checker(convertDate($order_details->order_date));
                $temp1['order_status'] = $order_status;
                $response['hasorder'] = true;
                $response['order'] = $temp1;
                
              }
              if($res->hasmembership==="0")//check last transaction
              {
               $response['hasmembership'] = false;
               $response['membership'] = new stdClass();
               
             }
             else
             {
              $temp2['membership_type'] = null_checker($res->membership_type);
              $temp2['is_membership_expire'] = $sub_expiry;
              $response['hasmembership'] = true;

              $response['membership'] = $temp2;
            }
              if($res->walletbalance >= 10)//check wallet
              {
                
                $response['can_by_membership'] = true;
                
              }
              else
              {
               $response['can_by_membership'] = false;
               
             }
             if(!empty($res->walletbalance))
             {
               $temp3['walletbalance']     = null_checker($res->walletbalance);
               $response['haswallet'] = true;
               $response['wallet'] = $temp3;
             }
             else
             {
               $response['haswallet'] = false;
               $response['wallet'] = new stdClass();
             }

           }

           echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'User logged in successfully!']);

         }
    else
      {
          $this->form_validation->set_rules('email', 'Email Id', 'trim|required');
          if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
          } 
          else
          {

           $check_email = $this->common_model->getsingle(USERS,array('email'=> $email));

           if(!empty($check_email))
           {
             $UpdateData['email']             = extract_value($data,'email',NULL);

             $this->common_model->deleteData(USERS_DEVICE_HISTORY,array('device_token'=>$UpdateData['device_token']));
             $this->common_model->deleteData(USERS_DEVICE_HISTORY,array('user_id' => $check_email->id));

        shell_exec("/usr/bin/php pending_cron.php {$check_email->id} >> paging.log &");//for asynchronous call
        save_user_device_history($check_email->id,$device_token,$device_type,$device_id);

        /* Update User Data */
        
        $this->common_model->updateFields(USERS,$UpdateData,array('id' => $check_email->id));

         if(!empty($check_email->user_image))
            {
              $image = base_url().$check_email->user_image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }


        $response['user_id']           = null_checker($check_email->id);
        $response['full_name']         = null_checker($check_email->full_name);
        $response['email']             = null_checker($check_email->email);
        $response['date_of_birth']     = null_checker(date_check_convertFormat(convertDate($check_email->date_of_birth)));
        $response['phone_number']      = null_checker($check_email->phone);
        $response['address']           = null_checker($check_email->address);
        $response['country']           = null_checker($check_email->country);
        $response['city']              = null_checker($check_email->city);
        $response['user_image']        = $image;
        $response['login_session_key'] = $check_email->login_session_key;
        $response['badges']            = null_checker($check_email->badges);


         $userDetailsQuery = "select u.username, u.full_name,IF(mt.token!='','1','0')as hastoken,IF(o.id!='','1','0')as hasorder,IF(um.membership_type!='','1','0')as hasmembership,o.id as order_id,o.order_date,o.total_price,o.status,um.membership_type,um.subscription_expiry_date,mt.token,mt.expiry_date,u.current_wallet_balance as walletbalance from users u LEFT JOIN user_membership um on um.user_id = u.id LEFT JOIN orders o on o.user_id = u.id LEFT JOIN membership_token mt on mt.user_id = u.id where u.id = ".$check_email->id;
        $res = $this->common_model->customQuery($userDetailsQuery,true);

        $options= array('table' => ORDERS.' as order',
                  'select'=> 'order.id,order.status,order.order_date,order.total_price',
                  'where'=> array('order.user_id'=>$check_email->id),
                  'order' => array('id'=>'DESC'),
                  'limit' => 1,
                  'single'=>true
               
            );
           $order_details = $this->common_model->customGet($options);
       
      }

      else{



       $UpdateData['full_name']  = $name;
       $UpdateData['phone']      = extract_value($data,'phone_number',NULL);
       $UpdateData['email']      = extract_value($data,'email',NULL);
       $UpdateData['created_on'] = datetime();
       $UpdateData['active'] = 1;
       $UpdateData['email_verify'] = 1;
       $UpdateData['login_session_key'] = get_guid();
        $this->common_model->deleteData(USERS_DEVICE_HISTORY,array('device_token'=>$UpdateData['device_token']));
       $options = array(
        'table' => USERS,
        'data' => $UpdateData
        );
       $user =  $this->common_model->customInsert($options);

       save_user_device_history($user,$device_token,$device_type,$device_id);

       $user_details = $this->common_model->getsingle(USERS,array('id'=> $user));

       if(!empty($user_details->user_image))
            {
              $image_user = base_url().$user_details->user_image;
            } else{
              /* set default image if empty */
              $image_user = base_url().DEFAULT_NO_IMG_PATH;
            }

        $response['user_id']           = $user;
        $response['full_name']         = $name;
        $response['email']             = $email;
        $response['date_of_birth']     = null_checker(date_check_convertFormat(convertDate($user_details->date_of_birth)));
        $response['phone_number']      = null_checker($user_details->phone);
        $response['address']           = null_checker($user_details->address);
        $response['country']           = null_checker($user_details->country);
        $response['city']              = null_checker($user_details->city);
        $response['user_image']        = $image_user;
        $response['login_session_key'] = $UpdateData['login_session_key'];
        $response['badges']            = null_checker($user_details->badges);


       $userDetailsQuery = "select u.username, u.full_name,IF(mt.token!='','1','0')as hastoken,IF(o.id!='','1','0')as hasorder,IF(um.membership_type!='','1','0')as hasmembership,o.id as order_id,o.order_date,o.total_price,o.status,um.membership_type,um.subscription_expiry_date,mt.token,mt.expiry_date,u.current_wallet_balance as walletbalance from users u LEFT JOIN user_membership um on um.user_id = u.id LEFT JOIN orders o on o.user_id = u.id LEFT JOIN membership_token mt on mt.user_id = u.id where u.id = ".$user;
       $res = $this->common_model->customQuery($userDetailsQuery,true);

        $options= array('table' => ORDERS.' as order',
                  'select'=> 'order.id,order.status,order.order_date,order.total_price',
                  'where'=> array('order.user_id'=>$user),
                  'order' => array('id'=>'DESC'),
                  'limit' => 1,
                  'single'=>true
               
            );
           $order_details = $this->common_model->customGet($options);

     }

         if(!empty($order_details)){

           if($order_details->status==1)
             {
              $order_status = 'Delivered';
             }else if($order_details->status==2)
             {
               $order_status = 'In Process';
             }else{
              $order_status = 'Ready';
             }
           $options= array('table' => ORDER_META.' as order_meta',
                  'select'=> 'order_meta.product_id,product.product_name,product.image,order_meta.product_qty,order_meta.product_price',
                  'join'=> array(PRODUCTS. ' as product'=>'product.id=order_meta.product_id'),
                  'where'=> array('order_meta.order_id'=>$order_details->id)
               
            );
           $order_meta = $this->common_model->customGet($options);
          
            foreach($order_meta as $order)
           {
             /* check for image empty or not */
             if(!empty($order->image))
             {
              $image = $upload_url.$order->image;
             }
            else
            {
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
             $temp4['product_id']        = null_checker($order->product_id);
             $temp4['product_name']      = null_checker($order->product_name);
             $temp4['product_qty']       = null_checker($order->product_qty);
             $temp4['product_price']     = null_checker($order->product_price);
             $temp4['product_image']     = $image;
             $eachArr[]=$temp4;
            
          }
          $temp1['ordered_products'] = $eachArr;
      }
        //$res = $this->common_model->customQuery($userDetailsQuery,true);
     $token_expiry = $res->expiry_date;
     $current_date = date('Y-m-d H:i:s',strtotime('-1 day'));

     if($current_date > $token_expiry)
     {
       $expiry = true;
     }else{
       $expiry = false;
     }
     $member_expiry = $res->subscription_expiry_date;
     if($member_expiry!='' && $member_expiry!='NULL'){
       if($current_date > $member_expiry)
       {
         $sub_expiry = true;
       }else{
         $sub_expiry = false;
       }
     }else{
      $sub_expiry = false;
    }

        

    if(!empty($res))
              { if($res->hastoken==="0")//check has token
            {

              $response['hastoken'] = false;
              $response['token'] = new stdClass();
            }
            else
            {

              $temp['token'] = null_checker($res->token);
              $temp['is_token_expire'] = $expiry;
              $response['hastoken'] = true;
              $response['token'] = $temp;

            }
            
              if($res->hasorder==="0")//check firstime on app
              {
                $response['hasorder'] = false;
                $response['order'] = new stdClass();
              }
              else
              {
                $temp1['order_id'] = null_checker($order_details->id);
                $temp1['total_cost'] = null_checker($order_details->total_price);
                $temp1['order_date'] = null_checker(convertDate($order_details->order_date));
                $temp1['order_status'] = $order_status;
                $response['hasorder'] = true;
                $response['order'] = $temp1;
                
              }
              if($res->hasmembership==="0")//check last transaction
              {
               $response['hasmembership'] = false;
               $response['membership'] = new stdClass();
               
             }
             else
             {
              $temp2['membership_type'] = null_checker($res->membership_type);
              $temp2['is_membership_expire'] = $sub_expiry;
              $response['hasmembership'] = true;

              $response['membership'] = $temp2;
            }
              if($res->walletbalance >= 10)//check wallet
              {
                
                $response['can_by_membership'] = true;
                
              }
              else
              {
               $response['can_by_membership'] = false;
               
             }
             if(!empty($res->walletbalance))
             {
               $temp3['walletbalance']     = null_checker($res->walletbalance);
               $response['haswallet'] = true;
               $response['wallet'] = $temp3;
             }
             else
             {
               $response['haswallet'] = false;
               $response['wallet'] = new stdClass();
             }

           }

           echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'User logged in successfully!']);
         }
       }
       
     }
   }

   public function user_profile_update_post()
   {
      $data = $this->input->post();
      if(isset($data['email']) && $data['email'] != ''){
        $this->form_validation->set_rules('email', 'Email Id', 'trim|valid_email');
      }
      $this->form_validation->set_rules('user_id', 'User id', 'trim|required|xss_clean');
      $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required|xss_clean');
      $this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]|is_natural');
      // $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|numeric|is_unique[users.phone]');
      $this->form_validation->set_rules('dob', 'Date of birth', 'trim|required');
      $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
      
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      } else{
          $dataArr = array();
          $user_id = extract_value($data, 'user_id', '');
          $password = extract_value($data, 'password', '');
          
          is_deactive($user_id);


          $result = $this->common_model->getsingle(USERS, array('id'=>$user_id));
          if(!empty($result)){
            $image = array();
            if(!empty($_FILES)){
              $image = fileUpload('user_image', 'users', 'png|jpg|jpeg|gif');
              if (isset($image['error'])) {
                echo $this->jsonresponse->geneate_response(1, 0,$image['error'],[]);
              }else{
                $dataArr['user_image'] = 'uploads/users/'.$image['upload_data']['file_name'];
              }  
            }
            
            if(isset($data['email']) && $data['email'] != ''){
              $email = extract_value($data, 'email', '');
              $op = array('table'=>USERS,'where'=>array('id != '=>$user_id,'email'=>$email));
              $count = $this->common_model->customCount($op);
              // $mailerr = array();
              if($count){
                // $mailerr['error'] = "Email is already exist";
                echo $this->jsonresponse->geneate_response(1, 0,'Email is already exist',[]);die;
              }
              $dataArr['email']    = extract_value($data, 'email', '');
            }

            $response                 = array();
            $dob                      = extract_value($data, 'dob', '');
            $anniversary              = extract_value($data, 'anniversary', '');
            $dataArr['full_name']     = extract_value($data, 'full_name', '');
            // $dataArr['phone']         = extract_value($data, 'phone', '');
            $dataArr['date_of_birth'] = date('Y-m-d', strtotime($dob));
            $dataArr['address']       = extract_value($data, 'address', '');
            $dataArr['anniversary']   = date('Y-m-d', strtotime($anniversary));
            $dataArr['occupation']    = extract_value($data, 'occupation', '');
            $dataArr['near_landmark'] = extract_value($data, 'landmark', '');
            $dataArr['gender']        = extract_value($data, 'gender', '');
            

            if(isset($password) && $password != ''){
              $dataArr['password']    = md5($password);
            }
            /* Update User Data Into Users Table */
            $status = $this->common_model->updateFields(USERS, $dataArr, array('id' =>$user_id));
            
            $dataArr['id'] = $user_id;
            $Status1 = $this->common_model->getsingle(USERS, $dataArr);
            if(!empty($Status1->user_image)){
              $image = base_url().$Status1->user_image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }

            /* Return Response */
            $response['user_id']          = null_checker($Status1->id);
            $response['full_name']        = null_checker($Status1->full_name);
            $response['email']            = null_checker($Status1->email);
            $response['date_of_birth']    = null_checker(date_check_convertFormat(convertDate($Status1->date_of_birth)));
            $response['phone_number']     = null_checker($Status1->phone);
            $response['address']          = null_checker($Status1->address);
            $response['occupation']       = null_checker($Status1->occupation);
            $response['near_landmark']    = null_checker($Status1->near_landmark);
            $response['anniversary']      = null_checker(date_check_convertFormat(convertDate($Status1->anniversary)));
            $response['gender']           = null_checker($Status1->gender);
            $response['user_image']       = $image;
            $response['login_session_key']= get_guid();
            $response['badges']           = null_checker($Status1->badges);

            if($status)
            {
              echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'Profile successfully updated!']);
            }
            else
            {
               echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'No changes done in profile.']);
            }
          }
          else
          {
              echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
          }

      }

   }

  /**
     * Function Name: get_user_profile
     * Description:   To Get user profile Details
 */
    public function get_user_profile_post(){
      $data = $this->input->post();
      $this->form_validation->set_rules('user_id', 'User id', 'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE) {
        $error = $this->form_validation->rest_first_error_string();
        echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      }else{
        $dataArr = array();
        $user_id = extract_value($data, 'user_id', '');

        is_deactive($user_id);

        
        $result = $this->common_model->getsingle(USERS, array('id'=>$user_id));
        if(!empty($result)){
          if(!empty($result->user_image)){
            $image = base_url().$result->user_image;
          }else{
            $image = base_url().DEFAULT_NO_IMG_PATH;
          }
          $temp['date_of_birth']    = null_checker(date_check_convertFormat(convertDate($result->date_of_birth)));
          $temp['full_name']        = null_checker($result->full_name);
          $temp['email']            = null_checker($result->email);
          $temp['password']         = null_checker($result->password);
          $temp['phone_number']     = null_checker($result->phone);
          $temp['address']          = null_checker($result->address);
          $temp['near_landmark']    = null_checker($result->near_landmark);
          $temp['occupation']       = null_checker($result->occupation);
          $temp['gender']           = null_checker($result->gender);
          $temp['anniversary']      = null_checker(date_check_convertFormat(convertDate($result->anniversary)));
          $temp['user_image']       = $image;
          echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$temp,'message'=>'User Profile Details found successfully!']);
         
        }else{
          echo $this->jsonresponse->geneate_response(1, 0,'Profile details not found!',[]);
        }
      }
    }


   /**
     * Function Name: change_profile_image
     * Description:   To Change User Profile Image
     */
    function change_profile_image_post() {
        $data = $this->input->post();
        
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required|xss_clean');
        if (empty($_FILES['user_image']['name'])) {
            $this->form_validation->set_rules('user_image', 'User Image', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
             echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {
            $upload_url = base_url();
            $dataArr = array();
            $user_id = extract_value($data, 'user_id', '');
            /* Upload user image */

            $image = fileUpload('user_image', 'users', 'png|jpg|jpeg|gif');
            if (isset($image['error'])) {
              echo $this->jsonresponse->geneate_response(1, 0,$image['error'],[]);
            } else {
                $dataArr['user_image'] = 'uploads/users/' . $image['upload_data']['file_name'];

                /* Update User Details */
                $status = $this->common_model->updateFields(USERS, $dataArr, array('id' => $user_id));
                if ($status) {
                    /* Return Response */
                    $response = array();
                    $response['user_image'] = $upload_url . $dataArr['user_image'];
                    echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response,'message'=>'Profile successfully updated!']);
                } else {
                    $is_error = db_err_msg();
                    if ($is_error == FALSE) {
                      echo $this->jsonresponse->geneate_response(1, 0,'Profile image not updated!',[]);
              
                    } else {
                      echo $this->jsonresponse->geneate_response(1, 0,$is_error,[]);
                        
                    }
                }
            }
        }
       
    }

    /**
     * Function Name: upgrade_membership
     * Description:   To Upgrade Membership
     */

  public function upgrade_membership_post()
  {
    $data = $this->input->post();
        
    $this->form_validation->set_rules('user_id', 'User id', 'trim|required|xss_clean');
    if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
             echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {
           $user_id = extract_value($data, 'user_id', ''); 
           $eachArr = array(); 
           $upload_url = base_url().'uploads/product/'; 
           $result = $this->common_model->getsingle(USERS, array('id'=>$user_id));
          if(!empty($result)){
            $userDetailsQuery = "select u.username, u.full_name,IF(mt.token!='','1','0')as hastoken,IF(o.id!='','1','0')as hasorder,IF(um.membership_type!='','1','0')as hasmembership,o.id as order_id,o.order_date,o.total_price,o.status,um.membership_type,um.subscription_expiry_date,um.membership_subscription_date,mt.token,mt.expiry_date,u.current_wallet_balance as walletbalance from users u LEFT JOIN user_membership um on um.user_id = u.id LEFT JOIN orders o on o.user_id = u.id LEFT JOIN membership_token mt on mt.user_id = u.id where u.id = ".$result->id;
            $res = $this->common_model->customQuery($userDetailsQuery,true);
           
            $options= array('table' => ORDERS.' as order',
                  'select'=> 'order.id,order.status,order.order_date,order.total_price',
                  'where'=> array('order.user_id'=>$result->id),
                  'order' => array('id'=>'DESC'),
                  'limit' => 1,
                  'single'=>true
               
            );
           $order_details = $this->common_model->customGet($options);
          if(!empty($order_details)){
           if($order_details->status==1)
             {
              $order_status = 'Delivered';
             }else if($order_details->status==2)
             {
               $order_status = 'In Process';
             }else{
              $order_status = 'Ready';
             }

           $options= array('table' => ORDER_META.' as order_meta',
                  'select'=> 'order_meta.product_id,product.product_name,product.image,order_meta.product_qty,order_meta.product_price',
                  'join'=> array(PRODUCTS. ' as product'=>'product.id=order_meta.product_id'),
                  'where'=> array('order_meta.order_id'=>$order_details->id)
               
            );
           $order_meta = $this->common_model->customGet($options);
          
            foreach($order_meta as $order)
           {
             /* check for image empty or not */
             if(!empty($order->image))
             {
              $image = $upload_url.$order->image;
             }
            else
            {
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
             $temp4['product_id']        = null_checker($order->product_id);
             $temp4['product_name']      = null_checker($order->product_name);
             $temp4['product_qty']       = null_checker($order->product_qty);
             $temp4['product_price']     = null_checker($order->product_price);
             $temp4['product_image']     = $image;
             $eachArr[]=$temp4;
            
          }
          $temp1['ordered_products'] = $eachArr;
       }
         if(!empty($result->user_image))
            {
              $image = base_url().$result->user_image;
            } else{
              /* set default image if empty */
              $image = base_url().DEFAULT_NO_IMG_PATH;
            }
     
     $token_expiry = $res->expiry_date;
     $current_date = date('Y-m-d H:i:s',strtotime('-1 day'));

     if($current_date > $token_expiry)
     {
       $expiry = true;
     }else{
       $expiry = false;
     }
     $member_expiry = $res->subscription_expiry_date;
     $member_subscription = $res->membership_subscription_date;
     if($member_expiry!='' && $member_expiry!='NULL' && $member_expiry!='0000-00-00'){
       if($current_date > $member_expiry)
       {
         $sub_expiry = true;
       }else{
         $sub_expiry = false;
       }
       $member_expiry_date = $member_expiry;
     }else{
      $sub_expiry = false;
      $member_expiry_date ='';
    }
    
       if(!empty($res))
              {

               if($res->hastoken==="0")//check has token
            {

              $response['hastoken'] = false;
              $response['token'] = new stdClass();
            }
            else
            {

              $temp['token'] = null_checker($res->token);
              $temp['is_token_expire'] = $expiry;
              $response['hastoken'] = true;
              $response['token'] = $temp;

            }
            
              if($res->hasorder==="0")//check firstime on app
              {
                $response['hasorder'] = false;
                $response['order'] = new stdClass();
              }
              else
              {
                $temp1['order_id'] = null_checker($order_details->id);
                $temp1['total_cost'] = null_checker($order_details->total_price);
                $temp1['order_date'] = null_checker(convertDate($order_details->order_date));
                $temp1['order_status'] = $order_status;
                $response['hasorder'] = true;
                $response['order'] = $temp1;
                
              }
              if($res->hasmembership==="0")//check last transaction
              {
               $response['hasmembership'] = false;
               $response['membership'] = new stdClass();
               
             }
             else
             {
              $temp2['membership_type']           = null_checker($res->membership_type);
              $temp2['is_membership_expire']      = $sub_expiry;
              $temp2['membership_expiry_date']    = date_check_convertFormat(convertDate($member_expiry_date));
              $temp2['membership_subscription_date'] = convertDate($member_subscription);
              $response['hasmembership'] = true;

              $response['membership'] = $temp2;
            }
              if($res->walletbalance >= 10)//check wallet
              {
                
                $response['can_by_membership'] = true;
                
              }
              else
              {
               $response['can_by_membership'] = false;
               
             }
             if(!empty($res->walletbalance))
             {
               $temp3['walletbalance']     = null_checker($res->walletbalance);
               $response['haswallet'] = true;
               $response['wallet'] = $temp3;
             }
             else
             {
               $response['haswallet'] = false;
               $response['wallet'] = new stdClass();
             }

           }
           $response['user_id']      = null_checker($result->id);
           $response['full_name']    = null_checker($result->full_name);
           $response['email']        = null_checker($result->email);
           $response['date_of_birth'] = null_checker(date_check_convertFormat(convertDate($result->date_of_birth)));
           $response['phone_number'] = null_checker($result->phone);
           $response['address']      = null_checker($result->address);
           $response['country']      = null_checker($result->country);
           $response['city']         = null_checker($result->city);
           $response['user_image']   = $image;
           $response['login_session_key'] = $result->login_session_key;
           $response['badges'] = null_checker($result->badges);
           echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$response ,'message'=>'Response found successfully!']); 


          } else{
            echo $this->jsonresponse->geneate_response(1, 0,'User not found!',[]);
          }
        }
  }

 }

 /* End of file User.php */
 /* Location: ./application/controllers/api/v1/User.php */
 ?>
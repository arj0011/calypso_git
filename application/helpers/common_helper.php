<?php

defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * [get user ]
 */
if (!function_exists('getUser')) {

    function getUser($id = "") {
        $CI = & get_instance();
        return $CI->common_model->customGet(array('table' => 'users', 'where' => array('id' => $id), 'single' => true));
    }

}

/**
 * [common query ]
 */
if (!function_exists('commonGetHelper')) {

    function commonGetHelper($option) {
        $ci = get_instance();
        return $ci->common_model->customGet($option);
    }

}

/**
 * [get common configure ]
 */
if (!function_exists('getConfig')) {

    function getConfig($key) {
        $ci = get_instance();
        $option = array('table' => KEY_CONFIGURATION,
            'where' => array('key_name' => $key, 'status' => 1),
            'single' => true,
        );
        $is_result = $ci->common_model->customGet($option);
        if (!empty($is_result)) {
            return $is_result->key_value;
        } else {
            return false;
        }
    }

}

/**
 * [Multidimensional Array Searching (Find key by specific value)]
 */
if (!function_exists('matchKeyValue')) {

    function matchKeyValue($products, $field, $value) {
        foreach ($products as $key => $product) {
            if ($product->$field === $value)
                return true;
        }
        return false;
    }

}

/**
 * [get role ]
 */
if (!function_exists('getRole')) {

    function getRole($id = "") {
        $CI = & get_instance();
        $option = array('table' => USERS . ' as user',
            'select' => 'group.name as group_name',
            'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
                array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left')),
            'where' => array('user.id' => $id),
            'single' => true
        );
        $user = $CI->common_model->customGet($option);
        if (!empty($user)) {
            return ucwords($user->group_name);
        } else {
            return false;
        }
    }

}

/**
 * [get role position ]
 */
if (!function_exists('getRolePosition')) {

    function getRolePosition($organization_id,$limit,$offset) {
        $CI = & get_instance();
        $option = array('table' => HIERARCHY_ROLE_ORDER . ' as roles',
                'select' => 'role_id',
                'where' => array('roles.organization_id' => $organization_id
                ),
                'order' => array('roles.id' => 'desc'),
                'single' => true,
                'limit' => array($limit=>$offset),
                'group_by' => array('roles.id')
            );

        $roles = $CI->common_model->customGet($option);
        if (!empty($roles)) {
            return $roles->role_id;
        } else {
            return false;
        }
    }

}


/**
 * [get common configure ]
 */
if (!function_exists('is_options')) {

    function is_options() {
        return array('loyalty_type','loyalty_value', 'premium_member_offer','site_name','site_logo','trending_type','gst','alacart_cancel_percent','foodparcel_cancel_percent','partypackage_cancel_percent','alacart_cancel_time','foodparcel_cancel_time','partypackage_cancel_time','wallet_amount');
    }

}


/**
 * [print pre ]
 */
if (!function_exists('dump')) {

    function dump($data) {
        echo"<pre>";
        print_r($data);
        echo"</pre>";
    }

}

/**
 * [Get year between Two Dates ]
 */
if (!function_exists('getYearBtTwoDate')) {

    function getYearBtTwoDate($datetime1, $datetime2) {
        //$datetime1 = new DateTime("$datetime1");
        //$datetime2 = new DateTime("$datetime2");

        $startDate = new DateTime($datetime1);
        $endDate = new DateTime($datetime2);

        $difference = $endDate->diff($startDate);

        return $difference->d; // This will print '12' die();
    }

}

/**
 * [To print last query]
 */
if (!function_exists('lq')) {

    function lq() {
        $CI = & get_instance();
        echo $CI->db->last_query();
        die;
    }

}

/**
 * [To get database error message]
 */
if (!function_exists('db_err_msg')) {

    function db_err_msg() {
        $CI = & get_instance();
        $error = $CI->db->error();
        if (isset($error['message']) && !empty($error['message'])) {
            return 'Database error - ' . $error['message'];
        } else {
            return FALSE;
        }
    }

}

/**
 * [To parse html]
 * @param string $str
 */
if (!function_exists('parseHTML')) {

    function parseHTML($str) {
        $str = str_replace('src="//', 'src="https://', $str);
        return $str;
    }

}

/**
 * [To get current datetime]
 */
if (!function_exists('datetime')) {

    function datetime($default_format = 'Y-m-d H:i:s') {
        $datetime = date($default_format);
        return $datetime;
    }

}

/**
 * [To convert date time format]
 * @param datetime $datetime
 * @param string $format
 */
if (!function_exists('convertDateTime')) {

    function convertDateTime($datetime, $format = 'd M Y h:i A') {
        $convertedDateTime = date($format, strtotime($datetime));
        return $convertedDateTime;
    }

}

/**
 * [To convert date format]
 * @param datetime $datetime
 * @param string $format
 */
if (!function_exists('convertDate')) {

    function convertDate($datetime, $format = 'd-m-Y') {
        $convertedDateTime = date($format, strtotime($datetime));
        return $convertedDateTime;
    }

}

/**
 * [To encode string]
 * @param string $str
 */
if (!function_exists('encoding')) {

    function encoding($str) {
        $one = serialize($str);
        $two = @gzcompress($one, 9);
        $three = addslashes($two);
        $four = base64_encode($three);
        $five = strtr($four, '+/=', '-_.');
        return $five;
    }

}

/**
 * [To decode string]
 * @param string $str
 */
if (!function_exists('decoding')) {

    function decoding($str) {
        $one = strtr($str, '-_.', '+/=');
        $two = base64_decode($one);
        $three = stripslashes($two);
        $four = @gzuncompress($three);
        if ($four == '') {
            return "z1";
        } else {
            $five = unserialize($four);
            return $five;
        }
    }

}
/**
 * [To generate random token]
 * @param string $length
 */
if (!function_exists('generateToken')) {

    function generateToken($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
        }
        return $token;
    }

}

/**
 * [To check null value]
 * @param string $value
 */
if (!function_exists('null_checker')) {

    function null_checker($value, $custom = "") {
        $return = "";
        if ($value != "" && $value != NULL) {
            $return = ($value == "" || $value == NULL) ? $custom : $value;
            return $return;
        } else {
            return $return;
        }
    }

}

/**
 * [To check null value]
 * @param string $value
 */
if (!function_exists('date_check_format')) {

    function date_check_format($value,$custom = "") {
        $return = "";
        if ($value != "" || $value != NULL || $value !='1969-12-31' || $value !='0000-00-00' || $value !='1970-01-01') {
            $return = ($value == "" || $value == NULL || $value =='1969-12-31' || $value =='0000-00-00' || $value =='1970-01-01') ? $custom : $value;
            return $return;
        } else {
            return $return;
        }
    }

}

/**
 * [To check null value]
 * @param string $value
 */
if (!function_exists('date_check_convertFormat')) {

    function date_check_convertFormat($value,$custom = "") {
        $return = "";
        if ($value != "" || $value != NULL || $value !='31 Dec 1969' || $value !='0000-00-00' || $value !='01 Jan 1970') {
            $return = ($value == "" || $value == NULL || $value =='31 Dec 1969' || $value =='0000-00-00' || $value =='01 Jan 1970') ? $custom : $value;
            return $return;
        } else {
            return $return;
        }
    }

}

/**
 * [To get default image if file not exist]
 * @param  [string] $filename
 * @param  [string] $filepath
 */
if (!function_exists('display_image')) {

    function display_image($filename, $filepath) {
        /* Send image path last slash */
        $file_path_name = $filepath . $filename;
        if (!empty($filename) && @file_exists($file_path_name)) {
            return urlencode(base_url() . $file_path_name);
        } else {
            return urlencode(base_url() . DEFAULT_NO_IMG_PATH);
        }
    }

}

/**
 * [To delete file from directory]
 * @param  [string] $filename
 * @param  [string] $filepath
 */
if (!function_exists('unlink_file')) {

    function unlink_file($filename, $filepath) {
        /* Send file path last slash */
        $file_path_name = $filepath . $filename;
        if (!empty($filename) && @file_exists($file_path_name) && @unlink($file_path_name)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
/**
 * [To auto generate password]
 * @param  [string] $filename
 */
if (!function_exists('randomPassword')) {

    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890#@&!';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

}

/**
 * [To add point]
 */
if (!function_exists('all_points')) {

    function all_points($point = 10, $value = "") {
        $htm = "";
        for ($i = 0; $i <= $point; $i++) {
            $select = (!empty($value)) ? ($value == $i) ? "selected" : "" : "";
            $htm .= "<option value='" . $i . "' " . $select . ">" . $i . "</option>";
        }
        return $htm;
    }

}

/**
 * [To add point]
 */
if (!function_exists('search_exif')) {

    function search_exif($exif, $field, $val) {
        if (!empty($exif)) {
            foreach ($exif as $data) {
                if ($data->$field == $val) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

}

/**
 * [To add point]
 */
if (!function_exists('search_exif_return')) {

    function search_exif_return($exif, $field, $val) {
        if (!empty($exif)) {
            foreach ($exif as $key=>$data) {
                if ($data->$field == $val) {
                    return $data->id;
                }
            }
        } else {
            return false;
        }
    }

}
     /**
     * [To get offsets]
     * @param  [int] $page_no
     */
    if (!function_exists('get_offsets')) {

        function get_offsets($page_no = 0) {
            $offset = ($page_no == 0) ? 0 : (int) $page_no * 10 - 10;
            return $offset;
        }

    }

    
    if (!function_exists('p')) {
        function p($array) {
            echo "<pre>";
            print_R($array);
            die;
        }
    }

    if (!function_exists('is_date_available')) {
        function is_date_available($sdate,$edate,$item_id){
            $CI = & get_instance();

            $existdate = $CI->db->get_where(ITEMDATES,array('item_id'=>$item_id,'end_date >=' => $sdate,'start_date<='=>$edate))->row_array();           
            if(empty($existdate)){
                return false;
            }else{
                return true;
            }
        }
    }


    if (!function_exists('date_difference')) {
        function date_difference($start, $end){
            $first_date = strtotime($start);
            $second_date = strtotime($end);
            $offset = $second_date-$first_date; 
            $result = array();
            for($i = 0; $i <= floor($offset/24/60/60); $i++) {
                $result[1+$i]['date'] = date('Y-m-d', strtotime($start. ' + '.$i.'  days'));
                $result[1+$i]['day'] = date('l', strtotime($start. ' + '.$i.' days'));
            }
           return $result;
            
        }

    }

    if (!function_exists('check_current_datetime')) {
        function check_current_datetime($date, $time){
            // echo $date;
            // echo $time;
            $date = strtotime($date);
            $time = strtotime($time);
            $current_date = date('Y-m-d');
            $current_date = strtotime($current_date);
            $current_time = date('H:i:s');
            $current_time = strtotime($current_time);
            
            if($date >= $current_date){
                if($time > $current_time){
                    //delivery date time is right
                    return 1;
                }else{
                    //delivery time is less than current time
                    return -1;
                }
            }else{
                //delivery date is less than current date
                return 0;
            }
        }
    }


    if (!function_exists('send_rdumption')) {
        function send_rdumption($user_id, $rduption){
            
            $CI = & get_instance();
            
            $userdata = $CI->db->get_where(USERS,array('id'=>$user_id))->row_array();
            if(!empty($userdata)){
              $phone = $userdata['phone'];
              $full_name = $userdata['full_name'];
              $email = $userdata['email'];


              //send OTP SMS
              $postfields = array(
                'dest'   => $phone,
                'msg'    => REDEEMCODE_SMS_MSG.$rduption, 
                'send'   => OTP_SMS_SEND, 
                'concat' => 1, 
                'uname'  => OTP_SMS_UNAME, 
                'pass'   => OTP_SMS_PWD
              ); 
              $result = Execute_Curl_URL(OTP_SMS_URL,$postfields);

              $from = FROM_EMAIL;
              $subject = "Order redeemption code";

              $data['content'] = "Congratulation! Your order were successfully placed.Your redeemption code is".$rduption;

              $data['user'] = ucwords($full_name);

              $message = $CI->load->view('email_template', $data, true);

              $title = "Redeemption code";

              /* send mail */
              if($email != ''):
                send_mail($message, $subject, $email, $from, $title);
              endif;  
              
            } 
        }

    }
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as cron management
 * @package   CodeIgniter
 * @category  Controller
 * @author    MobiwebTech Team
 */
class Cron extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('UTC');
    }

    /**
     * Function Name: delete_notifications
     * Description:   To Delete 30 Days Old Notifications
     */
    public function delete_notifications() {
        $sql = 'DELETE FROM ' . NOTIFICATIONS . ' WHERE sent_time + INTERVAL 30 DAY <= NOW()';
        $this->db->query($sql);
    }

    /**
     * Function Name: send_notifications
     * Description:   To Send Admin Notifications
     */
    public function send_notifications_old() {
        ini_set('max_execution_time', 1800); // 30 minutes

        /* Get notification request */
        $request = $this->common_model->getsingle(ADMIN_NOTIFICATIONS, array('status' => 'PENDING'));
        if (!empty($request)) {
            $where = array('email_verify' => 1, 'active' => 1, 'is_logged_out' => 0, 'user_type' => 'USER', 'id >' => $request->last_user_id);
            $users = $this->common_model->getAllwhere(USERS, $where, 'id', 'ASC', '', 50);
            if (!empty($users)) {

                /* Get admin details */
                $admin_details = $this->common_model->getsingle(USERS, array('email' => ADMIN_EMAIL));

                /* To get total users */
                $total_users = count($users);

                /* Send notifications */
                $i = 1;
                foreach ($users as $u) {
                    $devices = $this->common_model->getAllwhere(USERS_DEVICE_HISTORY, array('user_id' => $u->id));
                    if (!empty($devices)) {
                        /* To update user badges */
                        $device_badges = (int) $u->badges + 1;
                        $this->common_model->updateFields(USERS, array('badges' => $device_badges), array('id' => $u->id));

                        foreach ($devices as $d) {
                            if (!empty($d->device_token) && !empty($d->device_type)) {
                                $device_token = $d->device_token;
                                $message = $request->message;
                                $params = array('notification_type' => 'admin_notification');
                                $device_badges = $u->badges;
                                if ($d->device_type == 'ANDROID') {
                                    $noti_data = array('body' => $message, 'title' => $request->title, 'params' => $params);
                                    send_android_notification($noti_data, $device_token, $device_badges);
                                } else {
                                    send_ios_notification($device_token, $message, $params, $device_badges);
                                }
                            }
                        }
                    }
                    if ($i++ == $total_users) {
                        /* update notification request last user id */
                        $this->common_model->updateFields(ADMIN_NOTIFICATIONS, array('last_user_id' => $u->id), array('id' => $request->id));
                    }

                    /* To send notification details */
                    save_notification($u->id, $admin_details->id, 'admin_notification', $message);
                }
            } else {

                /* update notification request status */
                $this->common_model->updateFields(ADMIN_NOTIFICATIONS, array('status' => 'COMPLETED'), array('id' => $request->id));
            }
        }
    }


    /**
     * Function Name: send_notifications
     * Description:   To Send Admin Notifications
     */
    public function send_notifications_new() {
        ini_set('max_execution_time', 1800); // 30 minutes

        /* Get notification request */
        $request = $this->common_model->getsingle(ADMIN_NOTIFICATIONS, array('status' => 'PENDING','notification_type'=>'ADMIN_NOTIFICATION'));
        if(!empty($request)){
            if (!empty($request->user_ids)) {
                $removeUserArray    =   array();//users which we want to unset those are already blocked
                $validUsers         =   array();//users those are not blocked
                $userIDs_         =   isset($request->user_ids)?unserialize($request->user_ids):array();
                if(!empty($userIDs_)){
                    $userIDs    =   array_values($userIDs_);
                    $users      =   array_slice($userIDs, 0, 50);
                    $user       =   implode(",",$users);
                    $where      = " email_verify = 1  and active = 1 and is_logged_out = 0 and user_type = 'USER' and id IN(".$user.")";
                    $usersDetails = $this->common_model->getAllwhere(USERS, $where, 'id', 'ASC', '','');
                    if (!empty($usersDetails['result'])) {
                        foreach($usersDetails['result'] as $usr){
                            $validUsers[]  =   $usr->id;
                        }
                    }
                    $removeUserArray = array_diff($users,$validUsers);
                    if (!empty($usersDetails['result'])) {
                        $userCount  =   count($usersDetails['result']);
                        foreach($usersDetails['result'] as $row){
                            $userID = $row->id;
                            /* To send push notifications */
                            $notification_message = isset($request->message)?$request->message:'';

                            // $userHistory = $this->common_model->fetchSingleData('*',USERS_DEVICE_HISTORY,array('user_id'=>$userID));
                            // if(empty($userHistory)){
                            //     $notificationData['sender_id'] = 1;
                            //     $notificationData['receiver_id'] = $userID;
                            //     $notificationData['type'] = 'ADMIN_NOTIFICATION';
                            //     $notificationData['notification_type'] = 'ADMIN_NOTIFICATION';
                            //     $notificationData['dynamic_message'] = $notification_message;
                            //     $notificationData['sent_time'] = datetime();
                            //     $this->common_model->insertData(PENDING_NOTIFICATIONS,$notificationData);
                            // }else{
                            //     $noti_type = array('notification_type' => 'ADMIN_NOTIFICATION','send_id' => 1);//1 is the admin id
                            //     send_push_notifications($notification_message,$userID,$noti_type);
                            // }

                            if (($user_key = array_search($userID, $userIDs)) !== false) {
                                unset($userIDs[$user_key]);
                                if(!empty($userIDs)){
                                    $updatedUserIDs = serialize($userIDs);
                                    /* update notification request status */
                                    $this->common_model->updateFields(ADMIN_NOTIFICATIONS, array('user_ids' => $updatedUserIDs), array('id' => $request->id));
                                }else{
                                    $this->common_model->updateFields(ADMIN_NOTIFICATIONS, array('user_ids' => NULL,'status' => 'COMPLETED'), array('id' => $request->id));
                                }
                            }
                        }
                    }else{
                        //unset user those are blocked
                        if(!empty($removeUserArray) && !empty($userIDs_)){
                            $validUsersIds = array_diff($userIDs_,$removeUserArray);
                            if(!empty($validUsersIds)){
                                $updatedValidUserIDs = serialize($validUsersIds);
                                /* update notification request status */
                                $this->common_model->updateFields(ADMIN_NOTIFICATIONS, array('user_ids' => $updatedValidUserIDs), array('id' => $request->id));
                            }else{
                                $this->common_model->updateFields(ADMIN_NOTIFICATIONS, array('user_ids' => NULL,'status' => 'COMPLETED'), array('id' => $request->id));
                            }
                        }
                    }
                }else{
                    /* update notification request status */
                    $this->common_model->updateFields(ADMIN_NOTIFICATIONS, array('status' => 'COMPLETED'), array('id' => $request->id));
                }
            }else{
                /* update notification request status */
                $this->common_model->updateFields(ADMIN_NOTIFICATIONS, array('status' => 'COMPLETED'), array('id' => $request->id));
            }
        }
    }


     public function send_notifications_cron() {
        ini_set('max_execution_time', 600); // 10 minutes
        /* Get Pending Notifications */
        $pending_notifications = $this->common_model->getAllwhere(ADMIN_NOTIFICATION, array('status' => 'PENDING'), 'id', 'DESC');
        if (!empty($pending_notifications['result'])) {
            foreach ($pending_notifications['result'] as $pn) {

                    $flag = FALSE;
                    /* Get user ids */
                    $user_ids = (!empty($pn->user_ids)) ? unserialize($pn->user_ids) : array();
                    if (!empty($user_ids)) {
                        $user_ids = array_values($user_ids);
                        /* Get first 50 users */
                        $final_users = array_slice($user_ids, 0, 50);
                        if (!empty($final_users)) {
                            foreach ($final_users as $key => $fu) {
                                /* Get user info */
                                $user_details = $this->common_model->getsingle(USERS, array('id' => $fu));
                                if (!empty($user_details)) {
                                    $device_history = $this->common_model->getsingle(USERS_DEVICE_HISTORY, array('user_id' => $fu));
                                     // $device_badges = (int) $user_details->badges + 1;
                                     // $this->common_model->updateFields(USERS, array('badges' => $device_badges), array('id' => $fu));
                                        $flag = TRUE;
                                        if ($device_history->device_type == 'ANDROID' && !empty($device_history->device_token)) {
                                            $user_badges = $user_details->badges + 1;
                                            $data_array = array(
                                                'title' => $pn->title,
                                                'body' => $pn->message,
                                                'type' => $pn->notification_type,
                                                'type_id' => $pn->type_id,
                                                'user_id' => $fu,
                                                'badges' => $user_badges,
                                            );
                                            $noti_data = array('body' => 
                                           $pn->message,'params' => $data_array);
                                            $status = send_android_notification($noti_data, $device_history->device_token, $user_badges, $fu);
                                           // if ($status) {
                                                if (($user_key = array_search($fu, $user_ids)) !== false) {
                                                    unset($user_ids[$user_key]);
                                                }
                                                /* Update user notification sent status */
                                                $this->common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'reciever_id' => $fu));
                                            }
                                        //}
                                        if ($device_history->device_type == 'IOS' && !empty($device_history->device_token)) {
                                            $user_badges = $user_details->badges + 1;
                                            $params = array(
                                                'title' => $pn->title,
                                                'type' => $pn->notification_type,
                                                'type_id' => $pn->type_id,
                                                'user_id' => $fu
                                            );
                                            $status = send_ios_notification($device_history->device_token, $pn->message, $params, $user_badges, $fu);
                                            //if ($status) {
                                                if (($user_key = array_search($fu, $user_ids)) !== false) {
                                                    unset($user_ids[$user_key]);
                                                }
                                                /* Update user notification sent status */
                                                $this->common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'reciever_id' => $fu));
                                            }
                                        //}
                                  
                                }
                            }
                        } else {
                          
                                /* Update admin notification status */
                                $this->common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                            
                        }
                        
                            /* Update User ids from admin notifications */
                            $updated_user_ids = serialize(array_values($user_ids));
                            $this->common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => $updated_user_ids), array('id' => $pn->id));
                      
                    } else {
                      
                            /* Update admin notification status */
                            $this->common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                       
                    }
                
            }
        }
    }

   public function send_notifications_expire_users() {
        ini_set('max_execution_time', 600); // 10 minutes
        /* Get Pending Notifications */
       $tomorrow_date = date('Y-m-d',strtotime('+1 day'));
       $current_date = date('Y-m-d');
      
       $sql = "SELECT * FROM `user_membership` WHERE membership_type='PREMIUM' AND subscription_expiry_date = '".$tomorrow_date."' OR subscription_expiry_date = '".$current_date."'";

          $pending_notifications = $this->common_model->customQuery($sql);
       
       foreach($pending_notifications as $pending)
       {
         $user_ids = (!empty($pending->user_id)) ? ($pending->user_id) : array();
                    if (!empty($user_ids)) {   
                    $user_details = $this->common_model->getsingle(USERS, array('id' => $user_ids));
                    if (!empty($user_details)) {
                                    $device_history = $this->common_model->getsingle(USERS_DEVICE_HISTORY, array('user_id' => $user_ids));
                                     
                                        $flag = TRUE;
                                        $notification_arr = array(
                                                'type_id' => 0,
                                                'sender_id' => ADMIN_ID,
                                                'reciever_id' => $user_ids,
                                                'notification_type' => 'Membership',
                                                'title' => 'Membership Renewal',
                                                'notification_parent_id' => 0,
                                                'message' => "Your membership has been expired on ".$pending->subscription_expiry_date."",
                                                'is_read' => 0,
                                                'is_send' => 0,
                                                'sent_time' => date('Y-m-d H:i:s'),
                                        );
                                     $lid = $this->common_model->insertData(USER_NOTIFICATION,$notification_arr);
                                
                                        if ($device_history->device_type == 'ANDROID' && !empty($device_history->device_token)) {
                                            $user_badges = $user_details->badges + 1;
                                            $data_array = array(
                                                'title' =>'Membership Renewal',
                                                'body' => "Your membership has been expired on ".$pending->subscription_expiry_date."",
                                                'type' => 'Membership',
                                                //'type_id' => $user_ids,
                                                'user_id' => $user_ids,
                                                'badges' => $user_badges,
                                            );
                                            $noti_data = array('body' => 
                                         "Your membership has been expired on ".$pending->subscription_expiry_date."",'params' => $data_array);
                                            $status = send_android_notification($noti_data, $device_history->device_token, $user_badges, $user_ids);
                                            $this->common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('reciever_id' => $user_ids));
                                           
                                            }
                                       
                                        if ($device_history->device_type == 'IOS' && !empty($device_history->device_token)) {
                                            $user_badges = $user_details->badges + 1;
                                            $params = array(
                                                'title' =>'Membership Renewal',
                                                'type' => 'Membership',
                                                //'type_id' => $user_ids,
                                                'user_id' => $user_ids
                                            );
                                            $status = send_ios_notification($device_history->device_token, "Your membership has been expired on ".$pending->subscription_expiry_date."", $params, $user_badges, $user_ids);
                                            $this->common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('reciever_id' => $user_ids));
                                              
                                                /* Update user notification sent status */
                                               
                                            }

                                       }

                                }
                            }


        }




  public function send_notifications() {
        ini_set('max_execution_time', 600); // 10 minutes
        /* Get Pending Notifications */
        $pending_notifications = $this->common_model->getAllwhere(ADMIN_NOTIFICATION, array('status' => 'PENDING'), 'id', 'DESC');
        if (!empty($pending_notifications['result'])) {
            foreach ($pending_notifications['result'] as $pn) {

                $flag = FALSE;
                /* Get user ids */
                $user_ids_ = (!empty($pn->user_ids)) ? unserialize($pn->user_ids) : array();
                
                if (!empty($user_ids_)) {
                     $removeUserArray    =   array();//users which we want to unset those are already blocked
                    $validUsers         =   array();//users those are not blocked
                    $user_ids = array_values($user_ids_);
                    /* Get first 50 users */
                    $final_users = array_slice($user_ids, 0, 50);

                    $user       =   implode(",",$final_users);
                    $where      = "email_verify = 1  and active = 1 and is_logged_out = 0 and user_type = 'USER' and id IN(".$user.")";
                    $usersDetails = $this->common_model->getAllwhere(USERS, $where, 'id', 'ASC', '','');
                   // print_r($usersDetails);die;
                    if (!empty($usersDetails['result'])) {
                        foreach($usersDetails['result'] as $usr){
                            $validUsers[]  =   $usr->id;
                        }
                    }

                    $removeUserArray = array_diff($final_users,$validUsers);
                    if (!empty($usersDetails['result'])) {
                        $userCount  =   count($usersDetails['result']);
                        foreach($usersDetails['result'] as $row){
                            $userID = $row->id;
                            /* To send push notifications */
                            $user_details = $this->common_model->getsingle(USERS, array('id' => $userID));
                             //print_r($user_details);die;
                            if (!empty($user_details)) {
                                $device_history = $this->common_model->getsingle(USERS_DEVICE_HISTORY, array('user_id' => $userID));
                                   
                                $flag = TRUE;
                                if(!empty($device_history)){
                                    if ($device_history->device_type == 'ANDROID' && !empty($device_history->device_token)) {
                                        // $user_badges = $user_details->badges + 1;
                                        //arjun
                                        $user_badges = $this->common_model->get_user_badges($userID);
                                        $data_array = array(
                                            'title' => $pn->title,
                                            'body' => $pn->message,
                                            'noti_type' => $pn->notification_type,//1-status change,2-offer,3-best offer
                                            'type'=>$pn->type,//1-allacart,2-foodparcel,3-partypackage
                                            'type_id' => $pn->type_id,
                                            'user_id' => $userID,
                                            'badges' => $user_badges,
                                            );
                                        $noti_data = array('body' => 
                                         $pn->message,'params' => $data_array);
                                        $status = send_android_notification($noti_data, $device_history->device_token, $user_badges, $userID);

                                        if (($user_key = array_search($userID, $user_ids)) !== false) {
                                            unset($user_ids[$user_key]);
                                            if(!empty($user_ids)){
                                                $updatedUserIDs = serialize($user_ids);
                                                /* update notification request status */
                                                $this->common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => $updatedUserIDs), array('id' => $pn->id));
                                                $this->common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'reciever_id' => $userID));
                                            }else{
                                                $this->common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => NULL,'status' => 'COMPLETED'), array('id' => $pn->id));
                                            }
                                        } 
                                    }

                                    if ($device_history->device_type == 'IOS' && !empty($device_history->device_token)) {
                                        $user_badges = $user_details->badges + 1;
                                        $params = array(
                                            'title' => $pn->title,
                                            'type' => $pn->notification_type,
                                            'type_id' => $pn->type_id,
                                            'user_id' => $userID
                                            );
                                        $status = send_ios_notification($device_history->device_token, $pn->message, $params, $user_badges, $userID);
                                        if (($user_key = array_search($userID, $user_ids)) !== false) {
                                            unset($user_ids[$user_key]);
                                            if(!empty($user_ids)){
                                                $updatedUserIDs = serialize($user_ids);
                                                /* update notification request status */
                                                $this->common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => $updatedUserIDs), array('id' => $pn->id));
                                                $this->common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'reciever_id' => $userID));
                                            }else{
                                                $this->common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => NULL,'status' => 'COMPLETED'), array('id' => $pn->id));
                                            }
                                          } 

                                    }
                                }

                            }


                        }
                    }else{
                        //unset user those are blocked
                        if(!empty($removeUserArray) && !empty($user_ids_)){
                            $validUsersIds = array_diff($user_ids_,$removeUserArray);
                            if(!empty($validUsersIds)){
                                $updatedValidUserIDs = serialize($validUsersIds);
                                /* update notification request status */
                                $this->common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => $updatedValidUserIDs), array('id' => $pn->id));
                            }else{
                                $this->common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => NULL,'status' => 'COMPLETED'), array('id' => $pn->id));
                            }
                        }
                    }



                }

                else {

                    /* Update admin notification status */
                    $this->common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));

                }

                /* Update User ids from admin notifications */
                // $updated_user_ids = serialize(array_values($user_ids));
                // $this->common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => $updated_user_ids), array('id' => $pn->id));

            } 

        }
    }




}

/* End of file Cron.php */
/* Location: ./application/controllers/Cron.php */
?>
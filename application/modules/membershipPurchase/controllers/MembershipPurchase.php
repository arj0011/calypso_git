<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MembershipPurchase extends Common_Controller { 
  public $data = array();
  public $file_data = "";
  public $element = array();
  public function __construct() {
    $this->load->library(array('wallet'));
    parent::__construct();
    $this->is_auth_admin();
  }

     /**
     * @method index
     * @description listing display
     * @return array
     */
     public function index() {
      $this->data['parent'] = "MembershipPurchase";
      $this->data['title'] = "MembershipPurchase";
      $option = array('table' => USER_MEMBERSHIP .' as member',
        'select' => 'member.id,u.full_name, member.membership_type, member.membership_subscription_date,member.subscription_expiry_date',
        'join' => array(USERS.' as u'=>'u.id=member.user_id'),
		    'order' => array('member.id' => 'DESC'),
		);

      $this->data['list'] = $this->common_model->customGet($option);
	 
      $this->load->admin_render('list', $this->data, 'inner_script');
    }
   
    /**
     * @method open_model
     * @description load model box
     * @return array
     */

  

    function add() {
      $this->data['title'] = lang("add_membership");
       $option =array(
        'table' => USERS,
        'select'=> '*',
        'where' => array('id!='=>1)
        );
      $this->data['users']= $this->common_model->customGet($option);
      $this->load->admin_render('add', $this->data,'inner_script');
    }


   public function add_membership(){
         $this->data['parent'] = "Purchase";
         $this->data['title'] = lang("add_membership");
        $this->form_validation->set_rules('amount', 'Amount', 'required|trim');
       
       if ($this->form_validation->run() == false) {
           $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
             $this->load->admin_render('add', $this->data,'inner_script');

       }else{
        
        $user_id = $this->input->post('user_id');
        $amount = $this->input->post('amount');
        $current_date = date('Y-m-d');
        $current_date_expiry_check = date('Y-m-d H:i:s',strtotime('-1 day'));
        $result = $this->common_model->getsingle(USER_MEMBERSHIP, array('user_id'=>$user_id));
        if(!empty($result))
          {
            $member_expiry = $result->subscription_expiry_date;

             if($member_expiry!='' && $member_expiry!='NULL'){
               if($current_date_expiry_check > $member_expiry)
               {
                 $sub_expiry = true;
               }else{
                 $sub_expiry = false;
               }
             }else{
              $sub_expiry = false;
            }

            $membership = $result->membership_type;
            if($membership=='PREMIUM' && $sub_expiry==false)
            {
              
              $membership_type = 'PREMIUM';
            }else{
              
              $membership_type = 'BASIC';
            }
           
          }else{
            $membership_type = 'BASIC';
          }

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
        $user = $this->common_model->getsingle(USERS, array('id'=>$user_id));
        $user_balance = $user->current_wallet_balance;
         $options = array(
          'table' => USERS,
          'data' => array(
            
            'current_wallet_balance' => $user_balance+$amount ,
            ),
           'where' => array('id' => $user_id)
          );
         $wallet =  $this->common_model->customUpdate($options);

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
        $user = $this->common_model->getsingle(USERS, array('id'=>$user_id));
        $user_balance = $user->current_wallet_balance;
        
         $options = array(
          'table' => USERS,
          'data' => array(
            
            'current_wallet_balance' => $user_balance+$amount ,
            ),
           'where' => array('id' => $user_id)
          );
         $wallet =  $this->common_model->customUpdate($options);
      }

        $reward_point = $this->wallet->calculate_wallet_point($membership_type,$user_id,$amount);

      $this->wallet->add_wallet_log('CREDIT',$amount,$current_date,0,$user_id,'USER',$membership_type,"Earned ". $reward_point ." Points on Credit wallet Balance");

      if($wallet)
         {

           $this->session->set_flashdata('success', 'Membership Updated successfully');
           redirect('membershipPurchase/add');

        }else {
           $this->session->set_flashdata('error', 'Membership failed to updated');
           redirect('membershipPurchase/add');
        } 

     }   
     
    
  } 


  function del() {
        $response = "";
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        if (!empty($table) && !empty($id) && !empty($id_name)) { 

            $option = array(
                'table' => $table,
                'where' => array($id_name => $id)
            );
            $delete = $this->common_model->customDelete($option);
            if ($delete) {
                $response = 200;
            } else
                $response = 400;
        }else {
            $response = 400;
        }
        echo $response;
    }

  function view_membership() {
      $this->data['title'] = lang("view_membership");
      $id = decoding($this->uri->segment(3));
     $option = array('table' => USER_MEMBERSHIP .' as member',
        'select' => 'member.id,u.full_name,u.email, u.current_wallet_balance,member.user_id,member.membership_type,member.membership_subscription_date,member.subscription_expiry_date',
        'join' => array(USERS.' as u'=>'u.id=member.user_id'),
        'where' => array('member.id'=>$id),
        'single'=> true
        
    );

      $this->data['member'] = $this->common_model->customGet($option);
     $this->load->admin_render('view', $this->data,'inner_script');
  }

}

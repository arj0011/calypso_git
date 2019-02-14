<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Wallet
{
  private $CI;
  private $that;  
  public function __construct()
  { $this->CI = & get_instance();
    $this->CI->load->database();
    $this->CI->load->library('Security');
    $this->CI->load->library('Session');
    $this->CI->load->helper('common_helper');
  }



  function add_wallet_log($transcation_type,$amount,$date,$order_id='',$user_id,$transcation_user_type,$user_type='',$description)
  {
   $that = $this->CI;
   if($transcation_type=='CREDIT')
   {
     
     $calculate_amount = $this->calculate_wallet_point($user_type,$user_id,$amount);
     $array_data = array(
       "transaction_type" => $transcation_type,
       "amount"   => $calculate_amount,
       "order_id" => $order_id,
       "date"    => $date,
       "user_id"  => $user_id,
       "transcation_user_type" => $transcation_user_type,
       "description" => $description	

       );

      $that->db->insert("wallet", $array_data);

      $that->db->select('current_wallet_balance');
      $that->db->from('users');
      $that->db->where('id', $user_id);
     $query = $that->db->get()->row();
     $amount_data = $query->current_wallet_balance;
     $total = $amount_data+$calculate_amount;
     $current_balance = array('current_wallet_balance' => $total);
     $that->db->update("users", $current_balance, array('id' => $user_id));

     return true;

   }

   else 
   {
     $that->db->select('current_wallet_balance');
     $that->db->from('users');
     $that->db->where('id', $user_id);
     $query = $that->db->get()->row();
     $amount_data = $query->current_wallet_balance;
      if(!empty($amount_data) && $amount_data!=0)
      {
        
      $total = $amount_data-$amount;
     }else{

      $total = 0;
    }
     $array_data = array(
       "transaction_type" => $transcation_type,
       "amount"   => $amount,
       "order_id" => $order_id,
       "date"    => $date,
       "user_id"  => $user_id,
       "transcation_user_type" => $transcation_user_type,
       "description" => $description	

       );
     $that->db->insert("wallet", $array_data);
     
     $current_balance = array('current_wallet_balance' => $total);
     $that->db->update("users", $current_balance, array('id' => $user_id));
     return true;

   }


 }



 function calculate_wallet_point($user_type,$user_id,$amount)
 {
  $that = $this->CI;
  $loyalty_type   = getConfig('loyalty_type');
  $loyalty_value  = getConfig('loyalty_value');
  $premium_member = getConfig('premium_member_offer');
 
  $value =0;
  if($loyalty_type == 1)
  {

   $value = $amount*($loyalty_value/100);
  }
 else
 {

   $value =$loyalty_value;
 }

 if($user_type=='PREMIUM')
 {
  $wallet_point =  $value * $premium_member ;

}
else
{
  $wallet_point = $value;
}

return $wallet_point;

}

}
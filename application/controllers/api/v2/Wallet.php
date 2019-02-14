<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for category
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun Choudhary
 */
class Wallet extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }

  /**
     * Function Name: wallet_history
     * Description: To Get wallet history
    */
  public function wallet_history_post(){
  	$data = $this->input->post();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
          $error = $this->form_validation->rest_first_error_string();
          echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
        } else {
          
          $page_no    = extract_value($data,'page_no',1);  
          $user_id    = extract_value($data,'user_id',''); 

          is_deactive($user_id);
             
          $offset     = get_offsets($page_no);
          $wallet_amount = $this->common_model->user_wallet_amount($user_id);

          $options = array(
            'table'  => WALLET.' AS w',
            'select' => 'w.*,o.redeemption_code,o.unique_order_id',
            'join'=>array(ORDER.' AS o'=>'o.id = w.order_id'),
            'where'  => array('w.user_id'=>$user_id),
            'order'  => array('w.id' => 'desc'),
            'limit'  => array(10 => $offset)
          );
          
          $sql = "SELECT `w`.*, `o`.`redeemption_code`, `o`.`unique_order_id`
                  FROM `wallet` AS `w`
                  LEFT JOIN `order` AS `o` ON `o`.`id` = `w`.`order_id`
                  WHERE `w`.`user_id` = ".$user_id."
                  ORDER BY `w`.`id` DESC
                  LIMIT ".$offset.",10";


          /* To get offer list from offer table */
          // $list = $this->common_model->customGet($options);
          $query = $this->db->query($sql);
          $list = $query->result();
          /* check for image empty or not */

          if (!empty($list)) {

              $eachArr = array();

              $total_requested = (int) $page_no * 10; 

              /* Get total records */  
              // $total_records = getAllCount(WALLET,array('user_id'=>$user_id));
              /*$op = array(
                'table'  => WALLET.' AS w',
                'select' => 'w.*,o.redeemption_code,o.unique_order_id',
                'join'=>array(ORDER.' AS o'=>'o.id = w.order_id'),
                'where'  => array('w.user_id'=>$user_id)
              );
              $total_records = $this->common_model->customCount($op);*/


              $sql1 = "SELECT  COUNT(`w`.id) AS count
                       FROM `wallet` AS `w` 
                       LEFT JOIN `order` AS `o` ON `o`.`id` = `w`.`order_id` 
                       WHERE `w`.`user_id` = ".$user_id."";
              $query1 = $this->db->query($sql1);         
              $recordsData = $query1->row_array();
              $total_records = $recordsData['count'];
              
              if($total_records > $total_requested){                      
                $has_next = TRUE;                    
              }else{                        
                $has_next = FALSE;                    
              }

              foreach ($list as $rows):
                
                $temp['id']                    = (int)null_checker($rows->id);
                $temp['user_id']               = (int)null_checker($rows->user_id);
                $temp['order_id']              = (int)null_checker($rows->order_id);
                $temp['transaction_type']      = null_checker($rows->transaction_type);
                $temp['amount']                = (float)null_checker($rows->amount);
                $temp['description']           = null_checker($rows->description);
                $temp['transcation_user_type'] = (int)null_checker($rows->transcation_user_type);
                $temp['redeemption_code']      = null_checker($rows->redeemption_code);
                $temp['unique_order_id']      = null_checker($rows->unique_order_id);
                $temp['date']                  = $rows->date;

                $eachArr[] = $temp;
              endforeach;
              /* return success response*/

              echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'wallet_amount'=>$wallet_amount,'message'=>'Wallet history found successfully']);

            }else {
              echo $this->jsonresponse->geneate_response(1, 0,'Wallet history not found',[]);
          }
        }

    }
  }

   /* End of file Wallet.php */
   /* Location: ./application/controllers/api/v1/Wallet.php */
   ?>
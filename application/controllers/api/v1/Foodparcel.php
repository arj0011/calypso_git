<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for category
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun Choudhary
 */
class Foodparcel extends Common_API_Controller {

  function __construct() {
    parent::__construct();
    $this->lang->load('en', 'english');
  }


    /**
     * Function Name: exclusive_offer_list
     * Description:   To Get all exclusive offer
    */

    function foodparcel_list_post() {
    	$data = $this->input->post();

      	$this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

      	if ($this->form_validation->run() == FALSE) {
        	$error = $this->form_validation->rest_first_error_string();
        	echo $this->jsonresponse->geneate_response(1, 0,$error,[]);
      	} else {

        	$page_no    = extract_value($data,'page_no',1);  
        	$offset     = get_offsets($page_no);
      
        	$upload_url = base_url().'uploads/foodparcel/';
        	$current_date = datetime('Y-m-d');

        	$options = array(
        		'table' => FOODPARCEL.' AS fp',
          	'select' => 'fp.*',
          	'where'=>array('fp.status'=>1),
          	'order' => array('fp.id' => 'desc'),
          	'limit' => array(10 => $offset)
          	);

        	/* To get offer list from offer table */
        	$list = $this->common_model->customGet($options);
        	/* check for image empty or not */
        	if (!empty($list)) {

          		$eachArr = array();

          		$total_requested = (int) $page_no * 10; 

          		/* Get total records */  
          		$total_records = getAllCount(FOODPARCEL);

          		if($total_records > $total_requested){                      
            		$has_next = TRUE;                    
          		}else{                        
            		$has_next = FALSE;                    
          		}

          		foreach ($list as $rows):
            		if(!empty($rows->image)){
              			$image = $upload_url.$rows->image;
            		} else{
              			/* set default image if empty */
              			$image = base_url().DEFAULT_NO_IMG_PATH;
            		}
            		
                $option =array(
                  'table' => PARCELITEMS.' AS pi',
                  'select'=> 'pi.item_id,pi.item_limit,a.item_name',
                  'join'=>array(array(ALLACART.' AS a'=>'a.id=pi.item_id'),array(CATEGORY_MANAGEMENT.' AS cm'=>'cm.id=pi.category_id')),
                  'where' => array('pi.parcel_id'=>$rows->id,'cm.status'=>1)
                );
                $itemsArr = $this->common_model->customGet($option);
                if(!empty($itemsArr)){
                  $temp['items'] = $itemsArr;  
                }else{
                  $temp['items'] = $itemsArr;  
                }
		            $temp['id']           = null_checker($rows->id);
		            $temp['foodparcel']   = null_checker($rows->item_name);
		            $temp['price']      	= null_checker($rows->price);
		            $temp['description']  = null_checker($rows->description);
		            $temp['status']      	= null_checker($rows->status);
		            $temp['created_date'] = null_checker(convertDate($rows->created_date));
		            $temp['image']       	= $image;

            		$eachArr[] = $temp;
            	endforeach;
            	/* return success response*/

            	echo $this->jsonresponse->geneate_response(0, 1,'',['response'=>$eachArr,'has_next'=>$has_next,'message'=>'Food parcel found successfully']);

          	}else {
           		echo $this->jsonresponse->geneate_response(1, 0,'Food parcel not found',[]);
         	}
       	}
    }


}


   /* End of file Foodparcel.php */
   /* Location: ./application/controllers/api/v1/Foodparcel.php */
   ?>
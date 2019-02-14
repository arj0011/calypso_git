<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suggestionfeedback extends Common_Controller { 
  public $data = array();
  public $file_data = "";
  public $element = array();
  public function __construct() {
    parent::__construct();
    $this->is_auth_admin();
  }

     /**
     * @method index
     * @description listing display
     * @return array
     */
     
    public function suggestion() {
      $this->data['parent'] = "Suggestion";
      $this->data['title'] = "Suggestion";
      $option = array('table' => SUGGESTION_FEEDBACK .' as sf',
        'select' => 'sf.*,u.full_name',
        'join'=> array(USERS.' as u'=>'u.id=sf.user_id'),
        'where' => array('sf.type'=>1),
        'order' => array('sf.id' => 'DESC'),
      );

      $this->data['list'] = $this->common_model->customGet($option);
      //p($this->data['list']);
      $this->load->admin_render('list', $this->data, 'inner_script');
    }

    public function feedback() {
      $this->data['parent'] = "Feedback";
      $this->data['title'] = "Feedback";
      $option = array('table' => SUGGESTION_FEEDBACK .' as sf',
        'select' => 'sf.*,u.full_name',
        'join'=> array(USERS.' as u'=>'u.id=sf.user_id'),
        'where' => array('sf.type'=>2),
        'order' => array('sf.id' => 'DESC'),
      );

      $this->data['list'] = $this->common_model->customGet($option);
      $this->load->admin_render('list', $this->data, 'inner_script');
    }


    public function hierarchicalRenderer($list,$level=0){ 
      $element = [];
      if($list==''){
        return false;
      }else{
        foreach($list as $i=>$v){ 
          $temp = clone $v;
          if($level>0){ 
            $levelShower = '';
            for($i=0;$i<$level;$i++){
              $levelShower .= '- ';
            }
            $temp->category_name = $levelShower.''.$temp->category_name;
          }  
          unset($temp->childern);
          $this->element[] = $temp;
          if(isset($v->childern)){ 
            $this->hierarchicalRenderer($v->childern,$level+1);
          }
        }
        return $this->element;
      } 
    } 
  
  function del() {
        $response = 400;
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        
        if(!empty($table) && !empty($id) && !empty($id_name)) { 
          
          $option = array(
                  'table' => $table,
                  'where' => array($id_name => $id)
          );
          $delete = $this->common_model->customDelete($option);
          if($delete){
            $response = 200;
          }else{
            $response = 400;
          }   
        }
        echo $response;
  }

}

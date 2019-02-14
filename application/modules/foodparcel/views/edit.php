
<link href="<?php echo base_url().CUSTOMCSS;?>" rel="stylesheet"/> 
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <a href="<?php echo site_url('foodparcel');?>"><?php echo lang('food_items_management');?></a>
      </li>
    </ol>
  </div>
  
</div>
<div class="wrapper wrapper-content animated fadeIn">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
         <div class="row">
          <?php $message = $this->session->flashdata('success');
          if(!empty($message)):?><div class="alert alert-success">
          <?php echo $message;?></div><?php endif; ?>
          <?php $error = $this->session->flashdata('error');
          if(!empty($error)):?><div class="alert alert-danger">
          <?php echo $error;?></div><?php endif; ?>
          <div id="message"></div>
 
                    <form role="form" id="addFormAjax" method="post" action="<?php echo base_url('foodparcel/item_update') ?>" enctype="multipart/form-data">  
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label><?php echo lang('pack_name');?></label>
                                        <input type="text" value="<?php echo $results->item_name;?>" class="form-control" name="food_name" id="food_name" placeholder="<?php echo lang('pack_name');?>" />
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label ><?php echo lang('price');?> (&#8377;)</label>
                                        <input type="text" value="<?php echo $results->price;?>" class="form-control" name="price" id="price" placeholder="<?php echo lang('price') ;?>" />
                                    
                                </div>
                            </div>

                           <div class="col-md-6" >
                            <div class="form-group">
                                <label><?php echo lang('pack_image'); ?></label>
                                <div class="">
                                        <div class="profile_content edit_img">
                                        <div class="file_btn file_btn_logo">
                                          <input type="file"  class="input_img2" id="image" name="image" style="display: inline-block;">
                                          <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                            <div id="show_company_img"></div>
                                            <span class="ceo_logo">
                                                <?php if(!empty($results->image)){ ?>
                                                    <img src="<?php echo base_url().'uploads/foodparcel/'.$results->image;?>">
                                                <?php }else{ ?>
                                                    <img src="<?php echo base_url().'backend_asset/images/default.jpg';?>">
                                               <?php }?>                                                   
                                            </span>
                                            <i class="fa fa-camera"></i>
                                          </span>
                                          <img class="show_company_img2" style="display:none" alt="img" src="<?php echo base_url() ?>/backend_asset/images/logo.png">
                                          <span style="display:none" class="fa fa-close remove_img"></span>
                                        </div>
                                      </div>
                                      <div class="ceo_file_error file_error text-danger"></div>
                                </div>
                            </div>
                        </div>

                        

                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label><?php echo lang('pack_description');?></label>
                                        <textarea class="form-control" name="description" id="description" placeholder="<?php echo lang('pack_description');?>"><?php echo $results->description;?></textarea>
                                    </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label>Date</label>
                                        <input type="text" class="form-control" name="date" id="date" placeholder="Date" value="<?php echo date('d-m-Y',strtotime($results->created_date));?>" readonly />
                                    </div>
                            </div>
                                
                            <div class="my_appendiv">
                                <?php if(!empty($pack_items)){
                                    $count = 1;
                                    foreach ($pack_items as $detail) {
                                ?>
                                <div id="parent_<?php echo $count;?>">  

                                    <div class="col-md-5">
                                      <div class="form-group">
                                          <select class="form-control category_cls" id="category_<?php echo $count;?>" name="item[]">
                                           <?php 
                                            if(!empty($items_data)){
                                                foreach($items_data as $ite){
                                                    if($ite->id == $detail->item_id){
                                            ?>
                                                <option value="<?php echo $ite->id;?>"><?php echo $ite->item_name;?></option>            
                                            <?php   } ?>    
                                                
                                            <?php }}?>
                                          </select> 
                                      </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                          <input type="text" value="<?php echo $detail->item_limit;?>" id="limit_<?php echo $count;?>" name="limit[]" class="form-control" placeholder="Limit" />
                                        </div>
                                    </div>
                              

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <!-- <div class="col-md-6 cat_item_drpdwn" id="itemDivId_<?php echo $count;?>">
                                                <?php
                                                    $tempitemArr = array(); 
                                                    $tempitemArr = explode(',',$detail->items_id);?>                                        
                                                    <select class="form-control cat_item_cls" name="itemids[<?php echo $detail->category_id;?>][]" id="id_<?php echo $count;?>" multiple="">
                                                    <?php foreach ($detail->item_array as $item) {?>
                                                    <option value="<?php echo $item->item_id;?>" <?php echo (in_array($item->item_id, $tempitemArr)) ? 'selected' : ''; ?>><?php echo $item->item_name;?></option>
                                                    <?php } ?>
                                                    </select> 
                                            </div> -->
                                            <div class="col-md-6"></div>
                                        <div class="col-md-6" id="delbtn_<?php echo $count;?>">
                                            <div class="form-group">
                                              <a href="javascript:void(0);" id="rowcount_<?php echo $count;?>" class="btn btn-primary delrow">-</a>
                                            </div>
                                        </div>
                                        
                                        </div>
                                    </div>

                                </div>
                                <?php $count++;}} ?>
                            </div>

                            <div class="clearfix"></div> 
                                <div class="col-md-2">
                                    <a href="javascript:void(0);" id="addmorerow" class="btn btn-primary addmorerow">+</a>
                                </div> 
                            
                            <div class="space-22"></div>

                            <div class="col-md-12" >
                              <input type="hidden" name="id" id="id" value="<?php echo encoding($results->id); ?>">
                              <input type="hidden" name="exists_image" value="<?php echo $results->image;?>" />
                                  <!-- <button type="button" class="btn btn-danger"><?php echo lang('close_btn');?></button> -->
                                  <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('submit_btn');?></button>
                            </div>
                </form>            
            </div>
        
       
             </div>
             <div id="form-modal-box"></div>
           </div>
         </div>
       </div>
       </div>
       <div id="message_div">
        <span id="close_button"><img src="<?php echo base_url();?>backend_asset/images/close.png" onclick="close_message();"></span>
        <div id="message_container"></div>
      </div>
<script type="text/javascript">

</script>
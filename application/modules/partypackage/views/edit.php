
<link href="<?php echo base_url().CUSTOMCSS;?>" rel="stylesheet"/> 
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <a href="<?php echo site_url('partypackage');?>"><?php echo lang('food_items_management');?></a>
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
 
                    <form role="form" id="addFormAjax" method="post" action="<?php echo base_url('partypackage/item_update') ?>" enctype="multipart/form-data">  
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label><?php echo lang('package_name');?></label>
                                        <input type="text" value="<?php echo $results->item_name;?>" class="form-control" name="food_name" id="food_name" placeholder="<?php echo lang('package_name');?>" />
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label ><?php echo lang('strike_price');?> (&#8377;)</label>
                                        <input type="text" value="<?php echo $results->strike_price;?>" class="form-control" name="strike_price" id="strike_price" placeholder="<?php echo lang('strike_price') ;?>" />
                                    
                                </div>
                             </div>


                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label ><?php echo lang('package_price');?> (&#8377;)</label>
                                        <input type="text" value="<?php echo $results->price;?>" class="form-control" name="price" id="price" placeholder="<?php echo lang('package_price') ;?>" />
                                    
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label ><?php echo lang('partial_payment');?> (&#8377;)</label>
                                        <input type="text" value="<?php echo $results->partial_payment;?>" class="form-control" name="partial_payment" id="partial_payment" placeholder="<?php echo lang('partial_payment') ;?>" />
                                    
                                </div>
                             </div>

                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label ><?php echo lang('discount');?> (&#37;)</label>
                                        <input type="text" value="<?php echo $results->discount;?>" class="form-control" name="discount" id="discount" placeholder="<?php echo lang('discount') ;?>" />
                                    
                                </div>
                             </div>

                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label ><?php echo lang('min_person');?></label>
                                        <input type="text" value="<?php echo $results->min_person;?>" class="form-control" name="min_person" id="min_person" placeholder="<?php echo lang('min_person') ;?>" />
                                    
                                </div>
                             </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label><?php echo lang('gender_pref');?> </label>
                                    <div class="">
                                    <?php $genArr = explode(',',$results->gender_pref);?>
                                    <label>
                                        <input type="checkbox" <?php if(in_array(1,$genArr)){echo 'checked';};?> name="gender[]" id="male" value="1" /> Male
                                    </label>
                                    <label>
                                        <input type="checkbox" <?php if(in_array(2,$genArr)){echo 'checked';};?> name="gender[]" id="female" value="2"> Female
                                    </label>    
                                    </div>
                                </div>
                            </div>

                           <div class="col-md-6" >
                            <div class="form-group">
                                <label><?php echo lang('package_image'); ?></label>
                                <div class="">
                                        <div class="profile_content edit_img">
                                        <div class="file_btn file_btn_logo">
                                          <input type="file"  class="input_img2" id="image" name="image" style="display: inline-block;">
                                          <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                            <div id="show_company_img"></div>
                                            <span class="ceo_logo">
                                                <?php if(!empty($results->image)){ ?>
                                                    <img src="<?php echo base_url().'uploads/partypackage/'.$results->image;?>">
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
                                    <label><?php echo lang('package_description');?></label>
                                        <textarea class="form-control" name="description" id="description" placeholder="<?php echo lang('package_description');?>"><?php echo $results->description;?></textarea>
                                    </div>
                            </div>
                            
                            <div class="col-md-12">
                                
                                    <label "><?php echo lang('age_pref');?></label>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label"><?php echo lang('age_min');?></label>
                                    <select class="form-control" name="min_age" id="min_age">
                                    <?php for($i=10; $i<=40 ; $i=$i+10) { ?> 
                                        <option value="<?php echo $i; ?>" <?php echo  ($results->min_age == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>    
                                    <?php } ?>    
                                    </select> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label"><?php echo lang('age_max');?></label>
                                    <select class="form-control" name="max_age" id="max_age">
                                    <?php for($j=20; $j<=50 ; $j=$j+10) { ?>
                                        <option value="<?php echo $j; ?>" <?php echo  ($results->max_age == $j) ? 'selected' : ''; ?>><?php echo $j; ?></option>    
                                    <?php } ?>
                                    </select> 
                                </div>

                            </div>
                                
                            <div class="my_appendiv">
                                <?php if(!empty($category_detail)){
                                    $count = 1;
                                    foreach ($category_detail as $detail) {
                                ?>
                                <div id="parent_<?php echo $count;?>">  

                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <select class="form-control category_cls" id="category_<?php echo $count;?>" name="category[]">
                                           <?php 
                                            if(!empty($category)){
                                                foreach($category as $cat){
                                                    if($cat->id == $detail->category_id){
                                            ?>
                                                <option value="<?php echo $cat->id;?>"><?php echo $cat->category_name;?></option>            
                                            <?php   } ?>    
                                                
                                            <?php }}?>
                                          </select> 
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <input type="text" value="<?php echo $detail->item_limit;?>" id="limit_<?php echo $count;?>" name="limit[]" class="form-control" placeholder="Limit" />
                                        </div>
                                    </div>
                              

                                    <div class="">
                                        <div class="form-group">
                                            <div class="col-md-6 cat_item_drpdwn" id="itemDivId_<?php echo $count;?>">
                                                <?php
                                                    $tempitemArr = array(); 
                                                    $tempitemArr = explode(',',$detail->items_id);?>                                        
                                                    <select class="form-control cat_item_cls" name="itemids[<?php echo $detail->category_id;?>][]" id="id_<?php echo $count;?>" multiple="">
                                                    <?php foreach ($detail->item_array as $item) {?>
                                                    <option value="<?php echo $item->item_id;?>" <?php echo (in_array($item->item_id, $tempitemArr)) ? 'selected' : ''; ?>><?php echo $item->item_name;?></option>
                                                    <?php } ?>
                                                    </select> 
                                                    <script type="text/javascript">
                                                      //$('#id_<?php echo $count;?>').select2();
                                                      //$('.cat_item_cls').select2();
                                                    </script>
                                            </div>
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
                              <input type="hidden" name="package_id" id="package_id" value="<?php echo encoding($results->id); ?>">
                              <input type="hidden" name="exists_image" value="<?php echo $results->image;?>" />
                                  <!-- <button type="button" class="btn btn-danger"><?php echo lang('reset_btn');?></button> -->
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


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
                    <form class="" role="form" id="addFormAjax" method="post" action="<?php echo base_url('foodparcel/item_add') ?>" enctype="multipart/form-data">   
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label><?php echo lang('pack_name');?></label>
                                        <input type="text" class="form-control" name="food_name" id="food_name" placeholder="<?php echo lang('pack_name');?>" />
                                </div>
                            </div>


                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label ><?php echo lang('price');?> (&#8377;)</label>
                                        <input type="text" class="form-control" name="price" id="price" placeholder="<?php echo lang('price') ;?>" />
                                    
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
                                                  <img src="<?php echo base_url().'backend_asset/images/default.jpg';?>">
                                                </span>
                                                <i class="fa fa-camera"></i>
                                              </span>
                                              <img class="show_company_img2" style="display:none" alt="img" src="<?php echo base_url() ?>/backend_asset/images/logo.png">
                                              <span style="display:none" class="fa fa-close remove_img" onclick="remove_sel_img()"></span>
                                            </div>
                                          </div>
                                          <div class="ceo_file_error file_error text-danger"></div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label><?php echo lang('pack_description');?></label>
                                        <textarea class="form-control" name="description" id="description" placeholder="<?php echo lang('pack_description');?>"></textarea>
                                    </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label>Date</label>
                                        <input type="text" class="form-control" name="date" id="date" placeholder="Date" readonly value="<?php echo date('d-m-Y') ?>" />
                                    </div>
                            </div>

                            <div class="my_appendiv">
                              
                              <div id="parent_1">  

                                  <div class="col-md-5">
                                      <div class="form-group">
                                          <select class="form-control category_cls" id="category_1" name="item[]">
                                            <option value="">Select</option>
                                            <?php if(!empty($pack_items)){foreach($pack_items as $item){?>    
                                                <option value="<?php echo $item->id;?>"><?php echo $item->item_name;?></option>
                                            <?php }}?>
                                          </select> 
                                      </div>
                                  </div>
                                  <div class="col-md-5">
                                    <div class="form-group">
                                          <input type="text" id="limit_1" name="limit[]" class="form-control" placeholder="Limit" />
                                    </div>
                                  </div>
                              

                                  <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-6 cat_item_drpdwn" id="itemDivId_1">
                                            
                                        </div>
                                        <div class="col-md-6" id="delbtn_1">
                                            <div class="form-group">
                                              <a href="javascript:void(0);" id="rowcount_1" class="btn btn-primary delrow">-</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                  </div>

                              </div>

                            </div>

                               <div class="clearfix"></div> 
                            <div class="col-md-2">
                                <a href="javascript:void(0);" id="addmorerow" class="btn btn-primary addmorerow">+</a>
                            </div> 
                            
                            <div class="space-22"></div>

                            <div class="col-md-12" >
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

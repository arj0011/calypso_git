<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : "" ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('home'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('setting'); ?>"><?php echo lang('setting'); ?></a>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">

                <div class="ibox-content">
                    <div class="row">
                        <?php
                        $message = $this->session->flashdata('success');
                        if (!empty($message)):
                            ?><div class="alert alert-success">
                        <?php echo $message; ?></div><?php endif; ?>
                        <?php
                        $error = $this->session->flashdata('error');
                        if (!empty($error)):
                            ?><div class="alert alert-danger">
                        <?php echo $error; ?></div><?php endif; ?>
                        <div id="message"></div>
                        <div class="col-lg-12" style="overflow-x: auto">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5>All Application elements <small>With custom.</small></h5>
                                            <div class="ibox-tools">
                                                <a class="collapse-link">
                                                    <i class="fa fa-chevron-up"></i>
                                                </a>
                                                <a class="close-link">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="ibox-content">
                                            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('setting/set_configuration') ?>" enctype="multipart/form-data">

                                                <!-- <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('loyalty_type'); ?></label>
                                                  <div class="col-sm-10">
                                                  <select class="form-control" name="loyalty_type" id="loyalty_type">
                                          
                                                  <option value="1" <?php  if(getConfig('loyalty_type')==1){echo"selected";}?>>Percentage</option>
                                                  <option value="2" <?php if(getConfig('loyalty_type')==2){echo"selected";};?>>Fixed</option>
                                                      
                                                 </select>
                                                 </div>
                                                   
                                                </div> -->

                                                
                                                <!-- <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('loyalty_value'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="loyalty_value" id="loyalty_value" class="form-control" placeholder="<?php echo lang('loyalty_value'); ?>" value="<?php echo getConfig('loyalty_value'); ?>">
                                                    </div>
                                                </div> -->
                                                
                                                
                                                
                                               <!--  <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('premium_member_offer'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="premium_member_offer" id="premium_member_offer" class="form-control" placeholder="<?php echo lang('premium_member_offer'); ?>" value="<?php echo getConfig('premium_member_offer'); ?>">
                                                    </div>
                                                </div> -->


                                               <!-- <div class="hr-line-dashed"></div>
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('trending_type'); ?></label>
                                                  <div class="col-sm-10">
                                                  <select class="form-control" name="trending_type" id="trending_type">
                                                  
                                                  <option value="1" <?php if(getConfig('trending_type')==1){echo"selected";}?>>Manual</option>
                                                  <option value="2" <?php if(getConfig('trending_type')==2){echo"selected";}?>>Calculate</option>
                                                      
                                                 </select>
                                                 </div>
                                                   
                                                </div> -->

                                                 
                                                 <!-- <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('top_products'); ?></label>
                                                  <div class="col-sm-10">
                                                 <select class="" name="top_products[]" id="top_products" style="width:100%" multiple="" placeholder="<?php echo lang('select_product'); ?>">

                                                    <?php 
                                                      $option = array('table' => CONFIGURE_PRODUCTS . ' as product',
                                                     'select' => 'product.id,product.product_id'
                                                   );
                                                    $configure_products = commonGetHelper($option);

                                                     if(!empty($products)){foreach($products as $product){?>
                                                  
                                                     <option value="<?php echo $product->id;?>"  <?php foreach($configure_products as $key){if($key->product_id==$product->id) {?> selected <?php } }?>><?php echo $product->product_name;?></option>
                                                    <?php }}?>
                                                  </select>
                                                 </div>
                                                   
                                                </div> -->
                                               <!--  <div id ="product_check" style="color:red; padding-left:20%"></div> -->
                                             

                                                 <div class="hr-line-dashed"></div>
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('site_name'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="site_name" id="site_name" class="form-control" placeholder="<?php echo lang('site_name'); ?>" value="<?php echo getConfig('site_name'); ?>">
                                                    </div>
                                                </div>
                                                 <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('site_logo'); ?></label>
                                                    <div class="col-sm-10">
                                                        <div class="col-md-9">
                                                            <div class="profile_content edit_img">
                                                            <div class="file_btn file_btn_logo">
                                                              <input type="file"  class="input_img2" id="user_image" name="user_image" style="display: inline-block;">
                                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                                  <div id="show_company_img"></div>
                                                                <span class="ceo_logo">
                                                                    <?php $site_logo = getConfig('site_logo'); 
                                                                    if(!empty($site_logo)){?>
                                                                        <img src="<?php echo base_url().$site_logo;?>">
                                                                   <?php }else{ ?>
                                                                        <img src="<?php echo base_url().'backend_asset/images/default.jpg';?>">
                                                                  <?php  } ?>
                                                                    
                                                                        <input type="hidden" name="site_logo_url" value="<?php echo $site_logo;?>" />
                
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

                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('gst'); ?>(%)</label>
                                                    <div class="col-sm-10"><input type="text" name="gst" id="gst" class="form-control" placeholder="<?php echo lang('gst'); ?>" value="<?php echo getConfig('gst'); ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('alacart_cancel_percent'); ?> (%)</label>
                                                    <div class="col-sm-10"><input type="text" name="alacart_cancel_percent" id="alacart_cancel_percent" class="form-control" placeholder="<?php echo lang('alacart_cancel_percent'); ?>" value="<?php echo getConfig('alacart_cancel_percent'); ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('foodparcel_cancel_percent'); ?> (%)</label>
                                                    <div class="col-sm-10"><input type="text" name="foodparcel_cancel_percent" id="foodparcel_cancel_percent" class="form-control" placeholder="<?php echo lang('foodparcel_cancel_percent'); ?>" value="<?php echo getConfig('foodparcel_cancel_percent'); ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('partypackage_cancel_percent'); ?> (%)</label>
                                                    <div class="col-sm-10"><input type="text" name="partypackage_cancel_percent" id="partypackage_cancel_percent" class="form-control" placeholder="<?php echo lang('partypackage_cancel_percent'); ?>" value="<?php echo getConfig('partypackage_cancel_percent'); ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('alacart_cancel_time'); ?> (Hours)</label>
                                                    <div class="col-sm-10"><input type="text" name="alacart_cancel_time" id="alacart_cancel_time" class="form-control" placeholder="<?php echo lang('alacart_cancel_time'); ?>" value="<?php echo getConfig('alacart_cancel_time'); ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('foodparcel_cancel_time'); ?> (Hours)</label>
                                                    <div class="col-sm-10"><input type="text" name="foodparcel_cancel_time" id="foodparcel_cancel_time" class="form-control" placeholder="<?php echo lang('foodparcel_cancel_time'); ?>" value="<?php echo getConfig('foodparcel_cancel_time'); ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('partypackage_cancel_time'); ?> (Hours)</label>
                                                    <div class="col-sm-10"><input type="text" name="partypackage_cancel_time" id="partypackage_cancel_time" class="form-control" placeholder="<?php echo lang('partypackage_cancel_time'); ?>" value="<?php echo getConfig('partypackage_cancel_time'); ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('wallet_amount'); ?> (&#8377;)</label>
                                                    <div class="col-sm-10"><input type="text" name="wallet_amount" id="wallet_amount" class="form-control" placeholder="<?php echo lang('wallet_amount'); ?>" value="<?php echo getConfig('wallet_amount'); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-4 col-sm-offset-2">
                                                        <!-- <button class="btn btn-danger" type="submit"><?php echo lang('cancel_btn'); ?></button> -->
                                                        <button class="<?php echo THEME_BUTTON; ?>" type="submit" id="submit" ><?php echo lang('save_btn'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>  

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <script>  

    </script>
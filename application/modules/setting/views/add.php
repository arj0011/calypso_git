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
                                            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('setting/setting_add') ?>" enctype="multipart/form-data">
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('admin_email'); ?></label>

                                                    <div class="col-sm-10"><input type="text" name="admin_email" id="admin_email" class="form-control" placeholder="email@example.com" value="<?php echo getConfig('admin_email'); ?>">
                                                        <span class="help-block m-b-none"> The email set all mail configural from.</span></div>
                                                </div>
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
                                                <div class="hr-line-dashed"></div>
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('date_foramte'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="date_foramte" id="date_foramte" class="form-control" placeholder="<?php echo lang('date_foramte'); ?>" value="<?php echo getConfig('date_foramte'); ?>">
                                                    </div>
                                                </div>

                                                <div class="hr-line-dashed"></div>
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('date_timeforamte'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="date_timeforamte" id="date_timeforamte" class="form-control" placeholder="<?php echo lang('date_timeforamte'); ?>" value="<?php echo getConfig('date_timeforamte'); ?>">
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('site_meta_title'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="site_meta_title" id="site_meta_title" class="form-control" placeholder="<?php echo lang('site_meta_title'); ?>" value="<?php echo getConfig('site_meta_title'); ?>">
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('site_meta_description'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="site_meta_description" id="site_meta_description" class="form-control" placeholder="<?php echo lang('site_meta_description'); ?>" value="<?php echo getConfig('site_meta_description'); ?>">
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('google_captcha'); ?></label>
                                                    <div class="col-sm-10">
                                                        <input type="checkbox" class="js-switch" name="google_captcha" id="google_captcha" <?php  echo (getConfig('google_captcha') == 'on') ? "checked" : ""; ?>/>    
                                                    </div>
                                                </div>
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('data_sitekey'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="data_sitekey" id="data_sitekey" class="form-control" placeholder="<?php echo lang('data_sitekey'); ?>" value="<?php echo getConfig('data_sitekey'); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group"><label class="col-sm-2 control-label"><?php echo lang('secret_key'); ?></label>
                                                    <div class="col-sm-10"><input type="text" name="secret_key" id="secret_key" class="form-control" placeholder="<?php echo lang('secret_key'); ?>" value="<?php echo getConfig('secret_key'); ?>">
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>
                                                <div class="form-group">
                                                    <div class="col-sm-4 col-sm-offset-2">
                                                        <button class="btn btn-danger" type="submit"><?php echo lang('cancle_btn'); ?></button>
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

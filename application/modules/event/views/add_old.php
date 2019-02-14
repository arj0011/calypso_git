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
                             <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('event/event_add') ?>" enctype="multipart/form-data">
                               <div class="col-lg-12" id="0">
                                 <div class="form-group">
                                   <label class="col-sm-2 control-label"><?php echo lang('event_redirect_url'); ?></label>
                                   
                                   <div class="col-sm-5"><input type="text" name="url[]" id="url" class="form-control" placeholder="<?php echo lang('event_redirect_url'); ?>" value="<?php echo getConfig('event_redirect_url'); ?>">
                                    </div>

                                    <div class="col-md-5">
                                      <div class="profile_content edit_img">
                                        <div class="file_btn file_btn_logo">
                                          <input type="file"  class="input_img2" id="user_image" name="user_image[]" style="display: inline-block;">
                                          <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                          <div id="show_company_img"></div>
                                          <span class="ceo_logo">
                                              <img src="<?php echo base_url().'backend_asset/images/default.jpg';?>">
                                          </span><i class="fa fa-camera"></i></span>
                                          <img class="show_company_img2" style="display:none" alt="img" src="<?php echo base_url() ?>/backend_asset/images/logo.png">
                                          <span style="display:none" class="fa fa-close remove_img"></span>
                                        </div>
                                      </div>
                                      <div class="ceo_file_error file_error text-danger"></div>
                                    </div>
                                </div>
                               </div> 
                                <button type="button" class="btn btn-primary AddRow">+</button>
                                
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <button type="submit" id="" class="btn btn-primary">Submit</button>  
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
  <script>  

  </script>
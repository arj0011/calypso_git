<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('users/user_update') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                </div>
                <div class="modal-body">
                    <div class="loaders">
                        <img src="<?php echo base_url().'backend_asset/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="error-messages" style="display:none; color:red; padding-left:70px"></div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                           <div class="col-md-12" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo lang('user_name');?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="full_name" id="full_name" placeholder="<?php echo lang('user_name');?>" value="<?php echo $results->full_name;?>"/>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo lang('user_email');?></label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="user_email" id="user_email" value="<?php echo $results->email;?>" readonly="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo lang('phone_no');?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="phone_no" id="phone_no" placeholder="<?php echo lang('phone_no');?>" value="<?php echo $results->phone;?>" readonly="" />
                                </div>
                                <!-- <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span> -->
                            </div>
                        </div>
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo lang('password');?></label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="password" id="password" />
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('profile_image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="user_image" name="user_image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                    <?php if(!empty($results->user_image)){ ?>
                                                        <img src="<?php echo base_url().$results->user_image;?>">
                                                    <?php }else{ ?>
                                                        <img src="<?php echo base_url().'backend_asset/images/default.jpg';?>">
                                                   <?php }?>
                                                    
                                                </span>
                                                <i class="fa fa-camera"></i>
                                              </span>
                                              <img class="show_company_img2" style="display:none" alt="img" src="<?php echo base_url() ?>/assets/img/logo.png">
                                              <span style="display:none" class="fa fa-close remove_img"></span>
                                            </div>
                                          </div>
                                          <div class="ceo_file_error file_error text-danger"></div>
                                    </div>
                                </div>
                                </div>
                                
                        
                        
                        <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                       <!--  <input type="hidden" name="password" value="<?php echo $results->password;?>" /> -->
                        <input type="hidden" name="exists_image" value="<?php echo $results->user_image;?>" />
                        
                        <div class="space-22"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('close_btn');?></button>
                <button type="submit"  class="<?php echo THEME_BUTTON;?>" id="submit"><?php echo lang('update_btn');?></button>
            </div>
        </form>
    </div> <!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    $('#date_of_birth').datepicker({
        startView: 2,
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        endDate:'today',
        format: 'yyyy-mm-dd',
        
        
    });
</script>
<script src='<?php echo base_url(); ?>backend_asset/js/login.js'></script>
<!-- <script type="text/javascript">
    (function($){
        
        $('#submit').click(function(e){
            var myModal = new Signup({fullNameEleId: 'full_name',
                emailId : 'user_email',
                pwdId:'password',
                phoneId:'phone_no',
                formId:'editFormAjax'});

            if(myModal.error==true){
                e.preventDefault();
                var message = myModal.error_message;
                
                $(".error-messages").text(message).fadeIn().delay(1000).fadeOut();
                
            }
        });
    })(jQuery);
</script> -->
<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('partypackage/item_add') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                </div>
                <div class="modal-body"> 
                    <div class="loaders">
                        <img src="<?php echo base_url().LOADER_IMG_PATH;?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                        
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('package_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="food_name" id="food_name" placeholder="<?php echo lang('package_name');?>" />
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('package_description');?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="description" id="description" placeholder="<?php echo lang('package_description');?>"></textarea>

                                    </div>
                                    
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('package_image'); ?></label>
                                    <div class="col-md-9">
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
                                              <span style="display:none" class="fa fa-close remove_img"></span>
                                            </div>
                                          </div>
                                          <div class="ceo_file_error file_error text-danger"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('package_price');?> (&#8377;)</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="price" id="price" placeholder="<?php echo lang('package_price') ;?>" />
                                    </div>
                                    
                                </div>
                            </div>
                            

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('gender_pref');?> </label>
                                    <div class="col-md-9">
                                    <label>
                                        <input type="checkbox" name="gender[]" id="male" value="1" /> Male
                                    </label>
                                    <label>
                                        <input type="checkbox" name="gender[]" id="female" value="2"> Female
                                    </label>    
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?php echo lang('age_pref');?></label>
                                    <div class="col-md-4">
                                    <label class="control-label"><?php echo lang('age_min');?></label>
                                    <select class="form-control" name="min_age" id="min_age">
                                    <?php for($i=10; $i<=40 ; $i=$i+10) { ?> 
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>    
                                    <?php } ?>    
                                    </select> 
                                    </div>
                                    <div class="col-md-4">
                                    <label class="control-label"><?php echo lang('age_max');?></label>
                                    <select class="form-control" name="max_age" id="max_age">
                                    <?php for($j=20; $j<=50 ; $j=$j+10) { ?>
                                        <option value="<?php echo $j; ?>"><?php echo $j; ?></option>    
                                    <?php } ?>
                                    </select> 
                                    </div>
                                </div>
                            </div>

                            <div class="my_appendiv">
                                <div class="col-md-12 cat_cls_div" >
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <select class="form-control category_cls" id="category_1" name="category[]">
                                            <option value="">Select</option>
                                            <?php if(!empty($category)){foreach($category as $cat){?>    
                                                <option value="<?php echo $cat->id;?>"><?php echo $cat->category_name;?></option>
                                            <?php }}?>
                                            </select> 
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" id="limit_1" name="limit[]" class="form-control" placeholder="Limit" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <div class="col-md-8 cat_item_drpdwn" id="itemDivId_1">
                                            
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:void(0);" id="rowcount_1" class="btn btn-primary addmorerow">+</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('reset_btn');?></button>
                    <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('submit_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
$(document).ready(function(){
    $('.category_cls').select2();
});

$("#prelaunch_date").datepicker({
                todayBtn: "linked",
                format: 'yyyy/mm/dd',
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                startDate: '-0m'
            });
</script>
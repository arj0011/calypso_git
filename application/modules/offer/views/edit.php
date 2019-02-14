<style> 
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style> 
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('offer/offer_update') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                </div>
                <div class="modal-body">
                    <div class="loaders">
                        <img src="<?php echo base_url().'assets/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                        <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('offer_name');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="offer_text" id="offer_text" placeholder="<?php echo lang('offer_text');?>" value="<?php echo $results->offer_text;?>" />
                                    </div>
                                    </div>
                        </div>  
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label class="col-md-3 control-label">Date</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="date" id="date" placeholder="Date;?>" value="<?php echo date('d-m-Y',strtotime($results->created_date));?>" readonly />
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('offer_image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="offer_image" name="offer_image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                    <?php if(!empty($results->offer_image)){ ?>
                                                        <img src="<?php echo base_url().'uploads/offer/'.$results->offer_image;?>">
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
                            <div class="form-group ">
                                <label class="col-lg-3 control-label"> 
                                    <?php echo lang('status'); ?></label>
                                    <div class="col-md-9">
                                        <div class="custom_chk chk_box">
                                          <div class="checkbox">
                                            <input type="radio" <?php echo ($results->status == '1') ? 'checked' : ''; ?> class="all_user" name="status" value="1"><label class="checkbox-inline"><?php echo lang('active'); ?></label>
                                        </div>
                                        
                                        <div class="checkbox">
                                            <input type="radio" <?php echo ($results->status == '0') ? 'checked' : ''; ?> class="all_user" name="status" value="0"><label class="checkbox-inline"> <?php echo lang('inactive'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-12" >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?php echo lang('select_category');?></label>
                                        <div class="col-md-9">
                                             <select class="form-control" name="type" id="type" style="width:100%">
                                                <option value="">Select</option>
                                                <option <?php if($results->type=='allacart') echo "selected";?> value="allacart"><?php echo lang('alla_cart');?></option>
                                                <option <?php if($results->type=='foodparcel') echo "selected";?> value="foodparcel"><?php echo lang('food_parcel');?></option>
                                                <option <?php if($results->type=='partypackage') echo "selected";?> value="partypackage"><?php echo lang('party_package');?></option>
                                                
                                            </select>
                                        </div>
                                        
                                    </div>
                            </div>
                        <div class="col-md-12" >
                            <div class="form-group ">
                                <label class="col-lg-3 control-label"> 
                                    <?php //echo lang('status'); ?></label>
                                    <div class="col-md-9">
                                        <div class="custom_chk chk_box">
                                            <div class="">
                                                <input type="checkbox" <?php echo ($results->show_front == '1') ? 'checked' : ''; ?> class="all_user" name="show_front" value="1"><label class="checkbox-inline"><?php echo lang('show_front'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                            <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                            <input type="hidden" name="exists_image" value="<?php echo $results->offer_image;?>" />
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
// $('#date').datepicker({autoclose:true,startDate: '-0m',format: 'yyyy-mm-dd',keyboardNavigation:false,minDate:new Date()
// }); 
</script>
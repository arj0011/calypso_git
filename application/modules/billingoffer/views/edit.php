<style> 
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style> 
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('billingoffer/offer_update') ?>" enctype="multipart/form-data">
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
                                        <label class="col-md-3 control-label"><?php echo lang('select_category');?></label>
                                        <div class="col-md-9">
                                             <select class="form-control" name="type" id="type" style="width:100%">
                                                <option value="">Select</option>
                                                  <option <?php if($results->type == 1) echo "selected";?> value="1">Al-a Cart</option>
                                                  <option <?php if($results->type == 2) echo "selected";?> value="2">Food Parcel</option>
                                                  <option <?php if($results->type == 3) echo "selected";?> value="3">Party Package</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                            </div>

                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('amount');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="amount" id="amount" placeholder="<?php echo lang('amount');?>" value="<?php echo $results->amount;?>" />
                                    </div>
                                    </div>
                                </div>

                                <div class="col-md-12" >
                                 <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('discount');?> (%)</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="discount" id="discount" placeholder="<?php echo lang('discount');?>" value="<?php echo $results->discount;?>" />
                                    </div>
                                   </div> 
                                </div>

                                <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" value="<?php echo date('d-m-Y',strtotime($results->created));?>" name="date" id="date" readonly placeholder="Date" />
                                    </div>
                                    
                                </div>
                                </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('image'); ?></label>
                                    <div class="col-md-9">
                                            <div class="profile_content edit_img">
                                            <div class="file_btn file_btn_logo">
                                              <input type="file"  class="input_img2" id="image" name="image" style="display: inline-block;">
                                              <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                                <div id="show_company_img"></div>
                                                <span class="ceo_logo">
                                                    <?php if(!empty($results->image)){ ?>
                                                        <img src="<?php echo base_url().'uploads/billingoffer/'.$results->image;?>">
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
                            
                            <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                            <input type="hidden" name="exists_image" value="<?php echo $results->image;?>" />
                           
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
<script type="text/javascript">
//     $('#date').datepicker({autoclose:true,startDate: '-0m',format: 'yyyy-mm-dd',keyboardNavigation:false,minDate:new Date()
// });
</script>
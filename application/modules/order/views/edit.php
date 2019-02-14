<style> 
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style> 
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('order/order_update') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label"><?php echo lang('remaining_amount');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="remaining_amount" id="remaining_amount" placeholder="<?php echo lang('remaining_amount');?>" value="<?php echo $results->remaining_amount;?>" />
                                    </div>
                                    </div>
                                </div>

                                <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('payed_amount');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="payed_amount" id="payed_amount" placeholder="<?php echo lang('payed_amount');?>" value="<?php echo $results->wallet_payment;?>" readonly />
                                    </div>
                                    </div>
                                </div>

                                 <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('cash_payment');?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="cash_payment" id="cash_payment" placeholder="<?php echo lang('cash_payment');?>" value="<?php echo $results->cash_payment;?>" />
                                    </div>
                                    </div>
                                </div>


                        
                            <input type="hidden" name="id" value="<?php echo $results->id;?>" />
                           <input type="hidden" name="user_id" value="<?php echo $results->user_id;?>" />
                           
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
$(document).ready(function(){
$('#category_id').select2();
});
</script>
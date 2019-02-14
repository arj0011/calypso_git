<style>
    .modal-footer .btn + .btn {
    margin-bottom: 5px !important;
    margin-left: 5px;
}
</style>
<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('allacart/item_add') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                    <div>
                        <span>Offer Title : 
                            <?php echo (isset($offer_title)) ? ucwords($offer_title) : "" ?>
                        </span>
                    </div>
                </div>
                <div class="modal-body"> 
                    <div class="loaders">
                        <img src="<?php echo base_url().LOADER_IMG_PATH;?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                        <?php if(!empty($days_Data)){
                            foreach($days_Data as $dayname =>$daysnamedata){?>
                        
                        <div class="col-md-12" >
                        <label><?php echo $dayname;?></label>
                        <div class="time-list">
                        <ul>
                        <?php foreach($daysnamedata as $dystym){?>
                            
                                <li>
                                <?php echo $dystym->start_time.'-'.$dystym->end_time;?>
                                <span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $dystym->price;?></span>                                         
                                </li>
                        <?php } ?>  
                        </ul> 
                        </div> 
                        </div>
                            <div class="space-22"></div>
                            <?php }
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <!-- <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('submit_btn');?></button> -->
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

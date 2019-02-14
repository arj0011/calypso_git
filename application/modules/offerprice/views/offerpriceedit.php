<link href="<?php echo base_url().CUSTOMCSS;?>" rel="stylesheet"/> 
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <!-- <a href="<?php echo site_url("$ctlr");?>"><?php echo lang('food_items_management');?></a> -->
        <a href="<?php echo site_url("$ctlr");?>"><?php echo lang('food_items_management');?> / <?php echo ucwords($ctlr);?></a> / Edit Offer Price
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
        <div class="ibox-title">
        </div>
        <div class="ibox-content">
         <div class="row">
            <?php $message = $this->session->flashdata('success');
            if(!empty($message)):?><div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <?php echo $message;?></div><?php endif; ?>
            <?php $error = $this->session->flashdata('error');
            if(!empty($error)):?><div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <?php echo $error;?></div><?php endif; ?>
            <div id="message"></div>
            <form class="" role="form" method="post" id="someid" action="<?php echo base_url('offerprice/offerprice_update') ?>" enctype="multipart/form-data">
              <input type="hidden" name="ctlr" id="ctlr" value="<?php echo $ctlr;?>">
              <input type="hidden" name="item_id" id="item_id" value="<?php echo $item_id;?>">
              <input type="hidden" name="item_dates_id" id="item_dates_id" value="<?php echo $item_dates_id;?>">
             <!--  <input type="hidden" name="start_date" value="<?php echo $start_date;?>">
              <input type="hidden" name="end_date" value="<?php echo $end_date;?>"> -->
              
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-md-6">
                  <label class="col-md-3 control-label"><?php echo lang('offer_title');?></label>
                    <div class="form-group">
                      <input type="text" name="offer_title" value="<?php echo decoding($offer_title);?>" id="offer_title" placeholder="<?php echo lang('offer_title');?>" class="form-control" /> 
                    </div>
                  </div>

                  <div class="col-md-6">
                  <label class="col-md-3 control-label minQtyCls"><?php echo lang('min_qty');?></label>
                    <div class="form-group">
                      <input type="text" name="min_qty" id="min_qty" value="<?php echo decoding($min_qty);?>" placeholder="<?php echo lang('min_qty');?>" class="form-control" /> 
                    </div>
                  </div>
                  </div>
              </div>

            <div class="col-lg-12">
              <div class="row">
                <div class="col-md-6">
                <label class="col-md-3 control-label"><?php echo lang('start_date');?></label>
                  <div class="form-group">
                    <input type="text" name="start_date" value="<?php echo date('d-m-Y',strtotime(decoding($start_date)));?>" id="" placeholder="<?php echo lang('start_date');?>" class="form-control" readonly />
                    <div class="start_date_validation error"></div> 
                  </div>
                </div>

                <div class="col-md-6">
                <label class="col-md-3 control-label"><?php echo lang('end_date');?></label>
                  <div class="form-group">
                    <input type="text" name="end_date" id="" value="<?php echo date('d-m-Y',strtotime(decoding($end_date)));?>" placeholder="<?php echo lang('end_date');?>" class="form-control" readonly /> 
                    <div class="end_date_validation error"></div>
                  </div>
                </div>
                </div>
              </div>


              <?php if(!empty($days_Data)){
                $i=0;
                foreach($days_Data as $dayname =>$daysnamedata){
                  $dayname_count = count($daysnamedata);
              ?>
                
                <div class="col-md-12" >
                  <label><?php echo $dayname;?></label>
                  <input type="hidden" name="days[]" value="<?php echo $dayname;?>">
                  <div class="row fulldiv_<?php echo $dayname;?>">
                        <?php $j = 0; foreach($daysnamedata as $dystym){ ?>
                        <div class="timeprice_<?php echo $dayname;?>_<?php echo $j;?>">
                          <div class="col-md-3">
                            <input type="text" id="st_<?php echo $dayname;?>_<?php echo $j;?>" value="<?php echo $dystym->start_time;?>" placeholder="Start time" name="<?php echo $dayname;?>_start_time[]" class="form-control" readonly style="cursor: not-allowed;" />  
                          </div>
                          <div class="col-md-3">    
                            <input type="text" id="et_<?php echo $dayname;?>_<?php echo $j;?>" value="<?php echo $dystym->end_time; ?>" placeholder="End time" name="<?php echo $dayname;?>_end_time[]" class="form-control" readonly style="cursor: not-allowed;" />
                          </div>
                          <div class="col-md-3">    
                            <input type="text" id="pr_<?php echo $dayname;?>_<?php echo $j;?>" value="<?php echo $dystym->price; ?>" placeholder="Price" name="<?php echo $dayname;?>_price[]" class="form-control" readonly style="cursor: not-allowed;" />
                          </div>
                          <div class="col-md-3"> 
                            <?php if($dayname_count > 1): ?>
                            <button type="button" onclick="deletetimerow('<?php echo encoding($dystym->items_dates_days_id);?>')" class="btn btn-danger">-</button>
                          <?php endif; ?>
                          </div>
                        </div>
                        <?php $j++;} ?>    
                  </div>
                </div>
                <div class="col-md-3">  
                    <button type="button" data-button-count="<?php echo ($j - 1);?>" data-buttonday="<?php echo $dayname;?>" value="<?php echo ($j > 0) ? ($j - 1) : 0;?>" class="btn btn-primary AddPrice">+</button>
                </div> 
              <?php $i++;}} ?>
            <div class="col-lg-12">
              <button type="submit" id="offerpriceSubmit" class="btn btn-primary">Submit</button>
            </div>
            </form>
          </div>
        </div>
        <div id="form-modal-box"></div>
      </div>
    </div>
  </div>
  <div id="message_div">
      <span id="close_button"><img src="<?php echo base_url();?>backend_asset/images/close.png" onclick="close_message();"></span>
      <div id="message_container"></div>
  </div>


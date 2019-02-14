<link href="<?php echo base_url().CUSTOMCSS;?>" rel="stylesheet"/> 
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
   <?php 
   
   $ctlr = $this->uri->segment(3);
   $ctlstr = $ctlr;
   if($ctlr == 'allacart'):
    $ctlstr = 'A-la Carte';
    endif;
   ?>
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <a href="<?php echo site_url("$ctlr");?>"><?php echo lang('food_items_management');?> / <?php echo ucwords($ctlstr);?></a> / Offer Price
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
           
            <form class="someclass" role="form" method="post" id="someid" action="<?php echo base_url('offerprice/addprice') ?>" enctype="multipart/form-data">
            <input type="hidden" name="ctlr" value="<?php echo $ctlr;?>">
             <input type="hidden" name="item_id" id="item_id" value="<?php echo end($this->uri->segments);?>">
            
             <div class="col-lg-12">
              <div class="row">
                <div class="col-md-6">
                <label class="col-md-3 control-label"><?php echo lang('offer_title');?></label>
                  <div class="form-group">
                    <input type="text" name="offer_title" id="offer_title" placeholder="<?php echo lang('offer_title');?>" class="form-control" /> 
                  </div>
                </div>

                <div class="col-md-6">
                <label class="col-md-3 control-label minQtyCls"><?php echo lang('min_qty');?></label>
                  <div class="form-group">
                    <input type="text" name="min_qty" id="min_qty" onblur="<?php if($ctlr == 'partypackage'):?>minPersnFun(this);<?php endif;?>" placeholder="<?php echo lang('min_qty');?>" class="form-control" />
                    <input type="hidden" name="min_persn" id="min_persn" value="<?php echo (isset($min_person)) ? $min_person : '';?>"> 
                  </div>
                </div>
                </div>
            </div>

            <div class="col-lg-12">
              <div class="row">
                <div class="col-md-6">
                <label class="col-md-3 control-label"><?php echo lang('start_date');?></label>
                  <div class="form-group">
                    <input type="text" name="start_date" id="start_date" placeholder="<?php echo lang('start_date');?>" class="form-control" />
                    <div class="start_date_validation error"></div> 
                  </div>
                </div>

                <div class="col-md-6">
                <label class="col-md-3 control-label"><?php echo lang('end_date');?></label>
                  <div class="form-group">
                    <input type="text" name="end_date" id="end_date" placeholder="<?php echo lang('end_date');?>" class="form-control" /> 
                    <div class="end_date_validation error"></div>
                  </div>
                </div>
                </div>
            </div>


            <div id="acc"></div>

            <div class="col-lg-12">
              <button type="submit" id="offerpriceSubmit" class="btn btn-primary">Submit</button>
              <a href="javascript:window.history.go(-1);" class="btn btn-danger">Back</a>
            </div>
            </form>


            <div class="col-lg-12" style="overflow-x: auto">
            <table class="table table-bordered table-responsive" id="common_datatable">
              <thead>
                <tr>
                  <th><?php echo lang('serial_no');?></th>
                  <th><?php echo lang('offer_title');?></th>
                  <th><?php echo lang('min_qty');?></th>
                  <th><?php echo lang('start_date');?></th>
                  <th><?php echo lang('end_date');?></th>
                  <!-- <th><?php echo lang('day');?> ($)</th> -->
                  <th><?php echo lang('action');?></th>
                </tr>
              </thead>
              <tbody>
                <?php 

                if (isset($datesData) && !empty($datesData)):
                  $rowCount = 0;
                foreach ($datesData as $rows):
                  $rowCount++;
                ?>
                <tr>
                  <td><?php echo $rowCount; ?></td>

                  <td><?php echo $rows->offer_title; ?></td>
                  <td><?php echo $rows->min_qty; ?></td>
                  <td><?php echo convertDate($rows->start_date);?></td>
                  <td><?php echo convertDate($rows->end_date);?></td>
                  <!-- <td><?php echo $rows->day;?></td> -->
                  <td>
                  <a href="javascript:void(0)" onclick="opn_modal('offerprice','open_daytime_modal','<?php echo $rows->id;?>')" class="on-default edit-row"><img width="20" src="<?php echo base_url().VIEW_ICON;?>" /></a>
                  <a href="<?php echo base_url('offerprice/offerprice_edit');?>/<?php echo $this->uri->segment(3)?>/<?php echo encoding($rows->id);?>/<?php echo encoding($rows->item_id)?>" class="on-default edit-row"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                  </td>
                </tr>
                <?php endforeach; endif;?>
                </tbody>
              </table>
              </div>


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

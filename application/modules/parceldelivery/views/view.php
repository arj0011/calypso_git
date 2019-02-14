<style>
  #message_div{
    background-color: #ffffff;
    border: 1px solid;
    box-shadow: 10px 10px 5px #888888;
    display: none;
    height: auto;
    left: 36%;
    position: fixed;
    top: 20%;
    width: 40%;
    z-index: 1;
  }
  .ui-helper-hidden-accessible
  {
   display: none; 
 }
 #close_button{
  right:-15px;
  top:-15px;
  cursor: pointer;
  position: absolute;
}
#close_button img{
  width:30px;
  height:30px;
}    
#message_container{
  height: 450px;
  overflow-y: scroll;
  padding: 20px;
  text-align: justify;
  width: 99%;
}
.edit-row{
  color :red;
  text-decoration: underline;
}
.ui-autocomplete{
  background-color: #fff;

}

</style>


<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <a href="<?php echo site_url('purchase');?>"><?php echo lang('purchase_management');?></a>
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
       <!--  <div class="ibox-title">
          <div class="btn-group " href="#">
            <a href="<?=base_url('/purchase/add'); ?>" class="btn btn-primary">
              <?php echo lang('add_purchase');?>
              <i class="fa fa-plus"></i>
            </a>
          </div>
        </div> -->
        <div class="ibox-content">
         <div class="row">
          <?php $message = $this->session->flashdata('success');
          if(!empty($message)):?><div class="alert alert-success">
          <?php echo $message;?></div><?php endif; ?>
          <?php $error = $this->session->flashdata('error');
          if(!empty($error)):?><div class="alert alert-danger">
          <?php echo $error;?></div><?php endif; ?>
          <div id="message"></div>
          <div class="col-lg-12" >
            <form method="post" action="<?=base_url('purchase/add_process');?>" class="form form_product">
              <div class="row">
               <div class="col-md-6">
                 <div class="form-group">
                  <label class="control-label">Delivery Date</label>
                  
                  <input type="text" class="form-control"  name="order_date1" id="order_date1" value="<?php echo $order[0]->delivery_date; ?>" readonly>


                </div>
              </div>
              <div class="col-md-6">
               <div class="form-group">
                <label class="control-label">Delivery Time</label>

                <input type="text" class="form-control order_date"  name="order_via" id="order_via" value="<?php echo $order[0]->delivery_time; ?>"  readonly>


              </div>
            </div>

            <div class="col-md-12">
             <div class="form-group">
              <label class="control-label"><?=lang('full_name');?></label>

              <input type="text" class="form-control order_name"  name="order_name" id="order_name" placeholder="<?=lang('full_name');?>" value="<?php echo $order[0]->full_name; ?>" readonly>
              <?php echo form_error('order_name'); ?>
              <input type="hidden" name="order_name_id" class="order_name_id" id="order_name_id">

            </div>
          </div>

          <div class="col-md-6">
           <div class="form-group">
            <label class="control-label"><?=lang('user_email');?></label>
            <input type="text" class="form-control order_email"  name="order_email" id="order_email" placeholder="<?=lang('user_email');?>" value="<?php echo $order[0]->email; ?>" readonly>
             <?php echo form_error('order_email'); ?>
          </div>
        </div>

        <div class="col-md-6">
         <div class="form-group">
          <label class="control-label"><?=lang('phone_no');?></label>

          <input type="text" class="form-control order_phone"  name="order_phone" id="order_phone" placeholder="<?=lang('phone_no');?>" value="<?php echo $order[0]->phone; ?>" readonly>
        </div>
      </div>

      <div class="col-md-6">
         <div class="form-group">
          <label class="control-label"><?=lang('remaining_amount');?></label>

          <input type="text" class="form-control order_phone"  name="remaining_amount" id="remaining_amount" placeholder="<?=lang('remaining_amount');?>" value="<?php echo $order[0]->pending_amount; ?>" readonly>
        </div>
      </div>

      <div class="col-md-6">
         <div class="form-group">
          <label class="control-label">Paid Amount</label>

          <input type="text" class="form-control order_phone"  name="payed_amount" id="payed_amount" placeholder="Paid Amount" value="<?php echo $order[0]->paid_amount; ?>" readonly>
        </div>
      </div>

      <div class="col-md-6">
         <div class="form-group">
          <label class="control-label">Net Amount</label>

          <input type="text" class="form-control"  name="net_amount" id="net_amount" placeholder="Net Amount" value="<?php echo $order[0]->net_amount; ?>" readonly>
        </div>
      </div>

       <div class="col-md-6">
         <div class="form-group">
          <label class="control-label">Partial Amount</label>

          <input type="text" class="form-control order_phone"  name="estimated_delivery_date" id="estimated_delivery_date" placeholder="Partial Amount" value="<?php echo $order[0]->partial_payment; ?>" readonly>
        </div>
      </div>
       <?php 
        if($order[0]->status == 1){
          $status= "Pending";
        }else if($order[0]->status == 2){
          $status= "Confirm";
        }else if($order[0]->status == 3){
          $status= "In Process";
        }else if($order[0]->status == 4){
          $status= "Complete";
        }else if($order[0]->status == 5){
          $status= "Canceled";
        }else if($order[0]->status == 6){
          $status= "Deliver";
        } ?>
      <div class="col-md-6">
         <div class="form-group">
          <label class="control-label"><?=lang('order_status');?></label>
         
          <input type="text" class="form-control order_phone"  name="order_status" id="order_status" placeholder="<?=lang('order_status');?>" value="<?php echo $status; ?>" readonly>
        </div>
      </div>

       <!-- <div class="col-md-6">
         <div class="form-group">
          <label class="control-label"><?=lang('order_priority');?></label>

          <input type="text" class="form-control order_phone"  name="order_priority" id="order_priority" placeholder="<?=lang('order_priority');?>" value="<?php //echo $order[0]->order_priority; ?>" readonly>
        </div>
      </div> -->


      <hr/>
    
     <div class="billing_items">
        <div class="row">
          <div class="col-sm-12">
            <div class="clearfix" style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding:10px 0px; margin-bottom:20px;">
             <div class="product_No_cont col-md-1">S.No.</div>
             <div class="product_name col-md-3">Item Name</div>
             <div class="product_price col-md-2">Item Price</div>
             <div class="product_quantity col-md-2">Item Qty.</div>
             <div class="total col-md-2">Total</div>
            

           </div>
         </div>
       </div>
       <?php if(!empty($order)){
        $rowCount = 0;
       foreach($order as $ord){
         $rowCount++;?>
       <div class="row item-container" style="margin-bottom: 5px;">
         <div class="product_no col-md-1" style="text-align:center"><?php echo $rowCount ?></div>
         <div class="product_name col-md-3"><input type="text" class="product_name form-control" name="product_name[0]" required value="<?php echo $ord->item_name; ?>" readonly>
         <input type="hidden" name="product_id[0]" class="product_id" id="product_id"></div>
         <div class="product_price col-md-2"><input type="text" class="product_price form-control" name="product_price[0]" readonly value="<?php echo $ord->price; ?>" >
         </div>

         <div class="product_qua col-md-2"><input type="number" class="qty form-control"  name="product_qty[0]" value="<?php echo $ord->quanity; ?>" readonly >
         </div>
         <div class="total col-md-2"><input type="text" class="product_total form-control" name="product_total[0]" readonly value="<?php echo ($ord->price * $ord->quanity); ?>">
         </div>

         
       </div>
     
    <?php }} ?>
    </div>

     <div class="row">
      <div class="col-sm-12">
        <div class="clearfix" style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding:10px 0px; margin-bottom:20px;">
         
         <div class="item_no col-md-8" style="margin-bottom: 5px;">GST</div>
         <div class="product_name col-md-2" style="margin-bottom: 5px;"><input type="text" class="form-control total" value="<?php echo $order[0]->gst; ?>" readonly ></div>

         <div class="item_no col-md-8" style="margin-bottom: 5px;">Total</div>
         <div class="product_name col-md-2" style="margin-bottom: 5px;"><input type="text" class="form-control total" value="<?php echo $order[0]->net_amount; ?>" readonly ></div>
       </div>
     </div>
   </div>

 </div>

<!-- <div class="row">
  <div class="col-sm-12">
    <div class="clearfix" style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding:10px 0px; margin-bottom:20px;">
      <div class="col col-md-4 pull-right">
        <input type="button" class="btn btn-danger" value="close">
        <a href="<?php echo base_url('order')?>" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</a>
      </div>
    </div>
  </div>
</div> -->


</form> 
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
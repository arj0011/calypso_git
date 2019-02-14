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
        <a href="<?php echo site_url('membershipPurchase');?>"><?php echo lang('membership_purchase');?></a>
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
                  <label class="control-label"><?=lang('user_name');?></label>

                  <input type="text" class="form-control order_date"  name="full_name" id="full_name" placeholder="<?=lang('user_name');?>" value="<?php echo $member->full_name; ?>" readonly>


                </div>
              </div>

              <div class="col-md-6">
               <div class="form-group">
                <label class="control-label"><?=lang('membership_type');?></label>

                <input type="text" class="form-control order_date"  name="membership_type" id="membership_type" value="<?php echo $member->membership_type; ?>" placeholder="<?=lang('membership_type');?>" readonly>


              </div>
            </div>

              <div class="col-md-6">
               <div class="form-group">
                <label class="control-label"><?=lang('current_wallet_balance');?></label>

                <input type="text" class="form-control order_date"  name="wallet_balance" id="wallet_balance" value="<?php echo $member->current_wallet_balance; ?>" placeholder="<?=lang('current_wallet_balance');?>" readonly>


              </div>
            </div>

          

          <div class="col-md-6">
           <div class="form-group">
            <label class="control-label"><?=lang('subscription_date');?></label>
            <input type="text" class="form-control order_email"  name="subscription_date" id="subscription_date" placeholder="<?=lang('subscription_date');?>" value="<?php echo $member->membership_subscription_date; ?>" readonly>
             <?php echo form_error('subscription_date'); ?>


          </div>
        </div>
        <div class="col-md-6">
         <div class="form-group">
          <label class="control-label"><?=lang('expiry_date');?></label>

          <input type="text" class="form-control order_phone"  name="expiry_date" id="expiry_date" placeholder="<?=lang('expiry_date');?>" value="<?php echo $member->subscription_expiry_date; ?>" readonly>
          <?php echo form_error('expiry_date'); ?>


        </div>
      </div>	
      <hr/>
  
 <div class="row">
  <div class="col-sm-12">
    <div class="clearfix" style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding:10px 0px; margin-bottom:20px;">
      <div class="col col-md-4 pull-right">
        <input type="button" class="btn btn-danger" value="close">
        <a href="<?php echo base_url('membershipPurchase')?>" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> Back</a>
      </div>
    </div>
  </div>
</div>
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
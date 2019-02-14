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
        <a href="<?php echo site_url('membershipPurchase');?>"><?php echo lang('purchase_management');?></a>
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
          <div class="btn-group " href="#">
            <a href="<?=base_url('/membershipPurchase/add'); ?>" class="btn btn-primary">
              <?php echo lang('add_membership_purchase');?>
              <i class="fa fa-plus"></i>
            </a>
          </div>
        </div>
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
            <form method="post" action="<?=base_url('membershipPurchase/add_membership');?>" class="form form_product" id="addForm">
              <div class="row">

               <div class="col-md-6">
                 <div class="form-group">
                  <label class="control-label"><?=lang('select_user');?></label>
                  <select class="" name="user_id" id="user_id" style="width:100%">
                    <option value=""><?php echo lang('select_user');?></option>
                    <?php if(!empty($users)){foreach($users as $user){?>
                    <option value="<?php echo $user->id;?>"><?php echo $user->full_name." (".$user->email.")";?></option>
                    <?php }}?>
                  </select>
                </div>
              </div>

              
              <div class="col-md-6">
               <div class="form-group">
                <label class="control-label"><?=lang('amount');?></label>
                <input type="text" class="form-control"  name="amount" id="amount" placeholder="<?=lang('amount');?>">
                <?php echo form_error('amount'); ?>
              </div>
            </div>  
            <hr/>

            <div class="row">
              <div class="col-sm-12">
                <div class="clearfix" style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding:10px 0px; margin-bottom:20px;">
                  <div class="col col-md-4 pull-right">
                    <input type="button" class="btn btn-danger" value="close">
                    <input type="submit" class="btn btn-primary" value="submit" id="submit">
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
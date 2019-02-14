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
          <div class="col-lg-12" style="overflow-x: auto">
            <table class="table table-bordered table-responsive" id="common_datatable_member">
              <thead>
                <tr>
                  <th><?php echo lang('serial_no');?></th>
                  <th><?php echo lang('user_name');?></th>
                  <th><?php echo lang('membership_type');?></th>
                  <th><?php echo lang('subscription_date');?></th>
                  <th><?php echo lang('expiry_date');?></th>
                 
                  <th><?php echo lang('action');?></th>
                </tr>
              </thead>
              <tbody>
                <?php 

                if (isset($list) && !empty($list)):
                  $rowCount = 0;
                foreach ($list as $rows):
                  $rowCount++;
                ?>
                <tr>
                 <td><?php echo $rowCount; ?></td>
                 <td><?php echo $rows->full_name; ?></td>
                 <td><?php echo $rows->membership_type; ?></td>
                 <td><?php if($rows->membership_subscription_date=='0000-00-00'){echo '';} else{echo convertDate($rows->membership_subscription_date);}?></td>
                 <td><?php if($rows->subscription_expiry_date=='0000-00-00'){echo '';} else{echo convertDate($rows->subscription_expiry_date);}?></td>
                 
                 <td>

                   <a href="<?php echo site_url('membershipPurchase/view_membership/'.encoding($rows->id));?>" class="on-default edit-row"><img width="20" src="<?php echo base_url().VIEW_ICON;?>" /></a>

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
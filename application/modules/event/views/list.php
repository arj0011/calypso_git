<link href="<?php echo base_url().CUSTOMCSS;?>" rel="stylesheet"/> 
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <a href="<?php echo site_url('event');?>"><?php echo lang('event');?></a>
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
            <a href="javascript:void(0)"  onclick="open_modal('event')" class="btn btn-primary">
              <?php echo lang('event');?>
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
            <table class="table table-bordered table-responsive" id="common_datatable_event">
              <thead>
                <tr>
                  <th><?php echo lang('serial_no');?></th>
                  <th><?php echo lang('event_redirect_url');?></th>
                  <th><?php echo lang('image');?></th>
                  <th><?php echo lang('status');?></th>
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
                 <td><?php echo $rows->redirect_url;?></td>
                 <td><img width="100" src="<?php if(!empty($rows->image)){echo base_Url().'uploads/event/'?><?php echo $rows->image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                  <td>

                  <?php if($rows->status == 0) {?>
                    <a href="javascript:void(0)" class="on-default edit-row" onclick="statusChange('event','id','<?php echo encoding($rows->id);?>','<?php echo $rows->status;?>')" title="Active Now"><img width="20" src="<?php echo base_url().INACTIVE_ICON;?>" /></a>
                    <?php } else { ?>
                    <a href="javascript:void(0)" class="on-default edit-row text-danger" onclick="statusChange('event','id','<?php echo encoding($rows->id);?>','<?php echo $rows->status;?>')" title="Inactive Now"><img width="20" src="<?php echo base_url().ACTIVE_ICON;?>" /></a>
                    <?php } ?>
                  </td>
                 <td>
                   <!-- <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('allacart','item_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a> -->

                   <a href="javascript:void(0)" onclick="delFn('<?php echo EVENT;?>','id','<?php echo encoding($rows->id); ?>','event','del')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>

                  
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
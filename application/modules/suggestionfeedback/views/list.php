<link href="<?php echo base_url().CUSTOMCSS;?>" rel="stylesheet"/> 
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <?php $uri = $this->uri->segment(2);?>
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <a href="<?php echo site_url('suggestionfeedback');?>">Suggestion & Feedback</a>
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
        <!-- <div class="ibox-title">
          <div class="btn-group " href="#">
            <a href="javascript:void(0)"  onclick="open_modal('allacart')" class="btn btn-primary">
              <?php echo lang('add_food');?>
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
          <div class="col-lg-12" style="overflow-x: auto">
            <table class="table table-bordered table-responsive" id="common_datatable_suggsfeedback">
              <thead>
                <tr>
                  <th><?php echo lang('serial_no');?></th>
                  <th>User</th>
                  <?php
                    if($uri == 'suggestion'){
                  ?>
                  <th>Title</th>
                  <th>Suggestion</th>
                <?php }else{ ?>
                  <th>Feedback</th>
                  <?php } ?>
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
                 <td><?php echo $rows->full_name;?></td>
                  <?php
                    if($uri == 'suggestion'){
                  ?>
                  <td><?php echo $rows->title;?></td>
                  <td><?php echo $rows->suggestion;?></td>
                  <?php }else{ ?>
                  <td><?php echo $rows->feedback;?></td>
                  <?php } ?>
                  <td>
                   <?php if($rows->status == 0) {?>
                    <a href="javascript:void(0)" class="on-default edit-row" onclick="statusChange('<?php echo SUGGESTION_FEEDBACK;?>','id','<?php echo encoding($rows->id);?>','<?php echo $rows->status;?>')" title="Active Now"><img width="20" src="<?php echo base_url().INACTIVE_ICON;?>" /></a>
                    <?php } else { ?>
                    <a href="javascript:void(0)" class="on-default edit-row text-danger" onclick="statusChange('<?php echo SUGGESTION_FEEDBACK;?>','id','<?php echo encoding($rows->id);?>','<?php echo $rows->status;?>')" title="Inactive Now"><img width="20" src="<?php echo base_url().ACTIVE_ICON;?>" /></a>
                    <?php } ?> 
                  </td>
                  <td>
                   
                   <a href="javascript:void(0)" onclick="delFn('<?php echo SUGGESTION_FEEDBACK;?>','id','<?php echo encoding($rows->id); ?>','suggestionfeedback','del')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>

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
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
        <a href="<?php echo site_url('offer');?>"><?php echo lang('offer_management');?></a>
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
            <a href="javascript:void(0)"  onclick="open_modal('offer')" class="btn btn-primary">
              <?php echo lang('add_offer');?>
              <i class="fa fa-plus"></i>
            </a>
          </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
            <form id="filterform" action="<?php echo base_url('offer');?>" method="post">
              <div class="col-lg-3">
              <!-- <label>Status</label> -->
              <select id="statusfilter" name="statusfilter" class="form-control col-sm-3">
                  <option value="">Select</option>
                  <option value="1" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 1) ? 'selected' : '';?>>Active</option>
                  <option value="2" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 2) ? 'selected' : '';?>>Deactive</option>
              </select>
              </div>
              <div class="col-lg-9">
              <div class="row">
              <div class="col-sm-4">
              <input type="text" name="start_date" id="start_date" class="form-control" placeholder="Start Date" value="<?php echo (isset($start_date) && $start_date != '') ? $start_date : '';?>">
              </div>
              <div class="col-sm-4">
              <input type="text" name="end_date" id="end_date" class="form-control" placeholder="End Date" value="<?php echo (isset($end_date) && $end_date != '') ? $end_date : '';?>">
              </div>
              <div class="col-sm-4">
              <button type="submit" class="btn btn-primary">Submit</button>
              <input type="submit" name="reset" value="Reset" class="btn btn-danger" onclick="resetForm(this.form)" />
              </div>
              </div>
              </div>
          </form>
          </div>
          </div>
          <br/>

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
            <table class="table table-bordered table-responsive" id="common_datatable">
              <thead>
                <tr>
                  <th><?php echo lang('serial_no');?></th>
                  <th><?php echo lang('offer_text');?></th>
                  <th><?php echo lang('type');?></th>
                  <th><?php echo lang('show');?></th>
                  <th>Date</th>
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

                 <td><?php echo $rows->offer_text;?></td>
                 <td><?php echo ucfirst($rows->type);?></td>
                 <td><?php echo ($rows->show_front == 1) ? 'Yes' : 'No';?></td>
                 <td><?php echo convertDate($rows->created_date);?></td>
                 <td><img width="100" src="<?php if(!empty($rows->offer_image)){echo base_Url().'uploads/offer/'?><?php echo $rows->offer_image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                 <td>
                 <?php if($rows->status == 0) {?>
                    <a href="javascript:void(0)" class="on-default edit-row" onclick="statusChange('offers','id','<?php echo encoding($rows->id);?>','<?php echo $rows->status;?>')" title="Active Now"><img width="20" src="<?php echo base_url().INACTIVE_ICON;?>" /></a>
                    <?php } else { ?>
                    <a href="javascript:void(0)" class="on-default edit-row text-danger" onclick="statusChange('offers','id','<?php echo encoding($rows->id);?>','<?php echo $rows->status;?>')" title="Inactive Now"><img width="20" src="<?php echo base_url().ACTIVE_ICON;?>" /></a>
                    <?php } ?>
                  </td> 
                 <td>
                  <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('offer','offer_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                  <a href="javascript:void(0)" onclick="deleteFn('<?php echo OFFERS;?>','id','<?php echo encoding($rows->id); ?>','offer')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
                  
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
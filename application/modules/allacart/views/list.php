<link href="<?php echo base_url().CUSTOMCSS;?>" rel="stylesheet"/> 
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <a href="<?php echo site_url('allacart');?>"><?php echo lang('food_items_management');?></a>
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
            <a href="javascript:void(0)"  onclick="open_modal('allacart')" class="btn btn-primary">
              <?php echo lang('add_food');?>
              <i class="fa fa-plus"></i>
            </a>
          </div>
        </div>

         <div class="row">
            <div class="col-lg-12">
            <form id="filterform" action="<?php echo base_url('allacart');?>" method="post">
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
              <input type="text" name="start_date" id="startdate" class="form-control" placeholder="Start Date" value="<?php echo (isset($start_date) && $start_date != '') ? $start_date : '';?>">
              </div>
              <div class="col-sm-4">
              <input type="text" name="end_date" id="enddate" class="form-control" placeholder="End Date" value="<?php echo (isset($end_date) && $end_date != '') ? $end_date : '';?>">
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
            <table class="table table-bordered table-responsive" id="common_datatable_product">
              <thead>
                <tr>
                  <th><?php echo lang('serial_no');?></th>
                  <th><?php echo lang('item_name');?></th>
                  <th><?php echo lang('food_category');?></th>
                  <th><?php echo lang('price');?> (&#8377;)</th>
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

                 <td><?php echo $rows->item_name;?></td>
                 <td><?php echo $rows->category_name;?></td>
                 <td><?php echo $rows->price;?></td>
                 <td><?php echo convertDate($rows->created_date);?></td>
                 <td><img width="100" src="<?php if(!empty($rows->image)){echo base_Url().'/uploads/allacart/'?><?php echo $rows->image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                  <td>
                    <?php if($rows->status == 0) {?>
                    <a href="javascript:void(0)" class="on-default edit-row" onclick="statusFun('<?php echo $rows->id?>','<?php echo $rows->status;?>')" title="Active Now"><img width="20" src="<?php echo base_url().INACTIVE_ICON;?>" /></a>
                    <?php } else { ?>
                    <a href="javascript:void(0)" class="on-default edit-row text-danger" onclick="statusFun('<?php echo $rows->id?>','<?php echo $rows->status;?>')" title="Inactive Now"><img width="20" src="<?php echo base_url().ACTIVE_ICON;?>" /></a>
                    <?php } ?>  
                  </td>
                 <td>
                   <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('allacart','item_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                   <!-- <a href="javascript:void(0)" onclick="delFn('<?php echo ALLACART;?>','id','<?php echo encoding($rows->id); ?>','allacart','del')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a> -->
                    
                    <?php if($rows->status == 1): ?>
                    <a href="<?php echo site_url('offerprice/setpricevariation')?>/allacart/<?php echo encoding($rows->id)?>" class="on-default offrprize"><img width="20"/>offer price</a>
                    <?php endif; ?>
                  
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
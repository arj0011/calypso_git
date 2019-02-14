<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
            </li>
            <li>
                <a href="<?php echo site_url('users');?>"><?php echo lang('user');?></a>
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
                        <a href="javascript:void(0)"  onclick="open_modal('users')" class="<?php echo THEME_BUTTON;?>">
                            <?php echo lang('add_user');?>
                        <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div> -->

                <div class="ibox-title">
                    <div class="form-group " href="#">
                        <form id="filterform" action="<?php echo base_url('users');?>" method="post">
                        <div class="row">
                            <div class="col-sm-4">
                            <label>Status</label>
                            <select id="statusfilter" name="statusfilter" class="form-control col-sm-3">
                                <option value="">Select</option>
                                <!-- <option value="1" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 1) ? 'selected' : '';?>>Active</option>
                                <option value="0" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 0) ? 'selected' : '';?>>Deactive</option> -->
                                
                                <option value="1" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 1) ? 'selected' : '';?>>Active</option>
                                <option value="2" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 2) ? 'selected' : '';?>>Deactive</option>
                            </select>
                            </div>
                            <div class="col-sm-4">
                            <label></label>

                            <input type="submit" name="reset" value="Reset" class="btn btn-danger" onclick="resetForm(this.form)" style="margin-top: 23px;margin-bottom:  0;" >
                            </div>
                            </div>
                        </form>
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
                    <table class="table table-bordered table-responsive" id="common_datatable_users">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('user_name');?></th>
                                <th><?php echo lang('user_email');?></th>
                                <th>Date of Birth</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Anniversary</th>
                                <th>Occupation</th>
                                <th>Address</th>
                                <th>Near Landmark</th>
                                <th>Register</th>
                                 <th><?php echo lang('profile_image');?></th>
                                 <th><?php echo lang('status');?></th>
                                <th style="width: 12%"><?php echo lang('action');?></th>
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
                            <td><?php echo $rows->email?></td>
                            <td><?php echo ($rows->date_of_birth == '') ? '' : convertDate($rows->date_of_birth)?></td>
                            <td><?php echo $rows->phone?></td>
                            <td><?php echo ($rows->gender == 1) ? 'Male' : 'Female';?></td>
                            <td><?php echo ($rows->anniversary == '') ? '' : convertDate($rows->anniversary)?></td>
                            <td>
                                <?php 
                                    
                                    if(strlen($rows->occupation) > 0){
                                       echo (strlen($rows->occupation) > 30) ? substr($rows->occupation,0,30) : $rows->occupation;      
                                    }else{
                                        echo '';    
                                    }

                                ?>
                                
                            </td>
                            <td>
                            <?php 
                            if(strlen($rows->address) > 0){
                                echo (strlen($rows->address) > 30) ? substr($rows->address,0,30) : $rows->address;      
                            }else{
                                echo '';    
                            }
                            ?>    
                            </td>
                            <td>
                            <?php 
                            
                            if(strlen($rows->near_landmark) > 0){
                                echo (strlen($rows->near_landmark) > 30) ? substr($rows->near_landmark,0,30) : $rows->near_landmark;      
                            }else{
                                echo '';    
                            }
                            ?>
                                
                            </td>
                             <td><?php echo ($rows->created_on == '') ? '' : convertDate($rows->created_on)?></td>
                           
                            <td><img width="100" src="<?php if(!empty($rows->user_image)){echo base_Url()?><?php echo $rows->user_image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                            
                            <td><?php if($rows->active == 1) echo '<p class="text-success">'.lang('active').'</p>'; else echo '<p  class="text-danger">'.lang('deactive').'</p>';?></td>
<!--                            <td><?php //echo date('d F Y',$rows->created_on);?></td>-->
                            <td class="actions">
                                <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('<?php echo USERS;?>','user_edit','<?php echo encoding($rows->id); ?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                            
                            <?php if($rows->id != 1){if($rows->active == 1) {?>
                            <a href="javascript:void(0)" class="on-default edit-row" onclick="statusFn('<?php echo USERS;?>','id','<?php echo encoding($rows->id);?>','<?php echo $rows->active;?>')" title="Inactive Now"><img width="20" src="<?php echo base_url().ACTIVE_ICON;?>" /></a>
                            <?php } else { ?>
                            <a href="javascript:void(0)" class="on-default edit-row text-danger" onclick="statusFn('<?php echo USERS;?>','id','<?php echo encoding($rows->id); ?>','<?php echo $rows->active;?>')" title="Active Now"><img width="20" src="<?php echo base_url().INACTIVE_ICON;?>" /></a>
                            <?php } ?>
                            <!-- <a href="javascript:void(0)" onclick="deleteFn('<?php echo USERS;?>','id','<?php echo encoding($rows->id); ?>','users')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a> -->
                            
                            
                            </td>
                            </tr>
                            <?php }endforeach; endif;?>
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
                <div id="form-modal-box"></div>
        </div>
    </div>
</div>
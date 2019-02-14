<div class="col-lg-12" id="<?php echo $count;?>">
<div class="form-group">
 <label class="col-sm-2 control-label"><?php echo lang('event_redirect_url'); ?></label>
 
 <div class="col-sm-4"><input type="text" name="url[]" id="" class="form-control" placeholder="<?php echo lang('event_redirect_url'); ?>" value="<?php echo getConfig('event_redirect_url'); ?>">
  </div>

  <div class="col-md-4">
    <div class="profile_content edit_img">
      <div class="file_btn file_btn_logo">
        <input type="file"  class="input_img2" id="" name="user_image[]" style="display: inline-block;">
        <span class="glyphicon input_img2 logo_btn" style="display: block;">
        <div id="show_company_img"></div>
        <span class="ceo_logo">
            <img src="<?php echo base_url().'backend_asset/images/default.jpg';?>">
        </span><i class="fa fa-camera"></i></span>
        <img class="show_company_img2" style="display:none" alt="img" src="<?php echo base_url() ?>/backend_asset/images/logo.png">
        <span style="display:none" class="fa fa-close remove_img"></span>
      </div>
    </div>
    <div class="ceo_file_error file_error text-danger"></div>
  </div>
  <div class="col-md-2">
    <button type="button" data-value="<?php echo $count;?>" class="btn btn-danger RemoveRow">-</button>
  </div>
</div>
</div> 
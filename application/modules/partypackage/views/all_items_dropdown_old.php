<div class="col-md-12" id="firstdiv_<?php echo $count;?>">
    <div class="form-group">
        <div class="col-md-8">
            <select class="form-control category_cls" name="category[]" id="category_<?php echo $count;?>">
            <option value="">Select</option>
            <?php if(!empty($category)){foreach($category as $cat){?>    
                <option value="<?php echo $cat->id;?>"><?php echo $cat->category_name;?></option>
            <?php }}?>
            </select> 
        </div>
        <div class="col-md-4">
            <input type="text" name="limit[]" id="limit_<?php echo $count;?>" class="form-control" placeholder="Limit" />
        </div>
    </div>
</div>
<div class="col-md-12" id="seconddiv_<?php echo $count;?>">
    <div class="form-group">
        <div class="col-md-8 cat_item_drpdwn" id="itemDivId_<?php echo $count;?>">
            
        </div>
        <div class="col-md-2">
            <a href="javascript:void(0);" id="rowcount_<?php echo $count;?>" add_count="<?php echo $count; ?>" class="btn btn-primary addmorerow">+</a> 
        </div>
        <div class="col-md-2">               
            <a href="javascript:void(0);" id="rwcount_<?php echo $count;?>" class="btn btn-primary delrow">-</a>
        </div>
    </div>
</div>

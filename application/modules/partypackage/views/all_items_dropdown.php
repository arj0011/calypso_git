<!-- <div class="" id="firstdiv_<?php echo $count;?>"> -->
    <!-- <div class="form-group">
        <div class="col-md-8">
            <select class="category_cls" name="category[]" id="category_<?php echo $count;?>">
            <option value="">Select</option>
            <?php if(!empty($category)){foreach($category as $cat){?>    
                <option value="<?php echo $cat->id;?>"><?php echo $cat->category_name;?></option>
            <?php }}?>
            </select> 
        </div>
        <div class="col-md-4">
            <input type="text" name="limit[]" id="limit_<?php echo $count;?>" class="form-control" placeholder="Limit" />
        </div>
    </div> -->
<!-- </div> -->
<div id="parent_<?php echo $count;?>"> 

    <div class="col-md-6">
        <div class="form-group">
            <select class="form-control category_cls" id="category_<?php echo $count;?>" name="category[]">
                <option value="">Select</option>
                <?php if(!empty($category)){foreach($category as $cat){?>    
                <option value="<?php echo $cat->id;?>"><?php echo $cat->category_name;?></option>
                <?php }}?>
            </select> 
      </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <input type="text" id="limit_<?php echo $count;?>" name="limit[]" class="form-control" placeholder="Limit" />
        </div>
    </div>



    <div class="">
        <div class="form-group">
            <div class="col-md-6 cat_item_drpdwn" id="itemDivId_<?php echo $count;?>">
                
            </div>
            <div class="col-md-6" id="delbtn_<?php echo $count;?>">
                <div class="form-group">
                    <a href="javascript:void(0);" id="rowcount_<?php echo $count;?>" class="btn btn-primary delrow">-</a>
                </div>
            </div>
            
        </div>
    </div>

</div>



<!-- <div class="" id="seconddiv_<?php echo $count;?>">
    <div class="form-group">
        <div class="col-md-8 cat_item_drpdwn" id="itemDivId_<?php echo $count;?>">
            
        </div>
        <div class="col-md-2">               
            <a href="javascript:void(0);" id="rwcount_<?php echo $count;?>" class="btn btn-primary delrow">-</a>
        </div>
    </div>
</div> -->

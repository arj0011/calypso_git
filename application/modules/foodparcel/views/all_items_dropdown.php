
<div id="parent_<?php echo $count;?>"> 

    <div class="col-md-5">
        <div class="form-group">
            <select class="form-control category_cls" id="category_<?php echo $count;?>" name="item[]">
                <option value="">Select</option>
                <?php if(!empty($pack_items)){foreach($pack_items as $item){?>    
                <option value="<?php echo $item->id;?>"><?php echo $item->item_name;?></option>
                <?php }}?>
            </select> 
      </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <input type="text" id="limit_<?php echo $count;?>" name="limit[]" class="form-control" placeholder="Limit" />
        </div>
    </div>



    <div class="col-md-2">
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



<select class="cat_item_cls" id="id_<?php echo $count;?>" name="itemids[<?php echo $categoryID;?>][]" multiple="">
<?php foreach ($cat_items as $itemVal) {?>
<option value="<?php echo $itemVal->id;?>"><?php echo $itemVal->item_name;?></option>
<?php } ?>
</select> 
<script type="text/javascript">
  $('#id_'+<?php echo $count;?>).select2();
</script>

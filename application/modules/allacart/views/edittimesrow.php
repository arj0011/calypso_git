<!-- This page append in fulldiv(dayaccordian.php) -->
<div class="clearfix"></div>
<div class="timeprice_<?php echo $day;?>_<?php echo $button_count;?>">
    <div class="col-md-3">
      <input type="text" placeholder="Start time" required id="st_<?php echo $day;?>_<?php echo $button_count;?>"  name="<?php echo $day;?>_start_time[]" class="form-control clockpicker" />  
    </div>
    <div class="col-md-3">    
      <input type="text" placeholder="End time" required id="et_<?php echo $day;?>_<?php echo $button_count;?>" name="<?php echo $day;?>_end_time[]" class="form-control clockpicker" />
    </div>
    <div class="col-md-3">    
      <input type="text" placeholder="Price" required data-rule-number="true" id="pr_<?php echo $day;?>_<?php echo $button_count;?>" name="<?php echo $day;?>_price[]" class="form-control" />
    </div>
    <div class="col-md-3">    
      <button type="button" data-buttonday="<?php echo $day;?>" data-button-count="<?php echo $button_count;?>" data-del-button-count="<?php echo $button_count;?>" data-value="" class="btn btn-danger RemovePrice">-</button>
    </div>
</div>

<script type="text/javascript">
    $('.clockpicker').clockpicker({
      twelvehour: false,
      autoclose: true
    });
    $(".clockpicker").keydown(false);


    $(document).on('click','.EditPrice',function(){
      var btn = $(this);
      var btncount = $(this).data('button-count');
      // if(btncount != '' && btncount != undefined){
        var buttonday = $(this).data('buttonday');    
        var timesrow = $('.timeprice_'+buttonday+'_'+btncount);
        var start_time = $('#st_'+buttonday+'_'+btncount).val();
        var end_time = $('#et_'+buttonday+'_'+btncount).val();
        var price = $('#pr_'+buttonday+'_'+btncount).val();
        if((start_time != '' && start_time != null && start_time != undefined) && (end_time != '' && end_time != null && end_time != undefined) && (price != '' && price != null && price != undefined)){
            var btcount = Number(btncount) + 1;
            btn.data('button-count',btcount); 
            $.ajax({
                method: "POST",
                url: '<?php echo base_url('allacart/edittimesrow')?>',
                data: {day:buttonday,button_count:btncount},
                success: function (response) {
                    timesrow.append(response);
                       
                },
                error: function (error, ror, r) {
                    bootbox.alert(error);
                },
            });
        }
      // }
    });

    //Remove Row
    $(document).on('click','.RemovePrice',function(){
      var btn = $(this);
      var btncount = $(this).data('button-count');
      
      if(btncount != '' && btncount != undefined){
        var buttonday = $(this).data('buttonday');    
        var timesrow = $('.timeprice_'+buttonday+'_'+btncount);
        timesrow.remove();
      }
    });

</script>
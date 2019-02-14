
<div class="timeprice_<?php echo $day;?>_<?php echo $button_count;?>" style="clear:left;">
    <div class="col-md-3">
      <input type="text" placeholder="Start time" required id="st_<?php echo $day;?>_<?php echo $button_count;?>"  name="<?php echo $day;?>_start_time[]" class="form-control stimepicker" />  
    </div>
    <div class="col-md-3">    
      <input type="text" placeholder="End time" required id="et_<?php echo $day;?>_<?php echo $button_count;?>" name="<?php echo $day;?>_end_time[]" class="form-control etimepicker" />
    </div>
    <div class="col-md-3">    
      <input type="text" placeholder="Price" required id="pr_<?php echo $day;?>_<?php echo $button_count;?>" name="<?php echo $day;?>_price[]" class="form-control" />
    </div>
    <div class="col-md-3">    
      <button type="button" data-buttonday="<?php echo $day;?>" data-button-count="<?php echo $button_count;?>" data-del-button-count="<?php echo $button_count;?>" class="btn btn-danger RemovePrice">-</button>
    </div>
    <script type="text/javascript">
    /*$('.clockpicker').clockpicker({
      twelvehour: false,
      autoclose: true
    });
    $(".clockpicker").keydown(false);*/
    $(document).ready(function(){
      $('.stimepicker').timepicker({timeFormat: 'HH:mm:ss',interval: 15,
          change: function(time) {
              var starttime = $(this).timepicker().format(time);
              var starttimearr = $(this).attr('id').split('_');
              var day = starttimearr[1];
              var count = starttimearr[2];
              var endtime = $('#et_'+day+'_'+count).val();

              var timefrom = new Date();
              stemp = starttime.split(":");
              timefrom.setHours((parseInt(stemp[0]) - 1 + 24) % 24);
              timefrom.setMinutes(parseInt(stemp[1]));

              var timeto = new Date();
              etemp = endtime.split(":");
              timeto.setHours((parseInt(etemp[0]) - 1 + 24) % 24);
              timeto.setMinutes(parseInt(etemp[1]));

              if (timeto <= timefrom){
                bootbox.alert('Start time should be smaller');
                $(this).val('');
                $(this).focus();
              } 
                  
          }
      });
    
    $('.etimepicker').timepicker({timeFormat: 'HH:mm:ss',interval: 15,
        change: function(time) {
            var endtime = $(this).timepicker().format(time);
            var endtimearr = $(this).attr('id').split('_');
            var day = endtimearr[1];
            var count = endtimearr[2];
            var starttime = $('#st_'+day+'_'+count).val();
            

            var timefrom = new Date();
            stemp = starttime.split(":");
            timefrom.setHours((parseInt(stemp[0]) - 1 + 24) % 24);
            timefrom.setMinutes(parseInt(stemp[1]));

            var timeto = new Date();
            etemp = endtime.split(":");
            timeto.setHours((parseInt(etemp[0]) - 1 + 24) % 24);
            timeto.setMinutes(parseInt(etemp[1]));

            if (timeto <= timefrom){
              bootbox.alert('End time should be greater');
              $(this).val('');
              $(this).focus();
            } 
                
        }
    });
});

    </script>
</div>

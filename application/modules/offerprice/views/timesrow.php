
<div class="timeprice_<?php echo $day;?>_<?php echo $button_count;?>" style="clear:left;">
    <div class="col-md-3">
      <input type="text" style="margin: 5px 0;" placeholder="Start time" required id="st_<?php echo $day;?>_<?php echo $button_count;?>"  name="<?php echo $day;?>_start_time[]" class="form-control stimepicker" />  
    </div>
    <div class="col-md-3">    
      <input type="text" placeholder="End time" required id="et_<?php echo $day;?>_<?php echo $button_count;?>" style="margin:5px 0;" name="<?php echo $day;?>_end_time[]" class="form-control etimepicker" />
    </div>
    <div class="col-md-3">
        
      <input type="number" placeholder="Price" required id="pr_<?php echo $day;?>_<?php echo $button_count;?>" onblur="allnumeric(this);" style="margin: 5px 0;" name="<?php echo $day;?>_price[]" class="form-control" />
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
      
      $(".stimepicker").keydown(false);
      $(".etimepicker").keydown(false);


      // $('.stimepicker').timepicker({timeFormat: 'HH:mm:ss',interval: 15,
      //     change: function(time) {
      //         var starttime = $(this).timepicker().format(time);
      //         var starttimearr = $(this).attr('id').split('_');
      //         var day = starttimearr[1];
      //         var count = starttimearr[2];
      //         var endtime = $('#et_'+day+'_'+count).val();

      //         var timefrom = new Date();
      //         stemp = starttime.split(":");
      //         timefrom.setHours((parseInt(stemp[0]) - 1 + 24) % 24);
      //         timefrom.setMinutes(parseInt(stemp[1]));

      //         var timeto = new Date();
      //         etemp = endtime.split(":");
      //         timeto.setHours((parseInt(etemp[0]) - 1 + 24) % 24);
      //         timeto.setMinutes(parseInt(etemp[1]));

      //         if (timeto <= timefrom){
      //           bootbox.alert('Start time should be smaller');
      //           $(this).val('');
      //           $(this).focus();
      //         } 
                  
      //     }
      // });
    
    // $('.etimepicker').timepicker({timeFormat: 'HH:mm:ss',interval: 15,
    //     change: function(time) {
    //         var endtime = $(this).timepicker().format(time);
    //         var endtimearr = $(this).attr('id').split('_');
    //         var day = endtimearr[1];
    //         var count = endtimearr[2];
    //         var starttime = $('#st_'+day+'_'+count).val();
            

    //         var timefrom = new Date();
    //         stemp = starttime.split(":");
    //         timefrom.setHours((parseInt(stemp[0]) - 1 + 24) % 24);
    //         timefrom.setMinutes(parseInt(stemp[1]));

    //         var timeto = new Date();
    //         etemp = endtime.split(":");
    //         timeto.setHours((parseInt(etemp[0]) - 1 + 24) % 24);
    //         timeto.setMinutes(parseInt(etemp[1]));

    //         if (timeto <= timefrom){
    //           bootbox.alert('End time should be greater');
    //           $(this).val('');
    //           $(this).focus();
    //         } 
                
    //     }
    // });


    $('.stimepicker').timepicker({timeFormat: 'HH:mm:ss',interval: 15,
        change: function(time) {
            var starttime = $(this).timepicker().format(time);
            var starttimearr = $(this).attr('id').split('_');
            var day = starttimearr[1];
            var count = starttimearr[2];
            var endtime = $('#et_'+day+'_'+count).val();
            
            var stime = starttime.split(":");
            var startHour = stime[0];
            var startMinute = stime[1];
            var startSecond = stime[2];
            //Create date object and set the time to that
            var startTimeObject = new Date();
            startTimeObject.setHours(startHour, startMinute, startSecond);
            
            var etemp = endtime.split(":");
            var endHour = etime[0];
            var endMinute = etime[1];
            var endSecond = etime[2];

            //Create date object and set the time to that
            var endTimeObject = new Date(startTimeObject);
            endTimeObject.setHours(endHour, endMinute, endSecond);

            //Now we are ready to compare both the dates
            if(startTimeObject >= endTimeObject){
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
            
            /*start time hours minutes seconds*/
            var stime = starttime.split(":");
            var startHour = stime[0];
            var startMinute = stime[1];
            var startSecond = stime[2];

            //Create date object and set the time to that
            var startTimeObject = new Date();
            startTimeObject.setHours(startHour, startMinute, startSecond);

            var etime = endtime.split(":");
            var endHour = etime[0];
            var endMinute = etime[1];
            var endSecond = etime[2];

            //Create date object and set the time to that
            var endTimeObject = new Date(startTimeObject);
            endTimeObject.setHours(endHour, endMinute, endSecond);


            //Now we are ready to compare both the dates
            if(startTimeObject >= endTimeObject){
              bootbox.alert('End time should be greater');
              $(this).val('');
              $(this).focus();
            }
        }
    });




    
});

    </script>
</div>

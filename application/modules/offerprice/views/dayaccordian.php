<div class="col-lg-12">
  <div class="panel-group" id="accordion">

  <?php 
    $i = 0;
    $j = 1;
    // foreach($datedaysArr as $datedays):
    foreach($week as $day):

      if(in_array($day, $daysArr)){
  ?>
  <div class="panel panel-default">
    <div class="panel-heading">
    <h4 class="panel-title">
      <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $day;?>"><?php echo $day;?></a>
    </h4>
    </div>
    <div id="<?php echo $day;?>" class="panel-collapse collapse">
      <div class="panel-body">
          <input type="hidden" name="day[]" value="<?php echo $day;?>" />
          <div class="fulldiv_<?php echo $day;?>">
            
            <div class="timeprice_<?php echo $day;?>_0">
              <div class="col-md-3">
                <input type="text" placeholder="Start time" required id="st_<?php echo $day;?>_0" name="<?php echo $day;?>_start_time[]" class="form-control stimepicker" />  
              </div>
              <div class="col-md-3">    
                <input type="text" placeholder="End time" required id="et_<?php echo $day;?>_0" name="<?php echo $day;?>_end_time[]" class="form-control etimepicker" />
              </div>
              <div class="col-md-3">    
                <input type="number" placeholder="Price" required data-rule-number="true" id="pr_<?php echo $day;?>_0" name="<?php echo $day;?>_price[]" class="form-control" onblur="allnumeric(this);" />
              </div>
            </div>

            

          </div>
          
          <div class="col-md-3">    
            <button type="button" class="btn btn-primary AddPrice" data-button-count="" value="0" data-buttonday="<?php echo $day;?>">+</button>  
          </div>

        <!-- </form> -->

      </div>
    </div>
  </div>
  <?php }$i++;$j++;endforeach;?>
  </div>
</div>
<script type="text/javascript">
/*    $('.clockpicker').clockpicker({
      twelvehour: false,
      autoclose: true
    });
$(".clockpicker").keydown(false);*/

$(document).ready(function(){
    $(".stimepicker").keydown(false);
    $(".etimepicker").keydown(false);

    
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
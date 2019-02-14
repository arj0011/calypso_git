<script>
 $.validator.addMethod('positiveNumber',function (value) { 
        return Number(value) >= 0;
}, 'Enter positive number.');

$.validator.addMethod('strikeprice',function (value, element) { 
        var price = $('#price').val();
        return this.optional(element) || Number(value) > Number(price);
}, 'Strike price cant be less than price.'); 


/*Start*/
/*jQuery('body').on('click', '#offerpriceSubmit', function () {
   
    var form_name = this.form.id;
    var start_date  = $('#start_date').val();
    var end_date    = $('#end_date').val();

    $("#"+form_name).validate({
        ignore: "",
        rules: {
            start_date: "required",
            end_date: "required",
            "Wednesday_start_time[]":"required"

        },
        messages: {
            start_date: '<?php echo lang('start_date_req');?>',
            end_date: '<?php echo lang('end_date_req');?>',
            "Wednesday_start_time[]":"Please select start time"
        },
        submitHandler: function (form) {
        
            $(form).ajaxSubmit({
            });
        }
    });
});*/
/*End*/

    jQuery('body').on('click', '#submit', function () {
        
        var form_name= this.form.id;
        if(form_name=='[object HTMLInputElement]')
            form_name='addFormAjax';
        $("#"+form_name).validate({
            errorPlacement: function (error, element) {
                if (element.attr("type") == "file") {
                    error.insertBefore($('.profile_content'));
                }else {
                    $(error).insertAfter(element);
                }
            },
            rules: {
                category_id: "required", 
                food_name:"required", 
                price:{ 
                  required: true,
                  number: true,
                  positiveNumber:true
                }, 
                strike_price:{ 
                  required: true,
                  number: true,
                  positiveNumber:true,
                  strikeprice:true
                }
                // image:"required" 
            },
            messages: {
            },
            submitHandler: function (form) {
                jQuery(form).ajaxSubmit({
                });
            }
        });

    });    


jQuery('body').on('change', '.input_img2', function () {

    var file_name = jQuery(this).val();
    var fileObj = this.files[0];
    var calculatedSize = fileObj.size / (1024 * 1024);
    var split_extension = file_name.split(".");
    var ext = ["jpg", "gif", "png", "jpeg"];
    if (jQuery.inArray(split_extension[1].toLowerCase(), ext) == -1)
    {
        $(this).val(fileObj.value = null);
        //showToaster('error',"You Can Upload Only .jpg, gif, png, jpeg  files !");
        $('.ceo_file_error').html('<?php echo lang('image_upload_error'); ?>');
        return false;
    }
    if (calculatedSize > 1)
    {
        $(this).val(fileObj.value = null);
        //showToaster('error',"File size should be less than 1 MB !");
        $('.ceo_file_error').html(' 1MB');
        return false;
    }
    if (jQuery.inArray(split_extension[1].toLowerCase(), ext) != -1 && calculatedSize < 10)
    {
        $('.ceo_file_error').html('');
        readURL(this);
    }
});

function readURL(input) {
    var cur = input;
    if (cur.files && cur.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(cur).hide();
            $(cur).next('span:first').hide();
            $(cur).next().next('img').attr('src', e.target.result);
            $(cur).next().next('img').css("display", "block");
            $(cur).next().next().next('span').attr('style', "");
        }
        reader.readAsDataURL(input.files[0]);
    }
}

jQuery('body').on('click', '.remove_img', function () {
    var img = jQuery(this).prev()[0];
    var span = jQuery(this).prev().prev()[0];
    var input = jQuery(this).prev().prev().prev()[0];
    jQuery(img).attr('src', '').css("display", "none");
    jQuery(span).css("display", "block");
    jQuery(input).css("display", "inline-block");
    jQuery(this).css("display", "none");
    jQuery(".image_hide").css("display", "block");
    jQuery("#news_image").val("");
});

       
function show_message(msg) {
        var Base64 = {_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
                var t = "";
                var n, r, i, s, o, u, a;
                var f = 0;
                e = Base64._utf8_encode(e);
                while (f < e.length) {
                    n = e.charCodeAt(f++);
                    r = e.charCodeAt(f++);
                    i = e.charCodeAt(f++);
                    s = n >> 2;
                    o = (n & 3) << 4 | r >> 4;
                    u = (r & 15) << 2 | i >> 6;
                    a = i & 63;
                    if (isNaN(r)) {
                        u = a = 64
                    } else if (isNaN(i)) {
                        a = 64
                    }
                    t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
                }
                return t
            }, decode: function (e) {
                var t = "";
                var n, r, i;
                var s, o, u, a;
                var f = 0;
                e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                while (f < e.length) {
                    s = this._keyStr.indexOf(e.charAt(f++));
                    o = this._keyStr.indexOf(e.charAt(f++));
                    u = this._keyStr.indexOf(e.charAt(f++));
                    a = this._keyStr.indexOf(e.charAt(f++));
                    n = s << 2 | o >> 4;
                    r = (o & 15) << 4 | u >> 2;
                    i = (u & 3) << 6 | a;
                    t = t + String.fromCharCode(n);
                    if (u != 64) {
                        t = t + String.fromCharCode(r)
                    }
                    if (a != 64) {
                        t = t + String.fromCharCode(i)
                    }
                }
                t = Base64._utf8_decode(t);
                return t
            }, _utf8_encode: function (e) {
                e = e.replace(/\r\n/g, "\n");
                var t = "";
                for (var n = 0; n < e.length; n++) {
                    var r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r)
                    } else if (r > 127 && r < 2048) {
                        t += String.fromCharCode(r >> 6 | 192);
                        t += String.fromCharCode(r & 63 | 128)
                    } else {
                        t += String.fromCharCode(r >> 12 | 224);
                        t += String.fromCharCode(r >> 6 & 63 | 128);
                        t += String.fromCharCode(r & 63 | 128)
                    }
                }
                return t
            }, _utf8_decode: function (e) {
                var t = "";
                var n = 0;
                var r = c1 = c2 = 0;
                while (n < e.length) {
                    r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r);
                        n++
                    } else if (r > 191 && r < 224) {
                        c2 = e.charCodeAt(n + 1);
                        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                        n += 2
                    } else {
                        c2 = e.charCodeAt(n + 1);
                        c3 = e.charCodeAt(n + 2);
                        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                        n += 3
                    }
                }
                return t
            }}

        msg = Base64.decode(msg);
        $('#message_container').text(msg);
        $('#message_div').show();
}

function close_message(){
    $('#message_container').text('');
    $('#message_div').hide();
}

$('#common_datatable_product').dataTable({
    order: [],
    columnDefs: [ { orderable: false, targets: [4,5] } ]
});

//var statusFun = function (table, field, id, status,ctrl, method,param) {
var statusFun = function (id,status) {   
    var message = "";
    if (status == 1) {
        message = "<?php echo lang('deactive'); ?>";
    } else if (status == 0) {
        message = "<?php echo lang('active'); ?>";
    }
    bootbox.confirm({
        message: "Do you want to " + message + " it?",
        buttons: {
            confirm: {
                label: 'Ok',
                className: '<?php echo THEME_BUTTON; ?>'
            },
            cancel: {
                label: 'Cancel',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                var url = "<?php echo base_url() ?>/allacart/edit_status";
                var ctrl = 'allacart';
                $.ajax({
                    method: "POST",
                    url: url,
                    data: {id: id,status: status},
                    success: function (response) {
                        if (response == 200) {
                            setTimeout(function () {
                                $("#message").html("<div class='alert alert-success'><?php echo lang('change_status_success'); ?></div>");
                            });
                            window.setTimeout(function () {
                                window.location.href = ctrl;
                            }, 2000);
                        }
                    },
                    error: function (error, ror, r) {
                        bootbox.alert(error);
                    },
                });
            }
        }
    });
}


jQuery(document).ready(function(){
    jQuery('#day').select2();
});

/******************************Start Date and End Date with Validation****************************/
$('#start_date').datepicker({autoclose:true,startDate: '-0m',format: 'dd-mm-yyyy',keyboardNavigation:false,minDate:new Date()
}).change(function(){
    if($('#end_date').val() != '' && $('#end_date').val() != undefined){
      var first =  $(this).val();
      var second =  $('#end_date').val();
      if(new Date(second).getTime() <= new Date(first).getTime()){
        // $('.start_date_validation').html('Start date should be less than end date');
        bootbox.alert('<?php echo lang('startdate_less');?>');
        $(this).val('');
        $("#acc").html('');
      }
    }
});
$('#end_date').datepicker({autoclose:true,startDate: '-0m',format: 'dd-mm-yyyy',keyboardNavigation:false,minDate:new Date()
}).change(function(){
    if($('#start_date').val() != '' && $('#start_date').val() != undefined){
      var first = $('#start_date').val();
      var second = $(this).val();
      if(new Date(first).getTime() >= new Date(second).getTime()){
        // $('.end_date_validation').html('End date should be greater than start date');
        bootbox.alert('<?php echo lang('enddate_greater');?>');
        $(this).val('');
        $("#acc").html(''); 
      }
    } 
});


$("#start_date" ).focus(function() {
    $('.start_date_validation').html('');
});
$( "#end_date" ).focus(function() {
    $('.end_date_validation').html('');
}); 


$('#end_date').datepicker().on('changeDate', function(e) {
    var start_date = $('#start_date').val();  
    var end_date = $(this).val();
    var item_id = $('#item_id').val();
    if((start_date != '' && start_date != null && start_date != undefined) && (end_date != '' && end_date != null && end_date != undefined)){
        
        $.ajax({
            method: "POST",
            url: '<?php echo base_url('allacart/validate_dates')?>',
            data: {start_date:start_date,end_date:end_date,item_id:item_id},
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 1){
                    $.ajax({
                        method: "POST",
                        url: '<?php echo base_url('allacart/getdatediff')?>',
                        data: {start_date:start_date,end_date:end_date,item_id:item_id},
                        success: function (resp) {
                             $("#acc").html(resp);
                        },
                        error: function (error, ror, r) {
                            bootbox.alert(error);
                        },
                    });    
                }else{
                    bootbox.alert(obj.message);
                }
            },
            error: function (error, ror, r) {
                bootbox.alert(error);
            },
        });
    }
       
});

/*********************************************END**********************************************/




/***************** Remove Time,Price Row ******************/
/*$(document).on('click','.RemovePrice',function(){
    var buttonday = $(this).data('buttonday');
    var btncount = $(this).data('del-button-count');
    
    console.log('buttonday : '+buttonday);
    console.log('btncount : '+btncount);
    
    var start_time = $('#st_'+buttonday+'_'+btncount).remove();
    var end_time = $('#et_'+buttonday+'_'+btncount).remove();
    var price = $('#pr_'+buttonday+'_'+btncount).remove();
    $(this).remove(); // (-) button
    // var price = $('#'+buttonday+'_'+btncount).remove();// (+) button
    
});*/

$(document).on('click','.RemovePrice',function(){
    var buttonday = $(this).data('buttonday');
    var btncount = $(this).data('del-button-count');
    $('.timeprice_'+buttonday+'_'+btncount).remove();
});

/***************************End****************************/


var opn_modal = function (controller,method,id = '') {
    $.ajax({
        url: '<?php echo base_url(); ?>' + controller +"/"+ method,
        type: 'POST',
        data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',item_dates_id:id},
        success: function (data, textStatus, jqXHR) {
            $('#form-modal-box').html(data);
            $("#commonModal").modal('show');
        }
    });
}

/*--------------------------------------------------------------*/


/**************** Add Time,Price Row *********************/

/*$(document).on('click','.AddPrice',function(){
    alert('inner script');
    var btn = $(this);
    var val = btn.val();
    var btncount = $(this).data('button-count');
    if(btncount == '' || btncount == undefined){
        var buttonday = $(this).data('buttonday');    
        btncount = 0;
        var timesrow = $('.timeprice_'+buttonday+'_'+btncount);
        var start_time = $('#st_'+buttonday+'_'+btncount).val();
        var end_time = $('#et_'+buttonday+'_'+btncount).val();
        var price = $('#pr_'+buttonday+'_'+btncount).val();
        if((start_time != '' && start_time != null && start_time != undefined) && (end_time != '' && end_time != null && end_time != undefined) && (price != '' && price != null && price != undefined)){
            var btcount = Number(btncount) + 1;
            btn.data('button-count',btcount); 
            $.ajax({
                method: "POST",
                url: '<?php echo base_url('allacart/addtimerow')?>',
                data: {day:buttonday,button_count:btncount},
                success: function (response) {
                    timesrow.append(response);
                       
                },
                error: function (error, ror, r) {
                    bootbox.alert(error);
                },
            });
        }
    }
    
});*/

$(document).on('click','.AddPrice',function(){
    var btn = $(this);
    var val = btn.val();
    var buttonday = $(this).data('buttonday');    
    var fulldiv = $('.fulldiv_'+buttonday);
    var btcount = Number(val) + 1; 
    $.ajax({
        method: "POST",
        url: '<?php echo base_url('allacart/addtimerow')?>',
        data: {day:buttonday,button_count:btcount},
        success: function (response) {
            fulldiv.append(response);
            btn.val(btcount);   
        },
        error: function (error, ror, r) {
            bootbox.alert(error);
        },
    });
});

/***************************End****************************/

/*$(document).on('click','.EditPrice',function(){
    var btn = $(this);
    var btncount = $(this).data('button-count');
    var buttonday = $(this).data('buttonday');
    var timesrow = $('.timeprice_'+buttonday+'_'+btncount);
       
    var start_time = $('#st_'+buttonday+'_'+btncount).val();
    var end_time = $('#et_'+buttonday+'_'+btncount).val();
    var price = $('#pr_'+buttonday+'_'+btncount).val();
       
    if((start_time != '' && start_time != null && start_time != undefined) && (end_time != '' && end_time != null && end_time != undefined) && (price != '' && price != null && price != undefined)){
        var b = btncount + 1;
        btn.data('button-count',b); 
        $.ajax({
            method: "POST",
            url: '<?php echo base_url('allacart/edittimesrow')?>',
            data: {day:buttonday,button_count:btncount},
            success: function (response) {
                $('.timeprice_'+buttonday+'_'+btncount).append(response);   
            },
            error: function (error, ror, r) {
                bootbox.alert(error);
            },
        });
    }
});*/


/*Delete time row :- Call while time price row edit*/
// function deletetimerow(id,day,count){
function deletetimerow(id){

    bootbox.confirm({
            message: "<?php echo lang('delete'); ?>",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: '<?php echo THEME_BUTTON; ?>'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    if(id != '' && id != undefined){
                        // var row = $('.timeprice_'+day+'_'+count);
                        $.ajax({
                            method: "POST",
                            url: '<?php echo base_url('allacart/deletetimerow')?>',
                            data: {id:id},
                            success: function (response) {
                                if(response == 200){
                                    $("#message").html("<div class='alert alert-success'><?php echo lang('delete_success'); ?></div>");
                                    window.setTimeout(function () {
                                        location.reload();
                                    }, 2000);
                                }

                            },
                            error: function (error, ror, r) {
                                bootbox.alert(error);
                            },
                        });
                    }
                }
            }
    }); 
}

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


//Form Submit using status change for filters
    jQuery('body').on('change', '#statusfilter', function () {
        var status = $(this).val();
        $('#filterform').submit();
    }); 


    $('#startdate').datepicker({autoclose:true,format: 'yyyy-mm-dd',keyboardNavigation:false,minDate:new Date()
    }).change(function(){
        if($('#enddate').val() != '' && $('#enddate').val() != undefined){
          var first =  $(this).val();
          var second =  $('#enddate').val();
          if(new Date(second).getTime() <= new Date(first).getTime()){
            // $('.start_date_validation').html('Start date should be less than end date');
            bootbox.alert('<?php echo lang('startdate_less');?>');
            $(this).val('');
            $("#acc").html('');
          }
        }
    });
    $('#enddate').datepicker({autoclose:true,format: 'yyyy-mm-dd',keyboardNavigation:false,minDate:new Date()
    }).change(function(){
        if($('#startdate').val() != '' && $('#startdate').val() != undefined){
          var first = $('#startdate').val();
          var second = $(this).val();
          if(new Date(first).getTime() >= new Date(second).getTime()){
            // $('.end_date_validation').html('End date should be greater than start date');
            bootbox.alert('<?php echo lang('enddate_greater');?>');
            $(this).val('');
            $("#acc").html(''); 
          }
        } 
    });
</script>


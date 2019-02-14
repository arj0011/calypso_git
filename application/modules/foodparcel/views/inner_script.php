<script>
 $.validator.addMethod('positiveNumber',function (value) { 
        return Number(value) >= 0;
}, 'Enter positive number.');
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
                food_name: "required",
                price: 
                { 
                  required: true,
                  number: true,
                  positiveNumber:true
                },
                // image: "required",
                "item[]":"required",
                "limit[]":{ 
                  required: true,
                  digits:true
                }
            },
            messages: {
                
                price: 
                {
                    required: '<?php echo lang('price_validation');?>',
                    //number: '<?php echo lang('price_number_validation');?>'
                }
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
                    var url = "<?php echo base_url() ?>/foodparcel/edit_status";
                    var ctrl = 'foodparcel';
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


    var globalCatgoryArr = [];
    $(document).on('click','.addmorerow',function(){
        var count;
        var category_id;
        var btn = $(this);
        $('.category_cls').each(function(index){
            count = ($(this).attr('id')).split('_')[1];
            category_id = $('#category_'+count+' option:selected').val();
            if(category_id != '' && category_id != undefined && category_id != null){
                globalCatgoryArr.push(category_id);     
            }
        });
        
        count = parseInt(count) + 1;
        if(category_id != '' && category_id != undefined && category_id != null){
            $.ajax({
                method: "POST",
                url: '<?php echo base_url('foodparcel/get_all_items')?>',
                data: {count:count,globalCatgoryArr:globalCatgoryArr},
                success: function (response) {
                    $('.my_appendiv').append(response);
                },
                error: function (error, ror, r) {
                    bootbox.alert(error);
                },
            });    
        } 
        
    });


    $(document).on('click','.delrow',function(){
        
        var btn = $(this);
        var selarr = $(this).attr('id').split('_');
        var package_id = $('#package_id').val();
        var count = selarr.pop();
        var category_id;
        if(count != 1){
            $(this).each(function(index){
                category_id = $('#category_'+count+' option:selected').val();
                
                if(category_id != '' && category_id != undefined && category_id != null){
                    var indx = globalCatgoryArr.indexOf(category_id);
                    if (indx > -1) {
                        globalCatgoryArr.splice(indx, 1);
                    }     
                }
            });
            if((package_id != '' && package_id != undefined && package_id != null)){
                if(category_id != '' && category_id != undefined && category_id != null){
                   $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('foodparcel/delparcelitem')?>",
                        data: {package_id: package_id,category_id: category_id},
                        success: function (response) {
                            if (response == 200) {
                                bootbox.alert("<?php echo lang('cat_items_deleted');?>");
                            }
                        },
                        error: function (error, ror, r) {
                            bootbox.alert(error);
                        },
                    }); 
                }
            }
            $('#parent_'+count).remove();    
        }
         
    });

    // $('#date').datepicker({autoclose:true,startDate: '-0m',format: 'yyyy-mm-dd',keyboardNavigation:false,minDate:new Date()
    // });



//Form Submit using status change for filters
    jQuery('body').on('change', '#statusfilter', function () {
        var status = $(this).val();
        $('#filterform').submit();
    });  


/******************************Start Date and End Date with Validation****************************/
$('#start_date').datepicker({autoclose:true,format: 'dd-mm-yyyy',keyboardNavigation:false,minDate:new Date()
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
$('#end_date').datepicker({autoclose:true,format: 'dd-mm-yyyy',keyboardNavigation:false,minDate:new Date()
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
</script>


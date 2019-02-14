<link href="<?php echo base_url(); ?>backend_asset/plugins/select2/select2.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>backend_asset/plugins/select2/select2.js"></script>
<script>

    jQuery('body').on('click', '#submit', function () {

        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
            form_name = 'editFormAjax';
        $("#" + form_name).validate({
            rules: {
                full_name: "required",
                user_email: {
                    required: true,
                    email: true
                },
                phone_no: {
                    required: true,
//                    minlength: 10,
//                    maxlength: 20,
//                    number: true
                },
                // password: {
                //     required: true,
                //     minlength: 6
                // },
                first_name: "required",
                last_name: "required",
                company_name: "required",
                role_id: "required",
            },
            messages: {
                full_name: '<?php echo lang('user_name_validation'); ?>',
                first_name: '<?php echo lang('first_name_validation'); ?>',
                last_name: '<?php echo lang('last_name_validation'); ?>',
                company_name: '<?php echo lang('company_name_validation'); ?>',
                role_id: '<?php echo lang('role_id_validation'); ?>',
                user_email: {
                    required: '<?php echo lang('user_email_validation'); ?>',
                    email: '<?php echo lang('user_email_field_validation'); ?>'
                },
                phone_no: {
                    required: '<?php echo lang('phone_number_validation'); ?>',
                },
                // password: {
                //     required: '<?php echo lang('password_required_validation'); ?>',
                //     minlength: '<?php echo lang('password_minlength_validation'); ?>',
                // },
                confirm_password: {
                    required: '<?php echo lang('confirm_password_required_validation'); ?>',
                    equalTo: '<?php echo lang('confirm_password_equalto_validation'); ?>',
                    minlength: '<?php echo lang('confirm_password_minlength_validation'); ?>',
                },
                new_password: {
                    minlength: '<?php echo lang('password_minlength_validation'); ?>',
                },
                confirm_password1: {
                    equalTo: '<?php echo lang('confirm_password_equalto_validation'); ?>',
                    minlength: '<?php echo lang('confirm_password_minlength_validation'); ?>',
                },
                date_of_birth: '<?php echo lang('date_of_birth_validation'); ?>'

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

    jQuery('body').on('change', '.input_img3', function () {

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
        jQuery("#user_image").val("");
    });

    jQuery('body').on('change', '#company_name', function () {
        var organization_id = $("#company_name").val();
        $.ajax({
            url: '<?php echo base_url() . 'users/getCompanyRole' ?>',
            type: 'POST',
            data: {'organization_id': organization_id},
            dataType: "JSON",
            success: function (data) {
                if (data.status == 1) {
                    $("#role_id").html(data.roles);
                }
            }
        });

    });

   

    $('#common_datatable_users').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [3,4]}]
    });
    
    
   
   

    $(document).on('ready', function () {
     
        $('#start_date').datepicker({
            startView: 3,
            todayBtn: "linked",
            format: "mm/dd/yyyy",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: '-0d',
           // endDate: '+2m',
        });

        $('#end_date').datepicker({
            startView: 3,
            format: "mm/dd/yyyy",
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: '-0d',
            //endDate: '+2m',
        });
 
    });


    jQuery('body').on('change', '#statusfilter', function () {
        var status = $(this).val();
        $('#filterform').submit();
    });
</script>



<link href="<?php echo base_url();?>backend_asset/plugins/switchery/switchery.css" rel="stylesheet">
<script src="<?php echo base_url();?>backend_asset/plugins/switchery/switchery.js"></script>
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
                site_name: "required",
                gst: { 
                  required: true,
                  number: true,
                  positiveNumber:true
                },
                alacart_cancel_percent: 
                { 
                  required: true,
                  number: true,
                  positiveNumber:true
                },
                foodparcel_cancel_percent: { 
                  required: true,
                  number: true,
                  positiveNumber:true
                },
                partypackage_cancel_percent: { 
                  required: true,
                  number: true,
                  positiveNumber:true
                },
                alacart_cancel_time: { 
                  required: true,
                  // number: true,
                  digits:true,
                  positiveNumber:true
                },
                foodparcel_cancel_time: { 
                  required: true,
                  // number: true,
                  digits:true,
                  positiveNumber:true
                },
                partypackage_cancel_time: { 
                  required: true,
                  digits:true,
                  // number: true,
                  positiveNumber:true
                },
                wallet_amount: { 
                  required: true,
                  number: true,
                  positiveNumber:true
                }
                //site_logo_url:"required"
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

    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, {color: '#1AB394'});

    $(document).ready(function(){
        $('#top_products').select2();
    });



    jQuery('body').on('change', '#trending_type', function () {
 
         var trending_type = $(this).val();
        if(trending_type==2){
         getProductList(trending_type);

        }else{
        getAllProductList(trending_type); 
        }


   });
    function getProductList($trending_type)
    {
    
       $.ajax({
                type: "POST",
                url: "<?php echo  base_url()?>setting/getProducts",
                data: "trending_type="+trending_type,
                success: function (data) {
                  
                    $('#top_products').html(data);

                     $('#top_products').select2();
                    
                }
            });

     }

     function getAllProductList(trending_type)
    {
       $.ajax({
                type: "POST",
                url: "<?php echo  base_url()?>setting/getAllProducts",
                data: "trending_type="+trending_type,
                success: function (data) {
                
                    $('#top_products').html(data);

                     $('#top_products').select2();
                    
                }
            });

     }


 jQuery('body').on('click', '#submit', function () {
      
      var product_id = $('#top_products').val();
      var product_length = product_id.length;

      if(product_length > 5){
        $('#product_check').html('<p>'+'Only Top 5 Products you can select'+'</p>');
        return false;
    }else{
        $('#product_check').html('');
    }

});

   // $(document).on('change' , '#trending_type' , function(){
      
   //          var trending_type = $(this).val();
   //          $.ajax({
   //            type: "POST",
   //            url: "<?php echo base_url(); ?>setting/getProducts",
   //            data: {trending_type: trending_type},
   //            dataType: 'html'
   //          }).done(function(data) {
   //              $('#top_products').html(data);
   //        });

   //      });


</script>



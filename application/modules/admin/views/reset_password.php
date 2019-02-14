<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">
        <title><?php echo getConfig('site_name'); ?> | Admin Reset Password</title>
        <link href="<?php echo base_url(); ?>backend_asset/css/logincss.css" rel="stylesheet" type="text/css" />

    </head>

    <body>
        <div class="app-cross">
            <div class=""><img width="150" src="<?php echo base_url() . getConfig('site_logo'); ?>" class="img-responsive" alt="" /></div>
            <h2><?php echo lang('reset_password_heading'); ?></h2>


            <?php $attributes = array('id' => 'myform');
             echo form_open('admin/reset_password/' . $code,$attributes); ?>
             <div class="error-messages" style="display:none; color:red;"></div>
            <div id="infoMessage">
            <?php if(!empty($message)){?>
            <div class="alert alert-danger">
                <span style="text-align: center"><?php echo $message; ?></span>
            </div>
            <?php }?>
            </div>
            <p>
                <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length); ?></label> <br />
                <?php echo form_input($new_password); ?>
            </p>

            <p>
                <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm'); ?> <br />
                <?php echo form_input($new_password_confirm); ?>
            </p>

            <?php echo form_input($user_id); ?>
            <?php echo form_hidden($csrf); ?>

            <p><?php echo form_submit('submit', lang('reset_password_submit_btn'),"class='submit'"); ?></p>

            <?php echo form_close(); ?>


        </div>


    </body>
</html>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src='<?php echo base_url(); ?>backend_asset/js/forgotPassword.js'></script>
<script type="text/javascript">
(function($){
    $('.submit').click(function(e){
    
        var myModal = new ResetPassword({passForm: 'myform',
        newpassEleId : 'new',
        newpassConfirmEleId:'new_confirm'
        });

        if(myModal.error==true){
            e.preventDefault();
            var message = myModal.error_message;
            $(".error-messages").text(message).fadeIn().delay(1000).fadeOut();
           
        }
    });
})(jQuery);
</script>
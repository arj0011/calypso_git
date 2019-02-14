<script>
    var urls = "<?php echo base_url() ?>";
    /* window.setTimeout(function () {
     bootbox.hideAll();
     }, 2000);*/

    /** start script in application **/


    var logout = function () {
        bootbox.confirm('<?php echo lang('Logout_Confirmation'); ?>', function (isTrue) {
            if (isTrue) {
                $.ajax({
                    url: '<?php echo base_url() . 'admin/logout' ?>',
                    type: 'POST',
                    dataType: "JSON",
                    success: function (data) {
                        window.location.href = "<?php echo base_url(); ?>admin/login";
                    }
                });
            }
        });

    }

    var addFormBoot = function (ctrl, method)
    {
        $(document).on('submit', "#add-form-common", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>' + ctrl + "/" + method,
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".loaders").fadeIn("slow");
                },
                success: function (response, textStatus, jqXHR) {
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
//                            bootbox.alert({
//                                message: data.message,
//                                callback: function (
//
//
//                                        ) { /* your callback code */
//                                }
//                            });
                            $("#commonModal").modal('show');
                            toastr.success(data.message);


                            window.setTimeout(function () {
                                window.location.href = "<?php echo base_url(); ?>" + ctrl;
                            }, 2000);
                            $(".loaders").fadeOut("slow");

                        } else {
                            toastr.error(data.message);
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            $(".loaders").fadeOut("slow");
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
    }

    var updateFormBoot = function (ctrl, method)
    {
        $("#edit-form-common").submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>' + ctrl + "/" + method,
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".loaders").fadeIn("slow");
                },
                success: function (response, textStatus, jqXHR) {
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
//                            bootbox.alert({
//                                message: data.message,
//                                callback: function (
//
//
//                                        ) { /* your callback code */
//                                }
//                            });
                            $("#commonModal").modal('hide');
                            toastr.success(data.message);
                            window.setTimeout(function () {
                                window.location.href = "<?php echo base_url(); ?>" + ctrl;
                            }, 2000);
                            $(".loaders").fadeOut("slow");

                        } else {
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            $(".loaders").fadeOut("slow");
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
    }

    var editFn = function (ctrl, method, id) {
        $.ajax({
            url: '<?php echo base_url(); ?>' + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                $(".loaders").fadeIn("slow");
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                addFormBoot();
                $(".loaders").fadeOut("slow");
            }
        });
    }

    var viewFn = function (ctrl, method, id) {
        $.ajax({
            url: '<?php echo base_url(); ?>' + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                $(".loaders").fadeIn("slow");
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                addFormBoot();
                $(".loaders").fadeOut("slow");
            }
        });
    }
    var requestSent = false;
    var open_modal = function (controller) {
        if(!requestSent) {
            requestSent = true;
            $.ajax({
                url: '<?php echo base_url(); ?>' + controller + "/open_model",
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
                success: function (data, textStatus, jqXHR) {
                    $('#form-modal-box').html(data);
                    $("#commonModal").modal('show');
                    requestSent = false;
                }
            });
        }
    }

    var deleteFn = function (table, field, id, ctrl, method) {
        
        if(typeof method == "undefined" || method==""){
            method = "users/delete";
        }
        
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
                    var url = "<?php echo base_url() ?>"+method;
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {id: id, id_name: field, table: table},
                        success: function (response) {
                            if (response == 200) {

                                $("#message").html("<div class='alert alert-success'><?php echo lang('delete_success'); ?></div>");
                                window.setTimeout(function () {
                                    window.location.href = "<?php echo base_url(); ?>" + ctrl;
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

     var delFn = function (table, field, id, ctrl, method) {
        
        if(typeof method == "undefined" || method==""){
            method = "newsCategory/delete";
        }
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
                    var url = urls+ctrl+'/'+method;
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {id: id, id_name: field, table: table},
                        success: function (response) {
                            if (response == 200) {

                                $("#message").html("<div class='alert alert-success'><?php echo lang('delete_success'); ?></div>");
                                window.setTimeout(function () {
                                    // window.location.href = "<?php echo base_url(); ?>" + ctrl;
                                    window.location.reload();
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
    var deleteRole = function (table, field, id, ctrl) {

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
                    var url = "<?php echo base_url() ?>users/delete_role";
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {id: id, id_name: field, table: table},
                        success: function (response) {
                            if (response == 200) {

                                $("#message").html("<div class='alert alert-success'><?php echo lang('delete_success'); ?></div>");
                                window.setTimeout(function () {
                                    window.location.href = "<?php echo base_url(); ?>" + ctrl;
                                }, 2000);
                            }
                            else{
                                $("#message").html("<div class='alert alert-danger'><?php echo lang('delete_failed'); ?></div>");
                                window.setTimeout(function () {
                                    window.location.href = "<?php echo base_url(); ?>" + ctrl;
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

    var statusFn = function (table, field, id, status) {
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
                    var url = "<?php echo base_url() ?>admin/status";
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {id: id, id_name: field, table: table, status: status},
                        success: function (response) {
                            if (response == 200) {
                                setTimeout(function () {
                                    $("#message").html("<div class='alert alert-success'><?php echo lang('change_status_success'); ?></div>");
                                });
                                window.location.reload();
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

    var statusChange = function (table, field, id, status) {
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
                    var url = "<?php echo base_url() ?>admin/statuschange";
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {id: id, id_name: field, table: table, status: status},
                        success: function (response) {
                            if (response == 200) {
                                setTimeout(function () {
                                    $("#message").html("<div class='alert alert-success'><?php echo lang('change_status_success'); ?></div>");
                                });
                                window.setTimeout(function () {
                                    window.location.reload();
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

    /** end script in application **/

/**
 * This javascript file checks for the brower/browser tab action.
 * It is based on the file menstioned by Daniel Melo.
 * Reference: http://stackoverflow.com/questions/1921941/close-kill-the-session-when-the-browser-or-tab-is-closed
 */
var validNavigation = false;
 
function endSession() {
  // Browser or broswer tab is closed
  // Do sth here ...
  //alert("bye");
  // logout();
    // $.ajax({
    //     url: '<?php echo base_url() . 'admin/logout' ?>',
    //     type: 'POST',
    //     dataType: "JSON",
    //     success: function (data) {
    //         window.location.href = "<?php echo base_url(); ?>admin/login";
    //     }
    // });
    <?php 
    //$this->session->unset_userdata("id");
    //$this->session->sess_destroy(); 
    ?>
}
 
function wireUpEvents() {
  /*
  * For a list of events that triggers onbeforeunload on IE
  * check http://msdn.microsoft.com/en-us/library/ms536907(VS.85).aspx
  */
  // window.onbeforeunload = function() {
  //     if (!validNavigation) {
  //        endSession();
  //     }
  // }
 
  // // Attach the event keypress to exclude the F5 refresh
  // $(document).bind('keypress', function(e) {
  //   if (e.keyCode == 116){
  //     validNavigation = true;
  //   }
  // });
 
  // // Attach the event click for all links in the page
  // $("a").bind("click", function() {
  //   validNavigation = true;
  // });
 
  // // Attach the event submit for all forms in the page
  // $("form").bind("submit", function() {
  //   validNavigation = true;
  // });
 
  // // Attach the event click for all inputs in the page
  // $("input[type=submit]").bind("click", function() {
  //   validNavigation = true;
  // });
   

    if(window.event.clientX < 0 && window.event.clientY < 0){
      <?php 
    //$this->session->unset_userdata("id");
    //$this->session->sess_destroy(); 
    ?>
    }


}
 
// Wire up the events as soon as the DOM tree is ready
$(document).ready(function() {
  //wireUpEvents();  
});

function resetForm(form) {
    // clearing inputs
    var inputs = form.getElementsByTagName('input');
    for (var i = 0; i<inputs.length; i++) {
        switch (inputs[i].type) {
            // case 'hidden':
            case 'text':
                inputs[i].value = '';
                break;
            case 'radio':
            case 'checkbox':
                inputs[i].checked = false;   
        }
    }

    // clearing selects
    var selects = form.getElementsByTagName('select');
    for (var i = 0; i<selects.length; i++)
        selects[i].selectedIndex = 0;

    // clearing textarea
    // var text= form.getElementsByTagName('textarea');
    // for (var i = 0; i<text.length; i++)
    //     text[i].innerHTML= '';

    // return false;
    //window.location.reload();
}

function allnumeric(inputtxt)
{
    var numbers = /^[1-9]\d*(\.\d+)?$/;
    if(inputtxt.value != ''){
        if(inputtxt.value.match(numbers)){
            return true;
        }
        else{
            bootbox.alert('Please enter positive and non zero price only.');
            inputtxt.value = '';
            return false;
        }    
    }
    
} 

var remove_sel_img = function (){
    
    var $el = $('#image');
    $el.wrap('<form>').closest('form').get(0).reset();
    $el.unwrap();
    
}

</script>
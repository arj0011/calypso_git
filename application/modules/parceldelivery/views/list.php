<style>
  #message_div{
    background-color: #ffffff;
    border: 1px solid;
    box-shadow: 10px 10px 5px #888888;
    display: none;
    height: auto;
    left: 36%;
    position: fixed;
    top: 20%;
    width: 40%;
    z-index: 1;
  }
  #close_button{
    right:-15px;
    top:-15px;
    cursor: pointer;
    position: absolute;
  }
  #close_button img{
    width:30px;
    height:30px;
  }    
  #message_container{
    height: 450px;
    overflow-y: scroll;
    padding: 20px;
    text-align: justify;
    width: 99%;
  }
  .edit-row{
    color :red;
    text-decoration: underline;
  }


</style>

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
      </li>
      <li>
        <?php if($this->uri->segment(2) == 'alacart'){ ?>
        <a href="<?php echo site_url('parceldelivery/alacart');?>"><?php echo lang('alacart_mgt');?></a>
        <?php }else if($this->uri->segment(2) == 'foodparcel'){ ?>
        <a href="<?php echo site_url('parceldelivery/foodparcel');?>"><?php echo lang('delivery_management');?></a>
        <?php }else if($this->uri->segment(2) == 'partypackage'){ ?>
        <a href="<?php echo site_url('parceldelivery/partypackage');?>"><?php echo lang('party_mgt');?></a>
        <?php  }  ?>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">

  </div>
</div>
<div class="wrapper wrapper-content animated fadeIn">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <div class="form-group " href="#">
              <form id="filterform" action="<?php echo base_url('parceldelivery');?>/<?php echo $this->uri->segment(2);?>" method="post">
                <div class="row">
                <div class="col-sm-4">
                  <label>Status</label>
                  <select id="statusfilter" name="statusfilter" class="form-control col-sm-3">
                      <option value="">Select</option>
                      <option value="1" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 1) ? 'selected' : '';?>>Pending</option>
                      <option value="2" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 2) ? 'selected' : '';?>>Confirm</option>
                      <option value="3" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 3) ? 'selected' : '';?>>In-Progress</option>
                      <option value="4" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 4) ? 'selected' : '';?>>Complete</option>
                      <option value="5" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 5) ? 'selected' : '';?>>Cancelled</option>
                      <option value="6" <?php echo (isset($_POST['statusfilter']) && $_POST['statusfilter'] == 6) ? 'selected' : '';?>>Delivered</option>
                  </select>
                  </div>
                  <div class="col-sm-4">
                    <label></label>
                    <input type="submit" name="reset" value="Reset" class="btn btn-danger" onclick="resetForm(this.form)" style="margin-top: 23px;margin-bottom:  0;" >
                  </div>
                  </div>
              </form>
          </div>
        </div>

        <div class="ibox-content">
         <div class="row">
          <?php $message = $this->session->flashdata('success');
          if(!empty($message)):?><div class="alert alert-success">
          <?php echo $message;?></div><?php endif; ?>
          <?php $error = $this->session->flashdata('error');
          if(!empty($error)):?><div class="alert alert-danger">
          <?php echo $error;?></div><?php endif; ?>
          <div id="message"></div>
          <div class="col-lg-12" style="overflow-x: auto">
            <table class="table table-bordered table-responsive" id="common_datatable_parceldeliver">
              <thead>
                <tr>
                  
                  <th><?php echo lang('order_no');?></th>
                  <th><?php echo lang('order_customer');?></th>
                  <th><?php echo lang('order_customer_contact');?></th>
                  <th><?php echo lang('order_date');?></th>
                  <th><?php echo lang('delivery_date');?></th>
                  <th><?php echo lang('delivery_time');?></th>
                  <th><?php echo lang('total_price');?> (&#8377;)</th>
                  <th>Redeemption Code</th>
                  <th><?php echo lang('order_status');?></th>
                  <th><?php echo lang('action');?></th>
                </tr>
              </thead>
              <tbody>
                <?php 

                if (isset($list) && !empty($list)):
                  $rowCount = 0;
                foreach ($list as $rows):
                  $rowCount++;
                ?>
                <tr>
                 
                 <td><?php echo $rows->unique_order_id; ?></td>
                 <td><?php echo $rows->full_name; ?></td>
                 <td><?php echo $rows->phone; ?></td>
                 <td><?php if($rows->created==NULL){echo '';} else{echo convertDate($rows->created);}?></td>
                 <td><?php if($rows->delivery_date==NULL){echo '';} else{echo convertDate($rows->delivery_date);}?></td>
                 <td><?php echo $rows->delivery_time;?></td>
                 <td><?php echo $rows->net_amount;?></td>
                 <td><?php echo $rows->redeemption_code;?></td>
                 <td>
                 <?php  
                  if($rows->status == 5 || $rows->status == 6){
                    echo ($rows->status == 5) ? "Cancelled" : "Delivered";
                  }else{
                 ?>
                  <select class="form-control statusdropdown" onchange="changeStatus('<?php echo encoding($rows->id)?>',this.value,'<?php echo $rows->pending_amount?>');">
          
                    <?php if($rows->type == 1): ?>
                      <?php if($rows->status == 4): ?>  
                      <option value="4" <?php echo ($rows->status == 4) ? 'selected' : '';?>>Complete</option>
                      <option value="5" <?php echo ($rows->status == 5) ? 'selected' : '';?>>Cancelled</option>
                      <option value="6" <?php echo ($rows->status == 6) ? 'selected' : '';?>>Delivered</option>    
                      <?php else: ?>
                      <option value="2" <?php echo ($rows->status == 2) ? 'selected' : '';?>>Confirm</option>
                      <option value="4" <?php echo ($rows->status == 4) ? 'selected' : '';?>>Complete</option>
                      <option value="5" <?php echo ($rows->status == 5) ? 'selected' : '';?>>Cancelled</option>
                      <option value="6" <?php echo ($rows->status == 6) ? 'selected' : '';?>>Delivered</option>
                      <?php endif; ?>  
                    <?php endif; ?>  

                    <?php if($rows->type == 2): ?>  
                      <?php if($rows->status == 4): ?>
                      <option value="4" <?php echo ($rows->status == 4) ? 'selected' : '';?>>Complete</option>
                      <option value="5" <?php echo ($rows->status == 5) ? 'selected' : '';?>>Cancelled</option>
                      <option value="6" <?php echo ($rows->status == 6) ? 'selected' : '';?>>Delivered</option>  
                      <?php else: ?>
                      <option value="1" <?php echo ($rows->status == 1) ? 'selected' : '';?>>Pending</option>
                      <option value="3" <?php echo ($rows->status == 3) ? 'selected' : '';?>>Process</option>  
                      <option value="4" <?php echo ($rows->status == 4) ? 'selected' : '';?>>Complete</option>
                      <option value="5" <?php echo ($rows->status == 5) ? 'selected' : '';?>>Cancelled</option>
                      <option value="6" <?php echo ($rows->status == 6) ? 'selected' : '';?>>Delivered</option>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php if($rows->type == 3): ?>  
                      <?php if($rows->status == 4): ?>
                      <option value="4" <?php echo ($rows->status == 4) ? 'selected' : '';?>>Complete</option>
                      <option value="5" <?php echo ($rows->status == 5) ? 'selected' : '';?>>Cancelled</option>
                      <option value="6" <?php echo ($rows->status == 6) ? 'selected' : '';?>>Delivered</option>  
                      <?php else: ?>
                      <option value="3" <?php echo ($rows->status == 3) ? 'selected' : '';?>>Process</option>  
                      <option value="4" <?php echo ($rows->status == 4) ? 'selected' : '';?>>Complete</option>
                      <option value="5" <?php echo ($rows->status == 5) ? 'selected' : '';?>>Cancelled</option>
                      <option value="6" <?php echo ($rows->status == 6) ? 'selected' : '';?>>Delivered</option>
                      <?php endif; ?>
                    <?php endif; ?>

                  </select>   
                 <?php } ?>
                 </td>
                  
                 <td>       
                  <a href="<?php echo site_url('parceldelivery/view_order/'.encoding($rows->id));?>" class="on-default edit-row"><img width="20" src="<?php echo base_url().VIEW_ICON;?>" /></a>
            </tr>
            <?php endforeach; endif;?>
                     </tbody>
                   </table>
                 </div>
               </div>
             </div>
             <div id="form-modal-box"></div>
           </div>
         </div>
       </div>
       <div id="message_div">
        <span id="close_button"><img src="<?php echo base_url();?>backend_asset/images/close.png" onclick="close_message();"></span>
        <div id="message_container"></div>
      </div>

      <script type="text/javascript">
      function changeStatus(id,status,pending_amount){
  
        var type = '<?php echo $this->uri->segment(2); ?>';
    
        if(status == 5){
          bootbox.prompt({
            title: "Please select person who is cancelling order!",
            inputType: 'select',
            inputOptions: [
                {
                    text: 'Select Person',
                    value: '',
                },
                {
                    text: 'User',
                    value: '1',
                },
                {
                    text: 'Admin',
                    value: '2',
                }
            ],
            callback: function (result) {
              if(result){
                var person = result;
                var url = "<?php echo base_url(); ?>parceldelivery/edit_status";
                $.ajax({
                  method: "POST",
                  url: url,
                  data: {id: id,type:type,status:status,person:person},
                  success: function(data){
                  var obj = JSON.parse(data);
                  if(obj.status == 1){
                    
                    setTimeout(function () {
                      $("#message").html("<div class='alert alert-success'>"+obj.message+"</div>");
                    });
                  }else{
                    setTimeout(function () {
                      $("#message").html("<div class='alert alert-danger'>"+obj.message+"</div>");
                    });
                  }
                  window.setTimeout(function () {
                    window.location.href = obj.url;
                  }, 5000);
                  },error: function (error, ror, r) {
                    bootbox.alert(error);
                  },
                });
              }  
            }
        });

        }else{
          // to check whether party package payment is complete or not 
          // if(type == 'partypackage' || type == 'alacart' ){
            
            if(status == 4 || status == 6){
              
              if(pending_amount > 0){
                  
                  bootbox.prompt({
                    title: "Order not complete! Remaing amount is " + pending_amount, 
                    inputType: "number",
                    placeholder: "enter remaining amount",
                    callback: function(result) {

                      if(result != '' && result != undefined && result != null){
                        var url = "<?php echo base_url(); ?>parceldelivery/edit_status";
                        $.ajax({
                          method: "POST",
                          url: url,
                          data: {id: id,amount:result,status:status,type:type},
                          success: function(data){
                          var obj = JSON.parse(data);
                          if(obj.status == 1){
                            
                            setTimeout(function () {
                              $("#message").html("<div class='alert alert-success'>"+obj.message+"</div>");
                            });
                          }else{
                            setTimeout(function () {
                              $("#message").html("<div class='alert alert-danger'>"+obj.message+"</div>");
                            });
                          }
                          window.setTimeout(function () {
                            window.location.href = obj.url;
                          }, 3000);
                          },error: function (error, ror, r) {
                            bootbox.alert(error);
                          },
                        });
                      }  
                    }
                });

              }else{
                
                bootbox.confirm({
                message: "Are you want to sure to change status ?",
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
                    var url = "<?php echo base_url(); ?>parceldelivery/edit_status";
                    $.ajax({
                      method: "POST",
                      url: url,
                      data: {id: id,type:type,status:status},
                      success: function(data){
                      var obj = JSON.parse(data);
                      if(obj.status == 1){
                        
                        setTimeout(function () {
                          $("#message").html("<div class='alert alert-success'>"+obj.message+"</div>");
                        });
                      }else{
                        setTimeout(function () {
                          $("#message").html("<div class='alert alert-danger'>"+obj.message+"</div>");
                        });
                      }
                      window.setTimeout(function () {
                        window.location.href = obj.url;
                      }, 3000);
                      },error: function (error, ror, r) {
                        bootbox.alert(error);
                      },
                    });
                  }
                }
                });

              } 
            }

          // }
          // else{
          //   bootbox.confirm({
          //   message: "Are you want to sure to change status ?",
          //   buttons: {
          //       confirm: {
          //           label: 'Ok',
          //           className: '<?php echo THEME_BUTTON; ?>'
          //       },
          //       cancel: {
          //           label: 'Cancel',
          //           className: 'btn-danger'
          //       }
          //   },
          //   callback: function (result) {
          //     if (result) {
          //       var url = "<?php echo base_url(); ?>parceldelivery/edit_status";
          //       $.ajax({
          //         method: "POST",
          //         url: url,
          //         data: {id: id,type:type,status:status},
          //         success: function(data){
          //         var obj = JSON.parse(data);
          //         if(obj.status == 1){
                    
          //           setTimeout(function () {
          //             $("#message").html("<div class='alert alert-success'>"+obj.message+"</div>");
          //           });
          //         }else{
          //           setTimeout(function () {
          //             $("#message").html("<div class='alert alert-danger'>"+obj.message+"</div>");
          //           });
          //         }
          //         window.setTimeout(function () {
          //           window.location.href = obj.url;
          //         }, 3000);
          //         },error: function (error, ror, r) {
          //           bootbox.alert(error);
          //         },
          //       });
          //     }
          //   }
          //   });
          // }
        }   
      }

   </script>

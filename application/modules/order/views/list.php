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
        <a href="<?php echo site_url('order');?>"><?php echo lang('order_management');?></a>
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
       <!--  <div class="ibox-title">
          <div class="btn-group " href="#">
            <a href="<?=base_url('/purchase/add'); ?>" class="btn btn-primary">
              <?php echo lang('add_purchase');?>
              <i class="fa fa-plus"></i>
            </a>
          </div>
        </div> -->
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
            <table class="table table-bordered table-responsive" id="common_datatable_order">
              <thead>
                <tr>
                  <th><?php echo lang('serial_no');?></th>
                  <th><?php echo lang('order_no');?></th>
                  <th><?php echo lang('order_customer');?></th>
                  <th><?php echo lang('order_customer_contact');?></th>
                  <!-- <th><?php echo lang('products_order');?></th> -->
                  <th><?php echo lang('order_products');?></th>
                  <th><?php echo lang('total_price');?> ($)</th>
                  <th><?php echo lang('order_date');?></th>
                  <th><?php echo lang('remaining_amount');?></th>
                 <!--  <th><?php echo lang('estimated_delivery_date');?></th> -->
                  <th><?php echo lang('order_priority');?></th>
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
                 <td><?php echo $rowCount; ?></td>
                 <td><?php echo $rows->id; ?></td>
                 <td><?php echo $rows->full_name; ?></td>
                 <td><?php echo $rows->phone; ?></td>
                <!--  <td><?php $option = array('table' => ORDER_META.' as order_meta',
                                        'select' =>'order_meta.id,order_meta.order_id,product.product_name,order_meta.product_id',
                                        'join' => array(PRODUCTS.' as product' => 'product.id=order_meta.product_id'),
                                        'where' => array('order_meta.order_id' => $rows->id));
                                      $order_products = commonGetHelper($option);
                                      foreach($order_products as $product){
                                         ?>   
                                           <p class='text-success'><?php echo ucwords($product->product_name);?></p>
                                             <?php 
                                      }
                                      ?></td>  -->


                                      <td><?php echo $rows->order_products;?></td>
                                      <td><?php 
                                          $price=$rows->total_price;
                                          if((int) $price == $price)
                                          {
                                            echo  $price.".00";
                                          }else{
                                            echo  $price;
                                          }

                                      ?>
                                      

                                      </td>
                                      <td><?php if($rows->order_date=='0000-00-00'){echo '';} else{echo convertDate($rows->order_date);}?></td>
                                      <td><?php echo $rows->remaining_amount;?></td>
                                      <!-- <td><?php if($rows->estimated_delivery_date=='0000-00-00'){echo '';} else{echo convertDate($rows->estimated_delivery_date);}?></td> -->
                                      <td><?php echo $rows->order_priority; ?></td>
                                      <td><?php  if($rows->status==1){
                                        echo "Delivered";
                                      }else if($rows->status==2)
                                      {
                                        echo "In Process";
                                      }else
                                      {
                                        echo "Ready";
                                      }?></td>
                                      
                                      <td> 

                            <!-- <td>
                                <select id="status" update_id="<?php echo $rows->id;?>" name="status" size="1" required ">
                           
                              <option value="1" <?php if($rows->status == 1) echo "selected"; ?>>Delivered</option>
                              <option value="2" <?php if($rows->status == 2) echo "selected"; ?>>Process</option>
                              <option value="3" <?php if($rows->status == 3) echo "selected"; ?>>Ready</option>
                             
                             </select>
                            </td>
                            <td> -->
                              
                             <a href="<?php echo site_url('order/view_order/'.encoding($rows->id));?>" class="on-default edit-row"><img width="20" src="<?php echo base_url().VIEW_ICON;?>" /></a>
                             <?php if($rows->status==2){?>
                             
                             <a class="btn btn-sm btn-primary" href="javascript:void(0);" onclick="changeStatus('<?php echo $rows->id; ?>')" rid="<?php echo $rows->id; ?>">Ready</a>
                             <?php }else if($rows->status==3){?>
                             <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="editFn('order','order_edit','<?php echo encoding($rows->id)?>');">Deliver</a> 
                             <?php }?>
                           </td>

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
       function changeStatus(rid){

        var r = confirm("Are you want to sure to change status");
        if (r == true) 
        {
         txt = " OK!";

         $.ajax({
          url:"<?php echo base_url(); ?>order/changestatus",
          type:"post",
          data:{rid:rid},
          success: function(data)
          {
                         //alert(data);
                         window.location.href='<?php echo base_url();?>order'
                       }
                     });
       }
     }

   </script>

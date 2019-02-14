<link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css">
<style type="text/css">
  .btn-primary {
    background-color: #1ab394;
    border-color: #1ab394;
}
.btn-primary:hover, .btn-primary:active, .btn-primary.hover {
    background-color: #1ab394;
}
</style>

<div class="wrapper wrapper-content">
    <h3>Welcome <?php echo getConfig('site_name'); ?></h3>
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12" title="Total User">
          <a href="<?php echo base_url('users');?>">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total User</span>
              <span class="info-box-number"><?php echo $total_users; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12" title="Total Offer">
          <a href="<?php echo base_url('billingoffer');?>">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-gift"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Offer</span>
              <span class="info-box-number"><?php echo $total_offers; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12" title="Total Redeemed offer">
          <a href="<?php echo base_url('parceldelivery/alacart');?>">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Redeemed offer</span>
              <span class="info-box-number"><?php echo $total_orders; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          </a>
          <!-- /.info-box -->
        </div>
      </div>
      <!-- /.row -->
</section>



</div>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> <?php echo getConfig('site_name');?> | <?php 
        if(!empty($title) && isset($title)): echo ucwords($title);endif; ?></title>

        <link href="<?php echo base_url(); ?>backend_asset/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/animate.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/dropzone/basic.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/dropzone/dropzone.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/style.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/plugins/summernote/dist/summernote.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/toastr/toastr.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.css" rel="stylesheet"/><link href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.css" rel="stylesheet"/> 
        <style>.error{color: #DC2430;}</style>
        <style>
            .loaders {
              position:absolute; 
              opacity:0.5;
              background-color:fff; 
              width:100%; height:100%;
              top:0; left:0; bottom:0; right:0; 
              text-align:center; vertical-align:middle; 
              display: none;
              z-index: 2000;
          }
          .loaders img{
            left : 50%;
            top : 50%;
            position : absolute;
            z-index : 101;


        }
    </style>
    
</head>

<body class="pace-done <?php echo THEME;?>" cz-shortcut-listen="true">
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">

                        <div class="dropdown profile-element"> <span>
                            <img width="48" alt="image" class="img-circle" src="<?php echo base_url().  getConfig('site_logo'); ?>" />
                        </span>
                        <!-- <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">
                                <?php $user = getUser($this->session->userdata('id'));
                                if(!empty($user)){
                                   echo ucwords($user->full_name);}?></strong>
                               </span> <span class="text-muted text-xs block"><?php echo lang('Admin');?> <b class="caret"></b></span> </span> </a>
                               <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="<?php echo site_url('admin/profile'); ?>"><?php echo lang('profile');?></a></li>
                                <li><a href="<?php echo site_url('admin/password'); ?>"><?php echo lang('change_password');?></a></li>
                                
                            </ul> -->
                        </div>
                        <div class="logo-element">
                            <img width="80" src="<?php echo base_url().  getConfig('site_logo'); ?>" class="img-responsive img-circle" alt="" />
                        </div>
                    </li>

                    <li title="Dashboard" class="<?php echo (strtolower($this->router->fetch_class()) == "admin") ? "active" : "" ?>">
                        <a href="<?php echo site_url('admin'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label"><?php echo lang('dashboard');?></span></a>
                    </li>


                    <li title="Users" class="<?php echo (strtolower($this->router->fetch_class()) == "users") ? "active" : "" ?>">
                        <a href="<?php echo site_url('users'); ?>"><i class="fa fa-user"></i> <span class="nav-label"><?php echo lang('users');?></span></a>
                    </li>

                    <li title="Category" class="<?php echo (strtolower($this->router->fetch_class()) == "category") ? "active" : "" ?>">
                        <a href="<?php echo site_url('category'); ?>"><i class="fa fa-list"></i> <span class="nav-label"><?php echo lang('category_management');?></span></a>
                    </li>

                    <li title="Food Items" class="<?php echo (strtolower($this->router->fetch_class()) == "product") ? "active" : "" ?>">
                        <a href="javascript:void(0);"><i class="fa fa-gamepad"></i> <span class="nav-label"><?php echo lang('food_items_management');?></span></a>

                            <ul class="nav nav-second-level">
                                <li title="<?php echo lang('alla_cart'); ?>" class="<?php echo (strpos($title, "Alla Cart") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('allacart'); ?>"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo lang('alla_cart'); ?></span></a>
                                </li>   
            
                                <li title="<?php echo lang('food_parcel'); ?>" class="<?php echo (strpos($title, "Food Parcel") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('foodparcel'); ?>"><i class="fa fa-table"></i> <span class="nav-label"><?php echo lang('food_parcel'); ?></span></a>
                                </li>
                                <li title="<?php echo lang('party_package'); ?>" class="<?php echo (strpos($title, "Party Package") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('partypackage'); ?>"><i class="fa fa-table"></i> <span class="nav-label"><?php echo lang('party_package'); ?></span></a>
                                </li>
                            </ul>
                    </li>												
                    <li title="Best Offer" class="<?php echo (strtolower($this->router->fetch_class()) == "offer") ? "active" : "" ?>">
                        <a href="<?php echo site_url('offer'); ?>"><i class="fa fa-list"></i> <span class="nav-label">Best Offer</span></a>
                    </li>
                    <li title="Offer" class="<?php echo (strtolower($this->router->fetch_class()) == "billingoffer") ? "active" : "" ?>">
                        <a href="<?php echo site_url('billingoffer'); ?>"><i class="fa fa-list"></i> <span class="nav-label">Offer</span></a>
                    </li>
                    <li title="Product Management" class="<?php echo (strtolower($this->router->fetch_class()) == "product") ? "active" : "" ?>">
                        <a href="javascript:void(0);"><i class="fa fa-gamepad"></i> <span class="nav-label">Product Management</span></a>

                            <ul class="nav nav-second-level">
                                <li title="<?php echo lang('alla_cart'); ?>" class="<?php echo (strpos($title, "Alla Cart") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('parceldelivery/alacart'); ?>"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo lang('alla_cart'); ?></span></a>
                                </li>   
            
                                <li title="<?php echo lang('food_parcel'); ?>" class="<?php echo (strpos($title, "Food Parcel") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('parceldelivery/foodparcel'); ?>"><i class="fa fa-table"></i> <span class="nav-label"><?php echo lang('food_parcel'); ?></span></a>
                                </li>
                                <li title="<?php echo lang('party_package'); ?>" class="<?php echo (strpos($title, "Party Package") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('parceldelivery/partypackage'); ?>"><i class="fa fa-table"></i> <span class="nav-label"><?php echo lang('party_package'); ?></span></a>
                                </li>
                            </ul>
                    </li> 

                    <li title="Suggestion & Feedback" class="<?php echo (strtolower($this->router->fetch_class()) == "product") ? "active" : "" ?>">
                        <a href="javascript:void(0);"><i class="fa fa-gamepad"></i> <span class="nav-label">Suggestion & Feedback</span></a>

                            <ul class="nav nav-second-level">
                                <li title="Suggestion" class="<?php echo (strpos($title, "Suggestion") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('suggestionfeedback/suggestion'); ?>"><i class="fa fa-file-text-o"></i> <span class="nav-label">Suggestion</span></a>
                                </li>   
            
                                <li title="Feedback" class="<?php echo (strpos($title, "Feedback") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('suggestionfeedback/feedback'); ?>"><i class="fa fa-table"></i> <span class="nav-label">Feedback</span></a>
                                </li>
                            </ul>
                    </li> 

                    <li title="Event" class="<?php echo (strtolower($this->router->fetch_class()) == "event") ? "active" : "" ?>">
                        <a href="<?php echo site_url('event'); ?>"><i class="fa fa-list"></i> <span class="nav-label"><?php echo lang('event');?></span></a>
                    </li>

                    <li title="Settings" class="<?php echo (strtolower($this->router->fetch_class()) == "setting") ? "active" : "" ?>">
                        <a href="<?php echo site_url('setting'); ?>"><i class="fa fa-cogs"></i> <span class="nav-label"><?php echo lang('setting');?></span></a>
                    </li>

                    <!-- <li title="Logout">
                        <a href="javascript:void(0)" onclick="logout()"><i class="fa fa-sign-out"></i> <span class="nav-label"><?php echo lang('logout');?></span></a>
                    </li> -->

                </ul>
            </div>
        </nav>
        <input type="hidden" value="" id="latestOrderId">
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown custome_dropdown">
                       
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                  
                                <span class="block btn-sm btn-primary"> 
                                <?php $user = getUser($this->session->userdata('id'));
                                if(!empty($user)){
                                ?>    
                                   <?php echo ucwords($user->full_name);?>
                                <?php }else{ echo 'Admin';} ?>  <b class="caret"></b>  
                            
                               <!-- <span class="text-muted text-xs block"><?php echo lang('Admin');?> <b class="caret"></b></span> --> 
                               </span> 
                               </a>
                               <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="<?php echo site_url('admin/profile'); ?>"><?php echo lang('profile');?></a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo site_url('admin/password'); ?>"><?php echo lang('change_password');?></a></li>
                                
                            </ul>
                       
                    </li>
                        <li>
                            <a href="javascript:void(0)" onclick="logout()">
                                <span class="btn btn-sm btn-danger">
                                    <i class="fa fa-sign-out"></i> <?php echo lang('logout');?>
                                </span>
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>
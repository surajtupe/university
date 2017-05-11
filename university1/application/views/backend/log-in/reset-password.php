<!DOCTYPE html>
<html style="background-color:#191f26!important;">
    <head>
        <style>
    .alert{
        padding: 4px;
        margin-bottom: 10px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
   
    .alert-dismissable .close {
        position: relative;
        top: -2px;
        right: 3px;
        color: inherit;
        z-index: 999;
    }
    </style>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content=" - admin panel.">
        <meta name="author" content="Anuj Tyagi" ><!--		Developed by Anuj Tyagi-->
        <title><?php echo (isset($title)) ? $title : $global['site_title']; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url(); ?>media/backend/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <!-- Theme style -->
        <link href="<?php echo base_url(); ?>media/backend/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        
        <link href="<?php echo base_url(); ?>media/backend/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
          <!-- Ionicons -->
        <link href="<?php echo base_url(); ?>media/backend/css/ionicons.min.css" rel="stylesheet" type="text/css" />

      
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="background-color:#191f26!important;">     
        <?php
        $msg = $this->session->userdata('msg');
        $this->session->set_userdata('msg', '');
        $msg_success = $this->session->userdata('msg_success');
        $this->session->set_userdata('msg_success', '');
        ?> 
        <div id="loader_img" style="text-align: center;">
            <img src="<?php echo base_url()?>media/backend/img/loader7.gif" title="Admin">
        </div>
        <div class="form-box"  id="login-box" style="display: none;">
        
            <?php if (isset($msg) && ($msg != '')) { ?>

                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $msg; ?>
                </div>
            <?php } ?>
            <?php if (isset($msg_success) && ($msg_success != '')) { ?>

                <div class="alert alert-success alert-dismissable">
                    <i class="fa fa-check"></i>
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $msg_success; ?>
                </div>
            <?php } ?>
            <div class="header" >Admin Reset Password</div>
            <form action="<?php echo base_url(); ?>backend/reset-password/<?php echo isset($code)?$code:'0';?>" method="post" name="frm_admin_reset" id="frm_admin_reset" >
                <div class="body bg-gray">
                    <div class="form-group">
                        <input  class="form-control" autofocus type="password" name="user_password" size="" id="user_password" value="" placeholder="Password" >                    </div>
                    <div class="form-group">
                        <input  type="password" class="form-control" name="confim_user_password" id="confim_user_password" value="" placeholder="Confirm Password" >      
                    </div>          

                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Reset Now</button> 

                    <p><a href="<?php echo base_url(); ?>backend/login">Login</a></p>

                </div>
            </form>
        </div>
        <script src="<?php echo base_url(); ?>media/backend/js/jquery-2.0.2.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url(); ?>media/backend/js/bootstrap.min.js" type="text/javascript"></script>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/jquery.validate.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/login/admin-reset.js"></script>
<script>
     $(document).ready(function()
     {
         setTimeout(function()
         {
             $("#login-box").show();
             $("#loader_img").hide();
             
         },1000);
         
     });
 </script>
    </body>
</html>
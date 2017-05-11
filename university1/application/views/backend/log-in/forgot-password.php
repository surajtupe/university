<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content=" - admin panel.">
        <meta name="author" content="Anuj Tyagi" ><!--		Developed by Anuj Tyagi-->
        <title><?php echo (isset($title)) ? $title : $global['site_title']; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url(); ?>media/backend/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url(); ?>media/backend/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url(); ?>media/backend/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="bg-black">
        <?php
        $msg = $this->session->userdata('msg');
        $this->session->set_userdata('msg', '');
        $msg_success = $this->session->userdata('msg_success');
        $this->session->set_userdata('msg_success', '');
        ?> 
        <div class="form-box" id="login-box">
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
            <div class="header">Forgot Password</div>
            <form name="frm_admin_forgot_password" id="frm_admin_forgot_password" action="<?php echo base_url(); ?>backend/forgot-password" method="post">
                <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url(); ?>" />
                <div class="body bg-gray">
                    <div class="form-group">
                        <input  class="form-control" type="text" name="user_email" size="" id="user_email" value="" placeholder="Email">
                    </div>
                </div>			
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Submit</button>  

                    <p><a href="<?php echo base_url(); ?>backend/login">Back To Login</a></p>

                </div>
            </form>


        </div>



        <script src="<?php echo base_url(); ?>media/backend/js/jquery-2.0.2.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url(); ?>media/backend/js/bootstrap.min.js" type="text/javascript"></script>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/jquery.validate.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/login/forgot-password.js"></script>


    </body>
</html> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php
            if ($site_title != '') {
                echo $site_title;
            } else if ($header['title'] != "") {
                echo $header['title'];
            } elseif ($global['site_title'] != "") {
                echo $global['site_title'];
            } else {
                echo base_url();
            }
            ?>
        </title>
        <link href="<?php echo base_url() ?>media/front/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>media/front/css/main.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>media/front/css/responsive.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>media/front/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>media/front/css/owl.carousel.css" rel="stylesheet">

        <script src="<?php echo base_url() ?>media/front/js/jquery.js"></script> 
        <script src="<?php echo base_url() ?>media/front/js/jquery-v2.1.3.js"></script> 
        <script src="<?php echo base_url() ?>media/front/js/bootstrap.min.js"></script> 
        <script src="<?php echo base_url() ?>media/front/js/owl.carousel.min.js"></script> 
        <script src="<?php echo base_url() ?>media/front/js/html5shiv.js"></script>
        <script src="<?php echo base_url() ?>media/front/js/respond.js"></script>
        <script src="<?php echo base_url() ?>media/front/js/jquery.validate.js"></script>
        <script src="<?php echo base_url() ?>media/front/js/jquery.validate.password.js"></script>
        <script src="<?php echo base_url() ?>media/front/js/validation.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/front/js/custom.js"></script>
        <script type="text/javascript">
            var javascript_site_path = "<?php echo base_url(); ?>";
            $(document).ready(function(e) {
                jQuery('.close').click(function() {
                    $(this).parent('div').slideUp('slow');
                });
            });

        </script>

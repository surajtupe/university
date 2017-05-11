<!-- header logo: style can be found in header.less -->
<header class="header">
    <a href="<?php echo base_url(); ?>backend/dashboard" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <?php echo stripslashes($global['site_title']); ?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?php echo $user_account['user_name']; ?><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a class="btn btn-primary btn-flat" href="<?php echo base_url(); ?>backend/admin/profile"><?php echo $user_account['first_name'] . " " . $user_account['last_name']; ?></a>
                                <a class="btn btn-primary btn-flat" href="<?php echo base_url(); ?>backend/admin/edit-profile"> Update</a>
                                <a href="<?php echo base_url(); ?>backend/log-out" class="btn btn-warning btn-flat">Logout</a>
                            </div>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">		
    <style>
        .second{
            margin-left:1px;
        }
    </style>		
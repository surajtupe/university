<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Admin Profile Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Admin Profile</li>

        </ol>
    </section>
    <section class="content">
       
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <p class="lead">My Profile</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody><tr>
                                    <th style="width:50%">User Name:</th>
                                    <td><?php echo $arr_admin_detail['user_name']; ?> </td>
                                </tr>
                                <tr>
                                    <th>First Name :</th>
                                    <td> <?php echo $arr_admin_detail['first_name']; ?> </td>
                                </tr>
                                <tr>
                                    <th>Last Name :</th>
                                    <td><?php echo $arr_admin_detail['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email Id:</th>
                                    <td><?php echo $arr_admin_detail['user_email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Role:</th>
                                    <td><?php echo $arr_admin_detail['role_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Registered Date:</th>
                                    <td><?php echo date($global['date_format'], strtotime($arr_admin_detail['register_date'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td>
                                        <?php
                                        if ($arr_admin_detail['gender'] == 1) {
                                            echo "Male";
                                        } else if ($arr_admin_detail['gender'] == 2) {
                                            echo "Female";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>
    <?php $this->load->view('backend/sections/footer'); ?>

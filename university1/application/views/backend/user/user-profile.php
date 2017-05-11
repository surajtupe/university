<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            User Profile Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>backend/user/list"><i class="fa fa-fw fa-user"></i> Manage Users</a></li>
            <li class="active">User Profile</li>

        </ol>
    </section>
    <section class="content">
        <?php
        $msg = $this->session->userdata('msg');
        ?>
        <?php if ($msg != '') { ?>
            <div class="msg_box alert alert-success">
                <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">X</button>
                <?php
                echo $msg;
                $this->session->unset_userdata('msg');
                ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <p class="lead">User Profile</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width:50%">First Name :</th>
                                    <td><?php echo $arr_user_detail['first_name'] ? $arr_user_detail['first_name'] : 'Not Available'; ?> </td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Middle Name :</th>
                                    <td><?php echo $arr_user_detail['middle_name'] ? $arr_user_detail['middle_name'] : 'Not Available'; ?> </td>
                                </tr>
                                <tr>
                                    <th>Last Name  :</th>
                                    <td><?php echo $arr_user_detail['last_name'] ? $arr_user_detail['last_name'] : 'Not Available'; ?> </td>
                                </tr>
                                <tr>
                                    <th>Mobile Number:</th>
                                    <td><?php echo $arr_user_detail['phone_number'] ? $arr_user_detail['phone_number'] : 'Not Available'; ?> </td>
                                </tr>
                                <tr>
                                    <th>Email Id:</th>
                                    <td><?php echo $arr_user_detail['user_email'] ? $arr_user_detail['user_email'] : 'Not Available'; ?> </td>
                                </tr>
                                <tr>
                                    <th>Date Of Birth:</th>
                                    <td><?php echo $arr_user_detail['user_birth_date'] ? $arr_user_detail['user_birth_date'] : 'Not Available'; ?> </td>
                                </tr>
                                <tr>
                                    <th>Registered Date:</th>
                                    <td><?php echo date($global['date_format'], strtotime($arr_user_detail['register_date'])); ?></td>
                                </tr>
                                <?php if (isset($arr_user_detail['about_me']) && $arr_user_detail['about_me'] != '') {
                                    ?>
                                    <tr>
                                        <th>About Me:</th>
                                        <td><?php echo(isset($arr_user_detail['about_me'])) ? $arr_user_detail['about_me'] : '-'; ?></td>
                                    </tr>
                                <?php } ?>	
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>
    <?php $this->load->view('backend/sections/footer'); ?>

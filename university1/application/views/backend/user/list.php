<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Users Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-fw fa-user"></i>Manage Users</li>

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

        <script type="text/javascript">
            function changeStatus(user_id, user_status)
            {
                /* changing the user status*/
                var obj_params = new Object();
                obj_params.user_id = user_id;
                obj_params.user_status = user_status;
                jQuery.post("<?php echo base_url(); ?>backend/user/change-status", obj_params, function(msg) {
                    if (msg.error == "1")
                    {
                        alert(msg.error_message);
                    }
                    else
                    {
                        /* togling the bloked and active div of user*/
                        if (user_status == 2)
                        {
                            $("#blocked_div" + user_id).css('display', 'inline-block');
                            $("#active_div" + user_id).css('display', 'none');
                        }
                        else
                        {
                            $("#active_div" + user_id).css('display', 'inline-block');
                            $("#blocked_div" + user_id).css('display', 'none');
                        }
                    }
                }, "json");

            }
            function changeEmailVerified(user_id, user_email_verified)
            {
                /* changing the user status*/
                var obj_params = new Object();
                obj_params.user_id = user_id;
                obj_params.user_email_verified = user_email_verified;
                jQuery.post("<?php echo base_url(); ?>backend/user/change-status-email", obj_params, function(msg) {
                    if (msg.error == "1")
                    {
                        alert(msg.error_message);
                    }
                    else
                    {
                        /* togling the bloked and active div of user*/
                        if (user_email_verified == 1)
                        {
                            location.reload();
                            $("#active_div_email" + user_id).css('display', 'inline-block');
                            $("#inactive_div_email" + user_id).css('display', 'none');
                        }
                        else
                        {
                            location.reload();
                            $("#inactive_div_email" + user_id).css('display', 'inline-block');
                            $("#active_div_email" + user_id).css('display', 'none');
                        }
                    }
                }, "json");

            }
        </script>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form name="frm_admin_users" id="frm_admin_users" action="<?php echo base_url(); ?>backend/user/list" method="post">
                        <div class="box-body table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                                <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th> <center>
                                        <?php
                                        if (count($arr_user_list) > 0) {
                                            ?>
                                            <center>
                                                Select <br>
                                                <input type="checkbox" name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
                                            </center>
                                        <?php } ?>
                                    </center>
                                    </th>
                                    <!--<th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Username">Username</th>-->

                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Mobile Number">Mobile Number</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="First Name">Email Address</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Status">Status</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Created on">Created on</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Email Verified">Email Verified</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Reverification Link">Reverification Link</th>

                                    <th  role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Action">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        foreach ($arr_user_list as $user) {
                                            ?>
                                            <tr>
                                                <td ><center>
                                            <input name="checkbox[]" class="case" type="checkbox" id="checkbox[]" value="<?php echo $user['user_id']; ?>" />
                                        </center></td>
                                        <td ><?php echo stripslashes($user['phone_number']); ?></td>
                                        <td ><?php echo $user['user_email'] ? stripslashes($user['user_email']) : 'Not Available'; ?></td>
                                        <td ><?php
                                            if ($user['user_id'] != 1) {
                                                if ($user['user_status'] == 0) {
                                                    ?>
                                                    <div> <a style="cursor:default;" class="label label-warning" href="javascript:void(0);" id="status_<?php echo $user['user_id']; ?>">Inactive</a> </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div id="active_div<?php echo $user['user_id']; ?>"  <?php if ($user['user_status'] == 1) { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>>
                                                        <a class="label label-success" title="Click to Change Status" onClick="changeStatus('<?php echo $user['user_id']; ?>', 2);" href="javascript:void(0);" id="status_<?php echo $user['user_id']; ?>">Active</a>
                                                    </div>

                                                    <div id="blocked_div<?php echo $user['user_id']; ?>" <?php if ($user['user_status'] == 2) { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?> >

                                                        <a class="label label-danger" title="Click to Change Status" onClick="changeStatus('<?php echo $user['user_id']; ?>', 1);" href="javascript:void(0);" id="status_<?php echo $user['user_id']; ?>">Blocked</a>
                                                    </div>

                                                    <?php
                                                }
                                                ?>
                                                <?php
                                            } else {
                                                ?>
                                                <div id="active_div"> <a class="label label-success" style="cursor:default;" title="" href="javascript:void(0);" id="status">Active</a> </div>
                                                <?php
                                            }
                                            ?>
                                        </td>


                                        <td ><?php echo date($global['date_format'], strtotime($user['register_date'])); ?></td>
                                        <td ><div id="active_div_email<?php echo $user['user_id']; ?>"  <?php if ($user['email_verified'] == 1) { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>>
                                                <a class="label label-success" title="Click to Change Status" onClick="changeEmailVerified('<?php echo $user['user_id']; ?>', 0);" href="javascript:void(0);" id="status_<?php echo $user['user_id']; ?>">Verified</a>
                                            </div>

                                            <div id="inactive_div_email<?php echo $user['user_id']; ?>" <?php if ($user['email_verified'] == 0) { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?> >

                                                <a class="label label-danger" title="Click to Change Status" onClick="changeEmailVerified('<?php echo $user['user_id']; ?>', 1);" href="javascript:void(0);" id="status_<?php echo $user['user_id']; ?>">Not verified</a>
                                            </div>

                                        </td>


                                        <td><a href="<?php echo base_url(); ?>backend/user/reverification-link/<?php echo base64_encode($user['user_id']); ?>/<?php echo $user['user_type']; ?>">Resend Link</a></td>
                                        <td class="worktd">
                                            <a class="btn btn-info" title="Edit User Details" href="<?php echo base_url(); ?>backend/user/edit/<?php echo base64_encode($user['user_id']); ?>"> <i class="icon-edit icon-white"></i>Edit</a> 
                                            <a class="btn btn-primary" title="View User Details" href="<?php echo base_url(); ?>backend/user/view/<?php echo base64_encode($user['user_id']); ?>"> <i class="icon-edit icon-white"></i>View</a>
                                            <?php if ($user['user_email'] != '') { ?>
                                                <a class="btn btn-info" title="User Address Details" href="<?php echo base_url(); ?>backend/user/address/current-address-view/<?php echo base64_encode($user['user_id']); ?>">  <i class="icon-edit icon-white"></i>Address</a>
                                            <?php } ?>
                                        </td>


                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                    <?php
                                    if (count($arr_user_list) > 0) {
                                        ?>
                                        <tfoot>
                                        <th colspan="9"><input type="submit" id="btn_delete_all" name="btn_delete_all" class="btn btn-danger" onClick="return deleteConfirm();"  value="Delete Selected"></th>
                                        </tfoot>
                                    <?php }
                                    ?>
                                </table>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>
					
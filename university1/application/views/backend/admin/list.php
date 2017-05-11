<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Admin Users Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">  <i class="fa fa-fw fa-user"></i>Manage Admin Users</li>
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
                <div class="box">
                    <form name="frm_admin_users" id="frm_admin_users" action="<?php echo base_url(); ?>backend/admin/list" method="post">
                        <div class="box-body table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                                <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th> <center>
                                        Select <br>
                                        <?php
                                        if (count($arr_admin_list) > 1) {
                                            ?>
                                            <input type="checkbox"  name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
                                        <?php } ?>
                                    </center></th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Username">Username</th>

                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="First Name">First Name</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Last Nam">Last Name</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Email Id">Email Id</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Role">Role</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Status">Status</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Created on">Created on</th>
                                    <th  role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Action">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        foreach ($arr_admin_list as $admin) {
                                            ?>
                                            <tr>
                                                <td ><?php
                                                    if ($admin['role_id'] != 1) {
                                                        ?>
                                            <center>
                                                <input name="checkbox[]" class="case" type="checkbox" id="checkbox[]" value="<?php echo $admin['user_id']; ?>" />
                                            </center>
                                            <?php
                                        }
                                        ?></td>
                                        <td ><?php echo stripslashes($admin['user_name']); ?></td>
                                        <td ><?php echo stripslashes($admin['first_name']); ?></td>
                                        <td ><?php echo stripslashes($admin['last_name']); ?></td>
                                        <td ><?php echo stripslashes($admin['user_email']); ?></td>
                                        <td ><?php echo stripslashes($admin['role_name']); ?></td>
                                        <td ><?php
                                            if ($admin['role_id'] != 1) {
                                                if ($admin['user_status'] == 0) {
                                                    ?>
                                                    <div> <a style="cursor:default;" class="label label-warning" href="javascript:void(0);" id="status_<?php echo $admin['user_id']; ?>">Inactive</a> </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div id="active_div<?php echo $admin['user_id']; ?>"  <?php if ($admin['user_status'] == 1) { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>">
                                                        <a class="label label-success" title="Click to Change Status" onClick="changeStatus('<?php echo $admin['user_id']; ?>', 2);" href="javascript:void(0);" id="status_<?php echo $admin['user_id']; ?>">Active</a>
                                                    </div>

                                                    <div id="blocked_div<?php echo $admin['user_id']; ?>" <?php if ($admin['user_status'] == 2) { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?> >

                                                        <a class="label label-danger" title="Click to Change Status" onClick="changeStatus('<?php echo $admin['user_id']; ?>', 1);" href="javascript:void(0);" id="status_<?php echo $admin['user_id']; ?>">Blocked</a>
                                                    </div>

                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <div id="active_div"> <a class="label label-success" style="cursor:default;" title="" href="javascript:void(0);" id="status">Active</a> </div>
                                                <?php
                                            }
                                            ?>
                                        </td>

                                        <td ><?php echo date($global['date_format'], strtotime($admin['register_date'])); ?></td>
                                        <td class=""><?php
                                            if ($admin['role_id'] == 1) {
                                                $user_account = $this->session->userdata('user_account');
                                                if ($admin['user_id'] == $user_account['user_id']) {
                                                    ?>
                                                    <a class="btn btn-info" title="Edit Admin User Details" href="<?php echo base_url(); ?>backend/admin/edit/<?php echo base64_encode($admin['user_id']); ?>"> <i class="icon-edit icon-white"></i>Edit</a>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a class="btn btn-info" title="Edit Admin User Details" href="<?php echo base_url(); ?>backend/admin/edit/<?php echo base64_encode($admin['user_id']); ?>"> <i class="icon-edit icon-white"></i>Edit</a>
                                                <?php
                                            }
                                            ?></td>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                    <?php
                                    if (count($arr_admin_list) > 1) {
                                        ?>
                                        <tfoot>
                                        <th colspan="9">
                                            <input type="submit" id="btn_delete_all" name="btn_delete_all" class="btn btn-danger" onClick="return deleteConfirm();"  value="Delete Selected">
                                            <a id="add_new_admin" name="add_new_admin" href="<?php echo base_url(); ?>backend/admin/add" class="btn btn-primary pull-right" >Add New Admin </a>
                                        </th>
                                        </tfoot>
                                        <?php
                                    }
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

        <script>
            function changeStatus(user_id, user_status)
            {
                /* changing the user status*/
                var obj_params = new Object();
                obj_params.user_id = user_id;
                obj_params.user_status = user_status;
                jQuery.post("<?php echo base_url(); ?>backend/admin/change-status", obj_params, function (msg) {
                    if (msg.error == "1")
                    {
                        alert(msg.error_message);
                    }
                    else
                    {
                        /* toogling the bloked and active div of user*/
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
        </script>
        </body>
        </html>
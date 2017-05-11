<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Contact Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-fw fa-phone"></i> Manage Contacts</li>
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
                jQuery.post("<?php echo base_url(); ?>backend/user/change-status", obj_params, function (msg) {
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
                jQuery.post("<?php echo base_url(); ?>backend/user/change-status-email", obj_params, function (msg) {
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
                    <div class="box-body table-responsive">
                    <form name="frm_contact" id="frm_contact" action="<?php echo base_url(); ?>backend/contact-us" method="post">
                         
                        <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                            <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th> <center>
                                    <?php
                                    if (count($arr_contact_us) > 0) {
                                        ?>
                                        <center>
                                            Select <br>
                                            <input type="checkbox" name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
                                        </center>
                                    <?php } ?>
                                </center>
                                </th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Sender Name">Sender Name</th>

                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Sender Email">Sender Email</th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Date">Date</th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Status">Status</th>
                                <th  role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php
                            $cnt = 0;
                            foreach ($arr_contact_us as $contact) {
                                $cnt++;
                                ?>
                                <tr>
                                    <td>
                                        <center>
                                            <input value="<?php echo $contact['contact_id']; ?>" name="contact_ids[]" class="case" type="checkbox" id="checkbox">
                                        </center>
                                    </td>
                                    <td><?php echo ucfirst($contact['name']); ?></td>
                                    <td><?php echo $contact['mail_id']; ?></td>
                                    <td><?php echo date($global['date_format'], strtotime($contact['date'])); ?></td>
                                    <td><?php
                                        if ($contact['reply_status'] == '0') {
                                            echo 'Not replied';
                                        } else {
                                            echo 'Replied';
                                        }
                                        ?></td>
                                    <td class=""><a title="View" class="btn btn-info" href="<?php echo base_url(); ?>backend/contact-us/view/<?php echo base64_encode($contact['contact_id']); ?>"> <i class="icon-edit icon-white"></i>View</a></td>
                                    <?php
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                    <?php if ($cnt > 0) { ?>
                                    <th colspan="6">
                                    <input type="submit" onclick="return deleteConfirm();" id="btn_delete_all" class="btn btn-danger" value="Delete Selected">    
                                    </th>
                                    <?php } ?>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                    </div>

                </div>
            </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>

       
<?php 
//echo "<pre>";print_r($arr_newsletter_list);die;

$this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
           Manage Newsletter Subscribers
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manage Newsletter Subscribers</li>

        </ol>
    </section>
    <section class="content">
        <?php
        $msg = $this->session->userdata('msg');
        $msg_error = $this->session->userdata('msg_error');
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
        <?php if ($msg_error != '') { ?>
            <div class="msg_box alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">X</button>
                <?php
                echo $msg_error;
                $this->session->unset_userdata('msg_error');
                ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!DOCTYPE html>   
                    <form name="frm_newsletter" id="frm_newsletter" action="<?php echo base_url(); ?>backend/newsletter-subscriber/list" method="post">
                       <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">	
                             <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th> <center>
                                    Select <br>
                                    <?php
                                    if (count($arr_newsletter_list) > 0) {
                                        ?>
                                        <input type="checkbox" onchange="selectAll();" name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
                                    <?php } ?>
                                </center></th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="User Email">User Email</th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Subscribe Status">Subscribe Status</th>
                                </tr>
                                </thead>  
                                <tbody>
                                    <?php
                                    if (count($arr_newsletter_list) > 0) {
                                    foreach ($arr_newsletter_list as $newsletter) { ?>
                                        <tr>
                                            <td >
                                    <center><input name="checkbox[]" class="case" type="checkbox" id="checkbox[]" value="<?php echo $newsletter['newsletter_subscription_id']; ?>" /></center>
                                    </td>
                                    <td ><?php echo stripslashes($newsletter['user_email']); ?></td>
                                    <td >


                                        <div id="active_div<?php echo $newsletter['newsletter_subscription_id']; ?>"  <?php if ($newsletter['subscribe_status'] == 'Active') { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>>
                                            <a class="label label-success" title="Click to Change Status" onClick="changeStatus('<?php echo $newsletter['newsletter_subscription_id']; ?>', 'Inactive');" href="javascript:void(0);" id="status_<?php echo $newsletter['newsletter_subscription_id']; ?>">Active</a>
                                        </div>

                                        <div id="blocked_div<?php echo $newsletter['newsletter_subscription_id']; ?>" <?php if ($newsletter['subscribe_status'] == 'Inactive') { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?> >
                                            <a class="label label-warning" title="Click to Change Status" onClick="changeStatus('<?php echo $newsletter['newsletter_subscription_id']; ?>', 'Active');" href="javascript:void(0);" id="status_<?php echo $newsletter['newsletter_subscription_id']; ?>">Inactive</a>
                                        </div>

                                    </td>

                                    <?php }} ?>
                                </tbody>
                                <?php
                                if (count($arr_newsletter_list) > 0) {
                                    ?>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" > 
                                                <input type="submit" value="Delete Selected" onclick="return deleteConfirm();" class="btn btn-danger" name="btn_delete_all" id="btn_delete_all">
                                            </th>
                                        </tr>
                                    <?php } ?> 
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        </div>
        <script>
            function changeStatus(newsletter_subscription_id, subscribe_status)
            {
                var obj_params = new Object();
                obj_params.newsletter_subscription_id = newsletter_subscription_id;
                obj_params.subscribe_status = subscribe_status;
                jQuery.post("<?php echo base_url(); ?>backend/subscriber-newsletter/change-status", obj_params, function (msg) {
                    if (msg.error == "1")
                    {
                        alert(msg.error_message);
                    }
                    else
                    {
                        if (subscribe_status == 'Inactive')
                        {
                            $("#blocked_div" + newsletter_subscription_id).css('display', 'inline-block');
                            $("#active_div" + newsletter_subscription_id).css('display', 'none');
                        }
                        else
                        {
                            $("#active_div" + newsletter_subscription_id).css('display', 'inline-block');
                            $("#blocked_div" + newsletter_subscription_id).css('display', 'none');
                        }

                    }
                }, "json");

            }
        </script>
        </body>

        </html>

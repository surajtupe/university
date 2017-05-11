<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Newsletters Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"> <i class="fa fa-fw fa-envelope"></i>Manage  Newsletters</li>

        </ol>
    </section>
    <section class="content">
        <?php
        $alert_msg = $this->session->userdata('newsletter_msg');
        $class_type = 'success';
        switch ($alert_msg['msg_type']) {
            case 'error':
                $class_type = 'danger';
                break;
            case 'alert':
                $class_type = 'warning';
                break;
            case 'success':
                $class_type = 'success';
                break;
        }
        if ($alert_msg['newsletter_msg_val'] != '') {
            ?>
            <div class="msg_box alert alert-success">
                <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">X</button>
                <?php
                echo $alert_msg['newsletter_msg_val'];
                $this->session->unset_userdata('newsletter_msg');
                ?>
            </div>
<?php } ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                            <form name="frm_newsletter" id="frm_newsletter" action="<?php echo base_url(); ?>backend/newsletter/list" method="post">
                                <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                    <thead>

                                    <th> 
                                        <?php
                                        if (count($arr_newsletter_list) > 0) {
                                            ?>
                                        <center>
                                            Select <br>
                                            <input type="checkbox" name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
                                        </center>
                                        <?php
                                    }
                                    ?>
                                    </th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="News letter title">News letter title</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Status">Status</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Created on">Created on</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Updated on">Updated on</th>
                                    <th role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>

                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($arr_newsletter_list as $newsletter) {
                                            ?>
                                            <tr>
                                                <td ><center>
                                            <input name="checkbox[]" class="case" type="checkbox" id="checkbox[]" value="<?php echo $newsletter['newsletter_id']; ?>" />
                                        </center></td>
                                        <td ><?php echo stripslashes($newsletter['newsletter_subject']); ?></td>
                                        <td ><?php
                                            if ($newsletter['newsletter_status'] == 0) {
                                                ?>
                                                <div id="inactive_div<?php echo $newsletter['newsletter_id']; ?>"> <a class="label label-warning" title="Click to Change Status" onClick="changeStatus('<?php echo $newsletter['newsletter_id']; ?>', 1);" href="javascript:void(0);" id="status_<?php echo $newsletter['newsletter_id']; ?>">Inactive</a> </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div id="active_div<?php echo $newsletter['newsletter_id']; ?>"  <?php if ($newsletter['newsletter_status'] == 1) { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>>
                                                    <a class="label label-success" title="Click to Change Status" onClick="changeStatus('<?php echo $newsletter['newsletter_id']; ?>', 0);" href="javascript:void(0);" id="status_<?php echo $newsletter['newsletter_id']; ?>">Active</a>
                                                </div>

                                                <?php
                                            }
                                            ?>
                                        </td>


                                        <td ><?php echo date($global['date_format'], strtotime($newsletter['add_date'])); ?></td>
                                        <td ><?php echo ($newsletter['update_date'] != '0000-00-00') ? date($global['date_format'], strtotime($newsletter['update_date'])) : 'Not Updated'; ?></td>
                                        <td class=""><a class="btn btn-info" title="Edit Newsletter Details" href="<?php echo base_url(); ?>backend/newsletter/edit/<?php echo $newsletter['newsletter_id']; ?>"> <i class="icon-edit icon-white"></i>Edit</a>
                                            <?php if ($newsletter['newsletter_status'] == 1) { ?>
                                                <a class="btn btn-primary" title="Send Newsletter" href="<?php echo base_url(); ?>backend/send-newsletter/<?php echo $newsletter['newsletter_id']; ?>"> <i class="icon-envelope icon-white"></i>Send</a>
                                        <?php } ?></td>
<?php } ?>
                                    </tbody>
                                    </tr>
                                    <?php
                                    if (count($arr_newsletter_list) > 0) {
                                        ?>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6" > 
                                                    <input type="submit" value="Delete Selected" onclick="return deleteConfirm();" class="btn btn-danger" name="btn_delete_all" id="btn_delete_all">
                                                    <a  class="btn btn-primary  pull-right" href="<?php echo base_url(); ?>backend/newsletter/add"> Add New Newsletter</a>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    <?php }else{ ?>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="6" > 
                                                         <a  class="btn btn-primary  pull-right" href="<?php echo base_url(); ?>backend/newsletter/add"> Add New Newsletter</a>
                                                    </th>
                                                </tr>
                                             </tfoot>
                                    <?php }?>
                                </table>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        <script type="text/javascript">
            function changeStatus(newsletter_id, status)
            {
                var obj_params = new Object();
                obj_params.newsletter_id = newsletter_id;
                obj_params.status = status;
                jQuery.post("<?php echo base_url(); ?>backend/newsletter/change-status", obj_params, function (msg) {
                    window.location.reload();
                }, "json");
            }
        </script>
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Organization Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manage  Organization</li>

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
                    <div class="box-body table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                            <form method="post" action="<?php echo base_url(); ?>backend/organization/list" name="frm_list_organization" id="frm_list_organization">
                            <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th> <center>
                                    Select <br>
                                    <?php
                                    if (count($organization_list) > 1) {
                                        ?>
                                        <input type="checkbox" name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
                                    <?php } ?>
                                </center></th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Question">Organization Name</th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Question">Primary Email</th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Question">Mobile Number</th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Question">Status</th>
                                <th  class="wid-100" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 0;
                                    foreach ($organization_list as $organization) {
                                        $cnt++;
                                        ?>
                                        <tr>
                                            <td >
                                    <center>
                                        <input name="checkbox[]" class="case" type="checkbox" id="checkbox[]" value="<?php echo $organization['orgnisation_id']; ?>" />
                                    </center>
                                    </td>
                                    <td><?php echo stripslashes($organization['orgnisation_name']); ?></td>
                                    <td><?php echo stripslashes($organization['primary_email']); ?></td>
                                    <td><?php echo stripslashes($organization['mobile_number']); ?></td>
                                    <td><?php
                                        switch ($organization['status']) {
                                            case 'Active':
                                                $class = 'label-success';
                                                $status = $organization['status'];
                                                $status_to_change = 'Inactive';
                                                break;
                                            case 'Inactive':
                                                $class = 'label-warning';
                                                $status = $organization['status'];
                                                $status_to_change = 'Active';
                                                break;
                                        }
                                        ?>
                                        <div  id="activeDiv<?php echo $organization['orgnisation_id']; ?>" <?php if ($organization['status'] == "1") { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>>
                                            <a class="label label-success" title="Click To Change Status" onClick="changeStatus('<?php echo $organization['orgnisation_id']; ?>', '0');" href="javascript:void(0);" id="status_<?php echo $organization['orgnisation_id']; ?>">Active</a>
                                        </div>

                                        <div id="inActiveDiv<?php echo $organization['orgnisation_id']; ?>" <?php if ($organization['status'] == "0") { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>>

                                            <a class="label label-warning" title="Click To Change Status" onClick="changeStatus('<?php echo $organization['orgnisation_id']; ?>', '1');" href="javascript:void(0);" id="status_<?php echo $organization['orgnisation_id']; ?>">Inactive</a>
                                        </div>
                                    </td>
                                    <td class=""><a class="btn btn-info" href="<?php echo base_url(); ?>backend/organization/add/<?php echo base64_encode($organization['orgnisation_id']); ?>"> <i class="icon-edit icon-white"></i>Edit</a></td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6"><input type="submit" value="Delete Selected"  class="btn btn-danger" name="btnDeleteAll" id="btnDeleteAll">
                                            <a  class="btn btn-primary  pull-right" href="<?php echo base_url(); ?>backend/organization/add"> Add New Organization</a>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>

        <script type="text/javascript">
            function changeStatus(orgnisation_id, status)
            {
                var objParams = new Object();
                objParams.orgnisation_id = orgnisation_id;
                objParams.status = status;
                jQuery.post("<?php echo base_url(); ?>backend/organization/change-status", objParams, function(msg) {
                    if (msg.error == "1")
                    {
                        alert(msg.errorMessage);
                    } else {
                        if (status == '0') {
                            $("#inActiveDiv" + orgnisation_id).css('display', 'inline-block');
                            $("#activeDiv" + orgnisation_id).css('display', 'none');

                        } else {
                            $("#activeDiv" + orgnisation_id).css('display', 'inline-block');
                            $("#inActiveDiv" + orgnisation_id).css('display', 'none');
                        }
                    }
                }, "json");
            }

            jQuery("#btnDeleteAll").bind("click", function() {
                var flag = 0;
                jQuery(".case").each(function(index, element) {
                    if (jQuery(".case").is(":checked"))
                        flag = 1;
                });

                if (flag)
                {
                    if (confirm("Are you sure to delete these Records?"))
                    {
                        var document_ids = [];
                        jQuery(".case").each(function(index, element) {
                            if (jQuery(element).is(":checked"))
                                document_ids.push(jQuery(element).val());
                        });
                        var objParams = new Object();
                        objParams.document_ids = document_ids;
                        jQuery.post("<?php echo base_url(); ?>backend/document/delete", objParams, function(msg) {
                            if (msg.error == "1")
                            {
                                alert(msg.errorMessage);
                            }
                            else
                            {
                                location.href = location.href;
                            }
                        }, "json");
                    }
                }
                else
                {
                    alert("Please select atleast one record to delete.");
                }
            });
        </script> 
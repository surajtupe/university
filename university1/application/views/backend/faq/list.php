<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            FAQ's Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manage  FAQ's</li>

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
                            <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th> <center>
                                    Select <br>
                                    <?php
                                    if (count($arr_faqs) > 1) {
                                        ?>
                                        <input type="checkbox" name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
<?php } ?>
                                </center></th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Question">Question</th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Answer">Answer</th>
                                <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                    ?>	
                                    <th class="sorting_asc wid-100" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Language">Language</th>
<?php } ?>	
                                <th class="sorting_asc wid-100" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Status">Status</th>
                                <th  class="wid-100" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>

                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 0;
                                    foreach ($arr_faqs as $faq) {
                                        $cnt++;
                                        ?>
                                        <tr>
                                            <td >
                                    <center>
                                        <input name="checkbox[]" class="case" type="checkbox" id="checkbox[]" value="<?php echo $faq['faq_id']; ?>" />

                                    </center>
                                    </td>
                                    <td><?php echo stripslashes($faq['question']); ?></td>
                                    <td><?php echo stripslashes($faq['answer']); ?></td>
                                    <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                        ?>	
                                        <td><?php echo stripslashes($faq['lang_name']); ?></td>
                                        <?php } ?>	  
                                        <td><?php
                                                switch ($faq['status']) {
                                                    case 'Active':
                                                        $class = 'label-success';
                                                        $status = $faq['status'];
                                                        $status_to_change = 'Inactive';
                                                        break;
                                                    case 'Inactive':
                                                        $class = 'label-warning';
                                                        $status = $faq['status'];
                                                        $status_to_change = 'Active';
                                                        break;
                                                }
                                                ?>
                                                <div  id="activeDiv<?php echo $faq['faq_id']; ?>" <?php if ($faq['status'] == "Active") { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>>
                                                    <a class="label label-success" title="Click To Change Status" onClick="changeStatus('<?php echo $faq['faq_id']; ?>', 'Inactive');" href="javascript:void(0);" id="status_<?php echo $faq['faq_id']; ?>">Active</a>
                                                </div>

                                                <div id="inActiveDiv<?php echo $faq['faq_id']; ?>" <?php if ($faq['status'] == "Inactive") { ?> style="display:inline-block" <?php } else { ?> style="display:none;" <?php } ?>>

                                                    <a class="label label-warning" title="Click To Change Status" onClick="changeStatus('<?php echo $faq['faq_id']; ?>', 'Active');" href="javascript:void(0);" id="status_<?php echo $faq['faq_id']; ?>">Inactive</a>
                                                </div>
                                            </td>
                                    <td class=""><a class="btn btn-info" href="<?php echo base_url(); ?>backend/faqs/add/<?php echo base64_encode($faq['faq_id']); ?>"> <i class="icon-edit icon-white"></i>Edit</a></td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>

                                <tfoot>

                                    <tr>
                                        <th colspan="6"><input type="submit" value="Delete Selected"  class="btn btn-danger" name="btnDeleteAll" id="btnDeleteAll">
                                            <a  class="btn btn-primary  pull-right" href="<?php echo base_url(); ?>backend/faqs/add"> Add New FAQ</a>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
<?php $this->load->view('backend/sections/footer'); ?>

        <script type="text/javascript">

            function changeStatus(faq_id, status)
            {
                var objParams = new Object();
                objParams.faq_id = faq_id;
                objParams.status = status;
                jQuery.post("<?php echo base_url(); ?>backend/faqs/change-status", objParams, function (msg) {
                    if (msg.error == "1")
                    {
                        alert(msg.errorMessage);
                    }
                    else
                    {
                        if (status == 'Inactive')
                        {
                            $("#inActiveDiv" + faq_id).css('display', 'inline-block');
                            $("#activeDiv" + faq_id).css('display', 'none');

                        }
                        else
                        {
                            $("#activeDiv" + faq_id).css('display', 'inline-block');
                            $("#inActiveDiv" + faq_id).css('display', 'none');
                        }

                        location.href = location.href;
                    }
                }, "json");
            }
            jQuery("#btnDeleteAll").bind("click", function () {
                var flag = 0;
                jQuery(".case").each(function (index, element) {
                    if (jQuery(".case").is(":checked"))
                        flag = 1;
                });

                if (flag)
                {
                    if (confirm("Are you sure to delete these Records?"))
                    {
                        var arr_categories = [];
                        jQuery(".case").each(function (index, element) {
                            if (jQuery(element).is(":checked"))
                                arr_categories.push(jQuery(element).val());
                        });
                        var objParams = new Object();
                        objParams.faq_ids = arr_categories;
                        jQuery.post("<?php echo base_url(); ?>backend/faqs/delete", objParams, function (msg) {
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
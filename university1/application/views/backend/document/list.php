<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Documents Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manage  Documents</li>

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
                                    if (count($document_list) > 1) {
                                        ?>
                                        <input type="checkbox" name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
                                    <?php } ?>
                                </center></th>
                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Question">Document</th>
                                <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                    ?>	
                                    <th class="sorting_asc wid-100" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Language">Language</th>
                                <?php } ?>	
                                <th  class="wid-100" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 0;
                                    foreach ($document_list as $document) {
                                        $cnt++;
                                        ?>
                                        <tr>
                                            <td >
                                    <center>
                                        <input name="checkbox[]" class="case" type="checkbox" id="checkbox[]" value="<?php echo $document['document_id']; ?>" />
                                    </center>
                                    </td>
                                    <td><?php echo stripslashes($document['document_text']); ?></td>
                                    <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                        ?>	
                                        <td><?php echo stripslashes($document['lang_name']); ?></td>
                                    <?php } ?>	  
                                    <td class=""><a class="btn btn-info" href="<?php echo base_url(); ?>backend/document/add/<?php echo base64_encode($document['service_document_lang_id']); ?>"> <i class="icon-edit icon-white"></i>Edit</a></td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6"><input type="submit" value="Delete Selected"  class="btn btn-danger" name="btnDeleteAll" id="btnDeleteAll">
                                            <a  class="btn btn-primary  pull-right" href="<?php echo base_url(); ?>backend/document/add"> Add New Document</a>
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
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Email Template Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-fw fa-envelope"></i> Manage  Email Template</li>

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

                    <div class="box-body table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                            <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="ID">ID </th>
                                        <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Title">Title</th>

                                        <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Subject">Subject</th>
                                        <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                            ?>	
                                            <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Language">Language</th>
                                        <?php } ?>			
                                        <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Created on">Created on</th>
                                        <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Updated on">Updated on</th>

                                        <th  role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    <?php
                                    $cnt = 0;
                                    $i = 1;
                                    foreach ($arr_email_templates as $email_template) {
                                        $cnt++;
                                        ?>
                                        <tr>
                                            <td>#<?php echo $cnt; ?></td>
                                            <td><?php echo ucwords(str_replace("-", " ", $email_template['email_template_title'])); ?></td>
                                            <td><?php echo $email_template['email_template_subject']; ?></td>
                                            <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                                ?>	
                                                <td ><?php echo $email_template['lang_name']; ?></td>
                                            <?php } ?>	  
                                            <td><?php echo date($global['date_format'], strtotime($email_template['date_created'])); ?></td>
                                            <td><?php echo date($global['date_format'], strtotime($email_template['date_updated'])); ?></td>
                                            <td class=""><a class="btn btn-info" href="<?php echo base_url(); ?>backend/edit-email-template/<?php echo $email_template['email_template_id']; ?>" title="Edit Email Template"> <i class="icon-edit icon-white"></i>Edit</a></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>
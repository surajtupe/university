<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            CMS Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"> <i class="fa fa-fw fa-file-text"></i> Manage CMS Pages</li>

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
                                    <tr>
                                        <th width="10%">
                                            ID
                                        </th>
                                        <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Page Title">Page Title</th>
                                        <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Page Alias">Page Alias</th>

                                        <th role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($get_cms_list)) {
                                        $cnt = 1;
                                        foreach ($get_cms_list as $key => $value) {
                                            $cms_id = $value['cms_id'];
                                            $cms_page_alias = $value['page_alias'];
                                            $cms_page_title = $value['page_title'];
                                            $cms_page_content = $value['page_content'];
                                            $cms_page_status = $value['status'];
                                            ?>
                                            <tr>
                                                <td>#<?php echo stripslashes($cnt); ?></td>
                                                <td><?php echo stripslashes($cms_page_title); ?></td>
                                                <td><?php echo stripslashes($cms_page_alias); ?></td>
                                                <td class="center">
                                                    <a class="btn btn-info" href="<?php echo base_url(); ?>backend/cms/edit-cms/<?php echo $cms_id; ?>"><i class="icon-edit icon-white"></i>Edit</a>
                                                    <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                                        ?>	
                                                        <a class="btn btn-primary" href="<?php echo base_url(); ?>backend/cms/edit-cms-language/<?php echo $cms_id; ?>" title="Edit Global Settings Parameter for other Langauages">
                                                            <i class="icon-file icon-white"></i>Multi Language
                                                        </a>
                                                    <?php } ?>		 
                                                </td>
                                            </tr>
                                        <?php $cnt++;}
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
<?php $this->load->view('backend/sections/footer'); ?>
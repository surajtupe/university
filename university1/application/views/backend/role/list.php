<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Roles Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-fw fa-eye"></i> Manage Roles</li>

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
                    <form name="frm_roles" id="frm_roles" action="<?php echo base_url() ?>backend/role/list" method="post">
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                                <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th> <center>
                                        Select <br>
                                        <?php
                                        if (count($arr_roles) > 1) {
                                            ?>
                                            <input type="checkbox"  name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
                                        <?php } ?>
                                    </center></th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Role Name">Role Name</th>

                                    <th  role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" >Action</th>

                                    </tr>
                                    </thead>


                                    <tbody>
                                        <?php
                                        if (count($arr_roles) > 0) {
                                            foreach ($arr_roles as $roles) {
                                                ?>
                                                <tr>
                                                    <td width="10%">  <?php
                                                    if ($roles['role_id'] != 1) {
                                                        ?>
                                                            <center>
                                                                <input name="checkbox[]" class="case" type="checkbox" id="checkbox[]" value="<?php echo $roles['role_id']; ?>" />
                                                            </center>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo stripslashes($roles['role_name']); ?></td>


                                            <td class=""><?php
                                            if ($roles['role_id'] != 1) {
                                                ?>
                                                    <a class="btn btn-info" href="<?php echo base_url(); ?>backend/role/edit/<?php echo base64_encode($roles['role_id']); ?>" title="Edit Role"> <i class="icon-edit icon-white"></i>Edit</a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        <?php }
                                    } ?>

                                    </tbody>
                                    <tfoot>

                                    <th colspan="6" > 
                                        <input type="submit" value="Delete Selected" onclick="return deleteConfirm();" class="btn btn-danger" name="btn_delete_all" id="btn_delete_all">
                                        <a  class="btn btn-primary  pull-right" href="<?php echo base_url(); ?>backend/role/add"> Add New Role</a>
                                    </th></tr>
                                    </tfoot>
                                </table>		
                            </div><!-- /.box-body -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php $this->load->view('backend/sections/footer'); ?>
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            States Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"> <i class="fa fa-fw fa-home"></i>Manage  States</li>

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
                        <form name="frm_country" id="frm_country" action="<?php echo base_url(); ?>backend/delete-states" method="post">
                            <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                                <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th> <center>
                                        Select <br>
                                        <?php
                                        if (count($get_state_list) > 1) {
                                            ?>
                                            <input type="checkbox" name="check_all" id="check_all"  class="select_all_button_class" value="select all" />
<?php } ?>
                                    </center></th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Country Name">Country Name</th>
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="State Name">State Name</th>

                                    <th  role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $cnt = 0;
                                        if (count($get_state_list) > 0) {
                                            foreach ($get_state_list as $key => $value) {
                                                $cnt++;
                                                $state_id = $value['id'];
                                                $country_name = $value['country_name'];
                                                $state_name = $value['state_name'];
                                                ?>
                                                <tr>
                                                    <td ><center>
                                                <input name="checkbox[]" class="case" type="checkbox" id="checkbox[]"  value="<?php echo $state_id; ?>" />
                                            </center></td>
                                            <td><?php echo stripslashes($country_name); ?></td>
                                            <td><?php echo stripslashes($state_name); ?></td>
                                            <td class="center"><a class="btn btn-info" href="<?php echo base_url(); ?>backend/states/edit-states/<?php echo $state_id; ?>"><i class="icon-edit icon-white"></i>Edit</a>
                                                <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                                    ?>	
                                                    <a class="btn btn-primary" title="Multi Languge" href="<?php echo base_url(); ?>backend/state-change-language/<?php echo $state_id; ?>"><i class="icon-edit icon-white"></i>Multi Language</a>
                                                <?php } ?>
                                            </td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>

                                    <th colspan="6"><input type="submit" id="btn_delete_all" onclick="return deleteConfirm();"  class="btn btn-danger" value="Delete Selected">
                                        <a  class="btn btn-primary  pull-right" href="<?php echo base_url(); ?>backend/states/add"> Add New State</a>
                                    </th>

                                    </tfoot>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
<?php $this->load->view('backend/sections/footer.php'); ?>
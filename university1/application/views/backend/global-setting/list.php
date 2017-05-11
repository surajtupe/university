<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Global Settings Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manage Global Settings</li>

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
            <?php
        }
        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">									
                            <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_desc">No.</th>
                                        <th class="sorting_asc wid-130" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Parameter Name">Parameter Name</th>
                                        <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Parameter Value">Parameter Value</th>
                                        <th  class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Action</th>
                                        <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                            ?>		
                                            <th  class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">MultiLanguage</th>
                                        <?php } ?>		
                                    </tr>
                                </thead>


                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php
                                    $i = 0;
                                    if (count($arr_global_settings) > 0) {
                                        foreach ($arr_global_settings as $key => $global_setting) {
                                            ?>
                                            <tr>
                                                <td width="10%">#<?php echo $i + 1; ?></td>
                                                <td><?php echo ucwords(str_replace("_", " ", stripslashes($global_setting['name']))); ?>
                                                </td>
                                                <td ><?php
                                                    if ($global_setting['name'] == "date_format") {
                                                        echo date(stripslashes($global_setting['value']));
                                                    } else if ($global_setting['name'] == "OTP_expired") {
                                                        echo ($global_setting['value'] == 1) ? $global_setting['value'] . ' Minute' : $global_setting['value'] . ' Minutes';
                                                    } else {
                                                        echo stripcslashes(preg_replace("/[\\n\\r]+/", " ", $global_setting['value']));
                                                    }
                                                    ?></td>
                                                <td class=""><a class="btn btn-info" href="<?php echo base_url(); ?>backend/global-settings/edit/<?php echo base64_encode($global_setting['global_name_id']); ?>/<?php echo base64_encode(17); ?>" title="Edit Global Settings Parameter"> <i class="icon-edit icon-white"></i>Edit</a>
                                                </td>
                                                <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                                    ?>											
                                                    <td>  <a class="btn btn-primary" href="<?php echo base_url(); ?>backend/global-settings/edit-parameter-language/<?php echo base64_encode($global_setting['global_name_id']); ?>" title="Edit Global Settings Parameter for other Langauages">
                                                            <i class="icon-file icon-white"></i>Multi Language</a> 
                                                    </td>
                                                <?php } ?>		
                                                <?php
                                                $i++;
                                            }
                                        }
                                        ?>

                                </tbody>
                            </table>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>

    </section>
    <?php $this->load->view('backend/sections/footer'); ?>
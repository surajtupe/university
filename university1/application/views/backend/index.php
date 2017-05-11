<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<?php $this->session->unset_userdata('msg'); ?>
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        
        <h1>
            Admin Dashboard

        </h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>

        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      
        <!-- Small boxes (Stat box) -->
        <?php if ($user_account['user_type'] == '2' && $user_account['role_id'] == '1') { ?>
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
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">

                            <h3>
                                <?php echo $totalCount; ?>
                            </h3>
                            <p>
                                Total Admin Users
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?php echo base_url() ?>backend/admin/list" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">

                            <h3>
                               Crone Job
                            </h3>
                            <p>
                               Crone Temporary Access
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?php echo base_url() ?>crone-job-for-temp-access" class="small-box-footer">
                            Crone <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">

                            <h3>
                               Crone Job
                            </h3>
                            <p>
                               Crone To Delete Temporary Access
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?php echo base_url() ?>crone-job-for-delete-temp-access" class="small-box-footer">
                        Delete Crone <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- /.row -->


    </section><!-- /.content -->
</aside><!-- /.right-side -->

<?php $this->load->view('backend/sections/footer.php'); ?>
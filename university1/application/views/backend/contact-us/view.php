<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<script src="<?php echo base_url(); ?>media/backend/js/ckeditor/ckeditor.js"></script>

<aside class="right-side">
    <section class="content-header">

        <h1>
            Manage  Contact Us
        </h1>            
        <ol class="breadcrumb">
            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/contact-us"><i class="fa fa-fw fa-phone"></i>	Manage  Contact Us</a>					  </li>
            <li>View Contact Message Details</li>

        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <!--[message box]-->
                    <?php if ($this->session->userdata('msg') != '') { ?>
                        <div class="msg_box alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">�</button>
                            <?php echo $this->session->userdata('msg'); ?> </div>
                        <?php
                        $this->session->unset_userdata('msg');
                    }
                    if ($this->session->userdata('images_error') != '') {
                        ?>
                        <div class="msg_box alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">�</button>
                            <?php echo $this->session->userdata('images_error'); ?> </div>
                        <?php
                        $this->session->unset_userdata('images_error');
                    }
                    ?>

                    <form>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">From:</label>
                                <?php echo stripslashes($arr_contact[0]['name']); ?> 
                            </div>

                            <div class="form-group">
                                <label for="parametername">Email-id:</label>
                                <?php echo stripslashes($arr_contact[0]['mail_id']); ?> 
                            </div>

                            <div class="form-group">
                                <label for="parametername">Date:</label>
                                <?php echo date($global['date_format'], strtotime($arr_contact[0]['date'])); ?>
                            </div>

                            <div class="form-group">
                                <label for="parametername">Subject:</label>
                                <?php echo stripslashes($arr_contact[0]['subject']); ?>
                            </div>

                            <div class="form-group">
                                <label for="parametername">Message:</label>
                                <textarea readonly class="form-control" rows="10"><?php echo strip_slashes($message); ?></textarea>
                            </div>

                        </div>
                        <div class="box-footer">
                            <a class="btn btn-primary" href="<?php echo base_url() . 'backend/contact-us/reply/' . base64_encode($arr_contact[0]['contact_id']); ?>">Reply</a></div>
                    </form>
                </div> 
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>
        </div>
        </body>
        </html>
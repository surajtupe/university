<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<script src="<?php echo base_url(); ?>media/backend/js/ckeditor/ckeditor.js"></script>

<aside class="right-side">
    <section class="content-header">

        <h1>
            Reply To  Message
        </h1>            
        <ol class="breadcrumb">
            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/contact-us"><i class="fa fa-fw fa-phone"></i>Manage Contacts</a>					  </li>
            <li>Reply To Message</li>

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

                    <form name="form_reply" id="form_reply"  action="<?php echo base_url() . 'backend/contact-us/reply/' . base64_encode($arr_contact[0]['contact_id']); ?>" method="post">
                        <input type="hidden" name="contact_id" value="<?php echo $arr_contact[0]['contact_id']; ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">To:<sup class="mandatory">*</sup></label>
                                <input readonly type="text" size="80" value="<?php echo $arr_contact[0]['mail_id']; ?>" name="to" class="form-control" dir="ltr">

                            </div>
                            <div class="form-group">
                                <label for="parametername">From :<sup class="mandatory">*</sup></label>
                                <input  type="text" size="80" readonly id="from_name" value="<?php echo $global['site_title']; ?>" name="from_name" class="form-control">

                            </div>
                            <div class="form-group">
                                <label for="parametername">From Email :<sup class="mandatory">*</sup></label>
                                <input type="text" readonly value="<?php echo $global['contact_email']; ?>" name="from_email" class="form-control">

                            </div>
                            <div class="form-group">
                                <label for="parametername">Subject :<sup class="mandatory">*</sup></label>
                                <input type="text" value="<?php echo 'Reply:' . stripslashes($arr_contact[0]['subject']); ?>" name="subject" class="form-control">

                            </div>
                            <div class="form-group">
                                <label for="parametername">Message :<sup class="mandatory">*</sup></label>
                                <textarea name="message" id="message"   rows="10" class="form-control"><?php echo stripslashes($message); ?></textarea>

                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary" value="Save changes">Send</button>
                            <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                        </div>

                    </form>
                </div>
            </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>
        </div>
        </body>
        <script type="text/javascript" language="javascript">
            jQuery(document).ready(function () {
                jQuery("#form_reply").validate({
                    errorElement: 'div',
                    highlight: false,
                    rules: {
                        to: {required: true, email: true},
                        from_name: {required: true},
                        from_email: {required: true, email: true},
                        subject: {required: true},
                        message: {required: true}
                    },
                    messages: {
                        to: {required: 'Please enter the message to email id.'},
                        from_name: {required: 'Please enter message from name.'},
                        from_email: {required: 'Please enter from email.'},
                        subject: {required: 'Please enter message subject.'},
                        message: {required: 'Please enter message.'}
                    },
                    submitHandler: function (form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }
                });
            });
        </script>
        </html>
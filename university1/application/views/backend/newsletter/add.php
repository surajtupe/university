<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<script src="<?php echo base_url(); ?>media/backend/js/ckeditor/ckeditor.js"></script>
<aside class="right-side">
    <section class="content-header">

        <h1> Add Newsletter	</h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/newsletter/list"> <i class="fa fa-fw fa-envelope"></i> Manage Newsletters</a></li>
            <li>Add Newsletter</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="frm_add_newsletter" id="frm_add_newsletter" action="<?php echo base_url(); ?>backend/newsletter/add" method="post" >
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Title<sup class="mandatory">*</sup></label>
                                <input type="text"   class="form-control" name="input_subject" id="input_subject"  value=""/>
                                <?php echo form_error('input_subject'); ?> 
                            </div>
                            <div class="form-group">
                                <label for="parametername">Content<sup class="mandatory">*</sup></label>
                                <textarea  class="form-control"  class="ckeditor" name="input_content" id="input_content"><?php echo set_value('input_content'); ?></textarea>
                                 <label style="display: none;" class="error hidden" id="labelProductError">Please enter newsletter contents.</label>
                                <?php echo form_error('input_content'); ?> 
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="submit_button" id="btnSubmit" class="btn btn-primary" value="Save" id="submit_button">Save</button>
                            <button type="reset" name="cancel" class="btn" onClick="window.top.location = '<?php echo base_url(); ?>backend/newsletter/list';">Cancel</button>
                            <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                        </div>             
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        <script type="text/javascript">
            $(document).ready(function (e) {
               
                     CKEDITOR.replace('input_content',
                        { 
                            filebrowserUploadUrl: '<?php echo base_url(); ?>upload-image'
                        });
                $('#btn_cancel').click(function () {
                    window.location = "<?php echo base_url(); ?>backend/newsletter/list";
                });

                jQuery("#frm_add_newsletter").validate({
                    errorElement: 'div',
                    errorClass: 'error',
                    rules: {
                        input_subject: {
                            required: true,
                            minlength: 3
                        },
                        input_content: {
                            required: true
                        }
                    },
                    messages: {
                        input_subject: {
                            required: "Please enter newsletter title.",
                            minlength: "Please enter at least 3 characters."
                        },
                        input_content: {
                            required: "Please enter newsletter content."
                        }
                    },
                    submitHandler: function (form) {
                       
                          if ((jQuery.trim(jQuery("#cke_1_contents iframe").contents().find("body").html())).length < 12)
                        {
                          
                            jQuery("#labelProductError").removeClass("hidden");
                            jQuery("#labelProductError").show();
                        }
                        else {
                            jQuery("#labelProductError").addClass("hidden");
                             $("#btnSubmit").hide();
                               jQuery("#labelProductError").hide();                             
                             $('#loding_image').show();
                             form.submit();
                        }
                        
                    }
                });

           
            });
        </script>
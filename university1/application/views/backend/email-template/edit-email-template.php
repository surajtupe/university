<?php
/* * making sure that array having only one record.** */
$arr_email_template_details = end($arr_email_template_details);
?>
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<script src="<?php echo base_url(); ?>media/backend/js/ckeditor/ckeditor.js"></script>
<aside class="right-side">
    <section class="content-header">

        <h1> Edit Email Template	</h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/email-template/list"><i class="fa fa-fw fa-envelope"></i> Manage Email Templates</a></li>
            <li class="active">Edit Email Template</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="frm_email_template"  id="frm_email_template" action="<?php echo base_url(); ?>backend/edit-email-template/<?php echo(isset($email_template_id)) ? $email_template_id : ''; ?>" method="POST" >
                        <input type="hidden" name="email_template_hidden_id" id="email_template_hidden_id" value="<?php echo (isset($email_template_id)) ? $email_template_id : ''; ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Email Template Title<sup class="mandatory">*</sup></label>
                                <input type="text" dir="ltr" disabled="disabled"  class="form-control" name="inputTitle" id="inputTitle"  value="<?php echo strip_slashes(ucwords(str_replace("-", " ", $arr_email_template_details['email_template_title']))); ?>"/>

                            </div>
                            <div class="form-group">
                                <label for="parametername">Email Template Subject<sup class="mandatory">*</sup></label>
                                <input type="text" dir="ltr"   class="form-control" name="input_subject" id="input_subject"  value="<?php echo str_replace("\n", "", $arr_email_template_details['email_template_subject']); ?>"/>

                            </div>
                            <div class="form-group">
                                <label for="parametername">Email Template Content<sup class="mandatory">*</sup></label>
                                <textarea class="form-control" class="ckeditor" name="text_content" id="text_content"> <?php echo stripcslashes($arr_email_template_details['email_template_content']); ?></textarea>
                                <div class="error hidden" id="labelProductError">Please enter the email template content.</div>
                            </div>
                            <div class="form-group">
                                <label for="parametername">Email Template Content Macros</label>
                                <select  class="combobox form-control"  multiple ondblclick="insertText(this)" >
                                    <?php
                                    foreach ($arr_macros as $macros) {
                                        ?>
                                        <option value="<?php echo $macros['macros']; ?>"><?php echo ucwords(str_replace("_", " ", strtolower(trim($macros['macros'], '||'))) . ":-"); ?><?php echo $macros['macros']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="btn_submit" class="btn btn-primary" value="Save Changes" id="btnSubmit">Save Changes</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        <script type="text/javascript" language="javascript">
            $(document).ready(function () {
                jQuery("#frm_email_template").validate({
                    errorElement: 'label',
                    rules: {
                        input_subject: {
                            required: true
                        },
                        text_content: {
                            required: true
                        }
                    },
                    messages: {
                        input_subject: {
                            required: "Please enter the email template subject."
                        },
                        text_content: {
                            required: "Please enter the email template content."
                        }
                    },
                    // set this class to error-labels to indicate valid fields
                    submitHandler: function (form) {

                        if ((jQuery.trim(jQuery("#cke_1_contents iframe").contents().find("body").html())).length < 12)
                        {
                            jQuery("#labelProductError").removeClass("hidden");
                            jQuery("#labelProductError").show();
                        }
                        else {
                            jQuery("#labelProductError").addClass("hidden");
                            $('#btnSubmit').hide();
                            form.submit();
                        }
                    }
                });
                CKEDITOR.replace('text_content',
                        {
                            filebrowserUploadUrl: '<?php echo base_url(); ?>upload-image'
                        });

            });
            function insertText(obj) {
                newtext = obj.value;
                console.log(newtext);
                CKEDITOR.instances['text_content'].insertText(newtext);

            }
        </script>
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            <?php echo (isset($edit_id) && $edit_id != "") ? "Update" : "Add New"; ?> Document</li>     
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/document/list"> Manage Documents</a></li>
            <li><?php echo (isset($edit_id) && $edit_id != "") ? "Update" : "Add"; ?> Document</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6"> 
                <div class="box box-primary">
                    <form name="frm_document" id="frm_document"  action="<?php echo base_url(); ?>backend/document/add" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo(isset($edit_id)) ? $edit_id : '' ?>">
                        <div class="box-body">
                            <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                ?>	
                                <div class="form-group">
                                    <label for="Language">Language<sup class="mandatory">*</sup></label>
                                    <select class="form-control" name="lang_id" id="lang_id">
                                        <option value="">Select Language</option>
                                        <?php foreach ($arr_get_language as $languages) { ?>
                                            <option value="<?php echo $languages['lang_id']; ?>" <?php echo(isset($document_details['lang_id']) && ($languages['lang_id'] == $document_details['lang_id'])) ? 'selected' : '' ?>><?php echo $languages['lang_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('lang_id'); ?>

                                </div>	  
                            <?php } else { ?>
                                <input type="hidden" name="lang_id" id="lang_id" value="17" />
                            <?php } ?>	
                            <div class="form-group">
                                <label for="parametername">Document<sup class="mandatory">*</sup></label>
                                <input type="text" dir="ltr"  autofocus class="form-control" name="document_name" value="<?php echo stripslashes(isset($document_details['document_text']) ? $document_details['document_text'] : ''); ?>"   />
                                <input type="hidden" dir="ltr"  autofocus class="form-control" id="old_document_name" name="old_document_name" value="<?php echo stripslashes(isset($document_details['document_text']) ? $document_details['document_text'] : ''); ?>"   />
                            </div>
                            <div class="box-footer">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save" id="btnSubmit">Save <?php echo (isset($edit_id) && $edit_id != "") ? "Changes" : ""; ?> </button>
                                <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>

        <script type="text/javascript" language="javascript">
            $(document).ready(function() {
                var javascript_site_path = '<?php echo base_url(); ?>'
                jQuery.validator.addMethod("lettersonly", function(value, element) {
                    return this.optional(element) || /^[a-z\s]+$/i.test(value);
                }, "Please enter valid name");

                jQuery("#frm_document").validate({
                    errorElement: 'div',
                    rules: {
                        document_name: {
                            required: true,
                            lettersonly: true,
                            remote: {
                                url: javascript_site_path + 'chk-document',
                                method: 'post',
                                data: {
                                    old_document_name: function() {
                                        return $('#old_document_name').val();
                                    }
                                }
                            },
                        }
                    },
                    messages: {
                        document_name: {
                            required: "Please enter document name.",
                            remote: 'This document name is already exists.'
                        }
                    },
                    submitHandler: function(form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }
                });
            });

        </script>
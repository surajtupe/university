<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            <?php echo (isset($edit_id) && $edit_id != "") ? "Update" : "Add New"; ?> FAQ</li>     
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/faqs/list"><i class="fa fa-fw fa-question"></i> Manage FAQ's</a></li>
            <li class="active"><?php echo (isset($edit_id) && $edit_id != "") ? "Update" : "Add"; ?> FAQ</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6"> 
                <div class="box box-primary">
                    <form name="frmFaqs" id="frmFaqs"  action="<?php echo base_url(); ?>backend/faqs/add" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo(isset($edit_id)) ? $edit_id : '' ?>">

                        <div class="box-body">
                            <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                ?>	
                                <div class="form-group">
                                    <label for="Language">Language<sup class="mandatory">*</sup></label>
                                    <select class="form-control" name="lang_id" id="lang_id">
                                        <option value="">Select Language</option>
                                        <?php foreach ($arr_get_language as $languages) { ?>
                                            <option value="<?php echo $languages['lang_id']; ?>" <?php echo(isset($arr_faq['lang_id']) && ($languages['lang_id'] == $arr_faq['lang_id'])) ? 'selected' : '' ?>><?php echo $languages['lang_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('lang_id'); ?>

                                </div>	  
                            <?php } else { ?>
                                <input type="hidden" name="lang_id" id="lang_id" value="17" />
                            <?php } ?>	

                            <div class="form-group">
                                <label for="parametername">Question<sup class="mandatory">*</sup></label>
                                <input type="text" dir="ltr"  autofocus class="form-control" name="input_question" value="<?php echo stripslashes(isset($arr_faq['question']) ? $arr_faq['question'] : ''); ?>"   />

                            </div>
                            <div class="form-group">
                                <label for="parametername">Answer<sup class="mandatory">*</sup></label>
                                <textarea rows="6"  class="form-control" name="input_answer" style="resize: vertical;" ><?php echo stripslashes(isset($arr_faq['answer']) ? $arr_faq['answer'] : '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="parametername">Search Tags</label>
                                <textarea rows="3"  class="form-control" name="search_tags" style="resize: vertical;" ><?php echo stripslashes(isset($arr_faq['search_tags']) ? $arr_faq['search_tags'] : '') ?></textarea>
                            </div>
                            <?php if (isset($edit_id) && $edit_id = !'') { ?>
                                <div class="form-group">
                                    <label for="parametername">Created on : </label>
                                    <?php echo date("Y-m-d", strtotime($arr_faq['created_on'])); ?> 
                                </div>
                            <?php } ?>
                            <div class="box-footer">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save" id="btnSubmit">Save <?php echo (isset($edit_id) && $edit_id != "") ? "Changes" : ""; ?> </button>
                                <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                            </div>


                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>

        <script type="text/javascript" language="javascript">
            $(document).ready(function () {
                jQuery("#frmFaqs").validate({
                    errorElement: 'label',
                    rules: {
                        input_question: {
                            required: true,
                            minlength: 3
                        },
                        input_answer: {
                            required: true,
                            minlength: 25
                        },
                        lang_id: {
                            required: true
                        },
                    },
                    messages: {
                        lang_id: {
                            required: "Please select language"
                        },
                        input_question: {
                            required: "Please enter question.",
                            minlength: "Please enter at least 3 characters."
                        },
                        input_answer: {
                            required: "Please enter answer.",
                            minlength: "Please enter at least 25 characters."
                        }
                    },
                    submitHandler: function (form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }
                });
            });

        </script>
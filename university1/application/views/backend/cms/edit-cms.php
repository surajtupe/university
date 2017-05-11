<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<script src="<?php echo base_url(); ?>media/backend/js/ckeditor/ckeditor.js"></script>
<aside class="right-side">
    <section class="content-header">

        <h1> Edit CMS Page	</h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/cms"> <i class="fa fa-fw fa-file-text"></i>  Manage CMS Pages</a></li>
            <li class="active">Edit CMS Page</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">

                    <form  name="edit_cms_form" id="edit_cms_form" action="<?php echo base_url(); ?>backend/cms/edit-cms/<?php echo $arr_cms_details[0]['cms_id']; ?>" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">CMS Page Title <sup class="mandatory">*</sup></label>
                                <input type="text"   class="form-control" name="cms_page_title" id="cms_page_title"  value="<?php echo stripslashes($arr_cms_details[0]['page_title']); ?>"/>

                            </div>
                            <div class="form-group">
                                <label for="parametername">CMS Page Content<sup class="mandatory">*</sup></label>
                                <textarea class="form-control" class="ckeditor" name="cms_content" id="cms_content" ><?php echo stripslashes($arr_cms_details[0]['page_content']); ?></textarea>
                                <label style="display: none;" class="error hidden" id="labelProductError">Please enter CMS contents.</label>
                            </div>
                            <div class="form-group">
                                <label for="parametername">Page SEO Title <sup class="mandatory">*</sup></label>
                                <input type="text"   class="form-control" name="cms_page_seo_title" id="cms_page_seo_title"  value="<?php echo stripslashes($arr_cms_details[0]['page_seo_title_lang']); ?>"/>

                            </div>
                            <div class="form-group">
                                <label for="parametername">Meta Keywords<sup class="mandatory">*</sup></label>
                                <textarea class="form-control" name="cms_page_meta_keywords" id="cms_page_meta_keywords" ><?php echo stripslashes($arr_cms_details[0]['page_meta_keyword']); ?></textarea>

                            </div>
                            <div class="form-group">
                                <label for="parametername">Meta Description<sup class="mandatory">*</sup></label>
                                <textarea class="form-control" name="cms_page_meta_description" id="cms_page_meta_description" ><?php echo stripslashes($arr_cms_details[0]['page_meta_description']); ?></textarea>

                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" name="submit_button" class="btn btn-primary" value="Save" id="submit_button">Save Changes</button>
                            <input type="hidden" name="cms_id" id="cms_id" value="<?php echo $arr_cms_details[0]['cms_id']; ?>" >
                            <button type="reset" name="cancel" class="btn" onClick="window.top.location = '<?php echo base_url(); ?>backend/cms';">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>
        <script type="text/javascript" language="javascript">
            /* add admin Js */
            jQuery(document).ready(function () {

                jQuery("#edit_cms_form").validate({
                    errorElement: 'div',
                    rules: {
                        cms_page_title: {
                            required: true
                        },
                        cms_page_seo_title: {
                            required: true
                        },
                        status: {
                            required: true
                        },
                        cms_page_meta_keywords: {
                            required: true
                        },
                        cms_page_meta_description: {
                            required: true
                        },
                        cms_page_meta_content: {
                            required: true
                        }
                    },
                    messages: {
                        cms_page_seo_title: {
                            required: "Please mention page SEO title."
                        },
                        cms_page_title: {
                            required: "Please enter cms page title."
                        },
                        status: {
                            required: "Please select cms page status."
                        },
                        cms_page_meta_keywords: {
                            required: "Please mention page meta keywords."
                        },
                        cms_page_meta_description: {
                            required: "Please mention page meta description."
                        },
                        cms_page_meta_content: {
                            required: "Please mention page meta content."
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
                            form.submit();
                        }
                    }
                });
                CKEDITOR.replace('cms_content',
                        { 
//                            alert();
                            filebrowserUploadUrl: '<?php echo base_url(); ?>upload-image'
                        });
            });
        </script>
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
                    <form name="frm_edit_cms_parameter"  id="frm_edit_cms_form" action="<?php echo base_url(); ?>backend/cms/edit-cms-language/<?php echo $edit_id; ?>" method="POST">
                        <input type="hidden" name="cms_id" id="cms_id" value="<?php echo $edit_id; ?>" />

                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Language <sup class="mandatory">*</sup></label>
                                <select id="lang_id" class="form-control" name="lang_id">
                                    <option value="">Select Language</option>
                                    <?php
                                    foreach ($arr_languages as $language) {
                                        ?>
                                        <option value="<?php echo $language['lang_id']; ?>"><?php echo $language['lang_name']; ?></option><?php
                                    }
                                    ?>
                                </select> 
                            </div>
                            <div class="form-group">
                                <label for="parametername">CMS Page Title <sup class="mandatory">*</sup></label>
                                <input type="text"   class="form-control" name="cms_page_title" id="cms_page_title"  value=""/>

                            </div>

                            <div class="form-group">
                                <label for="parametername">CMS Page Content<sup class="mandatory">*</sup></label>
                                <textarea class="form-control" class="ckeditor" name="cms_content" id="cms_content" ></textarea>
                                <label style="display: none;" class="error hidden" id="labelProductError">Please enter CMS contents.</label>
                            </div>
                            <div class="form-group">
                                <label for="parametername">Page SEO Title <sup class="mandatory">*</sup></label>
                                <input type="text"   class="form-control" name="cms_page_seo_title" id="cms_page_seo_title"  value=""/>

                            </div>
                            <div class="form-group">
                                <label for="parametername">Meta Keywords<sup class="mandatory">*</sup></label>
                                <textarea class="form-control" name="cms_page_meta_keywords" id="cms_page_meta_keywords" ></textarea>

                            </div>
                            <div class="form-group">
                                <label for="parametername">Meta Description<sup class="mandatory">*</sup></label>
                                <textarea class="form-control" name="cms_page_meta_description" id="cms_page_meta_description" ></textarea>

                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" name="submit_button" class="btn btn-primary" value="Save" id="submit_button">Save Changes</button>
                            <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
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
                CKEDITOR.replace('cms_content',
                        {
                            filebrowserUploadUrl: '<?php echo base_url(); ?>upload-image'
                        });

                /*Binding change event to lang_id selectbox */
                jQuery("#lang_id").bind("change", getLanguageVals);
                /*Gettig langauage values for the cms*/
                function getLanguageVals()
                {
                    if (jQuery(this).val() != "")
                    {
                        jQuery.post("<?php echo base_url(); ?>backend/cms/get-cms-language", {lang_id: jQuery(this).val(), edit_id:<?php echo ($edit_id); ?>}, function (msg) {


                            if (msg.page_content != '')
                            {
                                CKEDITOR.instances['cms_content'].insertHtml(msg.page_content);
                            } else {

                                CKEDITOR.instances['content'].setData('');
                            }
                            //     jQuery("#cms_content").CKEDITOR()[0].clear().execCommand("inserthtml", msg.page_content);

                            //    jQuery("#cms_content").CKEDITOR()[0].clear();

                            jQuery("#cms_page_title").val(msg.page_title);
                            jQuery("#cms_page_seo_title").val(msg.page_seo_title_lang);
                            jQuery("#cms_page_meta_keywords").val(msg.page_meta_keyword);
                            jQuery("#cms_page_meta_description").val($.trim(msg.page_meta_description));
                        }, "json");
                    } else {
                        CKEDITOR.instances['cms_content'].setData();
                        jQuery("#cms_page_title,#cms_page_seo_title,#cms_page_meta_keywords,#cms_page_meta_description").val('');
                    }
                }

                jQuery("#frm_edit_cms_form").validate({
                    errorElement: 'div',
                    rules: {
                        cms_page_title: {
                            required: true
                        },
                        lang_id: {
                            required: true
                        },
                        cms_page_meta_keywords: {
                            required: true
                        },
                        cms_page_meta_description: {
                            required: true
                        },
                        cms_page_seo_title: {
                            required: true
                        }
                    },
                    messages: {
                        cms_page_title: {
                            required: "Please enter cms page title."
                        },
                        lang_id: {
                            required: "Please select a language."
                        },
                        cms_page_meta_keywords: {
                            required: "Please mention page meta keywords."
                        },
                        cms_page_meta_description: {
                            required: "Please mention page meta description."
                        },
                        cms_page_seo_title: {
                            required: "Please mention page seo title."
                        }
                    }, submitHandler: function (form) {
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
            });
        </script>
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Updated Category in Different Languages						 
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>backend/categories/list"><i class="fa fa-fw fa-user"></i> Manage Categories</a></li>
            <li class="active">	Updated Category in Different Languages</li>

        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form  name="frm_change_lang" id="frm_change_lang" action="" method="POST" >
                        <div class="box-body">
                            <input type="hidden" value="<?php echo base_url(); ?>" id="base_url" name="base_url">
                            <input type="hidden" value="<?php echo (isset($category_id))?$category_id:''?>" id="category_id" name="category_id">
                            <div class="form-group">
                                <label for="User Name">Language<sup class="mandatory">*</sup></label>
                                <select  class="form-control" name="lang_id" id="lang_id" onChange="getCategoryName(this.value, '<?php echo (isset($category_id))?$category_id:''?>');">
                                    <option value="">Select Language</option>
                                    <?php foreach ($arr_get_language as $languages) { ?>
                                        <option value="<?php echo $languages['lang_id'] ?>" ><?php echo $languages['lang_name'] ?></option>
                                    <?php } ?>
                                </select>

                            </div>

                            <div class="control-group" style="display:none" id="category_div">

                            </div>

                            <div class="control-group" style="display:none" id="description_div">

                            </div>

                            <div class="box-footer">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save changes">Save changes</button>
                            </div>

                    </form>
                </div>
                <!--[sortable body]--> 
            </div>
        </div>
        <!--[sortable table end]--> 
        <!--[include footer]-->
        </div><!--/#content.span10-->
        </div><!--/fluid-row-->
        <?php $this->load->view('backend/sections/footer.php'); ?>

        <script type="text/javascript">
            function getCategoryName(value, category_id_fk) {

                var lang_id = value;
                var category_id_fk = category_id_fk;
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>backend/category/category-name',
                    data: {
                        'lang_id': lang_id,
                        'category_id_fk': category_id_fk
                    },
                    success: function (msg) {
                        if (msg != 'false') {
                            $("#category_div").css("display", "block");
                            $("#category_div").html(msg);
                        }
                        else {
                            $("#category_div").css("display", "block");
                        }
                        applyRemoteCatgeoryNameRule();
                    }
                });
            }

            $(document).ready(function (e) {
                $("#frm_change_lang").validate({
                    errorElement: "div",
                    rules: {
                        lang_id: {
                            required: true

                        },
                        category_description: {
                            required: true,
                            maxlength: 200
                        }


                    },
                    messages: {
                        lang_id: {
                            required: "Please select language.",
                            remote: "Category already exists with this language."

                        },
                        category_name: {
                            required: "Please enter category name.",
                            lettersonly: "Please enter valid category name.",
                            remote: "Category name already exists."
                        },
                        category_description: {
                            required: "Please enter description.",
                            maxlength: "description should not exceed 200 characters."
                        }

                    },
                    submitHandler: function (form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }
                });

                jQuery.validator.addMethod("lettersonly", function (value, element) {
                    return this.optional(element) || /^[A-Z]+$/i.test(value);
                }, "");

            });
            function applyRemoteCatgeoryNameRule()
            {
                jQuery("#category_name").rules("remove");

                jQuery("#category_name").rules("add", {
                    required: true,
                    remote: {
                        url: "<?php echo base_url() ?>backend/category/check-category-name",
                        type: "post",
                        data: {old_category_name: jQuery('#old_category_name').val(), type: "edit"},
                    }
                });
            }
        </script>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/category-manage/change-language.js"></script>

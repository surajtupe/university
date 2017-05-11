<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            <?php echo (isset($edit_id) && $edit_id != "") ? "Update" : "Add New"; ?> Testimonial</li>    
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/testimonial/list"><i class="fa fa-fw fa-retweet"></i> Manage Testimonials</a></li>
            <li class="active"><?php echo (isset($edit_id) && $edit_id != "") ? "Update" : "Add"; ?> Testimonial</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <?php if ($this->session->userdata('msg') != '') { ?>
                        <div class="msg_box alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">×</button>
                            <?php echo $this->session->userdata('msg'); ?> </div>
                        <?php
                        $this->session->unset_userdata('msg');
                    }
                    if ($this->session->userdata('images_error') != '') {
                        ?>
                        <div class="msg_box alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">×</button>
                            <?php echo $this->session->userdata('images_error'); ?> </div>
                        <?php
                        $this->session->unset_userdata('images_error');
                    }
                    ?>
                    <form name="frmTestimonials" id="frmTestimonials" action="<?php echo base_url(); ?>backend/testimonial/add" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" value="<?php echo(isset($edit_id)) ? $edit_id : '' ?>">					 
                        <div class="box-body">
                            <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                ?>	
                                <div class="form-group">
                                    <label for="parametername">Language<sup class="mandatory">*</sup></label>
                                    <select class="form-control" name="lang_id" id="lang_id">
                                        <option value="">Select Language</option>
                                        <?php foreach ($arr_get_language as $languages) { ?>
                                            <option value="<?php echo $languages['lang_id'] ?>" <?php echo(isset($arr_testimonial['lang_id']) && ($languages['lang_id'] == $arr_testimonial['lang_id'])) ? 'selected' : ''; ?>><?php echo $languages['lang_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('lang_id'); ?>

                                </div>
                            <?php } else { ?>
                                <input type="hidden" name="lang_id" id="lang_id" value="17" />
                            <?php } ?>	
                            <div class="form-group">
                                <label for="parametername">Testimonial Title<sup class="mandatory">*</sup></label>
                                <input type="text" autofocus  class="form-control" name="inputName" value="<?php echo (isset($arr_testimonial['name'])) ? stripslashes($arr_testimonial['name']) : ''; ?>"   />

                            </div>
                            <div class="form-group">
                                <label for="parametername">Testimonial Description<sup class="mandatory">*</sup></label>
                                <textarea rows="6"  class="form-control" name="inputTestimonial"><?php echo (isset($arr_testimonial['testimonial'])) ? stripslashes($arr_testimonial['testimonial']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="parametername">Upload Image</label>
                                <input type="file" dir="ltr"  class="" name="upload_image" id="upload_image" value="" size="80"  autocomplete="off"  />
                                <br>
                                <div id="img_div">
                                    <img id="imageHolder" src="<?php if(isset($edit_id) && $edit_id != ""){ if($arr_testimonial['testimonial_img'] != ''){ echo base_url() ?>media/backend/img/testimonial_image/thumbs/<?php echo $arr_testimonial['testimonial_img']; } else{  echo base_url(); ?>media/front/img/avatar.png <?php } } else{  echo base_url(); ?>media/front/img/avatar.png <?php } ?>" onerror="src='<?php echo base_url(); ?>media/front/img/avatar.png';" width="150" height="150" />
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Save Changes">Save <?php echo (isset($edit_id) && $edit_id != "") ? "Changes" : ""; ?> </button>
                                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                                <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>  
        <?php if (isset($edit_id) && $edit_id != "") { ?>
            <script type="text/javascript" language="javascript">

            </script>
        <?php } else { ?>
            <script type="text/javascript" language="javascript">

            </script>
        <?php } ?>
        <script type="text/javascript" language="javascript">

            (function($) {
                $.fn.checkFileType = function(options) {
                    var defaults = {
                        allowedExtensions: [],
                        success: function() {
                        },
                        error: function() {
                        }
                    };
                    options = $.extend(defaults, options);

                    return this.each(function() {

                        $(this).on('change', function() {
                            var value = $(this).val(),
                                    file = value.toLowerCase(),
                                    extension = file.substring(file.lastIndexOf('.') + 1);

                            if ($.inArray(extension, options.allowedExtensions) == -1) {
                                options.error();
                                $(this).focus();
                            } else {
                                options.success();

                            }

                        });

                    });
                };

            })(jQuery);

            $(function() {
                $('#upload_image').checkFileType({
                    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                    error: function() {
                        $('#upload_image').replaceWith($('#upload_image').val('').clone(true));
                        alert('Please upload only jpg,jpeg,png,gif type file.');
                    }

                });

            });

            var _URL = window.URL || window.webkitURL;
            $("#upload_image").change(function(e) {
                var file, img;
                var width = 795;
                var height = 400;
                if ((file = this.files[0])) {
                    img = new Image();
                    img.onload = function() {
                        if (this.width < width && this.height < height) {
                            $('#upload_image').replaceWith($('#upload_image').val('').clone(true));
                            alert("Please upload image of " + width + "px width and " + height + "px height or greatar");
                        } else {

                            var input = document.getElementById('upload_image');
                            readURL(input);
                        }

                    };
                    img.src = _URL.createObjectURL(file);
                }
            });
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
//                        $("#img_div").show();
                        $('#imageHolder').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(document).ready(function() {
                jQuery("#frmTestimonials").validate({
                    errorElement: 'label',
                    rules: {
                        lang_id: {
                            required: true
                        },
                        inputTestimonial: {
                            required: true,
                            minlength: 20
                        },
                        inputName: {
                            required: true
                        },
                    },
                    messages: {
                        lang_id: {
                            required: "Please select language."
                        },
                        inputTestimonial: {
                            required: "Please enter testimonial description.",
                            minlength: "Please enter at least 20 characters."
                        },
                        inputName: {
                            required: "Please enter testimonial name."
                        },
                    },
                    submitHandler: function(form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }
                });

            });
        </script>
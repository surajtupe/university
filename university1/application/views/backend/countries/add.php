<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Add New Country</li>     
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/countries"> <i class="fa fa-fw fa-home"></i> Manage Countries</a></li>
            <li class="active"> Add Country</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="add_country_form" id="add_country_form" action="<?php echo base_url(); ?>backend/countries/add" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" value="<?php echo(isset($edit_id)) ? $edit_id : '' ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="Country Name">Country Name<sup class="mandatory">*</sup></label>
                                <input type="text" dir="ltr"  class="form-control" name="country_name" id="country_name" />

                            </div>
                            <div class="form-group">
                                <label for="Country ISO">Country ISO<sup class="mandatory">*</sup></label>
                                <input type="text" dir="ltr"  class="form-control" name="country_iso_name" id="country_iso_name" />
                            </div>
                            <div class="form-group">
                                <label for="Country ISO">Country Phone Code<sup class="mandatory">*</sup>(Start with + sign)</label>
                                <input type="text" dir="ltr"  class="form-control" name="country_phone_code" id="country_phone_code" />
                            </div>
                            <div class="form-group">
                                <label for="Country ISO">Country Flag<sup class="mandatory">*</sup></label>
                                <input type="file" dir="ltr"  name="country_flag" id="country_flag" />
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="btn_submit" class="btn btn-primary" value="Save" id="btnSubmit">Save</button>
                            <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        <script type="text/javascript" language="javascript">
            jQuery(document).ready(function() {

                jQuery.validator.addMethod("chk_phone_code", function(phone_number, element) {
                    phone_number = phone_number.replace(/\s+/g, "");
                    return this.optional(element) || phone_number.length > 2 &&
                            phone_number.match(/^\+[0-9]{2}$/);
                }, "");

                jQuery("#add_country_form").validate({
                    errorElement: 'div',
                    rules: {
                        country_iso_name: {
                            required: true,
                            lettersonly : true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-country-iso",
                                type: "post",
                                data: {}
                            }
                        },
                        country_phone_code: {
                            required: true,
                            chk_phone_code: true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-country-phone-code",
                                type: "post",
                                data: {}
                            }
                        },
                        country_name: {
                            required: true,
                            lettersonly : true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-country-name",
                                type: "post",
                                data: {}
                            }
                        },
                        country_flag: {
                            required: true,
                        }
                    },
                    messages: {
                        country_iso_name: {
                            required: "Please enter country ISO name.",
                            lettersonly : "Please enter valid country ISO name.",
                            remote: "This ISO code is already exists. "
                        },
                        country_name: {
                            required: "Please enter country name.",
                            lettersonly : "Please enter valid country name.",
                            remote: "This country is already exists."
                        },
                        country_phone_code: {
                            required: "Please enter country phone code.",
                            remote: "This phone code is already exists. ",
                            chk_phone_code: "Please enter valid phone code."
                        },
                        country_flag: {
                            required: "Please upload country flag.",
                        }
                    },
                    submitHandler: function(form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }
                });
                jQuery.validator.addMethod("lettersonly", function(value, element) {
                    return this.optional(element) || /^[a-z\s]+$/i.test(value);
                }, "");
            });
        </script>
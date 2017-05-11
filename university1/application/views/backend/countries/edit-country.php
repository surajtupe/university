<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Edit Country    
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/countries"><i class="fa fa-fw fa-home"></i> Manage Countries</a></li>
            <li class="active">Edit Country</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form  name="edit_country_form" id="edit_country_form" action="<?php echo base_url(); ?>backend/countries/edit-country/<?php echo $arr_country_details[0]['country_id']; ?>" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Country Name<sup class="mandatory">*</sup></label>
                                <input type="text" dir="ltr"  class="form-control" name="country_name" id="country_name"  value="<?php echo $arr_country_details[0]['country_name']; ?>"/>
                                <input type="hidden" name="old_country_name" id="old_country_name" value="<?php echo $arr_country_details[0]['country_name']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="parametername">Country ISO Name<sup class="mandatory">*</sup></label>
                                <input type="text" dir="ltr"  class="form-control" name="country_iso_name" id="country_iso_name"  value="<?php echo $arr_country_details[0]['iso']; ?>"/>
                                <input type="hidden" dir="ltr"  class="form-control" name="old_country_iso_name" id="old_country_iso_name"  value="<?php echo $arr_country_details[0]['iso']; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="Country ISO">Country Phone Code<sup class="mandatory">*</sup>(Start with + sign)</label>
                                <input type="text" dir="ltr"  class="form-control" name="country_phone_code" id="country_phone_code" value="<?php echo $arr_country_details[0]['country_phone_code']; ?>"/>
                                <input type="hidden" dir="ltr"  class="form-control" name="old_country_phone_code" id="old_country_phone_code" value="<?php echo $arr_country_details[0]['country_phone_code']; ?>" />
                            </div>
                            <div class="form-group">
                                <label for="Country ISO">Country Flag<sup class="mandatory">*</sup></label>
                                <input type="file" dir="ltr"  name="country_flag" id="country_flag" />
                                <input type="hidden" dir="ltr"  name="old_flag" id="old_flag" value="<?php echo $arr_country_details[0]['flag']; ?>" />
                            </div>
                            <img src="<?php echo base_url() ?>media/backend/img/country-flag/thumbs/<?php echo $arr_country_details[0]['flag']; ?>">

                            <div class="box-footer">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save Changes" id="btnSubmit">Save Changes</button>
                                <input type="hidden" name="country_id" id="country_id" value="<?php echo $arr_country_details[0]['country_id']; ?>" >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        </div>
        </body>
        <script type="text/javascript" language="javascript">
            jQuery(document).ready(function() {

                jQuery.validator.addMethod("chk_phone_code", function(phone_number, element) {
                    phone_number = phone_number.replace(/\s+/g, "");
                    return this.optional(element) || phone_number.length > 2 &&
                            phone_number.match(/^\+[0-9]{2}$/);
                }, "");

                jQuery("#edit_country_form").validate({
                    errorElement: 'div',
                    rules: {
                        country_iso_name: {
                            required: true,
                            lettersonly: true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-country-iso",
                                type: "post",
                                data: {old_country_iso_name: jQuery('#old_country_iso_name').val()}
                            }
                        },
                        country_name: {
                            required: true,
                            lettersonly: true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-country-name",
                                type: "post",
                                data: {old_country: jQuery('#old_country_name').val()}
                            }
                        },
                        country_phone_code: {
                            required: true,
                            chk_phone_code: true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-country-phone-code",
                                type: "post",
                                data: {old_country_phone_code: jQuery('#old_country_phone_code').val()}
                            }
                        }
                    },
                    messages: {
                        country_iso_name: {
                            required: "Please enter country ISO name.",
                            lettersonly: "Please enter valid country ISO name.",
                            remote: "This ISO code is already exists. "
                        },
                        country_name: {
                            required: "Please enter country name.",
                            lettersonly: "Please enter valid country name.",
                            remote: "This country is already exists."
                        },
                        country_phone_code: {
                            required: "Please enter country phone code.",
                            remote: "This phone code is already exists. ",
                            chk_phone_code: "Please enter valid phone code."
                        }
                    }
                });
                jQuery.validator.addMethod("lettersonly", function(value, element) {
                    return this.optional(element) || /^[a-z\s]+$/i.test(value);
                }, "");
            });
        </script>
        </html>
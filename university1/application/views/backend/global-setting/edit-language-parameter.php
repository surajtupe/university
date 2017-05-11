<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>


<aside class="right-side">
    <section class="content-header">
        <h1>
            Update  Global Settings 
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>backend/global-settings/list"><i class="fa fa-gear"></i> Global Settings</a></li>
            <li class="active">Update Global Setting Parameter</li>

        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">

                    <!-- form start -->
                    <form name="frm_edit_global_setting_parameter" id="frm_edit_global_setting_parameter" action="<?php echo base_url(); ?>backend/global-settings/edit-parameter-language/<?php echo $edit_id; ?>" method="POST">

                        <input type="hidden" name="global_name_id" id="global_name_id" value="<?php echo $edit_id; ?>" />

                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="Select Language">Select Language<sup class="mandatory">*</sup></label>
                                <select id="lang_id" name="lang_id" class="form-control">
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
                                <label for="parametername">Parameter Name</label>
                                <input class="form-control" type="text" dir="ltr" readonly  name="name" id="name" value="<?php echo ucwords(str_replace("_", " ", $arr_global_settings['name'])); ?>" />

                            </div>

                            <div class="form-group">
                                <label for="Parameter Value">Parameter Value<sup class="mandatory">*</sup></label>
<?php
if ($arr_global_settings['name'] == "date_format") {
    ?>
                                    <select name="value" id="value"   class="form-control">
                                        <option <?php if ($arr_global_settings['value'] == "Y-m-d") { ?> selected="selected"<?php } ?> value="Y-m-d"><?php echo date("Y-m-d"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "Y/m/d") { ?> selected="selected"<?php } ?> value="Y/m/d"><?php echo date("Y/m/d"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "Y-m-d H:i:s") { ?> selected="selected"<?php } ?> value="Y-m-d H:i:s"><?php echo date("Y-m-d H:i:s"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "Y/m/d H:i:s") { ?> selected="selected"<?php } ?> value="Y/m/d H:i:s"><?php echo date("Y-m-d H:i:s"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "F j, Y, g:i a") { ?> selected="selected"<?php } ?> value="F j, Y, g:i a"><?php echo date("F j, Y, g:i a"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "m.d.y") { ?> selected="selected"<?php } ?> value="m.d.y"><?php echo date("m.d.y"); ?></option>
                                    </select>	
    <?php
} else if ($arr_global_settings['name'] == "address") {
    ?>
                                    <textarea name="value"  class="form-control" id="value" ></textarea>
                                <?php
                                } else {
                                    ?>
                                    <input type="text" dir="ltr"  class="form-control" name="value" id="value" value="" />
                                    <?php
                                }
                                ?>	

                            </div>

                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>

<?php $this->load->view('backend/sections/footer'); ?>
                <script>
                    jQuery(document).ready(function(e) {
                    /*Binding change event to lang_id selectbox */
                    jQuery("#lang_id").bind("change", getLanguageVals);
                            /*Gettig langauage values for the global settings parameter*/
                                    function getLanguageVals()
                                    {
                                    if (jQuery(this).val() != "")
                                    {
                                    jQuery.post("<?php echo base_url(); ?>backend/global-settings/get-global-parameter-language", {lang_id:jQuery(this).val(), edit_id:<?php echo base64_decode($edit_id); ?>}, function(msg){
                                    jQuery("#value").val(msg.value);
                                    }, "json");
                                    }
                                    }

                            jQuery("#frm_edit_global_setting_parameter").validate({
                            errorElement: "div",
                                    rules: {
                                    lang_id:{
                                    required:true
                                    },
                                            value:{
                                            required:true
<?php
if ($arr_global_settings['name'] == "site_email" || $arr_global_settings['name'] == "contact_mail") {
    echo ",email:true";
}
if ($arr_global_settings['name'] == "phone_no") {
    echo ",number:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/";
}
if ($arr_global_settings['name'] == "contact_email") {
    echo ",email:true";
}
if ($arr_global_settings['name'] == "default_currency") {
    echo ",minlength:3";
    echo ",maxlength:3";
    echo ",lettersonly:true";
}
if ($arr_global_settings['name'] == "currency_symbol") {
    echo ",minlength:1";
    echo ",maxlength:1";
}
if ($arr_global_settings['name'] == "per_page_record") {
    echo ",number:true";
}
if ($arr_global_settings['name'] == "facebook_link" || $arr_global_settings['name'] == "twitter_link" || $arr_global_settings['name'] == "Google+_link" || $arr_global_settings['name'] == "Linkedin_link" || $arr_global_settings['name'] == "link1" || $arr_global_settings['name'] == "link2" || $arr_global_settings['name'] == "link3" || $arr_global_settings['name'] == "link4" || $arr_global_settings['name'] == "link5" || $arr_global_settings['name'] == "link6") {
    echo ",url:true";
}
if ($arr_global_settings['name'] == "phone_no") {
    //				        echo ",number:true";
    echo ",number:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/";
}
?>


                                            }
                                    },
                                    messages:{
                                    lang_id:{
                                    required:"Please select language."
                                    },
                                            value:{
<?php
if ($arr_global_settings['name'] == "site_email" || $arr_global_settings['name'] == "contact_email") {
    echo 'required:"Please enter an email address."';
} else if ($arr_global_settings['name'] == "site_title") {
    echo 'required:"Please enter a site title."';
} else if ($arr_global_settings['name'] == "contact_us_message") {
    echo 'required:"Please type a message."';
} else if ($arr_global_settings['name'] == "zip_code") {
    echo 'required:"Please enter a zip code."';
} else if ($arr_global_settings['name'] == "date_format") {
    echo 'required:"Please select a date format."';
} else if ($arr_global_settings['name'] == "default_currency") {
    echo 'required:"Please enter default currency."';
} else if ($arr_global_settings['name'] == "currency_symbol") {
    echo 'required:"Please enter currency symbol."';
} else if ($arr_global_settings['name'] == "per_page_record") {
    echo 'required:"Please enter per page record to display."';
} else if ($arr_global_settings['name'] == "default_meta_keyword") {
    echo 'required:"Please enter default meta keyword."';
} else if ($arr_global_settings['name'] == "default_meta_description") {
    echo 'required:"Please enter default meta description."';
}

if ($arr_global_settings['name'] == "site_email" || $arr_global_settings['name'] == "contact_mail") {
    echo ',email:"Please enter a valid email address."';
}

if ($arr_global_settings['name'] == "default_currency") {
    echo ',minlength:"Please enter only atlease three characters."';
    echo ',maxlength:"Please enter only atmost three characters."';
    echo ',lettersonly:"Please enter alphabetical characters."';
}

if ($arr_global_settings['name'] == "currency_symbol") {
    echo ',minlength:"Please enter only one character symbol."';
    echo ',maxlength:"Please enter only one character symbol."';
}
if ($arr_global_settings['name'] == "default_currency" || $arr_global_settings['name'] == "default_currency") {
    echo ',email:"Please enter a valid email address."';
}
if ($arr_global_settings['name'] == "per_page_record") {
    echo ',number:"Please enter valid number."';
}
?>
                                            }
                                    }
                            });
                                    jQuery.validator.addMethod("lettersonly", function(value, element) {
                                    return this.optional(element) || /^[A-Z]+$/i.test(value);
                                    },"");
                            });
                </script>

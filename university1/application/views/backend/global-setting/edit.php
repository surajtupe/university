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
                    <form role="form" name="frm_edit_global_setting_parameter"  id="frm_edit_global_setting_parameter" action="<?php echo base_url(); ?>backend/global-settings/edit/<?php echo $edit_id; ?>/<?php echo $lang_id; ?>" method="POST">
                        <input type="hidden" name="global_name_id" id="global_name_id" value="<?php echo $edit_id; ?>" />
                        <input type="hidden" name="lang_id" id="lang_id" value="<?php echo $lang_id; ?>" />
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Parameter Name</label>
                                <input class="form-control" type="text" dir="ltr" readonly  name="name" id="name" value="<?php echo ucwords(str_replace("_", " ", $arr_global_settings['name'])); ?>" />

                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Parameter Value<sup class="mandatory">*</sup></label>
                                <?php
                                if ($arr_global_settings['name'] == "date_format") {
                                    ?>
                                    <select name="value" class="form-control" id="value">
                                        <option <?php if ($arr_global_settings['value'] == "Y-m-d") { ?> selected="selected"<?php } ?> value="Y-m-d"><?php echo date("Y-m-d"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "Y/m/d") { ?> selected="selected"<?php } ?> value="Y/m/d"><?php echo date("Y/m/d"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "Y-m-d H:i:s") { ?> selected="selected"<?php } ?> value="Y-m-d H:i:s"><?php echo date("Y-m-d H:i:s"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "Y/m/d H:i:s") { ?> selected="selected"<?php } ?> value="Y/m/d H:i:s"><?php echo date("Y/m/d H:i:s"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "F j, Y, g:i a") { ?> selected="selected"<?php } ?> value="F j, Y, g:i a"><?php echo date("F j, Y, g:i a"); ?></option>
                                        <option <?php if ($arr_global_settings['value'] == "m.d.y") { ?> selected="selected"<?php } ?> value="m.d.y"><?php echo date("m.d.y"); ?></option>
                                    </select>
                                    <?php
                                } else if ($arr_global_settings['name'] == "address" || $arr_global_settings['name'] == 'contact_us_message') {
                                    ?>
                                    <textarea name="value" class="form-control" id="value"><?php echo stripcslashes(preg_replace("/[\\n\\r]+/", " ", $arr_global_settings['value'])); ?></textarea>
                                <?php } else if ($arr_global_settings['name'] == "OTP_expired") {
                                    ?>
                                    <input type="text" dir="ltr" class="form-control" name="value" id="value" value="<?php echo htmlentities(stripslashes($arr_global_settings['value'])); ?>"/> (In Minutes)
                                <?php } else {
                                    ?>
                                    <input type="text" dir="ltr" class="form-control" name="value" id="value" value="<?php echo htmlentities(stripslashes($arr_global_settings['value'])); ?>" />
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
                <script type="text/javascript">
                    jQuery(document).ready(function (e) {
                    jQuery("#frm_edit_global_setting_parameter").validate({
                    errorElement: "div",
                            rules: {

                            value:{
                            required:true
<?php
if ($arr_global_settings['name'] == "site_email") {
    echo ",email:true";
}
if ($arr_global_settings['name'] == "contact_email") {
    echo ",email:true";
}

if ($arr_global_settings['name'] == "per_page_record") {
    echo ",number:true";
}

if ($arr_global_settings['name'] == "phone_no") {
    echo ",number:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/";
    echo ",minlength:7";
}
if ($arr_global_settings['name'] == "OTP_expired") {
    echo ",number:/\(?([0-9]{3})\)?([]?)([0-9]{3})\2([0-9]{4})/";
    echo ",range: [1, 59]";
}
if ($arr_global_settings['name'] == "zip_code") {
    echo ",number:true";
    echo ",minlength:6";
    echo ",maxlength:6";
}

if ($arr_global_settings['name'] == "facebook_link" || $arr_global_settings['name'] == "twitter_link" || $arr_global_settings['name'] == "Google+_link" || $arr_global_settings['name'] == "instagram_link" || $arr_global_settings['name'] == "Linkedin_link" || $arr_global_settings['name'] == "ios_app_link" || $arr_global_settings['name'] == "android_app_link" || $arr_global_settings['name'] == "youtube_link" || $arr_global_settings['name'] == "link4" || $arr_global_settings['name'] == "link5" || $arr_global_settings['name'] == "link6") {
    echo ",url:true";
}
?>

                            }
                            },
                            messages:{

                            value:{
<?php
if ($arr_global_settings['name'] == "site_email") {
    echo 'required:"Please enter an email address."';
    echo ',email:"Please enter a valid email address."';
} else if ($arr_global_settings['name'] == "site_title") {
    echo 'required:"Please enter a site title."';
} else if ($arr_global_settings['name'] == "date_format") {
    echo 'required:"Please select a date format."';
} else if ($arr_global_settings['name'] == "per_page_record") {
    echo 'required:"Please enter per page record to display."';
}

if ($arr_global_settings['name'] == "contact_email") {
    echo 'required:"Please enter an  email address."';
    echo ',email:"Please enter a valid email address."';
}
if ($arr_global_settings['name'] == "facebook_link") {
    echo 'required:"Please enter facebook link."';
    echo',url:"Please enter valid link."';
}

if ($arr_global_settings['name'] == "twitter_link") {
    echo 'required:"Please enter twitter link."';
    echo ',url:"Please enter valid link."';
}

if ($arr_global_settings['name'] == "Google+_link") {
    echo 'required:"Please enter google+ link."';
    echo',url:"Please enter valid link."';
}

if ($arr_global_settings['name'] == "instagram_link") {
    echo 'required:"Please enter instagram link."';
    echo ',url:"Please enter valid link."';
}

if ($arr_global_settings['name'] == "linkedin_link") {
    echo 'required:"Please enter linkedin link."';
    echo ',url:"Please enter valid link."';
}

if ($arr_global_settings['name'] == "youtube_link") {
    echo 'required:"Please enter youtube link."';
    echo ',url:"Please enter valid link."';
}

if ($arr_global_settings['name'] == "ios_app_link") {
    echo'required:"Please enter IOS app link."';
    echo',url:"Please enter valid link."';
}
if ($arr_global_settings['name'] == "android_app_link") {
    echo'required:"Please enter android app link."';
    echo',url:"Please enter valid link."';
}
if ($arr_global_settings['name'] == "link3") {
    echo'url:"Please enter valid link."';
}
if ($arr_global_settings['name'] == "link4") {
    echo'url:"Please enter valid link."';
}
if ($arr_global_settings['name'] == "link5") {
    echo'url:"Please enter valid link."';
}
if ($arr_global_settings['name'] == "link6") {
    echo'url:"Please enter valid link."';
}


if ($arr_global_settings['name'] == "phone_no") {
    echo 'required:"Please enter a number."';
    echo ',number:"please enter only number."';
    echo ',minlength:"please enter atleast 9 number."';
}
if ($arr_global_settings['name'] == "OTP_expired") {
    echo 'required:"Please enter OTP expired time."';
    echo ',number:"please enter valid time."';
}
if ($arr_global_settings['name'] == "zip_code") {
    echo 'required:"Please enter zipcode."';
    echo ',number:"please enter only number."';
    echo ',minlength:"please enter atleast 6 number."';
    echo ',maxlength:"please enter only 6 number."';
}
if ($arr_global_settings['name'] == "address") {
    echo 'required:"Please enter address."';
}
if ($arr_global_settings['name'] == "street") {
    echo 'required:"Please enter street."';
}
if ($arr_global_settings['name'] == "city") {
    echo 'required:"Please enter city."';
}
?>
                            }
                            }
                    }
                    );
                    });
                </script>
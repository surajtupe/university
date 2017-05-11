<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo (isset($title)) ? $title : $global['site_title']; ?></title>
        <?php $this->load->view('backend/sections/header'); ?>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/jquery.validate.min.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/user-manage/add-user.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>media/backend/css/common.css"/>
        <script src="<?php echo base_url(); ?>media/front-media/js/jquery.validate.password.js"></script>
        <script>
            jQuery(document).ready(function (e) {
                jQuery("#frm_user_details").validate({
                    debug: true,
                    errorClass: 'error',
                    rules: {
                        user_email: {
                            required: true
                        },
                        user_password: {
                            required: true,
                            minlength: 8
                        },
                        confirm_password: {
                            required: true,
                            minlength: 8
                        }
                    },
                    messages: {
                        user_email: {
                            required: "Please enter email."
                        },
                        user_password: {
                            required: "Please enter password.",
                            minlength: "Please enter atleast 8 characters."
                        },
                        confirm_password: {
                            required: "Please enter password.",
                            minlength: "Please enter atleast 8 characters."
                        }
                    }, submitHandler: function (form) {
                        jQuery("#btn_login").hide();
                        jQuery("#btn_loader").show();
                        form.submit();
                    }
                });
            });
        </script>
    </head>
    <body>
        <?php $this->load->view('backend/sections/top-nav.php'); ?>
        <?php $this->load->view('backend/sections/leftmenu.php'); ?>
        <div id="content" class="span10"> 
            <!--[breadcrumb]-->
            <div>
                <ul class="breadcrumb">
                    <li> <a href="<?php echo base_url(); ?>backend/dashboard">Dashboard</a> <span class="divider">/</span> </li>
                    <li> <a href="<?php echo base_url(); ?>backend/user/list">Manage User</a> <span class="divider">/</span></li>
                    <li> Add User </li>
                </ul>
            </div>
            <div class="row-fluid sortable"> 
                <!--[sortable header start]-->
                <div class="box span12">
                    <div class="box-header well">
                        <h2><i class=""></i>Add User</h2>
                        <div class="box-icon"> <a title="Manage USer" class="btn btn-plus btn-round" href="<?php echo base_url(); ?>backend/user/list"><i class="icon-arrow-left"></i></a> </div>
                    </div>
                    <br >
                    <!--[sortable body]-->
                    <div class="box-content">
                        <form name="frm_user_details" id="frm_user_details" class="form-horizontal" action="<?php echo base_url(); ?>backend/user/add" method="post" >
                            <div class="control-group">
                                <label for="typeahead" class="control-label">Username<sup class="mandatory">*</sup> </label>
                                <div class="controls">
                                    <input type="text" value="" id="user_name" name="user_name" class="FETextInput">
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="typeahead" class="control-label">Email Id<sup class="mandatory">*</sup> </label>
                                <div class="controls">
                                    <input type="text" value="" name="user_email" id="user_email" class="FETextInput">
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="typeahead" class="control-label">Password<sup class="mandatory">*</sup> </label>
                                <div class="controls">
                                    <input type="password" id="user_password" name="user_password" class="FETextInput">
                                    <div>
                                        <div class="password-meter" style="display:none">
                                            <div class="password-meter-message password-meter-message-too-short">Too short</div>
                                            <div class="password-meter-bg">
                                                <div class="password-meter-bar password-meter-too-short"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="typeahead" class="control-label">Confirm Password<sup class="mandatory">*</sup> </label>
                                <div class="controls">
                                    <input type="password" id="confirm_password" name="confirm_password" class="FETextInput">
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="typeahead" class="control-label">I Wear</label>
                                <select class="form-control" name="i_wear" id="i_wear">
                                    <option value="">I Wear.....</option>
                                    <option>men's tees</option>
                                    <option>women's tees</option>
                                </select>
                            </div>
                            <div class="row_box">
                                <div class="box12  text_center">
                                    <label class="small">Subscription for news letter</label>
                                    <input type="checkbox" id="i_agree" name="Subscription for news letter">
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save changes">Save</button>
                            </div>
                        </form>
                    </div>
                    <!--[sortable body]--> 
                </div>
            </div>
            <!--[sortable table end]--> 
            <!--[include footer]--> 
        </div>
        <!--/#content.span10-->
    </div>
    <!--/fluid-row-->
    <?php $this->load->view('backend/sections/footer.php'); ?>
</div>
</body>
</html>
<link rel="stylesheet" href="<?php echo base_url(); ?>media/front/css/jquery.validate.password.css" />
<script src="<?php echo base_url(); ?>media/front/js/jquery.validate.password.js"></script>

<section class="middle-section">
    <div class="container">
        <div class="menu clearfix">
            <?php if ($this->session->userdata('msg_failed') != '') { ?>  
                <div class="alert alert-danger">
                    <?php echo $this->session->userdata('msg_failed'); ?>
                    <button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>
                </div>
                <?php
                $this->session->unset_userdata('msg_failed');
            }
            if ($this->session->userdata('msg_success') != '') {
                ?>  
                <div class="alert alert-success">
                    <?php echo $this->session->userdata('msg_success'); ?>
                    <button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>
                </div>
                <?php
                $this->session->unset_userdata('msg_success');
            }
            ?> 
            <nav>
                <ul class="clean">
                    <li class="active"><a href="javascript:void(0)">PROFILE</a></li>
                    <li><a href="javascript:void(0)">CONTACTS </a></li>
                    <li><a href="javascript:void(0)">  ALERTS   </a></li>
                    <li><a href="javascript:void(0)">   SERVICES   </a></li>
                    <li><a href="javascript:void(0)">SETTINGS</a></li>
                </ul>
            </nav>
            <a href="javascript:void(0)" class="pull-right edit-btn" onclick="chkPassword('edit_profile')">Edit</a>
        </div>
        <div class="mid-section">
            <h2> Change Password </h2>
            <div class="media">
                <form id="frm_edit_account_setting" name="frm_edit_account_setting" method="post" action="<?php echo base_url(); ?>change-password">
                    <div class="form-group">
                        <label for="user_email">New Password<sup class="mandatory">*</sup></label>
                        <input type="password" class="form-control" name="new_user_password" id="new_user_password" placeholder="New Password"> 
                        <span id="result"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Confirm New Password<sup class="mandatory">*</sup></label>
                        <input type="password" class="form-control" name="cnf_user_password" id="cnf_user_password" placeholder="Confirm New Password">                                                   
                    </div>                                
                    <div class="form-group">
                        <label for="security_code">Enter The Security Code<sup class="mandatory">*</sup></label>
                        <input type="text" class="form-control" name="security_code" id="security_code" placeholder="Security Code">                                                   
                        <sub>(We have sent security code to your email. Please check and use that code here.)</sub>
                        <div for="security_code" generated="true" class="text-danger"></div>
                    </div>       
                    <button type="submit" name="btn_account_setting"  id="btn_account_setting" class="btn btn-default login-btn">Save Changes</button> 
                    <img id="btn_loader" style="display: none;" src="<?php echo base_url(); ?>media/front/img/loader.gif" border="0">
                </form>
            </div>
        </div>
    </div>
</section>
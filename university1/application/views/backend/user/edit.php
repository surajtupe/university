<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>

<aside class="right-side">
    <section class="content-header">
        <h1>
            Edit User Details 

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>backend/user/list"><i class="fa fa-user"></i> Manage Users</a></li>
            <li class="active">	Edit User Details  </li>

        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form name="frm_user_details" role="form" id="frm_user_details" action="<?php echo base_url(); ?>backend/user/edit/<?php echo $edit_id; ?>" method="post">
                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="User Name">Title <sup class="mandatory">*</sup></label>
                                        <select class="form-control" name="title" id="title">
                                            <option value="mr" <?php echo ($arr_admin_detail['title'] == 'mr') ? 'selected' : '' ?>>Mr.</option>
                                            <option value="ms"<?php echo ($arr_admin_detail['title'] == 'ms') ? 'selected' : '' ?>>Ms.</option>
                                            <option value="mrs"<?php echo ($arr_admin_detail['title'] == 'mrs') ? 'selected' : '' ?>>Mrs.</option>
                                        </select>    
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">First Name <sup class="mandatory">*</sup></label>
                                        <input type="text" value="<?php echo $arr_admin_detail['first_name']; ?>" id="first_name" name="first_name" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="User Name">Middle Name </label>
                                        <input type="text" value="<?php echo $arr_admin_detail['middle_name']; ?>" id="middle_name" name="middle_name" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="Last Name">Last Name <sup class="mandatory">*</sup></label>

                                        <input type="text" name="last_name" id="last_name" value="<?php echo $arr_admin_detail['last_name']; ?>"  class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="Email Id">Email Id<sup class="mandatory">*</sup></label>

                                        <input type="text" value="<?php echo stripslashes($arr_admin_detail['user_email']); ?>" name="user_email" id="user_email" class="form-control">
                                        <input type="hidden" value="<?php echo stripslashes($arr_admin_detail['user_email']); ?>" name="old_email" id="old_email" class="form-control">		
                                    </div>
                                    <div class="form-group">
                                        <label for="Change Password">Change Status<sup class="mandatory">*</sup></label>
                                        <select id="user_status" name="user_status"  class="form-control">
                                            <?php
                                            if ($arr_admin_detail['user_status'] == 0) {
                                                ?>
                                                <option value="0">Inactive</option>
                                                <?php
                                            }
                                            ?>
                                            <option value="1" <?php if ($arr_admin_detail['user_status'] == 1) { ?> selected="selected" <?php } ?>>Active</option>
                                            <option value="2" <?php if ($arr_admin_detail['user_status'] == 2) { ?> selected="selected" <?php } ?>>Blocked</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Change Password">Change Password</label>

                                        <input type="checkbox" name="change_password"  id="change_password" class="hide-show-pass-div">	
                                    </div>

                                    <div id="change_password_div" style="display:none;">
                                        <div class="form-group">
                                            <label for="New Password">New Password<sup class="mandatory">*</sup></label>

                                            <input type="password" id="user_password" name="new_user_password" class="form-control">
                                            <div class="password-meter" style="display:none">
                                                <div class="password-meter-message password-meter-message-too-short">Too short</div>
                                                <div class="password-meter-bg">
                                                    <div class="password-meter-bar password-meter-too-short"></div>
                                                </div>
                                            </div>
                                            <span> (Password must be combination of atleast 1 number, 1 special character and 1 upper case letter with minimum 8 characters) </span> 
                                        </div>

                                        <div class="form-group">
                                            <label for="New Password">Confirm Password<sup class="mandatory">*</sup></label>                               
                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                        </div>
                                    </div>                                     
                                    <div class="form-group">
                                        <div class="col-md-12"> 
                                            <label for="date of birth">Date Of Birth<sup class="mandatory">*</sup></label>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" name="day" id="day">
                                                <option value="">DD</option>
                                                <?php
                                                $birth_date = explode('/', $arr_admin_detail['user_birth_date']);
                                                $maxDays = date('t');
                                                for ($day = 1; $day <= $maxDays; ++$day) {
                                                    ?>
                                                    <option value="<?php echo $day ?>" <?php echo ($day == $birth_date[0]) ? 'selected' : '' ?>><?php echo $day ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control"  name="month" id="month">
                                                <option value="">MM</option>
                                                <?php
                                                for ($month = 1; $month <= 12; ++$month) {
                                                    ?> 
                                                    <option value="<?php echo $month; ?>" <?php echo ($month == $birth_date[1]) ? 'selected' : '' ?>><?php echo $month; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" name="year" id="year">
                                                <option value="">YYYY</option>
                                                <?php
                                                $year_conter = date('Y') - 70;
                                                for ($year = 1; $year <= 70; ++$year) {
                                                    ?>  
                                                    <option value="<?php echo $year_conter ?>" <?php echo ($year_conter == $birth_date[2]) ? 'selected' : '' ?>><?php echo $year_conter ?></option>
                                                    <?php
                                                    $year_conter++;
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save changes">Save Changes</button>
                                <input type="hidden" name="edit_id" id="edit_id" value="<?php echo intval(base64_decode($edit_id)); ?>" />
                            </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/user-manage/edit-user.js"></script>
        <!--<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/admin-manage/edit-admin.js"></script>-->
        <script src="<?php echo base_url(); ?>media/front/js/jquery.validate.password.js"></script>

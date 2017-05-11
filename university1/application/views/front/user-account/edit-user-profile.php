<section class="middle-section">
    <div class="container">
        <div class="mid-section">
            <div class="head">
                <h2>Profile</h2>
            </div>
            <form id="frm_edit_profile" name="frm_edit_profile" method="post" action="" class="form-horizontal">
                <div class="form-group">
                    <div class="col-xs-12">
                        <a href="<?php echo base_url() ?>profile" id="back_btn_curr_add" class="back-btn pull-right">BACK</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-5 col-sm-3 code padding-right-0">
                        <label>Title </label>
                        <div class="select-box">
                            <select class="form-control" name="title" id="title">
                                <option value="mr" <?php echo ($arr_user_data['title'] == 'mr') ? 'selected' : '' ?>>Mr.</option>
                                <option value="ms"<?php echo ($arr_user_data['title'] == 'ms') ? 'selected' : '' ?>>Ms.</option>
                                <option value="mrs"<?php echo ($arr_user_data['title'] == 'mrs') ? 'selected' : '' ?>>Mrs.</option>
                            </select>      
                        </div>
                    </div>
                    <div class="col-xs-7 col-sm-9">
                        <label>FIRST NAME</label>
                        <input class="form-control" placeholder="FIRST NAME" id="first_name" name="first_name" value="<?php echo $arr_user_data['first_name']; ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">MIDDLE NAME</label>
                    <div class="col-xs-12">
                        <input placeholder="MIDDLE NAME" class="form-control" name="middle_name" id="middle_name" value="<?php echo $arr_user_data['middle_name'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">LAST NAME</label>
                    <div class="col-xs-12">
                        <input placeholder="LAST NAME" class="form-control" name="last_name" id="last_name" value="<?php echo $arr_user_data['last_name'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">E-MAIL</label>
                    <div class="col-xs-12">
                        <input placeholder="E-MAIL" class="form-control" id="user_email" name="user_email" value="<?php echo $arr_user_data['user_email'] ?>">
                        <input type="hidden" id="user_email_old" name="user_email_old" value="<?php echo $arr_user_data['user_email'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">DATE OF BIRTH</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="select-box">
                            <select class="form-control" name="day" id="day">
                                <option value="">DD</option>
                                <?php
                                $birth_date = explode('/', $arr_user_data['user_birth_date']);
                                $maxDays = date('t');
                                for ($day = 1; $day <= $maxDays; ++$day) {
                                    ?>
                                    <option value="<?php echo $day ?>" <?php echo ($day == $birth_date[0]) ? 'selected' : '' ?>><?php echo $day ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="select-box">
                            <select class="form-control"  name="month" id="month">
                                <option value="">MM</option>
                                <?php
                                for ($month = 1; $month <= 12; ++$month) {
                                    ?> 
                                    <option value="<?php echo $month; ?>" <?php echo ($month == $birth_date[1]) ? 'selected' : '' ?>><?php echo $month; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="select-box">
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
                <div class="form-group">
                    <label class="col-xs-12">Change Password
                        <input type="checkbox" name="change_password"  id="change_password" class="hide-show-pass-div">	
                    </label>
                </div>
                <div id="change_password_div" style="display:none;">
                    <div class="form-group">
                        <label class="col-xs-12">New Password</label>
                        <div class="col-xs-12">
                            <input type="password" id="user_password" name="new_user_password" class="form-control">
                            <div class="password-meter" style="display:none"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">Confirm Password</label>
                        <div class="col-xs-12">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                        </div>
                    </div>
                </div>  
                <div class="text-center offset-top-25 offset-bot-15">
                    <button class="btn blue-btn" name="btn_submit" id="btn_submit">SAVE CHANGES</button>
                    <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                </div>
            </form>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#check_box").css({display: "block", opacity: "0", height: "0", width: "0", "float": "right"});

        jQuery(".hide-show-pass-div").on("click", function() {


            if (jQuery(".hide-show-pass-div").is(":checked"))
            {
                jQuery('#change_password_div').css('display', 'block');
            }
            else
            {
                jQuery('#change_password_div').css('display', 'none');
            }
        });
    });
</script>
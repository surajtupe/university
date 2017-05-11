<body class="inner-pages" id="body_tag">
    <header>
        <div class="text-center container logo-bg">
            <a href="<?php echo base_url() ?>"><img src="<?php echo base_url(); ?>media/front/img/inner-logo.png"/></a>
            <p>UPDATE YOUR ADDRESS SMARTLY</p>
        </div>
    </header>
    <section class="middle-section" id="first_step">
        <div class="container">
            <div class="mid-section">
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
                <div class="alert_msg_div"></div>
                <div class="head">
                    <h2>REGISTER</h2>
                </div>
                <form class="form-horizontal" method="post" id="first_step_registration" name="first_step_registration" action="">
                    <div id="first_step_registarion">
                        <div class="form-group">
                            <label class="col-xs-12">Mobile</label>
                            <div class="col-xs-5 col-sm-3 code padding-right-0">
                                <?php if (isset($get_country_code_details) && COUNT($get_country_code_details) > 0) { ?>
                                    <span><img src="<?php echo base_url() ?>media/backend/img/country-flag/thumbs/<?php echo $get_country_code_details[0]['flag']; ?>" /></span>
                                    <input class="form-control country-code" value="<?php echo $get_country_code_details[0]['country_phone_code']; ?>" id="country_code" name="country_code" readonly/>
                                <?php } else { ?>
                                    <span><img src="<?php echo base_url() ?>media/front/img/flag.png" /></span>
                                    <input class="form-control country-code" value="+91" id="country_code" name="country_code" readonly/>
                                <?php } ?>
                            </div>
                            <div class="col-xs-7 col-sm-9">
                                <input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="ENTER 10 DIGIT MOBILE NUMBER" autofocus/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12">PASSWORD</label>
                            <div class="col-xs-12">
                                <input type="password" class="form-control" placeholder="MIN 8 CHARS,1 UPPERCASE,1 NUM & 1 SPL CHAR" name="password" id="password"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12">CONFIRM PASSWORD</label>
                            <div class="col-xs-12">
                                <input type="password" class="form-control" placeholder="MIN 8 CHARS,1 UPPERCASE,1 NUM & 1 SPL CHAR" id="confirm_password" name="confirm_password"/>
                            </div>
                        </div>
                    </div>
                    <div id="otp_number_div" class="form-group" style="display: none">
                        <label class="col-xs-12">ENTER OTP</label>
                        <div class="col-xs-12">
                            <input type="text" class="form-control" placeholder="OTP" id="otp_number" name="otp_number"/>

                        </div>
                        <div class="col-xs-12 ft-otp">
                            <div class="pull-left">
                                <input type="checkbox" id="accept_terms_conditions" name="accept_terms_conditions"/> I ACCEPT THE <a href="<?php echo base_url() ?>cms/terms-conditions" class="" target="_blank">TERMS AND CONDITIONS. </a>
                                <div for="accept_terms_conditions" generated="true" class="text-danger"></div>
                            </div>
                            <div class="pull-right">
                                <a href="javascript:void(0)" onClick="resendVerificationCode()" class="text-uppercase">Resend OTP</a>
                            </div>
                        </div>
                        <div class="text-center ft-text">Your OTP has been expired within <?php echo $global['OTP_expired'] ?> minute.</div>
                    </div>
                    <div class="text-center clearfix offset-top-25">
                        <button class="btn blue-btn registration_btn" name="btn_submit" id="btn_submit">REGISTER</button>
                        <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                    </div>
                    <hr class="offset-top-45 offset-bot-10">
                    <div class="text-center ft-text">HAVE AN ACCOUNT? <a href="<?php echo base_url() ?>sign-in">SIGN IN </a></div>
                </form>
            </div>
        </div>
    </section>
    <section class="middle-section" id="second_step" style="display: none">
        <div class="container">
            <div class="mid-section">
                <div class="alert_msg_div"></div>
                <div class="head">
                    <label>PROVIDE DETAILS AS PER GOVERNMENT/ADHAR RECORDS.</label>
                    <h2>Profile</h2>
                </div>
                <form id="frm_complete_profile" name="frm_complete_profile" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="col-xs-5 col-sm-3 code padding-right-0">
                            <label>Title </label>
                            <div class="select-box">
                                <select class="form-control" name="title" id="title">
                                    <option value="mr">Mr.</option>
                                    <option value="ms">Ms.</option>
                                    <option value="mrs">Mrs.</option>
                                </select>      
                            </div>
                        </div>
                        <div class="col-xs-7 col-sm-9">
                            <label>FIRST NAME</label>
                            <input class="form-control" placeholder="FIRST NAME" id="first_name" name="first_name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">MIDDLE NAME</label>
                        <div class="col-xs-12">
                            <input placeholder="MIDDLE NAME" class="form-control" name="middle_name" id="middle_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">LAST NAME</label>
                        <div class="col-xs-12">
                            <input placeholder="LAST NAME" class="form-control" name="last_name" id="last_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">E-MAIL</label>
                        <div class="col-xs-12">
                            <input placeholder="E-MAIL" class="form-control" id="user_email" name="user_email">
                            <input type="hidden" placeholder="E-MAIL" class="form-control" id="user_email_back" name="user_email_back">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">DATE OF BIRTH</label>
                        <div class="col-xs-12 col-sm-4">
                            <div class="select-box">
                                <select class="form-control" name="day" id="day">
                                    <option value="">DD</option>
                                    <?php
                                    $maxDays = date('t');
                                    for ($day = 1; $day <= $maxDays; ++$day) {
                                        ?>
                                        <option value="<?php echo $day ?>"><?php echo $day ?></option>
                                    <?php } ?>
                                </select></div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="select-box">
                                <select class="form-control"  name="month" id="month">
                                    <option value="">MM</option>
                                    <?php
                                    for ($month = 1; $month <= 12; ++$month) {
                                        ?> 
                                        <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
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
                                        <option value="<?php echo $year_conter ?>"><?php echo $year_conter ?></option>
                                        <?php
                                        $year_conter++;
                                    }
                                    ?>
                                </select></div>
                        </div>
                    </div>
                    <div class="text-center offset-top-25 offset-bot-15">
                        <input type="hidden" name="last_inser_id" id="last_inser_id" value="">
                        <input type="hidden" name="phone_number" id="phone_number" value="">
                        <!--<a class="btn blue-btn" name="btn_submit_cp" id="btn_submit_cp" onclick="thirdStepCompleteProfile()">Submit</a>-->
                        <button class="btn blue-btn" name="btn_submit_cp" id="btn_submit_cp">Submit</button>
                        <div id="loader_cp" style="display: none" class="three-quarters-loader">Loading…</div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="middle-section" id="current_address_step" style="display: none">
        <div class="container">
            <div class="mid-section">
                <div class="alert_msg_div"></div>
                <div class="head">
                    <h2>CURRENT HOME ADDRESS</h2>
                </div>
                <form class="form-horizontal" id="frm_current_address" name="frm_current_address" method="post" action="" enctype="multipart/form-data">
                    <div id="current_address_div">
                        <div class="buliding-photo">
                            <div class="build-img">
                                <a href="javascript:void(0);" onClick="openFileCurrentAddress();"> <img src="<?php echo base_url() ?>media/front/img/add-img.png" id="current_add_img" /></a>
                                <input type="file" value="" id="curr_add_building_pic" name="curr_add_building_pic">
                            </div>
                            <p>ADDRESS NAME: <span><?php echo $curr_address_name ?></span> </p>
                            <input type="hidden" value="<?php echo $curr_address_name ?>" class="form-control" id="current_add_address_name" name="current_add_address_name">
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6">
                                <label>COUNTRY</label>
                                <div class="select-box">
                                    <select class="form-control" name="current_add_country" id="current_add_country" onChange="currentAddStateInfo(this.value);">
                                        <option value=""> SELECT COUNTRY</option>
                                        <?php
                                        if (isset($arr_country_details) && COUNT($arr_country_details) > 0) {
                                            foreach ($arr_country_details as $country_details) {
                                                ?>
                                                <option value="<?php echo $country_details['country_id'] ?>"> <?php echo $country_details['country_name'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label>STATE</label>
                                <div class="select-box" id="curr_add_state_div">
                                    <select class="form-control" name="current_add_state" id="current_add_state">
                                        <option value=""> SELECT STATE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6">
                                <label>CITY</label>
                                <div class="select-box" id="curr_add_city_div">
                                    <select class="form-control" name="current_add_city" id="current_add_city">
                                        <option value=""> SELECT CITY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label>ZIPCODE</label>
                                <input type="text" class="form-control" id="current_add_zipcode" name="current_add_zipcode" placeholder="ZIPCODE" onBlur="currentAddressMap(this.value)"> 
                                <div for="current_add_zipcode" style="display: none;" id='error_zipcode_current' generated="true" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12">ADDRESS LINE 1</label>
                            <div class="col-xs-12">
                                <textarea class="form-control" name="current_add_first" id="current_add_first" placeholder="ADDRESS LINE 1"></textarea>
                            </div>
                        </div>
                        <div class="form-group offset-bot-0">
                            <label class="col-xs-12">ADDRESS LINE 2</label>
                            <div class="col-xs-12">
                                <textarea class="form-control" name="current_add_second" id="current_add_second" placeholder="ADDRESS LINE 2"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="checkbox"><label><input type="checkbox" name="current_location_same_as_above" id="current_location_same_as_above" /> MY CURRENT LOCATION IS SAME AS ABOVE ADDRESS</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="location" id="current_address_map_div" style="display: none">
                        <p>LOCATION YOUR CURRENT HOME ADDRESS ON THE MAP</p>
                        <div class="" id="current_add_map" style="height: 283px;width: 422px"></div>
                        <input type="hidden" name="current_add_lat" id="current_add_lat" value="">
                        <input type="hidden" name="current_add_long" id="current_add_long" value="">
                    </div> 
                    <div class="ft-box">
                        <input type="hidden" id="c_last_inser_id" name="c_last_inser_id" value="">
                        <input type="hidden" id="current_address_id_bk" name="current_address_id_bk" value="">
                        <a href="javascript:void(0);" onClick="forthStepBackBtn()" id="back_btn_curr_add" class="back-btn">Back</a>
                        <a href="javascript:void(0);" onClick="forthStepBackBtnMap()" id="back_btn_curr_add_map" class="back-btn" style="display: none;float: left;">Back</a>
                        <a href="javascript:void(0);" id="next_btn_current_add" onClick="forthStepCurrentAddress()" class="next-btn">Next</a>
                        <div id="loader_curr_add" style="display: none" class="three-quarters-loader">Loading…</div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="middle-section" id="permanent_address_step" style="display: none">
        <div class="container">
            <div class="mid-section">
                <div class="alert_msg_div"></div>
                <div class="head">
                    <h2>Permanent HOME ADDRESS</h2>
                </div>
                <form class="form-horizontal" id="frm_permanent_address" name="frm_permanent_address" method="post" action="" enctype="multipart/form-data">
                    <div id="permanent_address_div">
                        <div class="buliding-photo">
                            <div class="build-img">
                                <a href="javascript:void(0);"> 
                                    <img src="<?php echo base_url() ?>media/front/img/add-img.png" id="permanent_add_img" class="premanent_add_img"/>
                                    <div id="current_add_img_for_same_add" style="display: none"></div>
                                </a>
                                <input type="file" value="" id="per_add_building_pic" name="per_add_building_pic">
                            </div>
                            <p>ADDRESS NAME: <span><?php echo $perm_address_name ?></span> </p>
                            <input type="hidden" value="<?php echo $perm_address_name ?>" class="form-control" id="permanant_address_name" name="permanant_address_name">
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="checkbox"><label><input type="checkbox" name="permanent_add_same_as_current" id="permanent_add_same_as_current" /> MY PERMANENT ADDRESS SAME AS CURRENT ADDRESS.</label></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6">
                                <label>COUNTRY</label>
                                <div class="select-box">
                                    <select class="form-control" name="permanant_add_country" id="permanant_add_country" onChange="permanantAddStateInfo(this.value);">
                                        <option value=""> SELECT COUNTRY</option>
                                        <?php
                                        if (isset($arr_country_details) && COUNT($arr_country_details) > 0) {
                                            foreach ($arr_country_details as $country_details) {
                                                ?>
                                                <option value="<?php echo $country_details['country_id'] ?>"> <?php echo $country_details['country_name'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" id="permanant_add_country_id" name="permanant_add_country_id" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label>STATE</label>
                                <div class="select-box" id="per_add_state_div">
                                    <select class="form-control" name="permanant_add_state" id="permanant_add_state">
                                        <option value=""> SELECT STATE</option>
                                    </select>
                                </div>
                                <div class="select-box" id="per_add_state_div_for_same_add" style="display: none">
                                    <select class="form-control" name="permanant_add_state_same_add" id="permanant_add_state_same_add">
                                        <?php
                                        if (COUNT($arr_state_details) > 0) {
                                            foreach ($arr_state_details as $state) {
                                                ?>
                                                <option value="<?php echo $state['state_id_fk'] ?>"><?php echo $state['state_name'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?> 
                                    </select>
                                    <input type="hidden" id="permanant_add_state_id" name="permanant_add_state_id" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6">
                                <label>CITY</label>
                                <div class="select-box" id="per_add_city_div">
                                    <select class="form-control" name="permanant_add_city" id="permanant_add_city">
                                        <option value=""> SELECT CITY</option>
                                    </select>
                                </div>
                                <div class="select-box" id="per_add_city_div_for_same_add" style="display: none">
                                    <select class="form-control" name="permanant_add_city_same_add" id="permanant_add_city_same_add">
                                        <?php
                                        if (COUNT($arr_city_details) > 0) {
                                            foreach ($arr_city_details as $cities) {
                                                ?>
                                                <option value="<?php echo $cities['city_id_fk'] ?>"><?php echo $cities['city_name'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="permanant_add_city_id" id="permanant_add_city_id" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label>ZIPCODE</label>
                                <input type="text" class="form-control" id="permanant_add_zipcode" name="permanant_add_zipcode" placeholder="ZIPCODE" onBlur="permanentAddressMAP(this.value)"> 
                                <div for="permanant_add_zipcode" style="display: none;" id='error_zipcode' generated="true" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12">ADDRESS LINE 1</label>
                            <div class="col-xs-12">
                                <textarea class="form-control" name="permanant_add_first" id="permanant_add_first" placeholder="ADDRESS LINE 1"></textarea>
                            </div>
                        </div>
                        <div class="form-group offset-bot-0">
                            <label class="col-xs-12">ADDRESS LINE 2</label>
                            <div class="col-xs-12">
                                <textarea class="form-control" name="permanant_add_second" id="permanant_add_second" placeholder="ADDRESS LINE 2"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="checkbox"><label><input type="checkbox" name="permanent_location_same_as_above" id="permanent_location_same_as_above" /> MY PERMANENT LOCATION IS SAME AS ABOVE ADDRESS</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="location" id="permanent_address_map_div" style="display: none">
                        <p>LOCATION YOUR PERMANENT HOME ADDRESS ON THE MAP</p>
                        <div class="" id="paermanent_add_map" style="height: 283px;width: 422px"></div>
                        <input type="hidden" name="permanant_add_lat" id="permanant_add_lat" value="">
                        <input type="hidden" name="permanant_add_long" id="permanant_add_long" value="">
                    </div> 
                    <div class="ft-box">
                        <input type="hidden" id="p_last_inser_id" name="p_last_inser_id" value="">
                        <input type="hidden" id="permanent_address_id_bk" name="permanent_address_id_bk" value="">
                        <input type="hidden" id="current_address_id" name="current_address_id" value="">
                        <input type="hidden" id="current_address_image" name="current_address_image" value="">
                        <a href="javascript:void(0);" onClick="fifthStepBackBtn()" id="back_btn_per_add" class="back-btn">Back</a>
                        <a href="javascript:void(0);" onClick="fifthStepBackBtnMap()" id="back_btn_per_add_map" class="back-btn" style="display: none;float: left;">Back</a>
                        <a href="javascript:void(0);" id="next_btn_per_add" onClick="fifthStepPermanentAddress()" class="next-btn">Next</a>
                        <div id="loader_per_add" style="display: none" class="three-quarters-loader">Loading…</div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="middle-section" id="office_address_step" style="display: none">
        <div class="container">
            <div class="mid-section">
                <div class="alert_msg_div"></div>
                <div class="head">
                    <h2>OFFICE ADDRESS</h2>
                </div>
                <form class="form-horizontal" id="frm_office_address" name="frm_office_address" method="post" action="">
                    <div id="office_address_div">
                        <div class="buliding-photo">
                            <div class="build-img">
                                <a href="javascript:void(0);" onClick="openFileofficeAddress();"> <img src="<?php echo base_url() ?>media/front/img/add-img.png" id="office_add_img" /></a>
                                <input type="file" value="" id="office_add_building_pic" name="office_add_building_pic">
                            </div>
                            <p>ADDRESS NAME: <span><?php echo $office_address_name ?></span> </p>
                            <input type="hidden" value="<?php echo $office_address_name ?>" class="form-control" id="office_address_name" name="office_address_name">
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input type="hidden" id="skip_parameter" name="skip_parameter" value="">
                                <a href="javascript:void(0);" id="next_btn_office_add" onClick="sixthStepOfficeAddress('Yes')" class="next-btn">SKIP</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6">
                                <label>COUNTRY</label>
                                <div class="select-box">
                                    <select class="form-control" name="office_add_country" id="office_add_country" onChange="officeAddStateInfo(this.value);">
                                        <option value=""> SELECT COUNTRY</option>
                                        <?php
                                        if (isset($arr_country_details) && COUNT($arr_country_details) > 0) {
                                            foreach ($arr_country_details as $country_details) {
                                                ?>
                                                <option value="<?php echo $country_details['country_id'] ?>"> <?php echo $country_details['country_name'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label>STATE</label>
                                <div class="select-box" id="office_add_state_div">
                                    <select class="form-control" name="office_add_state" id="office_add_state">
                                        <option value=""> SELECT STATE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6">
                                <label>CITY</label>
                                <div class="select-box" id="office_add_city_div">
                                    <select class="form-control" name="office_add_city" id="office_add_city">
                                        <option value=""> SELECT CITY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label>ZIPCODE</label>
                                <input type="text" class="form-control" id="office_add_zipcode" name="office_add_zipcode" placeholder="ZIPCODE" onBlur="officeAddressMap(this.value)"> 
                                <div for="office_add_zipcode" style="display: none;" id='error_zipcode_office' generated="true" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12">ADDRESS LINE 1</label>
                            <div class="col-xs-12">
                                <textarea class="form-control" name="office_add_first" id="office_add_first" placeholder="ADDRESS LINE 1"></textarea>
                            </div>
                        </div>
                        <div class="form-group offset-bot-0">
                            <label class="col-xs-12">ADDRESS LINE 2</label>
                            <div class="col-xs-12">
                                <textarea class="form-control" name="office_add_second" id="office_add_second" placeholder="ADDRESS LINE 2"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="checkbox"><label><input type="checkbox" name="office_location_same_as_above" id="office_location_same_as_above" /> MY OFFICE LOCATION IS SAME AS ABOVE ADDRESS</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="location" id="office_address_map_div" style="display: none">
                        <p>LOCATION YOUR CURRENT HOME ADDRESS ON THE MAP</p>
                        <div class="" id="office_add_map" style="height: 283px;width: 422px"></div>
                        <input type="hidden" name="office_add_lat" id="office_add_lat" value="">
                        <input type="hidden" name="office_add_long" id="office_add_long" value="">
                    </div> 
                    <div class="ft-box">
                        <input type="hidden" id="o_last_inser_id" name="o_last_inser_id" value="">
                        <a href="javascript:void(0);" onClick="sixthStepBackBtn()" class="back-btn" id="back_btn_office_add">Back</a>
                        <a href="javascript:void(0);" onClick="sixthStepBackBtnMap()" class="back-btn" id="back_btn_office_add_map" style="display: none;float: left;">Back</a>
                        <a href="javascript:void(0);" id="next_btn_office_add" onClick="sixthStepOfficeAddress('')" class="next-btn">Next</a>
                        <div id="loader_office_add" style="display: none" class="three-quarters-loader">Loading…</div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            var loc = window.location.href,
                    index = loc.indexOf('#');
            if (index > 0) {
                window.location = loc.substring(0, index);
            }
        });
        function forthStepBackBtn() {
            $('#second_step').css('display', 'block');
            $('#btn_submit_cp').css('display', 'block');
            $('#current_address_step').css('display', 'none');
            $('#user_email_back').val($('#user_email').val());
            document.title = "Profile";
            window.location.hash = "Profile";
        }

        function forthStepBackBtnMap() {
            $('#back_btn_curr_add_map').css('display', 'none');
            $('#back_btn_curr_add').css('display', 'block');
            $('#back_btn_curr_add').css('float', 'left');
            $('#current_address_map_div').css('display', 'none');
            $('#current_address_div').css('display', 'block');
        }

        function fifthStepBackBtn() {
            $('#current_address_step').css('display', 'block');
            $('#current_address_div').css('display', 'none');
            $('#current_address_map_div').css('display', 'block');
            $('#permanent_address_step').css('display', 'none');
            $('#next_btn_current_add').css('display', 'block');
            $('#back_btn_curr_add_map').css('display', 'block');
            document.title = "Current Address";
            window.location.hash = "current-address";
        }

        function fifthStepBackBtnMap() {
            $('#permanent_address_map_div').css('display', 'none');
            $('#permanent_address_div').css('display', 'block');
            $('#back_btn_per_add').css('display', 'block');
            $('#back_btn_per_add').css('float', 'left');
            $('#back_btn_per_add_map').css('display', 'none');
        }

        function sixthStepBackBtn() {
            $('#permanent_address_step').css('display', 'block');
            $('#permanent_address_div').css('display', 'none');
            $('#permanent_address_map_div').css('display', 'block');
            $('#office_address_step').css('display', 'none');
            $('#next_btn_per_add').css('display', 'block');
            $('#back_btn_per_add_map').css('display', 'block');
            document.title = "Permanent Address";
            window.location.hash = "permanent-address";
        }
        function sixthStepBackBtnMap() {
            $('#office_address_map_div').css('display', 'none');
            $('#office_address_div').css('display', 'block');
            $('#back_btn_ofc_add').css('display', 'block');
            $('#back_btn_ofc_add_map').css('display', 'none');
        }

        function resendVerificationCode() {
            var mobile_number = $('#mobile_number').val();
            if (mobile_number != '' && mobile_number.length == 10) {
                $.ajax({
                    url: '<?php echo base_url() ?>first-step-registration',
                    method: 'post',
                    data: {
                        mobile_number: mobile_number
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.msg == 'true') {
                            $('#otp_number').val('');
                            $('#otp_number').val(response.otp_code);
                            var str = '';
                            str += '<div class="alert alert-success" style="display: block"> OTP has been sent on your mobile number.';
                            str += '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                            str += '</div>';
                            $('.alert_msg_div').html(str);
                        }
                    }
                });
            }
        }

        function firstStepRegistration() {
            var mobile_number = $('#mobile_number').val();
            var password = $('#password').val();
            var confirm_password = $('#confirm_password').val();
            var otp_number = $('#otp_number').val();
            if ($('#first_step_registration').valid()) {
                if (mobile_number != '' && mobile_number.length == 10 && password != '' && confirm_password != '' && otp_number == '') {
                    $.ajax({
                        url: '<?php echo base_url() ?>first-step-registration',
                        method: 'post',
                        data: {
                            mobile_number: mobile_number
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.msg == 'true') {
                                document.title = "Verify OTP";
                                window.location.hash = "verify-otp";
                                $('#first_step_registarion').css('display', 'none');
                                $('#otp_number').val('');
                                $('#otp_number').val(response.otp_code);
                                $('#otp_number_div').css('display', 'block');
                                var str = '';
                                str += '<div class="alert alert-success" style="display: block"> OTP has been sent on your mobile number.';
                                str += '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                                str += '</div>';
                                $('.alert_msg_div').html(str);
                            }
                        }
                    });
                }
            }
        }

        function secondStepOTP() {
            var mobile_number = $('#mobile_number').val();
            var password = $('#password').val();
            var confirm_password = $('#confirm_password').val();
            var otp_number = $('#otp_number').val();
            if (mobile_number != '' && mobile_number.length == 10 && password != '' && confirm_password != '' && otp_number != '') {
                $.ajax({
                    url: '<?php echo base_url() ?>second-step-registration',
                    method: 'post',
                    data: {
                        mobile_number: mobile_number,
                        password: password,
                        otp_number: otp_number
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response != '') {
                            if (response.msg == 'msg_success') {
                                if (response.last_insert_id != '') {
                                    document.title = "Profile";
                                    window.location.hash = "profile";
                                    jQuery("#loader").hide();
                                    $('#first_step').css('display', 'none');
                                    $('#body_tag').addClass('inner-pages inner-section');
                                    $('#second_step').css('display', 'block');
                                    $('#last_inser_id').val(response.last_insert_id);
                                    $('#phone_number').val(mobile_number);
                                    var str = '';
                                    str += '<div class="alert alert-success" style="display: block"> OTP has been verified successfully.';
                                    str += '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                                    str += '</div>';
                                    $('.alert_msg_div').html(str);
                                } else {
                                    var str = '';
                                    str += '<div class="alert alert-danger" style="display: block"> Something went wrong. Please try again.';
                                    str += '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                                    str += '</div>';
                                    $('.alert_msg_div').html(str);
                                }
                            } else {
                                var str = '';
                                str += '<div class="alert alert-danger" style="display: block">' + response.Response + '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                                str += '</div>';
                                $('.alert_msg_div').html(str);
                                jQuery("#loader").hide();
                                $('#btn_submit').css('display', 'block');
                            }
                        }
                    }
                });
            }
        }

        function thirdStepCompleteProfile() {
            var last_insert_id = $('#last_inser_id').val();
            if ($('#frm_complete_profile').valid()) {
                $('#btn_submit_cp').hide();
                $('#loader_cp').show();
                $.ajax({
                    url: '<?php echo base_url() ?>third-step-registration',
                    method: 'post',
                    data: $('#frm_complete_profile').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response != '') {
                            if (response.msg == 'msg_success') {
                                document.title = "Current Address";
                                window.location.hash = "current-address";
                                $('#loader_cp').hide();
                                $('#second_step').css('display', 'none');
                                $('#c_last_inser_id').val(last_insert_id);
                                $('#current_address_step').css('display', 'block');

                                var str = '';
                                str += '<div class="alert alert-success" style="display: block"> Your basic details has been submitted successfully.';
                                str += '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                                str += '</div>';
                                $('.alert_msg_div').html(str);
                            }
                        }
                    }
                });
            }
        }


        function forthStepCurrentAddress() {
            var last_insert_id = $('#c_last_inser_id').val();
            var form = document.getElementById('frm_current_address');
            var formData = new FormData(form);
            if ($('#frm_current_address').valid()) {
                if ($('#current_address_map_div').css('display') == 'none') {
                    $('#current_address_div').css('display', 'none');
                    $('#current_address_map_div').css('display', 'block');
                    var center = map.getCenter();
                    google.maps.event.trigger(map, 'resize', {});
                    map.setCenter(center);
                    $('.alert_msg_div').hide();
                    $('#back_btn_curr_add').css('display', 'none')
                    $('#back_btn_curr_add_map').css('display', 'block');
                } else {

                    $('#next_btn_current_add').hide();
                    $('#back_btn_curr_add').hide();
                    $('#loader_curr_add').show();
                    $('#back_btn_curr_add_map').css('display', 'none')

                    $.ajax({
                        url: '<?php echo base_url() ?>forth-step-registration',
                        method: 'post',asdasd
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        success: function(response) {
                            if (response != '') {
                                if (response.msg == 'msg_success') {
                                    document.title = "Permanent Address";
                                    window.location.hash = "permanent-address";
                                    $('#loader_curr_add').hide();
                                    $('#current_address_step').css('display', 'none');
                                    $('#p_last_inser_id').val(last_insert_id);
                                    $('#current_address_id').val(response.current_address_id);
                                    $('#current_address_id_bk').val(response.current_address_id);
                                    $('#current_address_image').val(response.address_picture);
                                    if (response.address_picture != '') {
                                        $('#current_add_img_for_same_add').html('<img id="permanent_add_img" src="<?php echo base_url() ?>media/front/img/address-picture/thumbs/' + response.address_picture + '">');
                                    } else {
                                        $('#current_add_img_for_same_add').html('<img id="permanent_add_img" src="<?php echo base_url() ?>media/front/img/add-img.png">');
                                    }
                                    $('#permanent_address_step').css('display', 'block');
                                    var str = '';
                                    str += '<div class="alert alert-success" style="display: block"> You have successfully submited your current address.';
                                    str += '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                                    str += '</div>';
                                    $('.alert_msg_div').html(str);
                                }
                            }
                        }
                    });
                }
            }
        }

        function fifthStepPermanentAddress() {
            var form = document.getElementById('frm_permanent_address');
            var formData = new FormData(form);

            var last_insert_id = $('#p_last_inser_id').val();
            if ($('#permanent_add_same_as_current').prop('checked')) {
                $('#back_btn_per_add').hide();
                $('#next_btn_per_add').hide();
                $('#loader_per_add').show();
                $.ajax({
                    url: '<?php echo base_url() ?>fifth-step-registration',
                    method: 'post',
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    data: formData,
                    success: function(response) {
                        if (response != '') {
                            if (response.msg == 'msg_success') {
                                document.title = "Office Address";
                                window.location.hash = "office-address";
                                $('#loader_per_add').hide();
                                $('#permanent_address_step').css('display', 'none');
                                $('#o_last_inser_id').val(last_insert_id);
                                $('#permanent_address_id_bk').val(response.permanent_address_id);
                                $('#office_address_step').css('display', 'block');
                                var str = '';
                                str += '<div class="alert alert-success" style="display: block"> You have successfully submited your permanent address.';
                                str += '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                                str += '</div>';
                                $('.alert_msg_div').html(str);
                            }
                        }
                    }
                });
            } else {
                if ($('#frm_permanent_address').valid()) {
                    if ($('#permanent_address_map_div').css('display') == 'none') {
                        $('#permanent_address_div').css('display', 'none');
                        $('#permanent_address_map_div').css('display', 'block');
                        var center = map1.getCenter();
                        google.maps.event.trigger(map1, 'resize', {});
                        map1.setCenter(center);
                        $('.alert_msg_div').hide();
                        $('#back_btn_per_add').css('display', 'none');
                        $('#back_btn_per_add_map').css('display', 'block');
                    } else {

                        $('#back_btn_per_add').hide();
                        $('#next_btn_per_add').hide();
                        $('#loader_per_add').show();
                        $('#back_btn_per_add_map').css('display', 'none');

                        $.ajax({
                            url: '<?php echo base_url() ?>fifth-step-registration',
                            method: 'post',
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            data: formData,
                            success: function(response) {
                                if (response != '') {
                                    if (response.msg == 'msg_success') {
                                        document.title = "Office Address";
                                        window.location.hash = "office-address";
                                        $('#loader_per_add').hide();
                                        $('#permanent_address_step').css('display', 'none');
                                        $('#o_last_inser_id').val(last_insert_id);
                                        $('#permanent_address_id_bk').val(response.permanent_address_id);
                                        $('#office_address_step').css('display', 'block');

                                        var str = '';
                                        str += '<div class="alert alert-success" style="display: block"> You have successfully submited your permanent address.';
                                        str += '<button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>';
                                        str += '</div>';
                                        $('.alert_msg_div').html(str);
                                    }
                                }
                            }
                        });
                    }
                }
            }
        }

        function sixthStepOfficeAddress(skip_parameter) {
            var form = document.getElementById('frm_office_address');
            var formData = new FormData(form);
            if (skip_parameter == 'Yes') {
                $('#back_btn_office_add').hide();
                $('#next_btn_office_add').hide();
                $('#loader_office_add').show();
                $('#skip_parameter').val(skip_parameter);
                $.ajax({
                    url: '<?php echo base_url() ?>sixth-step-registration',
                    method: 'post',
                    data: $('#frm_office_address').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response != '') {
                            if (response.msg == 'msg_success') {
                                $('#loader_office_add').hide();
                                location.href = '<?php echo base_url() . 'profile' ?>';
                            }
                        }
                    }
                });
            } else {
                if ($('#frm_office_address').valid()) {
                    if ($('#office_address_map_div').css('display') == 'none') {
                        $('#office_address_div').css('display', 'none');
                        $('#office_address_map_div').css('display', 'block');
                        var center = map2.getCenter();
                        google.maps.event.trigger(map2, 'resize', {});
                        map2.setCenter(center);
                        $('.alert_msg_div').hide();
                        $('#back_btn_office_add').css('display', 'none');
                        $('#back_btn_office_add_map').css('display', 'block');
                    } else {

                        $('#back_btn_office_add').hide();
                        $('#next_btn_office_add').hide();
                        $('#loader_office_add').show();
                        $('#back_btn_office_add_map').css('display', 'none');

                        $.ajax({
                            url: '<?php echo base_url() ?>sixth-step-registration',
                            method: 'post',
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            data: formData,
                            success: function(response) {
                                if (response != '') {
                                    if (response.msg == 'msg_success') {
                                        $('#loader_office_add').hide();
                                        location.href = '<?php echo base_url() . 'profile' ?>';
                                    }
                                }
                            }
                        });
                    }
                }
            }
        }

        // for geting current Address states details
        function currentAddStateInfo(value) {
            var country_id = value;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>front/get-state-info',
                data: {
                    'country_id': country_id
                },
                success: function(msg) {
                    currentAddCityInfo('');
                    if (msg != 'false') {
                        $("#curr_add_state_div").css("display", "block");
                        $("#curr_add_state_div").html(msg);
                    }
                    else {
                        $("#curr_add_state_div").css("display", "block");
                    }
                }
            });
        }

        // for geting Current Address City details
        function currentAddCityInfo(value) {
            $('#current_add_zipcode').val('');
            var state_id = value;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>front/get-city-info',
                data: {
                    'state_id': state_id
                },
                success: function(msg) {
                    if (msg != 'false') {
                        $("#curr_add_city_div").css("display", "block");
                        $("#curr_add_city_div").html(msg);
                    }
                    else {
                        $("#curr_add_city_div").css("display", "block");
                    }
                }
            });
        }

        $('#current_add_city').on('change', function() {
            $('#current_add_zipcode').val('');
        });

        // for geting Permanant Address states details
        function permanantAddStateInfo(value) {
            var country_id = value;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>front/get-state-info',
                data: {
                    'country_id': country_id,
                    'permanant_add': 'Yes'
                },
                success: function(msg) {
                    permanantAddCityInfo('');
                    if (msg != 'false') {
                        $("#per_add_state_div").css("display", "block");
                        $("#per_add_state_div").html(msg);
                    }
                    else {
                        $("#per_add_state_div").css("display", "block");
                    }
                }
            });
        }
        // for geting Permanant Address City details
        function permanantAddCityInfo(value) {
        $('#permanant_add_zipcode').val('');
            var state_id = value;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>front/get-city-info',
                data: {
                    'state_id': state_id,
                    'permanant_add': 'Yes'
                },
                success: function(msg) {
                    if (msg != 'false') {
                        $("#per_add_city_div").css("display", "block");
                        $("#per_add_city_div").html(msg);
                    }
                    else {
                        $("#per_add_city_div").css("display", "block");
                    }
                }
            });
        }
        
        $('#permanant_add_city').on('change', function() {
            $('#permanant_add_zipcode').val('');
        });


        // for geting Office Address states details
        function officeAddStateInfo(value) {
            var country_id = value;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>front/get-state-info',
                data: {
                    'country_id': country_id,
                    'office_add': 'Yes'
                },
                success: function(msg) {
                    officeAddCityInfo('');
                    if (msg != 'false') {
                        $("#office_add_state_div").css("display", "block");
                        $("#office_add_state_div").html(msg);
                    }
                    else {
                        $("#office_add_state_div").css("display", "block");
                    }
                }
            });
        }
        // for geting Permanant Address City details
        function officeAddCityInfo(value) {
        $('#office_add_zipcode').val('');
            var state_id = value;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>front/get-city-info',
                data: {
                    'state_id': state_id,
                    'office_add': 'Yes'
                },
                success: function(msg) {
                    if (msg != 'false') {
                        $("#office_add_city_div").css("display", "block");
                        $("#office_add_city_div").html(msg);
                    }
                    else {
                        $("#office_add_city_div").css("display", "block");
                    }
                }
            });
        }
        
         $('#office_add_city').on('change', function() {
            $('#office_add_zipcode').val('');
        });

        $('#permanent_add_same_as_current').on('click', function() {
            var current_add_country = $('#current_add_country').val();
            var current_add_state = $('#current_add_state').val();
            var current_add_city = $('#current_add_city').val();
            var current_add_zipcode = $('#current_add_zipcode').val();
            var current_add_first = $('#current_add_first').val();
            var current_add_second = $('#current_add_second').val();
            var current_add_lat = $('#current_add_lat').val();
            var current_add_long = $('#current_add_long').val();
            if (this.checked) {
                $('#permanent_location_same_as_above').removeAttr('checked');
                $('#current_add_img_for_same_add').css('display', 'block');
                $('.premanent_add_img').css('display', 'none');
                $('#per_add_city_div').css('display', 'none');
                $('#per_add_city_div_for_same_add').css('display', 'block');
                $('#per_add_state_div').css('display', 'none');
                $('#per_add_state_div_for_same_add').css('display', 'block');
                $('#permanant_add_country option[value="' + current_add_country + '"]').prop('selected', true);
                $('#permanant_add_country').prop('disabled', true);
                $('#permanant_add_state_same_add option[value="' + current_add_state + '"]').prop('selected', true);
                $('#permanant_add_state_same_add').prop('disabled', true);
                $('#permanant_add_city_same_add option[value="' + current_add_city + '"]').prop('selected', true);
                $('#permanant_add_city_same_add').prop('disabled', true);

                $('#permanent_location_same_as_above').prop('disabled', true);

                $('#permanant_add_zipcode').val(current_add_zipcode);
                $('#permanant_add_first').val(current_add_first);
                $('#permanant_add_second').val(current_add_second);
                $('#permanant_add_lat').val(current_add_lat);
                $('#permanant_add_long').val(current_add_long);

                $('#permanant_add_country_id').val(current_add_country);
                $('#permanant_add_state_id').val(current_add_state);
                $('#permanant_add_city_id').val(current_add_city);

                $('#permanant_add_zipcode').attr('readonly', true);
                $('#permanant_add_first').attr('readonly', true);
                $('#permanant_add_second').attr('readonly', true);
            } else {
                $('#current_add_img_for_same_add').css('display', 'none');
                $('.premanent_add_img').css('display', 'block');
                $('#permanant_add_country').val('');
                $('#per_add_city_div').css('display', 'block');
                $('#per_add_city_div_for_same_add').css('display', 'none');
                $('#per_add_state_div').css('display', 'block');
                $('#per_add_state_div_for_same_add').css('display', 'none');
                $('#permanant_add_zipcode').val('');
                $('#permanant_add_first').val('');
                $('#permanant_add_second').val('');
                $('#permanant_add_lat').val('');
                $('#permanant_add_long').val('');

                $('#permanant_add_country_id').val('');
                $('#permanant_add_state_id').val('');
                $('#permanant_add_city_id').val('');

                $('#permanant_add_country').prop('disabled', false);
                $('#permanent_location_same_as_above').prop('disabled', false);
                $('#permanant_add_country').removeAttr('readonly');
                $('#permanant_add_zipcode').removeAttr('readonly');
                $('#permanant_add_first').removeAttr('readonly');
                $('#permanant_add_second').removeAttr('readonly');
            }
        });
    </script>
    <input type="hidden" name="current_location_lat" id="current_location_lat" value="<?php echo (isset($latitude) && $latitude != '') ? $latitude : '18.5333'; ?>" />
    <input type="hidden" name="current_location_long" id="current_location_long" value="<?php echo (isset($longitude) && $longitude != '') ? $longitude : '73.866699'; ?>" />
    <script src="http://maps.googleapis.com/maps/api/js?v=3.21&sensor=false&amp;libraries=places&region=<?php echo $country_code_geo ?>"></script>
    <script src="<?php echo base_url(); ?>media/front/js/jquery.geocomplete.js"></script>
    <script type="text/javascript">
        var map;
        var map1;
        var map2;
        var marker;
        var marker1;
        var marker2;
        var infowindow = new google.maps.InfoWindow();
        var infowindow1 = new google.maps.InfoWindow();
        var infowindow2 = new google.maps.InfoWindow();
        var latitude = $('#current_location_lat').val();
        var longitude = $('#current_location_long').val();
        var myLatLng = new google.maps.LatLng(latitude, longitude)
        function initialize() {
            var mapOpt = {
                center: myLatLng,
                zoom: 15,
                minZoom: 3,
                country: 'IND'
            };
            map = new google.maps.Map(document.getElementById("current_add_map"), mapOpt);
            map1 = new google.maps.Map(document.getElementById("paermanent_add_map"), mapOpt);
            map2 = new google.maps.Map(document.getElementById("office_add_map"), mapOpt);
            var lat = latitude;
            var lng = longitude;
            if (typeof (marker) != "undefined" && marker !== null) {
                marker.setMap(null);
            }
            if (typeof (marker1) != "undefined" && marker1 !== null) {
                marker1.setMap(null);
            }
            if (typeof (marker2) != "undefined" && marker2 !== null) {
                marker2.setMap(null);
            }
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                offset: '0',
                map: map,
                draggable: true,
            });
            marker1 = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                offset: '0',
                map: map1,
                draggable: true,
            });
            marker2 = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                offset: '0',
                map: map2,
                draggable: true,
            });
            map.panTo(new google.maps.LatLng(lat, lng));
            map1.panTo(new google.maps.LatLng(lat, lng));
            map2.panTo(new google.maps.LatLng(lat, lng));

            $("#current_add_lat").val(lat);
            $("#current_add_long").val(lng);
            $("#permanant_add_lat").val(lat);
            $("#permanant_add_long").val(lng);
            $("#office_add_lat").val(lat);
            $("#office_add_long").val(lng);

            if (typeof (marker) != "undefined" && marker !== null) {
                google.maps.event.addListener(marker, 'dragend', function() {
                    //after draging marker geocodePositionCurrentAdd function will call 
                    geocodePositionCurrentAdd(marker.getPosition());
                });
            }

            if (typeof (marker1) != "undefined" && marker1 !== null) {
                google.maps.event.addListener(marker1, 'dragend', function() {
                    //after draging marker geocodePositionCurrentAdd function will call 
                    geocodePositionPermanantAdd(marker1.getPosition());
                });
            }

            if (typeof (marker2) != "undefined" && marker2 !== null) {
                google.maps.event.addListener(marker2, 'dragend', function() {
                    //after draging marker geocodePositionCurrentAdd function will call 
                    geocodePositionOfficeAdd(marker2.getPosition());
                });
            }
        }

        // this will call after dregged marker and info window will apear on that marker and also get draged marker lat long
        function geocodePositionCurrentAdd(pos) {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({latLng: pos}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            });
            $("input[name=current_add_lat]").val(pos.G);
            $("input[name=current_add_long]").val(pos.K);
        }

        // this will call after dregged marker and info window will apear on that marker and also get draged marker lat long
        function geocodePositionPermanantAdd(pos) {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({latLng: pos}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    infowindow1.setContent(results[0].formatted_address);
                    infowindow1.open(map1, marker1);
                }
            });
            $("input[name=permanant_add_lat]").val(pos.G);
            $("input[name=permanant_add_long]").val(pos.K);
        }

        // this will call after dregged marker and info window will apear on that marker and also get draged marker lat long
        function geocodePositionOfficeAdd(pos) {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({latLng: pos}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    infowindow3.setContent(results[0].formatted_address);
                    infowindow3.open(map3, marker3);
                }
            });
            $("input[name=office_add_lat]").val(pos.G);
            $("input[name=office_add_long]").val(pos.K);
        }

        $('#current_location_same_as_above').on('click', function() {
            if (this.checked) {
                initialize();
            } else {
                currentAddressMap($('#current_add_zipcode').val());
            }
        });

        function currentAddressMap(value) {
            var current_add_zipcode = value;
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': current_add_zipcode}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    var lat = location.lat();
                    var lng = location.lng();
                    if (typeof (marker) != "undefined" && marker !== null) {
                        marker.setMap(null);
                    }
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lng),
                        offset: '0',
                        map: map,
                        draggable: true,
                    });
                    map.panTo(new google.maps.LatLng(lat, lng));
                    $("#current_add_lat").val(lat);
                    $("#current_add_long").val(lng);
                    if (typeof (marker) != "undefined" && marker !== null) {
                        google.maps.event.addListener(marker, 'dragend', function() {
                            //after draging marker geocodePositionCurrentAdd function will call 
                            geocodePositionCurrentAdd(marker.getPosition());
                        });
                    }
                    //on entering zipcode info window will apear on marker
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            });
        }

        $('#permanent_location_same_as_above').on('click', function() {
            if (this.checked) {
                initialize();
            } else {
                permanentAddressMAP($('#permanant_add_zipcode').val())
            }
        });

        function permanentAddressMAP(value) {
            var permanent_add_zipcode = value;
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': permanent_add_zipcode}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    var lat = location.lat();
                    var lng = location.lng();
                    if (typeof (marker1) != "undefined" && marker1 !== null) {
                        marker1.setMap(null);
                    }
                    marker1 = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lng),
                        offset: '0',
                        map: map1,
                        draggable: true,
                    });
                    map1.panTo(new google.maps.LatLng(lat, lng));
                    $("#permanant_add_lat").val(lat);
                    $("#permanant_add_long").val(lng);
                    if (typeof (marker1) != "undefined" && marker1 !== null) {
                        google.maps.event.addListener(marker1, 'dragend', function() {
                            //after draging marker geocodePositionCurrentAdd function will call 
                            geocodePositionPermanantAdd(marker1.getPosition());
                        });
                    }
                    //on entering zipcode info window will apear on marker
                    infowindow1.setContent(results[0].formatted_address);
                    infowindow1.open(map1, marker1);
                }
            });
        }

        $('#office_location_same_as_above').on('click', function() {
            if (this.checked) {
                initialize();
            } else {
                officeAddressMap($('#office_add_zipcode').val());
            }
        });
        function officeAddressMap(value) {
            var permanent_add_zipcode = value;
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': permanent_add_zipcode}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    var lat = location.lat();
                    var lng = location.lng();
                    if (typeof (marker2) != "undefined" && marker2 !== null) {
                        marker2.setMap(null);
                    }
                    marker2 = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lng),
                        offset: '0',
                        map: map2,
                        draggable: true,
                    });
                    map2.panTo(new google.maps.LatLng(lat, lng));
                    $("#office_add_lat").val(lat);
                    $("#office_add_long").val(lng);
                    if (typeof (marker2) != "undefined" && marker2 !== null) {
                        google.maps.event.addListener(marker2, 'dragend', function() {
                            //after draging marker geocodePositionCurrentAdd function will call 
                            geocodePositionOfficeAdd(marker2.getPosition());
                        });
                    }
                    //on entering zipcode info window will apear on marker
                    infowindow2.setContent(results[0].formatted_address);
                    infowindow2.open(map2, marker2);
                }
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script>
        $('#curr_add_building_pic').on('change', function() {
            var file, img;
            var extension = this.files[0].name.split('.').pop().toLowerCase();
            if ((file = this.files[0])) {
                var base_url = $("#base_url").val();
                var reader = new FileReader();
                reader.onload = function(e) {
                    switch (extension) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                        case 'JPG':
                        case 'JPEG':
                        case 'PNG':
                        case 'GIF':
                            break;
                        default:
                            $("#curr_add_building_pic").replaceWith($("#curr_add_building_pic").val('').clone(true));
                            $("#imgprvw_upload_license").hide();
                            alert('Please upload a file only of type jpg,png,gif,jpeg.');
                            return true;
                    }
                    $('#current_add_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        $('#per_add_building_pic').on('change', function() {
            var file, img;
            var extension = this.files[0].name.split('.').pop().toLowerCase();
            if ((file = this.files[0])) {
                var base_url = $("#base_url").val();
                var reader = new FileReader();
                reader.onload = function(e) {
                    switch (extension) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                        case 'JPG':
                        case 'JPEG':
                        case 'PNG':
                        case 'GIF':
                            break;
                        default:
                            $("#per_add_building_pic").replaceWith($("#per_add_building_pic").val('').clone(true));
                            $("#imgprvw_upload_license").hide();
                            alert('Please upload a file only of type jpg,png,gif,jpeg.');
                            return true;
                    }
                    $('#permanent_add_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        $('#office_add_building_pic').on('change', function() {
            var file, img;
            var extension = this.files[0].name.split('.').pop().toLowerCase();
            if ((file = this.files[0])) {
                var base_url = $("#base_url").val();
                var reader = new FileReader();
                reader.onload = function(e) {
                    switch (extension) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                        case 'JPG':
                        case 'JPEG':
                        case 'PNG':
                        case 'GIF':
                            break;
                        default:
                            $("#office_add_building_pic").replaceWith($("#office_add_building_pic").val('').clone(true));
                            $("#imgprvw_upload_license").hide();
                            alert('Please upload a file only of type jpg,png,gif,jpeg.');
                            return true;
                    }
                    $('#office_add_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

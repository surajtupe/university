<body class="inner-pages">
    <header>
        <div class="text-center container logo-bg"><a href="<?php echo base_url() ?>"><img src="<?php echo base_url() ?>media/front/img/inner-logo.png"/></a><p>UPDATE YOUR ADDRESS SMARTLY</p></div>
    </header>
    <section class="middle-section">
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
                <div class="head">
                    <h2>FORGOT PASSWORD</h2>
                </div>
                <form class="form-horizontal" name="frm_forgot_password" id="frm_forgot_password" method="post">
                    <div class="form-group">
                        <label class="col-xs-12">Mobile</label>
                        <div class="col-xs-5 col-sm-3 code padding-right-0">
                            <span><img src="<?php echo base_url() ?>media/front/img/flag.png" /></span>
                            <input class="form-control country-code" value="+91" id="country_code" name="country_code"/>
                        </div>
                        <div class="col-xs-7 col-sm-9">
                            <input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="MOBILE" autofocus/>
                        </div>
                    </div>
                    <div id="otp_number_div" class="form-group" style="display: none">
                        <label class="col-xs-12">ENTER OTP</label>
                        <div class="col-xs-12">
                            <input type="text" class="form-control" placeholder="OTP" id="otp_number" name="otp_number"/>
                            <div class="ft-text offset-top-10">
                                <a href="javascript:void(0)" onclick="resendVerificationCode()">Resend OTP</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center clearfix"><button class="btn blue-btn">Reset your password</button></div>
                    <hr class="offset-top-45 offset-bot-10">
                    <div class="text-center ft-text offset-top-20"><a href="<?php echo base_url() ?>sign-in">SIGN IN</a> TO PCO</div>
                </form>

            </div>
        </div>
    </section>
    <script>
        function forgotPassword() {
            var mobile_number = $('#mobile_number').val();
            if ($('#otp_number_div').css('display') == 'none') {
                if (mobile_number != '' && mobile_number.length == 10) {
                    $.ajax({
                        url: '<?php echo base_url() ?>send-forgot-password-link',
                        method: 'post',
                        data: {
                            mobile_number: mobile_number
                        },
                        success: function(response) {
                            if (response == 'otp_generation') {
                                $('#otp_number_div').css('display', 'block');
                            } else {
                                location.href = '<?php echo base_url() ?>sign-in';
                            }
                        }
                    });
                }
            }
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
                    success: function(response) {
                        if (response == 'true') {
                            $('#first_step_registarion').css('display', 'none');
                            $('#otp_number_div').css('display', 'block');
                            alert('OTP resend success.');
                        }
                    }
                });
            }
        }
    </script>
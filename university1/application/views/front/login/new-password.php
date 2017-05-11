<body class="inner-pages">
    <header>
        <div class="text-center container logo-bg"><img src="<?php echo base_url() ?>media/front/img/inner-logo.png"/><p>UPDATE YOUR ADDRESS SMARTLY</p></div>
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
                    <h2>RESET PASSWORD</h2>
                </div>
                <form class="form-horizontal" name="frm_rest_password" id="frm_rest_password" method="post" action="">
                    <div class="form-group">
                        <label class="col-xs-12">ENTER NEW PASSWORD</label>
                        <div class="col-xs-12">
                            <input type="password" class="form-control" placeholder="MIN 8 CHARS,1 UPPERCASE,1 NUM & 1 SPL CHAR" id="new_password" name="new_password" autofocus/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">CONFIRM PASSWORD</label>
                        <div class="col-xs-12">
                            <input type="password" class="form-control" placeholder="MIN 8 CHARS,1 UPPERCASE,1 NUM & 1 SPL CHAR" id="confirm_password" name="confirm_password"/>
                        </div>
                    </div>
                    <div class="text-center clearfix offset-top-25">
                        <div class="text-center clearfix">
                            <input type="hidden" class="form-control" placeholder="OTP" id="security_code" name="security_code" value="<?php echo ($activation_code != '') ? $activation_code : '0'; ?>"/>
                            <button class="btn blue-btn" id="btn_submit" type="submit">Reset Password</button></div>
                        <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                        <hr class="offset-top-45 offset-bot-10">
                        <div class="text-center ft-text offset-top-20"><a href="<?php echo base_url() ?>sign-in">SIGN IN</a> TO PCO</div>
                    </div>
                </form>
            </div>
        </div>
    </section>
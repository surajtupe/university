<body class="inner-pages">
    <header>
        <div class="text-center container logo-bg"><a href="<?php echo base_url() ?>"><img src="<?php echo base_url(); ?>media/front/img/inner-logo.png"/></a><p>UPDATE YOUR ADDRESS SMARTLY</p></div>
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
                    <h2>SIGN IN</h2>
                </div>
                <form class="form-horizontal" id="frm_login" name="frm_login" method="post" action="">
                    <div class="form-group">
                        <label class="col-xs-12">Mobile</label>
                        <div class="col-xs-5 col-sm-3 code padding-right-0">
                            <span><img src="<?php echo base_url(); ?>media/front/img/flag.png" /></span><input class="form-control country-code" value="+91" />
                        </div>
                        <div class="col-xs-7 col-sm-9">
                            <input class="form-control"  name="mobile_number" id="mobile_number" placeholder="ENTER REGISTERED MOBILE NUMBER" <?php if (!empty($remember_me_array)) { ?>
                                   value="<?php echo isset($remember_me_array['cookie_mobile_number'])? $remember_me_array['cookie_mobile_number']: ""; ?>" <?php
                                   } else {
                                       echo '';
                                   }
                                   ?> autofocus/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">PASSWORD</label>
                        <div class="col-xs-12">
                            <input type="password" class="form-control" placeholder="ENTER PASSWORD" name="password" id="password" <?php if (!empty($remember_me_array)) { ?>
                                       value="<?php echo $remember_me_array['cookie_password']; ?>" <?php
                                   } else {
                                       echo '';
                                   }
                                   ?>/>
                        </div>
                    </div>
                    <div class="mid-ft">
                        <div class="checkbox pull-left">
                            <label> 
                                <input type="checkbox" name="remember_me" id="remember_me"  <?php if (!empty($remember_me_array)) { ?>checked="checked" <?php } ?> />REMEMBER ME
                            </label>
                        </div>
                        <a href="<?php echo base_url() ?>forgot-password" class="pull-right">FORGOT PASSWORD?</a>
                    </div>
                    <div class="text-center clearfix">
                        <button type="submit" class="btn blue-btn" name="btn_submit" id="btn_submit">SIGN IN</button>
                        <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                    </div>
                    <hr class="offset-top-45 offset-bot-10">
                    <div class="text-center ft-text">DONT HAVE AN ACCOUNT? <a href="<?php echo base_url() ?>sign-up">SIGN UP</a></div>
                </form>
            </div>
        </div>
    </section>
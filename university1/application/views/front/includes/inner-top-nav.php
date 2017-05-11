<body class="inner-pages inner-section">
    <header>
        <div class="text-center logo-bg">
            <a href="<?php echo base_url()?>"><img src="<?php echo base_url() ?>media/front/img/inner-logo.png"/></a>
            <p>UPDATE YOUR ADDRESS SMARTLY 
                <?php if (isset($user_account) && $user_account['user_id'] != '') { ?>
                    <a class="pull-right signout" href="<?php echo base_url() ?>api-demo-logout">Sign out</a>
                <?php } ?>
            </p>
        </div>
    </header>
    <div class="modal fade" id="my_password_modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal_close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">VERIFY PASSWORD</h4>
                </div>
                <div class="modal-body">
                    <form id="frm_valid_password" name="frm_valid_password" method="post" action="" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-xs-12">PASSWORD</label>
                            <div class="col-xs-12">
                                <input type="password" placeholder="PASSWORD" class="form-control" name="password" id="password" value="">
                                <div id="failed_pass_error" class="text-danger" style="display: none">Please enter your valid password.</div>
                            </div>
                        </div>
                        <div class="text-center offset-top-25 offset-bot-15">
                            <input type="hidden" id="form_type" name="form_type" value="">
                            <a class="btn blue-btn" name="btn_submit" id="btn_submit" onclick="chkValidPassword()">VERIFY</a>
                            <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

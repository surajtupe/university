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
                    <h2>SIGN Up</h2>
                </div>
                <form class="form-horizontal" id="frm_registration" name="frm_registration" method="post" action="">
                    <div class="form-group">
                        <label class="col-xs-12">Mobile Number</label>
                        <div class="col-xs-5 col-sm-3 code padding-right-0">
                            <span><img src="<?php echo base_url(); ?>media/front/img/flag.png" /></span><input class="form-control country-code" readonly="" value="+91" />
                        </div>
                        <div class="col-xs-7 col-sm-9">
                            <input class="form-control"  name="mobile_number" id="mobile_number" placeholder="ENTER MOBILE NUMBER"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12">First Name</label>
                        <div class="col-xs-12">
                            <input type="text" class="form-control" placeholder="ENTER FIRST NAME" name="first_name" id="first_name"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">Last Name</label>
                        <div class="col-xs-12">
                            <input type="text" class="form-control" placeholder="ENTER LAST NAME" name="last_name" id="last_name"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12">Password</label>
                        <div class="col-xs-12">
                            <input type="password" class="form-control" placeholder="ENTER PASSWORD" name="password" id="password"/>
                        </div>
                    </div>
                    <div class="text-center clearfix">
                        <button type="submit" class="btn blue-btn" name="btn_submit" id="btn_submit">SUBMIT</button>
                        <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            /**Login Static form validation*/
            jQuery("#frm_registration").validate({
                errorClass: 'text-danger',
                errorElement: 'div',
                rules: {
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    mobile_number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    }
                },
                messages: {
                    mobile_number: {
                        required: "Please enter your mobile number.",
                        minlength: jQuery.format("Please enter {0} digits number."),
                        maxlength: jQuery.format("Please enter only {0} digits number."),
                    },
                    first_name: {
                        required: "Please enter first name."
                    },
                    last_name: {
                        required: "Please enter last name."
                    },
                    password: {
                        required: "Please enter password."
                    }
                },
                submitHandler: function(form) {
                    jQuery("#btn_submit").hide();
                    jQuery("#loader").show();
                    form.submit();
                }

            });
        })
    </script>
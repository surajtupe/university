<section class="middle-section">
    <div class="container">
        <div class="mid-section">
            <?php
            if ($this->session->userdata('contact_success') != "") {
                ?>
                <div class="alert alert-success">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">&times;</a>
                    <?php
                    echo $this->session->userdata('contact_success');
                    $this->session->unset_userdata('contact_success');
                    ?>
                </div>
                <?php
            }
            ?>
            <?php
            if ($this->session->userdata('contact_fail') != "") {
                ?>
                <div class="alert alert-warning">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">&times;</a>
                    <?php
                    echo $this->session->userdata('contact_fail');
                    $this->session->unset_userdata('contact_fail');
                    ?>
                </div>
                <?php
            }
            ?>
            <div class="head">
                <h2>Contact</h2>
            </div>
            <div class="address-detail contact-detail">
                <div class="form-content">
                    <div class="form-group">
                        <label> <span><i class="fa fa-envelope"></i></span> <?php echo $contact_email ?></label>
                    </div>
                    <div class="form-group">
                        <label> <span><i class="fa fa-phone"></i></span> <?php echo $phone_no ?></label>
                    </div>
                    <div class="form-group">
                        <label> <span><img src="<?php echo base_url() ?>media/front/img/home.png"></span> <?php
                            $address_trim = str_replace('\n', '', trim($address));
                            print_r($address_trim);
                            ?>
                                <?php echo $city . ', ' . $street . ' - ' . $zip_code; ?></label>
                    </div>
                </div>
            </div>

            <form class="form-horizontal" name="form_contact_us" id="form_contact_us" action="<?php echo base_url(); ?>contact-us" method="post">  
                <div class="form-group">
                    <label class="col-xs-12">NAME</label>
                    <div class="col-xs-12">
                        <input placeholder="NAME" class="form-control" value="<?php echo(isset($name)) ? $name : '' ?>" name="first_name" id="first_name">
                    </div>
                </div>  
                <div class="form-group">
                    <label class="col-xs-12">E-MAIL</label>
                    <div class="col-xs-12">
                        <input placeholder="E-MAIL" class="form-control" name="email" id="email" value="<?php echo(isset($email_address)) ? $email_address : '' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">SUBJECT</label>
                    <div class="col-xs-12">
                        <input placeholder="SUBJECT" class="form-control" name="subject" id="subject">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">MESSAGE</label>
                    <div class="col-xs-12">
                        <textarea class="form-control" name="message" id="message"></textarea>
                    </div>
                </div>
                <div class="text-center offset-top-25 offset-bot-15">
                    <button type="submit" class="btn blue-btn">Send Message</button>
                    <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url(); ?>">
                </div>
            </form>  
        </div>
    </div>
</section>
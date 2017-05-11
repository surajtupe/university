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
                <?php if ($address_details[0]['address_type_id_fk'] == '2') { ?> 
                    <h2>CURRENT HOME ADDRESS</h2>
                <?php } else if ($address_details[0]['address_type_id_fk'] == '3') { ?>
                    <h2>PERMANENT HOME ADDRESS</h2>
                <?php } else { ?>
                    <h2>OFFICE ADDRESS</h2>
                <?php } ?>
            </div>
            <div class="">
                <a href="<?php echo base_url() ?>profile" id="back_btn_curr_add" class="back-btn">BACK</a>
                <a href="javascript:void(0);" id="next_btn_current_add" onclick="chkPassword('edit_address')" class="next-btn">EDIT</a>
            </div>
            <div class="buliding-photo">
                <div class="build-img">
                    <img src="<?php echo base_url() ?>media/front/img/address-picture/thumbs/<?php echo $address_details[0]['address_picture'] ?>"  onerror="src='<?php echo base_url() ?>media/front/img/add-img.png'"/>
                </div>
                <p>ADDRESS NAME: <span><?php echo $address_details[0]['address_name'] ?></span> </p>
            </div>

            <div class="address-detail">
                <div class="form-content">
                    <div class="form-group">
                        <label><span>Address  </span>: <span class="detail"><?php echo $address_details[0]['address_line1'] . ', ' . $address_details[0]['city_name'] . ', ' . $address_details[0]['state_name'] . ', ' . $address_details[0]['country_name'] . '-' . $address_details[0]['zip_code'] ?></span></label>
                    </div>
                </div>
            </div>
            <div class="user-information">
                <div class="information-content">
                    <h4><span><img src="<?php echo base_url() ?>media/front/img/home.png" /></span> ACCESS LIST</h4>
                    <nav>
                        <ul class="clean">
                            <li><a href="javascript:void(0)">MANAGE PERMANENT ADDRESS LIST <span class="pull-right"><i class="fa fa-angle-right"></i></span></a></li>
                            <li><a href="javascript:void(0)">MANAGE TEMPORARY ADDRESS LIST <span class="pull-right"><i class="fa fa-angle-right"></i></span></a></li>
                        </ul>
                    </nav>
                </div>
                <div class="information-content offset-top-15">
                    <h4><span><img src="<?php echo base_url() ?>media/front/img/office.png" /></span> FORWARDING ADDRESS</h4>
                    <nav>
                        <ul class="clean">
                            <?php if(COUNT($is_forwarding_address) > 0) { ?>
                                <li><a href = "<?php echo base_url() ?>view-forwarding-address/<?php echo base64_encode($is_forwarding_address[0]['forwarded_address_id']) ?>">MANAGE FORWARDING ADDRESS <span class = "pull-right"><i class = "fa fa-angle-right"></i></span></a></li>
                            <?php } else {
                                ?>
                                <li><a href="<?php echo base_url() ?>forwarding-address/add/<?php echo base64_encode($address_id) ?>">MANAGE FORWARDING ADDRESS <span class="pull-right"><i class="fa fa-angle-right"></i></span></a></li>
                            <?php } ?> 
                        </ul>
                    </nav>
                </div>
                <div class="information-content offset-top-15">
                    <h4><span><img src="<?php echo base_url() ?>media/front/img/office.png" /></span> SERVICES</h4>
                    <nav>
                        <ul class="clean">
                            <li><a href="javascript:void(0)">MANAGE SERVICE <span class="pull-right"><i class="fa fa-angle-right"></i></span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<input type="hidden" id="address_id" name="address_id" value="<?php echo isset($address_id) ? base64_encode($address_id) : ''; ?>">
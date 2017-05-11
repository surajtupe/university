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
                <?php if ($forwarind_address_details[0]['address_type_id_fk'] == '2') { ?> 
                    <h2>FORWARDING CURRENT ADDRESS</h2>
                <?php } else if ($forwarind_address_details[0]['address_type_id_fk'] == '3') { ?>
                    <h2>FORWARDING PERMANENT ADDRESS</h2>
                <?php } else { ?>
                    <h2>FORWARDING OFFICE ADDRESS</h2>
                <?php } ?>
            </div>
            <div class="">
                <a href="<?php echo base_url() ?>view-address/<?php echo base64_encode($forwarind_address_details[0]['user_address_id_fk']) ?>" id="back_btn_curr_add" class="back-btn">BACK</a>
                <a href="javascript:void(0);" id="next_btn_current_add" class="next-btn" onclick="chkPassword('edit_forwarding_address')">EDIT</a>
            </div>
            <div class="buliding-photo">
                <div class="build-img">
                    <img src="<?php echo base_url() ?>media/front/img/address-picture/thumbs/<?php echo $forwarind_address_details[0]['address_picture'] ?>"  onerror="src='<?php echo base_url() ?>media/front/img/add-img.png'"/>
                </div>
                <p>ADDRESS NAME: <span><?php echo $forwarind_address_details[0]['address_name'] ?></span> </p>
            </div>

            <div class="address-detail">
                <div class="form-content">
                    <div class="form-group">
                        <label><span>Address  </span>: <span class="detail"><?php echo $forwarind_address_details[0]['address_line1'] . ', ' . $forwarind_address_details[0]['city_name'] . ', ' . $forwarind_address_details[0]['state_name'] . ', ' . $forwarind_address_details[0]['country_name'] . '-' . $forwarind_address_details[0]['zip_code'] ?></span></label>
                    </div>
                    <div class="form-group">
                        <label><span>DATE FROM </span>: <span class="detail"><?php echo date('d/m/Y', strtotime($forwarind_address_details[0]['date_from'])); ?></span></label>
                        <label><span>DATE TO </span>: <span class="detail"><?php echo date('d/m/Y', strtotime($forwarind_address_details[0]['date_to'])); ?></span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<input type="hidden" id="address_id" name="address_id" value="<?php echo isset($forwarding_address_id) ? base64_encode($forwarding_address_id) : ''; ?>">
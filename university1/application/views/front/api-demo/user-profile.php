<section class="middle-section">
    <div class="container">
        <div class="menu clearfix">
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
            <nav>
                <ul class="clean">
                    <li class="active"><a href="javascript:void(0)">PROFILE</a></li>
                </ul>
            </nav>
        </div>
        <div class="address-container">
            <div class="mid-section">
                <div class="media">
                    <div class="media-body">
                        <form id="frm_address_details" name="frm_address_details" method="post" class="form-horizontal">
                            <div class="form-content">
                                <div class="form-group">
                                    <label><span>Name :</span><?php echo $arr_user_data['first_name'] . ' ' . $arr_user_data['last_name'] ?></label>
                                </div>
                                <div class="form-group">
                                    <label><span>CELL : </span> <?php echo $arr_user_data['phone_number']; ?></label>
                                </div>
                            </div>
                            <div class="form-group offset-top-25 offset-bot-15">
                                <label class="col-xs-12">Address Name</label>
                                <div class="col-xs-12">
                                    <input placeholder="ADDRESS NAME" class="form-control" id="address_name" name="address_name" value="">
                                </div>
                            </div>
                            <div class="text-center offset-top-25 offset-bot-15">
                                <input type="hidden" class="form-control" id="mobile_number" name="mobile_number" value="<?php echo $arr_user_data['phone_number']; ?>">
                                <a href="javascript:void(0)" onclick="getAddressDetails()" class="btn blue-btn" name="btn_submit" id="btn_submit">Submit</a>
                                <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="address-information"></div>
            <div class="forwaring-address-information"></div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        /**Login Static form validation*/
        jQuery("#frm_address_details").validate({
            errorClass: 'text-danger',
            errorElement: 'div',
            rules: {
                address_name: {
                    required: true
                }
            },
            messages: {
                address_name: {
                    required: "Please enter address name."
                }
            },
            submitHandler: function(form) {
                jQuery("#btn_submit").hide();
                jQuery("#loader").show();
                form.submit();
            }

        });
    })

    function getAddressDetails() {
        if (jQuery("#frm_address_details").valid()) {
            $.ajax({
                url: '<?php echo base_url() ?>api-demo-address-details',
                method: 'post',
                data: $('#frm_address_details').serialize(),
                dataType: 'json',
                success: function(response) {
                    $('.address-information').html('');
                    $('.forwaring-address-information').html('');
                    var str = '';
                    var str2 = '';
                    if (response != 'false' && response != null) {
                        var current_address = response.current_address;
                        var forwarding_address = response.forwarding_address;
                        if (current_address != '') {
                            str += '<div class="address-box">';
                            str += '<h4>Address Information</h4>';
                            str += '<div class="form-group">';
                            str += '<label> Address Line 1 : <span>' + current_address.address_line1 + '</span></label>';
                            str += '</div>';
                            if (current_address.address_line2 != '') {
                                str += '<div class="form-group">';
                                str += '<label> Address Line 2 : <span>' + current_address.address_line1 + '</span></label>';
                                str += '</div>';
                            }
                            str += '<div class="form-group">';
                            str += '<label> Country : <span>' + current_address.country_name + '</span></label>';
                            str += '</div>';
                            str += '<div class="form-group">';
                            str += '<label> State : <span>' + current_address.state_name + '</span></label>';
                            str += '</div>';
                            str += '<div class="form-group">';
                            str += '<label> City : <span>' + current_address.city_name + '</span></label>';
                            str += '</div>';
                            str += '<div class="form-group">';
                            str += '<label> Zipcode : <span>' + current_address.zip_code + '</span></label>';
                            str += '</div>';
                            str += '</div>';
                            $('.address-information').append(str);
                        }

                        if (forwarding_address != '') {
                            str2 += '<div class="address-box">';
                            str2 += '<h4>Forwarding Address Information</h4>';
                            str2 += '<div class="form-group">';
                            str2 += '<label> Address Line 1 : <span>' + forwarding_address.address_line1 + '</span></label>';
                            str2 += '</div>';
                            if (forwarding_address.address_line2 != '') {
                                str2 += '<div class="form-group">';
                                str2 += '<label> Address Line 2 : <span>' + forwarding_address.address_line1 + '</span></label>';
                                str2 += '</div>';
                            }
                            str2 += '<div class="form-group">';
                            str2 += '<label> Country : <span>' + forwarding_address.country_name + '</span></label>';
                            str2 += '</div>';
                            str2 += '<div class="form-group">';
                            str2 += '<label> State : <span>' + forwarding_address.state_name + '</span></label>';
                            str2 += '</div>';
                            str2 += '<div class="form-group">';
                            str2 += '<label> City : <span>' + forwarding_address.city_name + '</span></label>';
                            str2 += '</div>';
                            str2 += '<div class="form-group">';
                            str2 += '<label> Zipcode : <span>' + forwarding_address.zip_code + '</span></label>';
                            str2 += '</div>';
                            str2 += '</div>';
                            $('.forwaring-address-information').append(str2);
                        }
                    } else {
                        alert('Sorry.! You don\'t have an access of this address.');
                    }
                }
            })
        }
    }


</script>


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
                    <li><a href="javascript:void(0)">CONTACTS </a></li>
                    <li><a href="javascript:void(0)">  ALERTS   </a></li>
                    <li><a href="javascript:void(0)">   SERVICES   </a></li>
                    <li><a href="javascript:void(0)">SETTINGS</a></li>
                </ul>
            </nav>
            <a href="javascript:void(0)" class="pull-right edit-btn" onclick="chkPassword('edit_profile')">Edit</a>
        </div>
        <div class="mid-section">
            <div class="media">

                <?php
                if ($arr_user_data['profile_picture'] != '') {
                    $profie_pic = base_url() . 'media/front/img/user-profile-pictures/thumb/' . $arr_user_data['profile_picture'];
                } else {
                    $profie_pic = base_url() . 'media/front/img/avtar.jpg';
                }
                ?>

                <form name="imageform" id="imageform" method="POST" enctype="multipart/form-data" action=''> 
                    <a href="javascript:void(0);" class="pull-left relative" id="">
                        <img src="<?php echo $profie_pic; ?>" id="profile_picture_new"/>
                        <input type="file" size="9" class="infi" title="Upload Profile Picture" data-rel="tooltip" name="profile_image" id="profile_image" value="">
                        <input type="hidden" name="old_logo" id="old_logo" value="239316926">
                        <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url(); ?>">
                        <span class="edit-pencil"><i class="fa fa-pencil-square-o"></i></span>
                    </a>
                </form>

                <div class="media-body">
                    <div class="form-content">
                        <div class="form-group">
                            <label><span>Name :</span><?php echo $arr_user_data['first_name'] . ' ' . $arr_user_data['last_name'] ?></label>
                        </div>
                        <div class="form-group">
                            <label><span>D.O.B : </span><?php echo $arr_user_data['user_birth_date']; ?></label>
                        </div>
                        <div class="form-group">
                            <label><span>CELL : </span> <?php echo $arr_user_data['phone_number']; ?></label>
                        </div>
                        <div class="form-group">
                            <label><span>Email : </span>
                                <font><?php echo $arr_user_data['user_email']; ?> 
                                <span><?php echo ($arr_user_data['email_verified'] != '0') ? '[VERIFIED E-MAIL]' : '[VERIFY E-MAIL]'; ?></span>
                                </font>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-information">
                <?php
                $office_address_id = '0';
                if (COUNT($arr_get_address_details) > 0) {
                    foreach ($arr_get_address_details as $address_details) {
                        if ($address_details['address_type_id_fk'] == '2') {
                            $current_address_id = $address_details['address_id'];
                        } else if ($address_details['address_type_id_fk'] == '3') {
                            $permanent_address_id = $address_details['address_id'];
                        } else {
                            $office_address_id = $address_details['address_id'];
                        }
                    }
                }
                ?>
                <div class="information-content">
                    <h4><span><img src="<?php echo base_url() ?>media/front/img/home.png" /></span> Home</h4>
                    <nav>
                        <ul class="clean">
                            <li><a href="<?php echo base_url(); ?>view-address/<?php echo isset($current_address_id) ? base64_encode($current_address_id) : '0'; ?>">CURRENT HOME ADDRESS <span class="pull-right"><i class="fa fa-angle-right"></i></span></a></li>
                            <li><a href="<?php echo base_url(); ?>view-address/<?php echo isset($permanent_address_id) ? base64_encode($permanent_address_id) : '0'; ?>">PERMANENT HOME ADDRESS <span class="pull-right"><i class="fa fa-angle-right"></i></span></a></li>

                        </ul>
                    </nav>
                </div>
                <div class="information-content offset-top-15">
                    <h4><span><img src="<?php echo base_url() ?>media/front/img/office.png" /></span> OFFICE</h4>
                    <nav>
                        <ul class="clean">
                            <?php if ($office_address_id != '0') { ?>
                                <li><a href="<?php echo base_url(); ?>view-address/<?php echo isset($office_address_id) ? base64_encode($office_address_id) : '0'; ?>">OFFICE ADDRESS <span class="pull-right"><i class="fa fa-angle-right"></i></span></a></li>
                            <?php } else { ?> 
                                <li><a href="<?php echo base_url(); ?>office-address">OFFICE ADDRESS <span class="pull-right"><i class="fa fa-angle-right"></i></span></a></li>
                            <?php } ?> 
                        </ul>
                    </nav>
                </div>
                <div class="text-center offset-top-25 offset-bot-15"><button class="btn blue-btn">I AM HERE</button></div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url(); ?>media/front/js/ajaxupload.3.5.js"></script>
<script type="text/javascript">
                $('#profile_image').on('change', function() {
                    var form = document.getElementById('imageform');
                    var formData = new FormData(form);
                    $.ajax({
                        url: '<?php echo base_url() ?>profile/change-profile-picture',
                        method: 'post',
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: formData,
                        success: function(response) {
                            if (response.msg == 'Success') {
                                $('#profile_picture_new').attr('src', '<?php echo base_url() ?>media/front/img/user-profile-pictures/thumb/' + response.image);
                            }
                        }
                    });
                })
</script>    
<style>
    .mid-section {
        width: 435px !important;
    }
</style>
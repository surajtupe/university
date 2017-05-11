<link rel="stylesheet" href="<?php echo base_url(); ?>media/front/css/jquery.validate.password.css" />

<div class="container">
    <div class="bs-docs-section">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-12">
                    <h1 id="buttons">User Registration</h1>
                </div>
            </div>
        </div>
    </div>        
    <section>
        <div class="row">
            <div class="col-lg-12">
                <div class="well">
                    <form id="frm_user_registration" name="frm_user_registration" method="post" action="<?php echo base_url(); ?>signup" class="bs-example form-horizontal" enctype="multipart/form-data">
                        <fieldset>
                            <div id="OTP_number_div">
                                <div class="form-group">
                                    <label for="otp_number" class="col-lg-2 control-label">OTP Number:<sup class="mandatory">*</sup></label>
                                    <?php
                                    $otp_number = $this->session->userdata('otp_number');
                                    echo isset($otp_number) ? $otp_number : '';
                                    ?>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="otp_number" name="otp_number" value="">                                            
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="terms" class="col-lg-2 control-label">&nbsp;</label>
                                    <div class="col-lg-10">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="" name="terms" id="terms" >
                                                I agree to the </label><a class="btn-link ajax" href="<?php echo base_url(); ?>cms/terms-services" target="_blank">Terms and conditions.</a>
                                            <br><label class="text-danger" generated="true" for="terms"></label>

                                        </div>
                                    </div>
                                </div>                  
                                <div class="col-lg-10 col-lg-offset-2">
                                    <button type="submit" name="btn_otp_number" id="btn_otp_number" class="btn btn-success">Submit</button> 
                                    <img id="btn_otp_loader" style="display: none;" src="<?php echo base_url(); ?>media/front/img/loader.gif" border="0">
                                </div>
                            </div>
                            <div id="complete_profile_div" style="display: none">
                                <div class="form-group">
                                    <label for="first_name" class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-10">
                                        <select name="title" id="title">
                                            <option value="mr">Mr.</option>
                                            <option value="ms">Ms.</option>
                                            <option value="mrs">Mrs.</option>
                                        </select>                                            
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="first_name" class="col-lg-2 control-label">First name:<sup class="mandatory">*</sup></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="first_name" name="first_name">                                            
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="first_name" class="col-lg-2 control-label">Middle name:<sup class="mandatory">*</sup></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" autofocus="autofocus" >                                            
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name" class="col-lg-2 control-label">Last name:<sup class="mandatory">*</sup></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" name="last_name" id="last_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user_email" class="col-lg-2 control-label">Email:<sup class="mandatory">*</sup></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" name="user_email" id="user_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user_name" class="col-lg-2 control-label">Username:<sup class="mandatory">*</sup></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" name="user_name" id="user_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button type="submit" name="btn_complete_profile" id="btn_complete_profile" class="btn btn-success  pull-right">Next</button> 
                                        <img id="btn_loader" style="display: none;" src="<?php echo base_url(); ?>media/front/img/loader.gif" border="0">
                                    </div>
                                </div>
                            </div>
                            <div id="home_address_div" style="display: none">
                                <div class="text-center"><h4>Enter Home Address Details</h4></div>
                                <div class="col-md-12">
                                    <div class="help-block"><h4>Current Address</h4></div>
                                    <div class=" col-lg-6">
                                        <div class="form-group">
                                            <label for="address_name" class="col-lg-3 control-label">Address name:<sup class="mandatory">*</sup></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="<?php echo $curr_address_name ?>" class="form-control" id="current_add_address_name" name="current_add_address_name" placeholder="Address Name" readonly>
                                            </div>
                                        </div>
                                        <label for="first_name" class="col-lg-3 control-label">Address:<sup class="mandatory">*</sup></label>
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <select class="form-control" name="current_add_country" id="current_add_country" onChange="currentAddStateInfo(this.value);">
                                                    <option value=""> Select Your Country</option>
                                                    <?php
                                                    if (isset($arr_country_details) && COUNT($arr_country_details) > 0) {
                                                        foreach ($arr_country_details as $country_details) {
                                                            ?>
                                                            <option value="<?php echo $country_details['country_id'] ?>"> <?php echo $country_details['country_name'] ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div id="curr_add_state_div">
                                                    <select class="form-control" name="current_add_state" id="current_add_state">
                                                        <option value=""> Select Your State</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div id="curr_add_city_div">
                                                    <select class="form-control" name="current_add_city" id="current_add_city">
                                                        <option value=""> Select Your City</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="current_add_zipcode" name="current_add_zipcode" placeholder="Zipcode"> 
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" name="current_add_first" id="current_add_first" placeholder="Address 1"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" name="current_add_second" id="current_add_second" placeholder="Address 2"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="current_add_pic" class="col-lg-3 control-label">Upload Building Picture:</label>
                                            <div class="col-lg-8">
                                                <input type="file" value="" id="curr_add_building_pic" name="curr_add_building_pic">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="" id="current_add_map" style="height: 400px;width: 100%"></div>
                                        <input type="text" name="current_add_lat" id="current_add_lat" value="">
                                        <input type="text" name="current_add_long" id="current_add_long" value="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="help-block"><h4>Permanent Address</h4></div>
                                    <div class=" col-lg-6">
                                        <div class="form-group">
                                            <label for="first_name" class="col-lg-3 control-label">Address name:<sup class="mandatory">*</sup></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="permanant_address_name" name="permanant_address_name" placeholder="Address Name" value="<?php echo $perm_address_name ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="first_name" class="col-lg-3 control-label"></label>
                                            <div class="col-lg-8">
                                                <input type="checkbox" id="current_add_same_as_permanent" name="current_add_same_as_permanent">
                                                Permanent Address is same as Current address
                                            </div>
                                        </div>
                                        <div id="same_as_above_address_div">
                                            <label for="first_name" class="col-lg-3 control-label">Address:<sup class="mandatory">*</sup></label>
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    <select class="form-control" name="permanant_add_country" id="permanant_add_country" onChange="permanantAddStateInfo(this.value);">
                                                        <option value=""> Select Your Country</option>
                                                        <?php
                                                        if (isset($arr_country_details) && COUNT($arr_country_details) > 0) {
                                                            foreach ($arr_country_details as $country_details) {
                                                                ?>
                                                                <option value="<?php echo $country_details['country_id'] ?>"> <?php echo $country_details['country_name'] ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <div id="per_add_state_div"> 
                                                        <select class="form-control" name="permanant_add_state" id="permanant_add_state">
                                                            <option value=""> Select Your State</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div id="per_add_city_div"> 
                                                        <select class="form-control" name="permanant_add_city" id="permanant_add_city">
                                                            <option value=""> Select Your City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="permanant_add_zipcode" name="permanant_add_zipcode" placeholder="Zipcode"> 
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="permanant_add_first" id="permanant_add_first" placeholder="Address 1"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="permanant_add_second" id="permanant_add_second" placeholder="Address 2"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="permanant_add_pic" class="col-lg-3 control-label">Upload Building Picture:</label>
                                                <div class="col-lg-8">
                                                    <input type="file" value="" id="permanant_add_building_pic" name="permanant_add_building_pic">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="" id="permanant_add_map" style="height: 400px;width: 100%"></div>
                                        <input type="text" name="permanant_add_lat" id="permanant_add_lat" value="">
                                        <input type="text" name="permanant_add_long" id="permanant_add_long" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button type="submit" name="btn_home_address" id="btn_home_address" class="btn btn-success  pull-right">Next</button> 
                                        <img id="btn_loader" style="display: none;" src="<?php echo base_url(); ?>media/front/img/loader.gif" border="0">
                                    </div>
                                </div>
                            </div>
                            <div id="office_address_div" style="display: none">
                                <div class="text-center"><h4>Enter Office Address Details</h4></div>
                                <div class="col-md-12">
                                    <div class=" col-lg-6">
                                        <div class="form-group">
                                            <label for="first_name" class="col-lg-3 control-label">Address name:<sup class="mandatory">*</sup></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="office_address_name" name="office_address_name" placeholder="Address Name" value="<?php echo $office_address_name ?>" readonly>
                                            </div>
                                        </div>

                                        <label for="first_name" class="col-lg-3 control-label">Address:<sup class="mandatory">*</sup></label>
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <select class="form-control" name="office_add_country" id="office_add_country" onChange="officeAddStateInfo(this.value);">
                                                    <option value=""> Select Your Country</option>
                                                    <?php
                                                    if (isset($arr_country_details) && COUNT($arr_country_details) > 0) {
                                                        foreach ($arr_country_details as $country_details) {
                                                            ?>
                                                            <option value="<?php echo $country_details['country_id'] ?>"> <?php echo $country_details['country_name'] ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div id="office_add_state_div"> 
                                                    <select class="form-control" name="office_add_state" id="office_add_state">
                                                        <option value=""> Select Your State</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div id="office_add_city_div"> 
                                                    <select class="form-control" name="office_add_city" id="office_add_city">
                                                        <option value=""> Select Your City</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="office_add_zipcode" name="office_add_zipcode" placeholder="Zipcode"> 
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" name="office_add_first" id="office_add_first" placeholder="Address 1"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" name="office_add_second" id="office_add_second" placeholder="Address 2"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="office_add_pic" class="col-lg-3 control-label">Upload Building Picture:</label>
                                            <div class="col-lg-8">
                                                <input type="file" value="" id="office_add_building_pic" name="office_add_building_pic">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="" id="office_add_map" style="height: 400px;width: 100%"></div>
                                        <input type="text" name="office_add_lat" id="office_add_lat" value="">
                                        <input type="text" name="office_add_long" id="office_add_long" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button type="submit" name="btn_office_address" id="btn_office_address" class="btn btn-success  pull-right">Next</button> 
                                        <a  name="btn_office_address_skip" id="btn_office_address_skip" class="pull-right">Skip</a> 
                                        <img id="btn_loader" style="display: none;" src="<?php echo base_url(); ?>media/front/img/loader.gif" border="0">
                                    </div>
                                </div>
                            </div>
                            <div id="services_div" style="display: none">
                                <div class="form-group">
                                    <label for="services" class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-10">
                                        Services
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button type="submit" name="btn_services" id="btn_services" class="btn btn-success  pull-right">Submit</button> 
                                        <a  name="btn_service_skip" id="btn_service_skip" class="pull-right">Skip</a> 
                                        <img id="btn_services_loader" style="display: none;" src="<?php echo base_url(); ?>media/front/img/loader.gif" border="0">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
    </section>
    <br>
</div>
<script>
    // Hides OTP div after checking otp number is correct or not
    $('#btn_otp_number').click(function(event) {
        var otp_number = $('#otp_number').val();
        var terms_chekbox = $('#terms').is(':checked');
        if (!isNaN(otp_number) && (otp_number.length == 6) && otp_number != '' && terms_chekbox == true) {
            jQuery("#btn_otp_number").hide();
            jQuery("#btn_otp_loader").show();
            $.ajax({
                url: '<?php echo base_url() ?>otp-number-second-step',
                method: 'post',
                data: {
                    otp_number: otp_number
                },
                success: function(response) {
                    if (response == 'true') {
                        event.preventDefault();
                        $("#btn_otp_loader").hide();
                        $('#OTP_number_div').hide();
                        $('#complete_profile_div').show();
                    } else {
                        event.preventDefault();
                        $("#btn_otp_loader").hide();
                        $('#OTP_number_div').show();
                        jQuery("#btn_otp_number").show();
                        alert('The OTP you have entered is incorrect.Please enter valid OTP or try again.');
                    }
                }
            });
        }
    });

    $('#btn_office_address').click(function(event) {
        $('#office_address_div').hide();
        $('#services_div').show();
    });

    $('#btn_office_address_skip').click(function(event) {
        $('#office_address_div').hide();
        $('#services_div').show();
    });

    $('#btn_service_skip').click(function(event) {
        $('#office_address_div').show();
        $('#services_div').hide();
    });
</script>


<input type="hidden" name="current_location_lat" id="current_location_lat" value="<?php echo (isset($latitude) && $latitude != '') ? $latitude : '18.5333'; ?>" />
<input type="hidden" name="current_location_long" id="current_location_long" value="<?php echo (isset($longitude) && $longitude != '') ? $longitude : '73.866699'; ?>" />
<script src="http://maps.googleapis.com/maps/api/js?v=3.21&sensor=false&amp;libraries=places"></script>
<script src="<?php echo base_url(); ?>media/front/js/jquery.geocomplete.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Google map for current address on entering zipcode it reflects on map and show the location of this zipcode which enter earlier.
        var map;
        var map2;
        var map3;
        var marker;
        var marker2;
        var marker3;
        var infowindow = new google.maps.InfoWindow();
        var infowindow2 = new google.maps.InfoWindow();
        var infowindow3 = new google.maps.InfoWindow();
        function initialize() {
            var latitude = $('#current_location_lat').val();
            var longitude = $('#current_location_long').val();
            var mapOpt = {
                center: new google.maps.LatLng(latitude, longitude),
                zoom: 15,
                minZoom: 3,
            };

            map = new google.maps.Map(document.getElementById("current_add_map"), mapOpt);

            $("#current_add_zipcode").geocomplete().bind("geocode:result", function(event, result) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': result.formatted_address}, function(results, status) {

                    var address = results[0].address_components;
                    var zipcode = address[address.length - 1].long_name;
                    if ($.isNumeric(zipcode)) {
                        $('#current_add_zipcode').val(zipcode);
                    } else {
                        var current_add_zipcode = ($("#current_add_zipcode").val()).replace(/[A-Za-z.,$-]/g, "");
                        $('#current_add_zipcode').val(current_add_zipcode.replace(/\s+/g, ''));
                    }

                    var location = results[0].geometry.location;
                    var lat = location.lat();
                    var lng = location.lng();
                    if (typeof (marker) != "undefined" && marker !== null) {
                        marker.setMap(null);
                    }
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lng),
                        offset: '0',
                        map: map,
                        draggable: true,
                    });
                    map.panTo(new google.maps.LatLng(lat, lng));
                    $("#current_add_lat").val(lat);
                    $("#current_add_long").val(lng);
                    if (typeof (marker) != "undefined" && marker !== null) {
                        google.maps.event.addListener(marker, 'dragend', function() {
                            //after drging marker geocodePositionCurrentAdd function will call 
                            geocodePositionCurrentAdd(marker.getPosition());
                        });
                    }

                    //on entering zipcode info window will apear on marker
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                });
            });

            // this will call after dregged marker and info window will apear on that marker and also get draged marker lat long
            function geocodePositionCurrentAdd(pos) {
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({latLng: pos}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);
                        var address = results[0].address_components;
                        var zipcode = address[address.length - 1].long_name;
                        $('#current_add_zipcode').val(zipcode);
                    }
                });
                $("input[name=current_add_lat]").val(pos.G);
                $("input[name=current_add_long]").val(pos.K);
            }

            // Google map for Permanant address, on entering zipcode it reflects on map and show the location of this zipcode which enter earlier.

            map2 = new google.maps.Map(document.getElementById("permanant_add_map"), mapOpt);

            $("#permanant_add_zipcode").geocomplete().bind("geocode:result", function(event, result2) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': result2.formatted_address}, function(results, status) {

                    var address = results[0].address_components;
                    var zipcode = address[address.length - 1].long_name;
                    if ($.isNumeric(zipcode)) {
                        $('#permanant_add_zipcode').val(zipcode);
                    } else {
                        var permanant_add_zipcode = ($("#permanant_add_zipcode").val()).replace(/[A-Za-z.,$-]/g, "");
                        $('#permanant_add_zipcode').val(permanant_add_zipcode.replace(/\s+/g, ''));
                    }



                    var location = results[0].geometry.location;
                    var lat = location.lat();
                    var lng = location.lng();
                    if (typeof (marker2) != "undefined" && marker2 !== null) {
                        marker2.setMap(null);
                    }
                    marker2 = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lng),
                        offset: '0',
                        map: map2,
                        draggable: true,
                    });
                    map2.panTo(new google.maps.LatLng(lat, lng));
                    $("#permanant_add_lat").val(lat);
                    $("#permanant_add_long").val(lng);
                    if (typeof (marker2) != "undefined" && marker2 !== null) {
                        google.maps.event.addListener(marker2, 'dragend', function() {
                            //after drging marker geocodePositionCurrentAdd function will call 
                            geocodePositionPermanantAdd(marker2.getPosition());
                        });
                    }
                    //on entering zipcode info window will apear on marker
                    infowindow2.setContent(results[0].formatted_address);
                    infowindow2.open(map2, marker2);
                });
            });

            // this will call after dregged marker and info window will apear on that marker and also get draged marker lat long
            function geocodePositionPermanantAdd(pos) {
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({latLng: pos}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        infowindow2.setContent(results[0].formatted_address);
                        infowindow2.open(map2, marker2);
                        var address = results[0].address_components;
                        var zipcode = address[address.length - 1].long_name;
                        $('#permanant_add_zipcode').val(zipcode);
                    }
                });
                $("input[name=permanant_add_lat]").val(pos.G);
                $("input[name=permanant_add_long]").val(pos.K);
            }

            // Google map for Permanant address, on entering zipcode it reflects on map and show the location of this zipcode which enter earlier.

            map3 = new google.maps.Map(document.getElementById("office_add_map"), mapOpt);

            $("#office_add_zipcode").geocomplete().bind("geocode:result", function(event, result3) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': result3.formatted_address}, function(results, status) {

                    var address = results[0].address_components;
                    var zipcode = address[address.length - 1].long_name;
                    if ($.isNumeric(zipcode)) {
                        $('#office_add_zipcode').val(zipcode);
                    } else {
                        var office_add_zipcode = ($("#office_add_zipcode").val()).replace(/[A-Za-z.,$-]/g, "");
                        $('#office_add_zipcode').val(office_add_zipcode.replace(/\s+/g, ''));
                    }



                    var location = results[0].geometry.location;
                    var lat = location.lat();
                    var lng = location.lng();
                    if (typeof (marker3) != "undefined" && marker3 !== null) {
                        marker3.setMap(null);
                    }
                    marker3 = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lng),
                        offset: '0',
                        map: map3,
                        draggable: true,
                    });
                    map3.panTo(new google.maps.LatLng(lat, lng));
                    $("#office_add_lat").val(lat);
                    $("#office_add_long").val(lng);
                    if (typeof (marker3) != "undefined" && marker3 !== null) {
                        google.maps.event.addListener(marker3, 'dragend', function() {
                            //after drging marker geocodePositionCurrentAdd function will call 
                            geocodePositionOfficeAdd(marker3.getPosition());
                        });
                    }
                    //on entering zipcode info window will apear on marker
                    infowindow3.setContent(results[0].formatted_address);
                    infowindow3.open(map3, marker3);
                });
            });

            // this will call after dregged marker and info window will apear on that marker and also get draged marker lat long
            function geocodePositionOfficeAdd(pos) {
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({latLng: pos}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        infowindow3.setContent(results[0].formatted_address);
                        infowindow3.open(map3, marker3);

                        var address = results[0].address_components;
                        var zipcode = address[address.length - 1].long_name;
                        $('#office_add_zipcode').val(zipcode);
                    }
                });
                $("input[name=office_add_lat]").val(pos.G);
                $("input[name=office_add_long]").val(pos.K);
            }
        }
        google.maps.event.addDomListener(window, 'load', initialize);

        // this function checks that complete profile all fileds are filled or not and then it has been hide this complete profile div and shows the home address div
        $('#btn_complete_profile').click(function(event) {
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var middle_name = $('#middle_name').val();
            var user_email = $('#user_email').val();
            var user_name = $('#user_name').val();

            if (first_name != '' && last_name != '' && middle_name != '' && user_email != '' && user_name != '') {
                event.preventDefault();
                $('#complete_profile_div').hide();
                $('#home_address_div').show();
                google.maps.event.trigger(map, 'resize', {});
                google.maps.event.trigger(map2, 'resize', {});
            }
        })

        //this function is for check current address is same as permanent address
        $('#current_add_same_as_permanent').click(function(event) {
            if ($('#current_add_same_as_permanent').is(':checked')) {
                $('#same_as_above_address_div').slideToggle();
                $('#permanant_add_map').slideToggle();
            } else {
                $('#same_as_above_address_div').slideToggle();
                $('#permanant_add_map').slideToggle();
                google.maps.event.trigger(map2);
            }
        });

        // this function checks that Office Address all fileds are filled or not and then it has been hide this Office Address div and shows the My services div
        $('#btn_home_address').click(function(event) {
            var permanant_add_zipcode = $('#permanant_add_zipcode').val();
            var permanant_add_first = $('#permanant_add_first').val();
            var current_add_zipcode = $('#current_add_zipcode').val();
            var address_first = $('#current_add_first').val();
            var current_add_same_as_permanent = $('#current_add_same_as_permanent').is(':checked');
            if (current_add_zipcode != '' && address_first != '') {
                if (current_add_same_as_permanent == false) {
                    if (permanant_add_first != '' && permanant_add_zipcode != '') {
                        event.preventDefault();
                        $('#home_address_div').hide();
                        $('#office_address_div').show();
                        google.maps.event.trigger(map3, 'resize', {});
                    }
                } else {
                    event.preventDefault();
                    $('#home_address_div').hide();
                    $('#office_address_div').show();
                    google.maps.event.trigger(map3, 'resize', {});
                }
            }
        })

    });

    // for geting current Address states details
    function currentAddStateInfo(value) {
        var country_id = value;
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>front/get-state-info',
            data: {
                'country_id': country_id
            },
            success: function(msg) {
                if (msg != 'false') {
                    $("#curr_add_state_div").css("display", "block");
                    $("#curr_add_state_div").html(msg);
                }
                else {
                    $("#curr_add_state_div").css("display", "block");
                }
            }
        });
    }

    // for geting Current Address City details
    function currentAddCityInfo(value) {
        var state_id = value;
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>front/get-city-info',
            data: {
                'state_id': state_id
            },
            success: function(msg) {
                if (msg != 'false') {
                    $("#curr_add_city_div").css("display", "block");
                    $("#curr_add_city_div").html(msg);
                }
                else {
                    $("#curr_add_city_div").css("display", "block");
                }
            }
        });
    }

    // for geting Permanant Address states details
    function permanantAddStateInfo(value) {
        var country_id = value;
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>front/get-state-info',
            data: {
                'country_id': country_id,
                'permanant_add': 'Yes'
            },
            success: function(msg) {
                if (msg != 'false') {
                    $("#per_add_state_div").css("display", "block");
                    $("#per_add_state_div").html(msg);
                }
                else {
                    $("#per_add_state_div").css("display", "block");
                }
            }
        });
    }
    // for geting Permanant Address City details
    function permanantAddCityInfo(value) {
        var state_id = value;
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>front/get-city-info',
            data: {
                'state_id': state_id,
                'permanant_add': 'Yes'
            },
            success: function(msg) {
                if (msg != 'false') {
                    $("#per_add_city_div").css("display", "block");
                    $("#per_add_city_div").html(msg);
                }
                else {
                    $("#per_add_city_div").css("display", "block");
                }
            }
        });
    }

    // for geting Office Address states details
    function officeAddStateInfo(value) {
        var country_id = value;
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>front/get-state-info',
            data: {
                'country_id': country_id,
                'office_add': 'Yes'
            },
            success: function(msg) {
                if (msg != 'false') {
                    $("#office_add_state_div").css("display", "block");
                    $("#office_add_state_div").html(msg);
                }
                else {
                    $("#office_add_state_div").css("display", "block");
                }
            }
        });
    }
    // for geting Permanant Address City details
    function officeAddCityInfo(value) {
        var state_id = value;
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>front/get-city-info',
            data: {
                'state_id': state_id,
                'office_add': 'Yes'
            },
            success: function(msg) {
                if (msg != 'false') {
                    $("#office_add_city_div").css("display", "block");
                    $("#office_add_city_div").html(msg);
                }
                else {
                    $("#office_add_city_div").css("display", "block");
                }
            }
        });
    }
</script>
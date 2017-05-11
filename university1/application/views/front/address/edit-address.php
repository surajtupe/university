<section class="middle-section">
    <div class="container">
        <div class="mid-section">
            <div class="alert_msg_div"></div>
            <div class="head">
                <?php if ($address_details[0]['address_type_id_fk'] == '2') { ?> 
                    <h2>CURRENT HOME ADDRESS</h2>
                <?php } else if ($address_details[0]['address_type_id_fk'] == '3') { ?>
                    <h2>PERMANENT HOME ADDRESS</h2>
                <?php } else { ?>
                    <h2>OFFICE ADDRESS</h2>
                <?php } ?>
            </div>
            <form class="form-horizontal" id="frm_current_address" name="frm_current_address" method="post" action="">
                <div class="row"> 
                    <div class="col-xs-12">
                        <a href="javascript:void(0);" onclick="forthStepBackBtn()" id="back_btn_curr_add" class="back-btn pull-right" style="display: none">Back</a>
                        <a href="<?php echo base_url() . 'view-address/' . base64_encode($address_id) ?>" id="back_btn" class="back-btn pull-right">Back</a>
                    </div>
                </div>
                <div id="current_address_div">
                    <div class="buliding-photo">
                        <div class="build-img">
                            <a href="javascript:void(0);" onclick="openFileAddress();"><img src="<?php echo base_url() ?>media/front/img/address-picture/thumbs/<?php echo $address_details[0]['address_picture'] ?>"  onerror="src='<?php echo base_url() ?>media/front/img/add-img.png'" id="address_img"/></a>
                            <input type="file" value="" id="address_building_pic" name="address_building_pic">
                            <input type="hidden" value="<?php echo $address_details[0]['address_picture'] ?>" id="old_address_building_pic" name="old_address_building_pic" style="display:none">
                        </div>
                        <p>ADDRESS NAME: <span><?php echo $address_details[0]['address_name'] ?></span> </p>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6">
                            <label>COUNTRY</label>
                            <div class="select-box">
                                <select class="form-control" name="current_add_country" id="current_add_country" onChange="StateInfo(this.value);">
                                    <option value=""> SELECT COUNTRY</option>
                                    <?php
                                    if (isset($arr_country_details) && COUNT($arr_country_details) > 0) {
                                        foreach ($arr_country_details as $country_details) {
                                            ?>
                                            <option value="<?php echo $country_details['country_id'] ?>" <?php echo (isset($address_details[0]['country_id_fk']) && $address_details[0]['country_id_fk'] == $country_details['country_id']) ? 'selected' : ''; ?>> <?php echo $country_details['country_name'] ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 form-margin">
                            <label>STATE</label>
                            <div class="select-box" id="curr_add_state_div">
                                <select class="form-control" name="current_add_state" id="current_add_state">
                                    <option value=""> SELECT STATE</option>
                                    <?php
                                    if (isset($arr_state_details) && $arr_state_details != '') {
                                        foreach ($arr_state_details as $state_details) {
                                            ?>
                                            <option value="<?php echo $state_details['id'] ?>"<?php echo (isset($address_details[0]['state_id_fk']) && $address_details[0]['state_id_fk'] == $state_details['id']) ? 'selected' : ''; ?>><?php echo $state_details['state_name'] ?></option> 
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6">
                            <label>CITY</label>
                            <div class="select-box" id="curr_add_city_div">
                                <select class="form-control" name="current_add_city" id="current_add_city">
                                    <option value=""> SELECT CITY</option>
                                    <?php
                                    if (isset($arr_city_details) && $arr_city_details != '') {
                                        foreach ($arr_city_details as $city_details) {
                                            ?>
                                            <option value="<?php echo $city_details['city_id'] ?>"<?php echo (isset($address_details[0]['city_id_fk']) && $address_details[0]['city_id_fk'] == $city_details['city_id']) ? 'selected' : ''; ?>> <?php echo $city_details['city_name'] ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 form-margin">
                            <label>ZIPCODE</label>
                            <input type="text" class="form-control" id="current_add_zipcode" name="current_add_zipcode" placeholder="ZIPCODE" value="<?php echo $address_details[0]['zip_code'] ?>" onblur="addressMap(this.value)"> 
                            <div for="current_add_zipcode" style="display: none;" id='error_zipcode_current' generated="true" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">ADDRESS LINE 1</label>
                        <div class="col-xs-12">
                            <textarea class="form-control" name="current_add_first" id="current_add_first" placeholder="ADDRESS LINE 1"><?php echo $address_details[0]['address_line1'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group offset-bot-0">
                        <label class="col-xs-12">ADDRESS LINE 2</label>
                        <div class="col-xs-12">
                            <textarea class="form-control" name="current_add_second" id="current_add_second" placeholder="ADDRESS LINE 2"><?php echo $address_details[0]['address_line2'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="checkbox"><label><input type="checkbox" name="current_location_same_as_above" id="current_location_same_as_above" /> MY CURRENT LOCATION IS SAME AS ABOVE ADDRESS</label></div>
                        </div>
                    </div>
                </div>
                <div class="location" id="current_address_map_div" style="display: none">
                    <p>LOCATION YOUR CURRENT HOME ADDRESS ON THE MAP</p>
                    <div class="" id="current_add_map" style="height: 283px;width: 422px"></div>
                    <input type="hidden" name="current_add_lat" id="current_add_lat" value="">
                    <input type="hidden" name="current_add_long" id="current_add_long" value="">
                    <input type="hidden" name="address_id" id="address_id" value="<?php echo $address_details[0]['address_id'] ?>">
                </div> 
                <div class="ft-box">
                    <button class="btn blue-btn" name="btn_submit" id="btn_submit" onclick="updateAddress()">SAVE CHANGES</button>
                    <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    // for geting current Address states details
    function StateInfo(value) {
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
    $('#current_add_zipcode').val('');
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

$('#current_add_city').on('change', function() {
            $('#current_add_zipcode').val('');
        });
    function updateAddress() {
        var form = document.getElementById('frm_current_address');
        var formData = new FormData(form);

        if ($('#frm_current_address').valid()) {
            if ($('#current_address_map_div').css('display') == 'none') {
                $('#current_address_div').css('display', 'none');
                $('#back_btn').css('display', 'none');
                $('#current_address_map_div').css('display', 'block');
                $('#back_btn_curr_add').css('display', 'block');
                var center = map.getCenter();
                google.maps.event.trigger(map, 'resize', {});
                map.setCenter(center);
            } else {

                $('#next_btn_current_add').hide();
                $('#back_btn_curr_add').hide();
                $('#loader_curr_add').show();

                $.ajax({
                    url: '<?php echo base_url() ?>update-address',
                    method: 'post',
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    data: formData,
                    success: function(response) {
                        if (response != '') {
                            if (response.msg == 'msg_success') {
                                location.href = '<?php echo base_url() ?>view-address/' + response.address_id
                            }
                        }
                    }
                });
            }
        }
    }

    function forthStepBackBtn() {
        $('#current_address_map_div').css('display', 'none');
        $('#back_btn_curr_add').css('display', 'none');
        $('#current_address_div').css('display', 'block');
        $('#back_btn').css('display', 'block');
    }
</script>
<input type="hidden" name="current_location_lat" id="current_location_lat" value="<?php echo (isset($address_details[0]['latitude']) && $address_details[0]['latitude'] != '') ? $address_details[0]['latitude'] : $latitude; ?>" />
<input type="hidden" name="current_location_long" id="current_location_long" value="<?php echo (isset($address_details[0]['longitude']) && $address_details[0]['longitude'] != '') ? $address_details[0]['longitude'] : $longitude; ?>" />
<!--<input type="hidden" name="current_location_lat" id="current_location_lat" value="<?php echo $latitude; ?>" />
<input type="hidden" name="current_location_long" id="current_location_long" value="<?php echo $longitude; ?>" />-->
<script src="http://maps.googleapis.com/maps/api/js?v=3.21&sensor=false&amp;libraries=places&region=<?php echo $country_code_geo ?>"></script>
<script src="<?php echo base_url(); ?>media/front/js/jquery.geocomplete.js"></script>
<script type="text/javascript">
    var map;
    var marker;
    var infowindow = new google.maps.InfoWindow();
    var latitude = $('#current_location_lat').val();
    var longitude = $('#current_location_long').val();
    var myLatLng = new google.maps.LatLng(latitude, longitude)
    function initialize() {
        var mapOpt = {
            center: myLatLng,
            zoom: 15,
            minZoom: 3,
            country: 'IND'
        };
        map = new google.maps.Map(document.getElementById("current_add_map"), mapOpt);
        var lat = latitude;
        var lng = longitude;
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
                //after draging marker geocodePositionCurrentAdd function will call 
                geocodePositionCurrentAdd(marker.getPosition());
            });
        }

    }

    // this will call after dregged marker and info window will apear on that marker and also get draged marker lat long
    function geocodePositionCurrentAdd(pos) {
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({latLng: pos}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
            }
        });
        $("input[name=current_add_lat]").val(pos.G);
        $("input[name=current_add_long]").val(pos.K);
    }

    $('#current_location_same_as_above').on('click', function() {
        if (this.checked) {
            if (typeof (marker) != "undefined" && marker !== null) {
                marker.setMap(null);
            }
            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                center: 'maharashtra',
                position: new google.maps.LatLng('<?php echo $latitude ?>', '<?php echo $longitude ?>')
            });
            map.panTo(new google.maps.LatLng('<?php echo $latitude ?>', '<?php echo $longitude ?>'));
            if (typeof (marker) != "undefined" && marker !== null) {
                google.maps.event.addListener(marker, 'dragend', function() {
                    //after draging marker geocodePositionCurrentAdd function will call 
                    geocodePositionCurrentAdd(marker.getPosition());
                });
            }
            $("input[name=current_add_lat]").val(<?php echo $latitude ?>);
            $("input[name=current_add_long]").val(<?php echo $longitude ?>);
        } else {
            addressMap($('#current_add_zipcode').val())
        }
    });
    function addressMap(value) {
        var current_add_zipcode = value;
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': current_add_zipcode}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
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
                        //after draging marker geocodePositionCurrentAdd function will call 
                        geocodePositionCurrentAdd(marker.getPosition());
                    });
                }
                //on entering zipcode info window will apear on marker
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
            } 
//            else {
//                $('#error_zipcode_current').text('');
//                $('#error_zipcode_current').text('Please enter valid zipcode.');
//                $('#error_zipcode_current').show();
//                $('#current_add_zipcode').val('');
//            }
        });

    }
    google.maps.event.addDomListener(window, 'load', initialize);

    $('#address_building_pic').on('change', function() {
        var file, img;
        var extension = this.files[0].name.split('.').pop().toLowerCase();
        if ((file = this.files[0])) {
            var base_url = $("#base_url").val();
            var reader = new FileReader();
            reader.onload = function(e) {
                switch (extension) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                    case 'JPG':
                    case 'JPEG':
                    case 'PNG':
                    case 'GIF':
                        break;
                    default:
                        $("#curr_add_building_pic").replaceWith($("#curr_add_building_pic").val('').clone(true));
                        $("#imgprvw_upload_license").hide();
                        alert('Please upload a file only of type jpg,png,gif,jpeg.');
                        return true;
                }
                $('#address_img').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
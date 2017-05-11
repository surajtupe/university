<script src="<?php echo base_url(); ?>media/front/js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>media/front/css/jquery-ui.css">
<script>
    $(function() {
        $("#date_from").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: 0,
            dateFormat: 'yy-m-d',
            yearRange: '2015:2070',
            onClose: function(dateText, e) {
                var date = $(this).datepicker('getDate');
                var newDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1);
                $("#date_to").datepicker("option", "minDate", newDate);
            }
        }).datepicker("setDate", new Date());
        ;
        $("#date_to").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-m-d',
            yearRange: '2015:2070',
            minDate: 1,
            defaultDate: '',
            onClose: function(selected_date) {
                $("#date_from").datepicker("option", "maxDate", selected_date);
            }
        });
    });

    function forthStepBackBtn() {
        $('#current_address_map_div').css('display', 'none');
        $('#back_btn_curr_add').css('display', 'none');
        $('#current_address_div').css('display', 'block');
    }
</script>
<section class="middle-section" id="current_address_step">
    <div class="container">
        <div class="mid-section">
            <div class="head">
                <?php if ($arr_get_address_details[0]['address_type_id_fk'] == '2') { ?> 
                    <h2>FORWARDING CURRENT ADDRESS</h2>
                <?php } else if ($arr_get_address_details[0]['address_type_id_fk'] == '3') { ?>
                    <h2>FORWARDING PERMANENT ADDRESS</h2>
                <?php } else { ?>
                    <h2>FORWARDING OFFICE ADDRESS</h2>
                <?php } ?>
            </div>
            <a href="javascript:void(0);" onclick="forthStepBackBtn()" id="back_btn_curr_add" class="back-btn pull-right" style="display: none">Back</a>
            <form class="form-horizontal" id="frm_current_address" name="frm_current_address" method="post" action="" enctype="multipart/form-data">
                <div id="current_address_div">
                    <div class="buliding-photo">
                        <div class="build-img">
                            <a href="javascript:void(0);" onclick="openFileCurrentAddress();"> <img src="<?php echo base_url() ?>media/front/img/add-img.png" id="current_add_img" /></a>
                            <input type="file" value="" id="curr_add_building_pic" name="curr_add_building_pic" style="display:none">
                        </div>
                        <p>ADDRESS NAME: <span><?php echo $arr_get_address_details[0]['address_name']; ?></span> </p>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6">
                            <label>COUNTRY</label>
                            <div class="select-box">
                                <select class="form-control" name="current_add_country" id="current_add_country" onChange="currentAddStateInfo(this.value);">
                                    <option value=""> SELECT COUNTRY</option>
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
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label>STATE</label>
                            <div class="select-box" id="curr_add_state_div">
                                <select class="form-control" name="current_add_state" id="current_add_state">
                                    <option value=""> SELECT STATE</option>
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
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label>ZIPCODE</label>
                            <input type="text" class="form-control" id="current_add_zipcode" name="current_add_zipcode" placeholder="ZIPCODE" onblur="addressMap(this.value)"> 
                            <div for="current_add_zipcode" style="display: none;" id='error_zipcode_current' generated="true" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">ADDRESS LINE 1</label>
                        <div class="col-xs-12">
                            <textarea class="form-control" name="current_add_first" id="current_add_first" placeholder="ADDRESS LINE 1"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">ADDRESS LINE 2</label>
                        <div class="col-xs-12">
                            <textarea class="form-control" name="current_add_second" id="current_add_second" placeholder="ADDRESS LINE 2"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6">
                            <label>Date From</label>
                            <input type="text" class="form-control" id="date_from" name="date_from" placeholder="FROM" readonly> 
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label>Date To</label>
                            <input type="text" class="form-control" id="date_to" name="date_to" placeholder="TO" readonly> 
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
                </div> 
                <div class="ft-box">
                    <input type="hidden" id="c_last_inser_id" name="c_last_inser_id" value="">
                    <button class="btn blue-btn" name="btn_submit" id="btn_submit" type="submit">SUBMIT</button>
                    <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    $("form").submit(function() {
        if ($('#frm_current_address').valid()) {
            if ($('#current_address_map_div').css('display') == 'none') {
                $('#current_address_div').css('display', 'none');
                $('#current_address_map_div').css('display', 'block');
                var center = map.getCenter();
                google.maps.event.trigger(map, 'resize', {});
                map.setCenter(center);
                $('#back_btn_curr_add').css('display', 'block');
            } else {
                $('#loader').show();
                $('#btn_submit').hide();
                $('#frm_current_address').submit();
            }
        }
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
                currentAddCityInfo('');
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
</script>
<input type="hidden" name="current_location_lat" id="current_location_lat" value="<?php echo (isset($latitude) && $latitude != '') ? $latitude : '18.5333'; ?>" />
<input type="hidden" name="current_location_long" id="current_location_long" value="<?php echo (isset($longitude) && $longitude != '') ? $longitude : '73.866699'; ?>" />
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
            initialize();
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
            } else {
                $('#error_zipcode_current').text('');
                $('#error_zipcode_current').text('Please enter valid zipcode.');
                $('#error_zipcode_current').show();
                $('#current_add_zipcode').val('');
            }
        });

    }
    google.maps.event.addDomListener(window, 'load', initialize);

    function openFileCurrentAddress() {
        document.getElementById("curr_add_building_pic").click();
    }

    $('#curr_add_building_pic').on('change', function() {
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
                $('#current_add_img').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
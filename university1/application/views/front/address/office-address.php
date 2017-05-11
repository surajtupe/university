<section class="middle-section" id="office_address_step">
    <div class="container">
        <div class="mid-section">
            <div class="head">
                <h2>OFFICE ADDRESS</h2>
            </div>
            <form class="form-horizontal" id="frm_office_address" name="frm_office_address" method="post" action="" enctype="multipart/form-data">
                <div id="office_address_div">
                    <div class="">
                        <a href="<?php echo base_url() ?>profile" id="back_btn_curr_add" class="back-btn">BACK</a>
                    </div>
                    <div class="buliding-photo">
                        <div class="build-img">
                            <a href="javascript:void(0);" onclick="openFileAddress();"><img src="<?php echo base_url() ?>media/front/img/add-img.png" id="address_img"/></a>
                            <input type="file" value="" id="address_building_pic" name="address_building_pic" style="display:none">
                        </div>
                        <p>ADDRESS NAME: <span><?php echo $office_address_name ?></span> </p>
                        <input type="hidden" value="<?php echo $office_address_name ?>" class="form-control" id="office_address_name" name="office_address_name">
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6">
                            <label>COUNTRY</label>
                            <div class="select-box">
                                <select class="form-control" name="office_add_country" id="office_add_country" onChange="officeAddStateInfo(this.value);">
                                    <option value=""> SELECT YOUR COUNTRY</option>
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
                            <div class="select-box" id="office_add_state_div">
                                <select class="form-control" name="office_add_state" id="office_add_state">
                                    <option value=""> SELECT YOUR STATE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6">
                            <label>CITY</label>
                            <div class="select-box" id="office_add_city_div">
                                <select class="form-control" name="office_add_city" id="office_add_city">
                                    <option value=""> SELECT YOUR CITY</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label>ZIPCODE</label>
                            <input type="text" class="form-control" id="office_add_zipcode" name="office_add_zipcode" placeholder="ZIPCODE"> 
                            <div for="office_add_zipcode" style="display: none;" id='error_zipcode_office' generated="true" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12">ADDRESS LINE 1</label>
                        <div class="col-xs-12">
                            <textarea class="form-control" name="office_add_first" id="office_add_first" placeholder="ADDRESS LINE 1"></textarea>
                        </div>
                    </div>
                    <div class="form-group offset-bot-0">
                        <label class="col-xs-12">ADDRESS LINE 2</label>
                        <div class="col-xs-12">
                            <textarea class="form-control" name="office_add_second" id="office_add_second" placeholder="ADDRESS LINE 2"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="checkbox"><label><input type="checkbox" name="office_location_same_as_above" id="office_location_same_as_above" /> MY OFFICE LOCATION IS SAME AS ABOVE ADDRESS</label></div>
                        </div>
                    </div>
                </div>
                <div class="location" id="office_address_map_div" style="display: none">
                    <p>LOCATION YOUR CURRENT HOME ADDRESS ON THE MAP</p>
                    <div class="" id="office_add_map" style="height: 283px;width: 422px"></div>
                    <input type="hidden" name="office_add_lat" id="office_add_lat" value="">
                    <input type="hidden" name="office_add_long" id="office_add_long" value="">
                </div> 
                <div class="ft-box">
                    <button href="javascript:void(0);" id="next_btn_office_add" class="btn blue-btn" type="submit">Save</button>
                    <div id="loader_office_add" style="display: none" class="three-quarters-loader">Loading…</div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
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

//    function sixthStepOfficeAddress() {
    $("form").submit(function() {
        if ($('#frm_office_address').valid()) {
            if ($('#office_address_map_div').css('display') == 'none') {
                $('#office_address_div').css('display', 'none');
                $('#office_address_map_div').css('display', 'block');
                var center = map2.getCenter();
                google.maps.event.trigger(map2, 'resize', {});
                map2.setCenter(center);
            } else {
                $('#back_btn_office_add').hide();
                $('#next_btn_office_add').hide();
                $('#loader_office_add').show();
                $('#frm_office_address').submit();
            }
        }
    });

</script>
<input type="hidden" name="current_location_lat" id="current_location_lat" value="<?php echo (isset($latitude) && $latitude != '') ? $latitude : '18.5333'; ?>" />
<input type="hidden" name="current_location_long" id="current_location_long" value="<?php echo (isset($longitude) && $longitude != '') ? $longitude : '73.866699'; ?>" />
<script src="http://maps.googleapis.com/maps/api/js?v=3.21&sensor=false&amp;libraries=places"></script>
<script src="<?php echo base_url(); ?>media/front/js/jquery.geocomplete.js"></script>
<script type="text/javascript">
    var map2;
    var marker2;
    var infowindow2 = new google.maps.InfoWindow();

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

        map2 = new google.maps.Map(document.getElementById("office_add_map"), mapOpt);
        var lat = latitude;
        var lng = longitude;

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

        $("#office_add_lat").val(lat);
        $("#office_add_long").val(lng);


        if (typeof (marker2) != "undefined" && marker2 !== null) {
            google.maps.event.addListener(marker2, 'dragend', function() {
                //after draging marker geocodePositionCurrentAdd function will call 
                geocodePositionOfficeAdd(marker2.getPosition());
            });
        }
    }

    // this will call after dregged marker and info window will apear on that marker and also get draged marker lat long
    function geocodePositionOfficeAdd(pos) {
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({latLng: pos}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                infowindow3.setContent(results[0].formatted_address);
                infowindow3.open(map3, marker3);
            }
        });
        $("input[name=office_add_lat]").val(pos.G);
        $("input[name=office_add_long]").val(pos.K);
    }


    $('#office_location_same_as_above').on('click', function() {
        if (this.checked) {
            var permanent_add_zipcode = $('#office_add_zipcode').val();
            if (permanent_add_zipcode != '') {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': permanent_add_zipcode}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
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
                                //after draging marker geocodePositionCurrentAdd function will call 
                                geocodePositionCurrentAdd(marker2.getPosition());
                            });
                        }
                        //on entering zipcode info window will apear on marker
                        infowindow2.setContent(results[0].formatted_address);
                        infowindow2.open(map2, marker2);
                    } else {
                        $('#error_zipcode_office').text('');
                        $('#error_zipcode_office').text('Please enter valid zipcode.');
                        $('#error_zipcode_office').show();
                        $('#office_add_zipcode').val('');

                        // alert('Please enter valid zipcode.');
                        $("#office_location_same_as_above").removeAttr('checked');
                    }
                });
            } else {
                alert('Please enter your zipcode.');
            }
        } else {
            initialize();
        }
    });
    google.maps.event.addDomListener(window, 'load', initialize);

    function openFileAddress() {
        document.getElementById("address_building_pic").click();
    }

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
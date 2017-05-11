
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            View User Address
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>backend/user/list"><i class="fa fa-fw fa-user"></i> Manage Users</a></li>
            <li class="active">User Profile</li>

        </ol>
    </section>
    <section class="content">
        <?php
        $msg = $this->session->userdata('msg');
        ?>
        <?php if ($msg != '') { ?>
            <div class="msg_box alert alert-success">
                <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">X</button>
                <?php
                echo $msg;
                $this->session->unset_userdata('msg');
                ?>
            </div>
        <?php } ?>

        <div class="col-md-12 infowthTab">
            <ul class="cstmTab clearfix">
                <li role="presentation" class="active"><a href="#current_address" aria-controls="current_address" role="tab" data-toggle="tab">Current Address</a></li>
                <li role="presentation"><a href="<?php echo base_url(); ?>backend/user/address/permant-address-view/<?php echo base64_encode($user_id); ?>">Permanent Address</a></li>
                <li role="presentation"><a href="<?php echo base_url(); ?>backend/user/address/office-address-view/<?php echo base64_encode($user_id); ?>" >Office Address</a></li>
                <li role="presentation"><a href="<?php echo base_url(); ?>backend/user/address/forward-address-view/<?php echo base64_encode($user_id); ?>">Forward Address</a></li>

            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="current_address">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-6"><br>
                                <p class="lead">Current Address</p>
                                <?php if (!empty($current_address)) { ?>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="width:50%">Address Name :</th>
                                                    <td><?php echo $current_address['address_name']; ?> </td>
                                                </tr>
                                                <tr>
                                                    <th>Address Line 1 :</th>
                                                    <td><?php echo $current_address['address_line1']; ?> </td>
                                                </tr>
                                                <tr>
                                                    <th>Address Line 2 :</th>
                                                    <td><?php echo $current_address['address_line1']; ?> </td>
                                                </tr>
                                                <tr>
                                                    <th>Country :</th>
                                                    <td> <?php echo $current_address['country_name']; ?> </td>
                                                </tr>

                                                <tr>
                                                    <th>State :</th>
                                                    <td><?php echo $current_address['state_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>City :</th>
                                                    <td><?php echo $current_address['city_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Zip Code :</th>
                                                    <td><?php echo $current_address['zip_code']; ?></td>
                                                </tr>
                                            </tbody>    
                                        </table>
                                        <!--<a class="btn btn-info" title="Edit Address Details" href="<?php echo base_url(); ?>backend/user/address/edit/<?php echo base64_encode($current_address['address_id']); ?>">  <i class="icon-edit icon-white"></i>Edit Address</a>-->
                                    </div>
                                <?php } else {
                                    ?>
                                    <p>Current address not found.</p>
                                <?php }
                                ?>
                            </div>
                            <?php if (!empty($current_address)) { ?>
                                <div class="col-xs-6"><br>
                                    <p class="lead">Map</p>    
                                    <input type="hidden" name="current_location_lat" id="current_location_lat" value="<?php echo $current_address['latitude']; ?>" />
                                    <input type="hidden" name="current_location_long" id="current_location_long" value="<?php echo $current_address['longitude']; ?>" />    
                                    <div id="current_add_map" style="width: 100%; height: 300px;"></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php $this->load->view('backend/sections/footer'); ?>
    <style type="text/css">
        .infowthTab .box.box-primary{
            border: none;
            border-radius: 0;
        }
        .cstmTab{
            padding: 0;
            list-style-type: none;
            margin-bottom: 0;
            background: #6c6c6c;
        }
        .cstmTab li{
            float: left;
        }
        .cstmTab li a{
            display: block;
            padding: 8px 12px;
            color: #ffffff;
            border-right:2px solid #ffffff;
        }
        .cstmTab li.active a{
            background: #444444;
        }
        #current_add_map {
            width: 500px;
            height: 400px;
        }

    </style>
    <script src="http://maps.googleapis.com/maps/api/js?v=3.21&sensor=false&amp;libraries=places"></script>
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
                zoom: 12,
                minZoom: 3,
                country: 'IND'
            };
            map = new google.maps.Map(document.getElementById("current_add_map"), mapOpt);
            var lat = latitude;
            var lng = longitude;
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                offset: '0',
                map: map,
                draggable: false,
            });
            map.panTo(new google.maps.LatLng(lat, lng));
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
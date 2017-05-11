<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Edit City</li>     
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/cities"><i class="fa fa-fw fa-home"></i> Manage Cities</a></li>
            <li>Edit City</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">   

                    <form name="edit_city_form" id="edit_city_form" action="<?php echo base_url(); ?>backend/cities/edit-city/<?php echo $city_id; ?>" method="post">
                        <input type="hidden" name="lang_id" id="lang_id" value="17" />
                        <input type="hidden" name="city_id" id="city_id" value="<?php echo $city_id; ?>" />
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Country Name<sup class="mandatory">*</sup></label>
                                <select id="country_id" name="country_id" class="form-control" onChange="updateStateInfo(this.value);" >

                                    <option value="">--Select Country--</option>
                                    <?php foreach ($arr_county_details as $row) { ?>
                                        <option value="<?php echo $row['country_id'] ?>" <?php echo($arr_city_details[0]['country_id_fk'] == $row['country_id']) ? 'selected="selected"' : ''; ?>><?php echo stripslashes($row['country_name']) ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="parametername">State Name<sup class="mandatory">*</sup></label>
                                <div class="Religion" id="state_div">

                                    <select id="state_id" name="state_id" class="form-control" >
                                        <?php foreach ($arr_state_details as $row) { ?>
                                            <option value="<?php echo $row['state_id']; ?>" <?php echo($arr_city_details[0]['state_id_fk'] == $row['state_id']) ? 'selected="selected"' : ''; ?>><?php echo stripslashes($row['state_name']); ?></option>
                                        <?php } ?>                               
                                    </select>   
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="parametername">City Name<sup class="mandatory">*</sup></label>

                                <input type="text" name="city_name" id="city_name" class="form-control"  value="<?php echo $arr_city_details[0]['city_name']; ?>">
                                <input type="hidden" name="old_city_name" id="old_city_name" value="<?php echo $arr_city_details[0]['city_name']; ?>">
                            </div>
                        </div>   

                        <div class="box-footer">
                            <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary" value="Save Changes">Save Changes</button>
                            <input type="hidden" name="city_id" id="country_id" value="<?php echo $arr_city_details[0]['city_id']; ?>" >
                            <input type="hidden" name="city_id_lang" id="city_id_lang" value="<?php echo $arr_city_details[0]['city_id_lang']; ?>" >
                            <button type="reset" name="cancel" class="btn" onClick="window.top.location = '<?php echo base_url(); ?>backend/cities';">Cancel</button>
                        </div>
                    </form>   
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>

        <script type="text/javascript" language="javascript">

            jQuery(document).ready(function() {

                jQuery("#edit_city_form").validate({
                    errorElement: 'div',
                    rules: {
                        country_id: {
                            required: true
                        },
                        state_id: {
                            required: true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-city-name",
                                type: "post",
                                data: {country_id: $("#country_id").val(), city_name: $("#city_name").val(), state: $("#state_id").val(), type: "edit", state:$("#state_id").val(), old_city_name: jQuery('#old_city_name').val()},
                            },
                        },
                        city_name: {
                            required: true,
                            lettersonly: true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-city-name",
                                type: "post",
                                data: {country_id: $("#country_id").val(), type: "edit", state: $("#state_id").val(), old_city_name: jQuery('#old_city_name').val()}
                            }
                        }

                    },
                    messages: {
                        city_name: {
                            required: "Please enter city name.",
                            lettersonly: "Please enter valid city name.",
                            remote: "This city already exists. "
                        },
                        state_id: {
                            required: "Please enter state name.",
                            remote: "This city and state combination is already exists. "
                        },
                        country_id: {
                            required: "Please select the  country."
                        }
                    },
                    submitHandler: function(form) {
                        $("#btn_submit").hide();
                        $("#loding_image").show();
                        form.submit();
                    }
                });
                 jQuery.validator.addMethod("lettersonly", function(value, element) {
                    return this.optional(element) || /^[a-z\s]+$/i.test(value);
                }, "");
            });
            function applyRemoteStateNameRule()
            {
                jQuery("#state_id").rules("remove");
                jQuery("#state_id").rules("add", {
                    required: true,
                    remote: {
                        url: "<?php echo base_url() ?>backend/check-city-name",
                        type: "post",
                        data: {country_id: $("#country_id").val(), city_name: $("#city_name").val(), state: $("#state_id").val(), type: "edit", state:$("#state_id").val(), old_city_name: jQuery('#old_city_name').val()},
                        complete: function(data)
                        {
                            if (data.responseText == "true")
                            {

                                jQuery("#city_name").val("");

                            }
                        },
                    }
                });
                jQuery("#city_name").rules("remove");
                jQuery("#city_name").rules("add", {
                    required: true,
                    remote: {
                        url: "<?php echo base_url() ?>backend/check-city-name",
                        type: "post",
                        data: {country_id: $("#country_id").val(), type: "edit", state: $("#state_id").val(), old_city_name: jQuery('#old_city_name').val()}
                    }
                });
            }


            function updateStateInfo(value) {

                var county_id = value;

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>backend/get-state-info',
                    data: {
                        'country_id': county_id
                    },
                    success: function(msg) {

                        if (msg != 'false') {
                            $("#state_div").css("display", "block");
                            $("#state_div").html(msg);
                            applyRemoteStateNameRule();
                        }
                        else {
                            $("#state_div").css("display", "block");
                        }
                    }
                });
            }

        </script>
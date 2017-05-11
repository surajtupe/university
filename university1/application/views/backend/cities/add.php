<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Add New City  
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/cities"> <i class="fa fa-fw fa-home"></i> Manage Cities</a></li>
            <li class="active">Add New City</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="add_city_form" id="add_city_form"  action="<?php echo base_url(); ?>backend/cities/add" method="POST" >
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Country Name<sup class="mandatory">*</sup></label>
                                <select id="country_id" name="country_id" class="form-control" onChange="updateStateInfo(this.value);" >
                                    >
                                    <option value="">--Select Country--</option>
                                    <?php foreach ($arr_country_details as $row) { ?>
                                        <option value="<?php echo $row['country_id'] ?>"><?php echo $row['country_name'] ?></option>
                                    <?php } ?>
                                </select>

                            </div>

                            <div class="form-group">

                                <label for="parametername">State Name<sup class="mandatory">*</sup></label>
                                <div id="state_div">
                                    <select id="state_id" name="state_id" class="form-control">
                                        <option value="">--Select State--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="parametername">City Name<sup class="mandatory">*</sup></label>
                                <input type="text" value="" name="city_name" id="city_name" class="form-control">

                            </div>
                        </div>
                        <div class="box-footer">
                            <button id="btn_submit" type="submit" name="btn_submit" class="btn btn-primary" value="Save" id="btnSubmit">Save</button>
                            <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image"> 
                        </div>
                </div>
                </form>
            </div>
        </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        <script type="text/javascript" language="javascript">
            /* add admin Js */

            function updateStateInfo(value) {

                var country_id = value;
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>backend/get-state-info',
                    data: {
                        'country_id': country_id
                    },
                    success: function (msg) {
                        if (msg != 'false') {
                            $("#state_div").css("display", "block");
                            $("#state_div").html(msg);

                        }
                        else {
                            $("#state_div").css("display", "block");
                        }
                        applyRemoteStateNameRule();
                    }
                });
            }

            jQuery(document).ready(function () {

                jQuery("#add_city_form").validate({
                    errorElement: 'div',
                    rules: {
                        country_id: {
                            required: true
                        },
                        city_name: {
                            required: true,
                            lettersonly : true,
                        },
                    },
                    messages: {
                        city_name: {
                            required: "Please enter city name.",
                            lettersonly : "Please enter valid city name.",
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
                    submitHandler: function (form) {
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
                        data: {country_id: $("#country_id").val(), city_name: $("#city_name").val(), state: $("#state_id").val()},
                        complete: function (data)
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
                        data: {country_id: $("#country_id").val(), state: $("#state_id").val()}
                    }
                });
            }
        </script>
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Add New State</li>     
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/states"> <i class="fa fa-fw fa-home"></i> Manage States</a></li>
            <li>Add State</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="add_state_form" id="add_state_form"  action="<?php echo base_url(); ?>backend/states/add" method="POST" >
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Country Name<sup class="mandatory">*</sup></label>
                                <select id="country_id" name="country_id" class="form-control">
                                    <option value="">--Select Country--</option>
                                    <?php foreach ($arr_country_details as $row) { ?>
                                        <option value="<?php echo $row['country_id'] ?>"><?php echo $row['country_name'] ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="parametername">State Name<sup class="mandatory">*</sup></label>
                                <input type="text" value="" name="state_name" id="state_name"  class="form-control">

                            </div>

                            <div class="box-footer">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save" id="btnSubmit">Save</button>
                                <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">	
                            </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>

        <script type="text/javascript" language="javascript">
            jQuery(document).ready(function () {

                jQuery("#add_state_form").validate({
                    errorElement: 'div',
                    rules: {
                        country_id: {
                            required: true
                        },
                        state_name: {
                            required: true,
                            lettersonly : true,
                            remote: {
                                url: "<?php echo base_url() ?>backend/check-states-name",
                                type: "post",
                                data: {}
                            }
                        }
                    },
                    messages: {
                        state_name: {
                            required: "Please enter state name.",
                            lettersonly: "Please enter valid state name.",
                            remote: "This state name already exists. "
                        },
                        country_id: {
                            required: "Please select the country."
                        }
                    },
                    submitHandler: function (form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }
                });
                jQuery.validator.addMethod("lettersonly", function(value, element) {
                    return this.optional(element) || /^[a-z\s]+$/i.test(value);
                }, "");
            });
        </script>
        </html>
<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Edit State 
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/states"> <i class="fa fa-fw fa-home"></i> Manage States</a></li>
            <li class="active">Edit State</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form  name="edit_state_form" id="edit_state_form" action="<?php echo base_url(); ?>backend/states/edit-states/<?php echo $arr_state_details[0]['id']; ?>" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Country Name<sup class="mandatory">*</sup></label>
                                <select id="country_id" name="country_id" class="form-control">
                                    <option value="">--Select Country--</option>
                                    <?php foreach ($arr_country_details as $row) { ?>
                                        <option value="<?php echo $row['country_id'] ?>" <?php echo($arr_state_details[0]['country'] == $row['country_id']) ? 'selected="selected"' : ''; ?>><?php echo $row['country_name'] ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="parametername">State Name<sup class="mandatory">*</sup></label>
                                <input type="text" class="form-control" name="state_name" id="state_name" value="<?php echo stripslashes($arr_state_details[0]['state_name']); ?>">

                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary" value="Save Changes">Save Changes</button>  
                            <input type="hidden" name="state_id" id="country_id" value="<?php echo $arr_state_details[0]['id']; ?>" >
                            <button type="reset" name="cancel" class="btn" onClick="window.top.location = '<?php echo base_url(); ?>backend/states';">Cancel</button>

                        </div>

                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        </div>
        </body>
        <script type="text/javascript" language="javascript">
            jQuery(document).ready(function () {


                jQuery("#edit_state_form").validate({
                    errorElement: 'div',
                    rules: {
                        state_name: {
                            required: true,
                            lettersonly : true,
                        },
                        country_id: {
                            required: true
                        },
                    },
                    messages: {
                        state_name: {
                            required: "Please enter state name.",
                            lettersonly: "Please enter valid state name.",
                        },
                        country_id: {
                            required: "Please select country."
                        }
                    }
                });
                jQuery.validator.addMethod("lettersonly", function(value, element) {
                    return this.optional(element) || /^[a-z\s]+$/i.test(value);
                }, "");
            });
        </script>
        </html>
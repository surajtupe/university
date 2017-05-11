<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Change State Name in Multi-Language    
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/states"> <i class="fa fa-fw fa-home"></i> Manage States</a></li>
            <li class="active">Change State Name in Multi-Language</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="frm_change_statelang" id="frm_change_statelang" action="<?php echo base_url(); ?>backend/state-change-language/<?php echo isset($arr_state_list[0]['state_lang_id']) ? $arr_state_list[0]['state_lang_id'] : ''; ?>" method="POST">
                        <input type="hidden" value="<?php echo base_url(); ?>" id="base_url" name="base_url">
                        <input type="hidden" value="<?php echo (isset($arr_state_list[0]['state_name'])) ? $arr_state_list[0]['state_name'] : ''; ?>" id="old_state_name" name="old_state_name">
                        <input type="hidden" value="<?php echo isset($arr_state_list[0]['state_id_fk']) ? $arr_state_list[0]['state_id_fk'] : ''; ?>" id="state_id_fk" name="state_id_fk">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Language<sup class="mandatory">*</sup></label>
                                <select  name="lang_id" id="lang_id" onChange="getStateName(this.value, '<?php echo isset($arr_state_list['0']['state_id_fk']) ? $arr_state_list['0']['state_id_fk'] : ''; ?>');" class="form-control">
                                    <option value="">Select Language</option>
                                    <?php foreach ($arr_get_language as $languages) { ?>
                                        <option value="<?php echo $languages['lang_id'] ?>" ><?php echo $languages['lang_name'] ?></option>
                                    <?php } ?>
                                </select>

                            </div>

                            <div class="control-group" style="display:none" id="state_div">

                            </div>

                            <div class="control-group" style="display:none" id="status_div">

                            </div>

                            <div class="box-footer">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary" value="Save Changes">Save Changes</button>  
                                <button type="reset" name="cancel" class="btn" onClick="window.top.location = '<?php echo base_url(); ?>backend/states';">Cancel</button>

                            </div>

                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>

        <script type="text/javascript">
            function getStateName(value, state_id_fk) {

                var lang_id = value;
                var state_id_fk = state_id_fk;
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>backend/state/state-name',
                    data: {
                        'lang_id': lang_id,
                        'state_id_fk': state_id_fk
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


        </script>

        <script type="text/javascript" language="javascript">
            jQuery(document).ready(function () {

                jQuery("#frm_change_statelang").validate({
                    errorElement: 'div',
                    rules: {
                        lang_id: {
                            required: true
                        }

                    },
                    messages: {
                        state_name: {
                            required: "Please enter state name.",
                            remote: "This state name already exists. "
                        },
                        lang_id: {
                            required: "Please select a language."
                        }
                    },
                    submitHandler: function (form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }

                });
            });
            function applyRemoteStateNameRule()
            {
                jQuery("#state_name").rules("remove");
                jQuery("#state_name").rules("add", {
                    required: true,
                    remote: {
                        url: "<?php echo base_url() ?>backend/check-states-name",
                        type: "post",
                        data: {old_state: jQuery('#old_state').val()
                        }
                    }
                });
            }
        </script>   
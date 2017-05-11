<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Edit Country in multi-language
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/countries"><i class="fa fa-fw fa-home"></i> Manage Countries</a></li>
            <li class="active">Edit Country in Multi-language</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="edit_mlultilanguage_country"  id="edit_mlultilanguage_country" action="<?php echo base_url(); ?>backend/country-change-language/<?php echo isset($arr_country_list[0]['country_lang_id']) ? $arr_country_list[0]['country_lang_id'] : ''; ?>" method="POST">
                        <input type="hidden" value="<?php echo base_url(); ?>" id="base_url" name="base_url">
                        <input type="hidden" value="<?php echo isset($arr_country_list[0]['country_lang_id']) ? $arr_country_list[0]['country_lang_id'] : ''; ?>" id="country_lang_id" name="country_lang_id" />
                        <input type="hidden" value="<?php echo isset($arr_country_list[0]['country_id_fk']) ? $arr_country_list[0]['country_id_fk'] : ''; ?>" id="country_id_fk" name="country_id_fk">												
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Language<sup class="mandatory">*</sup></label>
                                <select  name="lang_id" id="lang_id"  class="form-control" onChange="getCountryInfo(this.value, '<?php echo $arr_country_list['0']['country_id_fk']; ?>');">
                                    <option value="">Select Language</option>
                                    <?php foreach ($arr_get_language as $languages) { ?>
                                        <option value="<?php echo $languages['lang_id'] ?>" ><?php echo $languages['lang_name'] ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('lang_id'); ?>
                            </div>	  

                            <div class="control-group" style="display:none" id="country_div">

                            </div>


                            <div class="box-footer">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save Changes" id="btnSubmit">Save Changes</button>
                                <input type="hidden" name="country_id" id="country_id" value="<?php echo(isset($arr_country_details[0]['country_id']))?$arr_country_details[0]['country_id']:''; ?>" >
                                <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                            </div>

                   
                </div> </form>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        <script type="text/javascript">

            function getCountryInfo(value, country_id_fk) {

                var lang_id = value;
                var country_id_fk = country_id_fk;
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>backend/country/country-name',
                    data: {
                        'lang_id': lang_id,
                        'country_id_fk': country_id_fk
                    },
                    success: function (msg) {
                        if (msg != 'false') {
                            $("#country_div").css("display", "block");
                            $("#country_div").html(msg);
                        }
                        else {
                            $("#country_div").css("display", "block");
                        }
                        applyRemoteCountryNameRule();
                    }

                });
            }


        </script>

        <script type="text/javascript" language="javascript">
            jQuery(document).ready(function () {

                jQuery("#edit_mlultilanguage_country").validate({
                    errorElement: 'div',
                    rules: {
                        country_iso_name: {
                            required: true
                        },
                        lang_id: {
                            required: true
                        }
                    },
                    messages: {
                        country_iso_name: {
                            required: "Please enter country ISO name."
                        },
                        lang_id: {
                            required: "Please enter a language."
                        },
                        country_name: {
                            required: "Please enter country name.",
                            remote: "This country already exists."
                        }
                    }
                });

            });


            function applyRemoteCountryNameRule()
            {
                jQuery("#country_name").rules("remove");

                jQuery("#country_name").rules("add", {
                    required: true,
                    remote: {
                        url: "<?php echo base_url() ?>backend/check-country-name",
                        type: "post",
                        data: {old_country: jQuery('#old_country_name').val(),country_id:jQuery("#country_id_fk").val()}
                    }
                });
            }

        </script> 
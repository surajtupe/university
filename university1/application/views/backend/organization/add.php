<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            <?php echo (isset($edit_id) && $edit_id != "") ? "Update" : "Add New"; ?> Organization</li>     
        </h1>            
        <ol class="breadcrumb">
            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/organization/list"> Manage Organizations</a></li>
            <li><?php echo (isset($edit_id) && $edit_id != "") ? "Update" : "Add"; ?> Organization</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12"> 
                <div class="box box-primary">
                    <form name="frm_orgnization" id="frm_orgnization"  action="" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo(isset($edit_id)) ? $edit_id : '' ?>">
                        <div class="box-body">
                            <div class="row">
                                <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                    ?>	
                                    <div class="form-group">
                                        <label for="Language">Language<sup class="mandatory">*</sup></label>
                                        <select class="form-control" name="lang_id" id="lang_id">
                                            <option value="">Select Language</option>
                                            <?php foreach ($arr_get_language as $languages) { ?>
                                                <option value="<?php echo $languages['lang_id']; ?>" <?php echo(isset($document_details['lang_id']) && ($languages['lang_id'] == $document_details['lang_id'])) ? 'selected' : '' ?>><?php echo $languages['lang_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php echo form_error('lang_id'); ?>

                                    </div>	  
                                <?php } else { ?>
                                    <input type="hidden" name="lang_id" id="lang_id" value="17" />
                                <?php } ?>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="Language">Main Category:<sup class="mandatory">*</sup></label>
                                        <select class="form-control" name="category" id="category" onchange="updateSubcategoryInfo(this.value)">
                                            <option value="">Select Category</option>
                                            <?php foreach ($arr_categary_list as $categary_list) { ?>
                                                <option value="<?php echo $categary_list['service_category_id']; ?>" <?php echo (isset($oganization_details['parent_id']) && $oganization_details['parent_id'] == $categary_list['service_category_id']) ? 'selected' : ''; ?>><?php echo $categary_list['category_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group" id="category_div">
                                        <label for="Language">Sub Category:<sup class="mandatory">*</sup></label>
                                        <select class="form-control" name="sub_category_id" id="sub_category_id">
                                            <option value="">Select Sub Category</option>
                                            <?php
                                            if (isset($arr_get_sub_category) && COUNT($arr_get_sub_category) > 0) {
                                                foreach ($arr_get_sub_category as $sub_category) {
                                                    ?>
                                                    <option value="<?php echo $sub_category['service_category_id']; ?>" <?php echo (isset($oganization_details['service_category_id_fk']) && $oganization_details['service_category_id_fk'] == $sub_category['service_category_id']) ? 'selected' : ''; ?>><?php echo $sub_category['category_name'] ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Organization:<sup class="mandatory">*</sup></label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="orgnisation_name" id="orgnisation_name" value="<?php echo isset($oganization_details['orgnisation_name']) ? $oganization_details['orgnisation_name'] : ''; ?>"/>
                                        <input type="hidden" name="old_organization_name" id="old_organization_name" value="<?php echo isset($oganization_details['orgnisation_name']) ? $oganization_details['orgnisation_name'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Address:<sup class="mandatory">*</sup></label>
                                        <textarea class="form-control" name="address" id="address"><?php echo isset($oganization_details['address']) ? $oganization_details['address'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Primary Email:<sup class="mandatory">*</sup></label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="primary_email" id="primary_email" value="<?php echo isset($oganization_details['primary_email']) ? $oganization_details['primary_email'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Secondary Email:</label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="secondry_email" id="secondry_email" value="<?php echo isset($oganization_details['secondry_email']) ? $oganization_details['secondry_email'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Mobile Number:<sup class="mandatory">*</sup></label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="mobile_number" id="mobile_number" value="<?php echo isset($oganization_details['mobile_number']) ? $oganization_details['mobile_number'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Fax Number:</label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="fax_number" id="fax_number" value="<?php echo isset($oganization_details['fax_number']) ? $oganization_details['fax_number'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Land-line Number:</label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="landline_number" id="landline_number" value="<?php echo isset($oganization_details['landline_number']) ? $oganization_details['landline_number'] : ''; ?>"/>
                                    </div>
                                    <!--                                    <div class="form-group">
                                                                            <label for="parametername">Document Required:</label>
                                    <?php
                                    if (COUNT($arr_document_list) > 0) {
                                        foreach ($arr_document_list as $document_list) {
                                            echo "<pre>";
                                            print_r($document_details);
                                            echo "</pre>";
                                            ?>
                                                                                                                            <div class="form-group">
                                                                                                                                <label for="parametername"><?php echo $document_list['document_text'] ?> : </label>
                                            <?php if (isset($document_details['selected_document_list']) && $document_details['selected_document_list'] != '') { ?>
                                                                                                                                                        <input type="checkbox" dir="ltr"  name="document_required[]" id="document_required[]" value="<?php echo $document_list['document_id'] ?>"/>
                                            <?php } else { ?>
                                                                                                                                                        <input type="checkbox" dir="ltr"  name="document_required[]" id="document_required[]" value="<?php echo $document_list['document_id'] ?>"/>
                                            <?php } ?>
                                                                                                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                                                        </div>-->
                                    <div class="form-group">
                                        <label for="parametername">Document Required:</label>
                                        <?php
//                                                                            echo '<pre>';
//                                                                            print_r($oganization_details['selected_document_list']);die;
                                        if (COUNT($arr_document_list) > 0) {
                                            foreach ($arr_document_list as $document_list) {
                                                ?>
                                                <div class="form-group">
                                                    <label for="parametername"><?php echo $document_list['document_text'] ?> : </label>

                                                    <input type="checkbox" dir="ltr"   name="document_required[]" id="document_required[]" value="<?php echo $document_list['document_id'] ?>" <?php echo (in_array($document_list['document_id'],$oganization_details['selected_document_list'])) ? 'checked' : ''; ?>/>

                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="parametername">Admin Fees For Mobile Change:</label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="admin_fees_for_mobile_change" id="admin_fees_for_mobile_change" value="<?php echo isset($oganization_details['admin_fees_for_mobile_change']) ? $oganization_details['admin_fees_for_mobile_change'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Admin Fees For Address Change:</label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="admin_fees_for_address_change" id="admin_fees_for_address_change" value="<?php echo isset($oganization_details['admin_fees_for_address_change']) ? $oganization_details['admin_fees_for_address_change'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="parametername">Admin Fees For POC Change:</label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="admin_fees_for_POC_change" id="admin_fees_for_POC_change" value="<?php echo isset($oganization_details['admin_fees_for_POC_change']) ? $oganization_details['admin_fees_for_POC_change'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">Mobile Change Notified:</label>
                                        <input type="radio" id="number_changed_notified" name="number_changed_notified"  value="0" <?php echo (isset($oganization_details['number_changed_notified']) && $oganization_details['number_changed_notified'] == '0') ? 'checked' : ''; ?>> No
                                        <input type="radio" id="number_changed_notified" name="number_changed_notified"  value="1" <?php echo (isset($oganization_details['number_changed_notified']) && $oganization_details['number_changed_notified'] == '1') ? 'checked' : ''; ?>> Yes
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">Address Change Notified: </label>
                                        <input type="radio" id="address_changed_notified" name="address_changed_notified" value="0" <?php echo (isset($oganization_details['address_changed_notified']) && $oganization_details['address_changed_notified'] == '0') ? 'checked' : ''; ?>> No
                                        <input type="radio" id="address_changed_notified" name="address_changed_notified" value="1" <?php echo (isset($oganization_details['address_changed_notified']) && $oganization_details['address_changed_notified'] == '1') ? 'checked' : ''; ?>> Yes
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">POC Change Notified: </label>
                                        <input type="radio" id="poc_changed_notified" name="poc_changed_notified"  value="0" <?php echo (isset($oganization_details['poc_changed_notified']) && $oganization_details['poc_changed_notified'] == '0') ? 'checked' : ''; ?>> No
                                        <input type="radio" id="poc_changed_notified" name="poc_changed_notified" value="1" <?php echo (isset($oganization_details['poc_changed_notified']) && $oganization_details['poc_changed_notified'] == '1') ? 'checked' : ''; ?>> Yes
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">POC Support: </label>
                                        <input type="radio" id="support_poc" name="support_poc"  value="0" <?php echo (isset($oganization_details['support_poc']) && $oganization_details['support_poc'] == '0') ? 'checked' : ''; ?>> No
                                        <input type="radio" id="support_poc" name="support_poc" value="1" <?php echo (isset($oganization_details['support_poc']) && $oganization_details['support_poc'] == '1') ? 'checked' : ''; ?>> Yes
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">Document Required Address Change:</label>
                                        <input type="radio" id="document_required_address_change" name="document_required_address_change"  value="0" <?php echo (isset($oganization_details['document_required_address_change']) && $oganization_details['document_required_address_change'] == '0') ? 'checked' : ''; ?>> No
                                        <input type="radio" id="document_required_address_change" name="document_required_address_change" value="1" <?php echo (isset($oganization_details['document_required_address_change']) && $oganization_details['document_required_address_change'] == '1') ? 'checked' : ''; ?>> Yes
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">Payment Required Address Change: </label>
                                        <input type="radio" id="payment_required_address_change" name="payment_required_address_change"  value="0" <?php echo (isset($oganization_details['payment_required_address_change']) && $oganization_details['payment_required_address_change'] == '0') ? 'checked' : ''; ?> onclick="addressChangesCharges(this.value)"> No
                                        <input type="radio" id="payment_required_address_change" name="payment_required_address_change"  value="1" <?php echo (isset($oganization_details['payment_required_address_change']) && $oganization_details['payment_required_address_change'] == '1') ? 'checked' : ''; ?> onclick="addressChangesCharges(this.value)"> Yes
                                        <input type="hidden" id="payment_required_address_change_flag" name="payment_required_address_change_flag" value="<?php echo (isset($oganization_details['payment_required_address_change']) && $oganization_details['payment_required_address_change'] != '') ? $oganization_details['payment_required_address_change'] : ''; ?>">
                                    </div>
                                    <div class="form-group" id="add_change_chrges_div" style="display: none">
                                        <label for="parametername">Address Change Charges:</label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="address_change_charges" id="address_change_charges" value="<?php echo isset($oganization_details['address_change_charges']) ? $oganization_details['address_change_charges'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">Document Required Mobile Change: </label>

                                        <input type="radio" id="document_required_mobile_change" name="document_required_mobile_change"  value="0" <?php echo (isset($oganization_details['document_required_mobile_change']) && $oganization_details['document_required_mobile_change'] == '0') ? 'checked' : ''; ?>> No
                                        <input type="radio" id="document_required_mobile_change" name="document_required_mobile_change"  value="1" <?php echo (isset($oganization_details['document_required_mobile_change']) && $oganization_details['document_required_mobile_change'] == '1') ? 'checked' : ''; ?>> Yes

                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">Payment Required Mobile Change: </label>
                                        <input type="radio" id="payment_required_mobile_change" name="payment_required_mobile_change"  value="0" <?php echo (isset($oganization_details['payment_required_mobile_change']) && $oganization_details['payment_required_mobile_change'] == '0') ? 'checked' : ''; ?> onclick="mobileChangesCharges(this.value)"> No
                                        <input type="radio" id="payment_required_mobile_change" name="payment_required_mobile_change"  value="1" <?php echo (isset($oganization_details['payment_required_mobile_change']) && $oganization_details['payment_required_mobile_change'] == '1') ? 'checked' : ''; ?> onclick="mobileChangesCharges(this.value)"> Yes
                                        <input type="hidden" id="payment_required_mobile_change_flag" name="payment_required_mobile_change_flag" value="<?php echo isset($oganization_details['payment_required_mobile_change']) ? $oganization_details['payment_required_mobile_change'] : '' ?>">
                                    </div>
                                    <div class="form-group" id="mobile_change_chrges_div" style="display: none">
                                        <label for="parametername">Mobile Change Charges:</label>
                                        <input type="text" dir="ltr"  autofocus class="form-control" name="mobile_change_charges" id="mobile_change_charges" value="<?php echo isset($oganization_details['mobile_change_charges']) ? $oganization_details['mobile_change_charges'] : ''; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">Support Number Change: </label>

                                        <input type="radio" id="support_number_change" name="support_number_change"  value="0" <?php echo (isset($oganization_details['support_number_change']) && $oganization_details['support_number_change'] == '0') ? 'checked' : ''; ?>> No
                                        <input type="radio" id="support_number_change" name="support_number_change" value="1" <?php echo (isset($oganization_details['support_number_change']) && $oganization_details['support_number_change'] == '1') ? 'checked' : ''; ?>> Yes

                                    </div>
                                    <div class="form-group">
                                        <label for="User Name">Support Address Change:</label>
                                        <input type="radio" id="support_address_change" name="support_address_change"  value="0" <?php echo (isset($oganization_details['support_address_change']) && $oganization_details['support_address_change'] == '0') ? 'checked' : ''; ?> > No
                                        <input type="radio" id="support_address_change" name="support_address_change"  value="1" <?php echo (isset($oganization_details['support_address_change']) && $oganization_details['support_address_change'] == '1') ? 'checked' : ''; ?>> Yes
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" name="btn_submit" class="btn btn-primary" value="Save" id="btnSubmit">Save <?php echo (isset($edit_id) && $edit_id != "") ? "Changes" : ""; ?> </button>
                                <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>

        <script type="text/javascript" language="javascript">
            $(document).ready(function() {
                var payment_required_address_change_flag = $('#payment_required_address_change_flag').val();
                if (payment_required_address_change_flag == '1') {
                    $('#add_change_chrges_div').css('display', 'block');
                }

                var payment_required_mobile_change_flag = $('#payment_required_mobile_change_flag').val();
                if (payment_required_mobile_change_flag == '1') {
                    $('#mobile_change_chrges_div').css('display', 'block');
                }

                //                $("ins").remove();
                var javascript_site_path = '<?php echo base_url(); ?>'
                jQuery.validator.addMethod("lettersonly", function(value, element) {
                    return this.optional(element) || /^[a-z\s]+$/i.test(value);
                }, "Please enter valid name");

                jQuery.validator.addMethod("money",
                        function(value, element) {
                            var isValidMoney = /^[0-9,.]+$/.test(value);
                            return this.optional(element) || isValidMoney;
                        }, "Please enter valid amount.");

                jQuery("#frm_orgnization").validate({
                    errorElement: 'div',
                    rules: {
                        orgnisation_name: {
                            required: true,
                            lettersonly: true,
                            remote: {
                                url: javascript_site_path + 'chk-orgnisation-name',
                                method: 'post',
                                data: {
                                    old_organization_name: function() {
                                        return $('#old_organization_name').val();
                                    }
                                }
                            },
                        },
                        address: {
                            required: true
                        },
                        primary_email: {
                            required: true,
                            email: true,
                        },
                        secondry_email: {
                            email: true,
                        },
                        mobile_number: {
                            required: true,
                            digits: true,
                            minlength: 10,
                            maxlength: 10,
                        },
                        fax_number: {
                            digits: true,
                        },
                        landline_number: {
                            digits: true,
                        },
                        category: {
                            required: true
                        },
                        sub_category_id: {
                            required: true
                        },
                        admin_fees_for_mobile_change: {
                            money: true
                        },
                        admin_fees_for_address_change: {
                            money: true
                        },
                        admin_fees_for_POC_change: {
                            money: true
                        },
                        mobile_change_charges: {
                            required: true,
                            money: true
                        },
                        address_change_charges: {
                            required: true, money: true
                        }

                    }, messages: {
                        orgnisation_name: {
                            required: "Please enter organization name.",
                            remote: 'This organization name is already exists.'
                        },
                        address: {
                            required: "Please enter address."
                        },
                        primary_email: {
                            required: "Please enter primary email.",
                            email: "Please enter valid email."
                        },
                        mobile_number: {
                            required: "Please ener mobile number.",
                            minlength: jQuery.format("Please enter {0} digits number."),
                            maxlength: jQuery.format("Please enter only {0} digits number."),
                        },
                        category: {
                            required: "Please select category."
                        },
                        sub_category_id: {
                            required: "Please sub select category."
                        },
                        secondry_email: {
                            email: "Please enter valid email."
                        },
                        mobile_change_charges: {
                            required: "Please enter mobile change charges."
                        },
                        address_change_charges: {
                            required: "Please enter address change charges."
                        }
                    },
                    submitHandler: function(form) {
                        $("#btnSubmit").hide();
                        $('#loding_image').show();
                        form.submit();
                    }
                });
            });

            function updateSubcategoryInfo(value) {
                var category_id = value;
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>backend/get-category-info',
                    data: {
                        'category_id': category_id
                    },
                    success: function(msg) {
                        if (msg != 'false') {
                            $("#category_div").css("display", "block");
                            $("#category_div").html(msg);
                        } else {
                            $("#category_div").css("display", "block");
                        }

                    }
                });
            }

            function mobileChangesCharges(value) {
                if (value == 1) {
                    $('#mobile_change_chrges_div').css('display', 'block');
                } else {
                    $('#mobile_change_chrges_div').css('display', 'none');
                }
            }

            function addressChangesCharges(value) {
                if (value == 1) {
                    $('#add_change_chrges_div').css('display', 'block');
                } else {
                    $('#add_change_chrges_div').css('display', 'none');
                }
            }
        </script>
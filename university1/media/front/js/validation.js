function generateCode() {
    $.ajax({
        type: 'post',
        data: '',
        url: javascript_site_path + 'generate-new-password-code',
        dataType: 'json',
        success: function(data) {
            alert(data.success_msg);

        },
        error: function(data) {

            alert(data.error_msg);
        }
    });
}

jQuery(document).ready(function() {
    jQuery.validator.addMethod("specialChars", function(value, element) {
        var regex = new RegExp("^[a-zA-Z0-9.@_-]+$");
        var key = value;

        if (!regex.test(key)) {
            return false;
        }
        return true;
    }, "please enter a valid value for the field.");
//    refreshCaptha();

    /*Contact Us Form Validation Start */

    jQuery.validator.addMethod('chk_username_field', function(value, element, param) {
        if (value.match('^[a-zA-Z0-9-_.]{5,20}$')) {
            return true;
        } else {
            return false;
        }

    }, "");

    jQuery.validator.addMethod('chk_name', function(value, element, param) {
        if (value.match('^[a-zA-Z]{1,20}$')) {
            return true;
        } else {
            return false;
        }

    }, "");

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "Please enter valid name");

    jQuery.validator.addMethod("noSpace", function(value, element) {
        return value.indexOf(" ") < 0 && value != "";
    }, "Please enter valid characters");


    jQuery.validator.addMethod('chk_full_name', function(value, element, param) {
        if (value.match("^[a-zA-Z]([-']?[a-zA-Z]+)*( [a-zA-Z]([-']?[a-zA-Z]+)*)+$")) {
            return true;
        } else {
            return false;
        }

    }, "");

    jQuery.validator.addMethod("password_strenth", function(value, element) {
        return isPasswordStrong(value, element);
    }, "Password must be combination of at least 1 number, 1 special character and 1 upper case letter with minimum 8 characters");


    /* landing Form Validation Start */
    jQuery("#first_step_registration").validate({
        errorElement: 'div',
        debug: true,
        errorClass: 'text-danger',
        rules: {
            mobile_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
                remote: {
                    url: javascript_site_path + 'chk-number-duplicate',
                    method: 'post'
                },
            },
            password: {
                required: true,
                minlength: 8,
                password_strenth: true
            },
//            country_code: {
//                required: true
//            },
            confirm_password: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
            otp_number: {
                required: true,
                digits: true,
                minlength: 4,
                maxlength: 4,
                remote: {
                    url: javascript_site_path + 'reset-valid-otp',
                    method: 'post',
                    data: {
                        mobile_number: function() {
                            return $('#mobile_number').val();
                        }
                    }
                },
            },
            accept_terms_conditions: {
                required: true
            }
        },
        messages: {
            country_code: {
                required: "Please select country code."
            },
            mobile_number: {
                required: "Please enter your mobile number.",
                minlength: jQuery.format("Please enter {0} digits number."),
                maxlength: jQuery.format("Please enter only {0} digits number."),
                remote: "The number you have entered is already exists."
            },
            password: {
                required: "Please enter password.",
                minlength: jQuery.format("Please enter at least {0} characters.")
            },
            confirm_password: {
                required: "Please confirm your password.",
                minlength: jQuery.format("Please enter at least {0} characters."),
                equalTo: "Please enter the confirm password same as above."
            },
            otp_number: {
                required: "Please enter OTP.",
                minlength: jQuery.format("Please enter {0} digits OTP."),
                maxlength: jQuery.format("Please enter only {0} digits OTP."),
                remote: "Please enter valid OTP."
            },
            accept_terms_conditions: {
                required: "Please accept the terms and conditions."
            },
        }, submitHandler: function(form) {
            var otp_number = $('#otp_number').val();
            if (otp_number != '') {
                jQuery("#btn_submit").hide();
                jQuery("#loader").show();
                secondStepOTP();
            } else {
                firstStepRegistration()
            }
        }
    });

    /* landing Form Validation Start */
    jQuery("#second_step_registration").validate({
        errorElement: 'div',
        debug: true,
        errorClass: 'text-danger',
        rules: {
            otp_number: {
                required: true,
                digits: true,
                minlength: 4,
                maxlength: 4
            },
        },
        messages: {
            otp_number: {
                required: "Please enter OTP.",
                minlength: jQuery.format("Please enter {0} digits OTP."),
                maxlength: jQuery.format("Please enter only {0} digits OTP.")
            },
        }, submitHandler: function(form) {
            jQuery("#btn_otp_submit").hide();
            jQuery("#loader").show();
            form.submit();
        }
    });



    jQuery("#frm_complete_profile").validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'div',
        rules: {
            first_name: {
                required: true,
                lettersonly: true
            },
//            middle_name: {
//                required: true,
//                lettersonly: true
//            },
            last_name: {
                required: true,
                lettersonly: true
            },
            user_email: {
                required: true,
                email: true,
                specialChars: true,
                remote: {
                    url: javascript_site_path + 'chk-email-duplicate',
                    method: 'post',
                    data: {
                        user_email_back: function() {
                            return $('#user_email_back').val();
                        }
                    }
                }
            },
            day: {
                required: true
            },
            month: {
                required: true
            },
            year: {
                required: true
            }
        },
        messages: {
            first_name: {
                required: "Please enter the first name."
            },
            middle_name: {
                required: "Please enter the middle name."
            },
            last_name: {
                required: "Please enter the last name."
            },
            user_email: {
                required: "Please enter an email address.",
                email: "Please enter a valid email address.",
                specialChars: "Please enter a valid email address.",
                remote: "This email address is already registered with site."
            },
            day: {
                required: "Please select date."
            },
            month: {
                required: "Please select month"
            },
            year: {
                required: "Please select year."
            }
        },
        submitHandler: function(form) {
            jQuery("#btn_submit_cp").hide();
            jQuery("#loader_cp").show();
            thirdStepCompleteProfile()
        }
    });

    jQuery("#frm_edit_profile").validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'div',
        rules: {
            first_name: {
                required: true,
                lettersonly: true
            },
//            middle_name: {
//                required: true,
//                lettersonly: true
//            },
            last_name: {
                required: true,
                lettersonly: true
            },
            user_email: {
                required: true,
                email: true,
                specialChars: true,
                remote: {
                    url: javascript_site_path + 'chk-edit-email-duplicate',
                    method: 'post',
                    data: {
                        user_email_old: function() {
                            return $('#user_email_old').val();
                        }
                    }
                }
            },
            day: {
                required: true
            },
            month: {
                required: true
            },
            year: {
                required: true
            },
            new_user_password: {
                required: true,
                minlength: 8,
                password_strenth: true
            },
            confirm_password: {
                required: true,
                equalTo: '#user_password'
            },
        },
        messages: {
            first_name: {
                required: "Please enter the first name."
            },
            middle_name: {
                required: "Please enter the middle name."
            },
            last_name: {
                required: "Please enter the last name."
            },
            user_email: {
                required: "Please enter an email address.",
                specialChars: "Please enter a valid email address.",
                email: "Please enter a valid email address.",
                remote: "This email address is already registered with site."
            },
            day: {
                required: "Please select date."
            },
            month: {
                required: "Please select month"
            },
            year: {
                required: "Please select year."
            },
            new_user_password: {
                required: "Please enter password.",
                minlength: "Please enter atleast 8 characters."
            },
            confirm_password: {
                required: "Please enter the confirm password.",
                equalTo: "Password and confirm password do not match."
            },
        },
        submitHandler: function(form) {
            jQuery("#btn_submit").hide();
            jQuery("#loader").show();
            form.submit();
        }
    });

    jQuery("#frm_current_address").validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'div',
        rules: {
            current_add_country: {
                required: true
            },
            current_add_state: {
                required: true
            },
            current_add_city: {
                required: true
            },
            current_add_zipcode: {
                required: true,
                minlength: 6,
                maxlength: 6,
                remote: {
                    url: javascript_site_path + 'check-current-zipcode',
                    method: 'post',
                    data: {
                        current_add_city: function() {
                            return $("#current_add_city option:selected").text();
                        },
                        current_add_state: function() {
                            return $("#current_add_state option:selected").text();
                        },
                        current_add_country: function() {
                            return $("#current_add_country option:selected").text();
                        }
                    }
                }
            },
            current_add_first: {
                required: true
            },
            date_from: {
                required: true
            },
            date_to: {
                required: true
            }
        },
        messages: {
            current_add_country: {
                required: "Please select your country."
            },
            current_add_state: {
                required: "Please select your state."
            },
            current_add_city: {
                required: "Please select your city."
            },
            current_add_zipcode: {
                required: "Please enter your zipcode.",
                minlength: "Please enter 6 digit zipcode number.",
                maxlength: "Please enter 6 digit zipcode number.",
                remote: "Zipcode is not exists with selected state and city.Please enter valid zipcode."
            },
            current_add_first: {
                required: "Please enter your address1."
            },
            date_from: {
                required: "Please select from date."
            },
            date_to: {
                required: "Please select to date."
            }
        }
    });

    jQuery("#frm_permanent_address").validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'div',
        rules: {
            permanant_add_country: {
                required: true
            },
            permanant_add_state: {
                required: true
            },
            permanant_add_city: {
                required: true
            },
            permanant_add_zipcode: {
                required: true,
                minlength: 6,
                maxlength: 6,
                remote: {
                    url: javascript_site_path + 'check-permanant-zipcode',
                    method: 'post',
                    data: {
                        permanant_add_city: function() {
                            return $("#permanant_add_city option:selected").text();
                        },
                        permanant_add_state: function() {
                            return $("#permanant_add_state option:selected").text();
                        },
                        permanant_add_country: function() {
                            return $("#permanant_add_country option:selected").text();
                        }
                    }
                }
            },
            permanant_add_first: {
                required: true
            }
        },
        messages: {
            permanant_add_country: {
                required: "Please select your country."
            },
            permanant_add_state: {
                required: "Please select your state."
            },
            permanant_add_city: {
                required: "Please select your city."
            },
            permanant_add_zipcode: {
                required: "Please enter your zipcode.",
                minlength: "Please enter 6 digit zipcode number.",
                maxlength: "Please enter 6 digit zipcode number.",
                remote: "Zipcode is not exists with selected state and city.Please enter valid zipcode."
            },
            permanant_add_first: {
                required: "Please enter your address1."
            }
        }
    });

    jQuery("#frm_office_address").validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'div',
        rules: {
            office_add_country: {
                required: true
            },
            office_add_state: {
                required: true
            },
            office_add_city: {
                required: true
            },
            office_add_zipcode: {
                required: true,
                minlength: 6,
                maxlength: 6,
                remote: {
                    url: javascript_site_path + 'check-office-zipcode',
                    method: 'post',
                    data: {
                        office_add_city: function() {
                            return $("#office_add_city option:selected").text();
                        },
                        office_add_state: function() {
                            return $("#office_add_state option:selected").text();
                        },
                        office_add_country: function() {
                            return $("#office_add_country option:selected").text();
                        }
                    }
                }
            },
            office_add_first: {
                required: true
            }
        },
        messages: {
            office_add_country: {
                required: "Please select your country."
            },
            office_add_state: {
                required: "Please select your state."
            },
            office_add_city: {
                required: "Please select your city."
            },
            office_add_zipcode: {
                required: "Please enter your zipcode.",
                minlength: "Please enter 6 digit zipcode number.",
                maxlength: "Please enter 6 digit zipcode number.",
                remote: "Zipcode is not exists with selected state and city.Please enter valid zipcode."
            },
            office_add_first: {
                required: "Please enter your address1."
            }
        }
    });

    /**contact us form validation**/
    jQuery("#form_contact_us").validate({
        errorElement: 'div',
        errorClass: 'text-danger',
        rules: {
            first_name: {
                required: true,
                chk_full_name: true
            },
            email: {
                required: true,
                email: true,
                specialChars: true,
            },
            subject: {
                required: true
            },
            message: {
                required: true
            },
            input_captcha_value: {
                required: true,
                remote: {
                    url: javascript_site_path + 'check-captcha',
                    method: 'post'
                }
            }

        },
        messages: {
            first_name: {
                required: 'Please enter your name.',
                chk_full_name: 'Please enter your full name.'
            },
            email: {
                required: 'Please enter your email address.',
                specialChars: 'Please enter valid email address.',
                email: 'Please enter a valid email address.',
                remote: "Zipcode is not exists with selected state and city.Please enter valid zipcode."
            },
            subject: {
                required: "Please enter a subject."
            },
            message: {
                required: "Please enter message."
            },
            input_captcha_value: {
                required: "Please enter the security code.",
                remote: "Please enter valid security code."
            }
        }

    });

    /**Login Static form validation*/
    jQuery("#frm_login").validate({
        errorClass: 'text-danger',
        errorElement: 'div',
        rules: {
            mobile_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            password: {
                required: true,
                minlength: 8
            }
        },
        messages: {
            mobile_number: {
                required: "Please enter your mobile number.",
                minlength: jQuery.format("Please enter {0} digits number."),
                maxlength: jQuery.format("Please enter only {0} digits number."),
                remote: "Zipcode is not exists with selected state and city.Please enter valid zipcode."
            },
            password: {
                required: "Please enter password.",
                minlength: jQuery.format("Please enter atleast {0} characters.")
            }
        },
        submitHandler: function(form) {
            jQuery("#btn_submit").hide();
            jQuery("#loader").show();
            form.submit();
        }

    });

    /**End here */
    /*forgot password validation form*/
    jQuery("#frm_forgot_password").validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'div',
        rules: {
            mobile_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
                remote: {
                    url: javascript_site_path + 'chk-number-exists',
                    method: 'post'
                },
            },
        },
        messages: {
            mobile_number: {
                required: "Please enter your mobile number.",
                minlength: jQuery.format("Please enter {0} digits number."),
                maxlength: jQuery.format("Please enter only {0} digits number."),
                remote: "Zipcode is not exists with selected state and city.Please enter valid zipcode."
            },
        },
        submitHandler: function(form) {
            var otp_number = $('#otp_number').val();
            if (otp_number != '') {
                jQuery("#btn_submit").hide();
                jQuery("#loader").show();
                form.submit();
            } else {
                forgotPassword();
            }
        }
    });


    /*reset password form validation */
    jQuery("#frm_rest_password").validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'div',
        rules: {
            security_code: {
                required: true,
                remote: {
                    url: javascript_site_path + 'check_for_valid_code_reset',
                    method: 'post',
                    cache: false,
                    sync: false,
                    data: {action: 'check_for_valid_code'}
                }
            },
            new_password: {
                required: true,
                minlength: 8,
                password_strenth: true
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            }
        },
        messages: {
            security_code: {
                required: 'Please enter valid security code.',
                remote: 'Securty code does not seems to be valid. please try again.'
            },
            new_password: {
                required: 'Please enter new password.',
                minlength: jQuery.format('please enter atleast {0} character.')

            },
            confirm_password: {
                required: 'Please confirm your password.',
                minlength: jQuery.format('please enter atleast {0} character.'),
                equalTo: 'Please enter the confirm password same as above.'
            }
        },
        submitHandler: function(form) {
            jQuery("#btn_submit").hide();
            jQuery("#loader").show();
            form.submit();
        }

    });

    /**End here*/

    /* change email Form Validation Start */
    jQuery("#frm_change_user_email").validate({
        errorElement: 'div',
        rules: {
            user_email: {
                required: true,
                email: true,
                remote: {
                    url: javascript_site_path + "chk-edit-email-duplicate",
                    type: "post",
                    data: {
                        action: "check_email"
                    }
                }
            }

        },
        messages: {
            user_email: {
                required: 'Please enter your email address.',
                email: 'Please enter a valid email address.',
                remote: 'Email already exists.'
            }
        }
    });


    /* Testimonial page validation start */
    jQuery("#frmTestimonials").validate({
        errorElement: 'div',
        errorClass: 'text-danger',
        rules: {
            inputTestimonial: {
                required: true,
                minlength: 20
            },
            inputName: {
                required: true
            }
        },
        messages: {
            inputTestimonial: {
                required: "Please enter testimonial.",
                minlength: "Please enter at least 20 characters."
            },
            inputName: {
                required: "Please enter your name."
            }
        },
        // set this class to error-labels to indicate valid fields
        success: function(label) {
            label.hide();
        }
    });
    /* Testimonial page validation end */
    /* Account setting page validation start */
    jQuery("#frm_edit_account_setting").validate({
        debug: true,
        errorClass: 'text-danger',
        rules: {
            security_code: {
                required: true,
                remote: {
                    url: javascript_site_path + 'check_for_valid_code',
                    method: 'post',
                    cache: false,
                    sync: false,
                    data: {action: 'check_for_valid_code'}
                }
            },
            new_user_password: {
                required: true,
                minlength: 8,
                password_strenth: true
            },
            cnf_user_password: {
                required: true,
                minlength: 8,
                equalTo: "#new_user_password"
            }
        },
        messages: {
            security_code: {
                required: "Please enter security code. you can get it from 'Get the security code from here' link",
                remote: "Security code does not seems to be valid. please try again!!"
            },
            new_user_password: {
                required: "Please enter new password.",
                minlength: jQuery.format("Please enter at least {0} characters.")
            },
            cnf_user_password: {
                required: "Please confirm password.",
                minlength: jQuery.format("Please enter at least {0} characters."),
                equalTo: "These passwords don't match. Try again."
            }
        }, submitHandler: function(form) {
            jQuery("#btn_account_setting").hide();
            jQuery("#btn_loader").show();
            form.submit();
        }
    });
    /* Account setting page validation end */


    jQuery("#frm_valid_password").validate({
        errorElement: 'div',
        errorClass: 'text-danger',
        rules: {
            password: {
                required: true
            }
        },
        messages: {
            password: {
                required: 'Please enter your password.'
            }
        }
    });

});
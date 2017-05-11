// JavaScript Document
$(document).ready(function(e) {
    jQuery.validator.addMethod("specialChars", function(value, element)
    {
        var regex = new RegExp("^[a-zA-Z0-9.@_-]+$");
        var key = value;

        if (!regex.test(key)) {
            return false;
        }
        return true;
    }, "please enter a valid value for the field.");

    $("#frm_user_details").validate({
        errorElement: "div",
        errorPlacement: function(label, element) {
            if (element[0].name == "admin_privileges[]")
            {
                label.insertAfter("#pre_div");
            } else
            {
                label.insertAfter(element);
            }
        },
        rules: {
            first_name: {
                required: true,
                notNumber: true

            },
            middle_name: {
                notNumber: true
            },
            last_name: {
                required: true,
                notNumber: true
            },
            user_name: {
                required: true,
                chk_username_field: true,
                remote: {
                    url: jQuery("#base_url").val() + "backend/admin/check-admin-username",
                    type: "post",
                    data: {
                        type: "edit",
                        old_username: jQuery('#old_username').val()
                    }
                }
            },
            user_email: {
                required: true,
                email: true,
                specialChars: true,
                remote: {
                    url: jQuery("#base_url").val() + "backend/admin/check-admin-email",
                    type: "post",
                    data: {
                        type: "edit",
                        old_email: jQuery('#old_email').val()
                    }
                }
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
                required: "Please enter first name.",
                notNumber: "Please enter valid name."
            },
            middle_name: {
                notNumber: "Please enter valid name."
            },
            last_name: {
                required: "Please enter last name.",
                notNumber: "Please enter valid name."
            },
            user_name: {
                required: "Please enter username.",
                chk_username_field: "Please enter a valid username. It must contain 5-20 characters. Characters other than <b> A-Z, a-z, 0-9, _ , . , - </b>  are not allowed.",
                remote: "Username already exists."
            },
            user_email: {
                required: "Please enter an email address.",
                specialChars: "Please enter valid email address.",
                email: "Please enter a valid email address.",
                remote: "Email address already exists."
            },
            new_user_password: {
                required: "Please enter password.",
                minlength: "Please enter atleast 8 characters."
            },
            confirm_password: {
                required: "Please enter the confirm password.",
                equalTo: "Password and confirm password do not match."
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
            $("#btnSubmit").hide();
            $('#loding_image').show();
            form.submit();
        }
    });
    jQuery.validator.addMethod("notNumber", function(value, element, param) {
        var reg = /[0-9]/;
        if (reg.test(value)) {
            return false;
        } else {
            return true;
        }
    }, "Number is not permitted");
    jQuery.validator.addMethod('chk_username_field', function(value, element, param) {
        if (value.match('^[0-9a-zA-Z-._-]{5,20}$')) {
            return true;
        } else {
            return false;
        }

    }, "");

    jQuery.validator.addMethod("password_strenth", function(value, element) {
        return isPasswordStrong(value, element);
    }, "Password must be strong");

    $("#check_box").css({display: "block", opacity: "0", height: "0", width: "0", "float": "right"});

    jQuery(".hide-show-pass-div").on("click", function() {
        if (jQuery(".hide-show-pass-div").is(":checked"))
        {
            jQuery('#change_password_div').css('display', 'block');
        }
        else
        {
            jQuery('#change_password_div').css('display', 'none');
        }
    });

});
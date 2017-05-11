jQuery(document).ready(function(e) {
    var base_url = jQuery("#base_url").val();

    // Call captcha function to load the security code
    refreshCaptha();

    jQuery("#form_contact_us").validate({
        errorElement: 'div',
        rules: {
            first_name: {required: true},
            last_name: {required: true},
            email: {required: true, email: true},
            subject: {required: true},
            message: {required: true},
            input_captcha_value: {
                required: true,
                remote: {
                    url: base_url + 'check-captcha',
                    method: 'post'
                }
            }


        },
        messages: {
            first_name: {required: 'Please enter first name.'},
            last_name: {required: 'Please enter last name.'},
            email: {required: 'Please enter email.'},
            subject: {required: 'Please enter subject.'},
            message: {required: 'Please enter message.'},
            input_captcha_value: {
                required: "Please enter security code.",
                remote: "Please enter valid security code."
            }
        }
    });
});

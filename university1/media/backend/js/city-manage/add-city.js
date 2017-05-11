// JavaScript Document
$(document).ready(function(e) {
    $("#frm_add_city").validate({
        errorElement: "div",
        rules: {
            city_name: {
                required: true,
                remote: {
                    url: jQuery("#base_url").val() + "backend/check-city-name",
                    type: "post",
                },
                lettersonly: true
            }

        },
        messages: {
            city_name: {
                required: "Please enter city name.",
                remote: "City with the same name already exists.",
                lettersonly: "Please enter valid city name."
            }

        }
    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[A-Z]+$/i.test(value);
    }, "");


});
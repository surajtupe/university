// JavaScript Document
$(document).ready(function(e) {
    $("#frm_edit_city").validate({
        errorElement: "div",
        rules: {
            city_name: {
                required: true,
                lettersonly: true,
                remote: {
                    url: jQuery("#base_url").val() + "backend/check-city-name",
                    type: "post",
                    data: {
                        type: "edit",
                        old_city_name: jQuery('#old_city_name').val()
                    }
                }
            }


        },
        messages: {
            city_name: {
                required: "Please enter city.",
                lettersonly: "Please enter valid city name.",
                remote: "City with the same name already exists."
            }
        }
    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[A-Z]+$/i.test(value);
    }, "");
});
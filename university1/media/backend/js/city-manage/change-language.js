
//// JavaScript Document
$(document).ready(function(e) {
    $("#frm_change_lang").validate({
        errorElement: "div",
        rules: {
            lang_id: {
                required: true

            },
            city_name: {
                required: true,
                lettersonly: true,
//                remote: {
//                    url: jQuery("#base_url").val() + "backend/check-city-name",
//                    type: "post",
//                    data: {
//                        type: "edit",
//                        old_city_name: jQuery('#old_city_name').val()
//                    }
//                }
            }


        },
        messages: {
            lang_id: {
                required: "Please select language."


            },
            city_name: {
                required: "Please enter city name.",
                lettersonly: "Please enter valid city name.",
                remote: "City already exists."
            }


        }
    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[A-Z]+$/i.test(value);
    }, "");


});
// JavaScript Document
$(document).ready(function(e) {
    $("#frm_add_category").validate({
        errorElement: "div",
        rules: {
            category_name: {
                required: true,
                remote: {
                    url: jQuery("#base_url").val() + "backend/category/check-category-name",
                    type: "post"
                }
            },
            parent_category: {
                required: true
            }

        },
        messages: {
            category_name: {
                required: "Please enter category name.",
                remote: "Category with the same name already exists."
            },
            parent_category: {
                required: "Please select category."
            }

        },
        submitHandler: function(form) {
            $("#btn_submit").hide();
            $("#loding_image").show();
            form.submit();
        }
    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[A-Z]+$/i.test(value);
    }, "");



});
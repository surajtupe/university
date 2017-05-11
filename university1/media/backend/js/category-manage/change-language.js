
//// JavaScript Document
$(document).ready(function(e) {
    $("#frm_change_lang").validate({
        errorElement: "div",
        rules: {
            lang_id: {
                required: true

            },
            category_name: {
                required: true,
                remote: {
                    url: jQuery("#base_url").val() + "backend/category/check-category-name",
                    type: "post",
                    data: {
                        type: "edit",
                        old_category_name: jQuery('#old_category_name').val()
                    }
                    
                }
            },
            category_description: {
                required: true,
                maxlength: 200
            }


        },
        messages: {
            lang_id: {
                required: "Please select language.",
                remote: "Category already exists with this language."

            },
            category_name: {
                required: "Please enter category name.",
                remote: "Category name already exists."
            },
            category_description: {
                required: "Please enter description.",
                maxlength: "description should not exceed 200 characters."
            }

        }
    });


jQuery.validator.addMethod("lettersonly", function(value, element) {
    	return this.optional(element) || /^[A-Z]+$/i.test(value);
	}, ""); 

});
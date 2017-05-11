// JavaScript Document
$(document).ready(function(e) {     
     jQuery.validator.addMethod("specialChars", function( value, element )  
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
		rules: {
			first_name:{
				required:true,
                                notNumber:true
			},
			last_name:{
				required:true,
                                notNumber:true
			},
			user_name:{
				required:true,
				chk_username_field:true,
				remote:{
					url: jQuery("#base_url").val()+"backend/revision-2-at/admin/check-admin-username",
					type: "post"
				}
			},
			user_email:{
				required:true,
				email:true,
                                 specialChars:true,
				remote:{
					url: jQuery("#base_url").val()+"backend/revision-2-at/admin/check-admin-email",	
					type: "post"
				}
			},
			user_password:{
				 required: true,
				 minlength: 8,
				 password_strenth: true
			},
			confirm_password:{
				required:true,
				equalTo:'#user_password'	
			},
			role_id:{
				required:true	
			}
		},
		messages:{
			first_name:{
				required:"Please enter first name.",
                                notNumber:"Please enter valid name."
			},
			last_name:{
				required:"Please enter last name.",
                                notNumber:"Please enter valid name."
			},
			contact_no:{
				number:"Please enter valid contact number.",
				minlength:"Please enter 10 digit conact number"
			},
			user_name:{
				required:"Please enter username.",
				chk_username_field:"Please enter a valid username. It must contain 5-20 characters. Characters other than <b> A-Z, a-z, 0-9, _ , . , - </b>  are not allowed.",
				remote:"Username already exists."
			},
			user_email:{
				required:"Please enter an email address.",                                
                                specialChars:"Please enter valid email address.",
				email:"Please enter a valid email address.",
				remote:"Email address already exists."
			},
			user_password:{
				required: "Please enter password.",
                minlength: "Please enter atleast 8 characters."
			},
			confirm_password:{
				required:"Please enter the confirm password.",
				equalTo:"Password and confirm password does not match."
			},
			role_id:{
				required:"Please select admin user role."
			}
		},
		submitHandler: function (form) {
         	 $("#btnSubmit").hide();
            $('#loding_image').show();
            form.submit();
        }
	});
	jQuery.validator.addMethod("notNumber", function(value, element, param) {
                       var reg = /[0-9]/;
                       if(reg.test(value)){
                             return false;
                       }else{
                               return true;
                       }
                }, "Number is not permitted");
	jQuery.validator.addMethod('chk_username_field', function(value, element, param) {
		 if ( value.match('^[0-9a-zA-Z-._-]{5,20}$') ) {
			return true;
		} else {
			 return false;
		}
		
	},"");
	
	jQuery.validator.addMethod("password_strenth", function(value, element) {
		return isPasswordStrong(value, element);
	}, "Password must be strong");
	
	$("#check_box").css({display:"block",opacity:"0",height:"0",width:"0","float":"right"});
});
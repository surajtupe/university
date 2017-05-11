// JavaScript Document
var inputP;
	jQuery(document).ready(function(e) {
		jQuery("#frm_send_testimonial").validate(
		{
			 errorElement:'div',
			 rules: {
				input_testimonial:{
					required: true,
					minlength: 20,
					maxlength: 100
				},
				input_name:{
					required: true,
					maxlength: 20
				}
			},
			messages: {
				input_testimonial:{
					required: "Please enter testimonial.",
					minlength: "Please enter at least 20 characters."
				},
				input_name:{
					required: "Please enter name."
				}
			},
			 submitHandler: function(form) {
				var arr_testimonial_data=new Object();
				arr_testimonial_data.input_testimonial=jQuery("#input_testimonial").val();
				arr_testimonial_data.input_name=jQuery("#input_name").val();
				saveTestimonialInfo(arr_testimonial_data);
			}	
		});
		
        jQuery("#btn_post_testimonial").bind("click",function(){jQuery('#frm_send_testimonial').submit();});
    });
	
	function saveTestimonialInfo(arr_testimonial_data)
	{
		jQuery.post(base_url+"send-testimonial-action",arr_testimonial_data,handleCommentPosted,'json');
	}
	
	function handleCommentPosted(msg)
	{
		if(msg.error=="1")
		{
			alert("Error while processing your request.\n\nPlease try again!");
		}
		else
		{
			alert(msg.msg);
			location.href=location.href;
		}
	}
	
	
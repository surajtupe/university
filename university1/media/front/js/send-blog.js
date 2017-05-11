// JavaScript Document
var inputP;
	jQuery(document).ready(function(e) {
		jQuery("#frm_send_blog").validate(
		{
			errorElement:"div",
			errorClass:"error",
			debug: true,
			invalidHandler:function(){
				jQuery("#preloader").hide();
			},
			rules: {
			inputBlogTitle:{
				required: true,
				minlength: 3,
				maxlength: 100
			},
			inputBlogShortDescription:{
				required:true,
				maxlength: 1000	
			},
			inputBlogPageTitle:{
			required:true
			}			
			},
			messages: {
			
				inputBlogTitle:{
					required: "Please enter blog title.",
					minlength: "Please enter at least 3 characters."
				},
				inputBlogShortDescription:{
					required:"Please enter blog content."
				},
				inputBlogPageTitle:{
					required:"Please enter blog page title."	
				}
			},
			 submitHandler: function(form) {
				var arr_blog_data=new Object();
				arr_blog_data.inputBlogTitle=jQuery("#inputBlogTitle").val();
				arr_blog_data.inputBlogShortDescription=jQuery("#inputBlogShortDescription").val();
				arr_blog_data.inputBlogDescription=jQuery("#inputBlogDescription").val();
				arr_blog_data.inputBlogPageTitle=jQuery("#inputBlogPageTitle").val();
				arr_blog_data.inputParentCategory=jQuery("#inputParentCategory").val();
				arr_blog_data.inputBlogTags=jQuery("#inputBlogTags").val();
				arr_blog_data.inputBlogKeywords=jQuery("#inputBlogKeywords").val();
				
				saveBlogInfo(arr_blog_data);
			}	
		});
		jQuery("#inputBlogDescription").cleditor({width:680, // width not including margins, borders or padding
        height:300});
		jQuery.cleditor.buttons.image.uploadUrl = SITE_PATH+'blog/upload-cleeditor-image';
		
        jQuery("#btnSendBlog").bind("click",function(){jQuery('#frm_send_blog').submit();});
    });
	
	function saveBlogInfo(objParams)
	{
		jQuery.post(SITE_PATH+"blog/send-blog-action",objParams,handleBlogPosted,'json');
	}
	
	function handleBlogPosted(msg)
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
	
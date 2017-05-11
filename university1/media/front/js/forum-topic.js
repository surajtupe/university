// JavaScript Document
var inputP;

	jQuery(document).ready(function(e) {
		
		jQuery("#frm_comments").validate(
		{
				errorElement:"div",
				errorClass:"text-danger",
				debug: true,
				invalidHandler:function(){
					jQuery("#preloader").hide();
				},
				rules:
				{
					input_comment:
					{
						required:true,
						minlength:25
					}
				},
				messages:
				{
					input_comment:
					{
						required:"Please enter your comment",
						minlength:"Please enter {0} characters"
					}
				},
				 submitHandler: function(form) {
					var arrCommentData=new Object();
					arrCommentData.msg_comment=jQuery("#input_comment").val();
					arrCommentData.p=inputP;
					saveCommentInfo(arrCommentData);
				}	
		});
		
        jQuery("#btnPostComment").bind("click",function(){
			jQuery('#frm_comments').submit();
		});
		
    });
	
	function saveCommentInfo(objParams)
	{
		jQuery.post(SITE_PATH+"forum/add-comment",objParams,handleCommentPosted,'json');
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
	
	function setInputP(strInputP)
	{
		inputP	= strInputP;
	}
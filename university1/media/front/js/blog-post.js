// JavaScript Document
var inputP;

jQuery(document).ready(function(e) {
		
    jQuery("#frmComments").validate(
    {
        errorElement:"div",
        errorClass:"text-danger",
        debug: true,
        invalidHandler:function(){
            jQuery("#preloader").hide();
        },
        rules:
        {
            posted_by:{
                required:true,
            },
            inputComment:
            {
                required:true,
                minlength:25
            }
        },
        messages:
        {
             posted_by:{
                required:"Please enter your name.",
            },
            inputComment:
            {
                required:"Please enter your comment.",
                minlength:"Please enter {0} characters."
            }
        },
        submitHandler: function(form) {
            jQuery("#btn_post_comment").hide();
            jQuery("#btn_loader").show();
            var arrCommentData=new Object();
            arrCommentData.msg_comment=jQuery("#inputComment").val();
            arrCommentData.posted_by=jQuery("#posted_by").val();
            arrCommentData.user_id=jQuery("#user_id").val();
            arrCommentData.p=inputP;
            saveCommentInfo(arrCommentData);
        }	
    });
		
    jQuery("#btnPostComment").bind("click",function(){
															
        jQuery('#frmComments').submit();
															
    });
});
	
function saveCommentInfo(objParams)
{
    jQuery.post(SITE_PATH+"blog/add-comment",objParams,handleCommentPosted,'json');
}
	
function handleCommentPosted(msg)
{
		
    if(msg.error=="1")
    {
        alert("Error while processing your request.\n\nPlease try again!");
    }
    else
    {
        location.href=location.href;
    }
}
	
function setInputP(strInputP)
{
    inputP = strInputP;
}
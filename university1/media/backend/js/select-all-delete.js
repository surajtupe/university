// JavaScript Document

jQuery(function(){    
    // when we click on select all button
      jQuery(".select_all_button_class").next().on("click",function () {
     
        if (jQuery('.select_all_button_class').parent().hasClass('checked')) {
			
            jQuery('.case').each(function(){
		 $(this).parent().removeAttr("class");
                 $(this).prop("checked",true);
                $(this).parent().attr("class","icheckbox_minimal checked");
           
            });
        }
        else
        {
			
            jQuery('.case').each(function(){
                  $(this).parent().removeAttr("class");
                $(this).removeAttr("checked");
                $(this).parent().attr("class","icheckbox_minimal");
             
            });
        }
});
 jQuery(".case").next().on("click",function () 
 {
            if($(this).parent().hasClass("icheckbox_minimal hover checked"))
            {
                $(this).parent().attr("class",'icheckbox_minimal checked');
            }
            var a=1;
            jQuery('.case').each(function(index,value)
            {
               
                if (!($(this).parent().attr("class")=='icheckbox_minimal checked'))
                {
               
                    a=0;  // if one of the is listed chekcbox is not  cheacked
                    //jQuery('.case').parent().removeAttr('class');
                }
          });
          
          if(a==0)
            {
                  jQuery(".select_all_button_class").parent().prop("class","icheckbox_minimal");
//                    jQuery('.case').parent().removeAttr('class');
//                    jQuery('.case').parent().attr('class','icheckbox_minimal checked');
            }else{
                jQuery(".select_all_button_class").parent().prop("class","icheckbox_minimal checked");
            }
  });
});
	/*$(".row-fluid select").change(function(){	
			jQuery("#check_all").attr("checked",false);
			jQuery("#check_all").parent('span').removeClass('checked');
			jQuery('.case').each(function(){
                $(this).attr("checked",false);
                $(this).parent('span').removeClass('checked');
            });
	});
	
	/*jQuery(".row-fluid ul").click(function(){
						alert($(this).element().clikced
			
			/*jQuery("#check_all").attr("checked",false);
			
			jQuery("#check_all").parent('span').removeClass('checked');
			jQuery('.case').each(function(){
                $(this).attr("checked",false);
                $(this).parent('span').removeClass('checked');
            });
			
	});*/
	


function deleteConfirm()
{
    var del_num=0;
	
    /* jQuery('.checked').each(function(){
        del_num=1; 
    });
	*/
	
	jQuery('.case').each(function(){
            if (jQuery('.case').is(":checked")) {
				del_num=1;
            }
    });
	
	if(!del_num){
        alert("Please select atleast one record to delete");
		return false;
    }
	else{
        var status=confirm("Do you really want to delete?");
        if(status)
        {
          return true;
        }
        else
        {
            return false;
        }
    }
}
function deleteconfirm()
{
    var del_num=0;
	
    /* jQuery('.checked').each(function(){
        del_num=1; 
    });
	*/
	
	jQuery('.case').each(function(){
            if (this.checked) {
				del_num=1;
            }
    });
	
	if(!del_num){
        alert("Please select atleast one record to delete");
		return false;
    }
	else{
        var status=confirm("Do you really want to delete?");
        if(status)
        {
            /* jQuery('#frm_admin_users').submit();            */
			return true;
        }
        else
        {
            return false;
        }
    }
}
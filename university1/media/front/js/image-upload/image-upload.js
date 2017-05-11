$(function()
{
		/*validation for the form */
		jQuery("#frm_image_upload").validate({
            debug: true,
            errorClass: 'text-danger',
            rules: {
					file_upload:{
						required:true	
					}
			},
            messages:{
				
			}, 
			submitHandler: function(form) {
                form.submit();
            }
        });	
	
	// Variable to store your files
	var files;
	// Add events
	$('.single_file_upload').on('change', uploadFileSingle);
	// Catch the form submit and upload the files
	function uploadFileSingle(event)
	{
		files = event.target.files;	
		event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening
        // We can add loader image or spinner here
		
        // Create a formdata object and add the files
		var data = new FormData();
		$.each(files, function(key, value)
		{
			data.append(key, value);
		});
		 data.append('thumb_width', jQuery('#thumb_width').val());
		 data.append('thumb_height', jQuery('#thumb_height').val());
		//data["thumb_width"]=jQuery('#thumb_width').val();
		//data["thumb_height"]=jQuery('#thumb_height').val();
        
        $.ajax({
            url: javascript_site_path+'upload-image',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR)
            {
            	if(typeof data.error === 'undefined')
            	{
					// Success so call function to process the form	
					$("#profile_picture").attr("src",data.files[0]);
					$(".btn_remove_image").css("display","inline-block");
					// Stop loading spinner or loader image here 
            	}
            	else
            	{
            		// Handle errors here
            		console.log('ERRORS: ' + data.error);
					alert(data.error);
            	}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	// Handle errors here
            	console.log('ERRORS: ' + textStatus);
				alert('ERRORS: ' + textStatus);
            	// STOP LOADING SPINNER
            }
        });
    }
});

/* ajax function to remove the uploaded or database image record */
function fnToRemoveImage()
{
	if(confirm("Are you really want to remove this image."))
	{
	 $.ajax({
		url: javascript_site_path+'remove-image',
		type: 'POST',
		data: {uploaded_image_name:jQuery("#profile_picture").attr('src'),rel_id:jQuery("#rel_id").val()},
		dataType: 'json',
		success: function(data)
		{
			$("#profile_picture").attr("src",javascript_site_path+"media/front/img/male.png");	
			$("#file_upload").val('');	
			$(".btn_remove_image").css("display","none");
		}
	});
	}
}
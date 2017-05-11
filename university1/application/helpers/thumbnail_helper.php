<?php
include('application/libraries/PHPImageWorkshop/ImageWorkshop.php');
include('application/libraries/PHPImageWorkshop/GifFrameExtractor.php');
include('application/libraries/PHPImageWorkshop/GifCreator.php');

/* ---------------- Helper function to generate image thumb from the file -----------------------
Parameter Required :-
	1. original_image_path -> Path of the image file folder from which the thumb is genereated
	2. dest_folder_to_thumb -> Destination folder path to save image thumbnail must be different than original_image_path
	3. file_name -> Name of the file from which the thumb is genereated 
	4. thumb_width ->  Required thumbnail width 
	5. thumb_height -> Required thumbnail height 
*/
function generateThumbnail($original_image_path,$dest_folder_to_thumb,$file_name,$thumb_width='',$thumb_height='')
{
	if($thumb_width=='' || $thumb_width==0)
	{
		$thumb_width=150;
	}
	if($thumb_height==''  || $thumb_width==0)
	{
		$thumb_height=150;
	}
	$ext=end(explode(".",strtolower($file_name)));
	// check this is an animated GIF
	if($ext=="gif" && GifFrameExtractor::isAnimatedGif($original_image_path.$file_name))
	{
			   // Extractions of the GIF frames and their durations
				$gfe = new GifFrameExtractor();
				$frames = $gfe->extract($original_image_path.$file_name);
				
				$retouchedFrames = array();
			 
				// For each frame, we resize it
				foreach ($frames as $frame)
				{
					// Initialization of the frame as a layer
					$frameLayer = ImageWorkshop::initFromResourceVar($frame['image']);
					
					$frameLayer->resizeInPixel($thumb_width, $thumb_height, true,"MM"); // Resizing
					$retouchedFrames[] = $frameLayer->getResult();
				}

				$gc = new GifCreator();
				$gc->create($retouchedFrames, $gfe->getFrameDurations(), 0);
				// store image in thumbnail destination
				file_put_contents($dest_folder_to_thumb.$file_name, $gc->getGif());
		
	}
	else
	{
			$image = ImageWorkshop::initFromPath($original_image_path.$file_name);
			$image->resizeInPixel($thumb_width, $thumb_height, true,"MM"); // Making a thumbnail
			// Save over original
			$image->save($dest_folder_to_thumb, $file_name);
	}
}
	
?>
<?php
class Thumbnail{

private $thumb_w,$thumb_h,$thumb_src,$thumb_dest,$thumb_temp_dir;

public function __construct($arr_config){
	
		$this->thumb_w=$arr_config['width'];
		$this->thumb_h=$arr_config['height'];
		$this->thumb_src=$arr_config['source'];
		$this->thumb_dest=$arr_config['destination_dir'].$arr_config['destination_file'];
		$this->thumb_temp_dir=$arr_config['destination_dir'];

}

	public function generateThumb()
	{
		$str_to_return=true;
		try{
		if(!is_file($this->thumb_src)) throw new Exception("Source file not found");
		// calculate source image size
		list($src_w,$src_h,$src_a,$src_b)=getimagesize($this->thumb_src);
		
		if(!is_writable($this->thumb_temp_dir)) throw new Exception("Destination directory not writable");
		
		// check whether the source image is greater than the thumbnail size
		if($src_w >= $this->thumb_w  || $src_h >= $this->thumb_h )
		{
			// okay, the source size is greater than the thumbnail size. Make it smaller
			$str_exec='convert "'.$this->thumb_src.'" -resize '.$this->thumb_w."X".$this->thumb_h.' "'.$this->thumb_dest.'"';
			exec($str_exec);
			
			// now check the size of newly created thumb to fit into the ratio of thumb
			list($dest_w,$dest_h,$dest_a,$dest_b)=getimagesize($this->thumb_dest);
			
			if($dest_w < $this->thumb_w || $dest_h < $this->thumb_h)
			{
				// create an empty image as placeholder
				$tmp_place_holder=$this->thumb_temp_dir.trim(str_replace(" ",'',microtime())).".jpg";
				echo $str_exec='convert -size '.$this->thumb_w.'X'.$this->thumb_h.' xc:white "'.$tmp_place_holder.'"';
				exec($str_exec);
				sleep(1);
				// composite both images
				$str_exec='convert -gravity center "'.$tmp_place_holder.'" "'.$this->thumb_dest.'" -composite "'.$this->thumb_dest.'"';
				exec($str_exec);
				
				// remove temparary image
				@unlink($tmp_place_holder);
			}
			
			
		}
		else
		{
			// the image is already a thumbnail, just create proper size of image
			// create an empty image as placeholder
				$tmp_place_holder=$this->thumb_temp_dir.trim(str_replace(" ",'',microtime())).".jpg";
				$str_exec='convert -size '.$this->thumb_w.'X'.$this->thumb_h.' xc:white "'.$tmp_place_holder.'"';
				exec($str_exec);
				sleep(1);
				// composite both images
				$str_exec='convert -gravity center "'.$tmp_place_holder.'" "'.$this->thumb_src.'" -composite "'.$this->thumb_dest.'"';
				exec($str_exec);
				
				// remove temparary image
				@unlink($tmp_place_holder);
			
		}
		
	}catch( Exception $e )
	{
		echo $e->getMessage();
		$str_to_return=false;
	}
	
	return $str_to_return;
	
	}

}
?>

<?php
class FancyImage {

	static function getImageBase64($chemin_complet) {
		$type = pathinfo($chemin_complet, PATHINFO_EXTENSION);
		$data = file_get_contents($chemin_complet);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		return $base64;
	}


	static function saveToWebP($dst_dir) {
		 if (file_exists($dst_dir)) {
		
			$image = imagecreatefromjpeg($dst_dir);
			$dst_dir_new = str_replace("jpg", "webp", $dst_dir);
			
			//Reduce this to decrease the file size.
			$quality = 75; // 1 - 100

			imagewebp($image, $dst_dir_new, $quality); //Create the webp image.
			
			if (file_exists($dst_dir)) {
				unlink($dst_dir);
			}
		 }
	}
	
	static function cropAndSaveImage($image_prefixe, $image_suffixe, $max_width, $max_height, $image_tmp, $dst_dir, $image_nom) {

		//enregistre une image au format WEBP
		
		$imageInfo = getimagesize($image_tmp); 

		$width = $imageInfo[0];
		$height = $imageInfo[1];
		$mime = $imageInfo['mime'];
		
		/*
		echo "image_tmp = ".$image_tmp."<br>";
		echo "dst_dir = ".$dst_dir."<br>";
		echo "image_nom = ".$image_nom."<br>";
		echo "max_width = ".$max_width."<br>";
		echo "max_height = ".$max_height."<br>";
		var_dump($imageInfo);
		exit;
		*/
		switch($mime){
			case 'image/png':
				$image_create = "imagecreatefrompng"; //image source
				$image = "imagewebp"; //image en sortie
				$quality = 75;
				$format = ".webp";
				break;
			case 'image/webp':
				$image_create = "imagecreatefromwebp"; //image source
				$image = "imagewebp"; //image en sortie
				$quality = 75;
				$format = ".webp";
				break;
			case 'image/jpeg':
				$image_create = "imagecreatefromjpeg"; //image source
				$image = "imagewebp"; //image en sortie
				$quality = 75;
				$format = ".webp";
				break;
			default:
				return false;
				break;
		}
		
		
		$image_nom = $image_prefixe . $image_nom . $image_suffixe . $format;
		$width_new = $height * $max_width / $max_height;
		$height_new = $width * $max_height / $max_width;
		
		$dst_img = imagecreatetruecolor($max_width, $max_height);
		
		
		$src_img = $image_create($image_tmp);
		
		//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
		if($width_new > $width){
			$h_point = (($height - $height_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
		}else{
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
		}
		
	
		$dst_dir .= "/".$image_nom;

		$image($dst_img, $dst_dir, $quality);
	
	  	if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);

	}
	

}

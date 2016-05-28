<?php 
function makeimage($filename,$newfilename,$path,$newwidth,$newheight) {
	/*if (!extension_loaded('gd2')) {
   if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
       dl('php_gd2.dll');
   } else {
       dl('php_gd2.so');
   }
	}*/
	
	//SEARCHES IMAGE NAME STRING TO SELECT EXTENSION (EVERYTHING AFTER . )
	$image_type = strstr(substr($filename, -5), '.'); // ตัดย้อนหลังมา 5 ตัวอักษรก่อน เพื่อไม่ให้มี ../ ติดมา
	
	//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
		switch($image_type) {
			case '.jpg':
				$source = imagecreatefromjpeg($filename);
				break;
			case '.JPG':
				$source = imagecreatefromjpeg($filename);
				break;
			case '.jpeg':
				$source = imagecreatefromjpeg($filename);
				break;
			case '.JPEG':
				$source = imagecreatefromjpeg($filename);
				break;
			case '.png':
				$source = imagecreatefrompng($filename);
				break;
			case '.PNG':
				$source = imagecreatefrompng($filename);
				break;
			case '.gif':
				$source = imagecreatefromgif($filename);
				break;
			case '.GIF':
				$source = imagecreatefromgif($filename);
				break;
			default:
				echo("Error Invalid Image Type");
				die;
				break;
			}
	
	//CREATES THE NAME OF THE SAVED FILE
	//$file = $newfilename . $filename;
	$file = $newfilename;
	
	//CREATES THE PATH TO THE SAVED FILE
	$fullpath = $path . $file;

	//FINDS SIZE OF THE OLD FILE
	list($width, $height) = getimagesize($filename);
	
	// New Size > Old Size
	if ($width > $newwidth) {
	// IF New Height = 0
		if ($newheight == 0) {
			$newheight = ($height * (($newwidth / $width) * 100)) / 100;
		}
	}
	else {
		$newwidth = $width;
		$newheight = $height;
	}
	
	//CREATES IMAGE WITH NEW SIZES
	$thumb = imagecreatetruecolor($newwidth, $newheight);

	//RESIZES OLD IMAGE TO NEW SIZES
	imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	
	/* Add String In Image */  // And Merge Image
	/*$X = 5;
	$Y = 5;
	
	$stringImage  = imagecreatefromgif("../image/home/logoPaypal.gif");
	$black = imagecolorallocate($thumb, 115, 115, 115);
	
	$margin = 5;
	$wmX = (bool)rand(0,1) ? $margin : (imageSX($thumb) - imageSX($stringImage)) - $margin;
 	$wmY = (bool)rand(0,1) ? $margin : (imageSY($thumb) - imageSY($stringImage)) - $margin;
	imagecopymerge($thumb, $stringImage, $X, $Y, 0, 0, imagesx($stringImage), imagesy($stringImage), 100);*/
	/*----------------------*/
	
	/* Add String In Image */
	/*$X = 0;
	$Y = 0;
	$string = "WWW.SOONPHRA.COM";
	$black = imagecolorallocate($thumb, 115, 115, 115);
	imagestring($thumb, 15, $X, $Y, $string, $black); // (รูป, ขนาดตัวหนังสือ, ตำแหน่ง X, ตำแหน่ง Y, ตัวหนังสือ, สี)*/
	/*----------------------*/
	
	//SAVES IMAGE AND SETS QUALITY || NUMERICAL VALUE = QUALITY ON SCALE OF 1-100
	imagejpeg($thumb, $fullpath, 100);

	//CREATING FILENAME TO WRITE TO DATABSE
	$filepath = $fullpath;
	
	//RETURNS FULL FILEPATH OF IMAGE ENDS FUNCTION
	return $filepath;

}

?> 

<?
session_start();
//$_POST[edit]=48;
//$_POST[dealrent]=2;
function chekdata($mydata){
		$mydata = strip_tags($mydata);
		$mydata = trim($mydata);
		$mydata = htmlspecialchars($mydata);
		$mydata = mysql_escape_string($mydata);
		$mydata = mb_substr($mydata, 0,1400, 'UTF-8');
		return $mydata;
}
function mythumbnails($x=100,$y=100,$path,$picture,$destin, $prefix){
	$im = @imagecreatefromjpeg($path.$picture);
	$ext="jpg";
	if ($im === false) {
		$im = @imagecreatefromgif($path.$picture);
		$ext="gif";
		if ($im === false) {
			$im = @imagecreatefrompng($path.$picture);
			$ext="png";
			}
		else {
			$ext=false;
			return false;
			}
		}
	if ($im){
	$size=array();
	$size=@getimagesize($path.$picture);
	$srcx= $size[0];
	$srcy= $size[1];
	if($srcx>$srcy){ 
		$dstx= $x;
		$dsty= $srcy/$srcx*$x;
		}
	else {
		$dsty= $y;
		$dstx= $srcx/$srcy*$y;
		}
	$dest = imagecreatetruecolor($dstx,$dsty);
	
	imagecopyresampled($dest, $im, 0, 0, 0 ,0, $dstx, $dsty, $srcx, $srcy);
	$verch=imagejpeg($dest,$destin.$prefix.$picture);
	if($verch) return $destin.$prefix.$picture;
	else return false;}
}
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break; 
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}
function imagerotateEquivalent($srcImg, $angle, $bgcolor = 0 , $ignore_transparent = 0) {
    function rotateX($x, $y, $theta){
        return $x * cos($theta) - $y * sin($theta);
    }
    function rotateY($x, $y, $theta){
        return $x * sin($theta) + $y * cos($theta);
    }

    $srcw = imagesx($srcImg);
    $srch = imagesy($srcImg);

    //Normalize angle
    $angle %= 360;
    //Set rotate to clockwise
    $angle = -$angle;

    if($angle == 0) {
        if ($ignore_transparent == 0) {
            imagesavealpha($srcImg, true);
        }
        return $srcImg;
    }

    // Convert the angle to radians
    $theta = deg2rad ($angle);

    //Standart case of rotate
    if ( (abs($angle) == 90) || (abs($angle) == 270) ) {
        $width = $srch;
        $height = $srcw;
        if ( ($angle == 90) || ($angle == -270) ) {
            $minX = 0;
            $maxX = $width;
            $minY = -$height+1;
            $maxY = 1;
        } else if ( ($angle == -90) || ($angle == 270) ) {
            $minX = -$width+1;
            $maxX = 1;
            $minY = 0;
            $maxY = $height;
        }
    } else if (abs($angle) === 180) {
        $width = $srcw;
        $height = $srch;
        $minX = -$width+1;
        $maxX = 1;
        $minY = -$height+1;
        $maxY = 1;
    } else {
        // Calculate the width of the destination image.
        $temp = array (rotateX(0, 0, 0-$theta),
        rotateX($srcw, 0, 0-$theta),
        rotateX(0, $srch, 0-$theta),
        rotateX($srcw, $srch, 0-$theta)
        );
        $minX = floor(min($temp));
        $maxX = ceil(max($temp));
        $width = $maxX - $minX;

        // Calculate the height of the destination image.
        $temp = array (rotateY(0, 0, 0-$theta),
        rotateY($srcw, 0, 0-$theta),
        rotateY(0, $srch, 0-$theta),
        rotateY($srcw, $srch, 0-$theta)
        );
        $minY = floor(min($temp));
        $maxY = ceil(max($temp));
        $height = $maxY - $minY;
    }

    $destimg = imagecreatetruecolor($width, $height);
        $bg2 = imagecolorallocate($destimg, 255, 0, 255);
        imagecolortransparent($destimg,$bg2);
        
/*    if ($ignore_transparent == 0) {
        imagefill($destimg, 0, 0, imagecolorallocatealpha($destimg, 255,255, 255, 127));
        imagesavealpha($destimg, true);
    }*/

    // sets all pixels in the new image
    for($x=$minX; $x<$maxX; $x++) {
        for($y=$minY; $y<$maxY; $y++) {
            // fetch corresponding pixel from the source image
            $srcX = round(rotateX($x, $y, $theta));
            $srcY = round(rotateY($x, $y, $theta));
            if($srcX >= 0 && $srcX < $srcw && $srcY >= 0 && $srcY < $srch) {
                $color = imagecolorat($srcImg, $srcX, $srcY );
            } else {
                $color = $bgcolor;
            }
            imagesetpixel($destimg, $x-$minX, $y-$minY, $color);
        }
    }
        imagecolortransparent($destimg, imagecolorallocate($destimg, 0, 0, 0));
    return $destimg;
}
function turn($turn_image, $image, $degrees){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	
		$newImage = imagerotateEquivalent($source, $degrees, 0);
	//$newImage =$source;
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$turn_image); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$turn_image,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$turn_image);  
			break; 
    }
	chmod($turn_image, 0777);
	return $turn_image;
}

function AutoCropImage($thumb_image_name, $image, $ratio){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	if($imagewidth/$imageheight<=$ratio){
		$width=$imagewidth;
		$height=$imagewidth/$ratio;
		$xstart=0;
		$ystart=($imageheight-$height)/2;		
	}
	else {
		$width=$imageheight*$ratio;
		$height=$imageheight;
		$xstart=($imagewidth-$width)/2;
		$ystart=0;
	}
	$newImage = imagecreatetruecolor($width,$height);
	imagecopyresampled($newImage,$source,0,0,$xstart,$ystart,$width,$height,$width,$height);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break; 
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}
function ScaleImage($thumb_image_name, $image, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$width=$imagewidth/$scale;
	$height=$imageheight/$scale;
	$newImage = imagecreatetruecolor($width,$height);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$width,$height,$imagewidth,$imageheight);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break; 
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}





if($_POST[upload]){
	$name=$_SESSION['name_of_user'];
	$path="uploads/".$_SESSION['name_of_user'];
	$dir = $path."/";
	$valid=array("gif","png","jpg","PNG","JPG","JPEG", "GIF","jpeg");
	$filessize=array();
	$filetime=array();
	
 	if (is_dir($dir)) {
  		if ($dh = opendir($dir)) {
	  		while (($file = readdir($dh)) !== false) {
		  		if(filetype($dir . $file)==file){
			 		$exten = substr($file,1 + strrpos($file, "."));
					
			 		if(in_array($exten, $valid)){
				 		$ft=filectime($dir.$file);
						 $filetime[$file]=$ft;
						 //list($imagewidth, $imageheight, $imageType) = getimagesize($dir.$file);
						 if(!is_dir($dir."tumb/"))@mkdir($dir."tumb/");
						 mythumbnails(200,200,$path."/",$file,$dir."tumb/","");
						 $filenameshort=array();
						 $filex=array();
						 $filey=array();
						 $filedate=array();
						 $filesize=array();						 
						 $filename=array();
						arsort($filetime,SORT_NUMERIC);
						foreach($filetime as $file => $value){
							$filename[]=$file;
							$ft=filectime($dir.$file);
							$fs=filesize($dir.$file); 
							$ext = substr($file,1 + strrpos($file, "."));
							$wext= substr($file,0, strrpos($file, "."));
							if(mb_strlen($wext,'utf-8')>9){
								$ffffff=mb_substr($wext, 0,8,'utf-8')."...".$ext;
								$wextff=mb_substr($wext, 0,8,'utf-8')."...";
							}
							else {
								$ffffff=$file;
								$wextff=$wext;
							}
							$size=@getimagesize($dir.$file);
							$srcx= $size[0];
							$srcy= $size[1];
							$dd=date("m/d/Y H:i", $ft);
							$fsize=ceil($fs/1024);
							
							$filenameshort[]=$ffffff;
						 	$filex[]=$srcx;
						 	$filey[]=$srcy;
						 	$filedate[]=$dd;
						 	$filesize[]=$fsize;
						 }
						/* if($imagewidth>1001 || $imageheight>1001){
							$scale=$imagewidth/1000;
							ScaleImage($dir.$file, $dir.$file, $scale);
						 }*/
					/*	if(!file_exists ($dir."ready/".$file)){							
							if(!is_dir($dir."ready/wide/"))@mkdir($dir."/ready/wide");
							if(!is_dir($dir."ready/high/"))@mkdir($dir."/ready/high");
							if(!is_dir($dir."ready/mid/"))@mkdir($dir."/ready/mid");
							if(!is_dir($dir."ready/low/"))@mkdir($dir."/ready/low");
							AutoCropImage($dir."ready/".$file,$dir.$file,1.33);
							AutoCropImage($dir."ready/wide/".$file,$dir.$file,1.81);
							list($imagewidth, $imageheight, $imageType) = getimagesize($dir."ready/wide/".$file);
							$scalethumb=$imagewidth/670;
							ScaleImage($dir."ready/wide/".$file, $dir."ready/wide/".$file, $scalethumb);
							list($imagewidth, $imageheight, $imageType) = getimagesize($dir."ready/".$file);							
							$scalethumb=$imagewidth/534;							
							ScaleImage($dir."ready/high/".$file, $dir."ready/".$file, $scalethumb);
							$scalethumb=$imagewidth/395;
							ScaleImage($dir."ready/mid/".$file, $dir."ready/".$file, $scalethumb);
							$scalethumb=$imagewidth/165;
							ScaleImage($dir."ready/low/".$file, $dir."ready/".$file, $scalethumb);
							}*/
				  		}
			
					}
 	 		}closedir($dh);
 		 }
	}
	
	
/*$q = mysql_query("SELECT `image` FROM main WHERE `id`='$_POST[id]' ");
$res = mysql_fetch_assoc($q);

if($res[image]=="") mysql_query("UPDATE main SET `image`='$filezzzz'  WHERE `id`='$_POST[id]'")or die(mysql_error());
$ress=mysql_query("SELECT `image` FROM main WHERE `id`='$_POST[id]' ");
$res = mysql_fetch_assoc($ress);*/
$mydata=array();
$mydata[files]=$filename;
//$mydata[main]=$res[image];
$mydata[id]=$name;
$mydata[filenameshort]=$filenameshort;
$mydata[filex]=$filex;
$mydata[filey]=$filey;
$mydata[filedate]=$filedate;
$mydata[filesize]=$filesize;
header("Content-Type: text/plain");
echo json_encode($mydata);
}

if($_POST[del]){
	$_SESSION['id']=$_POST[itemid];
	$dir = "uploads/".$_POST[itemid]."/";
	if(file_exists($dir.$_POST[del])) $res1=unlink($dir.$_POST[del]);
	if(file_exists($dir."sel_".$_POST[del])) $res1=unlink($dir."sel_".$_POST[del]);
	/*if(file_exists($dir."ready/".$_POST[del])) $res1=unlink($dir."ready/".$_POST[del]);
	if(file_exists($dir."ready/high/".$_POST[del])) $res1=unlink($dir."ready/high/".$_POST[del]);
	if(file_exists($dir."ready/mid/".$_POST[del])) $res1=unlink($dir."ready/mid/".$_POST[del]);
	if(file_exists($dir."ready/low/".$_POST[del])) $res1=unlink($dir."ready/low/".$_POST[del]);
	if(file_exists($dir."ready/wide/".$_POST[del])) $res1=unlink($dir."ready/wide/".$_POST[del]);*/

echo json_encode(true);
}
//if($_POST[makemain]){
//	$_SESSION['id']=$_POST[id];
//	$path="uploads/".$_POST[id];
//	$dir = $path."/";
//	$valid=array("gif","png","jpg","PNG","JPG","JPEG", "GIF","jpeg");
//	$filessize=array();
//	$filetime=array();
//	
// 	if (is_dir($dir)) {
//  		if ($dh = opendir($dir)) {
//	  		while (($file = readdir($dh)) !== false) {
//		  		if(filetype($dir . $file)==file){
//			 		$exten = substr($file,1 + strrpos($file, "."));
//			 		if(in_array($exten, $valid)){
//				 		$ft=filectime($dir.$file);
//						 $filetime[$file]=$ft;
//				  		}
//			
//					}
// 	 		}closedir($dh);
// 		 }
//	}
//	$filename=array();
//	arsort($filetime,SORT_NUMERIC);
//	foreach($filetime as $file => $value){
//		$filename[]=$file;
//	}
//
//mysql_query("UPDATE main SET `image`='$_POST[makemain]'  WHERE `id`='$_POST[id]'")or die(mysql_error());
//$ress=mysql_query("SELECT `image` FROM main WHERE `id`='$_POST[id]' ");
//$res = mysql_fetch_assoc($ress);
//$mydata=array();
//$mydata[files]=$filename;
//$mydata[main]=$res[image];
//$mydata[id]=$_POST[id];
//header("Content-Type: text/plain");
//echo json_encode($mydata);
//}
if ($_POST['rot']){
	/*if(!is_dir("uploads/"."wide/"))@mkdir("uploads/"."wide");
	if(!is_dir("uploads/"."high/"))@mkdir("uploads/"."high");
	if(!is_dir("uploads/"."mid/"))@mkdir("uploads/"."/mid");
	if(!is_dir("uploads/"."low/"))@mkdir("uploads/"."low");*/
	if($_POST['direction']== 'rigth')
	turn("uploads/".$_POST[rot],"uploads/".$_POST[rot], 90);
	if($_POST['direction']== 'left') 
	turn("uploads/".$_POST[rot],"uploads/".$_POST[rot], 270);
	/*AutoCropImage("uploads/".$_POST[rot],"uploads/".$_POST[rot],1.33);
	AutoCropImage("uploads/"."wide/".$_POST[rot],"uploads/".$_POST[rot],1.81);
	list($imagewidth, $imageheight, $imageType) = getimagesize("uploads/"."wide/".$_POST[rot]);
	$scalethumb=$imagewidth/670;
	ScaleImage("uploads/"."wide/".$_POST[rot], "uploads/"."wide/".$_POST[rot], $scalethumb);
	list($imagewidth, $imageheight, $imageType) = getimagesize("uploads/".$_POST[rot]);
	$scalethumb=$imagewidth/534;
	ScaleImage("uploads/"."high/".$_POST[rot], "uploads/".$_POST[rot], $scalethumb);
	$scalethumb=$imagewidth/395;
	ScaleImage("uploads/"."mid/".$_POST[rot], "uploads/".$_POST[rot], $scalethumb);
	$scalethumb=$imagewidth/165;
	ScaleImage("uploads/"."low/".$_POST[rot], "uploads/".$_POST[rot], $scalethumb);*/

	echo json_encode(true); 
}
if ($_POST['name']){
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	$id=$_POST["id"];
	$name=$_POST['name'];
	$source="uploads/".$name;
	$destination="uploads/";
	/*if(!is_dir($destination."wide/"))@mkdir($destination."wide");
	if(!is_dir($destination."high/"))@mkdir($destination."high");
	if(!is_dir($destination."mid/"))@mkdir($destination."mid");
	if(!is_dir($destination."low/"))@mkdir($destination."low");*/
	$cropped = resizeThumbnailImage($destination."sel_".$name, $source,$w,$h,$x1,$y1,1);
	/*AutoCropImage($destination."wide/".$name,$destination."sel_".$name,1.81);
	list($imagewidth, $imageheight, $imageType) = getimagesize($destination."wide/".$name);
	$scalethumb=$imagewidth/670;
	ScaleImage($destination."wide/".$name, $destination."wide/".$name, $scalethumb);
	list($imagewidth, $imageheight, $imageType) = getimagesize($destination.$name);
	$scalethumb=$imagewidth/534;	
	ScaleImage($destination."high/".$name, $destination.$name, $scalethumb);
	$scalethumb=$imagewidth/395;
	ScaleImage($destination."mid/".$name, $destination.$name, $scalethumb);
	$scalethumb=$imagewidth/165;
	ScaleImage($destination."low/".$name, $destination.$name, $scalethumb);*/
	
	
	echo json_encode(true);
}
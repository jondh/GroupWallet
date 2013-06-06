<?php
session_start();

function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
 }

 define ("MAX_SIZE","3000");

$errors=0;
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $image =$_FILES["file"]["name"];
	$uploadedfile = $_FILES['file']['tmp_name'];

	if ($image){
		$filename = stripslashes($_FILES['file']['name']);
		$extension = getExtension($filename);
		$extension = strtolower($extension);
		if (($extension != "jpg") && ($extension != "jpeg") 
		&& ($extension != "png") && ($extension != "gif")){
			echo ' Unknown Image extension ';
			$errors=1;
			}
		else
		{
			$size=filesize($_FILES['file']['tmp_name']);
			if ($size > MAX_SIZE*1024){
				echo "You have exceeded the size limit";
				$errors=1;
			}
			if(!$errors){
				if($extension=="jpg" || $extension=="jpeg" ){
					$uploadedfile = $_FILES['file']['tmp_name'];
					$src = imagecreatefromjpeg($uploadedfile);
				}
				else if($extension=="png"){
					$uploadedfile = $_FILES['file']['tmp_name'];
					$src = imagecreatefrompng($uploadedfile);
				}
				else{
					$src = imagecreatefromgif($uploadedfile);
				}
		 
				list($width,$height)=getimagesize($uploadedfile);

				$newwidth=100;
				$newheight=($height/$width)*$newwidth;
				$tmp=imagecreatetruecolor($newwidth,$newheight);

				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,
				 $width,$height);

				$filename = "userPhotos/user". $_SESSION['s_userID'] . ".jpg";

				imagejpeg($tmp,$filename,100);

				imagedestroy($src);
				imagedestroy($tmp);
			}
		}
	}
}
//If no errors registred, print the success message

 if(isset($_POST['Submit']) && !$errors) 
 {
   // mysql_query("update SQL statement ");
  echo "Image Uploaded Successfully!";

 }
?>
<?php 

@date_default_timezone_set("GMT"); 

define('DATA_PATH',realpath(dirname(__DIR__).""));

$response = array('error' => 'NO_ERROR', 'error_message' => 'Image Uploaded!');

$filepath = DATA_PATH.'/assets/racerImages/';


//var_dump($_FILES['file']);
$target_filepath = $filepath.basename($_FILES['file']['name']);

//$response['target_file'] = $target_filepath;
//echo "target file is " . $target_filepath . "<br><br>";

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_filepath,PATHINFO_EXTENSION));


//$uploaded_file = $_FILES['fileToUpload']['tmp_name'];
$uploaded_file = $_FILES['file']['tmp_name'];

//$check = getimagesize($uploaded_file);
$check = $_FILES['file']['size'];
if($check !== false) {
	//echo "File is an image - " . $check["mime"] . ".";
	$uploadOk = 1;
} else {
	//echo "File is not an image.";
	$response['error'] = "ERROR";
	$response['error_message'] = "File is not an image.";
	$uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "png") {
	//echo "<br>Sorry, only PNG files are allowed. Yours was a " . $imageFileType;
	$response['error'] = "ERROR";
	$response['error_message'] = "Sorry, only PNG files are allowed. Yours was a " . $imageFileType;
    $uploadOk = 0;
}



// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    //echo "<br>Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	
	list($imageWidth, $imageHeight) = getimagesize($uploaded_file);
	if ($imageWidth != $imageHeight) {
		$response['error_message'] = "Image isn't a square.  The image will be stretched to 285px by 285px.";
		
	}
	

	$src = imagecreatefrompng($uploaded_file);
	$tmp = imagecreatetruecolor(285 , 285);
	imagealphablending($tmp, true);
	
	imagecopyresampled($tmp, $src, 0, 0, 0, 0, 285 , 285, $imageWidth, $imageHeight);
	
	//imagejpeg($tmp, $_FILES["fileToUpload"]["name"], 100);
	//$newDir = getcwd() . '../assets/racerImages/';
	$new_filename = '../assets/racerImages/'.$_FILES['file']['name'];
	//$response['debug_message'] = "Attempting to save " . $tmp . " to " . $new_filename;
	if (imagepng($tmp, $new_filename, 0)) {//if (imagejpeg($tmp, $target_filepath, 100)) {
		//echo "<br>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		//$response['error_message'] = "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
    } else {
		$response['error_message'] = "Sorry, there was an error uploading your file.";
        //echo "<br>Sorry, there was an error uploading your file.";
    }
	imagedestroy($src);
	imagedestroy($tmp);
}

echo json_encode($response);
exit();
?>
<?php
require_once 'vendor/autoload.php';
require_once 'Class/Database.php';

$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = false;
$responseText='';
$responseError=0;

$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if($check !== false) {
    $uploadOk = true;
    //$responseText= $responseText."Image dimensions ".$check[0]." x".$check[1]." type ".$check[2]." MIME".$check['mime']."<br>";
    //Image size: upto 1920x1080 Index 0 and 1 contains respectively the width and the height of the image.
    if ($check[0] < 1 || $check[0] > 1920) {
        $responseText= $responseText."Sorry, width dimensions between 1 and 1920 ".$check[0]."<br>";
        $responseError =1;
        $uploadOk = $uploadOk & false;
    }
    if ($check[1] < 1  || $check[1] > 1080) {
        $responseText= $responseText."Sorry, height dimensions between 1 and 1080 ".$check[1]."<br>";
        $responseError =2;
        $uploadOk = $uploadOk & false;
    }
    if ($check[2] !==IMG_PNG  && $check[2] !==IMAGETYPE_GIF && $check[2] !==IMAGETYPE_JPEG && $check[2] !==IMAGETYPE_JPEG2000 ) {
        $responseText= $responseText."Sorry, only JPG, JPEG, PNG & GIF files are allowed. ".$check[2]."<br>";
        $responseError =3;
        $uploadOk = $uploadOk & false;
    }
} else {
    $responseText= $responseText."File is not an image.<br>";
        $responseError =4;
    $uploadOk = false;
}
// Check if file already exists
if (file_exists($target_file)) {
    $responseText= $responseText."Sorry, file already exists.<br>";
        $responseError =11;
    $uploadOk = false;
}
//  file size up to 20MB -> 20 * 1024 * 1024 bytes
// upload_max_filesize > 20M post_max_size > 20
if ($_FILES["fileToUpload"]["size"] > 1048576*20) {
    $responseText= $responseText."Sorry, your file is too large. ".$_FILES["fileToUpload"]["size"]."<br>";
    $responseError =12;
    $uploadOk = false;
}

// Check if $uploadOk is set to false by an error
if (!$uploadOk) {
    $responseText= $responseText. "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $responseText= $responseText. "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
    } else {
        $responseText= $responseText. "Sorry, there was an error uploading your file.<br>";
        $uploadOk = false;
    }
}

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$filename= basename($_FILES["fileToUpload"]["name"]);

if (!$uploadOk) {
    $insertDocumento = new \Forum\Database();
    $insertDocumento->setDocument($title, $filename);
    $responseText= $responseText. "Data.".$title." ggg ".$filename." <br>";
}


$dataResponse = array (
    "uploadOk" => $uploadOk,
    "error" => $responseError,
    "text"  => $responseText,
    "archive" => array(
                "filename" => basename($_FILES["fileToUpload"]["name"]),
                "title" => $title)
    );

//print_r(array_values($dataResponse));

////''/** whatever you're serializing **/];
header('Content-Type: application/json');
echo json_encode($dataResponse);



<?php
$uploaddir = dirname(__FILE__).'/uploads/';

$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File Uploaded!";
} else {
    echo "An error occurred while uploading your file, please try again.";
}
?>
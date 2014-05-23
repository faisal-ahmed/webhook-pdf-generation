<?php
$uploaddir = dirname(__FILE__).'/uploads/';
$template_name = str_replace(" ", "_", $_REQUEST['template_name']);
$file_name = $template_name . substr(basename($_FILES['userfile']['name']), strrpos(basename($_FILES['userfile']['name']), "."));
$uploadfile = $uploaddir . $file_name;

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo $file_name . "___File Uploaded!";
} else {
    echo "___An error occurred while uploading your file, please try again.";
}
?>
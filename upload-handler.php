<?php
$isImage = $_REQUEST['image'];

if ($isImage === 'true') {
    $uploaddir = dirname(__FILE__).'/templateImages/';
    $template_id = str_replace(" ", "_", $_REQUEST['template_id']);
    $file_name = $template_id . basename($_FILES['userfile']['name']);
} else {
    $uploaddir = dirname(__FILE__).'/uploads/';
    $template_name = str_replace(" ", "_", $_REQUEST['template_name']);
    $file_name = $template_name . substr(basename($_FILES['userfile']['name']), strrpos(basename($_FILES['userfile']['name']), "."));
}

$uploadfile = $uploaddir . $file_name;
$uploadfile = str_replace("\\", "/", $uploadfile);
if (!file_exists($uploadfile)) {
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        $file = ($isImage) ? $uploadfile : $file_name;
        echo $file . "___File Uploaded!";
    } else {
        echo "___An error occurred while uploading your file, please try again.";
    }
} else {
    echo $uploadfile . "___Filename is associated with another file. See the existing file URL at the top.";
}
?>
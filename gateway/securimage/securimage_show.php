<?php
error_reporting(1);
ini_set("display_errors", "On");
include_once '../securimage/securimage.php';
$img = new securimage();
$img->show(); // alternate use:  $img->show('/path/to/background.jpg');
?>

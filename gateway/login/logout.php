<?php
include_once('../inc/constants.php');
unset($_SESSION['ITUser']);

$Go = CMSSITEPATH."/index.php";
header("location:$Go");

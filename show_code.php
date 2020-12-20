<?php 
session_start();
include_once 'tool/vcode.php';
$_SESSION['vcode']=vcode();
?>
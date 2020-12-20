<?php 
include_once '../inc/config.php';
include_once '../inc/mysqli.php';
include_once '../tool/skip.php';
if(!isset($_GET['id'])||!isset($_GET['url'])||!is_numeric($_GET['id'])){
	exit();
}
$link=connect();
$sql="DELETE from son_module where id={$_GET['id']}";
execute_bool($link, $sql);
if(mysqli_affected_rows($link)==1){
	skip($_GET['url'],'ok','删除成功');
}
?>
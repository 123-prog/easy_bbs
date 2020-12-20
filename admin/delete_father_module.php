<?php 
include_once '../inc/config.php';
include_once '../inc/mysqli.php';
include_once '../tool/skip.php';
if(!isset($_GET['id'])||!isset($_GET['url'])||!is_numeric($_GET['id'])){
	exit();
}
$link=connect();
$sql="select * from son_module where father_module_id={$_GET['id']}";
$result=execute($link, $sql);
if(mysqli_num_rows($result)!=0){
	skip($_GET['url'],'error','该父版块下有子版块，请先删除其所有子版块！');
}
else{
$sql="DELETE from father_module where id={$_GET['id']}";
execute_bool($link, $sql);
if(mysqli_affected_rows($link)==1){
	skip($_GET['url'],'ok','删除成功');
}
}
?>
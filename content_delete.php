<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
$link=connect();
$login_id=isLogin($link);
if(!$login_id){
	skip('login.php', 'error', '请先登录');
}
if(!isset($_GET['id'])||!isset($_GET['url'])||!is_numeric($_GET['id'])){
	exit();
}
$sql="select member_id from content where id={$_GET['id']}";
$result=execute($link, $sql);
$member_id=mysqli_fetch_assoc($result);
if($member_id['member_id']==$login_id){
	$sql="delete from content where id={$_GET['id']}";
	execute($link, $sql);
	if(mysqli_affected_rows($link)==1){
		skip($_GET['url'], 'ok', '删除成功');
	}
	else{
		skip($_GET['url'], 'error', '删除失败，请重试');
	}
}
else{
	skip("member.php?id={$login_id}", 'error', '您无权操作他人的帖子');
}
?>
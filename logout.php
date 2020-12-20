<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
$link=connect();
$login_id=isLogin($link);
if($login_id){
	setcookie('member[name]',time()-3600);
	setcookie('member[pw]',time()-3600);
	skip('index.php', 'ok', '退出登录成功');
}
else{
	skip('login.php', 'error', '您还未登录');
}
?>
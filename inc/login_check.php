<?php 
//用户名验证
if(empty($_POST['name'])&&$_POST['name']!="0"){
	$name_error="用户名不得为空";
}
elseif(mb_strlen($_POST['name'])>32){
	$name_error="用户名不得超过32个字符";
}
else{
	$name=$_POST['name'];
}
//密码验证
if(empty($_POST['pw'])&&$_POST['pw']!="0"){
	$pw_error="密码不得为空";
}
else{
	$pw=$_POST['pw'];
}
//验证码验证
if(strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
	$vcode_error="验证码错误";
}

?>
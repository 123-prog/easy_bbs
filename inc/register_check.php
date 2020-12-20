<?php 
//用户名验证
if(empty($_POST['name'])&&$_POST['name']!="0"){
	$name_error="用户名不得为空";
}
elseif(mb_strlen($_POST['name'])>32){
	$name_error="用户名不得超过32个字符";
}
else{
	$_POST['name']=escape($link, $_POST['name']);
	$sql="select * from member where name='{$_POST['name']}'";
	$result=execute($link, $sql);
	if(mysqli_num_rows($result)){
		$name_error="用户名已经存在";
	}
	else{
		$name=$_POST['name'];
	}
}
//密码验证
if(empty($_POST['pw'])&&$_POST['pw']!="0"){
	$pw_error="密码不得为空";
}
elseif(!(mb_strlen($_POST['pw'])>=6&&mb_strlen($_POST['pw'])<=14)){
	$pw_error="密码应为6~14个字符";
}
else{
	$pw=$_POST['pw'];
}
//二次密码验证
if($_POST['confirm_pw']!=$pw){
	$confirm_pw_error="两次密码输入不一致,请检查";
}
//验证码验证
if(strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
	$vcode_error="验证码错误";
}

?>
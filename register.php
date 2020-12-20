<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
$css[]='register.css';
$title="注册";
$name=$pw=$vcode="";
$name_error=$pw_error=$confirm_pw_error=$vcode_error="";
$link=connect();
$login_id=isLogin($link);
if($_SERVER['REQUEST_METHOD']=="POST"){
	include_once 'inc/register_check.php';
	if(empty($name_error)&&empty($pw_error)&&empty($confirm_pw_error)&&empty($vcode_error)){
		$sql="insert into member(name,pw,register_time) values('{$name}',md5('{$pw}'),now())";
		execute($link, $sql);
		if(mysqli_affected_rows($link)==1){
			skip('login.php','ok','注册成功');
		}
	}
}
?>
<?php include_once 'inc/headhtml.php'?>
	<div id="register" class="auto">
		<h2>注册成为会员</h2>
		<form method="post">
			<label>用户名：<input type="text" name="name" /><span style="color:red;">*<?php echo $name_error;?></span></label>
			<label>密码：<input type="password" name="pw" /><span style="color:red;">*<?php echo $pw_error;?></span></label>
			<label>确认密码：<input type="password" name="confirm_pw" /><span style="color:red;">*<?php echo $confirm_pw_error;?></span></label>
			<label>验证码：<input name="vcode" type="text" name="vcode" /><span style="color:red;">*<?php echo $vcode_error;?></span></label>
			<img class="vcode" src="show_code.php" />
			<div style="clear:both;"></div>
			<input class="btn" type="submit" value="确定注册" />
		</form>
	</div>
<?php include_once 'inc/foothtml.php';?>
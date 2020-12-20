<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
$css[]='register.css';
$link=connect();
$title="登录";
$login_id=isLogin($link);
if($login_id){
	skip('index.php', 'error', '您已经登录，请不要重复登录');
}
$name=$pw="";
$name_error=$pw_error=$vcode_error="";
if($_SERVER['REQUEST_METHOD']=="POST"){
	include_once 'inc/login_check.php';
	if(empty($name_error)&&empty($pw_error)&&empty($vcode_error)){
		$_POST['name']=escape($link, $_POST['name']);
		$sql="select * from member where name='{$name}' and pw=md5('{$pw}')";
		$result=execute($link, $sql);
		if(mysqli_num_rows($result)){
			setcookie('member[name]',$name,time()+3600);
			setcookie('member[pw]',sha1(md5($pw)),time()+3600);
			skip('index.php', 'ok', '登录成功');
		}
		else{
			skip('login.php','error','用户名或密码错误');
		}
	}
}
?>
<?php include_once 'inc/headhtml.php';?>
<div id="register" class="auto">
	<h2>登录界面</h2>
	<form method="post">
		<label>用户名：<input type="text" name="name"  /><span style="color:red;"><?php echo $name_error;?></span></label>
		<label>密码：<input type="password" name="pw" /><span style="color:red;"><?php echo $pw_error;?></span></label>
		<label>验证码：<input name="vcode" type="text"  /><span style="color:red;"><?php echo $vcode_error;?></span></label>
		<img class="vcode" src="show_code.php" />
		<div style="clear:both;"></div>
		<input class="btn" type="submit" value="登录" />
	</form>
</div>
<?php include_once 'inc/foothtml.php';?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title><?php echo $title;?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" type="text/css" href="style/public.css" />
<?php 
foreach($css as $val){
echo "<link rel='stylesheet' type='text/css' href='style/$val' />";
}
?>
</head>
<body>
	<div class="header_wrap">
		<div id="header" class="auto">
			<div class="logo">UpoUp</div>
			<div class="nav">
				<a class="hover" href="index.php">首页</a>
			</div>
			<div class="serarch">
				<form>
					<input class="keyword" type="text" name="keyword" placeholder="搜索其实很简单" />
					<input class="submit" type="submit" name="submit" value="" />
				</form>
			</div>
			<div class="login">
			<?php 
				if($login_id){
			
$s=<<<A
				<span style="color:white;">欢迎</span>&nbsp;
				<a href="member.php?id=$login_id">{$_COOKIE['member']['name']}</a>
				<a href="logout.php" style="color:red;">退出<a>
A;
				echo $s;
			}
			else{
$s=<<<A
				<a href="login.php">登录</a>&nbsp;
				<a href="register.php">注册</a>
A;
				echo $s;
			}
			?>
			</div>
		</div>
	</div>
	<div style="margin-top:55px;"></div>
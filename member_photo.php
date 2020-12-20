<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
include_once 'inc/page.php';
include_once 'inc/upload.php';
$link=connect();
$title="修改头像";
$login_id=isLogin($link);
if(empty($_GET['id'])||!is_numeric($_GET['id'])){
	skip('index.php', 'error', 'id参数出错');
}
elseif($login_id!=$_GET['id']){
	skip('index.php', 'error', '无权修改他人头像');
}
else{
	$sql="select * from member where id={$_GET['id']}";
	$result_member=execute($link, $sql);
	$member=mysqli_fetch_assoc($result_member);
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$file=upload('uploads/', '2M');
	if(!$file['return']){
		skip('index.php', 'error', $file['error']);
	}
	else{
		$sql="update member set photo='{$file['save_path']}' where id={$login_id}";
		execute($link, $sql);
		if(mysqli_affected_rows($link)==1){
			skip("member.php?id={$login_id}", 'ok', '修改成功');
		}
	}
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<style type="text/css">
body {
	font-size:12px;
	font-family:微软雅黑;
}
h2 {
	padding:0 0 10px 0;
	border-bottom: 1px solid #e3e3e3;
	color:#444;
}
.submit {
	background-color: #3b7dc3;
	color:#fff;
	padding:5px 22px;
	border-radius:2px;
	border:0px;
	cursor:pointer;
	font-size:14px;
}
#main {
	width:80%;
	margin:0 auto;
}
</style>
</head>
<body>
	<div id="main">
		<h2>更改头像</h2>
		<div>
			<h3>原头像：</h3>
			<img  width=120px; height=120px; src="<?php if($member['photo']!=""){echo $member['photo'];}else{echo 'style/photo.jpg';}?>" />
		</div>
		<div style="margin:15px 0 0 0;">
			<form method="post" enctype="multipart/form-data"> 
				<input style="cursor:pointer;" width="100" type="file" name="file" /><br /><br />
				<input class="submit" type="submit" value="保存" />
			</form>
		</div>
	</div>
</body>
</html>

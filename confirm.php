<?php 
include_once 'inc/config.php';
$_GET['message']=htmlspecialchars($_GET['message']);
if(!isset($_GET['message'])||!isset($_GET['url'])||!isset($_GET['return'])){
	exit();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>确认界面</title>
<link rel="stylesheet" type="text/css" href="admin/style/remind.css" />
</head>
<body>
<div class="notice"><span class="pic ask"></span>是否删除帖子:<?php echo $_GET['message'];?>&nbsp;&nbsp;<a style="color:red;" href='<?php echo $_GET['url'];?>'>确认删除</a>&nbsp;&nbsp;<a href='<?php echo $_GET['return'];?>'>取消</a></div>
</body>
</html>
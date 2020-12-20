<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
include_once 'inc/page.php';
$link=connect();
$css[]='publish.css';
$title="帖子回复页";
$login_id=isLogin($link);
if(empty($_GET['id'])||!is_numeric($_GET['id'])){
	skip('index.php', 'error', 'id参数出错');
}
if(!$login_id){
	skip('login.php', 'error', '您还未登录，请先登录');
}

$sql="select * from content where id={$_GET['id']}";
$result_content=execute($link, $sql);
if(mysqli_num_rows($result_content)==0){
	skip('index.php', 'error', 'id参数不存在');
}
$content=mysqli_fetch_assoc($result_content);
$content['title']=htmlspecialchars($content['title']);

$sql="select * from son_module where id={$content['module_id']}";
$result_son=execute($link, $sql);
$son=mysqli_fetch_assoc($result_son);

$sql="select * from father_module where id={$son['father_module_id']}";
$result_father=execute($link, $sql);
$father=mysqli_fetch_assoc($result_father);

$sql="select * from member where id={$content['member_id']}";
$result_member=execute($link, $sql);
$member=mysqli_fetch_assoc($result_member);

if($_SERVER['REQUEST_METHOD']=="POST"){
	if(mb_strlen($_POST['content'])<3){
		skip($_SERVER['REQUEST_URI'], 'error', '回复不得少于3个字符');
	}
	$_POST['content']=escape($link, $_POST['content']);
	$sql="insert into reply(content_id,content,time,member_id) values({$content['id']},'{$_POST['content']}',now(),{$login_id})";
	execute($link, $sql);
	if(mysqli_affected_rows($link)==1){
		skip("show.php?id={$content['id']}", 'ok', '回复成功');
	}
	else{
		skip("show.php?id={$content['id']}", 'error', '回复失败，请重试');
	}
}

?>
<?php include_once 'inc/headhtml.php';?>
<div id="position" class="auto">
	 <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $father['id']?>"><?php echo $father['module_name']?></a> &gt; <a href="list_son.php?id=<?php echo $son['id']?>"><?php echo $son['module_name']?></a> &gt; <span><?php echo $content['title'];?></span>
</div>
<div id="publish">
	<div>回复：由 <?php echo $member['name']?> 发布的 <?php echo $content['title']?></div>
	<form method="post">
		<textarea name="content" class="content"></textarea>
		<input class="reply" type="submit" name="submit" value="" />
		<div style="clear:both;"></div>
	</form>
</div>
<?php include_once 'inc/foothtml.php';?>
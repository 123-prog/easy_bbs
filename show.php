<?php
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
include_once 'inc/page.php';
$link=connect();
$css[]='show.css';
$title="帖子页";
$login_id=isLogin($link); 
if(empty($_GET['id'])||!is_numeric($_GET['id'])){
	skip('index.php', 'error', 'id参数出错');
}
else{
	$sql="select * from content where id={$_GET['id']}";
	$result_content=execute($link, $sql);
	if(mysqli_num_rows($result_content)==0){
		skip('index.php', 'error', 'id参数不存在');
	}
	$content=mysqli_fetch_assoc($result_content);
	$content['title']=htmlspecialchars($content['title']);
	$content['content']=nl2br(htmlspecialchars($content['content']));
	
	$sql="update content set times={$content['times']}+1 where id={$_GET['id']}";
	execute($link, $sql);
	
	$sql="select * from son_module where id={$content['module_id']}";
	$result_son=execute($link, $sql);
	$son=mysqli_fetch_assoc($result_son);
	
	$sql="select * from father_module where id={$son['father_module_id']}";
	$result_father=execute($link, $sql);
	$father=mysqli_fetch_assoc($result_father);
	
	$sql="select * from member where id={$content['member_id']}";
	$result_member=execute($link, $sql);
	$member=mysqli_fetch_assoc($result_member);
	
	$sql="select count(*) from reply where content_id={$_GET['id']}";
	$count_reply=getnum($link, $sql);
	
	$page_per=7;
	$page=page($count_reply,$page_per , 5);
}

?>
<?php include_once 'inc/headhtml.php';?>
<div id="position" class="auto">
	 <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $father['id']?>"><?php echo $father['module_name']?></a> &gt; <a href="list_son.php?id=<?php echo $son['id']?>"><?php echo $son['module_name']?></a> &gt; <span><?php echo $content['title'];?></span>
</div>
<div id="main" class="auto">
	<div class="wrap1">
		<div class="pages">
			<?php echo $page['html'];?>
		</div>
		<a class="btn reply" href="reply.php?id=<?php echo $content['id']?>"></a>
		<div style="clear:both;"></div>
	</div>
	<?php if(!isset($_GET['page']) || $_GET['page']==1){?>
	<div class="wrapContent">
		<div class="left">
			<div class="face">
				<a  href="member.php?id=<?php echo $member['id'];?>">
					<img width=120px; height=120px; src="<?php if($member['photo']!=""){echo $member['photo'];}else{echo 'style/photo.jpg';}?>" />
				</a>
			</div>
			<div class="name">
				<a href=""><?php echo $member['name']?></a>
			</div>
		</div>
		<div class="right">
			<div class="title">
				<h2><?php echo $content['title']?></h2>
				<span>阅读：<?php echo $content['times']?>&nbsp;|&nbsp;回复：<?php echo $count_reply;?></span>
				<div style="clear:both;"></div>
			</div>
			<div class="pubdate">
				<span class="date">发布于：<?php echo $content['time']?> </span>
				<span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
			</div>
			<div class="content">
				 <?php echo $content['content']?>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php }?>
	<?php 
	$sql="select * from reply where content_id={$_GET['id']} order by id {$page['limit']}";
	$result_reply=execute($link, $sql);
	$f=1+($_GET['page']-1)*$page_per;
	while($reply=mysqli_fetch_assoc($result_reply)){
		$reply['content']=nl2br(htmlspecialchars($reply['content']));
		$sql="select * from member where id={$reply['member_id']}";
		$result_reply_member=execute($link, $sql);
		$reply_member=mysqli_fetch_assoc($result_reply_member);
	?>
	<div class="wrapContent">
		<div class="left">
			<div class="face">
				<a  href="member.php?id=<?php echo $reply_member['id'];?>">
					<img width=120px; height=120px; src="<?php if($reply_member['photo']!=""){echo $reply_member['photo'];}else{echo 'style/photo.jpg';}?>" />
				</a>
			</div>
			<div class="name">
				<a href=""><?php echo $reply_member['name']; ?></a>
			</div>
		</div>
		<div class="right">
			
			<div class="pubdate">
				<span class="date">回复时间：<?php echo $reply['time']; ?></span>
				<span class="floor"><?php echo $f++;?>楼&nbsp;|&nbsp;<a href="quote.php?id=<?php echo $content['id'];?>&rid=<?php echo $reply['id'];?>">引用</a></span>
			</div>
			<div class="content">
			<?php if($reply['quote_id']!=0){
			$sql="select count(*) from reply where content_id={$_GET['id']} and id<={$reply['quote_id']}";
			$floor=getnum($link, $sql);
			
			$sql="select * from reply where id={$reply['quote_id']}";
			$result_quote_reply=execute($link, $sql);
			$quote_reply=mysqli_fetch_assoc($result_quote_reply);
			$quote_reply['content']=nl2br(htmlspecialchars($quote_reply['content']));
			
			$sql="select * from member where id={$quote_reply['member_id']}";
			$result_quote_member=execute($link, $sql);
			$quote_member=mysqli_fetch_assoc($result_quote_member);
			?>
				<div class="quote">
					<h2>引用 <?php echo $floor;?>楼 <?php echo $quote_member['name'];?> 发表的: </h2>
					<?php echo $quote_reply['content'];?>
				</div>
			<?php }?>
				<div class="content">
					<?php echo $reply['content'];?>
				</div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php }?>
	<div class="wrap1">
		<div class="pages">
			<?php echo $page['html'];?>
		</div>
		<a class="btn reply" href="reply.php?id=<?php echo $content['id']?>"></a>
		<div style="clear:both;"></div>
	</div>
</div>




<?php include_once 'inc/foothtml.php';?>
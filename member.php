<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
include_once 'inc/page.php';
$link=connect();
$css[]='list.css';
$css[]='member.css';
$title="会员信息页";
$login_id=isLogin($link);
if(empty($_GET['id'])||!is_numeric($_GET['id'])){
	skip('index.php', 'error', 'id参数出错');
}
else{
	$sql="select * from member where id={$_GET['id']}";
	$result_member=execute($link, $sql);
	if(!mysqli_num_rows($result_member)){
	    skip('index.php', 'error', 'id对应会员信息不存在');
	}
	$member=mysqli_fetch_assoc($result_member);
	
	$sql="select count(*) from content where member_id={$_GET['id']}";
	$count_content=getnum($link, $sql);
	
	$page_per=7;
	$page=page($count_content,$page_per , 5);
	
	$sql="select * from content where member_id={$_GET['id']} {$page['limit']}";
	$result_content=execute($link, $sql);
	
	
	
}

?>
<?php include_once 'inc/headhtml.php';?>
	<div id="position" class="auto">
		<a href="index.php">首页</a> &gt; <?php echo $member['name']." 的会员中心";?>
	</div>
	<div id="main" class="auto">
		<div id="left">
			<ul class="postsList">
				<?php 
				while($content=mysqli_fetch_assoc($result_content)){
					$content['title']=htmlspecialchars($content['title']);
					
					$sql="select time from reply where content_id={$content['id']} order by id desc limit 0,1";
					$result_last_time=execute($link, $sql);
					if(mysqli_num_rows($result_last_time))
					{
						$reply_last_time=mysqli_fetch_assoc($result_last_time);
						$last_time=$reply_last_time['time'];
					}
					else{
						$last_time="暂无回复";
					}
					
					$sql="select count(*) from reply where content_id={$content['id']}";
					$count_reply=getnum($link, $sql);
					
				?>
				<li>
					<div class="smallPic">
							<img width="45" height="45" src="<?php if($member['photo']!=""){echo $member['photo'];}else{echo 'style/photo.jpg';}?>" />
					</div>
					<div class="subject">
						<div class="titleWrap"><h2><a href="show.php?id=<?php echo $content['id'];?>"><?php echo $content['title'];?></a></h2></div>
						<p>
							<?php 
							if($login_id==$content['member_id']){
								$delete_url=urlencode("content_delete.php?id={$content['id']}&url={$_SERVER['REQUEST_URI']}");
								$message=urlencode($content['title']);
								$return=urlencode($_SERVER['REQUEST_URI']);
								$url="confirm.php?message={$message}&url={$delete_url}&return={$_SERVER['REQUEST_URI']}";
								echo "<a href='content_update.php?id={$content['id']}'>编辑</a> | <a href='{$url}'>删除</a>";
							}
							?>
							最后回复：<?php echo $last_time;?>
						</p>
					</div>
					<div class="count">
						<p>
							回复<br /><span><?php echo $count_reply;?></span>
						</p>
						<p>
							浏览<br /><span><?php echo $content['times'];?></span>
						</p>
					</div>
					<div style="clear:both;"></div>
				</li>
				<?php }?>
			</ul>
			<div class="pages">
				<?php echo $page['html'];?>
			</div>
		</div>
		<div id="right">
			<div class="member_big">
				<dl>
					<dt>
						<img width="180" height="180" src="<?php if($member['photo']!=""){echo $member['photo'];}else{echo 'style/photo.jpg';}?>" />
					</dt>
					<dd class="name"><?php echo $member['name'];?></dd>
					<dd>帖子总计：<?php echo $count_content;?></dd>
					<?php 
					if($login_id==$member['id']){
						echo "<dd>--<a href='member_photo.php?id={$_GET['id']}'>修改头像</a> </br> --<a href=''>修改密码</a></dd>";
					}
					?>
				</dl>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
<?php include_once 'inc/foothtml.php';?>

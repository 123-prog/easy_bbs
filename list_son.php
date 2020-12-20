<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
include_once 'inc/page.php';
$link=connect();
$css[]='list.css';
$title="子版块页";
$login_id=isLogin($link);
if(empty($_GET['id'])||!is_numeric($_GET['id'])){
	skip('index.php', 'error', 'id参数出错');
}
else{
	$sql="select * from son_module where id={$_GET['id']}";
	$result_son=execute($link, $sql);
	$son=mysqli_fetch_assoc($result_son);
	
	$sql="select * from father_module where id={$son['father_module_id']}";
	$result_father=execute($link, $sql);
	$father=mysqli_fetch_assoc($result_father);
	
	$sql="select count(*) from content where module_id={$son['id']}";
	$count_all=getnum($link, $sql);
	$sql="select count(*) from content where module_id={$son['id']} and time>CURDATE()";
	$count_today=getnum($link, $sql);
	
	$page=page($count_all, 7, 5);
	
	$sql="select y.title,y.member_id,z.name,y.id,y.time,y.times,z.photo
	from content y,member z where y.module_id={$son['id']} and y.member_id=z.id {$page['limit']}";
	$result_content=execute($link, $sql);
	
	
}
?>
<?php include_once 'inc/headhtml.php';?>
<div id="position" class="auto">
		 <a href="index.php">首页</a> &gt; <a href="<?php echo "list_father.php?id={$father['id']}";?>"><?php echo $father['module_name'];?></a> &gt; <?php echo $son['module_name'];?>
	</div>
	<div id="main" class="auto">
		<div id="left">
			<div class="box_wrap">
				<h3><?php echo $son['module_name'];?></h3>
				<div class="num">
				    今日：<span><?php echo $count_today;?></span>&nbsp;&nbsp;&nbsp;
				    总帖：<span><?php echo $count_all;?></span>
				</div>
				<div class="moderator">版主：
					<span>
					<?php 
					if($son['member_id']=="0"){
						echo "暂无版主";
					}
					else{
						$sql="select * from member where id={$son['member_id']}";
						$result_member=execute($link, $sql);
						$member=mysqli_fetch_assoc($result_member);
						echo $member['name'];
					}
					?>
					</span>
				</div>
				<div class="notice">介绍:<?php echo htmlspecialchars($son['info']);?></div>
				<div class="pages_wrap">
					<a class="btn publish" href="publish.php?sid=<?php echo $son['id'];?>"></a>
					<div class="pages">
					<?php 
					echo $page['html'];
					?>	
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div style="clear:both;"></div>
			<ul class="postsList">
			<?php 
			while($content=mysqli_fetch_assoc($result_content)){
				$sql="select count(*) from reply where content_id={$content['id']}";
				$count_reply=getnum($link, $sql);

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
			?>
				<li>
					<div class="smallPic">
						<a href="member.php?id=<?php echo $content['member_id'];?>">
							<img width="45" height="45"src="<?php if($content['photo']!=""){echo $content['photo'];}else{echo 'style/photo.jpg';}?>">
						</a>
					</div>
					<div class="subject">
						<div class="titleWrap"><h2><a  href="show.php?id=<?php echo $content['id'];?>"><?php echo htmlspecialchars($content['title'])?></a></h2></div>
						<p>
							楼主：<?php echo $content['name']?>&nbsp;<?php echo $content['time']?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time;?>
						</p>
					</div>
					<div class="count">
						<p>
							回复<br /><span><?php echo $count_reply;?></span>
						</p>
						<p>
							浏览<br /><span><?php echo $content['times']?></span>
						</p>
					</div>
					<div style="clear:both;"></div>
				</li>
			<?php }?>
			</ul>
			<div class="pages_wrap">
					<a class="btn publish" href="publish.php?sid=<?php echo $son['id'];?>"></a>
				<div class="pages">
					<?php echo $page['html'];?>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div id="right">
			<div class="classList">
				<div class="title">版块列表</div>
				<ul class="listWrap">
				<?php 
				$sql="select * from father_module";
				$result_father=execute($link, $sql);
				while($father=mysqli_fetch_assoc($result_father)){
				?>
					<li>
						<h2><a href="list_father.php?id=<?php echo $father['id']?>"><?php echo $father['module_name']?></a></h2>
						<ul>
						<?php 
						$sql="select * from son_module where father_module_id={$father['id']}";
						$result_son=execute($link, $sql);
						while($son=mysqli_fetch_assoc($result_son)){
						?>
							<li><h3><a href="list_son.php?id=<?php echo $son['id']?>"><?php echo $son['module_name']?></a></h3></li>
						<?php }?>
						</ul>
					</li>
				<?php }?>
				</ul>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>


<?php include_once 'inc/foothtml.php';?>
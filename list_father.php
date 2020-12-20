<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
include_once 'inc/page.php';
$link=connect();
$css[]='list.css';
$title="父版块页";
$login_id=isLogin($link);
if(empty($_GET['id'])||!is_numeric($_GET['id'])){
	skip('index.php', 'error', 'id参数出错');
}
else{
	$sql="select * from father_module where id={$_GET['id']}";
	$result_father=execute($link, $sql);
	if(!mysqli_num_rows($result_father)){
		skip('index.php', 'error', '版块id不存在');
	}
	else{
		$father=mysqli_fetch_assoc($result_father);
		$son_id="";
		$son_module="";
		$sql="select * from son_module where father_module_id={$father['id']}";
		$result_son=execute($link, $sql);
		while($son=mysqli_fetch_assoc($result_son)){
			$son_id=$son_id.$son['id'].",";
			$son_module.="<a href='list_son.php?id={$son['id']}'>{$son['module_name']}&nbsp;&nbsp;</a>";
		}
		if($son_id==""){
			$son_id="0";
		}
		$son_id=trim($son_id,",");
		$sql="select count(*) from content where module_id in({$son_id})";
		$count_all=getnum($link, $sql);
		$sql="select count(*) from content where module_id in({$son_id}) and time>CURDATE()";
		$count_today=getnum($link, $sql);
	}
}
?>
<?php include_once 'inc/headhtml.php';?>
	<div id="position" class="auto">
		 <a href="index.php">首页</a> &gt; <?php echo $father['module_name'];?>
	</div>
	<div id="main" class="auto">
		<div id="left">
			<div class="box_wrap">
				<h3><?php echo $father['module_name'];?></h3>
				<div class="num">
				    今日：<span><?php echo $count_today;?></span>&nbsp;&nbsp;&nbsp;
				    总帖：<span><?php echo $count_all;?></span>
				  <div class="moderator"> 子版块： <?php echo $son_module;?></div>
				</div>
				<div class="pages_wrap">
					<a class="btn publish" href="publish.php?fid=<?php echo $father['id'];?>"></a>
					<div class="pages">
					<?php 
					$page=page($count_all, 7, 5);
					echo $page['html'];
					?>	
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div style="clear:both;"></div>
			<ul class="postsList">
			<?php 
			$sql="select x.id sid,x.module_name,y.id id,y.member_id,y.title,z.name,y.time,y.times,z.photo
			from son_module x,content y,member z where y.module_id in({$son_id}) and y.module_id=x.id and y.member_id=z.id {$page['limit']}";
			$result_content=execute($link, $sql);
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
						<div class="titleWrap"><a href="list_son.php?id=<?php echo $content['sid'];?>">[<?php echo $content['module_name']?>]</a>&nbsp;&nbsp;<h2><a href="show.php?id=<?php echo $content['id'];?>"><?php echo htmlspecialchars($content['title'])?></a></h2></div>
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
				<a class="btn publish" href="publish.php?fid=<?php echo $father['id'];?>"></a>
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
							<li><h3><a href="list_son.php?id=<?php echo $son['id'];?>"><?php echo $son['module_name']?></a></h3></li>
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
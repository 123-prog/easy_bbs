<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
$link=connect();
$css[]='index.css';
$title="首页";
$login_id=isLogin($link);
?>
<?php include_once 'inc/headhtml.php';?>
<div id="hot" class="auto">
	<div class="title">热门动态</div>
	<ul class="newlist">
		<!-- 20条 -->
		<li><a href="#">[库队]</a> <a href="#">私房库实战项目录制中...</a></li>
	</ul>
	<div style="clear:both;"></div>
</div>
<?php 
$sql="select * from father_module order by sort";
$father_result=execute($link, $sql);
while($father=mysqli_fetch_assoc($father_result)){
?>
<div class="box auto">
	<div class="title">
		<a href="list_father.php?id=<?php echo $father['id'];?>"><?php echo $father['module_name'];?></a>
	</div>
	<div class="classList">
	<?php 
	$sql="select * from son_module where father_module_id={$father['id']} order by sort";
	$son_result=execute($link, $sql);
	if(mysqli_num_rows($son_result)){
		while($son=mysqli_fetch_assoc($son_result)){
			$sql="select count(*) from content where module_id={$son['id']} and time > CURDATE()";
			$count_today=getnum($link, $sql);
			$sql="select count(*) from content where module_id={$son['id']}";
			$count_all=getnum($link, $sql);
			$html=<<<A
			<div class="childBox new">
				<h2><a href="list_son.php?id={$son['id']}">{$son['module_name']}</a> <span>(今日:{$count_today})</span></h2>
				帖子数量:{$count_all}<br />
			</div>	
A;
			echo $html;
		}
		echo "<div style='clear:both;'></div>";
	}
	else{
		echo "<div style='padding:10px 0;'>暂无子版块...</div>";
	}
	?>
	</div>
</div>
<?php }?>
<?php include_once 'inc/foothtml.php';?>
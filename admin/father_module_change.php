<?php 
include_once '../inc/config.php';
include_once '../inc/mysqli.php';
include_once '../tool/skip.php';
$title='修改父板块';
$link=connect();
$module_name_error=$sort_error="";
$module_name=$sort="";
if(empty($_GET['id'])||!is_numeric($_GET['id'])){
	skip('father_module.php','error','id参数错误');
}
else {
	$sql="select * from father_module where id={$_GET['id']}";
	$result=execute($link, $sql);
	if(!mysqli_num_rows($result)){
		skip('father_module.php','error','不存在的id');
	}
	$info=mysqli_fetch_assoc($result);
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$checkflag='change';
	include_once 'inc/father_check.php'; 
	if(empty($module_name_error)&&empty($sort_error)){
		$sql="update father_module set module_name='{$module_name}',sort='{$sort}' where id={$_GET['id']}";
		execute($link, $sql);
		if(mysqli_affected_rows($link)==1){
			skip('father_module.php','ok','修改成功');
		}
	}	
}
?>
<?php include_once 'inc/headhtml.php';?>
<div id="main">
<div class="title" style="margin-bottom:20px;">修改父版块-<?php echo $info['module_name'];?></div>
<form method="post" action="">
<table class="au">
		<tr>
			<td>新的版块名称</td>
			<td><input type="text" name="module_name" value="<?php echo $info['module_name'];?>"/></td>
			<td>
				<span style="color:red;">*<?php echo $module_name_error;?></span>
			</td>
		</tr>
		<tr>
			<td>新的排序(数字,默认0)</td>
			<td><input type="text" name="sort" value="<?php echo $info['sort'];?>"/></td>
			<td>
				<span style="color:red;">*<?php echo $sort_error;?></span>
			</td>
		</tr>
	</table>
	<input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="修改">
</form>
</div>
<?php include_once 'inc/foothtml.php';?>
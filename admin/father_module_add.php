<?php 
include_once '../inc/config.php';
include_once '../inc/mysqli.php';
include_once '../tool/skip.php';
$title='添加父板块';
$link=connect();
$module_name_error=$sort_error="";
$module_name=$sort="";
//过滤
if($_SERVER['REQUEST_METHOD']=="POST"){
		$checkflag='add';
		include_once 'inc/father_check.php';
		//插入
		if(empty($module_name_error)&&empty($sort_error)){
		$sql="insert into father_module(module_name,sort) values('{$module_name}',{$sort})";
		execute($link, $sql);
		if(mysqli_affected_rows($link)==1){
			skip('father_module.php','ok','添加成功');
		}
	}	
}
?>
<?php include_once 'inc/headhtml.php';?>
<div id="main">
<div class="title" style="margin-bottom:20px;">添加父版块</div>
<form method="post" action="">
<table class="au">
		<tr>
			<td>父版块名称</td>
			<td><input type="text" name="module_name" value="#"/></td>
			<td>
				<span style="color:red;">*<?php echo $module_name_error;?></span>
			</td>
		</tr>
		<tr>
			<td>排序(数字,默认0)</td>
			<td><input type="text" name="sort" value="0"/></td>
			<td>
				<span style="color:red;">*<?php echo $sort_error;?></span>
			</td>
		</tr>
	</table>
	<input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="添加">
</form>
</div>
<?php include_once 'inc/foothtml.php';?>
<?php 
include_once '../inc/config.php';
include_once '../inc/mysqli.php';
include_once '../tool/skip.php';
$title='添加子板块';
$link=connect();
$father_module_id_error=$member_id_error=$module_name_error=$info_error=$sort_error="";
$father_module_id=$member_id=$module_name=$info=$sort="";
if($_SERVER['REQUEST_METHOD']=="POST"){
	$checkflag='add';
	include_once 'inc/son_check.php';
	//插入
	if(empty($father_module_id_error)&&empty($member_id_error)&&empty($module_name_error)&&empty($info_error)&&empty($sort_error)){
		$sql="insert into son_module(father_module_id,member_id,module_name,info,sort) values($father_module_id,$member_id,'$module_name','$info',$sort)";
		execute($link, $sql);
		if(mysqli_affected_rows($link)==1){
			skip('son_module.php','ok','添加成功');
		}
	}
}
?>
<?php include_once 'inc/headhtml.php';?>
<div id='main'>
<div class="title" style="margin-bottom:20px;">添加子版块</div>
<form method="post" action="">
<table class="au">
		<tr>
			<td>所属父板块</td>
			<td>
				<select name="father_module_id">
					<option value="null">----请选择所属的父板块----</option>
					<?php 
					$sql="select * from father_module";
					$result=execute($link, $sql);
					while($data=mysqli_fetch_assoc($result)){
						echo "<option value='{$data['id']}'>{$data['module_name']}</option>";
					}
					?>
				</select>
			</td>
			<td>
				<span style="color:red;"><?php echo $father_module_id_error;?></span>
			</td>
		</tr>
		<tr>
			<td>版主</td>
			<td>
				<select name="member_id">
					<option value="0">----请选择板块的版主----</option>
					<?php 
					
					?>
				</select>
			</td>
			<td>
				<span style="color:red;">*<?php echo $member_id_error;?></span>
			</td>
		</tr>
		<tr>
			<td>板块名称</td>
			<td><input type="text" name="module_name" value=""/></td>
			<td>
				<span style="color:red;">*<?php echo $module_name_error;?></span>
			</td>
		</tr>
		<tr>
			<td>简介(255字符以内）</td>
			<td>
			<textarea name="info"></textarea>
			</td>
			<td>
				<span style="color:red;">*<?php echo $info_error;?></span>
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
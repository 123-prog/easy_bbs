<?php 
include_once '../inc/config.php';
include_once '../inc/mysqli.php';
include_once '../tool/skip.php';
$title='子板块信息';
$link=connect();
$sort_error="";
if($_SERVER['REQUEST_METHOD']=="POST"){
	$postS=$_POST['sort'];
	foreach($postS as $key=>$val){
		if(empty($val) && $val!="0"){
			$sort_error="存在空排序";
		}
		elseif(!is_numeric($val)){
			$sort_error="存在非法排序，排序应该是个数字";
		}
	}
	if(empty($sort_error)){
		foreach($postS as $key=>$val){
			$sql[]="update son_module set sort={$val} where id={$key}";
		}
		if(execute_multi($link,$sql,$error)){
			skip('son_module.php', 'ok', '修改成功');
		}
		else{
			skip('son_module.php','error',$error);
		}
	}
}
?>
<?php include_once 'inc/headhtml.php';?>
<div id="main">
<div class="title" style="margin-bottom:20px;">子版块列表</div>
<form method="post">
	<table class="list">
		<tr>
			<th>排序</th>	 	 	
			<th>版块名称</th>
			<th>版主</th>
			<th>所属父版块</th>
			<th>操作</th>
		</tr>

<?php 
$sql='SELECT a.sort sort,a.module_name module_name,a.id id,member_id,b.module_name father_module_name from son_module a,father_module b where a.father_module_id=b.id order by b.id';
$result=execute($link, $sql);
while($array=mysqli_fetch_assoc($result)){
$delete_url=urlencode("delete_son_module.php?id={$array['id']}&url={$_SERVER['REQUEST_URI']}");
$message=urlencode($array['module_name']);
$return=urlencode($_SERVER['REQUEST_URI']);
$url="confirm.php?message={$message}&url={$delete_url}&return={$_SERVER['REQUEST_URI']}";
$html=<<<a
	   <tr>
		   <td><input class="sort" type="text" name="sort[{$array['id']}]" value="{$array['sort']}" /></td>
		   <td>{$array['module_name']}[id:{$array['id']}]</td>
		   <td>{$array['member_id']}</td>
		   <td>{$array['father_module_name']}</td>
		   <td><a href="#">[访问]</a>&nbsp;&nbsp;<a href="son_module_change.php?id={$array['id']}">[编辑]</a>&nbsp;&nbsp;<a href="{$url}">[删除]</a></td>
	   </tr>
a;
echo $html;
}
close($link);
?>
	</table>
	<input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="修改" />
	&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;"><?php echo $sort_error;?></span>
</form>
</div>

<?php include_once 'inc/foothtml.php';?>
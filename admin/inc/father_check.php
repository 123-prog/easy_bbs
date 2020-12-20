<?php 
if(empty($_POST['module_name'])){
	$module_name_error="模块的名称不得为空";
}
elseif(mb_strlen($_POST['module_name'])>50){
	$module_name_error="模块的名称不得超过50个字符";
}
else{
	$_POST=escape($link,$_POST);    //转义操作
	switch($checkflag){
		case 'add':
			$sql="select * from father_module where module_name='{$_POST['module_name']}'";
			$result=execute($link, $sql);
			if(mysqli_num_rows($result)){
			$module_name_error="模块已经存在";
			}		
			else{
			$module_name=$_POST['module_name'];
			}
			break;
		case 'change':
			$sql="select * from father_module where module_name='{$_POST['module_name']}'&& id!={$_GET['id']}";
			$result=execute($link, $sql);
			if(mysqli_num_rows($result)){
				$module_name_error="模块名称已经存在";
			}
			else{
				$module_name=$_POST['module_name'];
			}
			break;	
		default:
			skip('father_module.php','error','checkflag参数错误');
	}
}
if(empty($_POST['sort']) && $_POST['sort']!="0"){
	$sort_error="排序不得为空";
}
elseif(!is_numeric($_POST['sort'])){
	$sort_error="排序应该输入一个数字";
}
else{
	$sort=$_POST['sort'];
}
?>
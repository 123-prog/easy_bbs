<?php 
//父板块id的验证
if($_POST['father_module_id']=="null"){
	$father_module_id_error="请选择一个板块作为该版块的父版块";
}
elseif(!is_numeric($_POST['father_module_id'])){
	$father_module_id_error="父版块数据存在未知的错误，请刷新重试";
}
else{
	$sql="select * from father_module where id={$_POST['father_module_id']}";
	$result=execute($link, $sql);
	if(mysqli_num_rows($result)==0){
		$father_module_id_error="此父版块不存在，请刷新重试";
	}
	else{
		switch($checkflag){
			case "add":
				$father_module_id=$_POST['father_module_id'];
				break;
			case "change":
				$sql="select * from son_module where father_module_id={$_POST['father_module_id']}&&module_name='{$_POST['module_name']}'&&id!={$_GET['id']}";
				$result=execute($link, $sql);
				if(mysqli_num_rows($result)){
					$father_module_id_error="父版块下存在同名子版块，请修改";
				}
				else{
					$father_module_id=$_POST['father_module_id'];
				}
				break;
			default:
				skip('son_module.php','error','checkflag参数错误');
		}
	}
}
//版主id的过滤 之后再做

$member_id=$_POST['member_id'];

//版块名称的验证
if(empty($_POST['module_name'])){
	$module_name_error="版块的名称不得为空";
}
elseif(mb_strlen($_POST['module_name'])>50){
	$module_name_error="版块的名称不得超过50个字符";
}
else{
	$_POST['module_name']=escape($link,$_POST['module_name']);    //转义操作
	switch($checkflag){
		case 'add':
			$sql="select * from son_module where module_name='{$_POST['module_name']}'&&father_module_id={$_POST['father_module_id']}";
			$result=execute($link, $sql);
			if(mysqli_num_rows($result)){
			$module_name_error="该父版块下已有同名子版块";
			}		
			else{
			$module_name=$_POST['module_name'];
			}
			break;
		case 'change':     //之后再做
			$sql="select * from son_module where module_name='{$_POST['module_name']}'&& id!={$_GET['id']}&&father_module_id={$_POST['father_module_id']}";
			$result=execute($link, $sql);
			if(mysqli_num_rows($result)){
				$module_name_error="父版块下存在同名子版块,请修改";
			}
			else{
				$module_name=$_POST['module_name'];
			}
			break;	
		default:
			skip('son_module.php','error','checkflag参数错误');
	}
}
//版块简介的验证
if(empty($_POST['info'])){
	$info_error="版块的简介不能为空";
}
elseif(mb_strlen($_POST['info'])>255){
	$info_error="版块的简介不能超过255个字符";
}
else{
	$info=escape($link, $_POST['info']);
}
//排序变量的过滤
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
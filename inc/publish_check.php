<?php 
//所属子版块id的验证
if($_POST['module_id']=='null'){
	$module_id_error="请选择一个版块";
}
elseif(empty($_POST['module_id'])||!is_numeric($_POST['module_id'])){
	$module_id_error="版块数据错误，请刷新重试";
}
else{
	$sql="select * from son_module where id={$_POST['module_id']}";
	$result=execute($link, $sql);
	if(!mysqli_num_rows($result)){
		$module_id_error="版块数据错误，请刷新重试";
	}
}
//标题验证（100字符）
if(empty($_POST['title'])){
	$title_error="标题不得为空";
}
elseif(mb_strlen($_POST['title'])>100){
	$title_error="标题不得超过100个字符";
}
?>
<?php 
header("Content-type:text/html;charset=utf-8;");
function upload($path,$file_max_size,$alltype=array('jpg','jpeg','gif','png')){
	//判断文件大小
	$ini_max=ini_get('upload_max_filesize');
	$ini_bytes=getbytes(substr($ini_max, -1), substr($ini_max, 0,-1));
	$set_bytes=getbytes(substr($file_max_size, -1), substr($file_max_size, 0,-1));
	
	if($set_bytes>$ini_bytes){
		$file['error']="设置的上传文件大小上限超过了配置文件中的文件大小上限";
		$file['return']=false;
		return $file;
	}
	//判断文件类型
	$info=pathinfo($_FILES['file']['name']);
	if(!isset($info['extension'])){
		$info['extension']='';
	}
	$upload_ext=$info['extension'];
	if(!in_array($upload_ext, $alltype)){
		$file['error']="上传文件类型不允许";
		$file['return']=false;
		return $file;
	}
	//判断上传文件过程是否出现错误
	$arr_errors=array(
			1=>'上传的文件超过了 php.ini中 upload_max_filesize 选项限制的值',
			2=>'上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值',
			3=>'文件只有部分被上传',
			4=>'没有文件被上传',
			6=>'找不到临时文件夹',
			7=>'文件写入失败'
	);
	
	if(!isset($_FILES['file']['error'])){
		$file['error']="上传发生错误，请刷新重试";
		$file['return']=false;
		return $file;
	}elseif($error=$_FILES['file']['error']){
		$file['error']=$arr_errors[$error];
		$file['return']=false;
		return $file;
	}
	
	//构造上传目录与文件名
	if(!file_exists($path)){
		if(!mkdir($path,0777,true)){
			$file['error']="上传目录不存在，程序自动创建失败，请检查权限或者自行创建";
			$file['return']=false;
			return $file;
		}
	}
	
	$filename=str_replace('.', '', uniqid(mt_rand(1000, 9999),true)).".".$upload_ext;
	
	if(!is_uploaded_file($_FILES['file']['tmp_name'])){
		$file['error']="上传文件方式不合法";
		$file['return']=false;
		return $file;
	}
	$newpath=rtrim($path,'/').'/'.$filename;
	if(!move_uploaded_file($_FILES['file']['tmp_name'], $newpath)){
		$file['error']="上传文件失败，请检查相应目录的权限";
		$file['return']=false;
		return $file;
	}
	$file['save_path']=$newpath;
	$file['return']=true;
	return $file;
}
function getbytes($a,$b){ //$a为单位，$b为数字部分
	switch(strtoupper($a)){
		case 'K':
			return 1024*$b;
		case 'M':
			return 1024*1024*$b;
		case 'G':
			return 1024*1024*$b;
		default:
			return -1;
	}
}
?>

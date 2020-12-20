<?php 
include_once 'config.php';
//数据库连接
function connect($host=SQL_HOST,$user=SQL_USER,$password=SQL_PASSWORD,$database=SQL_DATABASE,$port=SQL_PORT){
	$link=@mysqli_connect($host, $user, $password, $database, $port);
	if(mysqli_connect_errno()){
		exit(mysqli_connect_error());
	}
	mysqli_set_charset($link, 'utf8');
	return $link;
}
//执行一条sql语句
function execute($link,$sql){
	$result=mysqli_query($link, $sql);
	if(mysqli_errno($link)){
		exit(mysqli_error($link));
	}
	return $result;
}
//执行一条sql语句，返回bool类型值
function execute_bool($link,$sql){
	$result=mysqli_real_query($link, $sql);
	if(mysqli_errno($link)){
		exit(mysqli_error($link));
	}
	return $result;
}
//执行多条sql语句
/*
 一次性执行多条SQL语句
$link：连接
$arr_sqls：数组形式的多条sql语句
$error：传入一个变量，里面会存储语句执行的错误信息
使用案例：
$arr_sqls=array(
		'select * from sfk_father_module',
		'select * from sfk_father_module',
		'select * from sfk_father_module',
		'select * from sfk_father_module'
);
var_dump(execute_multi($link, $arr_sqls,$error));
echo $error;
*/
function execute_multi($link,$arr_sqls,&$error){
	$sqls=implode(';',$arr_sqls).';';
	if(mysqli_multi_query($link,$sqls)){
		$data=array();
		$i=0;//计数
		do {
			if($result=mysqli_store_result($link)){
				$data[$i]=mysqli_fetch_all($result);
				mysqli_free_result($result);
			}else{
				$data[$i]=null;
			}
			$i++;
			if(!mysqli_more_results($link)) break;
		}while (mysqli_next_result($link));
		if($i==count($arr_sqls)){
			return $data;
		}else{
			$error="sql语句执行失败：<br />&nbsp;数组下标为{$i}的语句:{$arr_sqls[$i]}执行错误<br />&nbsp;错误原因：".mysqli_error($link);
			return false;
		}
	}else{
		$error='执行失败！请检查首条语句是否正确！<br />可能的错误原因：'.mysqli_error($link);
		return false;
	}
}
//获取记录数
function getnum($link,$sql){
	$result=execute($link, $sql);
	$count=mysqli_fetch_row($result);
	return $count[0];
}
//对sql语句进行转义
function escape($link,$sql){
	if(is_string($sql))
	{
		return mysqli_real_escape_string($link, $sql);
	}
	if(is_array($sql)){
		foreach($sql as $key=>$val){
			$sql[$key]=escape($link,$val);
		}
		return $sql;
	}
}
//关闭连接
function close($link){
	mysqli_close($link);
}

?>
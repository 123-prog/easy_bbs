<?php
function isLogin($link){
	if(isset($_COOKIE['member']['name'])&&isset($_COOKIE['member']['pw'])){
		$name=escape($link, $_COOKIE['member']['name']);
		$pw=escape($link, $_COOKIE['member']['pw']);
		$sql="select * from member where name='{$name}' and sha1(pw)='{$pw}'";
		$result=execute($link, $sql);
		if(mysqli_num_rows($result)){
			$data=mysqli_fetch_assoc($result);
			return $data['id'];
		}	
		else{
			return false;
		}
	}
	else{
		return false;
	}
}
?>
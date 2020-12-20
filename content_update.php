<?php
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
$css[]='publish.css';
$title="修改帖子信息";
$link=connect();
$login_id=isLogin($link);
if($login_id){
	if(!isset($_GET['id'])||!is_numeric($_GET['id'])){
		skip("member_id?id={$login_id}", 'error', 'id参数错误');
	}else{
		$sql="select * from content where id={$_GET['id']}";
		$result=execute($link, $sql);
		if(mysqli_num_rows($result)){
			$content=mysqli_fetch_assoc($result);
			if($content['member_id']!=$login_id){
				skip("member.php?id={$login_id}", 'error', '您无权操作他人的帖子');
			}
		}else{
			skip("member.php?id={$login_id}", 'error', 'id不存在');
		}
	}
	$module_id_error=$title_error="";
	if($_SERVER['REQUEST_METHOD']=="POST"){
		include_once 'inc/publish_check.php';
		if(empty($module_id_error)&&empty($title_error)){
			$_POST=escape($link, $_POST);
			$sql="update content set module_id={$_POST['module_id']},title='{$_POST['title']}',content='{$_POST['content']}' where id={$_GET['id']}";
			if(execute_bool($link, $sql)){
				skip("member.php?id={$login_id}", 'ok', '修改成功');
			}
			else{
				skip("member.php?id={$login_id}",'error','修改失败，请刷新重试');
			}
		}	
	}
}
else{
	skip('login.php', 'error', '请先登录');
}
?>
<?php include_once 'inc/headhtml.php';?>
	<div id="position" class="auto">
		 <a href="index.php">首页</a> &gt; 修改帖子信息
	</div>
	<div id="publish">
		<form method="post">
			<select name="module_id">
				<option value='null'>请选择一个版块</option>
				<?php 
				if(isset($_GET['fid'])&&is_numeric($_GET['fid'])){
				   $sql="select * from father_module where id={$_GET['fid']}";
				}
				else{
					$sql="select * from father_module";
				}
				$result1=execute($link, $sql);
				while($father=mysqli_fetch_assoc($result1)){
					echo "<optgroup label='{$father['module_name']}'>";
					$sql="select * from son_module where father_module_id={$father['id']}";
					$result2=execute($link, $sql);
					while($son=mysqli_fetch_assoc($result2)){
						if($son['id']==$content['module_id']){
							echo "<option selected='selected' value='{$son['id']}'>{$son['module_name']}</option>";
						}
						else{
						 	echo "<option value='{$son['id']}'>{$son['module_name']}</option>";
						}
						
					}
					echo "</optgroup>";
				}
				?>
			</select>
			<span style="color:red;">*<?php echo $module_id_error?></span>
			<input class="title" placeholder="请输入标题" name="title" type="text" value="<?php echo htmlspecialchars($content['title'])?>" /><span style="color:red;">*<?php echo $title_error?></span>
			<textarea name="content" class="content" ><?php echo htmlspecialchars($content['content'])?></textarea>
			<input class="publish" type="submit" name="submit" value="" />
			<div style="clear:both;"></div>
		</form>
	</div>
<?php include_once 'inc/foothtml.php';?>

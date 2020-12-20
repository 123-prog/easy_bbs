<?php 
include_once 'inc/config.php';
include_once 'inc/mysqli.php';
include_once 'tool/skip.php';
include_once 'tool/isLogin.php';
$css[]='publish.css';
$title="发帖";
$link=connect();
if($login_id=isLogin($link)){
	$module_id_error=$title_error="";
	if($_SERVER['REQUEST_METHOD']=="POST"){
		include_once 'inc/publish_check.php';
		if(empty($module_id_error)&&empty($title_error)){
			$_POST=escape($link, $_POST);
			$sql="insert into content(module_id,title,content,time,member_id) values({$_POST['module_id']},'{$_POST['title']}','{$_POST['content']}',now(),{$login_id})";
			execute($link, $sql);
			if(mysqli_affected_rows($link)==1){
				skip('index.php', 'ok', '发布成功');
			}
			else{
				skip('publish.php','error','发布失败，请刷新重试');
			}
		}	
	}
}
else{
	skip('login.php', 'error', '只有会员才能发布帖子,请先登录');
}
?>
<?php include_once 'inc/headhtml.php';?>
	<div id="position" class="auto">
		 <a href="index.php">首页</a> &gt; <a>发布帖子</a>
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
						if(isset($_GET['sid'])&&$_GET['sid']==$son[id]){
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
			<input class="title" placeholder="请输入标题" name="title" type="text" /><span style="color:red;">*<?php echo $title_error?></span>
			<textarea name="content" class="content"></textarea>
			<input class="publish" type="submit" name="submit" value="" />
			<div style="clear:both;"></div>
		</form>
	</div>
<?php include_once 'inc/foothtml.php';?>

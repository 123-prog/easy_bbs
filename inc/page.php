<?php 
/*
 * $num_all:要显示的数据总数
 * $num_per:一页需要显示的数据数
 * $btn_num:分页的按钮数量
*/
function page($num_all,$num_per,$btn_num){
	if($num_all==0){
		$_GET['page']=1;
		$array=array(
			"html"=>"<span>暂无</span>",
			"limit"=>""
		);
		return $array;
	}
	$pages=ceil($num_all/$num_per); //总页数
	if(!isset($_GET['page'])||$_GET['page']<1||!is_numeric($_GET['page'])){
		$_GET['page']=1;
	}
	if($_GET['page']>$pages){
		$_GET['page']=$pages;
	}
    $limit1=$num_per*($_GET['page']-1);
    $limit2=$num_per;
    $limit="limit {$limit1},{$limit2}";
    
    $cur_url=$_SERVER['REQUEST_URI'];
    $url_array=parse_url($cur_url);
    $url_path=$url_array['path'];
    if(isset($url_array['query'])){
    	parse_str($url_array['query'],$output);
    	unset($output['page']);
    	if(empty($output)){
    		$url="{$url_path}?page=";
    	}
    	else{
    		$other=http_build_query($output);
    		$url="{$url_path}?{$other}&page=";
    	}
    }
    else{
    	$url="{$url_path}?page=";
    }
    if($btn_num>=$pages){
    	for($i=1;$i<=$pages;$i++){
    		if($i==$_GET['page']){
    			$html[$i]="<span>{$i}</span> ";
    		}
    		else{
    			$html[$i]="<a href='{$url}{$i}'>{$i}</a> ";
    		}
    	}
    }
    else{
    	$start_page=$_GET['page']-floor(($btn_num-1)/2);
    	if($start_page<1){
    		$start_page=1;
    	}
    	if($start_page+($btn_num-1)>$pages){
    		$start_page=$pages-$btn_num+1;
    	}
    	for($i=0;$i<$btn_num;$i++){
    		if($start_page==$_GET['page']){
    			$html[$start_page]="<span>{$start_page}</span>";
    		}
    		else{
    			$html[$start_page]="<a href='{$url}{$start_page}'>{$start_page}</a>";
    		}
    		$start_page++;
    	}
    	if($btn_num>2){
    	reset($html);
    	$start=key($html);
    	end($html);
    	$end=key($html);
    	if($start>1){
    		array_shift($html);
    		array_unshift($html, "<a href='{$url}1'>1...</a>");
    	}
    	if($end<$pages){
    		array_pop($html);
    		array_push($html, "<a href='{$url}{$pages}'>{$pages}...</a>");
    	}
    	}
    }
    $last_page=$_GET['page']-1;
    $next_page=$_GET['page']+1;
    if($last_page<1){
    	$last_page=1;
    }
    if($next_page>$pages){
    	$next_page=$pages;
    }
    if($_GET['page']!=1){
    	array_unshift($html, "<a href='{$url}{$last_page}'><<</a>");
    }
    if($_GET['page']!=$pages){
    	array_push($html, "<a href='{$url}{$next_page}'>>></a>");
    }
    $string=implode($html," ");

    $array=array(
    	"limit"=>$limit,
    	"html"=>$string	
    );
    return $array;
} 
?>
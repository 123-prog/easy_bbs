<?php 
function vcode(){
	header('Content-type:image/jpeg');
	$img=imagecreatetruecolor(120, 40);
	$array=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
	$string='';
	for($i=0;$i<5;$i++){
		$string.=$array[rand(0,count($array)-1)];
	}
	$colorBg=imagecolorallocate($img, rand(200,255),rand(200,255),rand(200,255));
	$colorPx=imagecolorallocate($img, rand(100,200),rand(100,200),rand(100,200));
	$colorLine=imagecolorallocate($img, rand(100,200),rand(100,200),rand(100,200));
	$colorString=imagecolorallocate($img, rand(10,80),rand(10,80),rand(10,80));
	imagefill($img, 0, 0, $colorBg);
	for($i=0;$i<300;$i++){
		imagesetpixel($img, rand(0,119), rand(0,39),$colorPx);
	}
	for($i=0;$i<3;$i++){
		imageline($img, rand(0,60), rand(0,40), rand(60,120), rand(0,40),$colorLine);
	}
	imagettftext($img, 20, rand(-5,5), rand(5,15), rand(30,35), $colorString,'front/swissko.ttf',$string);
	imagejpeg($img);
	imagedestroy($img);
	return $string;
}
?>

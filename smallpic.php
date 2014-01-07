<?php
if(isset($_GET["newsno"])){
$pic_width = $_GET["width"];
$pic_hieght = $_GET["height"];
echo'<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>活動圖片</title>
</head>
<body leftmargin="0" topmargin="0"><table width="'.$pic_width.'" height="'.$pic_hieght.'" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle">';
	$cPic = $_GET['newsno'];
	/*echo '<script language="javascript">';
	if($_GET['width']>800)
		$imgwidth = 800;
	else
		$imgwidth = $_GET["width"];
	echo 'window.width = '.$imgwidth;
	echo '</script>';*/	
	if(isset($_GET["width"]))
		$DestWidth = $_GET['width'];
	else
		$DestWidth = 500;
	echo '<img src="smallpic.php?pic='.$cPic.'&width='.$DestWidth.'>"';
	
echo'</td></tr></table></body></html>';
}
if(isset($_GET["pic"])){
	$PicFile = './upload_image/'.$_GET["pic"];
	// ŪJ, POɬ JPG  GIF
	if(strpos($PicFile,'.gif')>0){
		$OriginalPic = imagecreatefromgif($PicFile);
		$imgtype = "gif";
	}else{
		$OriginalPic = imagecreatefromjpeg($PicFile);
		$imgtype = "jpg";
	}
	$aImageInfo = getimagesize($PicFile);
	$NewPic = $OriginalPic;
	if(isset($_GET["width"])) $DestWidth = $_GET['width']; else $DestWidth = 500;
	$DestHeight = $DestWidth;

	// ϼej DestWidth , Y
	if($aImageInfo[0]>$DestWidth){
		$NewWidth = $DestWidth;
		$NewHeight = intval($aImageInfo[1]*$NewWidth/$aImageInfo[0]);
		$NewPic = imagecreatetruecolor($NewWidth,$NewHeight);
		imagecopyresized($NewPic,$OriginalPic,0,0,0,0,$NewWidth,$NewHeight,$aImageInfo[0],$aImageInfo[1]); 
	}
	if($NewHeight>$DestHeight){
		$NewHeight = $DestHeight;
		$NewWidth = intval($aImageInfo[0]*$NewHeight/$DestHeight);
		$NewPic = imagecreatetruecolor($NewWidth,$NewHeight);
		imagecopyresized($NewPic,$OriginalPic,0,0,0,0,$NewWidth,$NewHeight,$aImageInfo[0],$aImageInfo[1]); 
	}
	if($imgtype=="gif"){
		header("Content-type: image/gif");
		imagegif($NewPic); 
	}else{
		header("Content-type: image/jpeg");
		imagejpeg($NewPic); 
	}
	imagedestroy($NewPic);
	imagedestroy($OriginalPic);
}
?>
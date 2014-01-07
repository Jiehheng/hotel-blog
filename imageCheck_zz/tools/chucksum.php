<?php
session_start();
if($_SESSION["s_checksum"]) unset($_SESSION["s_checksum"]);
$_SESSION['s_checksum']=auto_checksum(6);
//===================�H�����ͱK�X(L3�~�̱K�X�۰ʲ���)====================
function auto_checksum($len){
	$UpdateKey_a=array("2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z");
	for($i=0;$i<$len;$i++){
		$run=rand(0,count($UpdateKey_a)-1);
		$UpdateKey.=$UpdateKey_a[$run];
	}
	return $UpdateKey;
}

function set_counter_img($sum){ 
	for($i=0;$i < strlen($sum ) ;$i++){
		$src ="../images/". substr($sum,$i,1) .".png";		//�����
		$srcSize = getImageSize($src);
		$srcImage = ImageCreateFromPNG($src);
		$null="../images/null.png";	//�ť�
		$nullImage = ImageCreateFromPNG($null);
		if($i==0){
			$destSize[0]=$srcSize[0] * strlen($sum) ;
			$destSize[1]=$srcSize[1];			
			$rcImage=ImageCreate($destSize[0],$destSize[1]);
			$white=imageColorAllocate($rcImage,255,255,255);
			$black=imageColorAllocate($rcImage,0,0,255);
			for($j=0;$j<5;$j++)
				Imagearc($rcImage,rand(0,$destSize[0]),rand(0,$destSize[1]),rand(0,$destSize[0]*2),rand(0,$destSize[1]*2),0,360,$black);	
		}		
		//ImageCopyResized($rcImage, $srcImage, $srcSize[0] * $i + rand(0,3), rand(0,$srcSize[0]/4), 0, 0,rand($srcSize[0]/4 * 3 ,$srcSize[0]),rand($srcSize[1]/4 * 3,$srcSize[1]),$srcSize[0],$srcSize[1]);
		ImageCopyResampled($rcImage, $srcImage, $srcSize[0] * $i + rand(0,3), rand(0,$srcSize[0]/4), 0, 0,rand($srcSize[0]/4 * 3 ,$srcSize[0]),rand($srcSize[1]/4 * 3,$srcSize[1]),$srcSize[0],$srcSize[1]);
		imagedestroy($srcImage);
	}
	return imagePng($rcImage);
}

echo set_counter_img($_SESSION['s_checksum']);
?>
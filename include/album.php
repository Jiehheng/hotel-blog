<?php
if(isset($_GET["action"]))
{
	//以下為相片資料
	if (isset($_GET["action"]))
	{
		$actioncode = $_GET["action"];
		$rs = $db->Execute("SELECT * FROM `blog_action` WHERE `actioncode`='".$actioncode."'");
		$rs_array = $db->fetch_array($rs);
		$rs_array_content = $rs_array["content"];
		$rs_array_a_pic = $rs_array["a_pic"];
		$RecCount = substr_count($rs_array_a_pic,'###@@@###');
		$f_pic = explode("###@@@###",$rs_array_a_pic);
		if($f_pic[0])
		{
			$j='0';
			for($i=0;$i<$RecCount+1;$i++)
			{
				if (substr_count($f_pic[$i],'-ns)')==0)
				{
					$g_pic = explode("|||@@@|||",$f_pic[$i]);
					if(isset($g_pic[0])) $img_url = 'upload_image/'.$g_pic[0];else $img_url = 'images/no-product.gif';
					$list["list"] .= '
					<div id="hotel_photo" class="albun"> <a href="./upload_image/'.$g_pic[0].'" rel="lightbox['.$actioncode.']"><img src="'.$img_url.'" width="180" height="140" alt="hotel_photo"></a>
					<div class="albun_text"><a href="./upload_image/'.$g_pic[0].'" rel="lightbox['.$actioncode.']">'.$g_pic[1].'<br />'.$g_pic[3].'</a>
					</div></div>';
					$j=$j+1;
				}
			}
		}
		if($rs) $db->Execute("UPDATE `blog_action` SET `click` = `click`+1 WHERE `actioncode` = '{$actioncode}'");
	}
}
else Fail("查無相關相簿!");
?>
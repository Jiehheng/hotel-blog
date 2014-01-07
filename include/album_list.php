<?php
if(isset($_GET["page"])) $nPage = $_GET["page"]; else $nPage = 1;
$rsActions = $db->Execute("SELECT * FROM `blog_action` WHERE `cust_sid` = '".$cUrl_sid."' ORDER BY `actiondate` DESC");
$RecCount = $db->num_rows($rsActions);
if(($RecCount % 9)==0) $TotalPage = intval($RecCount/9); else $TotalPage = intval($RecCount/9) + 1;
$nRow = ($nPage-1)*9;
if($RecCount) $db->data_seek($rsActions, $nRow);
for($y=0;$y<9;$y++)
{
	if($rsActions_array = $db->fetch_array($rsActions))
	{
		$f_pic=explode("###@@@###",$rsActions_array['a_pic']);
		$g_pic=eregi_replace("-","","$f_pic[0]");
		$g_pic=explode("|||@@@|||",$g_pic);
		//if(DetectCharset($rsActions_array["title"])<>'UTF-8') $rsActions_array_title = big5_utf8_encode($rsActions_array["title"]); else $rsActions_array_title = $rsActions_array["title"];
		$rsActions_array_title = $rsActions_array["title"];
		$click = ($rsActions_array["click"] ? $rsActions_array["click"] : "0");
		if(isset($g_pic[2])) $img_url = 'upload_image/'.$g_pic[2];else $img_url = 'images/no-product.gif';
		$list["list"] .= '<div id="hotel_photo" class="albun"> <a href="?'.$cUrl.'&amp;album&action='.$rsActions_array["actioncode"].'"><img src="'.$img_url.'" width="180" height="140" alt="hotel_photo"></a>
		<div class="albun_text"><a href="?'.$cUrl.'&amp;album&action='.$rsActions_array["actioncode"].'">'.$rsActions_array_title.'</a><br>
		  <span class="gray">相片 ('.count($f_pic).') | 人氣('.$click.')</span></div>
		</div>';
	}
}
if($RecCount>10){
	$list["page"] .= "<center>";
	if($nPage>1)
		$list["page"] .= '<a class="style2" href="./?'.$cUrl.'&album_list&page=1>第一頁</a>&nbsp;<a class="style2" href=./?'.$cUrl.'&album_list&page='.($nPage-1).'">上一頁</a>&nbsp;';
	for($x=1;$x<=$TotalPage;$x++)
	{	if($nPage==$x)
			$list["page"] .= '<font style="color: red;outline-color:black"><strong>'.$nPage."</strong></font>&nbsp;";
		else
			$list["page"] .= '<a class="style2" href="./?'.$cUrl.'&album_list&page='.$x.'">'.$x.'</a>&nbsp;';
	}
	if($nPage<$TotalPage)
	$list["page"] .= '<a class="style2" href="./?'.$cUrl.'&album_list&page='.($nPage+1).'">下一頁</a>&nbsp;<a class="style2" href="./?'.$cUrl.'&album_list&page='.$TotalPage.'">最後一頁</a>';
	$list["page"] .= "</center>";
}
?>
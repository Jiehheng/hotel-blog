<?php
$TEXT_SQL = "SELECT * FROM `blog_action` WHERE `cust_sid` = '".$CustUsed."' ORDER BY `actiondate` DESC, `actioncode` DESC";
/*if($u_rule=='1' || $u_rule=='2'){
	$TEXT_SQL = "SELECT * FROM blog_action ORDER BY actiondate DESC, actioncode DESC";
}else{
	$TEXT_SQL = "SELECT blog_action.*,user.user_name FROM blog_action INNER JOIN user ON (blog_action.content=user.user_id) WHERE blog_action.content = '".$_SESSION["admin"]."' ORDER BY blog_action.actioncode";
}*/
if(!$rsNews=mysql_query($TEXT_SQL)) Fail("連線資料庫失敗 請稍後重新執行");
	
if($RecCount = mysql_num_rows($rsNews))
{
	if(!isset($_GET["page"])) $nPage = 1; else $nPage = $_GET["page"];
	if(($RecCount % 20)==0) $TotalPage = intval($RecCount/20); else $TotalPage = intval($RecCount/20) + 1;
	$nRow = ($nPage-1)*20;
	if($RecCount)mysql_data_seek($rsNews, $nRow);
	for($x=1;$x<21;$x++)
	{
		if($rs_array=mysql_fetch_array($rsNews))
		{
			$f_pic=explode("###@@@###",$rs_array['a_pic']);
			$g_pic=eregi_replace("-","","$f_pic[0]");
			$g_pic=explode("|||@@@|||",$g_pic);
			$rs_array_title = $rs_array["title"];
			if(isset($g_pic[2])) $img_url = 'upload_image/'.$g_pic[2];else $img_url = 'images/no-product.gif';
			$list["list"] .= '<div id="hotel_photo" class="albun"> <a href="./?'.$cUrl.'&album&id='.$rs_array["actioncode"].'"><img src="'.$img_url.'" width="180" height="140" alt="hotel_photo"></a>
			<div class="albun_text"><a href="./?'.$cUrl.'&album&id='.$rs_array["actioncode"].'">'.$rs_array_title.'</a><br>
			  <span class="gray">相片 ('.count($f_pic).') | &nbsp;(&nbsp;'.substr($rs_array["actiondate"],0,10).'&nbsp;)</span></div>
			</div>';
	}}
	//$nPage						//目前筆數
	$allPage=$TotalPage;			//總筆數
	
	$Target = $nPage % 10;			//0
	$Targeta = floor($nPage / 10);	//1
	if($Target=='0')$Targeta-=1;	//1->0
	$Targetb = $Targeta * 10 +1;	//11
	$Targetc = ($Targeta+1) * 10;	//20
	if($Targetc>=$allPage)$Targetc=$allPage;	//78
	$scriptpage='?'.$cUrl.'&album_list&page=';
	$list["btn_1"] = $scriptpage.$Targetb-1;
	if($nPage<='10') $list["btn_2"] = 'style="display:none"';
	$list["btn_3"] = $scriptpage.$nPage-1;
	if($nPage<='1') $list["btn_4"] = 'style="display:none"';
	for($x=$Targetb;$x<=$Targetc;$x++){
		if($x==$nPage) $list["btn_5"] = '&nbsp;<a href="'.$scriptpage.$x.'"><u><b>'.$x.'</b></u></a>';
		else $list["btn_5"] = '&nbsp;<a href="'.$scriptpage.$x.'"><span class="style16">'.$x.'</span></a>';
	}
	$list["btn_6"] = $scriptpage.$nPage+1;
	if($nPage>=$allPage) $list["btn_7"] = 'style="display:none"';
	$list["btn_8"] = $scriptpage.$Targetc+1;
	if($Targetc>=$allPage) $list["btn_9"] = 'style="display:none"';
	$list["btn_10"] = $RecCount;
}
else
{
	echo'<div align="left"><hr size="1" width="950" color="#EEEEEE"></div><div align="center"><span class="style2">目前尚未建立任何相簿!</span><div align="left"><hr size="1" width="950" color="#EEEEEE"></div>
	<div align="center"><input type="image" width="60" height="22" align="baseline" border="0" src="./images/p9_banner6.jpg" name="submit2" value="submit" onclick="javascript:window.open(\'./?'.$cUrl.'&album&id=new\',\'_self\')" /></div>';
}
?>
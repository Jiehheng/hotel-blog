<?php
// 文章分類
$rs_d=$db->Execute("select `serial_id`,`caption` from `sys_bulletin_type` order by `sort`");
if ($rs_d)
{
	$list["menu"] = '<select name="post_type" id="post_type" onchange="return go_lnk(this);"><option value="0">分類選取</option>';
	while ($rows_v = $db->fetch_assoc($rs_d))
	{
		if($_GET["type"]==$rows_v["serial_id"]) $seled = ' selected';
		$list["menu"] .= '<option value="./?'.$cUrl.'&blog_list&type='.$rows_v["serial_id"].'"'.$seled.'>'.$rows_v["caption"].'</option>';
		unset($seled);
	}
	$list["menu"] .= '</select>';
}
else die('資料庫發生錯誤');


if($_GET["type"])
{
	$rs_type = $db->Execute("SELECT `caption` FROM `sys_bulletin_type` WHERE `serial_id` = '".$_GET["type"]."'");
	if($db->num_rows($rs_type)){ $Data_type = $db->fetch_array($rs_type); $list["type"] = $Data_type[0]; }
	$sql_ext = "`news_typ` = '".$_GET["type"]."' AND ";
}
else $list["type"] = '全部文章';

$Site_sid = $_SESSION["Site_sid"];
$rs=$db->Execute("SELECT * FROM `sys_bulletin` WHERE {$sql_ext}`cust_sid` = '".$Site_sid."' ORDER BY `news_time` DESC");
if(!$rs) Fail("連線資料庫失敗 請稍後重新執行");

// 設置分頁
$evenPage = 5;			// 每頁幾筆
$Countrs = $db->num_rows($rs);
if(!isset($_GET["page"])) $nPage = 1; else $nPage = $_GET["page"];
if($Countrs)
{
	if (($Countrs % $evenPage)==0) $TotalPage = intval($Countrs/$evenPage); else $TotalPage = intval($Countrs/$evenPage) + 1;
	$nRow = ($nPage-1)*$evenPage;
	$db->data_seek($rs, $nRow);
}

$pgif = '?'.$cUrl.'&blog_article';
//if(isset($_GET["type"])) $pgif .= '&type='.$_GET["type"];
//if(isset($_GET["page"])) $pgif .= '&page='.$_GET["page"];
for($x=0;$x<$evenPage;$x++)
{
	if($rs_array = $db->fetch_array($rs))
	{
		if($rs_array["hyplnk"]) $lnkis = '&nbsp;-&nbsp;<a href="http://'.$rs_array["hyplnk"].'" target="_blank">外部連結</a>'; else $lnkis = '';
		if(isset($rs_array["serial_id"])) $t_index_sid = $rs_array["serial_id"];
		if(isset($rs_array["title"])) $t_index_title = $rs_array["title"];
		$list["list"] .= '
		<div id="hotel_photo" class="photo4"><img src="img/hotel_photo.jpg" width="180" height="140" alt="hotel_photo"></div>
		<span class="style1"><a href="javascript:void(0)" onClick="javascript:window.open(\'./'.$pgif.'&id='.$rs_array["serial_id"].'\',\'_self\')" title="發佈時間:&nbsp;'.$rs_array["news_code"].'">'.$t_index_title.'&nbsp;'.$lnkis.'</a></span><br />
		'.cut_content($rs_array["content"],200).'
		<hr />';
	}
}
// 分頁表格區塊
//$nPage				// 目前筆數
$allPage = $TotalPage;	// 總筆數
$Target = $nPage % $evenPage;			//0
$Targeta = floor($nPage / $evenPage);	//1
if($Target=='0')$Targeta-=1;	//1->0
$Targetb = $Targeta * $evenPage +1;	//11
$Targetc = ($Targeta+1) * $evenPage;	//20
if($Targetc>=$allPage)$Targetc=$allPage;	//78
if($_GET["type"]) $scripttype = '&type='.$_GET["type"];
$scriptpage = '?'.$cUrl.'&blog_list'.$scripttype.'&page=';
if(isset($_GET["page"]))$list["pg"].='<input type="hidden" name="page" id="page" value="'.$_GET["page"].'">';
$list["pg"].='
<table style="width:100%; margin:0;">
<tr>
  <td style="width:33%;text-align:center;">&nbsp;
	<a href="'.$scriptpage.($Targetb-1).'"><img src="images/up_ten.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage<='10')$list["pg"].='style="display:none"';$list["pg"].=' /></a>&nbsp;
	<a href="'.$scriptpage.($nPage-1).'"><img src="images/up.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage<='1')$list["pg"].='style="display:none"';$list["pg"].=' /></a>
  </td>
  <td style="width:34%;text-align:center;">';
	for($x=$Targetb;$x<=$Targetc;$x++){
		if($x==$nPage)$list["pg"].='&nbsp;<a href="'.$scriptpage.$x.'"><u><b>'.$x.'</b></u></a>';
		else $list["pg"].='&nbsp;<a href="'.$scriptpage.$x.'">'.$x.'</a>';
	}
  $list["pg"].='</td>
  <td style="width:33%;text-align:center;">&nbsp;
	<a href="'.$scriptpage.($nPage+1).'"><img src="images/down.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage>=$allPage)$list["pg"].='style="display:none"';$list["pg"].='/></a>&nbsp;
	<a href="'.$scriptpage.($Targetc+1).'"><img src="images/down_ten.gif" width="58" height="14" border="0" align="absmiddle" ';if($Targetc>=$allPage)$list["pg"].='style="display:none"';$list["pg"].='/></a>
  </td>
 </tr>
</table>';
?>
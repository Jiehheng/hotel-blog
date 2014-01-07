<?php
// 文章分類
$rs_d=$db->Execute("select `serial_id`,`caption` from `sys_bulletin_type` order by `sort`");
if ($rs_d)
{
	$typlist = '<select name="post_type" id="post_type" onchange="return go_lnk(this);"><option value="0">分類選取</option>';
	while ($rows_v = $db->fetch_assoc($rs_d))
	{
		if($_GET["type"]==$rows_v["serial_id"]) $seled = ' selected';
		$typlist .= '<option value="./?'.$cUrl.'&blog_list&type='.$rows_v["serial_id"].'"'.$seled.'>'.$rows_v["caption"].'</option>';
		unset($seled);
	}
	$typlist .= '</select>';
}
else die('資料庫發生錯誤');

if($_GET["type"])
{
	// 資料庫連接
	$TEXT_SQL = "SELECT * FROM `sys_bulletin` WHERE `news_typ` = '".$_GET["type"]."' AND `cust_sid` = '".$CustUsed."' ORDER BY `news_time` DESC";
	$rs=$db->Execute($TEXT_SQL);
	if(!$rs) Fail("連線資料庫失敗 請稍後重新執行");
	
	// 設置分頁
	$Countrs = $db->num_rows($rs);
	if(!isset($_GET["page"])) $nPage = 1; else $nPage = $_GET["page"];
	if($Countrs>0){
	if (($Countrs % 10)==0) $TotalPage = intval($Countrs/10); else $TotalPage = intval($Countrs/10) + 1;
		$nRow = ($nPage-1)*10;
		$db->data_seek($rs, $nRow);
	}
	
	$pgif = '?'.$cUrl.'&blog_list';
	if(isset($_GET["type"])) $pgif .= '&type='.$_GET["type"];
	if(isset($_GET["page"])) $pgif .= '&page='.$_GET["page"];
	for($x=0;$x<10;$x++)
	{
		if($rs_array = $db->fetch_array($rs))
		{
			if($rs_array["hyplnk"]) $lnkis = '&nbsp;-&nbsp;<a href="http://'.$rs_array["hyplnk"].'" target="_blank">外部連結</a>'; else $lnkis = '';
			if(isset($rs_array["serial_id"])) $t_index_sid = $rs_array["serial_id"];
			if(isset($rs_array["title"])) $t_index_title = $rs_array["title"];
			$list_blog .= '
			<div class="roundedRectangleContent">
				<div style="width:25px;float:left;">'.($x+1).'<input type="hidden" name="t_index_'.$x.'" id="t_index_'.$x.'" value="'.$t_index_sid.'"></div>
				<div style="width:370px;float:left;"><span style="cursor:pointer;" onClick="javascript:window.open(\'./'.$pgif.'&id='.$rs_array["serial_id"].'\',\'_self\')" title="發佈時間:&nbsp;'.$rs_array["news_code"].'">'.$t_index_title.'&nbsp;'.$lnkis.'</span></div>
				<div style="width:115px;float:right;text-align:center;">
				  <input name="t_state_'.$x.'" type="radio" value="o" ';if($rs_array["s_state"]=="o") $list_blog.= 'checked="checked"';$list_blog.='/>開 
				  <input name="t_state_'.$x.'" type="radio" value="c" ';if($rs_array["s_state"]=="c") $list_blog.= 'checked="checked"';$list_blog.='/>關 
				  <input name="t_state_'.$x.'" type="radio" value="d" />刪
				</div>
				<div style="width:50px;float:right;text-align:center;">'.$rs_array["click"].'</div>
				<div style="width:75px;float:right"><span style="color:#777777">'.$rs_array["news_time"].'</span></div>
				<div style="float:none;">&nbsp;</div>
			</div>';
		}
	}
	// 分頁表格區塊
	//$nPage						//目前筆數
	$allPage=$TotalPage;			//總筆數
	
	$Target = $nPage % 10;			//0
	$Targeta = floor($nPage / 10);	//1
	if($Target=='0')$Targeta-=1;	//1->0
	$Targetb = $Targeta * 10 +1;	//11
	$Targetc = ($Targeta+1) * 10;	//20
	if($Targetc>=$allPage)$Targetc=$allPage;	//78
	if($_GET["type"]) $scripttype = '&type='.$_GET["type"].'&';
	$scriptpage = '?'.$cUrl.'&blog_list'.$scripttype.'page=';
	if(isset($_GET["page"]))$list_pg.='<input type="hidden" name="page" id="page" value="'.$_GET["page"].'">';
	$list_pg.='
	<table style="width:100%; margin:0;">
	<tr>
	  <td style="width:33%;text-align:center;">&nbsp;
		<a href="'.$scriptpage.($Targetb-1).'"><img src="images/up_ten.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage<='10')$list_pg.='style="display:none"';$list_pg.=' /></a>&nbsp;
		<a href="'.$scriptpage.($nPage-1).'"><img src="images/up.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage<='1')$list_pg.='style="display:none"';$list_pg.=' /></a>
	  </td>
	  <td style="width:34%;text-align:center;">';
		for($x=$Targetb;$x<=$Targetc;$x++){
			if($x==$nPage)$list_pg.='&nbsp;<a href="'.$scriptpage.$x.'"><u><b>'.$x.'</b></u></a>';
			else $list_pg.='&nbsp;<a href="'.$scriptpage.$x.'">'.$x.'</a>';
		}
	  $list_pg.='</td>
	  <td style="width:33%;text-align:center;">&nbsp;
		<a href="'.$scriptpage.($nPage+1).'"><img src="images/down.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage>=$allPage)$list_pg.='style="display:none"';$list_pg.='/></a>&nbsp;
		<a href="'.$scriptpage.($Targetc+1).'"><img src="images/down_ten.gif" width="58" height="14" border="0" align="absmiddle" ';if($Targetc>=$allPage)$list_pg.='style="display:none"';$list_pg.='/></a>
	  </td>
	 </tr>
	</table>';
	
	if(isset($_GET["id"]))
	{
		$input_EditID='<input type="hidden" name="EditNewsid" id="EditNewsid" value="'.$_GET["id"].'">';
		if(!$rsPost=$db->fetch_array($db->Execute("SELECT serial_id,news_typ,title,content,hyplnk,news_time FROM `sys_bulletin` WHERE `serial_id` = '".$_GET["id"]."'"))) Fail("連線資料庫失敗 請稍後重新執行");

		$word1 = '修改';
		$word2 = $rsPost["title"];
		$word3 = '&nbsp;文章時間：<input type="text" name="news_time" value="'.$rsPost["news_time"].'" size="10" onclick="cal_location();cal_birth(document.postNews.news_time.value,\'news_time\');showDiv(\'span_cal\', true);">';
		if($rsPost["hyplnk"]) $word4 = ' checked';else $word5 = ' style="display:none"';
	}
	else
	{
		$word1 = '建立新';
		$word3 = '<input type="hidden" name="news_time" value="'.$rsPost["news_time"].'">';
		$word5 = ' style="display:none"';
	}
	
	$view["title"] = $word1.'文章：<input type="text" name="t_cht_n" size="55" value="'.$word2.'">&nbsp;<input name="hyplnk_switch" id="hyplnk_switch" type="checkbox" value="1"'.$word4.' style="vertical-align:text-bottom" onclick="change()" />使用外部連結<span name="aaa" id="aaa"'.$word5.'>&nbsp;<br />http://&nbsp;<input type="text" name="hyplnk" size="55" value="'.$rsPost["hyplnk"].'"></span>';
	$view["time"] = $word3.'<div id = "span_cal" style="position:absolute; z-index:100;"></div>';
	$view["text"] = $rsPost["content"];
		
	$typlist .= '&nbsp;<input name="new-obj" type="button" onClick="javascript:window.location.href=\'./?'.$cUrl.'&blog_list&type='.$_GET["type"].'\';" value="新增文章" {add}>';
}

$Tab_assign = "_m_blog_list";
?>
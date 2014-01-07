<?php
header("Cache-control: private");
if ($check != 'kingfor') exit;	
	
// 文章分類
$rs_d=$db->Execute("select serial_id,caption from `sys_bulletin_type` order by sort");
if ($rs_d)
{
	$typlist = '分類&nbsp;<select name="post_type" id="post_type" onchange="return go_lnk(this);"><option value="0">下拉選取</option>';
	while ($rows_v=mysql_fetch_assoc($rs_d))
	{
		if($_GET["type"]==$rows_v["serial_id"]) $seled = ' selected';
		$typlist .= '<option value="./?type='.$rows_v["serial_id"].'"'.$seled.'>'.$rows_v["caption"].'</option>';
		unset($seled);
	}
	$typlist .= '</select>';
}
else die('資料庫發生錯誤');

if($_GET["type"])
{
	$sqlstr = ' WHERE A.news_typ = \''.$_GET["type"].'\'';
	
	// 資料庫連接
	$TEXT_SQL = "SELECT A.*,B.name FROM `sys_bulletin` as A INNER JOIN `sys_account` as B ON (A.author=B.account){$sqlstr} ORDER BY A.news_time DESC";
	$rs=$db->Execute($TEXT_SQL);
	if(!$rs) Fail("連線資料庫失敗 請稍後重新執行");
	
	// 設置分頁
	$Countrs = $db->num_rows($rs);
	if(!isset($_GET["page"])) $nPage = 1; else $nPage = $_GET["page"];
	if($Countrs>0){
	if (($Countrs % 10)==0) $TotalPage = intval($Countrs/10); else $TotalPage = intval($Countrs/10) + 1;
		$nRow = ($nPage-1)*10;
		mysql_data_seek($rs, $nRow);
	}
	
	$pgif = '?';
	if(isset($_GET["type"])) $pgif .= 'type='.$_GET["type"];
	if(isset($_GET["page"])) $pgif .= '&page='.$_GET["page"];
	for($x=0;$x<10;$x++)
	{
		if($rs_array = mysql_fetch_array($rs))
		{
			if($rs_array["hyplnk"]) $lnkis = '&nbsp;-&nbsp;<a href="http://'.$rs_array["hyplnk"].'" target="_blank">外部連結</a>'; else $lnkis = '';
			$list.='<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
			  <th scope="row">'.($x+1).'<input type="hidden" name="t_index_'.$x.'" id="t_index_'.$x.'" size="1" value="';if(isset($rs_array["serial_id"])) $list.=$rs_array["serial_id"];$list.='"></th>
			  <td align="left"><span style="cursor:pointer;" onClick="javascript:window.open(\'./'.$pgif.'&id='.$rs_array["serial_id"].'\',\'_self\')" title="發佈時間:&nbsp;'.$rs_array["news_code"].'">';
			  if(isset($rs_array["title"])) $list.= $rs_array["title"];
			  $list.='&nbsp;<span style="color:#777777">('.$rs_array["news_time"].')</span>'.$lnkis.'</span></td>
			  <td align="center">'.$rs_array["name"].'</td>
			  <td align="center">';if(isset($rs_array["click"])) $list.= $rs_array["click"];$list.='</td>
			  <td align="center">
				<input name="t_state_'.$x.'" type="radio" value="o" ';if($rs_array["s_state"]=="o") $list.= 'checked="checked"';$list.='/>開&nbsp;
				<input name="t_state_'.$x.'" type="radio" value="c" ';if($rs_array["s_state"]=="c") $list.= 'checked="checked"';$list.='/>關&nbsp;
				<input name="t_state_'.$x.'" type="radio" value="d" />刪
			  </td>
			</tr>';
		}
	}
	// 分頁表格區塊
	$list.='<tr>
	  <td colspan="5" class="lvtCol" align="right">';
		//$nPage						//目前筆數
		$allPage=$TotalPage;			//總筆數
		
		$Target = $nPage % 10;			//0
		$Targeta = floor($nPage / 10);	//1
		if($Target=='0')$Targeta-=1;	//1->0
		$Targetb = $Targeta * 10 +1;	//11
		$Targetc = ($Targeta+1) * 10;	//20
		if($Targetc>=$allPage)$Targetc=$allPage;	//78
		if($_GET["type"]) $scripttype = 'type='.$_GET["type"].'&';
		$scriptpage='./?'.$scripttype.'page=';
		if(isset($_GET["page"]))$list.='<input type="hidden" name="page" id="page" value="'.$_GET["page"].'">';
		$list.='
		<table aborder="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td>&nbsp;
			<a href="'.$scriptpage.($Targetb-1).'"><img src="images/up_ten.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage<='10')$list.='style="display:none"';$list.=' /></a>&nbsp;
			<a href="'.$scriptpage.($nPage-1).'"><img src="images/up.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage<='1')$list.='style="display:none"';$list.=' /></a>
		  </td>
		  <td>';
			for($x=$Targetb;$x<=$Targetc;$x++){
				if($x==$nPage)$list.='&nbsp;<a href="'.$scriptpage.$x.'"><u><b>'.$x.'</b></u></a>';
				else $list.='&nbsp;<a href="'.$scriptpage.$x.'">'.$x.'</a>';
			}
		  $list.='</td>
		  <td>&nbsp;
			<a href="'.$scriptpage.($nPage+1).'"><img src="images/down.gif" width="58" height="14" border="0" align="absmiddle" ';if($nPage>=$allPage)$list.='style="display:none"';$list.='/></a>&nbsp;
			<a href="'.$scriptpage.($Targetc+1).'"><img src="images/down_ten.gif" width="58" height="14" border="0" align="absmiddle" ';if($Targetc>=$allPage)$list.='style="display:none"';$list.='/></a>
		  </td>
		 </tr>
		</table>
	  </td>
	</tr>';
	
	if(isset($_GET["id"]))
	{
		$input_EditID='<input type="hidden" name="EditNewsid" id="EditNewsid" value="'.$_GET["id"].'">';
		if(!$rsPost=mysql_fetch_array(mysql_query("SELECT serial_id,news_typ,title,content,hyplnk,author,news_time FROM `sys_bulletin` WHERE `serial_id` = '".$_GET["id"]."'"))) Fail("連線資料庫失敗 請稍後重新執行");

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
	$view.='
	<tr align="center">
	  <td align="left" bgcolor="#F8F8F8">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:9.5pt;">
		  <tr>
			<td align="left">&nbsp;'.$word1.'文章：<input type="text" name="t_cht_n" size="55" value="'.$word2.'">&nbsp;&nbsp;<input name="hyplnk_switch" id="hyplnk_switch" type="checkbox" value="1"'.$word4.' style="vertical-align:text-bottom" onclick="change()" />使用外部連結<span name="aaa" id="aaa"'.$word5.'>：http://&nbsp;<input type="text" name="hyplnk" size="55" value="'.$rsPost["hyplnk"].'"></span></td>
			<td align="right" valign="top">'.$word3.'<div id = "span_cal" style="position:absolute; z-index:100;"></div>
			</td>
		  </tr>
		</table></td>
	</tr>
	<tr>
	  <td>';
		if(isset($_GET["id"])){
			$thedate = $rsPost["content"];
			// $thedate = $thedate;
			$view.='<input type="hidden" name="Adv" value="'.$thedate.'" />';
		}else $view.='<input type="hidden" name="Adv">';
		$view.='
	  </td>
	</tr>';
	
	$typlist .= '&nbsp;<input name="new-obj" type="button" onClick="javascript:window.location.href=\'./?type='.$_GET["type"].'\';" value="新增文章" {add}>';
}
$xtpl->assign('add',$edit);
$xtpl->assign('name',$_SESSION['session_name']);
$xtpl->assign('type_listid',$_GET["type"]);
$xtpl->assign('type_list',$typlist);
$xtpl->assign('authority_list',$list);
$xtpl->assign('content_list',$view);
$xtpl->assign('RecCount',$Countrs);
$xtpl->assign('input_EditID',$input_EditID);
if($view) $xtpl->parse('main.table.editarea');
$xtpl->parse('main.table');
?>
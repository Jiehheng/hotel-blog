<?php
// 判斷系統預設日期,與輸入日期
if(isset($_GET["ly"]))$Thisyear = $_GET["ly"];else $Thisyear = date("Y");
if(isset($_GET["lm"]))$Thismonth = sprintf("%02d", $_GET["lm"]);else $Thismonth = date("m");
$Thisday = date("j");
$list["year"] = $Thisyear;
$list["month"] = $Thismonth;
//一月大　二月小　三月大　四月小　五月大　六月小　七月大　八月大　九月小　十月大　十一月小　十二月大
if($Thismonth=='2') $Month_range='28';elseif($Thismonth=='4' || $Thismonth=='6' || $Thismonth=='9' || $Thismonth=='11') $Month_range='30';else $Month_range='31';
//echo'本月('.$Thismonth.')共有'.$Month_range.'天<br><hr>';
//找本月1號是星期幾
$first_d = date('w', strtotime(date("$Thisyear-$Thismonth-1")));
$exc_day = $first_d;

// 偵測建立新月份 GET["create"].
extract($_POST);
/*foreach ($_POST as $k => $v){
	  echo "$k   =   $v<br />";
}*/
if($_POST["dowhat"]=="create")
{
	extract($_POST);
	if(!$rs = $db->Execute("SELECT `detail`,`price` FROM `sys_room` WHERE `serial_id` = '".$Product."'")) Fail("資料庫連接失敗");		// 基本房型
	if($db->num_rows($rs))
	{
		$rs_ar = $db->fetch_array($rs);
		$detail = unserialize($rs_ar["detail"]);
		$DefauleRoom = $detail[3];
	}
	else
	{
		if(!$rs = $db->Execute("SELECT `room_open`,`price` FROM `sys_onsale` WHERE `serial_id` = '".$Product."'")) Fail("資料庫連接失敗");	// 專案房型
		$rs_ar = $db->fetch_array($rs);
		$DefauleRoom = $rs_ar["room_open"];
	}
	$priceO = unserialize($rs_ar["price"]);
	for($i=0;$i<$Month_range;$i++)
	{
		if(!isset($Quene)) $Quene = array(($i+1) => array($DefauleRoom,$priceO[$exc_day],1)); else array_push($Quene , array($DefauleRoom,$priceO[$exc_day],1));
		if($exc_day<6) $exc_day++; else $exc_day=0;
	}
	
	$serial_id = md5(uniqid(rand(),true));
	$Quene = serialize($Quene);
	if(!$rs = $db->Execute("SELECT * FROM `sys_room_state` WHERE `room_sid` = '".$Product."' AND `Ryear` = '".$Thisyear."' AND `Rmonth` = '".$Thismonth."'")) Fail("資料庫連接失敗");
	if($db->num_rows($rs)==0)
	{
		if(!$rsPost = $db->Execute("INSERT INTO `sys_room_state` (`serial_id`,`room_sid`,`Ryear`,`Rmonth`,`Rtable`) VALUES ('".$serial_id."','".$Product."','".$Thisyear."','".$Thismonth."','".$Quene."')")) Fail("新增失敗! 請重新檢查");
	}
	/*foreach ($Quene as $k => $v){
		//echo "$k   =   $v<br />";
		foreach ($v as $u => $i){
			echo "$u   =   $i<br />";
		}
	}*/
}

// 專案列表
// 房型列表
/* 讀取相同模組global_php/module_onsale.php 並帶出$TargetID 與訂價 $Order_percent*/
require_once('global_php/module_onsale.php');

// 判斷資料庫內是否已存在此月資料
if(!$rs = $db->Execute("SELECT * FROM `sys_room_state` WHERE `room_sid` = '".$TargetID."' AND `Ryear` = '".$Thisyear."' AND `Rmonth` = '".$Thismonth."'")) Fail("資料庫連接失敗");
if($db->num_rows($rs))
{
	$rs_array = $db->fetch_array($rs);
	$RoomInfo = unserialize($rs_array["Rtable"]);
	
	if($Thismonth=='01') $show_month = '一月'; elseif($Thismonth=='02')$show_month = '二月'; elseif($Thismonth=='03')$show_month = '三月';  elseif($Thismonth=='04')$show_month = '四月';  elseif($Thismonth=='05')$show_month = '五月';  elseif($Thismonth=='06')$show_month = '六月';  elseif($Thismonth=='07')$show_month = '七月';  elseif($Thismonth=='08')$show_month = '八月';  elseif($Thismonth=='09')$show_month = '九月';  elseif($Thismonth=='10')$show_month = '十月';  elseif($Thismonth=='11')$show_month = '十一月';  elseif($Thismonth=='12')$show_month = '十二月'; 
	if($Thismonth=='01') $befmonth = '?month='.($Thisyear-1).'-12&ly='.($Thisyear-1).'&lm=12'; else $befmonth = '?month='.$Thisyear.'-'.str_pad(($Thismonth-1),2,0,STR_PAD_LEFT).'&ly='.$Thisyear.'&lm='.($Thismonth-1);
	if($Thismonth=='12') $nexmonth = '?month='.($Thisyear+1).'-01&ly='.($Thisyear+1).'&lm=1'; else $nexmonth = '?month='.$Thisyear.'-'.str_pad(($Thismonth+1),2,0,STR_PAD_LEFT).'&ly='.$Thisyear.'&lm='.($Thismonth+1);
	
	$list["area0"] .= '上個月&nbsp;│&nbsp;'.$show_month.'&nbsp;│&nbsp;下個月';
	$list["roomstatesid"] = $rs_array["serial_id"];
	
	/*$db = new db_action;
	
	if(!isset($_GET["page"])) $nPage = 1; else $nPage = $_GET["page"];
	if(!isset($_GET["month"])) $month = date("Y-m"); else $month = $_GET["month"];
	
	if($_GET["type"]=='self')
		if(!$rsNews=$db->Execute("SELECT * FROM month WHERE showtype = 'forself' AND showdate LIKE '".$month."%' ORDER BY showdate DESC, showtime DESC")) Fail('資料庫連線失敗');
	else
		if(!$rsNews=$db->Execute("SELECT * FROM month WHERE showtype = 'forall' AND showdate LIKE '".$month."%' ORDER BY showdate DESC, showtime DESC")) Fail('資料庫連線失敗');
	*/
	
	$z=0;
	for($x=1;$x<=$first_d;$x++)
	{
		$list["area1"] .= '<td style="text-align:center;border:1px solid #CCCCCC;">&nbsp;</td>';
		$z=$z+1;
	}
	for($x=1;$x<=$Month_range;$x++)
	{
		if($z%7==0) $list["area1"] .='</tr><tr>';
		if($z%7==6) $tdcolor = '#DDECE7'; elseif($z%7==0) $tdcolor = '#F5E2E7'; else $tdcolor = '#FFFFFF';
		if($Thisyear==date(Y) && $Thismonth==date(n) && $x==$Thisday) $chang1 = 'red';	else $chang1 = '#009966';
		
		$list["area1"] .= '
			<td height="60" ';if($Thisyear==date(Y) && $Thismonth==date(n) && substr_count($tempmonth,",".$x.",")>0) $list["area1"] .='bgcolor="'.$tdcolor.'"';$list["area1"] .=' style="text-align:center;border:1px solid #CCCCCC;background:'.$tdcolor.';">
			<div style="text-align:left; border-bottom:1px #999999 dashed; margin:2px;"><input name="Exe_day[]" type="checkbox" value="'.$x.'" /><span style="float:right;letter-spacing: 1.5pt;color:'.$chang1.';">'.$x.'日</span></div>
			<div style="text-align:center; margin:2px;">空房：'.$RoomInfo[$x][0].'&nbsp;/&nbsp;'.$RoomInfo[$x][0].'間<br />'.$RoomInfo[$x][1].' 元<br />訂金：'.round($RoomInfo[$x][1]*($Order_percent/100)).' 元
			</td>
		';
		//if(substr_count($tempmonth,",".$x.",")>0) $list["area1"] .='<a href="'.$tmpV.'&month='.$Thisyear.'-'.$Thismonth.'&day='.$x.'"><b>';
		//$list["area1"] .='<input name="" type="checkbox" value="" /><span style="letter-spacing: 1.5pt;color:'.$chang1.';">'.$x.'</span><br/>1間<br />';
		//if(substr_count($tempmonth,",".$x.",")>0) $list["area1"] .='</b></a>';
		$z=$z+1;
	}
}
else $list["area1"] = '<td colspan="7" bgcolor="#D2EAF2" style="text-align:center;padding:6px;">尚未建立本月份控房資料<br /><br /><span style="cursor:pointer;border:1px solid #CCCCCC;padding:6px;" onclick="create()">按此建立</span><br /><br /></td>';

?>
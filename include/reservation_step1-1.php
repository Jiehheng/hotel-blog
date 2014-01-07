<?php
// 專案列表
// 房型列表
/* 讀取相同模組global_php/module_onsale.php 並帶出$TargetID 與訂價 $Order_percent*/
require_once('global_php/module_onsale.php');

if(isset($_GET["ly"]))$Thisyear = $_GET["ly"];else $Thisyear = date("Y");
if(isset($_GET["lm"]))$Thismonth = sprintf("%02d", $_GET["lm"]);else $Thismonth = date("m");
$Thisday = date("j");
$list["year"] = $Thisyear;
$list["month"] = $Thismonth;
					
//一月大　二月小　三月大　四月小　五月大　六月小　七月大　八月大　九月小　十月大　十一月小　十二月大
if($Thismonth=='2') $Month_range='28';elseif($Thismonth=='4' || $Thismonth=='6' || $Thismonth=='9' || $Thismonth=='11') $Month_range='30';else $Month_range='31';

//找本月1號是星期幾
$first_d = date('w', strtotime(date("$Thisyear-$Thismonth-1")));
//echo'本月('.$Thismonth.')共有'.$Month_range.'天<br><hr>';

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
	$list["roomsid"] = $TargetID;
	$list["roomstatesid"] = $rs_array["serial_id"];
	
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
		if($RoomInfo[$x][0]=='0') $RoomInfo[$x][1] = '客服訂房';
		if($RoomInfo[$x][1]=='0' || !$RoomInfo[$x][1]) $HowMany = '客服訂房'; else $HowMany = $RoomInfo[$x][1].' 元';
		if($RoomInfo[$x][0])
		{
			for($r=1;$r<=$RoomInfo[$x][0];$r++)
			{
				$R_numsopt .= '<option value="'.$r.'">'.$r.'</option>';
			}
			$R_nums = '下訂：<select name="FTT['.$x.'#'.$RoomInfo[$x][1].']">'.$R_numsopt.'</select>';unset($R_numsopt);
			$R_chkb = '<input name="Exe_day[]" type="checkbox" value="'.$x.'#'.$RoomInfo[$x][1].'" />';
		}
		else
		{
			$R_nums = '客滿';
		}
		
		$list["area1"] .= '
			<td height="60" ';if($Thisyear==date(Y) && $Thismonth==date(n) && substr_count($tempmonth,",".$x.",")>0) $list["area1"] .='bgcolor="'.$tdcolor.'"';$list["area1"] .=' style="text-align:center;border:1px solid #CCCCCC;background:'.$tdcolor.';">
			<div style="text-align:left; border-bottom:1px #999999 dashed; margin:2px;">'.$R_chkb.'<span style="float:right;letter-spacing: 1.5pt;color:'.$chang1.';">'.$x.'日</span></div>
			<div style="text-align:center; margin:2px;">'.$R_nums.'<br />'.$HowMany.'
			</td>
		';
		// 尚有空房 $RoomInfo[$x][0].'&nbsp;/&nbsp;'.$RoomInfo[$x][0]
		// 訂金暫時拿掉 <br />訂金：'.round($RoomInfo[$x][1]*($Order_percent/100)).' 元
		$z=$z+1;
	}
	$list["next_msg"] = '<br /><input name="Sout" value="下一步" type="submit" />';
}
else $list["area1"] = '<td colspan="7" bgcolor="#D2EAF2" style="text-align:center;padding:6px;">尚未建立該月份相關控房資料<br /><br /></td>';
?>
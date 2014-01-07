<?php
extract($_POST);
if(!$f_date1 || !$f_date2) Fail("日期不明,無法查詢!");
if($f_date1>$f_date2){$tmp_date=$f_date2;$f_date2=$f_date1;$f_date1=$tmp_date;}
$list["f_date"] = $f_date1;
$list["l_date"] = $f_date2;

// 交錯在兩月之間.正規化先
$rule_fdate1 = strtotime(date("$f_date1"));
$rule_fdate2 = strtotime(date("$f_date2"));
$Fdate = explode ("-", $f_date1);
$Edate = explode ("-", $f_date2);
$ar_month = Array_unique(array($Fdate[1],$Edate[1]));
//echo "查詢日期由 : ".$f_date1." ~ ".$f_date2."　＃　開始月份(".date(m,$rule_fdate1).")總天數為 : ".date(t,$rule_fdate1)."　＃　結束月份(".date(m,$rule_fdate2).")總天數為 : ".date(t,$rule_fdate2)."<br />";

// 做出跨年判斷,跨單月判斷,其餘不接受
switch(abs(date(m,$rule_fdate2) - date(m,$rule_fdate1)))
{
	case 0:		// Single Month
	$Total_days = date(j,$rule_fdate2) - date(j,$rule_fdate1) + 1;
	break;
	
	case 1:		// Next Month
	case 11:	// Next Year
	$Total_days = date(t,$rule_fdate1) - date(j,$rule_fdate1) + 1 + date(j,$rule_fdate2);	// 本月所用日數最後一天減入住日+隔月天數=總天數
	break;
	
	default:	// Error Input
	Fail("日期選取不符,系統限制僅接受跨一個月!");
	break;
}
$list["total_days"] = $Total_days;
//echo "總天數 ： ".$Total_days."<br />";
for($x=0;$x<$Total_days;$x++) $Alldate[] = date("Y-m-d",mktime(0,0,0,$Fdate[1],$Fdate[2]+$x,$Fdate[0]));

function Roomstate($x)
{
	switch($x)
	{
		case 0:
		$state = '客服訂房';
		break;
		case 1:
		$state = '立即訂房';
		break;
		case 2:
		$state = '客滿';
		break;
		case 2:
		$state = '房價維護中';
		break;
		default:
		$state = '不開放訂房';
		break;
	}
	return $state;
}

function GetRoomSt($id)	// GET Room's Table State
{
	global $db,$Fdate,$Edate,$ar_month;
	
	if(count($ar_month)>1) $sql_ext = " OR  `Ryear` = '".$Edate[0]."' AND`Rmonth` = '".$Edate[1]."'";
	$SQL_Quene=$db->Execute("SELECT `serial_id`,`Rmonth`,`Rtable` FROM `sys_room_state` WHERE `room_sid` = '".$id."' AND ( `Ryear` = '".$Fdate[0]."' AND `Rmonth` = '".$Fdate[1]."' ".$sql_ext.")");
	while ($rowsMo=$db->fetch_assoc($SQL_Quene)){ $RoomInfo[$rowsMo["Rmonth"]] = unserialize($rowsMo["Rtable"]); $RoomInfo["Rid"] = $rowsMo["serial_id"];}
	return $RoomInfo;
}

function OrderList($RoomInfo,$sid,$fd=0,$ed=0)
{
	global $Alldate;
	foreach($Alldate as $day_v)
	{
		$doornot = 1;
		if($fd) if($day_v < $fd || $day_v > $ed) $doornot = 0;
		if($doornot)
		{
			$day = explode ("-", $day_v);
			$dayint = (int)$day[2];
			$DatePrice .= $day_v.'&nbsp;<span class="red"><strong>';
			if($RoomInfo[$day[1]][$dayint][1])
			{
				$DatePrice .= $RoomInfo[$day[1]][$dayint][1].' 元';
				$AllState .= Roomstate($RoomInfo[$day[1]][$dayint][2]).'<br />';
				for($j=1;$j<=$RoomInfo[$day[1]][$dayint][0];$j++)
				{
					$Rooms .= '<option value="'.$RoomInfo["Rid"].'_'.$day_v.'_'.$RoomInfo[$day[1]][$dayint][1].'_'.$j.'">'.$j.'</option>';
				}
				$AllRooms .= '<select name="select[]" id="select[]" style="width:35px;"><option></option>'.$Rooms.'</select>&nbsp;間<br />';
			}
			else
			{
				$DatePrice .= '';
				$AllState .= '尚未定價<br />';
				$AllRooms .= '<br />';
			}
			$DatePrice .= '</strong></span><br />';
			unset($Rooms);
		}
	}
	return array("DatePrice"=>$DatePrice,"AllState"=>$AllState,"AllRooms"=>$AllRooms);
}

// -----------------------------------------------------------------------------------------------------------------------------------
// 專案列表,需討論跨月算法
$SQL_Quene = "SELECT * FROM `sys_onsale` WHERE `cust_sid` = '".$cUrl_sid."' AND '".$f_date1."' <= `f_date2` AND '".$f_date2."' >= `f_date1` AND `state` = '1' ORDER BY `type`,`auto_add`";
if(!$rs = $db->Execute($SQL_Quene)) Fail("資料庫連接失敗");
while ($rows=$db->fetch_assoc($rs))
{
	$Range_fdate = $rows["f_date1"];
	$Range_edate = $rows["f_date2"];
	
	$RoomInfo = GetRoomSt($rows["serial_id"]);
	
	// The Same Module
	//$Rlist = Ord
}
?>
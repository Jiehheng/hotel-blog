<?php
extract($_POST);

if($select)		// 從搜尋日期+房間數來
{
	foreach($select as $o) if($o)
	{
		$Edata = explode ("_",$o);	// Rid,Y-m-d,Pay,Roomcounts
		$Exe_data[$Edata[0]][] = array($Edata[1],$Edata[2],$Edata[3]);
		$Rtableid[] = $Edata[0];
	}
	$Rtableid = array_unique($Rtableid);
	foreach($Rtableid as $o)
	{
		if(isset($SQL_ext)) $SQL_ext .= " OR ";
		$SQL_ext .= "A.`serial_id` = '".$o."'";
	}
	
	$SQL_Quene = 
	"SELECT A.serial_id, B.subject Osubject, B.subject2 Osubject2, B.type Otype, C.subject Rsubject, C.type Rtype FROM `sys_room_state` AS A LEFT JOIN `sys_onsale` AS B 
	ON A.`room_sid` = B.`serial_id` LEFT JOIN `sys_room` AS C ON A.`room_sid` = C.`serial_id` WHERE {$SQL_ext} ORDER BY `Rtype`, `Otype` ";
	//echo $SQL_Quene.'<br>';
	$Rs_Quene = $db->Execute($SQL_Quene);
	while ($rowsMo=$db->fetch_assoc($Rs_Quene))
	{
		if($rowsMo["Osubject"])	// 促銷專案
		{
			$roomsubject = $rowsMo["Osubject"];
			$roomsubject2 = $rowsMo["Osubject2"];
		}
		else	// 一般房型
		{
			$roomsubject = $rowsMo["Rsubject"];
		}
		foreach($Exe_data[$rowsMo["serial_id"]] as $k => $Array)
		{
			$checkinDay = $Exe_data[$rowsMo["serial_id"]][$k][0]; $checkinDayS .= $checkinDay.'<br>';
			$Fdate = explode ("-", $checkinDay);
			$Totaldays = '1'; $TotaldaysS .= $Totaldays.' 天<br>';
			$checkoutDay = date("Y-m-d",mktime(0,0,0,$Fdate[1],$Fdate[2]+$Totaldays,$Fdate[0])); $checkoutDayS .= $checkoutDay.'<br>';
			$TotalRooms = $Exe_data[$rowsMo["serial_id"]][$k][2]; $TotalRoomsS .= $TotalRooms.'間<br>';
			$paymuch = $Exe_data[$rowsMo["serial_id"]][$k][1]; $paymuchS .= $paymuch.' 元<br>';
			$TotalCash = $paymuch*$TotalRooms; $TotalCashS .= $TotalCash.' 元<br>';$TotalCashALL = $TotalCashALL + $TotalCash;
			unset($Fdate);
			$Field[] = array($checkinDay,$checkoutDay,$Totaldays,$TotalRooms,$paymuch,$TotalCash);
		}
		
		$list["tr"] .= '<tr class="mytr">
		<td valign="top" class="mytd1">【沐戀商旅 '.$roomsubject.'】<br />　'.$roomsubject2.'</td>
		<td valign="top" class="mytd1">'.$checkinDayS.'</td>
		<td valign="top" class="mytd1">'.$checkoutDayS.'</td>
		<td valign="top" class="mytd1">'.$TotaldaysS.'</td>
		<td valign="top" class="mytd1">'.$TotalRoomsS.'</td>
		<td valign="top" class="mytd1">'.$paymuchS.'</td>
		<td valign="top" class="mytd1">'.$TotalCashS.'</td>
		</tr>';
		$TotalPays = $TotalPays+$TotalCashALL;
		$Quene[$rowsMo["serial_id"].'_'.$roomsubject.'_'.$roomsubject2] = $Field;
		unset($Field,$roomsubject,$roomsubject2,$checkinDayS,$checkoutDayS,$TotaldaysS,$TotalRoomsS,$paymuchS,$TotalCashS,$TotalCashALL);
	}
	$list["TotalCash"] = $TotalPays;
	
}
elseif($Exe_day)	// 從勾選天數來
{
	foreach ($Exe_day as $i)
	{
		$Edata = explode ("#",$i);
		$CheckinDay = $year.'-'.$month.'-'.$Edata[0]; $CheckinDayS .= $CheckinDay.'<br>';
		$CheckotDay = date("Y-m-d",mktime(0,0,0,$month,$Edata[0]+1,$year)); $CheckotDayS .= $CheckotDay.'<br>';
		$Ttdays = 1; $TtdaysS .= $Ttdays.' 天<br>';
		$Rooms = $FTT[$i]; $RoomsS .= $Rooms.'<br>';
		$Cash = $Edata[1]; $CashS .= $Cash.' 元<br>';
		$PreCash = $Edata[1]*$FTT[$i]; $PreCashS .= $PreCash.' 元<br>';
		$TotalCash = $TotalCash + $Edata[1]*$FTT[$i];
		$Field[] = array($CheckinDay,$CheckotDay,$Ttdays,$Rooms,$Cash,$PreCash);
	}
	
	$list["tr"] = '<tr class="mytr">
	<td class="mytd1" style="vertical-align:middle;">【沐戀商旅 '.$roomsubject.'】'.$roomsubject2.'</td>
	<td valign="top" class="mytd1">'.$CheckinDayS.'</td>
	<td valign="top" class="mytd1">'.$CheckotDayS.'</td>
	<td valign="top" class="mytd1">'.$TtdaysS.'</td>
	<td valign="top" class="mytd1">'.$RoomsS.'</td>
	<td valign="top" class="mytd1">'.$CashS.'</td>
	<td class="mytd1" style="vertical-align:middle;">'.$PreCashS.'</td>
	</tr>';
	$TotalPays = $TotalPays+$TotalCash;
	$list["TotalCash"] = $TotalPays;
	
	$Quene[$roomstatesid.'_'.$roomsubject.'_'.$roomsubject2] = $Field;
	unset($Field,$roomsubject,$roomsubject2,$CheckinDayS,$CheckotDayS,$TtdaysS,$RoomsS,$CashS,$PreCashS);
}
else Fail("Null Input!");
$list["Quene"] = htmlentities(serialize($Quene));
$_SESSION["Quene"] = serialize($Quene);
// Backup
/*	"SELECT A.*,B.serial_id Osid, B.cust_sid Ocid, B.type Otype, B.subject Osubject, B.subject2 Osubject2, B.manydays Omanyday */
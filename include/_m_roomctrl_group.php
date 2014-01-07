<?php
// 判斷系統預設日期,與輸入日期
if(isset($_GET["ly"]))$Thisyear = $_GET["ly"];else $Thisyear = date("Y");
$list["year"] = $Thisyear;
$list["lastyear"] = '<span style="cursor:pointer;" onclick="javascript:window.open(\'?'.$cUrl.'&roomctrl_group&ly='.($Thisyear-1).'\',\'_self\');"><img src="images/p3_back2.jpg" alt="ly" /></span>';
$list["nextyear"] = '<span style="cursor:pointer;" onclick="javascript:window.open(\'?'.$cUrl.'&roomctrl_group&ly='.($Thisyear+1).'\',\'_self\');"><img src="images/p3_next2.jpg" alt="ly" /></span>';

extract($_POST);
/*foreach ($_POST as $k => $v) echo "$k =$v<br />";*/

// 專案列表
// 房型列表
if(!$rs = $db->Execute("SELECT `serial_id`,`subject`,`room_open`,`price` FROM `sys_onsale` WHERE `cust_sid` = '".$cUrl_sid."' ORDER BY `type`,`auto_add`")) Fail("資料庫連接失敗");
while ($rows = $db->fetch_assoc($rs))
{
	$list_rm["sid"] = $rows['serial_id'];
	$list_rm["subject"] = $rows['subject'];
	$list_rm["room_open"] = $rows['room_open'];
	$list_rm["prepay"] = '100';
	$list_rm["price"] = implode (", ", unserialize($rows['price']));
	$arr_list[] = $list_rm;
	unset($list_rm);
}

if(!$rsRoom = $db->Execute("SELECT `serial_id`,`subject`,`detail`,`price`,`order_percent` FROM `sys_room` WHERE `cust_sid` = '".$cUrl_sid."' ORDER BY `type`,`auto_add`")) Fail("資料庫連接失敗");
while ($rows = $db->fetch_assoc($rsRoom))
{
	$list_rm["sid"] = $rows['serial_id'];
	$list_rm["subject"] = $rows['subject'];
	$room_open = unserialize($rows['detail']);
	$list_rm["room_open"] = $room_open[3];
	$list_rm["prepay"] = $rows['order_percent'];
	$list_rm["price"] = implode (",&nbsp;", unserialize($rows['price']));
	$arr_list[] = $list_rm;
	unset($list_rm);
}
//print_r($arr_list);

foreach ($arr_list as $room)
{
	if(!$rs = $db->Execute("SELECT `Rmonth` FROM `sys_room_state` WHERE `room_sid` = '".$room["sid"]."' AND `Ryear` = '".$Thisyear."'")) Fail("資料庫連接失敗");
	if($db->num_rows($rs))
	{
		while ($rowsDist=$db->fetch_assoc($rs)) $TotalMonth[] = $rowsDist['Rmonth'];
		for($i=1;$i<=12;$i++)
		{
			if(in_array($i,$TotalMonth)){ ${"temp".$i} = ' disabled="false" checked="checked"'; ${"tempS".$i} = 'background:#EEEEEE';}
		}
	}
	$list["area"] .= '<tr style="border:1px solid #CCCCCC;line-height:110%;">
	<td style="padding:3px;">'.$room["subject"].'<br/>間數[&nbsp;'.$room["room_open"].'&nbsp;]&nbsp;｜&nbsp;訂金[&nbsp;'.$room["prepay"].'%&nbsp;]&nbsp;｜&nbsp;週日 ~ 六價格=['.$room["price"].']</td>
	<td style="text-align:center;vertical-align:middle;'.$tempS1.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',1,"'.$temp1.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS2.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',2,"'.$temp2.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS3.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',3,"'.$temp3.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS4.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',4,"'.$temp4.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS5.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',5,"'.$temp5.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS6.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',6,"'.$temp6.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS7.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',7,"'.$temp7.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS8.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',8,"'.$temp8.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS9.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',9,"'.$temp9.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS10.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',10,"'.$temp10.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS11.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',11,"'.$temp11.' /></td>
	<td style="text-align:center;vertical-align:middle;'.$tempS12.'"><input name="month[]" id="month" type="checkbox" value="'.$room["sid"].',12,"'.$temp12.' /></td>
	</tr>';
	unset($TotalMonth,$temp1,$temp2,$temp3,$temp4,$temp5,$temp6,$temp7,$temp8,$temp9,$temp10,$temp11,$temp12);
	unset($tempS1,$tempS2,$tempS3,$tempS4,$tempS5,$tempS6,$tempS7,$tempS8,$tempS9,$tempS10,$tempS11,$tempS12);
}
?>
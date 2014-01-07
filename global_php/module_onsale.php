<?php
if($_POST["Product"]) $TargetID = $_POST["Product"]; elseif($_GET["Product"]) $TargetID = $_GET["Product"];

// 專案列表
if(!$rs = $db->Execute("SELECT * FROM `sys_onsale` WHERE `cust_sid` = '".$cUrl_sid."' ORDER BY `type`,`auto_add`")) Fail("資料庫連接失敗");
while ($rows=$db->fetch_assoc($rs))
{
	$list["subject"] = $rows['subject'];
	$list["subject2"] = ' - '.$rows['subject2'];
	$list["sprice"] = $rows['sprice'];
	$list["uprice"] = $rows['uprice'];
	if(!$TargetID) $TargetID = $rows['serial_id'];	// 沒有GET時指派第一筆資料為目標ID
	$list["c1"] = '<div class="TabbedPanelsContent">
	<div style="border:#CCCCCC 1px dashed;padding:5px;">'.$rows['content'].'</div>
	</div>';
	$arr_list[] = $list;
	if($TargetID && $TargetID==$rows['serial_id'])
	{
		$SwiSelect = ' selected="selected"';
		$Order_percent = $rows['order_percent'];
		$TargetSubject = $rows['subject'];
		$TargetSubject2 = $rows['subject2'];
	}
	$list["room_match"] .= "<option value=\"{$rows['serial_id']}\"{$SwiSelect}>促銷活動：&nbsp;{$rows['subject']}&nbsp;-&nbsp;{$rows['subject2']}</option>";
	unset($SwiSelect);
}

// 房型列表
if(!$rsRoom = $db->Execute("SELECT * FROM `sys_room` WHERE `cust_sid` = '".$cUrl_sid."' ORDER BY `type`,`auto_add`")) Fail("資料庫連接失敗");
while ($rowsR=$db->fetch_assoc($rsRoom))
{
	$room_detail = unserialize($rowsR['detail']);
	if($TargetID && $TargetID==$rowsR['serial_id'])
	{
		$SwiSelect = ' selected="selected"';
		$Order_percent = $rowsR['order_percent'];
		$TargetSubject = $rows['subject'];
	}
	$list["room_match"] .= "<option value=\"{$rowsR['serial_id']}\"{$SwiSelect}>基本房型：[&nbsp;{$room_detail[4]}人房&nbsp;]&nbsp;{$rowsR['subject']}&nbsp;(&nbsp;&nbsp;{$room_detail[3]}間&nbsp;)</option>";
	unset($SwiSelect);
}
?>
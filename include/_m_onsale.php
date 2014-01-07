<?php
if(!$rsRoom = $db->Execute("SELECT * FROM `sys_room` WHERE `cust_sid` = '".$CustUsed."' ORDER BY `type`,`auto_add`")) Fail("資料庫連接失敗");
if($db->num_rows($rsRoom)==0) Fail("：必須先行建立房型資料");
if(isset($_GET["new"]))
{
	while ($rows=$db->fetch_assoc($rsRoom))
	{
		$room_detail = unserialize($rows['detail']);
		$list["room_match"] .= "<option value=\"{$rows['serial_id']}\">[&nbsp;{$room_detail[4]}人房&nbsp;]&nbsp;{$rows['subject']}&nbsp;(&nbsp;&nbsp;{$room_detail[3]}間&nbsp;)</option>";
	}
	
	$htmllnk = '_m_onsale_new';
}
else
{
	$list["area1"] = '<input name="newone" value="新增促銷活動" type="button" onclick="javascript:window.open(\''.$tmpV.'&new\',\'_self\');" />';
	if(!$rs = $db->Execute("SELECT * FROM `sys_onsale` WHERE `cust_sid` = '".$CustUsed."' ORDER BY `type`,`auto_add`")) Fail("資料庫連接失敗");
	// 房型列陣
	while ($rowsRoom=$db->fetch_assoc($rsRoom))
	{
		$room_detail = unserialize($rowsRoom['detail']);
		$x=0;
		$RoomSelect[] = array($x => $rowsRoom['serial_id'],$room_detail[4],$rowsRoom['subject'],$room_detail[3]);
		$x++;
	}
	
	$x=1;$y=2;	// 日期格無線前置宣告
	while ($rows=$db->fetch_assoc($rs))
	{
		if($rows["state"]=='1') $list["selg"]='checked="checked"'; else $list["selh"]='checked="checked"';
		$editval[] = $rows['auto_add'];
		$list["setup1"] .= 'LoadEditor(\'content_'.$rows['auto_add'].'\', \'postPost\');';
		$list["title"] .= '<li class="TabbedPanelsTab" tabindex="0" onfocus="if(this.blur)this.blur()">'.$rows['subject'].'</li>';
		$list["auto_add"] = $rows['auto_add'];
		$list["subject"] = $rows['subject'];
		$list["subject2"] = $rows['subject2'];
		$list["sprice"] = $rows['sprice'];
		$list["uprice"] = $rows['uprice'];
		$list["manydays"] = $rows['manydays'];
		$priceAll = unserialize($rows['price']);
		$list["price1"] = $priceAll[0];
		$list["price2"] = $priceAll[1];
		$list["price3"] = $priceAll[2];
		$list["price4"] = $priceAll[3];
		$list["price5"] = $priceAll[4];
		$list["price6"] = $priceAll[5];
		$list["price7"] = $priceAll[6];
		foreach($priceAll as $m)	// 做出最便宜處理
		{
			if(!isset($minest)) $minest = $m;
			if($m < $minest) $minest = $m;
		}
		foreach ($RoomSelect as $k => $v)
		{
			if($rows["room_match"] == $v[0]) $tab = 'selected="selected" ';
			$list["room_match"] .= "<option value=\"{$v[0]}\" ".$tab.">[&nbsp;{$v[1]}人房&nbsp;]&nbsp;{$v[2]}&nbsp;(&nbsp;{$v[3]}間&nbsp;)</option>";
			unset($tab);
		}
		$list["room_open"] = $rows['room_open'];
		$list["f_date1"] = $rows['f_date1'];
		$list["x"] = $x;
		$list["f_date2"] = $rows['f_date2'];
		$list["y"] = $y;
		$list["content"] = $rows['content'];
		$list["creatime"] = $rows['creatime'];
		$list["order"] = $rows['type'];
		$arr_list[] = $list;

		unset($list["selg"],$list["selh"]);
		$list["area4"] .= 'cal.manageFields("f_btn'.$x.'", "f_date1_'.$rows['auto_add'].'", "%Y-%m-%d");cal.manageFields("f_btn'.$y.'", "f_date2_'.$rows['auto_add'].'", "%Y-%m-%d");';
		$x=$x+2; $y=$y+2;
	}
	if($minest >0)
		if(!$rsPost = $db->Execute("UPDATE `sys_customer` SET `cheep_onsale` = '".$minest."' WHERE `serial_id` = '".$CustUsed."'")) Fail("修改失敗! 請重新檢查");
	if(is_array($editval)) $list["editvalue"] = implode (",", $editval);
	$ISONSALE = 1;
	//$rs_ar = $db->fetch_array($rs);
	//$list = htmlspecialchars($rs_ar["content"]);
}
?>
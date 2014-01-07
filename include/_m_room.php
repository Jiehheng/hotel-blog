<?php
if(isset($_GET["new"]))
{
	$htmllnk = '_m_room_new';
}
else
{
	$list["area1"] = '<input name="newone" value="新增房型" type="button" onclick="javascript:window.open(\''.$tmpV.'&new\',\'_self\');" />';
	if(!$rs = $db->Execute("SELECT * FROM `sys_room` WHERE `cust_sid` = '".$CustUsed."' ORDER BY `type`,`auto_add`")) Fail("資料庫連接失敗");
	while ($rows=$db->fetch_assoc($rs))
	{
		$detail = unserialize($rows['detail']);
		$priceAll = unserialize($rows['price']);
		if($detail[6]=='1') $list["sela"]='checked="checked"'; else $list["selb"]='checked="checked"';
		if($detail[7]=='1') $list["selc"]='checked="checked"'; else $list["seld"]='checked="checked"';
		if($detail[8]=='1') $list["sele"]='checked="checked"'; else $list["self"]='checked="checked"';
		if($rows["state"]=='1') $list["selg"]='checked="checked"'; else $list["selh"]='checked="checked"';
		$editval[] = $rows['auto_add'];
		$list["setup1"] .= 'LoadEditor(\'content_'.$rows['auto_add'].'\', \'postPost\');';
		$list["title"] .= '<li class="TabbedPanelsTab" tabindex="0" onfocus="if(this.blur)this.blur()">'.$rows['subject'].'</li>';
		$list["auto_add"] = $rows['auto_add'];
		$list["subject"] = $rows['subject'];
		$list["content"] = $rows['content'];
		$list["pinswo"] = $detail[0];
		$list["sprice"] = $detail[1];
		$list["uprice"] = $detail[2];
		$list["counts"] = $detail[3];
		$list["people"] = $detail[4];
		$list["bedtype"] = $detail[5];
		$list["deban"] = $detail[9];
		$list["yaush"] = $detail[10];
		$list["order_percent"] = $rows['order_percent'];
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
		$list["order"] = $rows['type'];
		$list["creatime"] = $rows['creatime'];
		$arr_list[] = $list;
		unset($list["sela"],$list["selb"],$list["selc"],$list["seld"],$list["sele"],$list["self"],$list["selg"],$list["selh"]);
	}
	if($minest >0)
		if(!$rsPost = $db->Execute("UPDATE `sys_customer` SET `cheep_room` = '".$minest."' WHERE `serial_id` = '".$CustUsed."'")) Fail("修改失敗! 請重新檢查");
	if(is_array($editval)) $list["editvalue"] = implode (",", $editval);
	$ISONSALE = 1;
	//$rs_ar = $db->fetch_array($rs);
	//$list = htmlspecialchars($rs_ar["content"]);
}
?>
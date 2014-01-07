<?php
$login_id = $_SESSION["login_id"];
$rs=$db->Execute("select * from `sys_members` where `serial_id`='{$login_id}'");
if ($db->num_rows($rs))
{
	$rs_User = $db->fetch_array($rs);
	$list = $rs_User;
	
	// 性別
	if($rs_User["sex"]==1) $sel_boy = ' selected="selected"'; else $sel_girl = ' selected="selected"';
	$list["sex"] = '<option value="1"'.$sel_boy.'>先生</option><option value="0"'.$sel_girl.'>小姐</option>';
	
	// 生日拆解
	$bir = explode ("-", $rs_User["birthday"]);
	for($y=(date(Y)-90);$y<(date(Y)-10);$y++)
	{
		if($bir[0]==$y) $sel = ' selected="selected"';
		$list["birthday_Year"] .= '<option value="'.$y.'"'.$sel.'>'.$y.'</option>';
		unset($sel);
	}
	for($m=1;$m<=12;$m++)
	{
		if($bir[1]==$m) $sel = ' selected="selected"';
		$list["birthday_Month"] .= '<option value="'.$m.'"'.$sel.'>'.$m.'</option>';
		unset($sel);
	}
	for($d=1;$d<=31;$d++)
	{
		if($bir[2]==$d) $sel = ' selected="selected"';
		$list["birthday_Day"] .= '<option value="'.$d.'"'.$sel.'>'.$d.'</option>';
		unset($sel);
	}

}
?>
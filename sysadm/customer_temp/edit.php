<?php
	header("Cache-control: private");
	if ($check != 'kingfor') exit;

	$serial_id = filter_xss($_POST['edit_which']);
	if (!$rs = $db->Execute("select * from `sys_customer_temp` where `serial_id`='{$serial_id}'")) die('資料庫發生錯誤');
	$row = $db->fetch_assoc($rs);

	// 部門
	if(!$rs = $db->Execute("select * FROM `sys_depart_mb` order by `sort` asc")) die('資料庫發生錯誤');
	while ($rows=$db->fetch_assoc($rs))
	{
		if($rows['serial_id']==$row['city']) $list["city"] = $rows['caption'];
	}
	$list["name"] = $row["name"];
	$list["addr"] = $row['addr'];
	$list["tele"] = $row['tele'];
	$list["contacter"] = $row['Contacter'];
	$list["email"] = $row['email'];
	$list["creatime"] = $row['creatime'];
	$list["subject"] = $row['Subject'];
	$list["content"] = $row['Content'];
	
	$xtpl->assign('content_list', $list);

	$xtpl->parse('table');
	$xtpl->parse('main.table');
?>
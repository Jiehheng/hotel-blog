<?php
	header("Cache-control: private");
    if ($check != 'kingfor')
		exit;
	if (empty($_POST['edit_which']))
		die();
	
	$edit_which=filter_sql_injection($_POST['edit_which']);
	$sql="select * from sys_depart where serial_id='{$edit_which}'";	
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$row=$db->fetch_assoc($rs);
		$xtpl->assign('DATA', $row);
	}else
		die('資料庫發生錯誤');
		
	$xtpl->parse('table');
    $xtpl->parse('main.table');
?>	
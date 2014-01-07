<?php
	header("Cache-control: private");
	if ($check != 'kingfor') exit;
	if (empty($_POST['edit_which'])) die('');
	
	$edit_which=filter_sql_injection($_POST['edit_which']);
	$sql="select * from sys_module where serial_id='{$edit_which}'";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$row=$db->fetch_assoc($rs);
		// 是否列入特修時數計算
		if($row['display']=='y') $xtpl->assign('display1', 'checked="checked"'); else $xtpl->assign('display2', 'checked="checked"');
		$xtpl->assign('DATA', $row);
	}else
		die('資料庫發生錯誤');
	
	$xtpl->assign('serial_id',$edit_which);
	$xtpl->parse('main.table');
?>	
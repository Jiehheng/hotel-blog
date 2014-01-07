<?php
	header("Cache-control: private");
    if ($check != 'kingfor')
		exit;
	if (empty($_POST['edit_which']))
		die();
	
	$edit_which=filter_sql_injection($_POST['edit_which']);
	$sql="select * FROM sys_unit where serial_id='{$edit_which}'";	
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$row=$db->fetch_assoc($rs);
		$xtpl->assign('DATA', $row);
	}else
		die('資料庫發生錯誤');

	$depart_sql = "select serial_id,caption from sys_depart order by sort";
	$rs_d=$db->Execute($depart_sql);
	if ($rs_d)
	{
		while ($rows_v=mysql_fetch_assoc($rs_d))
		{
			if($row["depart_sid"]==$rows_v["serial_id"]) $seled = ' selected';
			$depart_list .= '<option value="'.$rows_v["serial_id"].'"'.$seled.'>'.$rows_v["caption"].'</option>';
			unset($seled);
		}
	}else die('資料庫發生錯誤');
	$xtpl->assign('depart_list', $depart_list);
	
	$xtpl->parse('table');
    $xtpl->parse('main.table');
?>	
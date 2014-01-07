<?php
	header("Cache-control: private");
    if ($check != 'kingfor') exit;
	
	$depart_sql = "select serial_id,caption from sys_depart order by sort";
	$rs_d=$db->Execute($depart_sql);
	if ($rs_d)
	{
		while ($rows_v=mysql_fetch_assoc($rs_d))
		{
			$depart_list .= '<option value="'.$rows_v["serial_id"].'"'.$seled.'>'.$rows_v["caption"].'</option>';
		}
	}else die('��Ʈw�o�Ϳ��~');
	$xtpl->assign('depart_list', $depart_list);

	$xtpl->parse('main.table');
?>
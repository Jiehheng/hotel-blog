<?php
	header("Cache-control: private");
	if ($check != 'kingfor')
		exit;

	//部門
	$sql="select * from sys_unit order by sort asc";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows=$db->fetch_assoc($rs))
			$unit_list .= '<option value="'.$rows['serial_id'].'">'.$rows['caption'].'</option>';
		

	}else
		die('資料庫發生錯誤');

	//身份
	$sql="select * from sys_authority order by sort asc";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows1=$db->fetch_assoc($rs))
			$authority_list .= '<option value="'.$rows1['serial_id'].'">'.$rows1['caption'].'</option>';
		

	}else
		die('資料庫發生錯誤');

	//生日-西元
	for ($i=date('Y')-65;$i<date('Y')-18;$i++)
		$year .= '<option value='.$i.'>'.$i.'</option>';
	//生日-月份
	for ($i=1;$i<13;$i++)
		$month .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
	//生日-日
	for ($i=1;$i<32;$i++)
		$day .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';

	//職務
	$sql="select * from sys_job ";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows2=$db->fetch_assoc($rs))
			$job_list .= '<option value="'.$rows2['serial_id'].'">'.$rows2['caption'].'</option>';
	}else
		die('資料庫發生錯誤');
		
	$xtpl->assign('unit_list', $unit_list);
	$xtpl->assign('job_list', $job_list);
	$xtpl->assign('authority_list', $authority_list);
	$xtpl->assign('year', $year);
	$xtpl->assign('month', $month);
	$xtpl->assign('day', $day);
	$xtpl->parse('table');
    $xtpl->parse('main.table');

?>
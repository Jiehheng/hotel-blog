<?php
	header("Cache-control: private");
	if ($check != 'kingfor')
		exit;

	//生日-西元
	for ($i=date('Y')-65;$i<date('Y')-18;$i++)
		$year .= '<option value='.$i.'>'.$i.'</option>';
	//生日-月份
	for ($i=1;$i<13;$i++)
		$month .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
	//生日-日
	for ($i=1;$i<32;$i++)
		$day .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
		
	$xtpl->assign('unit_list', $unit_list);
	$xtpl->assign('year', $year);
	$xtpl->assign('month', $month);
	$xtpl->assign('day', $day);
	$xtpl->parse('table');
    $xtpl->parse('main.table');

?>
<?php
	header("Cache-control: private");
	if ($check != 'kingfor')
		exit;

/*	//生日-西元
	for ($i=date('Y')-65;$i<date('Y')-18;$i++)
		$year .= '<option value='.$i.'>'.$i.'</option>';
	//生日-月份
	for ($i=1;$i<13;$i++)
		$month .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
	//生日-日
	for ($i=1;$i<32;$i++)
		$day .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
*/	
	// 區域抽離
	$sql_dist=$db->Execute("SELECT `caption`,`stid` FROM `case_district` ORDER BY `sort`");
	$x=1;
	while ($rowsDist=$db->fetch_assoc($sql_dist)){
		if($x%10==0)$dist_list.='<br/>　';
		$dist_list.='<input type="checkbox" name="ob_District[]" id="ob_District[]" value="'.$rowsDist['stid'].'" />'.$rowsDist['caption'].'&nbsp;';
		$x++;
	}
	
	$xtpl->assign('unit_list', $unit_list);
	$xtpl->assign('dist_list', $dist_list);
	$xtpl->assign('authority_list', $authority_list);
	$xtpl->assign('year', $year);
	$xtpl->assign('month', $month);
	$xtpl->assign('day', $day);
	$xtpl->parse('table');
    $xtpl->parse('main.table');

?>
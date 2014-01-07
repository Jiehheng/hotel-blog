<?php
	header("Cache-control: private");
	if ($check != 'kingfor') exit;

	$serial_id = filter_xss($_POST['edit_which']);
	$rs = $db->Execute("select * from `sys_members` where `serial_id`='{$serial_id}'");
	if (!$rs) die('資料庫發生錯誤');
	$row = $db->fetch_assoc($rs);

	//部門
	$rs = $db->Execute("select * FROM `sys_depart_mb` order by `sort` asc");
	if(!$rs) die('資料庫發生錯誤');
	if(empty($row['unit'])) $tag_sel = ' selected';
	while ($rows=$db->fetch_assoc($rs))
	{
		if($rows['serial_id']==$row['unit']) $tag_sel = ' selected';
		if(empty($row['unit']) && $rows['serial_id']=='7767ac51c656da73e24dd1850643438d') $tag_sel = ' selected';
		$list .= '<option value="'.$rows['serial_id'].'"'.$tag_sel.'>'.$rows['caption'].'&nbsp;('.$rows['stid'].')</option>';
		unset($tag_sel);
	}
	$xtpl->assign('authority_list', $list);

	if (!empty($row['birthday']))
		$birthday=explode('-',$row['birthday']);
	//生日-西元
	for ($i=date('Y')-65;$i<date('Y')-18;$i++)
	{
		if (is_array($birthday) and $birthday[0]==$i)
			$year .= '<option value='.$i.' selected>'.$i.'</option>';
		else
			$year .= '<option value='.$i.'>'.$i.'</option>';
	}
	//生日-月份
	for ($i=1;$i<13;$i++)
	{
		if (is_array($birthday) and $birthday[1]==$i)
			$month .= '<option value="'.sprintf("%02d",$i).'" selected>'.sprintf("%02d",$i).'</option>';
		else
			$month .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
	}
	//生日-日
	for ($i=1;$i<32;$i++)
	{
		if (is_array($birthday) and $birthday[2]==$i)
			$day .= '<option value="'.sprintf("%02d",$i).'" selected>'.sprintf("%02d",$i).'</option>';
		else
			$day .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
	}

	$xtpl->assign('year', $year);
	$xtpl->assign('month', $month);
	$xtpl->assign('day', $day);
	$xtpl->assign('DATA', $row);
	$xtpl->assign('name',$_SESSION['session_name']);
	$xtpl->parse('table');
	$xtpl->parse('main.table');

?>
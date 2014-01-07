<?php
	header("Cache-control: private");
	if ($check != 'kingfor')
		exit;

	$serial_id=filter_xss($_POST['edit_which']);
	$sql="select * from sys_account where serial_id='{$serial_id}'";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$row=$db->fetch_assoc($rs);
	}else
		die('資料庫發生錯誤');

	//門市SID轉中文
	$rsdid = $db->Execute("select serial_id,caption from sys_depart");
	while ($rowsdid=mysql_fetch_assoc($rsdid))
		$depart_sid[$rowsdid['serial_id']]=$rowsdid['caption'];
	
	//部門
	$sql="select * FROM sys_unit order by sort asc";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows=$db->fetch_assoc($rs))
		{
			if ($rows['serial_id']==$row['unit'])
				$list .= '<option value="'.$rows['serial_id'].'" selected>'.$rows['caption'].'&nbsp;('.$depart_sid[$rows['depart_sid']].')</option>';
			else
				$list .= '<option value="'.$rows['serial_id'].'">'.$rows['caption'].'&nbsp;('.$depart_sid[$rows['depart_sid']].')</option>';
		}
		$xtpl->assign('unit_list', $list);
	}else
		die('資料庫發生錯誤');

	//身份
	$sql="select * from sys_authority order by sort asc";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows1=$db->fetch_assoc($rs))
		{
			if ($rows1['serial_id']==$row['authority'])
				$list1 .= '<option value="'.$rows1['serial_id'].'" selected>'.$rows1['caption'].'</option>';
			else
				$list1 .= '<option value="'.$rows1['serial_id'].'">'.$rows1['caption'].'</option>';
		}
		$xtpl->assign('authority_list', $list1);

	}else
		die('資料庫發生錯誤');

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
	//職務
	$sql="select * FROM sys_job ";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$job_array=unserialize($row['job']);
		while ($rows2=$db->fetch_assoc($rs))
		{
			if ($rows2['serial_id']==$job_array[0])
				$job_list .= '<option value="'.$rows2['serial_id'].'" selected>'.$rows2['caption'].'</option>';
			else
				$job_list .= '<option value="'.$rows2['serial_id'].'">'.$rows2['caption'].'</option>';
			$job_temp[]=array('serial_id'=>$rows2['serial_id'],'caption'=>$rows2['caption']);
		}
		if (count($job_array)>1)
		{
			for ($i=1;$i<count($job_array);$i++)
			{
				$extra_job .= '<div id="a_'.sprintf("%03d",$i).'"><select name="job[]">';
				for ($j=0;$j<count($job_temp);$j++)
				{
					if ($job_temp[$j]['serial_id']==$job_array[$i])
						$extra_job .= '<option value="'.$job_temp[$j]['serial_id'].'" selected>'.$job_temp[$j]['caption'].'</option>';
					else
						$extra_job .= '<option value="'.$job_temp[$j]['serial_id'].'">'.$job_temp[$j]['caption'].'</option>';
				}
				$extra_job .='</select> <input type="button" value="移除" onclick="less_job(\'a_'.sprintf("%03d",$i).'\')" style="padding-top:2px"></div>';
			}
		}
	}else
		die('資料庫發生錯誤');

	$xtpl->assign('job_list', $job_list);
	$xtpl->assign('extra_job', $extra_job);
	$xtpl->assign('year', $year);
	$xtpl->assign('month', $month);
	$xtpl->assign('day', $day);
	$xtpl->assign('DATA', $row);
	$xtpl->assign('name',$_SESSION['session_name']);
	$xtpl->parse('table');
	$xtpl->parse('main.table');

?>
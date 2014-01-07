<?php
	header("Cache-control: private");
	if ($check != 'kingfor') exit;
	
	///模組列表 start///
	$sql="select * from sys_module ORDER BY sort asc";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$i=0;
		while ($rows=$db->fetch_assoc($rs))
		{			
			if (in_array('action',$_SESSION['session_authority_list'])) //檢測 行為 的權限
				$edit='';
			else
				$edit='style="display:none"';
			
			if($rows['display']=='n') $show_state='<span style="color:#FF0000;">(隱藏)</span>';
			$tree .= '
			<tr>
			  <td align="center" width="2%" class="lvtCol">'.$rows['sort'].'</td>
			  <td align="left" width="15%" class="lvtCol">'.$rows['caption'].'&nbsp;'.$show_state.'</td>
			  <td align="left" width="83%" class="lvtCol"><input type="button" value="編輯" onclick="edit_it(\''.$rows['serial_id'].'\')" style="padding-top:2px" '.$edit.'>
				<input type="button" value="刪除" onclick="delete_it(\''.$rows['serial_id'].'\')" style="padding-top:2px" '.$edit.'>
			  </td>
			</tr>';
			$show_state='';
		}
	}
	else die('資料庫發生錯誤');
		
	$xtpl->assign('tree',$tree);
	$xtpl->parse('main.table');
?>
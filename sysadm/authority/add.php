<?php
	header("Cache-control: private");
	if ($check != 'kingfor')
		exit;
	
	$sql="select * from sys_module order by sort asc";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows=$db->fetch_assoc($rs))
		{
			$authority_list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
				<td align="center" style="width:30%">'.$rows['caption'].'</td>
				<td style="width:70%" align="center" >
					<input type="checkbox"  id="'.$rows['serial_id'].'_list" name="'.$rows['serial_id'].'_list" value="1" onclick="list_ok(\''.$rows['serial_id'].'\')">檢視(列表)
					&nbsp;&nbsp;<input type="checkbox" id="'.$rows['serial_id'].'_action" name="'.$rows['serial_id'].'_action" value="1" onclick="action_ok(\''.$rows['serial_id'].'\')" disabled >行為(新增,刪除,修改)
					&nbsp;&nbsp;<input type="checkbox" id="'.$rows['serial_id'].'_manage" name="'.$rows['serial_id'].'_manage" value="1" disabled>管理					
				</td>
			</tr>';
		}
	}else{
		die('資料庫發生錯誤');
	}
	
	$xtpl->assign('name',$_SESSION['session_name']);
	$xtpl->assign('authority_list',$authority_list);
	$xtpl->parse('table');
	$xtpl->parse('main.table');
?>	
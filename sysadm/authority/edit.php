<?php
	header("Cache-control: private");
	if ($check != 'kingfor')
		exit;
		
	$edit_which=filter_xss($_POST['edit_which']);	
	$sql="select * from sys_authority where serial_id='{$edit_which}'";	
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$row=$db->fetch_assoc($rs);
	}else
		die('資料庫發生錯誤');
	
	$xtpl->assign('DATA',$row);	
	
	$authority=unserialize($row['authority']);
	$authority_module=array();
	foreach($authority as $module_id => $action)
		$authority_module[]=$module_id;
	
	$sql="select * from sys_module order by sort asc ";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows=$db->fetch_assoc($rs))
		{
			$list='';
			$action='disabled';
			$manage='disabled';
			if (in_array($rows['serial_id'],$authority_module))
			{
				if (in_array('list',$authority[$rows['serial_id']]))
				{
					$list='checked';				
					$action='';
				}
				if (in_array('action',$authority[$rows['serial_id']]))
				{
					$action='checked';									
					$manage='';
				}
				if (in_array('manage',$authority[$rows['serial_id']]))
					$manage='checked';				
			}
			if($rows['display']=='y') $display = 'checked'; else unset($display);
			
			$authority_list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
				<td align="center" style="width:25%">'.$rows['caption'].'</td>
				<td style="width:75%" align="center" >
					<input type="checkbox" name="'.$rows['serial_id'].'_list" value="1" '.$list.' onclick="list_ok(\''.$rows['serial_id'].'\')">檢視(列表)
					&nbsp;&nbsp;<input type="checkbox" name="'.$rows['serial_id'].'_action" value="1" '.$action.' onclick="action_ok(\''.$rows['serial_id'].'\')" >行為(新增,刪除,修改)
					&nbsp;&nbsp;<input type="checkbox" name="'.$rows['serial_id'].'_manage" value="1" '.$manage.'>管理					
				</td>
			</tr>';	
			unset($list,$manage,$action);
		}
	}else{
		die('資料庫發生錯誤');
	}
	
	$xtpl->assign('authority_list',$authority_list);	
	$xtpl->parse('table');
	$xtpl->parse('main.table');
?>	
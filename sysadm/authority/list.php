<?php
	header("Cache-control: private");
	if ($check != 'kingfor') exit;	
	
	////POST參數 start////
	$now_page=intval($_POST['now_page']); //頁數
	if (empty($now_page) or !is_numeric($now_page))
		$now_page=1;
	////POST參數 end////

	$page_row=10;	// 每頁幾筆

	///權限列表
	$start = ($now_page-1)*$page_row; //資料分頁
	$sql="select SQL_CALC_FOUND_ROWS * from sys_authority order by sort asc limit {$start},{$page_row}";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$rs2=$db->Execute("SELECT FOUND_ROWS()"); //分頁專用函式 for mysql
		list($total_rows)=$db->fetch_row($rs2);
		$total_page=ceil($total_rows/$page_row);
		if ($total_rows>0)
		{
			while ($rows=$db->fetch_assoc($rs))
			{
				$module_id=unserialize($rows['authority']);
				foreach($module_id as $module => $actions)
					$module_array[] = $module;
				$modules=implode("','",$module_array);
				$sql="select caption from sys_module where serial_id in ('{$modules}') order by sort asc";
				$rs1=$db->Execute($sql);
				if ($rs1)
				{
					while($row1=$db->fetch_assoc($rs1))
						$authority_list .= $row1['caption'].',';
				}else
					die('資料庫發生錯誤');
				$authority_list = rtrim($authority_list,',');
				if (in_array('action',$_SESSION['session_authority_list'])) //檢測 行為 的權限
					$edit='';
				else
					$edit='style="display:none"';
				
				$list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
								<td align="center"><input type="checkbox" value="'.$rows['serial_id'].'" '.$edit.'></td>
								<td align="center">'.$rows['caption'].'</td>
								<td align="left">'.$authority_list.'</td>
								<td align="center" width="100" nowrap><input style="padding-top:2px" type="button" onclick="edit(\''.$rows['serial_id'].'\');" value="修改" '.$edit.'></td>
							</tr>';
				unset($authority_list,$module_array,$modules);
			}
			if ($now_page==1)
				$first_last='';
			else
				$first_last='<a href="javascript:;" onclick="change_page(\'1\')">第一頁</a>&nbsp;<a href="javascript:;" onclick="change_page(\''.($now_page-1).'\')">上一頁</a>&nbsp;';
			if ($now_page==$total_page)
				$next_final='';
			else
				$next_final='<a href="javascript:;" onclick="change_page(\''.($now_page+1).'\')">下一頁</a>&nbsp;<a href="javascript:;" onclick="change_page(\''.$total_page.'\')">最末頁</a>&nbsp;';
			//刪除列
			$list .= '<tr>
						<td width="6%" class="lvtCol" align="center"><input style="padding-top:2px" type="button" onclick="del()" value="刪除" style="padding-top:2px" '.$edit.'></td>
						<td class="lvtCol" align="right" colspan="3">'.$first_last.$next_final.'&nbsp;資料筆數：'.$total_rows.'&nbsp;&nbsp;&nbsp;頁數：'.$now_page.'/'.$total_page.'</td>
					  </tr>';
		}else{
			//當沒有資料的時候
			$list = '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
						<td align="center" colspan="4">沒有任何資料</td>
					 </tr>';
		}
	}else
		die('資料庫發生錯誤');
	///權限列表 end///

	$xtpl->assign('add',$edit);
	$xtpl->assign('name',$_SESSION['session_name']);
	$xtpl->assign('authority_list',$list);
	$xtpl->parse('table');
	$xtpl->parse('main.table');	
?>
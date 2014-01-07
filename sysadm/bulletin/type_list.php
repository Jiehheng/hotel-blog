<?php
	header("Cache-control: private");
    if ($check != 'kingfor') exit;

	$now_page=intval($_POST['now_page']); //第幾頁
	if (empty($now_page) or !is_numeric($now_page))
		$now_page = 1;	
	$page_row = 10; //每頁幾筆	
	$start = ($now_page-1)*$page_row; //資料分頁
	
	$sql="SELECT SQL_CALC_FOUND_ROWS * FROM sys_bulletin_type order by sort asc limit {$start},{$page_row}";
	$rs=$db->Execute($sql);
    if ($rs)
	{
		$rs2=$db->Execute("SELECT FOUND_ROWS()"); //分頁專用函式 for mysql
		list($total_rows)=$db->fetch_row($rs2);
		$total_page=ceil($total_rows/$page_row);
		
		if ($now_page==1)
			$first_last='';
		else
			$first_last='<a href="javascript:;" onclick="change_page(\'1\')">第一頁</a>&nbsp;<a href="javascript:;" onclick="change_page(\''.($now_page-1).'\')">上一頁</a>&nbsp;';

		if ($now_page==$total_page)
			$next_final='';
		else
			$next_final='<a href="javascript:;" onclick="change_page(\''.($now_page+1).'\')">下一頁</a>&nbsp;<a href="javascript:;" onclick="change_page(\''.$total_page.'\')">最末頁</a>&nbsp;';

		if ($total_rows>0)
		{
			while ($rows=$db->fetch_assoc($rs))
			{
				if (in_array('action',$_SESSION['session_authority_list'])) //檢測 行為 的權限
					$edit='';
				else
					$edit='style="display:none"';
				$list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
							<td align="center"><input type="checkbox" value="'.$rows['serial_id'].'" '.$edit.'></td>
							<td align="center">'.$rows['sort'].'</td>
							<td align="center">'.$rows['caption'].'</td>
							<td align="center">							
								<input type="button" onclick="edit(\''.$rows['serial_id'].'\');" value="修改" style="padding-top:2px" '.$edit.'>							
							</td>
						</tr>';
			}
			$list .= '<tr>
					<td width="30px" class="lvtCol" align="center"><input type="button" onclick="del()" value="刪除" style="padding-top:2px"></td>
					<td class="lvtCol" align="right" colspan="3">'.$first_last.$next_final.'&nbsp;資料筆數：'.$total_rows.'&nbsp;&nbsp;&nbsp;頁數：'.$now_page.'/'.$total_page.'</td>
				  </tr>';	
		}else{
			$list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
				<td colspan="4" align="center">無任何資料</td>
			</tr>';
		}
	}else
		die('資料庫發生錯誤');

	$xtpl->assign('type_list', $list);
	$xtpl->parse('table');
    $xtpl->parse('main.table');
	
?>
<?php
	header("Cache-control: private");
	if ($check != 'kingfor') exit;

	////POST參數
	$now_page=intval($_POST['now_page']); //第幾頁
	if (empty($now_page) or !is_numeric($now_page)) $now_page=1;

	$page_row=10; //每頁幾筆
	
	///區域帶出
	if(!$rs=$db->Execute("select * FROM `sys_depart_mb` order by `sort` asc")) die('資料庫發生錯誤');
	while ($rows=$db->fetch_assoc($rs)) $area[$rows["serial_id"]] = $rows["caption"];
	
	///帳號列表
	$start = ($now_page-1)*$page_row; //資料分頁
	$sql = "SELECT SQL_CALC_FOUND_ROWS * from `sys_customer_temp` limit {$start},{$page_row}";
	$rs = $db->Execute($sql);
    if ($rs)
	{
		$rs2=$db->Execute("SELECT FOUND_ROWS()"); //分頁專用函式 for mysql
		list($total_rows)=$db->fetch_row($rs2);
		$total_page=ceil($total_rows/$page_row);
		if ($total_rows>0)
		{
			if (in_array('action',$_SESSION['session_authority_list'])) //檢測 行為 的權限
				$edit='';
			else
				$edit='style="display:none"';
			
			while ($rows=$db->fetch_assoc($rs))
			{
				if($rows['License']) $license = '有'; else $license = '無';
				/*$job_array = unserialize($rows['job']);
				for($i=0;$i<count($job_array);$i++)
				{
					$job_cont .= $job_fid[$job_array[$i]].'<br>';
				}*/
				$list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
							<td align="center"><input type="checkbox" value="'.$rows['serial_id'].'" '.$edit.'></td>
							<td align="left">'.$area[$rows["city"]].'</td>
							<td align="left">'.$rows['name'].'</td>
							<td align="left">'.$rows['Contacter'].'</td>
							<td align="left">'.$rows['tele'].'</td>
							<td align="center">'.$license.'</td>
							<td align="center">'.$rows['creatime'].'</td>
							<td align="center">
								<input type="button" onclick="edit(\''.$rows['serial_id'].'\');" value="修改" style="padding-top:2px" '.$edit.'>
							</td>
						</tr>';
				unset($job_cont,$want2join);
			}
			if ($now_page==1)
				$first_last='';
			else
				$first_last='<a href="javascript:;" onclick="change_page(\'1\')">第一頁</a>&nbsp;<a href="javascript:;" onclick="change_page(\''.($now_page-1).'\')">上一頁</a>&nbsp;';
			if ($now_page==$total_page)
				$next_final='';
			else
				$next_final='<a href="javascript:;" onclick="change_page(\''.($now_page+1).'\')">下一頁</a>&nbsp;<a href="javascript:;" onclick="change_page(\''.$total_page.'\')">最末頁</a>&nbsp;';
			$list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
					<td class="lvtCol" align="center"><input type="button" onclick="del()" value="刪除" style="padding-top:2px" '.$edit.'></td>
					<td class="lvtCol" align="right" colspan="7">'.$first_last.$next_final.'&nbsp;資料筆數：'.$total_rows.'&nbsp;&nbsp;&nbsp;頁數：'.$now_page.'/'.$total_page.'</td>
				  </tr>';
		}else{
			$list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
				<td colspan="8" align="center">無任何資料</td>
			</tr>';
		}
	}
	else die('資料庫發生錯誤');

	$xtpl->assign('add',$edit);
	$xtpl->assign('unit',$edit);
	$xtpl->assign('export',$edit);
	$xtpl->assign('account_list', $list);
	// 判斷是否顯示出權限修改的 Button
	if (in_array('list',$_SESSION['session_authority']['4f54d3b83a1f1986599eef6914849306']))
	{
		 $show_authority_button='<input type="button" style="padding-top:2px" value="權限管理" onclick="window.location.href=\'../authority\'" {unit} >&nbsp;';
		 $xtpl->assign('show_authority_button',$show_authority_button);
	}
	$xtpl->parse('main.table');
?>
<?php
	header("Cache-control: private");
	if ($check != 'kingfor') exit;

	////POST參數
	$now_page=intval($_POST['now_page']); //第幾頁
	if (empty($now_page) or !is_numeric($now_page)) $now_page=1;
	$page_row=10; //每頁幾筆
	
	// 區域抽離
	$sql_dist=$db->Execute("SELECT `caption`,`stid` FROM `case_district`");
	while ($rowsDist=$db->fetch_assoc($sql_dist)) $GetDistCaption[$rowsDist['stid']]=$rowsDist['caption'];
	
	///帳號列表
	$start = ($now_page-1)*$page_row; //資料分頁
	//$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,c.caption as authority_caption from sys_members as a left join sys_authority as c on a.authority=c.serial_id limit {$start},{$page_row}";
	$sql = "SELECT * FROM case_list limit {$start},{$page_row}";
	$rs = $db->Execute($sql);
    if ($rs)
	{
/*		// 抓出 eip 中 Unit 的 serial_id
		$rsUnit = $db->Execute("select serial_id,caption FROM sys_unit");
		while ($rowsUnit=$db->fetch_assoc($rsUnit))
			$job_Unit[$rowsUnit['serial_id']]=$rowsUnit['caption'];
*/				
		// 抓出 member 以 serial_id 換名
		$rsWhos = $db->Execute("select serial_id,name FROM sys_members");
		while ($rowsWhos=$db->fetch_assoc($rsWhos))
			$Whos_fid[$rowsWhos['serial_id']]=$rowsWhos['name'];
	
		$rs2=$db->Execute("SELECT FOUND_ROWS()"); // 分頁專用函式 for mysql
		list($total_rows)=$db->fetch_row($rs2);
		$total_page=ceil($total_rows/$page_row);
		if ($total_rows>0)
		{
			if (in_array('action',$_SESSION['session_authority_list'])) // 檢測 行為 的權限
				$edit='';
			else
				$edit='style="display:none"';
			
			while ($rows=$db->fetch_assoc($rs))
			{
				$Dist_array = unserialize($rows['ob_District']);
				for($i=0;$i<count($Dist_array);$i++)
				{
					if($Dist_array[$i]=='a')$Dist_cont='不拘';
					else
					{
						if(isset($Dist_cont)) $Dist_cont.=','.$GetDistCaption[$Dist_array[$i]]; else $Dist_cont=$GetDistCaption[$Dist_array[$i]];
					}
				}
				if($rows['service_bywho']) $service_user = userinfo($rows['service_bywho'])->name;
				else
				{
					// 配對人員列表
					$sql_dist=$db->Execute("SELECT `serial_id`,`name` FROM `sys_members` WHERE `serviceis` = '1' ORDER BY `create_time`");
					while ($rowsMbs=$db->fetch_assoc($sql_dist))
					{
						$service_user .= '<option value="'.$rowsMbs["serial_id"].'">'.$rowsMbs["name"].'</option>';
					}
					$service_user = '<select name="service_bywho" id="service_bywho" onchange="up_sev(\''.$rows['serial_id'].'\',this.options[this.options.selectedIndex].value)"><option value="0">未指派</option>'.$service_user.'</select>';
				
				} //$service_user = '<span style="color:#FF0000">未指派</span>';
				$list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
							<td align="center"><input type="checkbox" value="'.$rows['serial_id'].'" '.$edit.'></td>
							<td align="center">'.$rows['ob_name'].'</td>
							<td align="left">&nbsp;'.$Dist_cont.'</td>
							<td align="center" style="width:80px;">'.$Whos_fid[$rows['whos']].'</td>
							<td align="center" style="width:80px;">'.$service_user.'</td>
							<td align="center">'.$rows['creatime'].'</td>
							<td align="center">
								<input type="button" onclick="edit(\''.$rows['serial_id'].'\');" value="修改" style="padding-top:2px" '.$edit.'>
							</td>
						</tr>';
				unset($Dist_cont,$service_user);
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
					<td class="lvtCol" align="right" colspan="6">'.$first_last.$next_final.'&nbsp;資料筆數：'.$total_rows.'&nbsp;&nbsp;&nbsp;頁數：'.$now_page.'/'.$total_page.'&nbsp;</td>
				  </tr>';
		}else{
			$list .= '<tr bgcolor="white" onMouseOver="this.className=\'lvtColDataHover\'" onMouseOut="this.className=\'lvtColData\'">
				<td colspan="7" align="center">無任何資料</td>
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
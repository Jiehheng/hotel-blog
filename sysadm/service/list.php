<?php
	header("Cache-control: private");
	if ($check != 'kingfor') exit;

	//// 權限判斷 Start ////
	if (in_array('action',$_SESSION['session_authority_list'])) $DeleteButton = '<input type="submit" value="刪除" name="B1" class="lvtCol2">';

	$Hour_count = "<input class='lvtCol2' LANGUAGE=javascript onclick='return window.open(\"Hour_count.php?cal_number=".$_POST['cal_number']."&ser_type=".$_POST['ser_type']."\",\"test\",\"width=800,height=400,resizable=no,scrollbars=1, toolbar=no, titlebar=no, left=200, top=226, screenX=100, screenY=126\");' type='button' value='維護時數統計' style='font-family: Arial'>";

	// Function START-------------------------------------------------------------------------
	function GetCustID($custID)
	{
		$rs = mysql_query("SELECT serial_id FROM customer WHERE id = '".$custID."'");//"SELECT serial_id FROM customer WHERE id = '".$custID."'"
		if(mysql_num_rows($rs)){
			$val_array = mysql_fetch_array($rs);
			return $val_array[0];
		}else return 'NULL';
	}
	function unGetCustID($custID)
	{
		$rs = mysql_query("SELECT id FROM customer WHERE serial_id = '".$custID."'");//
		if(mysql_num_rows($rs)){
			$val_array = mysql_fetch_array($rs);
			return $val_array[0];
		}else return 'NULL';
	}
	// Function END---------------------------------------------------------------------------
	//// 挑選案件 Start ////
	if($_POST['cal_number']) // 下拉是挑選案件
	{
		$WHERE = " WHERE website.customer = '{$_POST['cal_number']}'";
	}
	
	// 以廠商編號或廠商關鍵字做搜尋
	if($_POST['Search_word'])
	{
		$CustID = GetCustID($_POST['Search_word']);
		if(isset($WHERE)) $WHERE .= " AND "; else $WHERE = " WHERE ";
		$WHERE.= "(website.caption LIKE '%{$_POST['Search_word']}%'";
		if($CustID)$WHERE.= " OR website.customer = '{$CustID}'";
		$WHERE.= ")";
	}
	$xtpl->assign('Search_word', $_POST['Search_word']);

	 // 下拉是挑選問題種類
	if($_POST['ser_type'] != '')
	{
		if(isset($WHERE)) $WHERE .= " AND "; else $WHERE = " WHERE ";
		$WHERE .= "service_record.ser_type = '{$_POST['ser_type']}'";
		switch($_POST['ser_type'])
		{
			case "業務問題":
				$xtpl->assign('ser_type_selected_1', 'selected');
				break;
			case "頁面問題":
				$xtpl->assign('ser_type_selected_2', 'selected');
				break;
			case "程式問題":
				$xtpl->assign('ser_type_selected_3', 'selected');
				break;
		}
	}
	
	 // 下拉是挑選狀態種類
	if($_POST['ser_status'])
	{
		switch($_POST['ser_status'])
		{
			case "未處理":
				$xtpl->assign('ser_status_selected_1', 'selected');
				if(isset($WHERE)) $WHERE .= " AND "; else $WHERE = " WHERE ";
				$WHERE .= "service_record.ser_status = '{$_POST['ser_status']}'";
				break;
			case "已處理":
				$xtpl->assign('ser_status_selected_2', 'selected');
				if(isset($WHERE)) $WHERE .= " AND "; else $WHERE = " WHERE ";
				$WHERE .= "service_record.ser_status = '{$_POST['ser_status']}'";
				break;
		}
	}
	
	//// 讀取案件
	$Cust_sql = "SELECT serial_id,id,caption FROM customer ORDER BY id";
	$Cust_sql_rs = mysql_query($Cust_sql);
	while($row_cust = mysql_fetch_assoc($Cust_sql_rs))
	{
		if($_POST['cal_number'] == $row_cust['id'])
			$selected = "selected";
		else
			$selected = null;

		$CaseList .= "<option value=\"".$row_cust['serial_id']."\" ".$selected.">".$row_cust['id']."_".$row_cust['caption']."</option>";
	}

	$rows = array();
	$PagesSQL = "SELECT service_record.*,website.caption,website.customer
			FROM service_record
			LEFT JOIN website ON service_record.cal_number = website.serial_id
			{$WHERE}
			ORDER BY ser_id DESC";

	// 處理分頁前趨
	$RecCount = mysql_num_rows(mysql_query($PagesSQL));
	if (($RecCount % 10)==0) $TotalPage = intval($RecCount/10); else $TotalPage = intval($RecCount/10) + 1;
	$nRow = ($nPage-1)*10;
	if(isset($_POST['page']) & ($TotalPage>=$_POST['page']))
	{
		$nPage = $_POST['page'];
		$SPage = ($nPage-1)*10;
	}
	else
	{
		$nPage = 1;$SPage = 0;
	}
	$PagesSQL .= " LIMIT {$SPage} , 10";

	$result = mysql_query($PagesSQL);
	$field_num = mysql_num_fields($result); // 共有多少欄位
	$a = 1;
	
	while($row = mysql_fetch_assoc($result))
	{
		$field_name = array();
		$field_var = array();
		$combine_var = array();
		for($k=0;$k<$field_num;$k++)
		{
			array_push($field_name,mysql_field_name($result, $k));
			array_push($field_var,$row[mysql_field_name($result, $k)]);
			$combine_var = array_combine($field_name,$field_var);
			// echo mysql_field_name($result, $k).'&nbsp;=&nbsp;'.$row[mysql_field_name($result, $k)].'<br/>';
			$rows[$a]=$combine_var;
		}
		$a++;
	} // while($row = mysql_fetch_assoc($result)) end

	$rowsize = count($rows); // count times
	for ($i=1; $i<=$rowsize; $i++)
	{
		$rows[$i]['cal_number'] = unGetCustID($rows[$i]['customer']);
		$rows[$i]['ser_question'] = $rows[$i]['ser_question'];//substr($rows[$i]['ser_question'],0,60)."...";
		if($rows[$i]['ser_respond'] != '')
		{
			$rows[$i]['ser_respond'] = $rows[$i]['ser_respond'];//substr($rows[$i]['ser_respond'],0,70)."...";
		}
		if($rows[$i]['accid'])
		{
			$temp = $rows[$i]['accid'];
			$FindResult = mysql_query("SELECT * FROM account WHERE serial_id = '".$temp."'");
			$object = mysql_fetch_object($FindResult);
			$rows[$i]['acctwname'] = $object -> name."(".$object -> account.")";
		}else{
			$rows[$i]['acctwname'] = 'null';
		}

		if($rows[$i]['ser_respondtime'] == '0000-00-00 00:00:00')
			$rows[$i]['time'] = '00:00:00';
		else
			$rows[$i]['time'] = date('h:i:s', (strtotime($rows[$i]['ser_respondtime']) - strtotime($rows[$i]['ser_datetime']))/60); // 解決時間

		if($rows[$i]['ser_respond'] == "")
		{
			$rows[$i]['ser_respond'] = '目前尚無回覆說明';
			$rows[$i]['ser_respond_all'] = '目前尚無回覆說明';
		}else{
			$rows[$i]['ser_respond'] = nl2br($rows[$i]['ser_respond']);
			$rows[$i]['ser_respond_all'] = nl2br($rows[$i]['ser_respond_all']);
		}
		
		switch($rows[$i]['ser_status'])
		{
			case "未處理":
				$rows[$i]['ser_status'] = "<font color=red><b>未處理</b></font>";
				break;
			case "已處理":
				$rows[$i]['ser_status'] = "<font color=green><b>已處理</b></font>";
				break;
		}
		
		// 如果尚未回覆時不顯示 Ajax 欄目
		if($rows[$i]['ser_tinyint'] == 'Y'){
			$custom_width="class=\"custom-width\" title=\"回覆人員:&nbsp;".$rows[$i]["acctwname"]."&nbsp;&nbsp|<div style='margin-bottom:5px;'><span style='font-size:9.5pt;'>".$rows[$i]["ser_respond"]."</span></div><hr>
			<div style='width:100%;height:15px'>
			<span style='color:#FF0000;font-size:9.5pt;float:right;'>".$rows[$i]["ser_respondtime"]."</span>
			<span style='color:#666666;font-size:9.5pt;float:right;'>維護時數:".$rows[$i]["ser_hour"]."時".$rows[$i]["ser_min"]."分&nbsp;&nbsp;</span>
			</div>\"";
			$xtpl->assign('custom_width', $custom_width);
		}else $xtpl->assign('custom_width', '');

		//// 刪除鈕控制
		$rows[$i]['DeleteButton'] = $DeleteButton;

		$rows[$i]['ser_question'] = nl2br($rows[$i]['ser_question']);
		$xtpl->assign('DATA', $rows[$i]);
		$xtpl->parse('main.table.TaskList');
	}
	
	/// 翻頁選單 Start ///
	//$nPage						//目前筆數
	$allPage=$TotalPage;			//總筆數
	
	$Target = $nPage % 10;			//0
	$Targeta = floor($nPage / 10);	//1
	if($Target=='0')$Targeta-=1;	//1->0
	$Targetb = $Targeta * 10 +1;	//11
	$Targetc = ($Targeta+1) * 10;	//20
	if($Targetc>=$allPage)$Targetc=$allPage;	//78
	$scriptpage='./?page=';
	$Page_table='
	<DIV style="padding:0;border:0;">
	  <span>
		<span style="cursor:pointer;" onclick="javascript:document.custom.page.value='.($Targetb-1).';go();"><img src="images/p3_back.jpg" width="20" height="15" border="0" style="vertical-align:middle;';if($nPage<='10')$Page_table.='display:none';$Page_table.='"/></span>&nbsp;
		<span style="cursor:pointer;" onclick="javascript:document.custom.page.value='.($nPage-1).';go();"><img src="images/p3_back2.jpg" width="15" height="15" border="0" style="vertical-align:middle;';if($nPage<='1')$Page_table.='display:none';$Page_table.='"/></span>
	  </span>
	  <span>';
		for($x=$Targetb;$x<=$Targetc;$x++){
			$Page_table.='&nbsp;<span style="cursor:pointer;';
			if($x==$nPage)$Page_table.='font-weight:bold; text-decoration:underline;';
			$Page_table.='" onclick="javascript:document.custom.page.value='.$x.';go();">'.$x.'</span>';
		}$Page_table.='
	  </span>
	  <span>&nbsp;
		<span style="cursor:pointer;" onclick="javascript:document.custom.page.value='.($nPage+1).';go();"><img src="images/p3_next2.jpg" width="15" height="15" border="0" style="vertical-align:middle;';if($nPage>=$allPage)$Page_table.='vertical-align:text-bottom;display:none';$Page_table.='"/></span>&nbsp;
		<span style="cursor:pointer;" onclick="javascript:document.custom.page.value='.($Targetc+1).';go();"><img src="images/p3_next.jpg" width="20" height="15" border="0" style="vertical-align:middle;';if($Targetc>=$allPage)$Page_table.='vertical-align:text-bottom;display:none';$Page_table.='"/></span>
	  </span>
	</DIV>';
	$xtpl->assign('nPage', $nPage);
	$xtpl->assign('totalPage', $allPage);
	$xtpl->assign('PAGE', $Page_table);
	
	//// 任務列表 End ////

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$xtpl->assign('CaseList', $CaseList); // 案件列表 Option
	$xtpl->assign('Hour_count', $Hour_count);
	$xtpl->parse('main.table');
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
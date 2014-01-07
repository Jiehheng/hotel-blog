<?php
	// 防止SQL Injection: 執行 select 時使用
	function filter_sql_injection($string)
	{
		$db=new db_action;  // 連結狀態下才能使用
		return addcslashes(mysql_real_escape_string(trim($string)),"%_");// 簡易版
	}
	
	// 防止XSS過濾:寫入DB時使用
	function filter_xss($string)
	{
		if (!get_magic_quotes_gpc())
			return addslashes(htmlspecialchars(trim($string)));
		else
			return htmlspecialchars(trim($string));
	}
	
	// 檢測是否具有權限
	function check_authority($type)
	{
		// 注意session timeout的情形
		global $_SESSION,$intranet_ip; // intranet_ip 在 db_config.php中
		
		// 先判斷是否逾時
		if (empty($_SESSION['session_serial_id']))
		{
			session_destroy();
			echo html_start().'<script>alert("系統逾時,請重新登入"); location.href="../index.php"</script>'.html_end();
			exit;
		}
		
		$db = new db_action;
		$dir_name = basename(dirname($_SERVER['PHP_SELF']));
		$sql = "select serial_id from sys_module where directory='{$dir_name}'"; // 取模組
		$rs = $db->Execute($sql);
		if ($rs)
			list($module_id)=$db->fetch_row($rs); // 取得目前模組的編號
		else
			die('資料庫存取發生異常');
			
		$sql="select b.authority from sys_account as a inner join sys_authority as b on a.authority=b.serial_id where a.serial_id='{$_SESSION['session_serial_id']}'";
		$rs1=$db->Execute($sql);
		$authority=array();
		if ($rs1)
		{
			list($temp)=$db->fetch_row($rs1);
			$authority=unserialize($temp);
			$_SESSION['session_authority']=$authority; // 在這裡即時刷新權限清單
			$_SESSION['session_authority_list']=$authority[$module_id]; // 取得在此模組中的權限細項
		}
		else die('資料庫存取發生異常');
		
		$module_ids_arry=array();		
		foreach($authority as $m_id => $action)
			$module_ids_arry[]=$m_id;
		
		// 檢測權限細項
		switch ($type)
		{
			case 'list':
				// 只需要檢查是否出現在權限清單即可
				if (!in_array($module_id,$module_ids_arry))
				{
					// session_destroy();
					echo html_start().'<script>alert("您無權限");javascript:window.history.go(-1);</script>'.html_end();
					exit;
				}
				break;
			case 'action':
			case 'manage':
				if (!in_array($type,$authority[$module_id]))
				{
					// session_destroy();
					echo html_start().'<script>alert("您無權限");javascript:window.history.go(-1);</script>'.html_end();
					exit;
				}
				break;
		}
	}
	// 左邊選單
	function menu()
	{
		global $SESSION; // 注意session timeout的情形
		if (empty($_SESSION['session_authority']))
			return;
		$db=new db_action;
		$module_array=array();
		$sql="select directory from sys_module where display = 'y'";
		$rs1=$db->Execute($sql);
		while ($rows1=$db->fetch_assoc($rs1))
			$directory_array[]=$rows1['directory'];
		$dir_name = basename(dirname($_SERVER['PHP_SELF']));
		
		$rs=$db->Execute("SELECT up_layer FROM sys_module WHERE directory = '".$dir_name."'");
		$row=$db->fetch_assoc($rs);
		if($row['up_layer'])$dir_name = $row['up_layer'];
		
		foreach($_SESSION['session_authority'] as $module_id => $action)
			$module_array[]=$module_id;
		
		$modules=implode("','",$module_array);
		$sql="select * from sys_module where serial_id in ('{$modules}') order by sort asc";
		$rs=$db->Execute($sql);
		if ($rs)
		{
			while ($row1=$db->fetch_assoc($rs))
			{
				if($dir_name==$row1['directory'])$style_lnk = ' style="font-weight:bolder;color:#0066CC;"';
				if($row1['display']=='y')
					if (in_array($dir_name,$directory_array))
					{
						$menu .= '<li><a href="../'.$row1['directory'].'/index.php"'.$style_lnk.'>'.$row1['caption'].'</a></li>';
					}
					else
					{
						$menu .= '<li><a href="'.$row1['directory'].'/index.php"'.$style_lnk.'>'.$row1['caption'].'</a></li>';
					}
					//$menu .= '<li><a href="../'.$row1['directory'].'/index.php"'.$style_lnk.'>'.$row1['caption'].'</a></li>';
				unset($style_lnk);
			}
		}else
			die('資料庫發生錯誤');
		return $menu;
	}
	
	// 寫入系統紀錄
	function WriteLOG($string)
	{
		global $_SESSION,$remoteaddr,$datetime; // intranet_ip 在 db_config.php中
		return mysql_query("INSERT INTO `sys_log` ( `account` , `remoteaddr` , `datetime` , `work`) VALUES ('{$_SESSION['session_account']}', '{$remoteaddr}', '{$datetime}', '{$string}')"); // log record
	}
	
	// 轉換 UTF-8 > BIG5
	function twu2b($string)
	{
		return mb_convert_encoding($string,'big5','utf8');
	}
	
	// 固定取多少字
	function cut_content($a,$b){
		$a = strip_tags(htmlspecialchars_decode($a));
		$sub_content = mb_substr($a, 0, $b, 'UTF-8');
		if (strlen($a) > strlen($sub_content)) $sub_content .= " ...";
		return $sub_content;
	}

	// 發生錯誤時,以HTML BIG5顯示,防止瀏灠器用UTF-8變亂碼,有些地方用header輸出時就不能加,否則會跳錯
	function html_start()
	{
		return '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
	}
	function html_end()
	{
		return '</body></html>';
	}

	// 錯誤提示
	function Fail($x){
		echo'<script language="JavaScript" charset="utf-8">alert("ERROR! '.$x.' ");javascript:history.back();</script>'.html_end();
		exit;
	}
	
	// 轉跳提示
	function AlertJump($x,$y){
		if($x)$alert='alert("Message! '.$x.' ");';
		echo'<script language="JavaScript" charset="utf-8">javascript:'.$alert.'location.replace("'.$y.'");</script>'.html_end();
		exit;
	}

	// 代出使用者資料
	function userinfo($userid){
		$db = new db_action;
		if (!$rsSS = $db->Execute("SELECT * FROM `sys_members` WHERE `serial_id`='".$userid."'")) die('資料庫發生錯誤');
		$rowServiceIS = $db->fetch_object($rsSS);
		return $rowServiceIS;
	}
	
	// 錯誤訊息帶出新頁
	function errorPage($msg)
	{
		global $Html;
		$xtpl = new XTemplate('error.html');
		$xtpl->assign('HTML', $Html);
		$xtpl->assign('do_action',$msg);
		$refurl = $_SERVER['HTTP_REFERER'];
		$xtpl->assign('refurl',$refurl);
		$xtpl->parse('main');
		$xtpl->out('main');
		exit;	
	}
?>
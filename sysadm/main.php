<?php
	header("Cache-control: private");
	require_once('../config/db_action.php');
	require_once('../lib/function.php');
	require_once('../lib/Xtemplate.class.php');
	$xtpl = new XTemplate('main.htm');
	$xtpl -> assign('web_title',$web_title);
	$db = new db_action;
	session_start();

	if(empty($_SESSION['session_serial_id']))
	{
		session_destroy();
		header("Location: index.php");
	}

	//取權限
	$sql = "select b.authority from sys_account as a inner join sys_authority as b on a.authority=b.serial_id where a.serial_id='{$_SESSION['session_serial_id']}'";
	$rs1 = $db->Execute($sql);
	if ($rs1)
	{
		list($temp) = $db->fetch_row($rs1);
		unset($_SESSION['session_authority']);
		$_SESSION['session_authority'] = unserialize($temp);
	}
	else die('資料庫存取發生異常');

	///左選單
	$xtpl->assign('menu',menu());

	///右邊內容 start////
	//取帳號資料
	$sql="select * from sys_account";
	$rs3=$db->Execute($sql);
	if ($rs3)
	{
		while ($rows2=$db->fetch_assoc($rs3))
			$account[$rows2['serial_id']]=$rows2['account'];
	}
	
	
	// 公佈欄資料 Start
	$DataGridSQL = "SELECT serial_id,title,news_time FROM sys_bulletin WHERE s_state='o' ORDER BY news_time DESC";
	$result = $db->Execute($DataGridSQL);
	if(!$result) Fail("連線資料庫失敗 請稍後重新執行");
	function big5_substr($StrInput,$strStart,$strLen)
	{
		$StrInput_force = mb_substr(strip_tags($StrInput), $strStart, $strLen, "UTF8");
		if(mb_strlen($StrInput,'UTF8')>$strLen) $StrInput_force.='...';
		return $StrInput_force;
	}
	
    $field_num = mysql_num_fields($result); // 共有多少欄位
    $a = 1;

	while ($row = $db->fetch_assoc($result))
    {
     		$field_name = array();
          	$field_var = array();
            $combine_var = array();
            for($k=0;$k<$field_num;$k++)
            {
            		array_push($field_name,mysql_field_name($result, $k));
            		array_push($field_var,$row[mysql_field_name($result, $k)]);
            		$combine_var = array_combine($field_name,$field_var);
            		$rows[$a]=$combine_var;
            }
            $a++;
    } // while($row = mysql_fetch_assoc($result)) end

    $rowsize = count($rows); // count times
    for ($i=1; $i<=$rowsize; $i++)
    {
        $rows[$i][nottitle] = '<a href="javascript:void(window.open(\'./bulletin/?which=notice&notid='.$rows[$i][serial_id].'\', \'_self\'))">'.big5_substr($rows[$i][title],0,8).'<a/>';
        $rows[$i][notdatetime] = substr($rows[$i][news_time],0,10);
        $xtpl->assign('DATA', $rows[$i]);
        $xtpl->assign('ROW_NR', $i);
        $xtpl->parse('main.notice');
    }
	
	// 公佈欄資料 End
	///右邊內容 end////

	$xtpl->assign('list',$list);
	$xtpl->assign('name',$_SESSION['session_name']);
	$xtpl->parse('main');
	$xtpl->out('main');
?>
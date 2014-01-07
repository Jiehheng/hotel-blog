<?php
	header("Cache-control: private");
	if ($check != 'kingfor')
		exit;	
	
	// 資料庫連接
	if(!isset($_GET["notid"])) Fail("端口錯誤!");
	$rs = $db->Execute("SELECT A.*,B.name FROM `sys_bulletin` as A INNER JOIN `sys_account` as B ON (A.author=B.account) WHERE A.`serial_id` = '".$_GET["notid"]."'");
	if(!$rsPost=mysql_fetch_array($rs)) Fail("連線資料庫失敗 請稍後重新執行");

	$type = '||&nbsp;'.$alltype[($rsPost["news_typ"]-1)].'&nbsp;||';
	$titl = $rsPost["title"];
	$time = $rsPost["news_time"];
	$auth = $rsPost["name"];
	$view = html_entity_decode($rsPost["content"]);
	$sql = "UPDATE `sys_bulletin` SET `click` = `click`+1 WHERE `serial_id` = '".$_GET["notid"]."'";
	$db->Execute($sql);

	$xtpl->assign('notic_type',$type);
	$xtpl->assign('notic_title',$titl);
	$xtpl->assign('notic_time',$time);
	$xtpl->assign('notic_auth',$auth);
	$xtpl->assign('notic_content',$view);
	$xtpl->parse('table');
	$xtpl->parse('main.table');	
?>
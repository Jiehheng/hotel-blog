<?php
	require_once('../config/db_action.php');
	require_once('../lib/function.php');
	require_once('../lib/Xtemplate.class.php');
	$xtpl = new XTemplate('index.htm');
	
	session_start();
	if(empty($_SESSION['session_serial_id']))
	{
		$xtpl->assign('web_title',$web_title);
		$xtpl->parse('main');
		$xtpl->out('main');
	}else
		header("Location: main.php");
	exit;
?>
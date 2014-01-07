<?php
	/*
		在這個模組中擁有 行為(新,刪,修) 的權限就等同於擁有 管理 的權限
	*/
	header("Cache-control: private");
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
    include_once('../../lib/Xtemplate.class.php');
    $xtpl = new XTemplate('../template/index.htm');
	$xtpl -> assign('web_title',$web_title);
    $db=new db_action;	
	session_start();
	
	//左選單
	$xtpl->assign('menu',menu());
	
	$action_list=array('list','action','manage');
	if (empty($_GET['action']) or !in_array($_GET['action'],$action_list))
		$action='list';
	else
		$action=$_GET['action'];	
	//檢測權限,並且設定權限細項在$_SESSION中
	check_authority($action);
	
	if (empty($_GET['which']))
	{
		$html='list.htm';
		$php='list.php';		
	}else{
		$html=filter_xss($_GET['which']).'.htm';
		$php=filter_xss($_GET['which']).'.php';
	}		
	if (!file_exists($html) or !file_exists($php))
	{
		header('Location ../index.php');
		exit;
	}else{		
		$check='kingfor';		
		$xtpl->assign_file('load_page', $html); // 載入網頁
		include_once($php);
	}
		
	$xtpl->assign('name',$_SESSION['session_name']);	
	$xtpl->parse('main');
	$xtpl->out('main');
?>	
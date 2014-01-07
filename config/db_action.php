<?php
	require_once('db_config.php');	
	date_default_timezone_set('Asia/Taipei'); // 設定時區
	class db_action //躲白箱用
    {
		function db_action()
        {
			global $db_host,$db_user,$db_psword,$db_name;
			$link = mysql_connect($db_host,$db_user,$db_psword) or die (" 資料庫無法連線 ");

			mysql_query("SET NAMES utf8");
			mysql_query("SET CHARACTER_SET_CLIENT=utf8");
			mysql_query("SET CHARACTER_SET_RESULTS=utf8");

			$select_db=mysql_select_db($db_name,$link);
			if (!$select_db) die('DB select error');
		}
		function Execute($sql) //仿ADODB,如果哪天有出什麼緊急事件,可在這裡再針對SQL做一次動作
		{
			$rs=mysql_query($sql);
			if(!$rs)
				die("SQL錯誤:{$sql}<br />錯誤:".mysql_error());
			else
				return $rs;
		}
		function fetch_row($resource)
		{
			if (!is_resource($resource))
				die('fetch_row resource is not mysql result!');
			return mysql_fetch_row($resource);
		}
		function fetch_assoc($resource)
		{
			if (!is_resource($resource))
				die('fetch_assoc resource is not mysql result!');
			return mysql_fetch_assoc($resource);
		}
		function fetch_object($resource)
		{
			if (!is_resource($resource))
				die('fetch_object resource is not mysql result!');
			return mysql_fetch_object($resource);
		}
		function fetch_array($resource)
		{
			if (!is_resource($resource))
				die('fetch_array resource is not mysql result!');
			return mysql_fetch_array($resource);
		}
		function data_seek($resource1,$resource2)
		{
			return mysql_data_seek($resource1,$resource2);
		}
		function insert_id()
		{
			return mysql_insert_id();
		}
		function affected_rows()
		{
			return mysql_affected_rows();
		}
		function num_rows($resource)
		{
			return mysql_num_rows($resource);
		}
		function free($resource)
		{
			if (!is_resource($resource))
				die('free resource is not mysql result!');
			mysql_free_result($resource);
		}
	}
?>
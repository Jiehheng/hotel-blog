<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

	$db=new db_action;
    session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');

	$content=twu2b('"帳號","姓名","部門","身份",');
	foreach($_POST as $column => $value)
	{
		$columns[]=$column;
		$content .= twu2b("\"$value\",");
	}
	$content = rtrim($content,',');
	$content .= "\n";

	if (is_array($columns))
		$word=','.implode(',',$columns);
	else
		$word='';
	$sql="select account,name {$word} from sys_members";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows=$db->fetch_assoc($rs))
		{
			foreach($rows as $column => $value)
			{
				$content .= twu2b("=\"{$rows[$column]}\",");
			}
			$content=rtrim($content,',');
			$content .= "\n";
		}
	}else
		die('資料庫發生錯誤');

	ob_clean();
	header('Content-Disposition: attachment; filename="account.csv"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Type: application/xml; name="account.csv"');
	echo $content;
?>
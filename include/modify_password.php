<?php
$login_id = $_SESSION["login_id"];
$rs=$db->Execute("select * from `sys_members` where `serial_id`='{$login_id}'");
if ($db->num_rows($rs))
{
	$rs_User = $db->fetch_array($rs);
	$list = $rs_User;
}
?>
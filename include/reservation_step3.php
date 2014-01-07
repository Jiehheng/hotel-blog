<?php
if(!$_SESSION["Quene"]) Fail("暫存功能未有內容");
$Quene = unserialize($_SESSION["Quene"]);

foreach($Quene as $y => $u)
{
	$title = explode("_", $y);
	foreach($u as $o)
	{
		//echo "$o[0]_$o[1]_$o[2]_$o[3]_$o[4]<br />";
		$f_date .= $o[0].'<br />';
		$l_date .= $o[1].'<br />';
		$total_days .= $o[2].'日<br />';
		$rooms .= $o[3].'間<br />';
		if(substr_count($o[4],",")){
			$UnitPay = explode(",", $o[4]);
			foreach($UnitPay as $p){ $UnitPayS .= $p.'元<br />';$tmp_TotalPay = $tmp_TotalPay + $p;}
			
		}else
		{
			$UnitPayS .= $o[4].'元<br />';
			$tmp_TotalPay .= $o[3] * $o[4].'元<br />';
		}
	}
	//if(is_array($UnitPay)){ foreach($UnitPay as $p) $tmp_TotalPay = $tmp_TotalPay+$p;  $tmp_TotalPay .= '元<br />';}
	
	$list["tr"] .= '
		<tr class="mytr">
		<td valign="top" class="mytd1">【'.$title[1].'】<br />'.$title[2].'</td>
		<td valign="top" class="mytd1">'.$f_date.'</td>
		<td valign="top" class="mytd1">'.$l_date.'</td>
		<td valign="top" class="mytd1">'.$total_days.'</td>
		<td valign="top" class="mytd1">'.$rooms.'</td>
		<td valign="top" class="mytd1">'.$UnitPayS.'</td>
		<td valign="top" class="mytd1">'.$tmp_TotalPay.'</td>
		</tr>';
	unset($title,$f_date,$l_date,$total_days,$rooms,$UnitPayS,$tmp_TotalPay);
}

if($_SESSION["Record_Order"])
{
	$list["od"] = $_SESSION["Record_Order"];
	$list["li"] = $_SESSION["Record_Liver"];
	$list["py"] = $_SESSION["Record_Payer"];
}
elseif($_SESSION['login_id'])
{
	$user_tent = userinfo($_SESSION["login_id"]);
	$list["od"]["name"] = $user_tent->name;
	$list["od"]["sid"] = $user_tent->identity_id;
	$list["od"]["email"] = $user_tent->email;
	$list["od"]["tel"] = $user_tent->telephone;
	$list["od"]["phone"] = $user_tent->cellphone;
	$list["od"]["fax"] = $user_tent->fax;
	$list["od"]["addr"] = $user_tent->com_address;
}
?>
<?php
if(!$_SESSION["Quene"] || !$_SESSION["Record_Order"] || !$_SESSION["Record_Liver"] || !$_SESSION["Record_Payer"]) Fail("暫存功能未有內容");
if($_SESSION["verify"] != $_POST["verify"]) Fail("參數檢查碼錯誤,系統無法作業!");
$Quene = unserialize($_SESSION["Quene"]);
extract($_POST);
//echo '<pre>',print_r($_POST),print_r($_SESSION["Quene"]),print_r($_SESSION["Record_Order"]),print_r($_SESSION["Record_Liver"]),print_r($_SESSION["Record_Payer"]),'</pre>';
// 進行確認插入資料庫
$Cdata = serialize(array($cname,$caddress,$ctel));
$Xdata = serialize(array($xname,$xaddress,$xtel));
$Odata = serialize($_SESSION["Record_Order"]);
$Ldata = serialize($_SESSION["Record_Liver"]);
$Pdata = serialize($_SESSION["Record_Payer"]);
$Rdata = $_SESSION["Quene"];

if($db->Execute("INSERT INTO `creditrecord` (`txid`, `amount`, `pay_type`, `status`, `tid`, `Cdata`, `Xdata`, `error_code`, `error_desc`, `auth_code`, `Odata`, `Ldata`, `Pdata`, `Rdata`, `creatime`) VALUES 
('{$txid}', '{$amount}', '{$pay_type}', '{$status}', '{$tid}', '{$Cdata}', '{$Xdata}', '{$error_code}', '{$error_desc}', '{$auth_code}', '{$Odata}', '{$Ldata}', '{$Pdata}', '{$Rdata}', NOW())"))
{
	// 成功後扣除被訂之房間
	if($pay_type == 1 && $status == 1) $list["Message"] = '成功後扣除被訂之房間';
	else $list["Message"] = '信用卡支付失敗';
}
else Fail("新增失敗! 請重新檢查或聯繫管理員");

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
			$add_TotalPay = $add_TotalPay + ($o[3] * $o[4]);
		}
	}
	
	$list["tr"] .= '
		<tr class="mytr">
		<td style="text-align:left" class="mytd1">【'.$title[1].'】<br />'.$title[2].'</td>
		<td style="text-align:center" class="mytd1">'.$f_date.'</td>
		<td style="text-align:center" class="mytd1">'.$l_date.'</td>
		<td style="text-align:center" class="mytd1">'.$total_days.'</td>
		<td style="text-align:center" class="mytd1">'.$rooms.'</td>
		<td style="text-align:right" class="mytd1">'.$UnitPayS.'</td>
		<td style="text-align:right" class="mytd1">'.$tmp_TotalPay.'</td>
		</tr>';
	unset($title,$f_date,$l_date,$total_days,$rooms,$UnitPayS,$tmp_TotalPay);
}

?>
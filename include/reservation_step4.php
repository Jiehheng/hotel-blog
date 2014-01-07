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
			$add_TotalPay = $add_TotalPay + ($o[3] * $o[4]);
		}
	}
	//if(is_array($UnitPay)){ foreach($UnitPay as $p) $tmp_TotalPay = $tmp_TotalPay+$p;  $tmp_TotalPay .= '元<br />';}
	
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
$list["tr"] .= '
	<tr class="mytr">
	<td style="text-align:right" class="mytd1" colspan="6">總金額&nbsp;</td>
	<td style="text-align:right" class="mytd1">'.$add_TotalPay.'元</td>
	</tr>';

extract($_POST);
$list["Order"] = array("name"=>$Od_name, "sid"=>$Od_sid, "email"=>$Od_email, "tel"=>$Od_tel, "phone"=>$Od_phone, "fax"=>$Od_fax, "addr"=>$Od_addr);
$list["Liver"] = array("name"=>$Li_name, "sid"=>$Li_sid, "tel"=>$Li_tel, "phone"=>$Li_phone, "fax"=>$Li_fax);
$list["Payer"] = array("name"=>$Py_name, "sid"=>$Py_sid, "tel"=>$Py_tel, "phone"=>$Py_phone, "fax"=>$Py_fax, "company"=>$Py_company, "cid"=>$Py_cid, "address"=>$Py_address, "question"=>$question);

$_SESSION["Record_Order"] = $list["Order"];
$_SESSION["Record_Liver"] = $list["Liver"];
$_SESSION["Record_Payer"] = $list["Payer"];

// PrePare to Send Data to Credit Card Center
function request_uri() 
{ 
    if (isset($_SERVER['REQUEST_URI'])) 
    { 
        $uri = $_SERVER['REQUEST_URI']; 
    } 
    else 
    { 
        if (isset($_SERVER['argv'][0])){ 
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0]; 
        }else if (isset($_SERVER['QUERY_STRING'])){ 
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; 
        }else {  
            $uri = $_SERVER['PHP_SELF']; 
        }
 
}
return $uri;
}
srand((double)microtime()*1000000);
$rand_number = date("YmdHis").rand();
$verify = md5($CRarray["code_start"].'|'.$CRarray["mid"].'|'.$rand_number.'|'.$add_TotalPay.'|'.$CRarray["code_end"]);
$_SESSION["verify"] = $verify;
$r_url = $_SERVER['HTTP_HOST'].request_uri();
if(substr_count($r_url,"reservation_step4")) $r_url = str_replace('reservation_step4','reservation_step5',$r_url);

if($CRarray["debug"]) $list["submit"] = $CRarray["submit_debug"]; else $list["submit"] = $CRarray["submit"];
$list["Credit"] = array(
	'mid' => $CRarray["mid"],
	'txid' => $rand_number,
	'mode' => '1',
	'select_paymethod' => '1',
	'iid' => '0',
	'amount' => $add_TotalPay,
	'verify' => $verify,
	'access_key' => $CRarray["access"],
	'return_url' => 'http://'.$r_url,
	'cname' => $list["Order"]["name"],
	'caddress' => $list["Order"]["addr"],
	'ctel' => $list["Order"]["tel"],
	'cemail' => $list["Order"]["email"]
);
?>
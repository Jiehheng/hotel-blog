<?php
    include ("../HeadHtml.php"); // HTML 標頭
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
    include_once('../../lib/Xtemplate.class.php');
    $xtpl = new XTemplate('Hour_count.htm'); // Load Template File //
    session_start();
    if($_SESSION['accid'] == "")
    {
         header("Location: ./index.php");
    }

	extract($_GET);

	if($_GET["cal_number"]==""){
	$SQL="SELECT case_list.cal_name,case_list.cal_number FROM service_record,case_list WHERE service_record.cal_number = case_list.cal_number GROUP BY service_record.cal_number ORDER BY service_record.cal_number DESC";
	}else{
	$SQL="SELECT DISTINCT case_list.cal_name,case_list.cal_number FROM service_record,case_list WHERE case_list.cal_number = '".$_GET["cal_number"]."' GROUP BY service_record.cal_number";		
	}
		
	$res_one=mysql_query($SQL);
	$sertype=array("業務問題","頁面問題","程式問題","全部");	
	
	while($row_one=mysql_fetch_row($res_one)){
		$xtpl->assign('case_nname',$row_one[0]);
		if($_GET["ser_type"]==""){
			for($i=0;$i<=3;$i++){
				$xtpl->assign('ser_type', $sertype[$i]);
				if($sertype[$i] != "全部"){
				$SQL="SELECT accid FROM `service_record` WHERE cal_number = ".$row_one[1]." AND ser_type = '".$sertype[$i]."' AND accid != 0 GROUP BY accid ";
				}else{
				$SQL="SELECT accid FROM `service_record` WHERE cal_number = ".$row_one[1]." AND  accid != 0 GROUP BY accid ";
				}
				$res_three=mysql_query($SQL);
				while($row_three=mysql_fetch_row($res_three)){
					$SQL="SELECT acctwname,accname FROM account WHERE accid=".$row_three[0]."";
					$res_name=mysql_query($SQL);
					$row_name=mysql_fetch_assoc($res_name);
					if($row_name{"acctwname"} != ""){
					$xtpl->assign('acc_name',$row_name{"acctwname"}."(".$row_name{"accname"}.")");
					if($sertype[$i] != "全部"){
					$SQL="SELECT SUM(ser_hour) as server_hour,sum(ser_min) as server_min FROM service_record WHERE accid=".$row_three[0]." AND cal_number = ".$row_one[1]." AND ser_type = '".$sertype[$i]."'";
					}else{
					$SQL="SELECT SUM(ser_hour) as server_hour,sum(ser_min) as server_min FROM service_record WHERE accid=".$row_three[0]." AND cal_number = ".$row_one[1]."";
					}
					$res_count=mysql_query($SQL);
					$row_count=mysql_fetch_assoc($res_count);
					$chang_hour=0;
					if($row_count{"server_min"}>=60){
						$chang_hour=floor($row_count{"server_min"}/60);
						$mod_min=$row_count{"server_min"}%60;
					}else{
						$mod_min=$row_count{"server_min"};
					}
					if($mod_min==""){
						$mod_min=0;
					}
					$real_sh=($row_count{"server_hour"}+$chang_hour)."時".$mod_min."分";
					
					$xtpl->assign('server_hour',$real_sh);
					$xtpl->parse('main.table1.row.table2.row2.table3.row3');
					}
				}			
				
				$xtpl->parse('main.table1.row.table2.row2.table3');
				$xtpl->parse('main.table1.row.table2.row2');
			}
			
		}else{
				$xtpl->assign('ser_type', $_GET["ser_type"]);
				$SQL="SELECT accid FROM `service_record` WHERE cal_number = ".$row_one[1]." AND ser_type = '".$_GET["ser_type"]."' AND accid != 0 GROUP BY accid ";
				$res_three=mysql_query($SQL);
				while($row_three=mysql_fetch_row($res_three)){
					$SQL="SELECT acctwname,accname FROM account WHERE accid=".$row_three[0]."";
					$res_name=mysql_query($SQL);
					$row_name=mysql_fetch_assoc($res_name);
					if($row_name{"acctwname"} != ""){
					$xtpl->assign('acc_name',$row_name{"acctwname"}."(".$row_name{"accname"}.")");
					
					$SQL="SELECT SUM(ser_hour) as server_hour,SUM(ser_min) as server_min FROM service_record WHERE accid=".$row_three[0]." AND cal_number = ".$row_one[1]." AND ser_type = '".$_GET["ser_type"]."'";

					$res_count=mysql_query($SQL);
					$row_count=mysql_fetch_assoc($res_count);
					$chang_hour=0;
					if($row_count{"server_min"}>=60){
						$chang_hour=floor($row_count{"server_min"}/60);
						$mod_min=$row_count{"server_min"}%60;
					}else{
						$mod_min=$row_count{"server_min"};
					}
					if($mod_min==""){
						$mod_min=0;
					}
					$real_sh=($row_count{"server_hour"}+$chang_hour)."時".$mod_min."分";
					
					$xtpl->assign('server_hour',$real_sh);
					$xtpl->parse('main.table1.row.table2.row2.table3.row3');
					}		
				}
				$xtpl->parse('main.table1.row.table2.row2.table3');				
				$xtpl->parse('main.table1.row.table2.row2');
		}
		
		
		$xtpl->parse('main.table1.row.table2');
		$xtpl->parse('main.table1.row');
	}

    $xtpl->parse('main.table1');
    $xtpl->parse('main');
    $xtpl->out('main');
?>


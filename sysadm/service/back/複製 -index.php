<?php
    include ("../HeadHtml.php"); // HTML ���Y
    include_once('../Config.php');
    include_once('../Db_connect.php');
    include_once('../lib/DateFindWeek.php');  	
    include_once('../Xtemplate.class.php'); // load Xtemplate //
    $xtpl = new XTemplate('index.htm'); // Load Template File //
    $rows = array();
    session_start();

    //// �P�O�O�_���n�J start ////
    if($_SESSION['accid'] == "")
    {
         header("Location: ./index.php");
    }
    //// �P�O�O�_���n�J End ////

    //// �v���P�_ Start ////
    $MenuResult = mysql_Query("SELECT * FROM secondmenu WHERE accid = '$_SESSION[accid]' AND secname = '�ȪA�t��'");
    $MenuRows = mysql_fetch_assoc($MenuResult);
    if($MenuRows['del'] == '1')
        $DeleteButton = '<input type="submit" value="�R��" name="B1" class="lvtCol2">';
    else
        $DeleteButton = '<input type="submit" value="�R��" name="B1" class="lvtCol2" disabled>';
    //// �v���P�_ End ////

    if($_POST['cal_number'] == '') // �U�ԬO�D��ץ�
   	    $WHERE = "";
    else
   	    $WHERE = " AND service_record.cal_number = '$_POST[cal_number]'";

    if($_POST['ser_type'] != '') // �U�ԬO�D����D����
    {
   	    $WHERE .= " AND service_record.ser_type = '$_POST[ser_type]'";
   	    switch($_POST['ser_type'])
   	    {
   		    case "�~�Ȱ��D":
   			    $xtpl->assign('ser_type_selected_1', 'selected');
   			    break;
   		    case "�������D":
   			    $xtpl->assign('ser_type_selected_2', 'selected');
   			    break;
   		    case "�{�����D":
   			    $xtpl->assign('ser_type_selected_3', 'selected');
   			    break;
   	    }
    }	
   	    
    if($_POST['ser_status'] != '') // �U�ԬO�D�窱�A����
    {
   	    $WHERE .= " AND service_record.ser_status = '$_POST[ser_status]'";
   	    switch($_POST['ser_status'])
   	    {
    	    case "���B�z":
   			    $xtpl->assign('ser_status_selected_1', 'selected');
    		    break;
    	    case "�w�B�z":
    		    $xtpl->assign('ser_status_selected_2', 'selected');
    		    break;    			
   	    }
    }

   	    
    //// Ū���ץ�  Start //// 
    $result = mysql_query("SELECT case_list.cal_name,case_list.cal_number FROM service_record,case_list WHERE service_record.cal_number = case_list.cal_number GROUP BY service_record.cal_number ORDER BY service_record.cal_number DESC"); //SQL
    while($rows = mysql_fetch_assoc($result))
    {
        if($_POST['cal_number'] == $rows['cal_number'])
    	    $selected = "selected";
        else
    	    $selected = null;
            
	    $CaseList .= "<option value=".$rows['cal_number']." ".$selected.">".$rows['cal_number']."_".$rows['cal_name']."</option>";
    }       
    //// Ū���ץ�  End ////

    $rows = array();

    $PagesSQL = "SELECT service_record.*,case_list.cal_name,case_list.cal_number 
    	    FROM service_record,case_list 
    	    WHERE service_record.cal_number = case_list.cal_number    		
    	    $WHERE 
    	    ORDER BY ser_id DESC";       
             
    $result = mysql_query($PagesSQL);
    $field_num = mysql_num_fields($result); // �@���h�����
    $a = 1;
    while($row = mysql_fetch_assoc($result))
    {
     	    $field_name = array();
            $field_var = array();
            $combine_var = array();
            for($k=0;$k<$field_num;$k++)
            {
            	    array_push($field_name,mysql_field_name($result, $k));
            	    array_push($field_var,$row[mysql_field_name($result, $k)]);
            	    $combine_var = array_combine($field_name,$field_var);
            	    $rows[$a]=$combine_var;
            }
            $a++;
    } // while($row = mysql_fetch_assoc($result)) end

    $rowsize = count($rows); // count times
    for ($i=1; $i<=$rowsize; $i++)
    {
        
        $rows[$i][ser_question] = substr($rows[$i][ser_question],0,30)."...";
        if($rows[$i][ser_respond] != '')
        {
    	    $rows[$i][ser_respond] = substr($rows[$i][ser_respond],0,50)."...";
        }
        
        if($rows[$i][accid] != 0)
        {
    	    $temp = $rows[$i][accid];
    	    $FindResult = mysql_query("SELECT * FROM account WHERE accid = '$temp'");
    	    $object = mysql_fetch_object($FindResult);
    	    $rows[$i][acctwname] = $object -> acctwname."(".$object -> accname.")";
        }
        else
        {
    	    $rows[$i][acctwname] = 'null';
        }
    	    
        
        if($rows[$i][ser_respondtime] == '0000-00-00 00:00:00')
    	    $rows[$i][time] = '00:00:00';
        else
    	    $rows[$i][time] = date('h:i:s', (strtotime($rows[$i][ser_respondtime]) - strtotime($rows[$i][ser_datetime]))/60); // �ѨM�ɶ�

        if($rows[$i][ser_respond] == "")
    	    $rows[$i][ser_respond] = '�ثe�|�L�^�л���';
        else
    	    $rows[$i][ser_respond] = nl2br($rows[$i][ser_respond]);
        
        switch($rows[$i][ser_status])
        {
    	    case "���B�z":
    		    $rows[$i][ser_status] = "<font color=red><b>���B�z</b></font>";
    		    break;
    	    case "�w�B�z":
    		    $rows[$i][ser_status] = "<font color=green><b>�w�B�z</b></font>";
    		    break;    			
        }
        
        //// �R���s���� Start ////
        $rows[$i][DeleteButton] = $DeleteButton;
        //// �R���s���� End ////        
            
        $rows[$i][ser_question] = nl2br($rows[$i][ser_question]);
	    $xtpl->assign('DATA', $rows[$i]);
        $xtpl->parse('main.table.TaskList');
    }	
    //// ���ȦC�� End ////

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

    $xtpl->assign('CaseList', $CaseList); // �ץ�C�� Option    
    $xtpl->parse('main.table');
    $xtpl->parse('main');
    $xtpl->out('main');
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>



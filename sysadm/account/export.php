<?php
	header("Cache-control: private");
    if ($check != 'kingfor')
		exit;
	
	$xtpl->parse('table');
	$xtpl->parse('main.table');
?>
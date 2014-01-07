<?php
	header("Cache-control: private");
    if ($check != 'kingfor') exit;
	
	$xtpl->parse('main.table');
?>
<?php
	require_once("pb_connect.php");
	$date=date("d/m/Y");
	$birthdays_query = "update birthday set date = '$date'";
	$birthdays = mysql_query($birthdays_query);
	$log = fopen("pre_sms_log.txt","a");
	fwrite($log, date("Y-m-d h:i:sa")."INFO: Updated date of births to $date.\n");
?>
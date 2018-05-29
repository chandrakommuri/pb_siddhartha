<?php	
	$con = mysql_connect("localhost","eassvija_admin1","mc@8051");	
	if (!$con)	
	{		
		die('Could not connect: ' . mysql_error());	
	}
	mysql_select_db("eassvija_pbs", $con);
?>
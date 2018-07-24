<?php
	if(isset($_GET['section']))
	{
		$section = $_GET['section'];
		$batch = $_GET['batch'];
		require_once("pb_connect.php");
		$batches_query = "select distinct syear as batch from names where sect like '$section' and syear like '$batch%' and syear <> '' order by syear";
		//echo $batches_query;
		$batches_result = mysql_query($batches_query);
		$batches = array();
		while($batch_row = mysql_fetch_assoc($batches_result))
		{
			$batches[] = $batch_row['batch'];
		}
		echo json_encode($batches);
	}
	else
	{
		echo "[]";
	}
?>
<?php
	$section = $_GET['section'];
	require_once("pb_connect.php");
	$sections_query = "select distinct sect as section from names where sect like '%$section%' and sect not in ('','sect','YOGA') order by sect";
	//echo $sections_query;
	$sections_result = mysql_query($sections_query);
	$sections = array();
	while($section_row = mysql_fetch_assoc($sections_result))
	{
		$sections[] = $section_row['section'];
	}
	echo json_encode($sections);
?>
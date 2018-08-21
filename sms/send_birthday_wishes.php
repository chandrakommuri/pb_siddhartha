<?php
	require_once("pb_connect.php");
	$date=date("d/m");
	$birthdays_query = "select name, phone from birthday where date like '$date%'";
	$birthdays = mysql_query($birthdays_query);
	$log = fopen("sms_log.txt","a");
	while($birthday = mysql_fetch_assoc($birthdays))
	{
		$name = $birthday['name'];
		$phone = $birthday['phone'];
		$msg = "Hi $name, WISH YOU A HAPPY BIRTHDAY.";
		$sms_service_url = "https://www.mgage.solutions/SendSMS/sendmsg.php?uname=pbs_college&pass=Siddha@1&send=PBSCAS&dest=91$phone&msg=$msg";
		$sms_service_url = str_replace(' ', '%20', $sms_service_url);
		//echo $sms_service_url.'<br />';
		$curl = curl_init($sms_service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		
		if ($curl_response === false) 
		{
			$info = curl_getinfo($curl);
			$log_message = date("Y-m-d h:i:sa")."ERROR: Could not send wishes to $name on $phone. More info::".var_export($info)."\n";
			fwrite($log, $log_message);
		}
		else
		{
			$log_message = date("Y-m-d h:i:sa")."INFO: Sent wishes to $name on $phone. MID::".$curl_response."\n";
			fwrite($log, $log_message);
		}
		curl_close($curl);
		sleep(2);
	}
?>
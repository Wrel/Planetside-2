<?php
	//API Service ID
	$sid = "<YOUR SERVICE ID>"; //http://census.soe.com/#service-id
	//Pull Census API Data
	$Census = file_get_contents("https://census.soe.com/s:".$sid."/get/ps2/character/?name.first=".$name."&c:resolve=world");
	$playerData = json_decode($Census, true);
	//Return JSON with results
	
	//Incomplete....
	
	echo json_encode($playerData, true);
?>
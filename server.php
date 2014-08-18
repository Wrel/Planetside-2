<?php
	//API Service ID
	$sid = "<YOUR SERVICE ID>"; //http://census.soe.com/#service-id
	//Pull Census API Data
	$Census = file_get_contents("http://census.soe.com/s:".$sid."/get/ps2/world/?c:lang=en&c:limit=6");
	$serverData = json_decode($Census, true);
	if($serverData['returned'] == 0 || !isset($serverData['returned'])){
		$serverInfo['Status'] = "ERR";
	} else {
		$serverInfo['Status'] = "OK";
		$serverInfo['Time'] = $serverData['timing']['total-ms'];
	}
	foreach ($serverData['world_list'] as $server) {
			$serverInfo[$server['name']['en']] = $server['state'];
	}
	echo json_encode($serverInfo, true);
?>
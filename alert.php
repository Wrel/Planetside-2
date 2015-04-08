<?php
	//Servers
	//	Connery 1
	//	Miller  10
	//	Cobalt  13
	//	Emerald 17
	//	Briggs  25

	//Pull Census API Data
	$sid = "<YOUR SERVICE ID>"; //http://census.daybreakgames.com/#service-id
	$Census = file_get_contents("http://census.daybreakgames.com/s:".$sid."/get/ps2:v2/world_event/?world_id=".$_GET['server']."&c:limit=50&type=METAGAME");
	$alertData = json_decode($Census, true);
	//Return JSON, To Be added by Below Code.
	$alertInfo = array();
	//If API is Down / Bad Response
	if($alertData['returned'] == 0 || !isset($alertData['returned'])){
		$alertInfo['Status'] = "ERR";
	} else {
		$alertInfo['Status'] = "OK";
		$alertInfo['Time'] = $alertData['timing']['total-ms'];
	}
	//Common Status
	$amerish = array();
	$esamir = array();
	$hossin = array();
	$indar = array();
	for($_alert = 0; $_alert <= 49; $_alert++){ //Loop 0-49
		$alert = $alertData['world_event_list'][$_alert];
		if($alert['metagame_event_id'] == "33" && !isset($amerish['status'])){ //Amerish Alert
			if($alert['metagame_event_state_name'] == "ended" || $alert['metagame_event_state_name'] == "canceled"){
				$amerish['status'] = "ended";
				$alertInfo['Alert']['Amerish'] = "false";
			} else {
				$amerish['status'] = "started";
				$alertInfo['Alert']['Amerish'] = "true";
			}
		}
		if($alert['metagame_event_id'] == "32" && !isset($esamir['status'])){ //Esamir Alert
			if($alert['metagame_event_state_name'] == "ended" || $alert['metagame_event_state_name'] == "canceled"){
				$esamir['status'] = "ended";
				$alertInfo['Alert']['Esamir'] = "false";
			} else {
				$esamir['status'] = "started";
				$alertInfo['Alert']['Esamir'] = "true";
			}
		}
		if($alert['metagame_event_id'] == "34" && !isset($hossin['status'])){ //Hossin Alert
			if($alert['metagame_event_state_name'] == "ended" || $alert['metagame_event_state_name'] == "canceled"){
				$hossin['status'] = "ended";
				$alertInfo['Alert']['Hossin'] = "false";
			} else {
				$hossin['status'] = "started";
				$alertInfo['Alert']['Hossin'] = "true";
			}
		}
		if($alert['metagame_event_id'] == "31" && !isset($indar['status'])){ //Indar Alert
			if($alert['metagame_event_state_name'] == "ended" || $alert['metagame_event_state_name'] == "canceled"){
				$indar['status'] = "ended";
				$alertInfo['Alert']['Indar'] = "false";
			} else {
				$indar['status'] = "started";
				$alertInfo['Alert']['Indar'] = "true";
			}
		}
	}
	echo json_encode($alertInfo, true);
?>

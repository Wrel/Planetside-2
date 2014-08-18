<?php
	//Servers
	//	Connery 1
	//	Miller  10
	//	Cobalt  13
	//	Emerald 17
	//	Briggs  25
	$Continents = array(
		"Amerish" => 1, //Zone ID 6
		"Esamir"  => 3, //Zone ID 8
		"Hossin"  => 2, //Zone ID 4
		"Indar"   => 0  //Zone ID 2
	);
	$Factions = array(
		"Vanu Sovereignty" => 1,
		"New Conglomerate" => 2,
		"Terran Republic" => 3
	);
	//Pull Census API Data
	$sid = "<YOUR SERVICE ID>"; //http://census.soe.com/#service-id
	$Census = file_get_contents("http://census.soe.com/s:".$sid."/get/ps2:v2/map/?world_id=".$_GET['server']."&zone_ids=2,4,6,8");
	$mapData = json_decode($Census, true);
	//Return JSON, To Be added by Below Code.
	$ContinentInfo = array();
	//If API is Down / Bad Response
	if($mapData['returned'] == 0 || !isset($mapData['returned'])){
		$ContinentInfo['Status'] = "ERR";
	} else {
		$ContinentInfo['Status'] = "OK";
		$ContinentInfo['Time'] = $mapData['timing']['total-ms'];
	}
	//Common Status
	for($map = 0; $map <= 3; $map++){ //Loop 0-3 (4 conts.)
		$NC = array();
		$VS = array();
		$TR = array();
		foreach($mapData['map_list'][$map]['Regions']['Row'] as $hex){
			if($hex['RowData']['FactionId'] == $Factions['New Conglomerate']){
				$NC[] = "NC";
			} elseif($hex['RowData']['FactionId'] == $Factions['Vanu Sovereignty']){
				$VS[] = "VS";
			} elseif($hex['RowData']['FactionId'] == $Factions['Terran Republic']){
				$TR[] = "TR";
			}
		}
		if(count($VS) == 0 && count($TR) == 0 && count($NC) == 0){
			//Unknown API is Down / Bad Response
			$ContinentInfo[array_search($map, $Continents)]['Status'] = "Unknown";
			$ContinentInfo[array_search($map, $Continents)]['Faction'] = "-";
		} else {
			if(count($VS) == 0 && count($TR) == 0){
				//NC Lock
				$ContinentInfo[array_search($map, $Continents)]['Status'] = "Locked";
				$ContinentInfo[array_search($map, $Continents)]['Faction'] = "New Conglomerate";
			} elseif(count($NC) == 0 && count($TR) == 0){
				//VS Lock
				$ContinentInfo[array_search($map, $Continents)]['Status'] = "Locked";
				$ContinentInfo[array_search($map, $Continents)]['Faction'] = "Vanu Sovereignty";
			} elseif(count($NC) == 0 && count($VS) == 0){
				//TR Lock
				$ContinentInfo[array_search($map, $Continents)]['Status'] = "Locked";
				$ContinentInfo[array_search($map, $Continents)]['Faction'] = "Terran Republic";
			} else {
				//Unlocked
				$ContinentInfo[array_search($map, $Continents)]['Status'] = "Unlocked";
				$ContinentInfo[array_search($map, $Continents)]['Faction'] = "-";
			}
		}
		
	}
	echo json_encode($ContinentInfo, true);
?>
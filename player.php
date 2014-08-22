<?php
	//API Service ID
	$sid = "<YOUR SERVICE ID>"; //http://census.soe.com/#service-id
	//Pull Census API Data
	$Census = file_get_contents("https://census.soe.com/s:".$sid."/get/ps2/character/?name.first=".$name."&c:resolve=world");
	$playerData = json_decode($Census, true);
	$playerInfo = array();
	if($playerData['returned'] == 0 || !isset($playerData['returned'])){
		$playerInfo['Status'] = "ERR";
	} else {
		$playerInfo['Status'] = "OK";
		$playerInfo['Time'] = $playerData['timing']['total-ms'];
	}
	$playerInfo['Name'] = $playerData['character_list'][0]['name']['first'];
	$playerInfo['ID'] = $playerData['character_list'][0]['character_id'];
	$playerInfo['Faction'] = array_search($playerData['character_list'][0]['faction_id'], $Factions);
	if($playerData['character_list'][0]['online_status'] == "1"){
		$playerInfo['Online'] = "true";
	} else {
		$playerInfo['Online'] = "false";
	}
	$playerInfo['Server'] = array_search($playerData['character_list'][0]['world_id'], $Servers);
	$playerInfo['Outfit']['Name'] = $playerData['character_list'][0]['outfit']['name'];
	$playerInfo['Outfit']['Tag'] = $playerData['character_list'][0]['outfit']['alias'];
	$playerInfo['BattleRank']['Rank'] = $playerData['character_list'][0]['battle_rank']['value'];
	$playerInfo['BattleRank']['Progress'] = $playerData['character_list'][0]['battle_rank']['percent_to_next'];
	$playerInfo['CertPoints']['Certs'] = $playerData['character_list'][0]['certs']['available_points'];
	$playerInfo['CertPoints']['Progress'] = round((float)$playerData['character_list'][0]['certs']['percent_to_next'] * 100 );
	$playerInfo['Nanites'] = $playerData['character_list'][0]['currency']['quantity'];
	//Return JSON with results
	echo json_encode($playerInfo, true);
	//Return JSON with results
	echo json_encode($playerData, true);
?>
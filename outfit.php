<?php
	//API Service ID
	$sid = "<YOUR SERVICE ID>"; //http://census.daybreakgames.com/#service-id
	//Pull Census API Data
	$outfitID = json_decode(file_get_contents("http://census.daybreakgames.com/s:".$sid."/get/ps2/outfit/?alias=".$_GET['tag']."&c:show=outfit_id,name,member_count"), true);
	$Census = file_get_contents("http://census.daybreakgames.com/s:".$sid."/get/ps2/outfit_member?c:limit=1000&c:resolve=online_status,character(name.first)&c:hide=member_since,member_since_date,outfit_id,rank,rank_ordinal&outfit_id=".$outfitID['outfit_list'][0]['outfit_id']);
	$outfitData = json_decode($Census, true);
	//Return JSON, To Be added by Below Code.
	$outfitInfo = array();
	if($outfitData['returned'] == 0 || !isset($outfitData['returned'])){
		$outfitInfo['Status'] = "ERR";
	} else {
		$outfitInfo['Status'] = "OK";
		$outfitInfo['Time'] = $outfitData['timing']['total-ms'];
	}
	$outfitInfo['Outfit']['Name'] = $outfitID['outfit_list'][0]['name'];
	$outfitInfo['Outfit']['Tag'] = $_GET['tag'];
	$outfitInfo['Outfit']['ID'] = $outfitID['outfit_list'][0]['outfit_id'];
	$outfitInfo['Outfit']['Members']['Count'] = $outfitID['outfit_list'][0]['member_count'];
	foreach ($outfitData['outfit_member_list'] as $player) {
		if ($player['online_status'] != 0){
			//Player is online
			$outfitInfo['Outfit']['Members']['Online'][] = $player['character']['name']['first'];
		} else {
			//Player is offline
			$outfitInfo['Outfit']['Members']['Offline'][] = $player['character']['name']['first'];
		}
	}
	//Return JSON with results
	echo json_encode($outfitInfo, true);
?>

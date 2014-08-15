<?php
	//API Service ID
	$sid = "example";
	//Pull Census API Data
	$outfitID = json_decode(file_get_contents("http://census.soe.com/s:".$sid."/get/ps2/outfit/?alias=".$_GET['tag']."&c:show=outfit_id,name,member_count"), true);
	$Census = file_get_contents("http://census.soe.com/s:".$sid."/get/ps2/outfit_member?c:limit=1000&c:resolve=online_status,character(name.first)&c:hide=member_since,member_since_date,outfit_id,rank,rank_ordinal&outfit_id=".$outfitID['outfit_list'][0]['outfit_id']);
	$outfitData = json_decode($Census, true);
	//Return JSON, To Be added by Below Code.
	$outfitInfo = array();
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
	

	/*
	Info
	================
	Gets the Online and Offline Members in an outfit
	by the Outfit Tag (e.g. TAG) and returns in JSON
	
	Example Usage
	================
	getMembers.php?tag=TAG
	
	Example Output
	================
		{
			"Outfit":{
				"Name":"ExampleOutfit",
				"Tag":"TAG",
				"ID":"237351897058732",
				"Members":{
					"Count":"6",
					"Online":[
						"JaneDoe",
						"TheRealExample"
					],
					"Offline":[
						"Player",
						"Example",
						"JohnDoe",
						"PewPew"
					]
				}
			}
		}
	*/
?>
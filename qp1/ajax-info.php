<?php

include("db-config.php");


if(isset($_POST['action'])){

	$action = $_POST['action'];
	
	switch ($action) {
	    case "getState":

		$country_code = $_POST['country_code'];
		$stateInfo = "";
		$sql2 = "SELECT state FROM visitors_info WHERE country_code = '".$country_code."' GROUP BY state";	
		$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt2->execute();
		$states = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		if(count($states) > 0){
			foreach($states as $state){

				$stateInfo .= "<option value='".$state['state']."'>".$state['state']."</option>";
			}
		}
		$stmt2 = null;
		echo $stateInfo;
		break;

	    case "getCity":
		$state = $_POST['state'];
		$cityInfo = "";
		$sql2 = "SELECT city FROM visitors_info WHERE state = '".$state."' GROUP BY city";	
		$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt2->execute();
		$cities = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		if(count($cities) > 0){
			foreach($cities as $city){

				$cityInfo .= "<option value='".$city['city']."'>".$city['city']."</option>";
			}
		}
		$stmt2 = null;
		echo $cityInfo;
		break;
	    
	    default:
		break;
	}
	
}

exit;
?>

<?php
/******************************************************************
 * Ideabytes Software India Pvt Ltd.                              *
 * 50 Jayabheri Enclave, Gachibowli, HYD                          *
 * Created Date : 23/01/2014                                      *
 * Created By : Pradeep G                                         *
 * Vision : Project Sparkle                                       *  
 * Modified by : Pradeep G     Date : 05/02/2014    Version : V1  *
 * Description : Home page (Defaut page of sparkle)               *
 *****************************************************************/

include("../qp/agent-config.php");
include("../mobile_detect/secure_token.php");

@$action = @$_REQUEST['action'];


if($action=="get_vidurl")
{

$vidurl = $_POST['vid_url'];
$vidurl=base64_decode($vidurl);

return $vidurl;

}

if($action=="updatestatus"){
	/* player load */
	//echo "AAaAA_updating status".$_POST['session_id'];
	$sql = "UPDATE mobile_app_check SET status = 1 WHERE session_id = ?";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($_POST['session_id']));	
		$stmt = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}	
	
}else if($action=="checkstatus"){

	//echo "checking status";
	$sql = "SELECT status FROM mobile_app_check WHERE session_id = ?";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($_POST['session_id']));	
		$count = $stmt->rowCount();
		if($count>0){
			$aData = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$sql1 = "UPDATE mobile_app_check SET status = 0 WHERE session_id = ?";	
			$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt1->execute(array($_POST['session_id']));	
			$stmt1 = null;
			echo $aData['status'];
			exit;
		}
		$stmt = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}

}else if($action=="addanalytics"){

	/* Defult every 30 Sec */



//get access token

$access_token=$_REQUEST['access_token'];
//echo "Uat:".$access_token;



///print_r($ana_info);

//echo "Uat:".var_dump($access_token);

//print access_token
$result_token=validateAuthTokenAna($access_token);

//var_dump($result_token);


if(is_array($result_token)){

	echo "ok";
	$ana_info=$result_token['analyticsinfo'];

}
else
{
	//echo "no";
	echo $result_token;
	exit;
}


	
	//$user_id = $_POST['user_id'];
	//$user_name = $_POST['user_name'];

	$user_id=$ana_info["user_id"];
	$user_name = $ana_info['user_name'];

	if($user_id > 0)
	{
		$sql = 'SELECT user_email FROM wp_users WHERE ID = ?';
		try {
			$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute(array($user_id));		
			$mail = $stmt->fetch(PDO::FETCH_ASSOC);                        
			$stmt = null;
			 
			}            
		catch (PDOException $e){
			print $e->getMessage();
		}
	}
	
	$user_email=$mail['user_email'];

	//$page_id = $_POST['page_id']; 		
	//$ip_address = $_POST['ip_address']; 

	$page_id = $ana_info['page_id']; 		
	$ip_address = $ana_info['ip_address'];

	/*$geoinfo = "http://api.ipinfodb.com/v3/ip-city/?key=13ebc6d8740ab89e93e615530a59dd0f22df559274089129135f83188578f84d&ip=$ip_address&format=json";
	$ch_geoinfo = curl_init($geoinfo); 	
	curl_setopt($ch_geoinfo, CURLOPT_HEADER, 0);         	
	curl_setopt($ch_geoinfo, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch_geoinfo, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch_geoinfo, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch_geoinfo, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch_geoinfo,CURLOPT_CONNECTTIMEOUT,60);
	curl_setopt($ch_geoinfo, CURLOPT_FAILONERROR, 1);

	$execute_geoinfo = curl_exec($ch_geoinfo);
	if(!curl_errno($ch_geoinfo)){		
	$json_geoinfo = str_replace('\\', '\\\\', $execute_geoinfo);
	$json_decode_geoinfo = json_decode($json_geoinfo, true);   
	$country=$country_name = $json_decode_geoinfo["countryName"]; //country
	$city = $json_decode_geoinfo["cityName"];			//city
	$country_code = $json_decode_geoinfo["countryCode"];	//country_code		
	$state = $json_decode_geoinfo["regionName"];		//state
	$lat = $json_decode_geoinfo["latitude"];	
	$lngt = $json_decode_geoinfo["longitude"];	
	$tz = $json_decode_geoinfo["timeZone"];
	$geoinfo_status=1;	 //geo_info_status		

			}*/

			
	//$country_code=$_POST['country_code']; 
	//$country=$_POST['country']; 
	//$state=$_POST['state']; 
	//$city=$_POST['city']; 
	//$tz=$_POST['tz'];
	//$lat=$_POST['lat'];
	//$lngt=$_POST['lngt'];
	//$geoinfo_status=$_POST['geo_info_status'];

	/*$device = $_POST['device']; 
	$os_name = $_POST['os_name']; 
	$os_version =$_POST['os_version']; 
	$browser_name =  $_POST['browser_name']; 
	$browser_version = $_POST['browser_version'];
	$page_referer=$_POST['page_referer'];
	$play=$_POST['play']; 
	$page_title = $_POST['page_title']; 
	$page_name = $_POST['page_name'];
	$session_id = $_POST['session_id']; */

	$device = $ana_info['device']; 
	$os_name = $ana_info['os_name']; 
	$os_version =$ana_info['os_version']; 
	$browser_name =  $ana_info['browser_name']; 
	$browser_version = $ana_info['browser_version'];
	$page_referer=$ana_info['page_referer'];
	$play=$ana_info['play']; 
	$page_title = $ana_info['page_title']; 
	$page_name = $ana_info['page_name'];
	$session_id = $ana_info['session_id']; 



	$start_time = gmdate("Y-m-d H:i:s");
	$end_time = gmdate("Y-m-d H:i:s"); 
	$created_datetime = gmdate("Y-m-d H:i:s"); 
	$updated_datetime = gmdate("Y-m-d H:i:s"); 

	$duration = 0; 
	
	$end_time = gmdate("Y-m-d H:i:s");
	$start_time = $end_time;
	
	$play = ($play == "-1" OR $play == "0" ) ? 0 : 1;

	$sql = 'SELECT count(id) as count FROM page WHERE id = ?';
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($page_id));		
		$pageData = $stmt->fetch(PDO::FETCH_ASSOC);                        
		$stmt = null;
		if($pageData['count'] == 0){                
			$sqlInsert = 'INSERT INTO page(id,page_name,short_name) VALUES(?,?,?)';
			$stmt = $dbcon->prepare($sqlInsert, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute(array($page_id,$page_title,$page_name));
			$stmt = null;        
		}            
	}catch (PDOException $e){
		print $e->getMessage();
	}

	/* $sql = 'SELECT count(id) as count FROM page WHERE id = ?';
		try {
			$stmt = $dbconAnalytics->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute(array($page_id));		
			$pageData = $stmt->fetch(PDO::FETCH_ASSOC);                        
			$stmt = null;
			if($pageData['count'] == 0){                
				$sqlInsert = 'INSERT INTO page(id,page_name,short_name) VALUES(?,?,?)';
				$stmt = $dbconAnalytics->prepare($sqlInsert, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
				$stmt->execute(array($page_id,$page_title,$page_name));
				$stmt = null;        
			}            
		}catch (PDOException $e){
			print $e->getMessage();
		}
	*/


	/*   dbconAnalytics for LIVE only
	if($user_id > 0){
		
		$sql1 = "SELECT id FROM visitors_info WHERE user_id = ? AND page_id = ? AND ip_address = ? AND device = ? AND os_name = ? AND os_version = ? AND browser_name = ? AND browser_version = ? AND session_id = ? AND play = ?";
		$data1 = array($user_id,$page_id,$ip_address,$device,$os_name,$os_version,$browser_name,$browser_version,$session_id,$play);
		$stmt1 = $dbconAnalytics->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute($data1);
	
		//print_r($dbconAnalytics->errorInfo());
		
		if($stmt1->rowCount() > 0){
			$result = $stmt1->fetch();			
			$sql = "UPDATE visitors_info SET duration = duration + ".$app_analytics_update_duration.", end_datetime = ? WHERE id = ?";		
			$data = array($end_time, $result['id']);
			
		}else{			
			$sql = "INSERT INTO visitors_info(user_id,user_name,page_id,ip_address,page_referer,device,os_name,os_version,browser_name,browser_version,start_datetime,end_datetime,session_id,duration,play) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";		
			$data = array($user_id,$user_name,$page_id,$ip_address,$page_referer,$device,$os_name,$os_version,$browser_name,$browser_version,$start_time,$end_time,$session_id,$duration,$play);
		}
		$stmt = null;
	
	}else{
		
		$sql1 = "SELECT id FROM visitors_info WHERE page_id = ? AND ip_address = ? AND device = ? AND os_name = ? AND os_version = ? AND browser_name = ? AND browser_version = ? AND session_id = ? AND play = 0";
		$data1 = array($page_id,$ip_address,$device,$os_name,$os_version,$browser_name,$browser_version,$session_id);
		$stmt1 = $dbconAnalytics->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute($data1);	
		
		if($stmt1->rowCount() > 0){
			$result = $stmt1->fetch();	
			$sql = "UPDATE visitors_info SET duration = duration + ".$app_analytics_update_duration.", end_datetime = ? WHERE id = ?";		
			$data = array($end_time, $result['id']);
			
		}else{			
			$sql = "INSERT INTO visitors_info(user_id,user_name,page_id,ip_address,page_referer,device,os_name,os_version,browser_name,browser_version,start_datetime,end_datetime,session_id,duration,play) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";		
			$data = array($user_id,$user_name,$page_id,$ip_address,$page_referer,$device,$os_name,$os_version,$browser_name,$browser_version,$start_time,$end_time,$session_id,$duration,$play);
		}
		$stmt = null;
	}


	
	try {
		$stmt = $dbconAnalytics->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute($data);	
		//print_r($dbconAnalytics->errorInfo());

		$stmt = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}   dbconAnalytics only for live */
	
  	/*  //dbcon local //comment below after testing
	try {
		$stmtL = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmtL->execute($data); //added new logic	
		//$stmt1->execute(array($user_id,$user_name,$page_id,$ip_address,$page_referer,$device,$os_name,$os_version,$browser_name,$browser_version,$start_time,$end_time,$session_id,$duration,$play,$end_time));	
	//print_r($dbcon->errorInfo());

		$stmtL = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}*/

//REMOVE BELOW (only for testing) LOCAL dbcon Added on 27-04-2016

	if($user_id > 0){

		$sql1 = "SELECT id,start_datetime FROM visitors_info WHERE user_id = ? AND page_id = ? AND ip_address = ? AND device = ? AND os_name = ? AND os_version = ? AND browser_name = ? AND browser_version = ? AND session_id = ? AND play = ?";
		$data1 = array($user_id,$page_id,$ip_address,$device,$os_name,$os_version,$browser_name,$browser_version,$session_id,$play);
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute($data1);
	
		//print_r($dbcon->errorInfo());
		
		if($stmt1->rowCount() > 0){
			$result = $stmt1->fetch();

			$duration = strtotime($end_time) - strtotime($result['start_datetime']);
			
			$sql = "UPDATE visitors_info SET duration = ?, end_datetime = ? WHERE id = ?";		
			$data = array($duration, $end_time, $result['id']);
			
		}else{			

			$g=get_geoinfo_by_ip($ip_address);
			$country_code=$g[0];
	$country=$g[1];
	$state=$g[2];
	$city=$g[3];
	$tz=$g[4];
	$lat=$g[5];
	$lngt=$g[6];
	$geoinfo_status=$g[7];


			$sql = "INSERT INTO visitors_info(user_id,user_name,user_email,ip_address,page_id,page_name,page_referer,country_code,country,state,city,timezone,latitude,longitude,geo_info_status,device,os_name,os_version,browser_name,browser_version,start_datetime,end_datetime,session_id,duration,play) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";		
			$data = array($user_id,$user_name,$user_email,$ip_address,$page_id,$page_name,$page_referer,$country_code,$country,$state,$city,$tz,$lat,$lngt,$geoinfo_status,$device,$os_name,$os_version,$browser_name,$browser_version,$start_time,$end_time,$session_id,$duration,$play);
		}
		$stmt1 = null;
	
	}else{
		
		$sql1 = "SELECT id FROM visitors_info WHERE page_id = ? AND ip_address = ? AND device = ? AND os_name = ? AND os_version = ? AND browser_name = ? AND browser_version = ? AND session_id = ? AND play = 0";
		$data1 = array($page_id,$ip_address,$device,$os_name,$os_version,$browser_name,$browser_version,$session_id);
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute($data1);	
		
		if($stmt1->rowCount() > 0){
			$result = $stmt1->fetch();		
			$duration = strtotime($end_time) - strtotime($result['start_datetime']);
			$sql = "UPDATE visitors_info SET duration = ?, end_datetime = ? WHERE id = ?";		
			$data = array($duration, $end_time, $result['id']);
			
		}else{		

			$g=get_geoinfo_by_ip($ip_address);
			$country_code=$g[0];
	$country=$g[1];
	$state=$g[2];
	$city=$g[3];
	$tz=$g[4];
	$lat=$g[5];
	$lngt=$g[6];
	$geoinfo_status=$g[7];

			$sql = "INSERT INTO visitors_info(user_id,user_name,user_email,ip_address,page_id,page_name,page_referer,country_code,country,state,city,timezone,latitude,longitude,geo_info_status,device,os_name,os_version,browser_name,browser_version,start_datetime,end_datetime,session_id,duration,play) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";		
			$data = array($user_id,$user_name,$user_email,$ip_address,$page_id,$page_name,$page_referer,$country_code,$country,$state,$city,$tz,$lat,$lngt,$geoinfo_status,$device,$os_name,$os_version,$browser_name,$browser_version,$start_time,$end_time,$session_id,$duration,$play);
		}
		$stmt1 = null;
	}


	
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute($data);	
		//print_r($dbcon->errorInfo());

		$stmt = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}

	//echo "OK";
	exit;

	/***********REMOVE TILL HERE*************/

	/** geo-info only for local**/
	//require_once $_SERVER['DOCUMENT_ROOT'].'/mobile_detect/more_detection.php'; //LIVE
	//require_once $_SERVER['DOCUMENT_ROOT'].'/newqezyplay/mobile_detect/more_detection.php'; //DEMO
	//get_country_data_by_ip();	
}
?>

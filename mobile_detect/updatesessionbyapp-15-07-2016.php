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

/* create connection */
 
try{

	//$dbconAnalytics = new PDO('mysql:host=cm.ideabytes.com;port=3306;dbname=cmadmin_qezyplay_analytics', 'cmadmin_dev', 'CmAdmin123', array( PDO::ATTR_PERSISTENT => false));//CENTRAL only for LIVE
    //$dbcon = new PDO("mysql:host=192.169.213.239;dbname=qezyplay_wordpress", "qezyplay_test", "test_qezyplay"); //LIVE local
	$dbcon = new PDO("mysql:host=50.62.170.42;dbname=tradmin_newqezy", "tradmin_qezyplay", "&(qezy@word)&"); //DEMO local
	//$dbcon = new PDO("mysql:host=50.62.35.167;dbname=tradmin_qezyplaydemo", "tradmin_qezyplay", "&(qezy@word)&"); //DEMO local

}
catch(PDOException $e){
    echo $e->getMessage();    
}


@$action = @$_REQUEST['action'];

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
			
			echo $aData['status'];
			exit;
		}
		$stmt = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}

}else if($action=="addanalytics"){

	/* Defult every 30 Sec */

	$app_analytics_update_duration = 30;

	$sqlDuration = "SELECT option_value FROM wp_options WHERE option_name = 'app_analytics_update_duration'";
	try {
		$stmtDuration = $dbcon->prepare($sqlDuration, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmtDuration->execute();		
		$durationData = $stmtDuration->fetch(PDO::FETCH_ASSOC);                        
		$stmtDuration = null;
		if((int)$durationData['option_value'] > 0) 
			$app_analytics_update_duration = $durationData['option_value'];
			
	}catch (PDOException $e){
		print $e->getMessage();
	}
	
		
	$user_id = $_POST['user_id'];
$user_name = $_POST['user_name'];
$page_id = $_POST['page_id']; 		
$ip_address = $_POST['ip_address']; 
$country_code=$_POST['country_code']; 
$country=$_POST['country']; 
$state=$_POST['state']; 
$city=$_POST['city']; 
$geoinfo_status=$_POST['geo_info_status'];
$device = $_POST['device']; 
$os_name = $_POST['os_name']; 
	$os_version =$_POST['os_version']; 
$browser_name =  $_POST['browser_name']; 
$browser_version = $_POST['browser_version'];
$page_referer=$_POST['page_referer'];
$play=$_POST['play']; 
$page_title = $_POST['page_title']; 
$page_name = $_POST['page_name'];
$session_id = $_POST['session_id']; 
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

		$sql1 = "SELECT id FROM visitors_info WHERE user_id = ? AND page_id = ? AND ip_address = ? AND device = ? AND os_name = ? AND os_version = ? AND browser_name = ? AND browser_version = ? AND session_id = ? AND play = ?";
		$data1 = array($user_id,$page_id,$ip_address,$device,$os_name,$os_version,$browser_name,$browser_version,$session_id,$play);
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute($data1);
	
		//print_r($dbcon->errorInfo());
		
		if($stmt1->rowCount() > 0){
			$result = $stmt1->fetch();			
			$sql = "UPDATE visitors_info SET duration = duration + ".$app_analytics_update_duration.", end_datetime = ? WHERE id = ?";		
			$data = array($end_time, $result['id']);
			
		}else{			
			$sql = "INSERT INTO visitors_info(user_id,user_name,ip_address,page_id,page_name,page_referer,country_code,country,state,city,geo_info_status,device,os_name,os_version,browser_name,browser_version,start_datetime,end_datetime,session_id,duration,play) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";		
			$data = array($user_id,$user_name,$ip_address,$page_id,$page_name,$page_referer,$country_code,$country,$state,$city,$geoinfo_status,$device,$os_name,$os_version,$browser_name,$browser_version,$start_time,$end_time,$session_id,$duration,$play);
		}
		$stmt = null;
	
	}else{
		
		$sql1 = "SELECT id FROM visitors_info WHERE page_id = ? AND ip_address = ? AND device = ? AND os_name = ? AND os_version = ? AND browser_name = ? AND browser_version = ? AND session_id = ? AND play = 0";
		$data1 = array($page_id,$ip_address,$device,$os_name,$os_version,$browser_name,$browser_version,$session_id);
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute($data1);	
		
		if($stmt1->rowCount() > 0){
			$result = $stmt1->fetch();	
			$sql = "UPDATE visitors_info SET duration = duration + ".$app_analytics_update_duration.", end_datetime = ? WHERE id = ?";		
			$data = array($end_time, $result['id']);
			
		}else{			
			$sql = "INSERT INTO visitors_info(user_id,user_name,ip_address,page_id,page_name,page_referer,country_code,country,state,city,geo_info_status,device,os_name,os_version,browser_name,browser_version,start_datetime,end_datetime,session_id,duration,play) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";		
			$data = array($user_id,$user_name,$ip_address,$page_id,$page_name,$page_referer,$country_code,$country,$state,$city,$geoinfo_status,$device,$os_name,$os_version,$browser_name,$browser_version,$start_time,$end_time,$session_id,$duration,$play);
		}
		$stmt = null;
	}


	
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute($data);	
		//print_r($dbcon->errorInfo());

		$stmt = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}

	echo "OK";
	exit;

	/***********REMOVE TILL HERE*************/

	/** geo-info only for local**/
	//require_once $_SERVER['DOCUMENT_ROOT'].'/mobile_detect/more_detection.php'; //LIVE
	//require_once $_SERVER['DOCUMENT_ROOT'].'/newqezyplay/mobile_detect/more_detection.php'; //DEMO
	//get_country_data_by_ip();	
}
?>

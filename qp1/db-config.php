<?php

ob_start();

session_start(); 

try{
	//$dbcon = new PDO("mysql:host=localhost;dbname=qezyplay_shonarbangla", "qezyplay_shonarb", "&(qezy@word)&"); //QP SB
	$dbcon = new PDO("mysql:host=50.62.170.42;dbname=tradmin_newqezy", "tradmin_qezyplay", "&(qezy@word)&");	 //DEMO

}
catch(PDOException $e){
    echo $e->getMessage();    
}


define("SITE_URL", "http://ideabytestraining.com/newqezyplay");
define("ENV", "dev");  //dev (or) live

define("ADMIN_EMAIL1", "admin@qezyplay.com");
define("ADMIN_EMAIL", "siddish.gollapelli@ideabytes.com");

define('ABSPATH', dirname(__DIR__));


$media=ABSPATH."/wp-content/uploads/".date('Y')."/".date('m');
$media1=SITE_URL."/wp-content/uploads/".date('Y')."/".date('m');

define("UPLOAD_FOLDER","wp-content/uploads");
define("DATED_PATH",date('Y')."/".date('m'));
define("MEDIA_FOLDER", $media);
define("MEDIA_FOLDER_LINK", $media1);


define("ROW_PER_PAGE", "10");


$timezone_array = array("-12.0"=>"-12:00","-11.0"=>"-11:00","-10.0"=>"-10:00","-9.0"=>"-09:00",
		"-8.0"=>"-08:00","-7.0"=>"-07:00","-6.0"=>"-06:00","-5.0"=>"-05:00",
		"-4.0"=>"-04:00","-3.5"=>"-03:30","-3.0"=>"-03:00","-2.0"=>"-02:00",
		"-1.0"=>"-01:00","0.0"=>"+00:00","1.0"=>"+01:00","2.0"=>"+02:00",
		"3.0"=>"+03:00","3.5"=>"+03:30","4.0"=>"+04:00","4.5"=>"+04:30",
		"5.0"=>"+05:00","5.5"=>"+05:30","5.75"=>"+05:45","6.0"=>"+06:00",
		"7.0"=>"+07:00","8.0"=>"+08:00","9.0"=>"+09:00","9.5"=>"+09:30",
		"10.0"=>"+10:00","11.0"=>"+11:00","12.0"=>"+12:00");

//echo 'Time zone is :'.$_GET['timezone'];
//Settig the TimeZone Coocki
$coockie = ''; $visitorTimezone = '';
if (!isset($_COOKIE['timezone'])) {
    if (isset($_GET['timezone'])) {
        //$coockie = $_GET['timezone'];
		$coockie = $timezone_array[$_GET['timezone']];
        setcookie('timezone', $coockie);        
    } else {
		//$visitorTimezone = getVisitorTimezone();
		//echo " ip   ".$_SERVER['REMOTE_ADDR'];
		$visitorTimezone = ($visitorTimezone == "") ? "+00:00" : $visitorTimezone;
        setcookie('timezone', $visitorTimezone);
        $coockie = $visitorTimezone;
		
    }
     //echo ' Cocki not set , setting initially';
	 
} else {
    if (isset($_GET['timezone'])) {
        //$coockie = $_GET['timezone'];
		$coockie = $timezone_array[$_GET['timezone']];
        setcookie('timezone', $coockie);       
    } else {
        
        $coockie = $_COOKIE['timezone'];
    }
}




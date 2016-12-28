
<?php

//$ua = $_SERVER["HTTP_USER_AGENT"];

//$a=getBrowser($ua);
//echo $browser_name=$a[0];
//echo $browser_version=$a[1];

function getBrowser($ua){
    $chrome            = strpos($ua, 'Chrome') ? true : false;        // Google Chrome
    $firefox        = strpos($ua, 'Firefox') ? true : false;    // Firefox
      
    $msie            = strpos($ua, 'MSIE') ? true : false;        // All Internet Explorer
    $msie_7            = strpos($ua, 'MSIE 7.0') ? true : false;    // Internet Explorer 7
    $msie_8            = strpos($ua, 'MSIE 8.0') ? true : false;    // Internet Explorer 8
    
    $opera            = preg_match("/\bOpera\b/i", $ua);                    // Opera
    $uc=strpos($ua, 'UCBrowser') ? true : false;   //UC
    
    $safari            = strpos($ua, 'Safari') ? true : false;        // All Safari

if ($ua) {

       	if ($firefox) {
        		$from="Firefox/";
			$to=" ";
			$browser="Firefox";
			$browser_version=getStringBetweenE($ua,$from,$to);
    			}
	elseif($uc)
			{
			$from="UCBrowser/";
			$to=" ";
			$browser="UCBrowser";
			$browser_version=getStringBetweenM($ua,$from,$to);
			}
       	elseif ( ($safari || $chrome)) {
        		if ($safari && !$chrome) {               
						$from="Safari/";
						$to=" ";
						$browser="Safari";
						$browser_version=getStringBetweenE($ua,$from,$to);
                   				 }
		           			
       			 elseif ($chrome) {    
					$from="Chrome/";
					$to=" ";
					$browser="Chrome";
					$browser_version=getStringBetweenM($ua,$from,$to);
          				  }
   				 }

       	 elseif ($msie) {
       			 
			$browser="IE";
			
       				
      			  if ($msie_7) {    $browser_version="7";     }                 // Internet Explorer 7
           
       			 elseif ($msie_8) {  $browser_version="8";   }                       // Internet Explorer 8
           
      			  else { $browser_version="-";      }
           
  			  }

     	 elseif ($opera) {
       			$from="Opera/";
			$to=" ";
			$browser="Opera";
			$browser_version=getStringBetweenM($ua,$from,$to);
     			   }
	

       	 else {      $browser="unknown";
			$browser_version="-";     }
}

return array($browser,$browser_version);
}

function getStringBetweenE($str,$from,$to)
		{
   		 $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
		return $sub;
		}

function getStringBetweenM($str,$from,$to)
		{
   		 $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
		return substr($sub,0,strpos($sub,$to));
		}

//echo $os=getOS($ua);

function getOS($user_agent) { 

    

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function get_country_data_by_ip(){


try{
    //$dbcon = new PDO("mysql:host=192.169.213.239;dbname=qezyplay_wordpress", "qezyplay_test", "test_qezyplay"); //LIVE
	$dbcon = new PDO("mysql:host=localhost;dbname=tradmin_newqezy", "tradmin_qezyplay", "&(qezy@word)&"); //DEMO
	}
catch(PDOException $e){
    echo $e->getMessage();    
}


$s_query = "SELECT ip_address FROM visitors_info WHERE geo_info_status = 0 GROUP BY ip_address"; 
$select_query = $dbcon->prepare($s_query);
$select_query->execute();
$select_query->rowCount();

if($select_query->rowCount() > 0){

	$select_query_result = $select_query->fetchAll();
	foreach($select_query_result as $select_query_row){    
		$present_ip = $select_query_row['ip_address'];		
		$variable_to_echo .= "processed ip = ".$present_ip."\n";
		$geoinfo = "http://api.ipinfodb.com/v3/ip-city/?key=13ebc6d8740ab89e93e615530a59dd0f22df559274089129135f83188578f84d&ip=$present_ip&format=json";

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
			
			$country_name = $json_decode_geoinfo["countryName"];
			$city_name = $json_decode_geoinfo["cityName"];
			$country_code = $json_decode_geoinfo["countryCode"];			
			$state = $json_decode_geoinfo["regionName"];
			
			$variable_to_echo .= "country :  ".$country_name." -------- city :  ".$city_name."\n";	

			$geo_info_status_var = 1;
			$update_q = "UPDATE visitors_info SET country = :country, country_code = :country_code, city = :city, geo_info_status = 1, state = :state WHERE geo_info_status = 0 AND ip_address = :ip_address";
			$update_query = $dbcon->prepare($update_q);
			$update_query->bindParam(":country",$country_name);
			$update_query->bindParam(":city",$city_name);			
			$update_query->bindParam(":ip_address",$present_ip);
			$update_query->bindParam(":country_code",$country_code);
			$update_query->bindParam(":state",$state);
			
			try{ 
				$update_query->execute();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}
		sleep(3);		
	}
}

}


?>

    


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

    		}elseif($uc){

			$from="UCBrowser/";
			$to=" ";
			$browser="UCBrowser";
			$browser_version=getStringBetweenM($ua,$from,$to);

		}elseif ( ($safari || $chrome)) {

        		if ($safari && !$chrome) {    
           
				$from="Safari/";
				$to=" ";
				$browser="Safari";
				$browser_version=getStringBetweenE($ua,$from,$to);

                   	}elseif ($chrome) { 
   
				$from="Chrome/";
				$to=" ";
				$browser="Chrome";
				$browser_version=getStringBetweenM($ua,$from,$to);

          		}

   		}elseif ($msie) {
       			 
			$browser="IE";			
       				
			if ($msie_7) {    
				$browser_version="7";  
  
			} elseif ($msie_8) { 
				$browser_version="8"; 
  
			} else {
				$browser_version="-";      
			}

		} elseif ($opera) {

       			$from="Opera/";
			$to=" ";
			$browser="Opera";
			$browser_version=getStringBetweenM($ua,$from,$to);
     		}else {
			$browser="unknown";
			$browser_version="-";     
		}
	}

	return array($browser,$browser_version);
}

function getStringBetweenE($str,$from,$to){

	$sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
	return $sub;
}

function getStringBetweenM($str,$from,$to){

	$sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
	return substr($sub,0,strpos($sub,$to));
}



function getOS($user_agent) { 

    

	$os_platform    =   "Unknown OS Platform";

	$os_array     =   array(
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
?>

    


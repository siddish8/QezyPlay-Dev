<?php 


$action=$_REQUEST['action'];
if($action=="createToken"){

	$user_id=$_REQUEST['user_id'];
	$user_name=$_REQUEST['user_name'];
	$page_id = $_POST['page_id']; 		
	$ip_address = $_POST['ip_address']; 
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
echo qezy_enc_web_analytics($user_id,$user_name,$page_id,$page_title,$page_name,$page_referer,$os_name,$browser_name,$browser_version,$session_id,$ip_address,$play,$device);
}


define("TOKEN_SECRET_KEY","QezyplayIB");

function base64url_encode($s) {
	return str_replace(array('+', '/'), array('-', '_'), base64_encode($s));
}


function base64url_decode($s) {
	return base64_decode(str_replace(array('-', '_'), array('+', '/'), $s));
}

//echo "hi";

function qezy_enc($user_id,$user_name,$post_id,$post_title,$post_name,$page_url,$page_referer,$os_name,$browser_name,$browser_version,$vidurl,$imgurl,$session_id,$ip_address,$country_code,$country_name,$state,$city,$geoinfo_status,$app_analytics_update_duration,$app_android_version,$app_ios_version,$app_force_update_status,$app_shutdown_wait_timeout)
{
$jwtAuthTokenSecretKey = base64_encode("QezyplayIB");

$jwt_header = array();
$jwt_payload = array();

//Inject typ and alg inputs as per JSON Web Tokens Specification in to $jwt_header array
$jwt_header["typ"] = "JWT";
$jwt_header["alg"] = "SHA256";
//print_r($jwt_header);
$jwt_header_json_encoded = json_encode($jwt_header);
$jwt_header_json_encoded_base64_encoded = base64_encode($jwt_header_json_encoded);

//Inject Registered Claims like iat, nbf, exp, iss along with other private claim inputs as per JSON Web Tokens Specification in to $jwt_payload array

//need to create a function to get latest EPOCH as per chosen TIMEZONE,
//since, $current_epoch gives epoch at GMT< we had added 19800 seconds manually.


//$user_id = "2";//get req user_id

$token_created_time_epoch = strtotime(gmdate("Y-m-d H:i:s"));
$token_expiry_epoch = $token_created_time_epoch + 5000; //2hrs 

$jwt_payload["iat"] = $token_created_time_epoch;
$jwt_payload["nbf"] = $token_created_time_epoch;
$jwt_payload["exp"] = $token_expiry_epoch;
$jwt_payload["iss"] = "ideabytes";
$jwt_payload["uid"] = strval($user_id);
//$jwt_payload["channelinfo"] = array("url" => "url","name" => "name");

$jwt_payload["channelinfo"] = array("user_name" => $user_name,"page_id" => $post_id, "post_title" => $post_title, "post_name" => $post_name, "page_url" => $page_url, "page_referer" => $page_referer, "os_name" => $os_name,"browser_name" => $browser_name, "browser_version" => $browser_version, "session_id" => $session_id, "country_code" => $country_code, "country" => $country, "state" => $state, "city" => $city, "geo_info_status"=> $geoinfo_status, "imgpath" => $imgurl, "octo_url" => $vidurl, "app_analytics_update_duration" => $app_analytics_update_duration, "app_android_version" => $app_android_version, "app_ios_version" => $app_ios_version, "app_force_update_status" => $app_force_update_status , "app_shutdown_wait_timeout" => $app_shutdown_wait_timeout); 

//print_r($jwt_payload);
$jwt_payload_json_encoded = json_encode($jwt_payload);
$jwt_payload_json_encoded_base64_encoded = base64_encode($jwt_payload_json_encoded);


//base64 decode jwt secret
$jwt_secret_base64_encoded = $jwtAuthTokenSecretKey;
$jwt_secret_base64_decoded = base64_decode($jwt_secret_base64_encoded);

//Create Token Signature using HS256
$created_token_signature = hash_hmac("SHA256", $jwt_header_json_encoded_base64_encoded.".".$jwt_payload_json_encoded_base64_encoded, $jwt_secret_base64_decoded, true);

$created_token_signature_base64_urlencoded = base64url_encode($created_token_signature); 

//remove padding (=)
$created_token_signature_base64_urlencoded_after_removing_padding = str_replace("=", "", $created_token_signature_base64_urlencoded);

//Create JWT Token
return $jwt_token_created = $jwt_header_json_encoded_base64_encoded.".".$jwt_payload_json_encoded_base64_encoded.".".$created_token_signature_base64_urlencoded_after_removing_padding;
}

//function qezy_enc_web_analytics($user_id,$user_name,$post_id,$post_title,$post_name,$page_referer,$os_name,$browser_name,$browser_version,$session_id,$ip_address,$country_code,$country_name,$state,$city,$geoinfo_status,$play,$device)
function qezy_enc_web_analytics($user_id,$user_name,$post_id,$post_title,$post_name,$page_referer,$os_name,$browser_name,$browser_version,$session_id,$ip_address,$play,$device)
{
$jwtAuthTokenSecretKey = base64_encode("QezyplayIB");

$jwt_header = array();
$jwt_payload = array();

//Inject typ and alg inputs as per JSON Web Tokens Specification in to $jwt_header array
$jwt_header["typ"] = "JWT";
$jwt_header["alg"] = "SHA256";
//print_r($jwt_header);
$jwt_header_json_encoded = json_encode($jwt_header);
$jwt_header_json_encoded_base64_encoded = base64_encode($jwt_header_json_encoded);

//Inject Registered Claims like iat, nbf, exp, iss along with other private claim inputs as per JSON Web Tokens Specification in to $jwt_payload array

//need to create a function to get latest EPOCH as per chosen TIMEZONE,
//since, $current_epoch gives epoch at GMT< we had added 19800 seconds manually.


//$user_id = "2";//get req user_id

$token_created_time_epoch = strtotime(gmdate("Y-m-d H:i:s"));
$token_expiry_epoch = $token_created_time_epoch + 5000; //2hrs 

$jwt_payload["iat"] = $token_created_time_epoch;
$jwt_payload["nbf"] = $token_created_time_epoch;
$jwt_payload["exp"] = $token_expiry_epoch;
$jwt_payload["iss"] = "ideabytes";
$jwt_payload["uid"] = strval($user_id);
//$jwt_payload["channelinfo"] = array("url" => "url","name" => "name");

$jwt_payload["analyticsinfo"] = array("user_id" => $user_id,"user_name" => $user_name, "session_id" => $session_id,"page_id" => $post_id,"page_name" => $post_name,"page_title" => $post_title,"device"=>$device,"os_version"=>" ","ip_address" => $ip_address,"os_name" => $os_name,"browser_name" => $browser_name,"browser_version" => $browser_version,"page_referer" => $page_referer,"play"=>$play); 

//print_r($jwt_payload);
$jwt_payload_json_encoded = json_encode($jwt_payload);
$jwt_payload_json_encoded_base64_encoded = base64_encode($jwt_payload_json_encoded);


//base64 decode jwt secret
$jwt_secret_base64_encoded = $jwtAuthTokenSecretKey;
$jwt_secret_base64_decoded = base64_decode($jwt_secret_base64_encoded);

//Create Token Signature using HS256
$created_token_signature = hash_hmac("SHA256", $jwt_header_json_encoded_base64_encoded.".".$jwt_payload_json_encoded_base64_encoded, $jwt_secret_base64_decoded, true);

$created_token_signature_base64_urlencoded = base64url_encode($created_token_signature); 

//remove padding (=)
$created_token_signature_base64_urlencoded_after_removing_padding = str_replace("=", "", $created_token_signature_base64_urlencoded);

//Create JWT Token
return $jwt_token_created = $jwt_header_json_encoded_base64_encoded.".".$jwt_payload_json_encoded_base64_encoded.".".$created_token_signature_base64_urlencoded_after_removing_padding;
}

// For validate authendication token
function validateAuthToken($received_jwt_token){
	
	$current_epoch = strtotime(gmdate("Y-m-d H:i:s"));

	$jwtAuthTokenSecretKey = base64_encode(TOKEN_SECRET_KEY);

	$jwt_secret_base64_decoded = base64_decode($jwtAuthTokenSecretKey);
	
	$received_jwt_token_exploded = explode(".", $received_jwt_token);
	$received_jwt_token_exploded_count = count($received_jwt_token_exploded);
	
	if ($received_jwt_token_exploded_count == "3") {

	   //do the required code
		$received_jwt_token_header = $received_jwt_token_exploded[0];
		//echo "received_jwt_token_header: " . $received_jwt_token_header . "<br><br>";

		$received_jwt_token_payload = $received_jwt_token_exploded[1];
		//echo "received_jwt_token_payload: " . $received_jwt_token_payload . "<br><br>";

		$received_jwt_token_signature = $received_jwt_token_exploded[2];
		//echo "received_jwt_token_signature: " . $received_jwt_token_signature . "<br><br>";

		
		//json decoded header
		$received_jwt_token_header_json_decoded = json_decode(base64_decode($received_jwt_token_header), true);

		//json decoded payload
		$received_jwt_token_payload_json_decoded = json_decode(base64_decode($received_jwt_token_payload), true);
		
		/*
		echo "<pre>";
		print_r($received_jwt_token_header_json_decoded);
		echo "<br><br><br>";
		print_r($received_jwt_token_payload_json_decoded);
		echo "</pre>";
		*/

		//Re-Create Signature of Header and payload
		//$created_hash_for_verification = hash_hmac('sha256', $received_jwt_token_header . "." . $received_jwt_token_payload, $jwt_secret_base64_encoded);
		$created_hash_for_verification = hash_hmac("SHA256", $received_jwt_token_header.".".$received_jwt_token_payload, $jwt_secret_base64_decoded, true);
		//$created_hash_for_verification = hash_hmac('sha256', $received_jwt_token_payload, $jwt_secret_base64_decoded);
		//$created_hash_for_verification = hash_hmac('sha256', base64_encode($received_jwt_token_header_json_decoded). "." . base64_encode($received_jwt_token_payload_json_decoded), $jwt_secret_base64_encoded);
		$created_hash_for_verification_base64_encoded = base64_encode($created_hash_for_verification);


		//As per Base64 URL Encoding Concept that is described in https://tools.ietf.org/html/rfc4648#page-7
		//http://stackoverflow.com/a/11449627
		

		$created_hash_for_verification_base64_urlencoded = base64url_encode($created_hash_for_verification);

		//remove padding (=)
		$created_hash_for_verification_base64_urlencoded_after_removing_padding = str_replace("=", "", $created_hash_for_verification_base64_urlencoded);
		
		/*
		echo "Received Signature (base64 decoded): " . base64_decode("T_qDr0_gYt9FZVm1yplQLVCdreCNAVAspVqjQaXvTB4") . "<br>";
		//echo "Received Signature (base64 encoded): " . "T_qDr0_gYt9FZVm1yplQLVCdreCNAVAspVqjQaXvTB4" . "<br>";
		echo "Received Signature (base64 encoded): " . $received_jwt_token_signature . "<br>";
		echo "Created Signature: " . $created_hash_for_verification . "<br>";
		echo "Created Signature (base64 encoded): " . $created_hash_for_verification_base64_encoded . "<br>";
		echo "Created Signature (base64 urlencoded): " . $created_hash_for_verification_base64_urlencoded . "<br>";
		echo "Created Signature (base64 urlencoded after removing padding): " . $created_hash_for_verification_base64_urlencoded_after_removing_padding . "<br>";
		*/

		if ($created_hash_for_verification_base64_urlencoded_after_removing_padding === $received_jwt_token_signature) {

			//Signature is valid
			//Check the issuer
			//echo $received_jwt_token_payload_json_decoded["iss"];
			if ($received_jwt_token_payload_json_decoded["iss"] == "ideabytes") {

				//echo 'received_jwt_token_payload_json_decoded["exp"]: ' . $received_jwt_token_payload_json_decoded["exp"] . "<br>";          
				//echo 'current_epoch: ' . $current_epoch . "<br>";  
				if (($received_jwt_token_payload_json_decoded["iat"] < $received_jwt_token_payload_json_decoded["exp"]) && ($current_epoch < $received_jwt_token_payload_json_decoded["exp"])) {

					//Token is Valid
					//echo "Token is Valid";
					
					$customer_id = $received_jwt_token_payload_json_decoded['uid'];
					//$verification = verifyCustomerToken($customer_id, $received_jwt_token);
					$verification = true;
					if($verification){
						
						return $received_jwt_token_payload_json_decoded;
						
					}else{
						//Token got Expired In database as Status as '0' or Not found the token in table
						$msg =  "Token not found in table or Status is '0'";
					}
					
				} else {
					//Token got Expired
					$msg =  "Token got Expired";					
				}

			} else {
				//Invalid issuer
				$msg =  "Invalid Issuer";				
			}

		} else {
			//Invalid Signature
			$msg = "Invalid Signature";
		}
	}else {
		//reject the JWT Token Input, as it is not Valid.
		$msg =  "Invalid JWT Token.";
	}	
	return $msg;	
}
//$ready_token=qezy_enc("octoshape://streams.octoshape.net/ideabytes/live/ib-ch60/auto","TEST"); 
//echo "Sending Token: ".$ready_token;

function validateAuthTokenAna($received_jwt_token){

	//echo $received_jwt_token;
	
	$current_epoch = strtotime(gmdate("Y-m-d H:i:s"));

	$jwtAuthTokenSecretKey = base64_encode(TOKEN_SECRET_KEY);

	$jwt_secret_base64_decoded = base64_decode($jwtAuthTokenSecretKey);
	
	$received_jwt_token_exploded = explode(".", $received_jwt_token);
	
	$received_jwt_token_exploded_count = count($received_jwt_token_exploded);
	
	if ($received_jwt_token_exploded_count == "3") {

	   //do the required code
		$received_jwt_token_header = $received_jwt_token_exploded[0];
		//echo "received_jwt_token_header: " . $received_jwt_token_header . "<br><br>";

		$received_jwt_token_payload = $received_jwt_token_exploded[1];
		//echo "received_jwt_token_payload: " . $received_jwt_token_payload . "<br><br>";

		$received_jwt_token_signature = $received_jwt_token_exploded[2];
		//echo "received_jwt_token_signature: " . $received_jwt_token_signature . "<br><br>";

		
		//json decoded header
		$received_jwt_token_header_json_decoded = json_decode(base64_decode($received_jwt_token_header), true);

		//json decoded payload
		$received_jwt_token_payload_json_decoded = json_decode(base64_decode($received_jwt_token_payload), true);
		
		/*
		echo "<pre>";
		print_r($received_jwt_token_header_json_decoded);
		echo "<br><br><br>";
		print_r($received_jwt_token_payload_json_decoded);
		echo "</pre>";
		*/

		//Re-Create Signature of Header and payload
		//$created_hash_for_verification = hash_hmac('sha256', $received_jwt_token_header . "." . $received_jwt_token_payload, $jwt_secret_base64_encoded);
		$created_hash_for_verification = hash_hmac("SHA256", $received_jwt_token_header.".".$received_jwt_token_payload, $jwt_secret_base64_decoded, true);
		//$created_hash_for_verification = hash_hmac('sha256', $received_jwt_token_payload, $jwt_secret_base64_decoded);
		//$created_hash_for_verification = hash_hmac('sha256', base64_encode($received_jwt_token_header_json_decoded). "." . base64_encode($received_jwt_token_payload_json_decoded), $jwt_secret_base64_encoded);
		$created_hash_for_verification_base64_encoded = base64_encode($created_hash_for_verification);


		//As per Base64 URL Encoding Concept that is described in https://tools.ietf.org/html/rfc4648#page-7
		//http://stackoverflow.com/a/11449627
		

		$created_hash_for_verification_base64_urlencoded = base64url_encode($created_hash_for_verification);

		//remove padding (=)
		$created_hash_for_verification_base64_urlencoded_after_removing_padding = str_replace("=", "", $created_hash_for_verification_base64_urlencoded);
		
		/*
		echo "Received Signature (base64 decoded): " . base64_decode("T_qDr0_gYt9FZVm1yplQLVCdreCNAVAspVqjQaXvTB4") . "<br>";
		//echo "Received Signature (base64 encoded): " . "T_qDr0_gYt9FZVm1yplQLVCdreCNAVAspVqjQaXvTB4" . "<br>";
		echo "Received Signature (base64 encoded): " . $received_jwt_token_signature . "<br>";
		echo "Created Signature: " . $created_hash_for_verification . "<br>";
		echo "Created Signature (base64 encoded): " . $created_hash_for_verification_base64_encoded . "<br>";
		echo "Created Signature (base64 urlencoded): " . $created_hash_for_verification_base64_urlencoded . "<br>";
		echo "Created Signature (base64 urlencoded after removing padding): " . $created_hash_for_verification_base64_urlencoded_after_removing_padding . "<br>";
		*/

		if ($created_hash_for_verification_base64_urlencoded_after_removing_padding === $received_jwt_token_signature) {

			//Signature is valid
			//Check the issuer
			
			if ($received_jwt_token_payload_json_decoded["iss"] == "ideabytes") {

				//echo 'received_jwt_token_payload_json_decoded["exp"]: ' . $received_jwt_token_payload_json_decoded["exp"] . "<br>";          
				//echo 'current_epoch: ' . $current_epoch . "<br>";  
			//	if (($received_jwt_token_payload_json_decoded["iat"] < $received_jwt_token_payload_json_decoded["exp"]) && ($current_epoch < $received_jwt_token_payload_json_decoded["exp"])) {
					if (1) {

					//Token is Valid
					//echo "Token is Valid";
					
					$customer_id = $received_jwt_token_payload_json_decoded['uid'];
					//$verification = verifyCustomerToken($customer_id, $received_jwt_token);
					$verification = true;
					if($verification){
						//var_dump($received_jwt_token_payload_json_decoded);
						return $received_jwt_token_payload_json_decoded;
						
					}else{
						//Token got Expired In database as Status as '0' or Not found the token in table
						$msg =  "Token not found in table or Status is '0'";
					}
					
				} else {
					//Token got Expired
					$msg =  "Token got Expired";					
				}

			} else {
				//Invalid issuer
				$msg =  "Invalid Issuer";				
			}

		} else {
			//Invalid Signature
			$msg = "Invalid Signature";
		}
	}else {
		//reject the JWT Token Input, as it is not Valid.
		$msg =  "Invalid JWT Token.";
	}	
	return $msg;	
}

function get_geoinfo_by_ip($ip_address){

$geoinfo = "http://api.ipinfodb.com/v3/ip-city/?key=13ebc6d8740ab89e93e615530a59dd0f22df559274089129135f83188578f84d&ip=$ip_address&format=json";
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

			}

			return array($country_code,$country,$state,$city,$tz,$lat,$lngt,$geoinfo_status);

}

?>

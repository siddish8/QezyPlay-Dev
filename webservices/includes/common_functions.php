<?php

function base64url_encode($s) {
	return str_replace(array('+', '/'), array('-', '_'), base64_encode($s));
}


function base64url_decode($s) {
	return base64_decode(str_replace(array('-', '_'), array('+', '/'), $s));
}

function createToken($username, $email, $userId){

	$jwtAuthTokenSecretKey = base64_encode(TOKEN_SECRET_KEY);

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

	$token_created_time_epoch = strtotime(gmdate("Y-m-d H:i:s"));
	$token_expiry_epoch = $token_created_time_epoch + 84000; 

	$jwt_payload["iat"] = $token_created_time_epoch;
	$jwt_payload["nbf"] = $token_created_time_epoch;
	$jwt_payload["exp"] = $token_expiry_epoch;
	$jwt_payload["iss"] = "ideabytes";
	$jwt_payload["uid"] = strval($userId);
	
	$jwt_payload["userinfo"] = array("user_email" => $email,"user_login" => $username);
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
	$jwt_token_created = $jwt_header_json_encoded_base64_encoded.".".$jwt_payload_json_encoded_base64_encoded.".".$created_token_signature_base64_urlencoded_after_removing_padding;

	return $jwt_token_created;	
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
						$msg =  "Invalid token for the user";
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

function sendMail($toAddress, $subject, $message){

	if ( !class_exists("PHPMailer") ) 
		{ include('../PHPMailer/class.phpmailer.php'); }
	
	 
	$mail = new PHPMailer;
	
	/*$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'bu.pradeep@gmail.com';
	$mail->Password = '';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->SMTPAuth = true;
	$mail->isSMTP();
	$mail->SMTPDebug = 1;*/

	$mail->SetFrom("admin@qezyplay.com","QezyPlay");
	
	//$mail->SetFrom("bu.pradeep@gmail.com");
	$mail->AddAddress($toAddress);
	 
	$mail->isHTML(true);
	 
	$mail->Subject = $subject;
	$mail->MsgHTML($message);
	 
	if(!$mail->send()) {	   
	   //echo 'Mailer Error: ' . $mail->ErrorInfo;
	   //exit;
	}
}




//added
		






?>

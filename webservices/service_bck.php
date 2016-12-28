<?php

class Service extends ServiceData{
	

	public function handleRegister() {

		try {
			
			if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["phone"])){			
			
				$info = $this->processRegistration($_POST["username"], $_POST["email"], $_POST["phone"]); 
				$info = $this->processRegistration($_POST["username"], $_POST["email"], $_POST["phone"],$_POST["ip"]); 
				if(is_array($info) && count($info) > 0){
					$toUser = "pradeep.ganapathy@ideabytes.com";
					$toAdmin = ADMIN_EMAIL;

					//$userSubject = "pradeep.ganapathy@ideabytes.com";
					//$userMessage = "pradeep.ganapathy@ideabytes.com";
					//sendMail($toUser, $userSubject, $userMessage);
					//sendmail($toAdmin, $adminSubject, $adminMessage);
				}

				$this->response($info);
				
			}else{				
				$this->response('Insufficeient inputs');
			}
	
		}catch (Exception $e){
		    $this->response("Request failed"); 
		}
		
	}
	
	public function handleLogin() {

		try {
			
			if(isset($_POST["username"]) && isset($_POST["password"])){			
				
				$info = $this->processLogin($_POST["username"], $_POST["password"]); 
				$this->response($info);
				
			}else{				
				$this->response('Insufficeient inputs');
			}
	
		}catch (Exception $e){
		    $this->response("Request failed"); 
		}
		
	}

	public function handleForgetPassword() {

		try {
			
			if(isset($_POST["email"])){			
				
				$info = $this->processForgetPassword($_POST["email"]); 

				if(is_array($info) && count($info) > 0){

					$info = getUserInfo("user_email", $_POST["email"]);

					if(is_array($info) && count($info) > 1){
						$token =  createToken($info['user_login'], $info['user_email'], $info['id']);
						$toUser = "pradeep.ganapathy@ideabytes.com";
						$toAdmin = ADMIN_EMAIL;
					

						//$userSubject = "pradeep.ganapathy@ideabytes.com";
						//$userMessage = "pradeep.ganapathy@ideabytes.com";
						//sendMail($toUser, $userSubject, $userMessage);
						//sendmail($toAdmin, $adminSubject, $adminMessage);
					}
				}
				$this->response($info);
				
			}else{				
				$this->response('Insufficeient inputs');
			}
	
		}catch (Exception $e){
		    $this->response("Request failed"); 
		}		
	}

	public function handleResetPassword() {

		try {
			
			if(isset($_POST["token"]) && isset($_POST["password"])){			
				
				$userInfo =  validateAuthToken($info['token']);
				if(is_array($info) && count($info) > 1){
					$info = $this->processResetPassword($userInfo["user_email"], $_POST["password"]); 
				}
				$this->response($info);
				
			}else{				
				$this->response('Insufficeient inputs');
			}
	
		}catch (Exception $e){
		    $this->response("Request failed"); 
		}
		
	}	
}
?>

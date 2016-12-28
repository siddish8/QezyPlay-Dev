<?php

class Service extends ServiceData{
	

	public function handleRegister() {

		try {
			
			if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["ip"])){			
			
				//$info = $this->processRegistration($_POST["username"], $_POST["email"], $_POST["phone"]); 
				$info = $this->processRegistration($_POST["username"], $_POST["email"], $_POST["phone"],$_POST["ip"]); 
				if(is_array($info) && count($info) > 0){
					$toUser = "pradeep.ganapathy@ideabytes.com";
					$toAdmin = ADMIN_EMAIL;

					//$userSubject = "pradeep.ganapathy@ideabytes.com";
					//$userMessage = "pradeep.ganapathy@ideabytes.com";

					$userSubject = 'Your Account Info';
					$adminSubject ='New User';
		
				
			

			//$userMessage="<p>Hi,</p> <p>Thanks for registering. Your account needs activation.</p><p> Please click on the link below to activate your account: {{user_ultra_activation_url}} </p><p>Your account e-mail: {{userultra_user_email}} </p><p>Your account username: {{userultra_user_name}}</p><p> Your account password: {{userultra_pass}} </p><p>Note: You can change your password at {{userultra_change_password}} after Login.<br><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

			$userMessage="<p>Hi,</p> <p>Thanks for registering. Your account needs activation.</p><p>Your account e-mail: {{userultra_user_email}} </p><p>Your account username: {{userultra_user_name}}</p><p> Your account password: {{userultra_pass}} </p><p>Note: You can change your password at {{userultra_change_password}} after Login.<br><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

			$adminMessage="Hi Admin, <br> <p>New User Registered at qezyplay.com .</p><p>Account e-mail: {{userultra_user_email}} </p><p>Account username: {{userultra_user_name}}</p><p>Account phone:{{userultra_user_phone}} </p> <br /> <p>Regards,</p><p>QezyPlay</p>";
		

		$u_email=$_POST["email"];
		$user_login=$_POST["username"];
		$user_phone=$_POST["phone"];
		$admin_email1="admin@qezyplay.com";
		$login_url =SITE_URL;
		$change_pwd_link=SITE_URL."/privacy-settings";
		
		$userMessage = str_replace("{{user_ultra_activation_url}}", $activation_link, $userMessage);
		$userMessage = str_replace("{{userultra_user_email}}", $u_email,  $userMessage);
		$userMessage = str_replace("{{userultra_user_name}}", $user_login,  $userMessage);
		$userMessage= str_replace("{{userultra_pass}}", $user_pass,  $userMessage);
		$userMessage = str_replace("{{userultra_admin_email}}", $admin_email1,  $userMessage);
		$userMessage = str_replace("{{userultra_change_password}}", $change_pwd_link,  $userMessage);
		
		//admin
		$adminMessage = str_replace("{{userultra_user_email}}", $u_email,  $adminMessage);
		$adminMessage = str_replace("{{userultra_user_name}}", $user_login,  $adminMessage);
		$adminMessage = str_replace("{{userultra_user_phone}}", $user_phone,  $adminMessage);
		//$template_admim = str_replace("{{userultra_admin_email}}", $admin_email,  $template_admim);	
				

		

					sendMail($toUser, $userSubject, $userMessage);
					sendmail($toAdmin, $adminSubject, $adminMessage);



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

					$info =$this->getUserInfo("user_email", $_POST["email"]);
					
					if(is_array($info) && count($info) > 1){
						$token =  createToken($info['user_login'], $info['user_email'], $info['id']);
						//$toUser = "pradeep.ganapathy@ideabytes.com";
						$toUser = $info['user_email'];
						$toAdmin = ADMIN_EMAIL;
					
						$reset_link=SITE_URL."/login/?reset_passkey=".$token;
						$link=$reset_link; //ADD THIS
						//$userSubject = "pradeep.ganapathy@ideabytes.com";
						//$userMessage = "pradeep.ganapathy@ideabytes.com";

						$userMessage="<p>Hi,</p> <p>Please use the following link to reset your password.</p><p> {{userultra_reset_link}}</p> <br><p>If you did not request a new password delete this email.</p><p>Best Regards, <br />Admin - Qezyplay</p>";
		
						//$blogname  = $this->get_option('messaging_send_from_name');
		
		
						$userSubject = "Reset Your Password";
		
						$userMessage = str_replace("{{userultra_reset_link}}", $link,  $userMessage);
						$userMessage = str_replace("{{userultra_admin_email}}", $admin_email,  $userMessage);		
		
						
						sendMail($toUser, $userSubject, $userMessage);
						
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

					$toUser = "pradeep.ganapathy@ideabytes.com";
						$toAdmin = ADMIN_EMAIL;
					

						//$userSubject = "pradeep.ganapathy@ideabytes.com";
						//$userMessage = "pradeep.ganapathy@ideabytes.com";

						$userSubject = "Password Reset Confirmation";
		
		
						$userMessage="<p>Hi,</p>  <p> Your password has been reset.</p><p> To login please visit the following URL: {{userl_ultra_login_url}} </p><p>Your e-mail: {{userultra_user_email}} </p><p>Your username: {{userultra_user_name}} </p><p>Your password: {{userultra_pass}} </p><br><p>If you have any problems, please contact us at {{userultra_admin_email}}. </p>  <p>Best Regards, <br/>Admin - Qezyplay</p>";
						
						$login_url =SITE_NAME."/login";
						$user_login=$userInfo["user_login"];
						$u_email=$userInfo["user_email"];
						$user_pass=$_POST["password"];
						$admin_email1="admin@qezyplay.com";

						$userMessage = str_replace("{{userl_ultra_login_url}}", $login_url,  $userMessage);		
						$userMessage = str_replace("{{userultra_user_name}}", $user_login,  $userMessage);		
						$userMessage = str_replace("{{userultra_user_email}}", $u_email,  $userMessage);
						$userMessage = str_replace("{{userultra_pass}}", $user_pass,  $userMessage);
						$userMessage = str_replace("{{userultra_admin_email}}", $admin_email1,  $userMessage);

						sendMail($toUser, $userSubject, $userMessage);
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
}
?>

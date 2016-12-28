<?php

class Service extends ServiceData{
	

	public function handleRegister() {

		try {
			
			if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["password"])){			
			
				//$info = $this->processRegistration($_POST["username"], $_POST["email"], $_POST["phone"]); 
				$info = $this->processRegistration($_POST["username"], $_POST["email"],$_POST["password"], $_POST["phone"],$_POST["cc"],$_POST["ip"]); 
				if(is_array($info) && count($info) > 0){
					$toUser = $_POST["email"];
					$toAdmin = "siddish.gollapelli@ideabytes.com";

					//$userSubject = "pradeep.ganapathy@ideabytes.com";
					//$userMessage = "pradeep.ganapathy@ideabytes.com";

					$userSubject = 'Verify Your Account';
					$adminSubject ='New User';
		
				
			

			//$userMessage="<p>Hi,</p> <p>Thanks for registering. Your account needs activation.</p><p> Please click on the link below to activate your account: {{user_ultra_activation_url}} </p><p>Your account e-mail: {{userultra_user_email}} </p><p>Your account username: {{userultra_user_name}}</p><p> Your account password: {{userultra_pass}} </p><p>Note: You can change your password at {{userultra_change_password}} after Login.<br><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

		//	$userMessage="<p>Hi,</p> <p>Thanks for registering. Your account needs activation.</p><p> Please click on the link below to activate your account: {{user_ultra_activation_url}} </p><p>Your account e-mail: {{userultra_user_email}} </p><p>Your account username: {{userultra_user_name}}</p><p> Your account password: {{userultra_pass}} </p><p>Note: You can change your password at {{userultra_change_password}} after Login.<br><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

			//$adminMessage="Hi Admin, <br> <p>New User Registered at qezyplay.com .</p><p>Account e-mail: {{userultra_user_email}} </p><p>Account username: {{userultra_user_name}}</p><p>Account phone:{{userultra_user_phone}} </p> <br /> <p>Regards,</p><p>QezyPlay</p>";
				

			
		$u_email=$_POST["email"];
		$user_login=$_POST["username"];
		$user_phone=$_POST["phone"];
		$user_pass=$_POST["password"];
		$admin_email1="admin@qezyplay.com";
		$login_url =SITE_URL;
		$change_pwd_link=SITE_URL."/privacy-settings";

		$key=$this->get_act_key($user_login);
		$activation_link=SITE_URL."/activate/?act_link=".$key;

		$sub_stat=$this->get_sub_details($user_login);

		$userMessage="<p>Hi,</p> <p>Thanks for registering. Your account needs activation.</p><p> Please click on the link below to activate your account: {{user_ultra_activation_url}} </p><p>E-mail: {{userultra_user_email}} </p><p>Username: {{userultra_user_name}}</p><p>Note: {{user_sub_details}} Enjoy Viewing.</p><p>You can change your password at {{userultra_change_password}} after Login.<br><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

			$adminMessage="Hi Admin, <br> <p>New User Registered with Email change at ".SITE_URL. " .</p><p>Account e-mail: {{userultra_user_email}} </p><p>Account username: {{userultra_user_name}}</p><p>Account phone:{{userultra_user_phone}} </p> <br /> <p>Regards,</p><p>QezyPlay</p>";
		
		$userMessage = str_replace("{{user_ultra_activation_url}}", $activation_link, $userMessage);
		$userMessage = str_replace("{{userultra_user_email}}", $u_email,  $userMessage);
		$userMessage = str_replace("{{userultra_user_name}}", $user_login,  $userMessage);
		$userMessage= str_replace("{{userultra_pass}}", $user_pass,  $userMessage);
		$userMessage = str_replace("{{userultra_admin_email}}", $admin_email1,  $userMessage);
		$userMessage = str_replace("{{userultra_change_password}}", $change_pwd_link,  $userMessage);
		$userMessage = str_replace("{{user_sub_details}}", $sub_stat,  $userMessage);
		
		
		
		//admin
		$adminMessage = str_replace("{{userultra_user_email}}", $u_email,  $adminMessage);
		$adminMessage = str_replace("{{userultra_user_name}}", $user_login,  $adminMessage);
		$adminMessage = str_replace("{{userultra_user_phone}}", $user_phone,  $adminMessage);
		//$template_admim = str_replace("{{userultra_admin_email}}", $admin_email,  $template_admim);	
				
				$ad="admin@qezyplay.com";
				$from="QezyPlay <".$ad.">";

					$toAdmin="siddish.gollapelli@ideabytes.com";
					$toUser=$u_email;
					sendMail($toUser, $userSubject, $userMessage);
					sendMail("siddish.gollapelli@ideabytes.com", $adminSubject, $adminMessage);

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
	
		$info = $this->processLogin($_POST["username"]); 
				if(is_array($info) && count($info) > 0){
					$this->response($info);
				}
	
}

	

	
}
?>

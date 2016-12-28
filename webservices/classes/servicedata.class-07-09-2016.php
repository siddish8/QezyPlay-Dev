<?php

Class ServiceData extends DBCon{
		
	//function processRegistration($username, $email, $phone){
	function processRegistration($username, $email, $phone,$ip){
		if($username == "")
			return "Username is empty";
		if($email == "")
			return "Email is empty";
		if($phone == "")
			return "Phone is empty";
			
		try{
			$stmt21 = $this->db->prepare('SELECT ID FROM wp_users WHERE user_login = "'.$username.'" OR user_email = "'.$email.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt21->execute();
			$result21 = $stmt21->fetch(PDO::FETCH_ASSOC);
			$userexit = $result21['ID'];					
			if((int)$userexit > 0){
				return "Username/Email already exist";
			}else{

									
					
				$stmt23 = $this->db->prepare("SHOW TABLE status LIKE 'wp_users'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt23->execute();
			$result23 = $stmt23->fetchAll(PDO::FETCH_ASSOC);
				foreach($result23 as $res)
				{
				$userid = $res['Auto_increment'];
				}
				$date=new DateTime("now");
				$date=$date->format("Y-m-d H:i:s");
				$pass=md5($username.$userid);

				$meta_key="uultra_user_registered_ip";
				$ipaddr=$ip;
					
				$stmt22 = $this->db->prepare('INSERT INTO wp_users(user_login,user_pass,user_email,user_nicename,phone,user_registered,display_name) VALUES("'.$username.'","'.$pass.'","'.$email.'","'.$username.'","'.$phone.'","'.$date.'","'.$username.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
				$stmt22->execute();

				$stmt24 = $this->db->prepare('INSERT INTO wp_usermeta(user_id,meta_key,meta_value) VALUES("'.$userid.'","'.$meta_key.'","'.$ipaddr.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
				$stmt24->execute();
							
				$response['status'] = 1;
				
				return $response;
			}									
		}catch(Exception $e){					
			return "Error, Please try again";			
		}
	}
	
	function processLogin($username, $password){
						
		if($username == "")
			return "Username is empty";
		if($password == "")
			return "Password is empty";
					
		try{
			
			$stmt21 = $this->db->prepare('SELECT ID,user_pass,user_email,user_login FROM wp_users WHERE (user_login = "'.$username.'" OR user_email = "'.$username.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt21->execute();
			$result21 = $stmt21->fetch(PDO::FETCH_ASSOC);								
			if(count($result21) > 1){
											
				$username = $result21['user_login'];
				$dbpassword = $result21['user_pass'];
				$email = $result21['user_email'];
				$userId = $result21['ID'];

				include '../wp-includes/class-phpass.php';

				$wp_hasher=new PasswordHash(8,TRUE);

				$valid1=(int)$wp_hasher->checkPassword($password,$dbpassword);
				$valid2=(md5($password)==$dbpassword)?1:0;
				if($valid1 or $valid2){
				//if($password == $dbpassword){
				//if(1){	
					$token = createToken($username, $email, $userId);					
					if($token != ""){
						$response['status'] = "1";
						$response['token'] = $token;							
						return $response;
					}else{
						return "Please try again"; 
					}						
				}else{
					return "Invalid password";
				}						
			}else{
				return "User not found";
			}				
		}catch(Exception $e){					
			return "Error, Please try again";			
		}	
	}
	
	function processForgetPassword($email){
		
		if($email == "")
			return "Email is empty";
		
		try{
			
			$stmt21 = $this->db->prepare('SELECT user_login,user_pass,id FROM wp_users WHERE user_email = "'.$email.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt21->execute();
			$result21 = $stmt21->fetch(PDO::FETCH_ASSOC);								
			if(count($result21) > 1){					
				
				//Send mail
				$response['status'] = 1;
				return $response;
				
			}else{
				return "User not found";
			}				
		}catch(Exception $e){					
			return "Error, Please try again";			
		}		
	}

	function processResetPassword($email, $password){
		
		if($email == "")
			return "Email is empty";
		if($password == "")
			return "Password is empty";
		try{
			
			$stmt21 = $this->db->prepare('SELECT user_login,user_pass,ID FROM wp_users WHERE user_email = "'.$email.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt21->execute();
			$result21 = $stmt21->fetch(PDO::FETCH_ASSOC);								
			if(count($result21) > 1){					
				
				$pass = md5($password);

				$stmt22 = $this->db->prepare('UPDATE wp_users SET user_pass = "'.$pass.'" WHERE user_email = "'.$email.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
				$stmt22->execute();

				$response['status'] = 1;
				return $response;
				
			}else{
				return "User not found";
			}				
		}catch(Exception $e){					
			return "Error, Please try again";			
		}		
	}

	function getUserInfo($by, $value){
		
			
		try{
			
			$stmt21 = $this->db->prepare('SELECT * FROM wp_users WHERE '.$by.' = "'.$value.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt21->execute();
			$result21 = $stmt21->fetch(PDO::FETCH_ASSOC);								
			if(count($result21) > 1){					
				return $result21;
				
			}else{
				return "User not found";
			}				
		}catch(Exception $e){					
			return "Error, Please try again";			
		}		
	}
	
}

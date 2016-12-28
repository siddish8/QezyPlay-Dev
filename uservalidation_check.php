<?php
include("db-config.php");
include("function_common.php");
include '../wp-includes/class-phpass.php';

//$action=$_POST['action'];
$action=$_REQUEST['action'];




switch($action){

	case "searchuser":
		
		$emailUN = trim($_POST['emailUN']);
		$email = trim($_POST['email']);
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$username = $_POST['username'];
		$phone = $_POST['phone'];

		$cond1 = $condMeta = $cond2 = "";
		if($emailUN!="")
			$cond1 .= " AND (user_email = :emailUN OR user_login = :emailUN)";
		if($email!="")
			$cond1 .= " AND user_email = :email";
		if($username!="")
			$cond1 .= " AND user_login = :username";
		if($firstname!="")
			$cond2 .= " AND (meta_key = 'first_name' AND meta_value like :firstname)";
		if($lastname!="")
			$cond2 .= " AND (meta_key = 'last_name' AND meta_value like :lastname)";
		if($phone!="")
			$cond2 .= " AND (meta_key = 'phone' AND meta_value like :phone)";

		if($cond2 != ""){
			$condMeta = " AND ID in (SELECT DISTINCT user_id FROM wp_usermeta WHERE 1".$cond2.")";
		}

		$sql = "SELECT ID, user_login, user_email FROM wp_users WHERE 1".$cond1."".$condMeta;		
		try {
			$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			if($emailUN!="")	
				$stmt->bindParam(":emailUN", $emailUN);
			if($email!="")	
				$stmt->bindParam(":email", $email);
			if($username!="")
				$stmt->bindParam(":username", $username);
			if($firstname!="")
				$stmt->bindParam(":firstname", $firstname);
			if($lastname!="")
				$stmt->bindParam(":lastname", $lastname);
			if($phone!="")
				$stmt->bindParam(":phone", $phone);

			$stmt->execute();	
			$count = $stmt->rowCount();
			if($count > 0){
				
				$res = $stmt->fetch(PDO::FETCH_ASSOC);							
				echo json_encode($res);				
			}		

			$stmt = null;

		}catch (PDOException $e){
			print $e->getMessage();
		}
		exit;
		break;
	case "useractive":

		$sub_id=$_POST['user_id'];

		$sql2 = "SELECT count(*) as count FROM agent_vs_subscription_credit_info WHERE 1 AND subscriber_id = ? AND DATE(subscription_end_on) >= CURRENT_DATE()";	
	
	$sql3 = "SELECT count(*) as count FROM wp_pmpro_memberships_users WHERE 1 AND user_id = ? AND status = 'active'";	
	
	try {
		
		$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		//$stmt->execute(array($plan_id, $sub_id));
		$stmt2->execute(array($sub_id));
		$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);	
		$agentSubsCont = $result2['count'];
		$stmt2 = null;
		
		$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));		
		$stmt3->execute(array($sub_id));
		$result3 = $stmt3->fetch(PDO::FETCH_ASSOC);	
		$SelfSubsCont = $result3['count'];
		$stmt3 = null;

		//$delay=get_var("select option_value from wp_options where option_name='pmpro_sub_support_delay_".$sub_id."' ");
		//$upto=get_var("select next_paydate from pmpro_dates_chk1 where user_id=".$sub_id." order by id desc ");
		
		if(($agentSubsCont > 0) || ($SelfSubsCont > 0))
			{
				//echo "{Delay:".$delay."} {NextPay/End Date:".$upto."}";
				echo "1"; //from live_bck
			}
		else
		
				echo "0";		
		
	}catch (PDOException $e){
		print $e->getMessage();
	}
	exit;
		break;

	case "userpending":

		$sub_id=$_POST['user_id'];

		$sql4 = "SELECT agent_id,count(*) as count FROM agent_remittence_pending WHERE subscriber_id = ? AND status = 'pending'";	
		//$sql4 = "SELECT count(*) as count FROM agent_remittence_pending WHERE subscriber_id = ? AND status = 'pending'";	//frm live bck
	try {
		
		$stmt4 = $dbcon->prepare($sql4, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		//$stmt->execute(array($plan_id, $sub_id));
		$stmt4->execute(array($sub_id));
		$result4 = $stmt4->fetch(PDO::FETCH_ASSOC);	
		$subPendingCount = $result4['count'];
		$agent=$result4['agent_id'];//not in live bck
		$stmt4 = null;
		
		//echo $sub_id."--".$subPendingCount;
				
		if($subPendingCount > 0)
			echo $agent; //not in live bck
			//echo "1"; //from live_bck
		else
			echo "0";		
		
	}catch (PDOException $e){
		print $e->getMessage();
	}
	exit;
		break;


	case "getEmail":

		$username=$_POST["user"];
		$reg_email=get_var("SELECT user_email from wp_users where user_login='".$username."'");

		echo $reg_email; return;
		break;
	exit;


	case "couponCheck":

		$cpn=$_POST['cc'];

		//echo $cpn;

		$cc_exist=get_var("SELECT count(*) from buy_a_friend where coupon_code='".$cpn."'");
		$promo_cc_exist=get_var("SELECT count(*) from promo_codes where coupon_code='".$cpn."'");

			if($cc_exist==0 and $promo_cc_exist==0)
			{
			//$msg=1;
			//$msg='Invalid Coupon code';
			echo "1";
			}
			else
			{
			$cc_used=get_var("SELECT count(*) from buy_a_friend where status='Paid' and coupon_code='".$cpn."'");
			$promo_cc_max_used=get_var("SELECT count(*) from promo_codes where usage_count<max_count and coupon_code='".$cpn."'");
				if($cc_used==0 and $promo_cc_max_used==0)
				{
				$msg="2";
				//$msg="Coupon Code already used"; or max_used
				echo $msg;
				}
				else{
				$msg="3";
			//$msg="Valid coupon code";
				echo "3";
				}

			}
			//echo $msg;
			//return $msg;

		
	exit;
		break;

	case "userExist":

		//$username=$_POST['u_name'];
		$username=$_REQUEST['u_name'];

		$cc_exist=get_var("SELECT count(*) from wp_users where user_login='".$username."'");
//added
		$u_id=get_var("SELECT ID from wp_users where user_login='".$username."'");
		
		$u_status=get_var("SELECT meta_value FROM wp_usermeta where meta_key='usersultra_account_status' and user_id=".$u_id." ");

		
		
			if($cc_exist==0)
			{

				echo $msg="0"; return;
			//$msg[1]='New User';
			
			}
			else
			{
				if($u_status=="active")
				{
					echo $msg="1"; return;
				}
				else
				{
					
					//pending so send swal
						echo $msg="2"; return;

				}
			//return $msg;
			}
		//return $msg;
		
	exit;
		break;

	case "emailExist":

		//$email=$_POST['u_email'];
		$email=$_REQUEST['u_email'];
		
		$cc_exist=get_var("SELECT count(*) from wp_users where user_email='".$email."'");
		
		$u_id=get_var("SELECT ID from wp_users where user_email='".$email."'");

		$u_status=get_var("SELECT meta_value FROM wp_usermeta where meta_key='usersultra_account_status' and user_id=".$u_id." ");

			if($cc_exist==0)
			{

				echo $msg="0"; return;
			//$msg[1]='New User';
			
			}
			else
			{
				if($u_status=="active")
				{
					echo $msg="1"; return;
				}
				else
				{
					
					//pending so send swal
						echo $msg="2"; return;

				}
			//return $msg;
			}
		//return $msg;
	exit;
		break;

	case "lr":

		$plan=$_POST['p'];
		
		$_SESSION['log_href']=$value="/gift-a-friend?plan_id=".$plan."&boq_id=4"; 
		//setcookie("log_href", $value, time()+3600);
	exit;
		break;

	case "lrB":
		if($_POST['p']!="")
		{
		$plan=$_POST['p'];
		$plan=str_replace(SITE_URL,"",$plan);}
			elseif($_POST['q']!="")
			{
			$plan=$_POST['q'];
			}
		$_SESSION['log_href']=$plan; 
		//$_COOKIE['log_href']=$plan; 
	exit;
		break;

	case "regEditEmail":

		
		
		$email=$_REQUEST['user_email'];
		
		$o_email=$_REQUEST['old_user_email'];
		
		

		/*if($email=="")
		{
		echo "Please Enter an Email-Id"; return;
	
		}
		
		elseif(!preg_match("/^[a-zA-Z0-9.!#$%&Â’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.].[a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/", $email)) 
					{
 					echo "Enter a valid Email-id."; return;
			}
		*/

		if(get_var("SELECT meta_value FROM wp_usermeta where meta_key='usersultra_account_status' and user_id=(SELECT ID from wp_users where user_email='".$o_email."')")=="active")
			{
				echo "<p><b>Error</b></p><p>Your Account Already Activated</p>"; return; exit;

			}


		if(get_var("SELECT count(*) from wp_users where user_email='".$email."'")>0 or !preg_match("/^[a-zA-Z0-9.!#$%&Â’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.].[a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/", $email) or $email=="")
	
			
			{
			
				echo "<p><b>Error</b></p><p>Email-id already exists. Please select another</p>"; return; exit;
			}

		

		


		//else
			//{

				//update email  

				$sql="UPDATE wp_users SET user_email='".$email."' where user_email='".$o_email."'";
				$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
				$stmt->execute();

				$user_id=get_var("SELECT ID from wp_users where user_email='".$email."'");
				
				$user_name=get_var("SELECT user_login from wp_users where user_email='".$email."'");
				
				

				$sql1="UPDATE wp_usermeta SET meta_value='".$email."' where user_id=".$user_id." and meta_key='user_email' ";
				$stmt = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
				$stmt->execute();
				
				$subject_admin ='New User Changed Email and act resent-ShonarBangla';

				//resend activation link mail
				user_resend_activation_link($user_id, $email, $user_name,$subject_admin); 
				//re_send_activation_link($u_email, $user_login, $activation_link)
				//$info="Email Edited to ".$email." and Activation Link Resent";
				//fwrite($file,$info);
				echo "<p><b>Info</b></p><p>Email updated to $email and Activation Link has been sent to updated Email-id</p>"; return;

			
			//}
	exit;
		break;

	case "regEditEmailPass":

		
		
		$email=$_REQUEST['user_email'];
		
		$o_email=$_REQUEST['old_user_email'];
		
		$password=$_REQUEST['pwd'];

		$dbpassword=get_var("SELECT user_pass from wp_users where user_email='".$o_email."'");

		$wp_hasher=new PasswordHash(8,TRUE);

				$valid1=(int)$wp_hasher->checkPassword($password,$dbpassword);
				$valid2=(md5($password)==$dbpassword)?1:0;
		
		

		/*if($email=="")
		{
		echo "Please Enter an Email-Id"; return;
	
		}
		
		if(!preg_match("/^[a-zA-Z0-9.!#$%&Â’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.].[a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/", $email)) 
					{
 					echo "Enter a valid Email-id."; return;
			}
		*/

		if(get_var("SELECT meta_value FROM wp_usermeta where meta_key='usersultra_account_status' and user_id=(SELECT ID from wp_users where user_email='".$o_email."')")=="active")
			{
				echo "<p><b>Error</b></p><p>Your Account Already Activated with old(registered) email</p>"; return; exit;

			}

		if(get_var("SELECT count(*) from wp_users where user_email='".$email."'")>0 or !preg_match("/^[a-zA-Z0-9.!#$%&Â’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.].[a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/", $email) or $email=="")
	
			
			{
			
				echo "<p><b>Error</b></p><p>Email-id already exists. Please select another</p>"; return; exit;
			}

		
		
		if(!$valid1 and !$valid2){
				
				echo "<p><b>Error</b></p><p>We could not identify you.</p><p> If you cannot recall your Password used to register, please create a new account</p><p>Contact us for <a href='mailto:admin@qezyplay.com?cc=siddish.gollapelli@ideabytes.com&amp;subject=QezyPlay%20Regsitration%20Assistance&amp;'>Assistance</a></p>"; return;  exit;

			}

		/*if(get_var("SELECT count(*) from wp_users where user_email='".$email."'")>0)
	
			
			{
			
				echo "<p><b>Error</b></p><p>Email-id already exists. Please select another</p>"; return; exit;
			}*/
				
				

		

		//else
			//{

				//update email  

				$sql="UPDATE wp_users SET user_email='".$email."' where user_email='".$o_email."'";
				$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
				$stmt->execute();

				$user_id=get_var("SELECT ID from wp_users where user_email='".$email."'");
				
				$user_name=get_var("SELECT user_login from wp_users where user_email='".$email."'");
				
				

				$sql1="UPDATE wp_usermeta SET meta_value='".$email."' where user_id=".$user_id." and meta_key='user_email' ";
				$stmt = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
				$stmt->execute();
				
				$subject_admin ='New User Changed Email and act resent-ShonarBangla';

				//resend activation link mail
				user_resend_activation_link($user_id, $email, $user_name,$subject_admin); 
				//re_send_activation_link($u_email, $user_login, $activation_link)
				//$info="Email Edited to ".$email." and Activation Link Resent";
				//fwrite($file,$info);
				echo "<p><b>Info</b></p><p>Email updated to $email and Activation Link has been sent to updated Email-id</p>"; return;

			
			//}
	exit;
		break;

	case "resendEmail":

				
				$email=$_REQUEST['user_email'];

					

				if(get_var("SELECT meta_value FROM wp_usermeta where meta_key='usersultra_account_status' and user_id=(SELECT ID from wp_users where user_email='".$email."')")=="active")
			{
				echo "<p><b>Error</b></p><p>Your Account Already Activated</p>"; return; exit;

			}
			else
			{				
				$user_id=get_var("SELECT ID from wp_users where user_email='".$email."'");
				
				$user_name=get_var("SELECT user_login from wp_users where user_email='".$email."'");
				
				
				$subject_admin ='New User(RS)-ShonarBangla';

				//resend activation link mail
				user_resend_activation_link($user_id, $email, $user_name,$subject_admin); 
				//re_send_activation_link($u_email, $user_login, $activation_link)
				//$info="Activation Link Email Resent"; 
				//fwrite($file,$info);
				echo "<p><b>Info</b></p><p>Activation Email has been sent to your Registered Email-id</p> "; return;
			}
			exit;
		break;


}

function user_resend_activation_link($user_id, $u_email, $user_login,$subject_admin) 
  {
	  		
		$web_url =SITE_URL."/activate/";	
		$unique_key=get_var("SELECT meta_value from wp_usermeta where user_id=".$user_id." and meta_key='xoouser_ultra_very_key'");  
		//$unique_key = get_user_meta($user_id, 'xoouser_ultra_very_key', true);
		$activation_link = $web_url."?act_link=".$unique_key;
		
		re_send_activation_link($u_email, $user_login, $activation_link,$user_id,$subject_admin);
		  
   }

function  re_send_activation_link($u_email, $user_login, $activation_link,$user_id,$subject_admin)
	{
		
		
		//$user_id=get_var("SELECT ID FROM wp_users where user_login='$user_login' or user_email='$u_email' ");
		$user_phone=get_var("SELECT meta_value FROM wp_usermeta where meta_key='phone' and user_id=$user_id ");

		$plan_id=get_var("SELECT membership_id from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");
		$coupon=get_var("SELECT code_id from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");
		$plan_ends=get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");


		//$plan_ends=new Date($plan_ends);
		$plan_ends=new DateTime($plan_ends);
		$plan_ends=$plan_ends->format("d-m-Y");

		//$plan_details=$wpdb->get_var("SELECT * from wp_pmpro_membership_levels where id=$plan_id");

		
		$plan_name=get_var("SELECT name from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_no=get_var("SELECT cycle_number from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_prd=get_var("SELECT cycle_period from wp_pmpro_membership_levels where id=$plan_id");

		$delay=$wpdb->get_var("SELECT delay from pmpro_dates_chk1 where user_id=$user_id order by id desc limit 1");
		
		if($coupon!=""){
			if($coupon[0]!="Q" and $coupon[0]!="0")
			$sub_stat.="With Promo Code: $coupon ,";
			elseif($coupon[0]=="Q")
			$sub_stat.="With Coupon Code: $coupon ,";
				}

		if($plan_id==4)
			$sub_stat.="You will have access to all our free selection and are awarded $delay-day(s) Free Trial of premium content.";
		else
			$sub_stat.="You will have access to all our free selection and are awarded a $plan_name plan of premium content.";
			//You are awarded $plan_name plan of $plan_cyc_no $plan_cyc_prd(s), valid till $plan_ends .


		
		
		if($user_phone=="")
			$user_fb_url=get_var("SELECT user_url FROM wp_users where ID=$user_id ");
		
		$admin_email ="siddish.gollapelli@ideabytes.com"; 
		$admin_email1 ="admin@qezyplay.com"; 		
		
		$subject ='Verify Your Account';
		
		
			
		//$template_admim = stripslashes($this->get_option('messaging_welcome_email_with_activation_admin')); <p> Password: {{userultra_pass}} </p>

			$template_client="<p>Hi,</p> <p>Thanks for registering with QezyPlay, where you can watch free and premium live TV channels for the family.  Your account needs activation for you to be able to watch the streams.</p><p> Please click on the link below to activate your account: {{user_ultra_activation_url}} </p><p>E-mail: {{userultra_user_email}} </p><p>Username: {{userultra_user_name}}</p><p>Note: $sub_stat Enjoy Viewing.</p><p>Shonar Bangla, a bouquet of live Bengali channels, is now available exclusively for your viewing from any device (mobile, computer or television).</p><p>You can change your password at {{userultra_change_password}} after Login.<br><p>Please feel free to share your experiences on Facebook and also introduce this method of watching TV, on the go, to all your friends and relatives.</p><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

			$template_admim="Hi Admin, <br> <p>New User Registered with Email change at qezyplay.com .</p><p>Account e-mail: {{userultra_user_email}} </p><p>Account username: {{userultra_user_name}}</p><p>Account phone:{{userultra_user_phone}} </p> <br /> <p>Regards,</p><p>QezyPlay</p>";

					
		$login_url =SITE_URL."/";
		$change_pwd_link=SITE_URL."/privacy-settings";
		
		$template_client = str_replace("{{user_ultra_activation_url}}", $activation_link,  $template_client);
		$template_client = str_replace("{{userultra_user_email}}", $u_email,  $template_client);
		$template_client = str_replace("{{userultra_user_name}}", $user_login,  $template_client);
		$template_client = str_replace("{{userultra_pass}}", $user_pass,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email1,  $template_client);
		$template_client = str_replace("{{userultra_change_password}}", $change_pwd_link,  $template_client);
		
		//admin
		$template_admim = str_replace("{{userultra_user_email}}", $u_email,  $template_admim);
		$template_admim = str_replace("{{userultra_user_name}}", $user_login,  $template_admim);
		$template_admim = str_replace("{{userultra_user_phone}}", $user_phone,  $template_admim);
		$template_admim = str_replace("{{userultra_fb_id}}", $user_fb_url,  $template_admim);
		//$template_admim = str_replace("{{userultra_admin_email}}", $admin_email,  $template_admim);	
				

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: QezyPlay <admin@qezyplay.com>' . "\r\n";
		
		mail($u_email, $subject, $template_client,$headers);		
		//send to admin		
		mail($admin_email, $subject_admin, $template_admim,$headers);
		
	}
?>

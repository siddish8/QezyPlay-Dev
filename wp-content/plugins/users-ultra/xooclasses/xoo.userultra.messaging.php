<?php
class XooMessaging extends XooUserUltraCommon 
{
	var $mHeader;
	var $mEmailPlainHTML;
	var $mHeaderSentFromName;
	var $mHeaderSentFromEmail;
	var $mCompanyName;
	

	function __construct() 
	{
		$this->setContentType();
		$this->setFromEmails();				
		$this->set_headers();	
		
	}
	
	function setFromEmails() 
	{
		global $xoouserultra;
			
		$from_name =  $this->get_option('messaging_send_from_name'); 
		$from_email = $this->get_option('messaging_send_from_email'); 	
		if ($from_email=="")
		{
			$from_email =get_option('admin_email');
			
		}		
		$this->mHeaderSentFromName=$from_name;
		$this->mHeaderSentFromEmail=$from_email;	
		
    }
	
	function setContentType() 
	{
			
		$type = 0; 			
		
		if($type==0)
		{
			$this->mEmailPlainHTML="text/plain";		
		}
		
		if($type==1)
		{
			$this->mEmailPlainHTML="text/html";		
		}
    }
	
	public function set_headers() {   			
		//Make Headers aminnistrators
		$header ="MIME-Version: 1.0\n"; 
		$header .= "Content-type: ".$this->mEmailPlainHTML."; charset=UTF-8\n"; 	
		$header .= "From: ".$this->mHeaderSentFromName." <".$this->mHeaderSentFromEmail.">\n";	
		$header .= "Organization: ".$this->mCompanyName." \n";
		$header .=" X-Mailer: PHP/". phpversion()."\n";		
		$this->mHeader = $header;		
    }
	
	
	public function  send ($to, $subject, $message)
	{
		global $xoouserultra;	
		
		$uultra_emailer = $xoouserultra->get_option('uultra_smtp_mailing_mailer');
		
		if($uultra_emailer=='mail' || $uultra_emailer=='' ) //use the defaul email function
		{	
			wp_mail( $to , $subject, $message, $this->mHeader);
		
		}else{ //third-party
		
			if (function_exists('uultra_third_party_email_sender') && $to !='') 
			{
				
				uultra_third_party_email_sender($to , $subject, $message);				
				
			}
		}
					
		
	}
	
	//--- Automatic Activation	
	public function  welcome_email($u_email, $user_login, $user_pass)
	{
		global $xoouserultra;
		global $wpdb;
		$user_id=$wpdb->get_var("SELECT ID FROM wp_users where user_login='$user_login' or user_email='$u_email' ");
		$user_phone=$wpdb->get_var("SELECT meta_value FROM wp_usermeta where meta_key='phone' and user_id=$user_id ");

		$user_pass="(As given by you at Registration)";
		
			$file=fopen("phone_mail_chk.txt","a");
			$info="\n";
			$info.="User Id:".$user_id;
			$info.="User Phone:".$user_phone;
			$info.="Date:".date("Y-m-d H:i:s");
			$info.="\n";
			fwrite($file,$info);
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		$admin_email1 ="admin@qezyplay.com"; 
		
		//get welcome email
		$template_client =stripslashes($this->get_option('messaging_welcome_email_client'));
		$template_admim = stripslashes($this->get_option('messaging_welcome_email_client_admin'));
		
		$login_url =site_url("/");
		$change_pwd_link=site_url("privacy-settings");
		
		$subject = __('Registration','xoousers');
		$subject_admin = __('New Registration','xoousers');

		$template_client="<p>Hi,</p> <p>Thanks for registering.</p><p>Your account e-mail: {{userultra_user_email}} </p><p>Your account username: {{userultra_user_name}}</p><p> Your account password: {{userultra_pass}} </p><p>Note: You are awarded 7-day of Free Trial. Enjoy Watching. </p><p>You can change your password at {{userultra_change_password}} after Login.<br><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

		$template_admim="Hi Admin, <br> <p>New User Registered.</p><p>Account e-mail: {{userultra_user_email}} </p><p>Account username: {{userultra_user_name}}</p><p>Account phone:{{userultra_user_phone}} </p> <br /> <p>Regards,</p><p>QezyPlay</p>";
		
		$template_client = str_replace("{{userl_ultra_login_url}}", $login_url,  $template_client);				
		$template_client = str_replace("{{userultra_user_email}}", $u_email,  $template_client);
		$template_client = str_replace("{{userultra_user_name}}", $user_login,  $template_client);
		$template_client = str_replace("{{userultra_pass}}", $user_pass,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email1,  $template_client);
		$template_client = str_replace("{{userultra_change_password}}", $change_pwd_link,  $template_client);
		
		//admin
		$template_admim = str_replace("{{userultra_user_email}}", $u_email,  $template_admim);
		$template_admim = str_replace("{{userultra_user_name}}", $user_login,  $template_admim);
		$template_admim = str_replace("{{userultra_user_phone}}", $user_phone,  $template_admim);		
		
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		//send to client
		$this->send($u_email, $subject, $template_client,$headers);		
		//send to admin		
		$this->send($admin_email, $subject_admin, $template_admim,$headers);
		
					
		
	}
	
	//--- Link Activation Resend	
	public function  re_send_activation_link($u_email, $user_login, $activation_link)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		
		
		$subject = __('Verify Your Account-Resend','xoousers');
		
		//get welcome email
		$template_client =stripslashes($this->get_option('messaging_re_send_activation_link'));
		
		$login_url =site_url("/");
		
		$template_client = str_replace("{{user_ultra_activation_url}}", $activation_link,  $template_client);
		$template_client = str_replace("{{userultra_user_email}}", $u_email,  $template_client);
		$template_client = str_replace("{{userultra_user_name}}", $user_login,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);		
		$this->send($u_email, $subject, $template_client);
		
	}
	
		//--- Admin Activation	
	public function  welcome_email_with_admin_activation($u_email, $user_login, $user_pass,  $activation_link)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		
		
		$subject = __('Account Verification','xoousers');
		$subject_admin = __('New Account To Approve','xoousers');
		
		//get welcome email
		$template_client =stripslashes($this->get_option('messaging_admin_moderation_user'));
		$template_admim = stripslashes($this->get_option('messaging_admin_moderation_admin'));
		
		$login_url =site_url("/");
		
		$template_client = str_replace("{{userultra_user_name}}", $user_login,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);
		
		//admin
		$template_admim = str_replace("{{userultra_user_email}}", $u_email,  $template_admim);
		$template_admim = str_replace("{{userultra_user_name}}", $user_login,  $template_admim);
		$template_admim = str_replace("{{userultra_admin_email}}", $admin_email,  $template_admim);				
		
		//send user
		$this->send($u_email, $subject, $template_client);
		
		//send to admin		
		$this->send($admin_email, $subject_admin, $template_admim);
		
	}
	
	
	//--- Link Activation	
	public function  welcome_email_with_activation($u_email, $user_login, $user_pass,  $activation_link)
	{
		global $xoouserultra;
		global $wpdb;
		$user_id=$wpdb->get_var("SELECT ID FROM wp_users where user_login='$user_login' or user_email='$u_email' ");
		$user_phone=$wpdb->get_var("SELECT meta_value FROM wp_usermeta where meta_key='phone' and user_id=$user_id ");

		$plan_id=$wpdb->get_var("SELECT membership_id from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");
		$coupon=$wpdb->get_var("SELECT code_id from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");
		$plan_ends=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");


		//$plan_ends=new Date($plan_ends);
		$plan_ends=new DateTime($plan_ends);
		$plan_ends=$plan_ends->format("d-m-Y");

		//$plan_details=$wpdb->get_var("SELECT * from wp_pmpro_membership_levels where id=$plan_id");

		
		$plan_name=$wpdb->get_var("SELECT name from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_no=$wpdb->get_var("SELECT cycle_number from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_prd=$wpdb->get_var("SELECT cycle_period from wp_pmpro_membership_levels where id=$plan_id");

		$delay=$wpdb->get_var("SELECT delay from pmpro_dates_chk1 where user_id=$user_id order by id desc limit 1");

		
		$sub_stat="";

		if($coupon!=""){
			if($coupon[0]!="Q" and $coupon[0]!="0")
			$sub_stat.="With Promo Code: $coupon ,";
			elseif($coupon[0]=="Q")
			$sub_stat.="With Coupon Code: $coupon ,";
				}

		if($plan_id==4)
			$sub_stat.="You are awarded $delay-day(s) of Free Trial.";
		else
			$sub_stat.="You are awarded $plan_name plan of $plan_cyc_no $plan_cyc_prd(s), valid till $plan_ends .";


		
		
		if($user_phone=="")
			$user_fb_url=$wpdb->get_var("SELECT user_url FROM wp_users where ID=$user_id ");
		
			$file=fopen("phone_mail_chk.txt","a");
			$info="\n";
			$info.="User Id:".$user_id;
			$info.="User Phone:".$user_phone;
			$info.="User FB:".$user_fb_url;
			$info.="Date:".date("Y-m-d H:i:s");
			$info.="\n";
			fwrite($file,$info);
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		$admin_email1 ="admin@qezyplay.com"; 		
		
		$subject = __('Verify Your Account','xoousers');
		$subject_admin = __('New User -ShonarBangla','xoousers');
		
		//get welcome email
		$template_client =stripslashes($this->get_option('messaging_welcome_email_with_activation_client'));
		$template_admim = stripslashes($this->get_option('messaging_welcome_email_with_activation_admin'));

			$template_client="<p>Hi,</p> <p>Thanks for registering. Your account needs activation.</p><p> Please click on the link below to activate your account: {{user_ultra_activation_url}} </p><p>E-mail: {{userultra_user_email}} </p><p>Username: {{userultra_user_name}}</p><p> Password: {{userultra_pass}} </p><p>Note: $sub_stat Enjoy Viewing.</p><p>You can change your password at {{userultra_change_password}} after Login.<br><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

			$template_admim="Hi Admin, <br> <p>New User Registered at qezyplay.com .</p><p>Account e-mail: {{userultra_user_email}} </p><p>Account username: {{userultra_user_name}}</p><p>Account phone:{{userultra_user_phone}} </p> <br /> <p>Regards,</p><p>QezyPlay</p>";

			if($user_phone=="" and $user_fb_url!=""){
				$subject = __('Account Verified-Activated through Facebook','xoousers');
				
				$template_client="<p>Hi,</p> <p>Thanks for registering. Your account activated through Facebook.</p><p>E-mail: {{userultra_user_email}} </p><p>Username: {{userultra_user_name}}</p><p> Password: {{userultra_pass}} </p><p>Note: You are awarded 7-day of Free Trial. Enjoy Watching.</p><p>You can change your password at {{userultra_change_password}} after Login.<br><p>If you have any problems, please contact us at {{userultra_admin_email}}.</p>  <p>Best Regards, </p><p>Admin - Qezyplay</p>";

			$template_admim="Hi Admin, <br> <p>New User Registered at qezyplay.com .</p><p>Account e-mail: {{userultra_user_email}} </p><p>Account username: {{userultra_user_name}}</p><p>Account Facebook id:{{userultra_fb_id}} </p> <br /> <p>Regards,</p><p>QezyPlay</p>";}
		
		$login_url =site_url("/");
		$change_pwd_link=site_url("privacy-settings");
		
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
		
		$this->send($u_email, $subject, $template_client,$headers);		
		//send to admin		
		$this->send($admin_email, $subject_admin, $template_admim,$headers);
		
		
		
	}
	
	//---  Activation	
	public function  confirm_activation($u_email, $user_login)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		
		$admin_email =get_option('admin_email'); 
		
		//get welcome email
		$template_client =stripslashes($this->get_option('admin_account_active_message_body'));
		
		$login_url =site_url("/");
		
		$subject = __('Account Activation','xoousers');		
		
		$template_client = str_replace("{{userl_ultra_login_url}}", $login_url,  $template_client);				
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);		
		
		$this->send($u_email, $subject, $template_client);					
		
	}
	
	//---  Verification Sucess	
	public function  confirm_verification_sucess($u_email)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');		
		
		$admin_email =get_option('admin_email'); 
		
		//get welcome email
		$template_client =stripslashes($this->get_option('account_verified_sucess_message_body'));
		
		$login_url =site_url("/");
		
		$subject = __('Account Verified','xoousers');	
		
		$template_client = str_replace("{{userl_ultra_login_url}}", $login_url,  $template_client);				
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);		
		
		$this->send($u_email, $subject, $template_client);
		
		
					
		
	}
	
	//---  Deny	
	public function  deny_activation($u_email, $user_login)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');		
		
		$admin_email =get_option('admin_email'); 
		
		//get welcome email
		$template_client =stripslashes($this->get_option('admin_account_deny_message_body'));
		
		$login_url =site_url("/");
		
		$subject = __('Account Activation Deny','xoousers');		
					
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);		
		
		$this->send($u_email, $subject, $template_client);
		
					
		
	}
	
	//--- Paid Activation	
	public function  welcome_email_paid($u_email, $user_login, $user_pass, $package)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		
		
		//get welcome email
		$template_client =stripslashes($this->get_option('messaging_welcome_email_client'));
		$template_admim = stripslashes($this->get_option('messaging_welcome_email_client_admin'));
		
		$login_url =site_url("/");
		
		$subject = __('Registration Information','xoousers');
		$subject_admin = __('New Paid Subscription','xoousers');
		
		$template_client = str_replace("{{userl_ultra_login_url}}", $login_url,  $template_client);				
		$template_client = str_replace("{{userultra_user_email}}", $u_email,  $template_client);
		$template_client = str_replace("{{userultra_user_name}}", $user_login,  $template_client);
		$template_client = str_replace("{{userultra_pass}}", $user_pass,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);
		
		//get users package 
		
		$user_package = $package->package_name;	
		
		$template_admim = str_replace("{{userultra_user_email}}", $u_email,  $template_admim);
		$template_admim = str_replace("{{userultra_user_name}}", $user_login,  $template_admim);
		$template_admim = str_replace("{{userultra_user_package}}", $user_package,  $template_admim);	
		
		$this->send($u_email, $subject, $template_client);
		
		//send admin email		
		$this->send($admin_email, $subject_admin, $template_admim);
		
					
		
	}
	
	//--- Email Activation	
	public function  welcome_email_link_activation($u_email, $user_login, $user_pass)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		
		//get welcome email
		$template_client =stripslashes($this->get_option('messaging_welcome_email_client'));
		$template_admim = stripslashes($this->get_option('messaging_welcome_email_client_admin'));
		
		$login_url =site_url("/");
		
		$subject = __('Registration ','xoousers');
		
		$template_client = str_replace("{{userl_ultra_login_url}}", $login_url,  $template_client);				
		$template_client = str_replace("{{userultra_user_email}}", $u_email,  $template_client);
		$template_client = str_replace("{{userultra_user_name}}", $user_login,  $template_client);
		$template_client = str_replace("{{userultra_pass}}", $user_pass,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);		
		
		$this->send($u_email, $subject, $template_client);
					
		
	}
	
	//--- Private Message to User	
	public function  send_private_message_user($receiver, $sender_nick, $uu_subject, $uu_message)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		
		$u_email = $receiver->user_email;
		
		$template_client =stripslashes($this->get_option('messaging_user_pm'));
		
		$blogname  = $this->get_option('messaging_send_from_name');
		
		$login_url =site_url("/");		
		
		
		$subject = __('New Private Message','xoousers');
		
		$template_client = str_replace("{{userultra_user_name}}", $sender_nick,  $template_client);
		$template_client = str_replace("{{user_ultra_blog_name}}", $blogname,  $template_client);
		$template_client = str_replace("{{userultra_pm_subject}}", $uu_subject,  $template_client);
		$template_client = str_replace("{{userultra_pm_message}}", stripslashes($uu_message),  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);		
		
		$this->send($u_email, $subject, $template_client);
					
		
	}

	//--- Send Friend Request	
	public function  send_friend_request($receiver, $sender)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		
		$u_email = $receiver->user_email;
		
		$template_client =stripslashes($this->get_option('message_friend_request'));		
		$blogname  = $this->get_option('messaging_send_from_name');
		
		$login_url =site_url("/");				
		
		$subject = __('New Friend Request','xoousers');
		
		$template_client = str_replace("{{user_ultra_blog_name}}", $blogname,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);			
		
		$this->send($u_email, $subject, $template_client);
					
		
	}
		//--- Reset Link	
	public function  send_reset_link($receiver, $link)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		
		$u_email = $receiver->user_email;
		
		//$template_client =stripslashes($this->get_option('reset_lik_message_body'));
		$template_client =$this->get_option('reset_lik_message_body');
		
		$blogname  = $this->get_option('messaging_send_from_name');
		
		
		$subject = __('Reset Your Password','xoousers');
		
		$template_client = str_replace("{{userultra_reset_link}}", $link,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);		
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		$this->send($u_email, $subject, print_r($template_client,true),$headers);	
		//$this->send($admin_email, $subject, print_r($template_client,true),$headers);	//admin copy for testing
				
		
	}
	
	//--- confirm password reset	
	public function  send_new_password_to_user($u_email, $user_login, $user_pass)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 
		
		
		$subject = __('Password Reset Confirmation','xoousers');
		
		//get welcome email
		$template_client =stripslashes($this->get_option('password_reset_confirmation'));
		
		$login_url =site_url("/");
		
		$template_client = str_replace("{{userl_ultra_login_url}}", $login_url,  $template_client);		
		$template_client = str_replace("{{userultra_user_name}}", $user_login,  $template_client);		
		$template_client = str_replace("{{userultra_user_email}}", $u_email,  $template_client);
		$template_client = str_replace("{{userultra_pass}}", $user_pass,  $template_client);
		$template_client = str_replace("{{userultra_admin_email}}", $admin_email,  $template_client);
		
		$this->send($u_email, $subject, $template_client);
		
		
	}
	
	public function  paypal_ipn_debug( $message)
	{
		global $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$admin_email =get_option('admin_email'); 		
		$this->send($admin_email, "IPN notification", $message);
					
		
	}
	
}
$key = "messaging";
$this->{$key} = new XooMessaging();

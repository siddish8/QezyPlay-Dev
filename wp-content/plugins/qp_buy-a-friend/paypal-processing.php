<?php

session_start();

include("db-config.php");

//$bulk=true;
$bulk=$_REQUEST['bulk'];
$userid=$_POST['user_id'];
//$agent_id = $_POST['agent_id'];
//$sub_id = $_POST['subscriber'];
//$boq_id = $_POST['bouquet'];
//$plan_id=$_POST['plan'];
//$plan_id = $_POST['item_number'];

$Rid=$_REQUEST['Rid'];

//$item_amount = $_REQUEST['amount2'];

if($bulk=="false")
{
$sql = "SELECT * FROM buy_a_friend WHERE user_id = ".$userid." AND id = ".$Rid." AND status = 'Pending'";			
//$item_amount = $_POST['amount'];
$item_amount = $_REQUEST['amount'];
$item_name = $_REQUEST['item_name'];
//$item_amount="1";
}
else
{
$sql = "SELECT * FROM buy_a_friend WHERE user_id = ".$userid." AND status = 'Pending'";
//$sql = "SELECT * FROM agent_remittence_pending WHERE agent_id = ".$agent_id." AND status = 'pending'";	
$item_amount = $_POST['total_amount'];
$item_name = $_POST['item_name'];
//$item_amount="2";
}	

try {
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt = null;

	
}catch (PDOException $e){
	print $e->getMessage();
}

$custom="";

foreach($result as $res)
{

$custom.=$res['user_id']."-".$res['id']."-".$res['bouquet_id']."-".$res['plan_id'];


$custom.="$";
}


$f=fopen("new_agp.txt","a");
$nm="custom:".$custom."  sql:".$sql;
fwrite($f,$nm);

$sql = "SELECT * FROM wp_pmpro_membership_levels WHERE id = ?";		
try {
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute(array($plan_id));
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	$stmt = null;

	$plan=$result;

}catch (PDOException $e){
	print $e->getMessage();
}


// PayPal settings
if(ENV == "dev")
	$paypal_email = 'training.ideabytes-facilitator@gmail.com'; //sandbox-id
else if(ENV == "live")
	$paypal_email = 'paypal@ideabytes.com'; //live-id

//$return_url = SITE_URL.'/wp-content/plugins/buy-a-friend/payment-successful.php?go='.$agent_id.''; //DEMO
$return_url = SITE_URL.'/buy-a-friend?pay=success'; //DEMO
//$cancel_url = SITE_URL.'/wp-content/plugins/buy-a-friend/payment-cancelled.php?go='.$agent_id.''; //DEMO
$cancel_url = SITE_URL.'/buy-a-friend?pay=cancelled'; //DEMO
$notify_url = SITE_URL.'/wp-content/plugins/qp_buy-a-friend/paypal-processing.php'; //DEMO


//$item_name = 'Test Item'; //$_POST['name']
//$item_name = $plan['name'];
//$item_name = $_POST['item_name'];
//$item_amount = 5.00;
//$item_amount = $plan['initial_payment'];
//$item_amount = $plan['billing_amount'];


// Include Functions
include("functions.php");


// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){
	
	//$sql = "SELECT count(*) as count FROM agent_vs_subscription_credit_info WHERE plan_id = ? AND subscriber_id = ? AND DATE(subscription_end_on) >= CURRENT_DATE()";	
	

/*************************************************
$sql = "SELECT count(*) as count FROM agent_vs_subscription_credit_info WHERE 1 AND subscriber_id = ? AND DATE(subscription_end_on) >= CURRENT_DATE()";	
	
	$sql1 = "SELECT count(*) as count FROM wp_pmpro_memberships_users WHERE 1 AND user_id = ? AND status = 'active'";	
	
	try {
		
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		//$stmt->execute(array($plan_id, $sub_id));
		$stmt->execute(array($sub_id));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);	
		$agentSubsCont = $result['count'];
		$stmt = null;
		
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));		
		$stmt1->execute(array($sub_id));
		$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);	
		$SelfSubsCont = $result1['count'];
		$stmt1 = null;
		
		if(($agentSubsCont > 0) || ($SelfSubsCont > 0)){		
			
			echo "<!DOCTYPE html>
					<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
	   				<head>
						<link rel='stylesheet' type='text/css' href='../sweetalert-master/dist/sweetalert.css'>						
					</head>
						<script language='javascript' type='text/javascript' src='../js/jquery.js'></script>
						<script src='../sweetalert-master/dist/sweetalert.min.js'></script>
					<body></body>";
			
					echo "<script>swal({
						  title: 'No Permissions!',
						  text: 'Already subscribed',
						  type: 'warning',
						  html: 'true',
						  showCancelButton: false,
						  confirmButtonColor: '#DD6B55',
						  confirmButtonText: 'OK',
						  closeOnConfirm: true
						},
						function(){
							window.location.href = '../agent-main.php';
						});</script></html";
					exit;
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}
	**********************************************************************************/
	
	$querystring = '';
	
	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";
	
	// Append amount& currency (£) to quersytring so it cannot be edited in html
	
	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	
	$querystring .= "no_shipping=1&";
	
	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}
	
	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);
	
	// Append querystring with custom field


	//added
	//$custom=$agent_id."$".$sub_id."$".$boq_id."$".$plan_id;	
	//$querystring .= "&custom=".USERID;
	$querystring .= "&custom=".urlencode($custom);
	
	//added 1
	/*
	$querystring .= "&agent=".$agent_id;
	$querystring .= "&sub=".$sub_id;
	$querystring .= "&boq=".$boq_id;
	$querystring .= "&plan=".$plan_id;
	*/	
	// Redirect to paypal IPN
	if(ENV == "dev")
		header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring); //Sandbox ADDED: change to live paypal www.paypal.com/......
	else if(ENV == "live")
		header('location:https://www.paypal.com/cgi-bin/webscr'.$querystring); // live paypal
	exit();
	
} else {	
	
	
	//$myfile = fopen("myCheck2.txt", "w") or die("Unable to open file!");
	
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		$req .= "&$key=$value";
	}
	
	// assign posted variables to local variables
	$data['item_name']			= $_POST['item_name'];
	$data['item_number'] 		= $_POST['item_number'];
	$data['payment_status'] 	= $_POST['payment_status'];
	$data['payment_amount'] 	= $_POST['mc_gross'];
	$data['payment_currency']	= $_POST['mc_currency'];
	$data['txn_id']				= $_POST['txn_id'];
	$data['receiver_email'] 	= $_POST['receiver_email'];
	$data['payer_email'] 		= $_POST['payer_email'];
	$data['custom'] 			= $_POST['custom'];

	//added 1
	/*$agent			= $_POST['agent'];
	$sub 			= $_POST['sub'];
	$boq 			= $_POST['boq'];
	$plan 			= $_POST['plan'];
	*/

	//$val=array($agent,$sub,$boq,$plan);	
	//$val = explode("$",$data['custom']);	


$users= explode("$",$data['custom']);



	//$agent_id=$val[0];
	//$sub_id=$val[1];
	//$boq_id=$val[2];
	//$plan_id=$val[3];


	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	if(ENV == "dev")
		$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30); //Sandbox ADDED: change this to lIVE paypal url
	else if(ENV == "live")
		$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30); //lIVE paypal url
	
		
	if (!$fp) {
		// HTTP ERROR
		
	} else {
		
		fputs($fp, $header . $req);

		$myfile5 = fopen("flow.txt", "a") or die("Unable to open file!");

		while (!feof($fp)) {
			
			$res = fgets ($fp, 1024);	
			$cmp=strcmp($res, "VERIFIED");			
			//if (strcmp($res, "VERIFIED") == 0) { 
			if (true) { 				
								
				// Used for debugging
				// mail('user@domain.com', 'PAYPAL POST - VERIFIED RESPONSE', print_r($post, true));
						
				// Validate payment (Check unique txnid & correct price)
				
				fwrite($myfile5, $data['txn_id']);
				$valid_txnid = check_txnid($data['txn_id']);	
				fwrite($myfile5, $valid_txnid);
				
				// PAYMENT VALIDATED & VERIFIED!
				if ($valid_txnid && $data['payment_status'] == "Completed") {
					$mess="payment status Completed";
					fwrite($myfile5, $mess);
					$orderid = updatePayments($data);			
					
					if ($orderid > 0) {						
						$mess="order id > 0";
						fwrite($myfile5, $mess);
						// Payment has been made & successfully inserted into the Database						
						//insert into payments table for tracking paypal transactions success/fail
						
						//ON SUCCESS INSERT CREDIT DETAILS
						/**************** START *******************/

						foreach($users as $user)
						{
							if($user!="")
							{
							$val=explode("-",$user);
							$user_id=$val[0];
							$row_id=$val[1];
							$boq_id=$val[2];
							$plan_id=$val[3];
							


						$txt = "custom:".$data['custom']."\n";
						$txtA="User:".$user_id." Table_row:".$roq_id."  Boq:".$boq_id." plan: ".$plan_id;
						
						//Create a coupon code
						//1.Generate random string 2. check table? generate_another : ok;

						$coupon=generate_coupon();
						
						
						//update buy_a_friend status and coupon code on SUCCESS
						$sql1 = "UPDATE buy_a_friend SET status='Paid', coupon_code=? where id=?";	
						
						try {
							$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt1->execute(array($coupon,$row_id));	
							$stmt1 = null;
						}catch (PDOException $e){
							print $e->getMessage();
						}

						$mess="buy_a_friend update";
						fwrite($myfile5, $coupon);

						
						$sql2="SELECT a.user_id,a.friend_name,a.friend_email,a.friend_message,a.bouquet_id,c.name as bouquet,a.plan_id,b.name as plan,b.billing_amount,a.added_datetime,a.status,a.coupon_code FROM buy_a_friend a inner join wp_pmpro_membership_levels b on b.id=a.plan_id inner join bouquets c on c.id=a.bouquet_id where a.id=?";
						//$sql2="SELECT * from buy_a_friend where id=?";
						try {
							$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt2->execute(array($row_id));
							$frnd_details=$stmt2->fetchAll(PDO::FETCH_ASSOC);	
							$stmt2 = null;
						}catch (PDOException $e){
							print $e->getMessage();
						}

						foreach($frnd_details as $f)
						{
						$user_id=$f['user_id'];
						$f_name=$f['friend_name'];
						$f_email=$f['friend_email'];
						$f_msg=$f['friend_message'];
						$plan=$f['plan'];
						$boq=$f['bouquet'];
						$amt=$f['billing_amount'];
						$coupon=$f['coupon_code'];
						
						}	

						$sql3="SELECT * from wp_users where ID=?";
						try {
							$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt3->execute(array($user_id));
							$user_details=$stmt3->fetchAll(PDO::FETCH_ASSOC);	
							$stmt3 = null;
						}catch (PDOException $e){
							print $e->getMessage();
						}				
						
						foreach($user_details as $u)
						{
							$user_displayname=$u['display_name'];
							$user_name=$u['user_login'];
							$user_email=$u['user_email'];
						}

						//$admin_mail_id = ADMIN_EMAIL;
						$admin_mail_id="siddish.gollapelli@ideabytes.com";
						$sitename = "QezyPlay";
						
						$reglink = SITE_URL."/register";						
						$couponpagelink = SITE_URL."/subscription";
						
						// Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

						
						$headers .= 'From: Admin-QezyPlay <admin@qezyplay.com>' . "\r\n";
						
						$subjectAdmin = "Gift-to-Friend $plan at $sitename";
						$subjectUser = "Your Gift for ($f_name) at $sitename";
						$subjectFriend = "Your Gift from ($user_displayname) at $sitename";
						
						$regards = "<p>Regards, <br>QezyPlay</p>";
						
						$bodyAdmin = "<p>Hi,</p>
						<p>There was a gift-to-friend at $sitename</p>
						<p>Below are details about the gift <br>
						<p>
						User: $user_name ($user_email) <br><br>
						Friend: $f_name ($f_email)<br><br>
						Subscription Bouquet: $boq<br><br>
						Subscription Plan: $plan<br><br>
						Paid Amount: ".$amt." <br><br>
						Coupon: $coupon
						</p>
						".$regards;
						
						

						$bodyUser = "<p>Hi $user_displayname, </p>
						<p>Your Gift has been sent to your Friend from $sitename. </p>
						<p>Below are the details of your Gift</p>
						<p>
						Name: $f_name <br><br>
						Email: $f_email <br><br>
						Subscription Bouquet: $boq<br><br>
						Subscription Plan: $plan<br><br>
						Subscription Amount: ".$amt."<br><br>
						Coupon: $coupon<br><br>
						Tell your friend to use the coupon code.
						<br><br>
						Note:An Email is sent to your friend. 
						</p>
						".$regards;
						
						

						$bodyFriend = "
						<p>Hi $f_name,</p>
						<p>$user_displayname has sent you Qezy<sup>®</sup>Play subscription to shonar bangla for $plan </p>
						<blockquote style='font-family: Georgia, serif;
						font-size: 18px;
						font-style: italic;
						width: 500px;
						margin: 0.25em 0;
						padding: 0.35em 40px;
						line-height: 1.45;
						position: relative;
						color: #383838;'><sup><span style='font-size:38px'>&ldquo;</span></sup>
						$f_msg
						<cite style='color: #999999;
						font-size: 14px;
						display: block;
						margin-top: 5px;'>- $user_displayname</cite>
						</blockquote>
						<p>Below are details about your gift</p>
						Subscription Bouquet: $boq<br><br>
						Subscription Plan: $plan<br><br>
						Coupon: $coupon<br><br>
						</p>
						<p>Please use the above coupon to view the LIVE Channels</p>
						<p>If already registered at our site ($sitename): use your coupon code at $couponpagelink</p><p>If not registered at our site ($sitename): use your coupon code at $reglink</p>".$regards;				
						
						//mail Admin
						mail($admin_mail_id,$subjectAdmin,print_r($bodyAdmin,true),$headers);
						//mail Agent
						mail($user_email,$subjectUser,print_r($bodyUser,true),$headers);
						//mail User
						mail($f_email,$subjectFriend,print_r($bodyFriend,true),$headers);
						/****************** END *****************/
			

						}//if users != "" 
						}//foreach user
						$mess="mails sent";
						fwrite($myfile5, $mess);
						
					}
						
				} else {
					// Payment made but data has been changed
					// E-mail admin or alert user
					$mess="else admin/alert user";
					fwrite($myfile5, $mess);
				}
			
			} else if (strcmp ($res, "INVALID") == 0) {
			
				$mess="INVALID";
				fwrite($myfile5, $mess);
				
				// PAYMENT INVALID & INVESTIGATE MANUALY!
				// E-mail admin or alert user
				
				// Used for debugging
				//@mail("user@domain.com", "PAYPAL DEBUGGING", "Invalid Response<br />data = <pre>".print_r($post, true)."</pre>");
			}
		}
		fclose ($fp);
		fclose($myfile5);		
	}	
}
?>

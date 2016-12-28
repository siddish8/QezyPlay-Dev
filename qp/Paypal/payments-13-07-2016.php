<?php

session_start();

include("../agent-config.php");


$agent_id = $_POST['agent_id'];
$sub_id = $_POST['subscriber'];
$boq_id = $_POST['bouquet'];
//$plan_id=$_POST['plan'];
$plan_id = $_POST['item_number'];

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

$return_url = SITE_URL.'/qp/Paypal/payment-successful.php?go='.$agent_id.''; //DEMO
$cancel_url = SITE_URL.'/qp/Paypal/payment-cancelled.php?go='.$agent_id.''; //DEMO
$notify_url = SITE_URL.'/qp/Paypal/payments.php'; //DEMO


//$item_name = 'Test Item'; //$_POST['name']
$item_name = $plan['name'];
//$item_amount = 5.00;
//$item_amount = $plan['initial_payment'];
$item_amount = $plan['billing_amount'];

// Include Functions
include("functions.php");


// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){
	
	$sql = "SELECT count(*) as count FROM agent_vs_subscription_credit_info WHERE plan_id = ? AND subscriber_id = ? AND DATE(subscription_end_on) >= CURRENT_DATE()";	
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($plan_id, $sub_id));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$stmt = null;
		
		if($result['count'] > 0){		
			
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
						  text: 'Already subscribed for same plan',
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
	$custom=$agent_id."$".$sub_id."$".$boq_id."$".$plan_id;	
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
	
	
	//$myfile = fopen("myCheck1.txt", "w") or die("Unable to open file!");
	
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
	$val=explode("$",$data['custom']);	

	$agent_id=$val[0];
	$sub_id=$val[1];
	$boq_id=$val[2];
	$plan_id=$val[3];


	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	if(ENV == "dev")
		$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30); //Sandbox ADDED: change this to lIVE paypal url
	else if(ENV == "live")
		$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30); //lIVE paypal url
		
	$created_datetime = gmdate("Y-m-d H:i:s");
	//insert into payments table for tracking paypal transactions success/fail
	$sql = "INSERT INTO payments(txnid,payment_amount,payment_status,itemid,createdtime) VALUES(?,?,?,?,?)";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($data['txn_id'],$data['payment_amount'],$data['payment_status'],$data['item_number'],$created_datetime));	
		$stmt = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}

	//ON SUCCESS INSERT CREDIT DETAILS
	/**************** START *******************/

	

	$txt = "custom:".$data['custom']."\n";
	//fwrite($myfile, $txt);
	//$created_datetime=gmdate("Y-m-d H:i:s");
	$created_datetime=new DateTime("now");
	$startdate1=$created_datetime;
	$created_datetime1=$created_datetime->format('Y-m-d H:i:s');
	$sql0 = "SELECT subscriber_id FROM agent_vs_subscription_credit_info";		
	try {
		$stmt0 = $dbcon->prepare($sql0, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt0->execute();
		$result0 = $stmt0->fetchall(PDO::FETCH_ASSOC);
		$stmt0 = null;
		foreach($result0 as $user){
			if($user["subscriber_id"] == $sub_id)
			$count=1;
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}


	$txt.= $data['payment_status']."DB:"."agent_id:".$agent_id."sub:".$sub_id."boq:".$boq_id."plan:".$plan_id;
	//fwrite($myfile, $txt);



	$sql0 = "SELECT user_id FROM wp_pmpro_memberships_users";		
	try {
		$stmt0 = $dbcon->prepare($sql0, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt0->execute();
		$result0 = $stmt0->fetchall(PDO::FETCH_ASSOC);
		$stmt0 = null;
		foreach($result0 as $user){
			if($user["user_id"] == $sub_id)
				$count1=1;
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}
				
	if($count==1 or $count1==1){		
		$startdate1=$created_datetime;
	}else {

		if($plan_id != 4){
			$startdate1->add(new DateInterval('P30D'));
		}
	}	

	$startdate=$startdate1->format('Y-m-d H:i:s');

	$txt.= $data['payment_amount']."DB:"."paid:".$created_datetime1."start:".$startdate."end:".$enddate;
	$enddate=new DateTime($startdate);

	$sql2 = "select name,cycle_number,cycle_period,expiration_number,expiration_period from wp_pmpro_membership_levels where id=".$plan_id;		
	try {
		$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt2->execute();
		$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		$stmt2 = null;
		foreach($result2 as $plan){
			$plan_period_no=$plan['cycle_number'];
			$plan_period=$plan['cycle_period'];
			$trail_period=$plan['expiration_period'];
			$trail_period_no=$plan['expiration_number'];
			$plan_name=$plan['name'];
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}
            			 
	$txt.="planperiod:".$plan_period."; plan period no:".$plan_period_no."sql2:".$sql2;
	//fwrite($myfile, $txt);
	//$plan_period_no=$wpdb->get_var("select cycle_number from wp_pmpro_membership_levels where id=".$plan_id."  ");
	//$plan_period=$wpdb->get_var("select cycle_period from wp_pmpro_membership_levels where id=".$plan_id."  ");

	if($plan_id == 4){

		if( ($trail_period_no != "") && ($trail_period != "")){
			$enddate=$enddate->add(new DateInterval('P'.$trail_period_no.$trail_period[0]));
		}

	}else{
		if( ($plan_period_no != "") && ($plan_period != "")){
			$enddate=$enddate->add(new DateInterval('P'.$plan_period_no.$plan_period[0]));
		}
	}

	/*if($plan_id==1)
	{
	$enddate->add(new DateInterval('P3M'));
	}elseif($plan_id==2)
	{
	$enddate->add(new DateInterval('P6M'));
	}elseif($plan_id==3)
	{
	$enddate->add(new DateInterval('P1Y'));
	}elseif($plan_id==5)
	{$enddate=$enddate->add(new DateInterval('P1D'));}
	else{$enddate="";}*/


	$enddate=$enddate->format('Y-m-d H:i:s');
						

	//fclose($myfile);

	//insert into agent_vs_subs ONLY on SUCCESS
	$sql1 = "INSERT INTO agent_vs_subscription_credit_info(agent_id,subscriber_id,bouquet_id,plan_id,amount,subscription_start_from,subscription_end_on,credited_datetime) VALUES(?,?,?,?,?,?,?,?)";		
	try {
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute(array($agent_id,$sub_id,$boq_id,$plan_id,$data['payment_amount'],$startdate,$enddate,$created_datetime1));	
		$stmt1 = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}


//added on 13-07-2016
$user_id=$sub_id;
$txtA="user:".$user_id;

		$sqlUsersOld = "select * from wp_pmpro_memberships_users where user_id=".$user_id." order by id desc limit 1";		
	try {
		$stmtUsersOld = $dbcon->prepare($sqlUsersOld, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmtUsersOld->execute();
		$resultUsersOld = $stmtUsersOld->fetchAll(PDO::FETCH_ASSOC);
		$stmtUsersOld = null;
		foreach($resultUsersOld as $usersOld){
			$old_planid_user=$usersOld['membership_id'];
			$old_startdate_user=$usersOld['startdate'];
					}

	}catch (PDOException $e){
		print $e->getMessage();
	}		

	$txtA.="oldplan id user:".$old_planid_user;
	$txtA.="oldstartdate user:".$old_startdate_user;


	$sqlAgentUsersOld = "select * from agent_vs_subscription_credit_info where subscriber_id=".$user_id." order by id desc limit 1,1";		
	try {
		$stmtAgentUsersOld = $dbcon->prepare($sqlAgentUsersOld, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmtAgentUsersOld->execute();
		$resultAgentUsersOld = $stmtAgentUsersOld->fetchAll(PDO::FETCH_ASSOC);
		$stmtAgentUsersOld = null;
		foreach($resultAgentUsersOld as $AgentusersOld){
			$old_planid_Agentuser=$AgentusersOld['plan_id'];
			$old_startdate_Agentuser=$AgentusersOld['credited_datetime'];
					}

	}catch (PDOException $e){
		print $e->getMessage();
	}		

	$txtA.="oldplan id Agentuser:".$old_planid_Agentuser;
	$txtA.="oldstartdate Agentuser:".$old_startdate_Agentuser;

	if($old_startdate_user!="")
		{

		if( (new DateTime($old_startdate_user)) > (new DateTime($old_startdate_Agentuser)) )
			{$old_planid=$old_planid_user;}
		else	
			{$old_planid=$old_planid_Agentuser;}
		}
	else
		{
		
		if($old_startdate_Agentuser!="")
			{$old_planid=$old_planid_Agentuser;}
					
		}
//$old_planid="";//get it from ppmro_users or agent_vs_sub

	

$sqla = "SELECT name from wp_pmpro_membership_levels where id=".$old_planid."";		
	try {
		$stmta = $dbcon->prepare($sqla, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmta->execute();
		$resulta = $stmta->fetch(PDO::FETCH_ASSOC);
		$stmta = null;
		foreach($resulta as $plan){
			$old_planname=$plan['name'];
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}

	$txtA.="oldplan id final:".$old_planid;
	$txtA.="oldplan name final:".$old_planname;

$planid=$boq_id;
$planname=$plan_name;
$startdate1=$created_datetime1;
$plan_startdate=new DateTime($startdate);
$plan_startdate=$plan_startdate->format("Y-m-d");
$next_paydate=$enddate;


		$txtA.="planid:".$planid;
		$txtA.="planname:".$planname;
		$txtA.="startdate:".$startdate1;
		$txtA.="plan_startdate:".$plan_startdate;
		$txtA.="next_paydate:".$next_paydate;
		


				$today=new DateTime("now");
				$today=$today->format("Y-m-d");
				$today=new DateTime($today);

				if( (new DateTime($plan_startdate)) > $today )
				{
					$delayeddate=new DateTime($plan_startdate);
				}
				else
				{
					$delayeddate=new DateTime($next_paydate);
				}
				
				$temp = date_diff($today,$delayeddate);
				$delayUpd=$temp->format('%R%a');

				if($delayUpd < 0)
 					{$delayUpd=0;}
				else
				{$delayUpd=$temp->format('%a');}


$agent="agent-id:".$agent_id;

		$txtA.="delayNew:".$delayUpd;
		$txtA.="agent:".$agent;

//insert in pmpro_dates_chk1
$sqlA = "INSERT INTO pmpro_dates_chk1(user_id,old_plan_id,old_plan_name,plan_id,plan_name,startdate,plan_startdate,next_paydate,delay,agent) VALUES(?,?,?,?,?,?,?,?,?,?)";	
try {
		$stmtA = $dbcon->prepare($sqlA, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmtA->execute(array($user_id,$old_planid,$old_planname,$planid,$planname,$startdate1,$plan_startdate,$enddate,$delayUpd,$agent));	
		$stmtA = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}	

$sqlD = "UPDATE wp_options SET option_value=".$delayUpd." where option_name=pmpro_sub_support_delay_".$user_id."";	
try {
		$stmtD = $dbcon->prepare($sqlD, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmtD->execute();	
		$stmtD = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}	


$sqld = "SELECT option_value from wp_options where option_name=pmpro_sub_support_delay_".$user_id."";		
	try {
		$stmtd = $dbcon->prepare($sqld, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmtd->execute();
		$resultd = $stmtd->fetch(PDO::FETCH_ASSOC);
		$stmtd = null;
		foreach($resultd as $delay){
			$delay_db=$delay['option_value'];
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}

	$txtA.="delay from db:".$delay_db;

$file = fopen("testAgent.txt","w");
echo fwrite($file,$txtA);
fclose($file);
	mail("siddish.gollapelli@ideabytes.com","Agent_vs_Sub",print_r($txt,true));

	/****************** END *****************/


	if (!$fp) {
		// HTTP ERROR
		
	} else {
		fputs($fp, $header . $req);

		while (!feof($fp)) {

			$res = fgets ($fp, 1024);
			if (strcmp($res, "VERIFIED") == 0) {

								
				// Used for debugging
				// mail('user@domain.com', 'PAYPAL POST - VERIFIED RESPONSE', print_r($post, true));
						
				// Validate payment (Check unique txnid & correct price)
				$valid_txnid = check_txnid($data['txn_id']);
				$valid_price = check_price($data['payment_amount'], $data['item_number']);
				// PAYMENT VALIDATED & VERIFIED!
				if ($valid_txnid && $valid_price) {

					$orderid = updatePayments($data);
					
					if ($orderid) {
						// Payment has been made & successfully inserted into the Database

						
					} else {
						// Error inserting into DB
						// E-mail admin or alert user
						// mail('user@domain.com', 'PAYPAL POST - INSERT INTO DB WENT WRONG', print_r($data, true));
					}
				} else {
					// Payment made but data has been changed
					// E-mail admin or alert user
				}
			
			} else if (strcmp ($res, "INVALID") == 0) {
			
				// PAYMENT INVALID & INVESTIGATE MANUALY!
				// E-mail admin or alert user
				
				// Used for debugging
				//@mail("user@domain.com", "PAYPAL DEBUGGING", "Invalid Response<br />data = <pre>".print_r($post, true)."</pre>");
			}
		}
	fclose ($fp);
		
	}
}
?>

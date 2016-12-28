<?php

session_start();

include("../db-config.php");

//$bulk=true;
$bulk=$_REQUEST['bulk'];
$userid=$_POST['user_id'];
$agent_id = $_POST['agent_id'];
//$sub_id = $_POST['subscriber'];
//$boq_id = $_POST['bouquet'];
//$plan_id=$_POST['plan'];
//$plan_id = $_POST['item_number'];

$Rid=$_REQUEST['Rid'];

//$item_amount = $_REQUEST['amount2'];

if($bulk=="false")
{
$sql = "SELECT * FROM agent_remittence_pending WHERE agent_id = ".$agent_id." AND id = ".$Rid." AND status = 'pending'";			
//$item_amount = $_POST['amount'];
$item_amount = $_REQUEST['amount'];
$item_name = $_REQUEST['item_name'];
//$item_amount="1";
}
else
{
$sql = "SELECT * FROM agent_remittence_pending WHERE agent_id = ".$agent_id." AND status = 'pending'";	
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

$custom.=$res['agent_id']."-".$res['subscriber_id']."-".$res['bouquet_id']."-".$res['plan_id'];


$custom.="$";
}


$f=fopen("new_agp.txt","a");
$nm="custom:".$custom."  sql:".$sql;
fwrite($f,$nm);
//exit;
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
							$agent_id=$val[0];
							$sub_id=$val[1];
							$boq_id=$val[2];
							$plan_id=$val[3];
							


						$txt = "custom:".$data['custom']."\n";
						$txtA="Ag:".$agent_id." User:".$sub_id."  Boq:".$boq_id." plan: ".$plan_id;
						//fwrite($myfile, $txt);
						//$created_datetime=gmdate("Y-m-d H:i:s");
						$created_datetime = new DateTime("now");
						$startdate1 = $created_datetime;
						$created_datetime1 = $created_datetime->format('Y-m-d H:i:s');
						//$sql0 = "SELECT subscriber_id FROM agent_vs_subscription_credit_info";//uncomment and remove dbl_chk		
						$sql0="select count(subscriber_id) as countA from agent_vs_subscription_credit_info where subscriber_id=".$sub_id." ";
						try {
							$stmt0 = $dbcon->prepare($sql0, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt0->execute();
							$result0 = $stmt0->fetch(PDO::FETCH_ASSOC);
							$stmt0 = null;							
							$countA = $result0['countA'];

						}catch (PDOException $e){
							print $e->getMessage();
						}

						$txtA.="countA:".$countA;
						$txt.= $data['payment_status']."DB:"."agent_id:".$agent_id."sub:".$sub_id."boq:".$boq_id."plan:".$plan_id;
						//fwrite($myfile, $txt);

						//$sql0 = "SELECT user_id FROM wp_pmpro_memberships_users"; //uncomment
						$sql0="select count(user_id) as countU from wp_pmpro_memberships_users where user_id=".$sub_id." ";
						
						try {
							$stmt0 = $dbcon->prepare($sql0, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt0->execute();
							$result0 = $stmt0->fetch(PDO::FETCH_ASSOC);
							$stmt0 = null;							
							$countU=$result0['countU'];
							
						}catch (PDOException $e){
							print $e->getMessage();
						}

						$txtA.="countU:".$countU;

						$countTotal = $countU + $countA;
						$txtA.="countTotal:".$countTotal;
						
						//if($count==1 or $count1==1){		
							//$startdate1=$created_datetime;
						//}else {

							//if($plan_id != 4){
							//	$startdate1=$startdate1->add(new DateInterval('P30D'));
							//	$delay=30;
							//}else {$delay=0;}
						//}	

						//dbl_chk
						if($countTotal < 1){
							
							$sqldi = "SELECT option_value FROM wp_options WHERE `option_name` = 'pmpro_subscription_delay_".$plan_id."'";		
							try {
								$stmtdi = $dbcon->prepare($sqldi, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
								$stmtdi->execute();
								$resultdi = $stmtdi->fetchAll(PDO::FETCH_ASSOC);
								$stmtdi = null;
								foreach($resultdi as $delay){
									$delayi = $delay['option_value'];
								}
							}catch (PDOException $e){
								print $e->getMessage();
							}
						
							if($delayi=="")
								$free = "free";
							
							$txtA.="new user-".$free;
							
						}else{
							
							$sqldi = "SELECT option_value FROM wp_options WHERE `option_name` = 'pmpro_sub_support_delay_".$user_id."'";		
						
							try {
								$stmtdi = $dbcon->prepare($sqldi, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
								$stmtdi->execute();
								$resultdi = $stmtdi->fetchAll(PDO::FETCH_ASSOC);
								$stmtdi = null;
								foreach($resultdi as $delay){
									$delayi = $delay['option_value'];
								}

							}catch (PDOException $e){
								print $e->getMessage();
							}
							$txtA.="not new user";
						}
						
						$txtA.="delayInitial:".$delayi;
						//if delay is null pass 0
						
						if($delayi=="")
							$delayi=0; 

						$txtA.="delayInitial:".$delayi;

						//$startdate=$startdate1->format('Y-m-d H:i:s');
						$startdate2 = $created_datetime->format('Y-m-d');
						$startdate = date('Y-m-d', strtotime($startdate2. ' +'.(int)$delayi.' days'));
						//$startdate = $startdate->format('Y-m-d');

						$txtA.="plan_startdate:".$startdate;	
						$txt.= $data['payment_amount']."DB:"."paid:".$created_datetime1."start:".$startdate."end:".$enddate;
						
						$enddate = new DateTime($startdate);

						$sql2 = "select name,cycle_number,cycle_period,expiration_number,expiration_period,billing_amount from wp_pmpro_membership_levels where id=".$plan_id;		
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
								$plan_amount=$plan['billing_amount'];
							}

						}catch (PDOException $e){
							print $e->getMessage();
						}

						$txt.="planperiod:".$plan_period."; plan period no:".$plan_period_no."; plan_amount:".$plan_amount."; sql2:".$sql2;
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


						$enddate=$enddate->format('Y-m-d');
						$d=1;
					$enddate_1=date('Y-m-d', strtotime($enddate. ' -'.(int)$d.' Days')); //added on 19-07-2016
						//$enddate_1=new DateTime($enddate;	

						//insert into agent_vs_subs ONLY on SUCCESS
						$sql1 = "INSERT INTO agent_vs_subscription_credit_info(agent_id,subscriber_id,bouquet_id,plan_id,amount,subscription_start_from,subscription_end_on,credited_datetime) VALUES(?,?,?,?,?,?,?,?)";		
						try {
							$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt1->execute(array($agent_id,$sub_id,$boq_id,$plan_id,$plan_amount,$startdate,$enddate_1,$created_datetime1));	
							$stmt1 = null;
						}catch (PDOException $e){
							print $e->getMessage();
						}

						$mess="agent_vs_sub insertion";
						fwrite($myfile5, $mess);

						//added on 13-07-2016
						$user_id=$sub_id;
						$txtA.="user:".$user_id;

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

						if($old_startdate_user != ""){
							if( (new DateTime($old_startdate_user)) > (new DateTime($old_startdate_Agentuser)) ){
								$old_planid=$old_planid_user;
							}else{
								$old_planid=$old_planid_Agentuser;
							}
						}else{		
							if($old_startdate_Agentuser!=""){
								$old_planid=$old_planid_Agentuser;
							}					
						}
						//$old_planid="";//get it from ppmro_users or agent_vs_sub	

						$sqla = "SELECT name from wp_pmpro_membership_levels where id = ".$old_planid."";		
						try {							
							$stmta = $dbcon->prepare($sqla, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmta->execute();
							$resulta = $stmta->fetch(PDO::FETCH_ASSOC);
							$stmta = null;
							$old_planname = $resulta['name'];

						}catch (PDOException $e){
							print $e->getMessage();
						}


						if($old_planid==""){
							$old_planname="---";						
						}
						$txtA.="oldplan id final:".$old_planid;
						$txtA.="oldplan name final:".$old_planname;

						$planid=$boq_id;
						$planname=$plan_name;
						$startdate1=$created_datetime1;
						$plan_startdate=new DateTime($startdate);
						$plan_startdate=$plan_startdate->format("Y-m-d");
						$next_paydate=(new DateTime($enddate))->format("Y-m-d");


						$txtA.="planid:".$planid;
						$txtA.="planname:".$planname;
						$txtA.="startdate:".$startdate1;
						$txtA.="plan_startdate:".$plan_startdate;
						$txtA.="next_paydate:".$next_paydate;



						$today=new DateTime("now");
						$today=$today->format("Y-m-d");
						$today=new DateTime($today);

						//changed because Agent already receives payment for full duration						
						//if( (new DateTime($plan_startdate)) > $today ){
							//$delayeddate=new DateTime($plan_startdate);
						//}else{
							$delayeddate=new DateTime($next_paydate);
						//}

						$temp = date_diff($today,$delayeddate);
						$delayUpd=$temp->format('%R%a');

						if($delayUpd < 0){
							$delayUpd=0;
						}else{
							$delayUpd=$temp->format('%a');
						}

						$agent = "agent-id:".$agent_id;	
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


						$mess="pmpro dates chk1 insertion";
						fwrite($myfile5, $mess);

						$sqlD = "UPDATE wp_options SET option_value=".$delayUpd." where option_name='pmpro_sub_support_delay_".$user_id."'";	
						try {
							$stmtD = $dbcon->prepare($sqlD, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmtD->execute();	
							$stmtD = null;
						}catch (PDOException $e){
							print $e->getMessage();
						}	


						$mess="updated delay info";
						fwrite($myfile5, $mess);

						$sqld = "SELECT option_value from wp_options where option_name = 'pmpro_sub_support_delay_".$user_id."'";		
						try {
							$stmtd = $dbcon->prepare($sqld, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmtd->execute();
							$resultd = $stmtd->fetchAll(PDO::FETCH_ASSOC);
							$stmtd = null;
							foreach($resultd as $delay){
								$delay_db=$delay['option_value'];
							}

						}catch (PDOException $e){
							print $e->getMessage();
						}


						//fclose($myfile);
						
						$txtA.="delay from db:".$delay_db;
						$file = fopen("testAgent.txt","a");
						echo fwrite($file,$txtA);
						fclose($file);
						mail("siddish.gollapelli@ideabytes.com","Agent_vs_Sub",print_r($txt,true));//debug mail

						//mails for all
						$sqlMu = "SELECT * from wp_users where ID = ".$user_id." ";		
						try {
							$stmtMu = $dbcon->prepare($sqlMu, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmtMu->execute();
							$resultMu = $stmtMu->fetchAll(PDO::FETCH_ASSOC);
							$stmtMu = null;
							foreach($resultMu as $result){
								$user_mail_id=$result['user_email'];
								$user_name=$result['user_login'];
							}

						}catch (PDOException $e){
							print $e->getMessage();
						}

						$sqlMa = "SELECT * from agent_info where id = ".$agent_id." ";		
						try {
							$stmtMa = $dbcon->prepare($sqlMa, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmtMa->execute();
							$resultMa = $stmtMa->fetchAll(PDO::FETCH_ASSOC);
							$stmtMa = null;
							foreach($resultMa as $result){
								$agent_mail_id=$result['email'];
								$agent_name=$result['agentname'];
							}

						}catch (PDOException $e){
							print $e->getMessage();
						}

						//$admin_mail_id = ADMIN_EMAIL;
						$admin_mail_id="siddish.gollapelli@ideabytes.com";
						$sitename = "QezyPlay";
						$loginlink = SITE_URL."/login";						
						$agentloginlink = SITE_URL."/qp/agent-login.php";
						
						// Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

						// More headers
					//	$headers .= 'From: <'.ADMIN_EMAIL.'>' . "\r\n";
					$headers .= 'From: Admin-QezyPlay <admin@qezyplay.com>' . "\r\n";
						
						$subjectAdmin = "Member Subscribed for $planname at $sitename";
						$subjectAgent = "Your Subscription for $planname($user_mail_id) at $sitename";
						$subjectUser = "Your Subscription confirmation for $sitename";
						
						$regards = "<p>Regards, <br>QezyPlay</p>";
						
						$bodyAdmin = "<p>Hi,</p>
						<p>There was a new member checkout at $sitename</p>
						<p>Below are details about the new subscription <br>
						<p>
						Agent: $agent_name ($agent_mail_id) <br><br>
						Account: $user_name ($user_mail_id)<br><br>
						Previous Plan: $old_planname <br><br>
						Subscription Plan: $planname<br><br>
						Trail/Delay awarded: $delayi<br><br>
						Subscription Amount: ".$plan_amount." <br><br>
						New Plan starts on: $plan_startdate <br><br>
						Plan ends on: $enddate_1 <br><br>
						</p>
						<p>Log in to your subscription account here: $loginlink</p>".$regards;
						
						

						$bodyAgent = "<p>Hi,</p>
						<p>User($user_name) has been subscribed at $sitename. Subscription plan is now active.</p>
						<p>Below are details about the subscription</p>
						<p>
						Subscriber Account: $user_name ($user_mail_id)<br><br>
						Subscription Plan: $planname<br><br>
						Subscription Amount: ".$plan_amount."<br><br>
						</p>
						<p>Log in to your Agent Portal here: $agentloginlink</p>".$regards;
						
						

						$bodyUser = "<p>Hi,</p>
						<p>Thank you for your subscription to $sitename. Your subscription plan is now active.</p>
						<p>Below are details about your subscription</p>
						<p>
						Agent: $agent_name<br><br>						
						Previous Plan: $old_planname<br><br>
						Subscription Plan: $planname<br><br>
						Trail/Delay awarded: $delayi<br><br>
						Subscription Amount: ".$plan_amount."<br><br>
						Plan starts on: $plan_startdate <br><br>
						Plan ends on: $enddate_1 <br><br>
						</p>
						<p>Log in to your subscription account here: $loginlink</p>".$regards;				
						
						//mail Admin
						mail($admin_mail_id,$subjectAdmin,print_r($bodyAdmin,true),$headers);
						//mail Agent
						mail($agent_mail_id,$subjectAgent,print_r($bodyAgent,true),$headers);
						//mail User
						mail($user_mail_id,$subjectUser,print_r($bodyUser,true),$headers);
						/****************** END *****************/



						//update payment(remittence) pending status

						$sqlPR = "UPDATE agent_remittence_pending SET status='paid' where agent_id = ".$agent_id."  and subscriber_id=".$sub_id." ";		
						try {
							$stmtPR = $dbcon->prepare($sqlPR, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmtPR->execute();
							
							$stmtMa = null;
							
						}catch (PDOException $e){
							print $e->getMessage();
						}

						//update credit request status
						
						$sqlCR = "DELETE FROM agent_credit_requests where agent_id = ".$agent_id."  and user_name='".$user_name."' and status='unread' order by id desc";		
						try {
							$stmtPR = $dbcon->prepare($sqlCR, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmtPR->execute();
							
							$stmtNa = null;
							
						}catch (PDOException $e){
							print $e->getMessage();
						}
						

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

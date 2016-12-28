<?php
/* create connection */
try{
    $dbcon = new PDO("mysql:host=192.169.213.239;dbname=qezyplay_wordpress", "qezyplay_test", "test_qezyplay"); //LIVE
	//$dbcon = new PDO("mysql:host=50.62.170.42;dbname=tradmin_qezyplaydemo", "tradmin_qezyplay", "&(qezy@word)&"); //DEMO
}
catch(PDOException $e){
    echo $e->getMessage();    
}

session_start();

$agent_id=$_POST['agent_id'];
$sub_id=$_POST['subscriber'];
$boq_id=$_POST['bouquet'];
//$plan_id=$_POST['plan'];
$plan_id=$_POST['item_number'];

$sql = "SELECT * FROM wp_pmpro_membership_levels WHERE id = ?";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($plan_id));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
 
    		$stmt = null;

		$plan=$result;
		
	     }
	catch (PDOException $e){
			print $e->getMessage();
             }


// Database variables
$host = "localhost"; //database location
$user = ""; //database username
$pass = ""; //database password
$db_name = ""; //database name

// PayPal settings
$paypal_email = 'training.ideabytes-facilitator@gmail.com'; //sandbox-id

//$return_url = 'http://ideabytestraining.com/demoqezyplay/qp/Paypal/payment-successful.php?go='.$agent_id.''; //DEMO
//$cancel_url = 'http://ideabytestraining.com/demoqezyplay/qp/Paypal/payment-cancelled.php?go='.$agent_id.''; //DEMO
//$notify_url = 'http://ideabytestraining.com/demoqezyplay/qp/Paypal/payments.php'; //DEMO

$return_url = 'https://qezyplay.com/qp/Paypal/payment-successful.php?go='.$agent_id.''; //LIVE
$cancel_url = 'https://qezyplay.com/qp/Paypal/payment-cancelled.php?go='.$agent_id.''; //LIVE
$notify_url = 'https://qezyplay.com/qp/Paypal/payments.php'; //LIVE


//$item_name = 'Test Item'; //$_POST['name']
$item_name = $plan['name'];
//$item_amount = 5.00;
$item_amount = $plan['initial_payment'];

// Include Functions
include("functions.php");

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){
	$querystring = '';
	
	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";
	
	// Append amount& currency (£) to quersytring so it cannot be edited in html
	
	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	
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
	$querystring .= "&custom=".$custom;
//added 1
/*
$querystring .= "&agent=".$agent_id;
$querystring .= "&sub=".$sub_id;
$querystring .= "&boq=".$boq_id;
$querystring .= "&plan=".$plan_id;
*/	
	// Redirect to paypal IPN
	header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring); //Sandbox ADDED: change to live paypal www.paypal.com/......
	exit();
} else {
	//Database Connection
	$link = mysql_connect($host, $user, $pass);
	mysql_select_db($db_name);
	
	// Response from Paypal

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
	
	$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30); //Sandbox ADDED: change this to lIVE paypal url
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

try{
    $dbcon = new PDO("mysql:host=192.169.213.239;dbname=qezyplay_wordpress", "qezyplay_test", "test_qezyplay"); //LIVE
	//$dbcon = new PDO("mysql:host=50.62.170.42;dbname=tradmin_qezyplaydemo", "tradmin_qezyplay", "&(qezy@word)&"); //DEMO
}
catch(PDOException $e){
    echo $e->getMessage();    
}


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
		
	    			 }
				catch (PDOException $e){
					print $e->getMessage();
            			 }

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
		
	    			 }
				catch (PDOException $e){
					print $e->getMessage();
            			 }
				
				if($count==1 or $count1==1)
				{		
				$startdate1=$created_datetime;
				}
				else {$startdate1->add(new DateInterval('P30D'));}	

				$startdate=$startdate1->format('Y-m-d H:i:s');
				

$enddate=new DateTime($startdate);
if($plan_id==1)
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
else{$enddate="";}


				$enddate=$enddate->format('Y-m-d H:i:s');
						
$myfile = fopen("myCheck.txt", "w") or die("Unable to open file!");
$txt = "custom:".$data['custom']."\n";
fwrite($myfile, $txt);
$txt = $data['payment_status']."DB:"."agent_id:".$agent_id."sub:".$sub_id."boq:".$boq_id."plan:".$plan_id;
fwrite($myfile, $txt);
$txt = $data['payment_amount']."DB:"."paid:".$created_datetime1."start:".$startdate."end:".$enddate;
fwrite($myfile, $txt);
fclose($myfile);
						//insert into agent_vs_subs ONLY on SUCCESS
						$sql1 = "INSERT INTO agent_vs_subscription_credit_info(agent_id,subscriber_id,bouquet_id,plan_id,amount,subscription_start_from,subscription_end_on,credited_datetime) VALUES(?,?,?,?,?,?,?,?)";		
	try {
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute(array($agent_id,$sub_id,$boq_id,$plan_id,$data['payment_amount'],$startdate,$enddate,$created_datetime1));	
		$stmt1 = null;
	}catch (PDOException $e){
		print $e->getMessage();
	}
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

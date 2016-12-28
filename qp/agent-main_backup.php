<?php

ob_start();
session_start();

include("agent-config.php");


//Access your POST variables
$agent_id = $_SESSION['POST'];
//echo "id:".$agent_id;
//Unset the useless session variable


function getPlanName($planid){

	global $dbcon;

	$sql2 = "SELECT name FROM wp_pmpro_membership_levels where id = ".$planid;		
	try {
		$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt2->execute();
		$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
 		$stmt2 = null;
		foreach($result2 as $plan){
			return $plan['name'];
		
		}
	}catch (PDOException $e){
		print $e->getMessage();
	}

}


if($agent_id==""){
	
	header('Location: agent-login.php');
	exit;
	
}else{

	//unset($_SESSION['POST']);

	$sql = "SELECT agentname FROM agent_info WHERE id = ?";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($agent_id));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
 
    	$stmt = null;

		$agent_name=$result['agentname'];
		
	}catch (PDOException $e){
		print $e->getMessage();
    }



	if(isset($_GET['logout'])){

		unset($_SESSION['POST']);
		session_destroy();
		// or...
		//unset($_SESSION['username'];

		header('Location: agent-login.php');
		exit;
	}
	
	include("header.php");
	
	$html = "";
	$html .= "<link rel='stylesheet' href='css/ag.css'>
	
	 <script language='javascript' type='text/javascript' src='js/jquery.js'></script>	
	 <div class='row' style='padding:0px 10px;background-color:#000 !important;height:90px;color:#fff !important;max-width:1500px;'>
					<div class='twelve columns'>
						<header>
							<div class='four columns'>
								<div id='logo'>
								<img src='../qp/qezyplay-logo.png' alt='QezyPlay' height='73' width='173'>
								</div>
							</div>
							<div class='four columns'>
								<div id='hedtext'>								
									<h3 style='color:#fff !important;'>AGENT PORTAL</h3>
								</div>
							</div>

							<div class='four columns'>
								<div id='hedtext'>
									<h4 style='color:#fff !important;'>Welcome ".$agent_name.",&nbsp;&nbsp;<span><a id='logout' href='?logout=true'>Logout</a></span></h4>
								</div>
							</div>

					  </header>
					</div>
				</div>
	<div class='clear'></div>
	<div align='center' class='row' style='margin:0 auto;padding:7% 10px;background-color:white !important;height:150px;color:#fff !important;max-width:1500px;'>
	<table id='emailchecking'><tr><td><label style='font-size: 15px;'  for='subscriber'>Enter subscriber's E-mail</label></td></tr><tr><td><input type='email' name='user-mail' id='user-mail' onclick='clearError()' /></td><td><button onclick='checkMail();' value='Check e-mail' >Validate</button></td></tr>
	<tr><td colspan='2'><p style='color:red;' id='emailErr'></p></td></tr></table>

	<p id='emailSuccess' style='color:black;display:none;'></p>



	<form class='paypal' action='../qp/Paypal/payments.php' method='post' id='paypal_form' target='_self' style='display:none;'>
			<input type='hidden' name='cmd' value='_xclick' />
			<input type='hidden' name='no_note' value='1' />
			<input type='hidden' name='lc' value='IND' />
			<input type='hidden' name='currency_code' value='USD' />
			<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest' />
			<input type='hidden' name='first_name' value=''  />
			<input type='hidden' name='last_name' value=''  />

			<input type='hidden' name='subscriber' id='sub' />


	<table>
	<!-- <tr><td><label for='subscriber'>User/Subscriber</label></td><td><select name='subscriber' required><option value=''>Select User</option> -->";

	

	$html.="<tr><td><label style='font-size: 15px;'  for='bouquet'>Bouquet</label></td><td><select name='bouquet' required><option value=''>Select Bouquet</option><option value='1'>Bangla Bouquet</option>";

		//For other bouquets 

	$html.="</select></td></tr>
	<tr><td><label style='font-size: 15px;'  for='plan'>Subscription Plan</label></td><td><select name='item_number' required><option value=''> --Select Plan-- </option>";

	$sql2 = "SELECT id, name, billing_amount FROM wp_pmpro_membership_levels";		
	try {
		$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt2->execute();
		$result2 = $stmt2->fetchall(PDO::FETCH_ASSOC);
		$stmt2 = null;
		foreach($result2 as $plan){
			$planid = $plan["id"];
			$planname = $plan["name"];
			if($plan["billing_amount"] > 0){
				$html.="<option value='".$planid."'>".$planname."</option>";
			}
		}
	}catch (PDOException $e){
		print $e->getMessage();
	}




	$html.="</select></td></tr>
	<tr><td></td><td><input type='submit' name='submit' value='Pay' id='Paypal' /></td></tr>
	</table>
		<input type='hidden' name='payer_email' value=''  />	
		<input type='hidden' name='agent_id' value='".$agent_id."' / >

	  </form>
	</div>
	<script>
	function clearError(){
		document.getElementById('emailErr').innerHTML = '';
	}

	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	function checkMail(){	
	
		var mail=document.getElementById('user-mail').value;
		var val={'action':'agentuser-email','mail':mail};
		
		if(mail==''){	
			document.getElementById('emailErr').innerHTML = 'Please enter Email-id';
			//document.getElementById('user-mail').setCustomValidity('Please enter Email-id');
		}else{
		
			var emailValidateCheck = validateEmail(mail);
			if(emailValidateCheck){
			
				jQuery.ajax({url: 'uservalidation_check.php',
				type: 'post',
				data: val,
				success: function(response){   
						if(response==1){							
							document.getElementById('emailErr').style.display='block';document.getElementById('emailErr').innerHTML='Entered Email-id does not exists';
						}else if(response==2){							
							document.getElementById('emailErr').style.display='block';document.getElementById('emailErr').innerHTML='Something wrong! Contact Admin';
						}else{
							document.getElementById('emailchecking').style.display='none';
							document.getElementById('emailSuccess').innerHTML='Subscriber: '+mail;	
							document.getElementById('emailSuccess').style.display='block';						
							document.getElementById('paypal_form').style.display='block';
							document.getElementById('sub').value=response;
						}
					}
				});	
				
			}else{				
				document.getElementById('emailErr').style.display='block';
				document.getElementById('emailErr').innerHTML='Please enter valid Email-id';
			}
		}
	}
	</script>

	<div id='AgentCredits' align='center' class='pmpro_box' style='overflow-y:scroll; overflow-x:hidden; height:200px;'><h4>List of Subscriptions</h4> 
	<table class='widefat membership-levels' style='text-align: center;width:95% !important;color:black !important;background: linear-gradient(135deg, rgba(243,226,199,1) 0%, rgba(193,158,103,1) 26%, rgba(182,141,76,1) 75%, rgba(233,212,179,1) 100%) !important;border: 2px solid rgba(123, 92, 1, 0.94) !important;
		border-radius: 10px;'>

			<thead>
				<tr style='font-size:15px;'>
					<th>Subscriber</th>
					<th>Bouquet</th>
					<th>Plan</th>
					<th>Amount</th>
					<th>Start-date</th>
					<th>End-date</th>
					<th>Paid Date</th>
				</tr>
			</thead>
			<tbody class='ui-sortable'     style='font-size: 12px;'>";

	$sql3 = "SELECT * FROM agent_vs_subscription_credit_info where agent_id=?";		
	try {
		$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt3->execute(array($agent_id));
		$result3 = $stmt3->fetchall(PDO::FETCH_ASSOC);
 		$stmt3 = null;
		foreach($result3 as $credit){
			$subid=$credit["subscriber_id"];
			$boqid=$credit["bouquet_id"];
			$planid=$credit["plan_id"];

			$planName = getPlanName($planid);

			$amount=$credit["amount"];
			$start=$credit["subscription_start_from"];
			$end=$credit["subscription_end_on"];
			$paid=$credit["credited_datetime"];

			
			$sql4 = "SELECT user_login,user_email FROM wp_users where ID=?";				
			$stmt4 = $dbcon->prepare($sql4, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt4->execute(array($subid));
			$result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
			$user_email=$result4['user_email'];
			$user_login=$result4['user_login'];
		
			if(count($result4) > 1){
				$html.="<tr style='' class='ui-sortable-handle'>			
							<td style='width: 44px;'>".$user_login." (".$user_email.")</td>
							<td style='width: 342px;'>Bangla Bouquet</td>
							<td style='width: 184px;'>".$planName."</td>
							<td style='width: 192px;'>".$amount."</td>
							<td style='width: 192px;'>".$start."</td>
							<td style='width: 192px;'>".$end."</td>
							<td style='width: 192px;'>".$paid."</td>
						</tr>";
			}

		}
			
	}catch (PDOException $e){
		print $e->getMessage();
	}


	$html .= "</tbody></table></div>";
	
	echo $html;
	
	include("footer.php");
	
}
?>

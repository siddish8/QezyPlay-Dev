<?php

include("db-config.php");
include("function_common.php");

//Access your POST variables
$agent_id = $_SESSION['agentid'];
//echo "id:".$agent_id;
//Unset the useless session variable


function sendPost($data,$url) {

    $ch = curl_init();
    // you should put here url of your getinfo.php script
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec ($ch); 
    curl_close ($ch); 

    return $result; 
}

$msg = ""; 
$msgR = ""; 

if(isset($_POST['add_to_pay'])){

$agentId = $_POST['agent_id'];
$sub_id = $_POST['subscriber'];
$boq_id = $_POST['bouquet'];
//$plan_id=$_POST['plan'];
$plan_id = $_POST['item_number'];
$added_datetime=new DateTime("now");
$added_datetime=$added_datetime->format("Y-m-d H:i:s");

$uname=get_var("select user_login from wp_users where ID=".$sub_id." ");

$sql01="SELECT * FROM agent_remittence_pending where agent_id=".$agentId." and subscriber_id=".$sub_id." and bouquet_id=".$boq_id." and plan_id=".$plan_id." and status='penidng' ";
			$stmt01 = $dbcon->prepare($sql01, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt01->execute();
			$result01 = $stmt01->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($result01)==0)
			{
			$sql1 = "INSERT INTO agent_remittence_pending(agent_id,subscriber_id,bouquet_id,plan_id,added_datetime) VALUES(?,?,?,?,?)";		
						try {
							$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt1->execute(array($agentId,$sub_id,$boq_id,$plan_id,$added_datetime));	
							$stmt1 = null;
						}catch (PDOException $e){
							print $e->getMessage();
						}

			$sq="DELETE FROM agent_credit_requests where agent_id=".$agent_id." and user_name='".$uname."' ";
					
			$st=$dbcon->prepare($sq, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$st->execute();
			}
			else{
			//echo "<script>swal('Already in Payment Pending List ')</script>";
				}
		$added_hide=1;
		$userPR=1;
}

/* if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  if(isset($_POST['PRid']))
  {
       // print_r($_POST);
$id = $_POST['PRid'];
echo $id;
exit;
  }

}*/

if(isset($_POST['PRids']))
  {
       // print_r($_POST);
$id = $_POST['PRids'];
$stmt13 = $dbcon->prepare("DELETE FROM agent_remittence_pending WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt13->execute();
	
	
	$msgR = "<span style='color:green'>Pending Remittance deleted Successfully</span>";

		$userPR=1;
//echo $id;
//echo "<script>alert('Del: '".$id."');</script>";
//exit;
  }

/*if(isset($_POST['removePR'])){

	$id = $_POST['PRid'];
//echo $id;
//echo $_POST['delPR'];
echo $id;
exit;
	$stmt13 = $dbcon->prepare("DELETE FROM agent_remittence_pending WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt13->execute();
	
	
	$msgR = "<span style='color:green'>Pending Remittance deleted Successfully</span>";

		$userPR=1;
//echo '<script>window.location.href="http://ideabytestraining.com/newqezyplay/qp/user_forms.php";</script>';
	
}*/


if(isset($_REQUEST['delCRs'])){

	$id = $_REQUEST['CRids'];
//echo "del CR:".$id;
//exit;
	$stmt11 = $dbcon->prepare("DELETE FROM agent_credit_requests WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();
	
	
	$msg = "<span style='color:green'>Subscribe Request deleted Successfully</span>";

		$userCR=1;
//echo '<script>window.location.href="http://ideabytestraining.com/newqezyplay/qp/user_forms.php";</script>';
	
}




/*if(isset($_GET['readCR'])){

		$id = $_GET['id'];


		$stmt22 = $dbcon->prepare('UPDATE agent_credit_requests SET status = "read" WHERE id = '.$id, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
		$stmt22->execute();

			$userCR=1;	
	
	
	}	*/


if(isset($_REQUEST['payCRs'])){

		$id = $_REQUEST['pCRids'];
		$email_CR=$_REQUEST['CRemails'];
	//echo "id:".$id."   email:".$email_CR;
	
		$userPR=1;
		$CR_to_PR=1;

}


if(isset($_GET['logout'])){

	unset($_SESSION['agentid']);

	header('Location: agent-login.php');
	exit;
}

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


if((int)$_SESSION['agentid'] <= 0){
	
	header('Location: agent-login.php');
	exit;
	
}else{
	
	$sql = "SELECT agentname FROM agent_info WHERE id = ?";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($agent_id));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
 
    	$stmt = null;

		$agent_name = $result['agentname'];
		
	}catch (PDOException $e){
		print $e->getMessage();
   	}
	
	include("header-agent.php");
	?>
		

<style>

.mx{max-width:80%;margin:0 auto !important;}
 /* Style the list */
ul.tab {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Float the list items side by side */
ul.tab li {float: left;}

/* Style the links inside the list items */
ul.tab li a {
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
ul.tab li a:hover {background-color: #ddd;}

/* Create an active/current tablink class */
ul.tab li a:focus, .active {background-color: #ccc;}

/* Style the tab content */
.tabcontent {
    display: none;
       border-top: none;
	 text-decoration:none;
}

.show{display:block !important;}
.hide{display:none !important;}

.link_btn:hover{
    background: #000;
    color: #fff;
    border: solid 1px #000;
}

.link_btn{
    border-radius: 3px;
    line-height: 2.75;
    text-align: center;
    margin: 5px;
    padding: 5px 18px;
    outline: none;
    background: #4141a0;
    border: solid 1px #4141a0;
    color: #fff;
    transition: all .2s ease;
    text-decoration:none;
	border-radius:5px;
}

td:nth-child(even) {background: #FFF}
td:nth-child(odd) {background: #DDD}
th{color:whitesmoke !important;text-align: center;border: 1px solid rgba(255,255,255,.15) !important;}
thead{background-color: #4141a0}
table td {
    color: #444;
border-bottom: 1px solid #777 !important;
	text-align: center;
}


div#SubscriberList > div > input[type="text"]{max-width:250px}
._or{margin:0 15px}

footer{position: relative;
    bottom: 0px;
    width: 100%;}
#bottom-nav_1{position: relative;
    bottom: 0px;
    width: 100%;}
#emailchecking{display:none;}

</style>
<div align="center" style="margin:0 auto">
<ul class="tab mx">
  <li><a id="CR" href="#" class="tablinks active" onclick="agentTabs(event, 'CreditRequest')">Request for Subscribe</a></li>
 <li><a id="PR" href="#" class="tablinks" onclick="agentTabs(event, 'PendingRemittance')">Remittance Pending</a></li>
  <li><a id="SL" href="#" class="tablinks" onclick="agentTabs(event, 'SubscriberList')">Subscribers List</a></li>
 </ul>
</div>	
<input type="hidden" id="AID" value="<?php echo $agent_id?>" />
	<div id="content" role="main" style="min-height:500px;">

		<div id="PendingRemittance" class="tabcontent mx">

		<div class="msg" align="center" style="display:block"><h4><?php echo $msgR; ?></h4></div> 	
	<br />	<div style="float:right">
		<a style="color:#2a85e8;border: 2px solid black;padding: 0px 6px;" href="#" onclick="return other_user_btn()" >Validate Another User</a>&nbsp; &nbsp;
		<a style="color:#2a85e8;border: 2px solid black;padding: 0px 6px;" href="<?php echo SITE_URL ?>/register" target="_blank">Create New Registration</a></div>
		<div id="emailchkdiv" class="xoouserultra-wrap xoouserultra-login" style="float:left;max-width:100% !important;padding:0px 10px">
			<div class="xoouserultra-inner xoouserultra-login-wrapper" style="border:none;border-radius:unset;box-shadow:none">
				<!-- <div class="xoouserultra-main"> -->
					<div id="emailchecking" style="float:left;width:50%;padding:0px 10px;style:border 1px blue;display:none">
						<div style="margin:10px" class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">					
							<center><span>Validate subscriber:</span></center>
						</div>	


						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type" style="margin:12px 0px !important">
								<span>User Name or E-mail: </span>
							</label>
							<div class="xoouserultra-field-value">
								<!-- <input required onkeypress="return event.charCode !=32" class="xoouserultra-input" name='user-email' id='user-email' onclick='clearError()' type="email">	-->
								<input required onkeypress="return event.charCode !=32" class="xoouserultra-input" name='user-emailUN' id='user-emailUN' onclick='clearError()' type="text">
							</div>
						</div>	
						
						<!-- <div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type">
								<span>User Name </span>
							</label>
							<div class="xoouserultra-field-value">
								<input class="xoouserultra-input" name='user-username' id='user-username' onclick='clearError()' type="text">
								
							</div>
						</div> -->

					<!--	<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type">
								<span>First Name </span>
							</label>
							<div class="xoouserultra-field-value">
								<input class="xoouserultra-input" name='user-firstname' id='user-firstname' onclick='clearError()' type="text">
								
							</div>
						</div>

						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type">
								<span>Last Name </span>
							</label>
							<div class="xoouserultra-field-value">
								<input class="xoouserultra-input" name='user-lastname' id='user-lastname' onclick='clearError()' type="text">
								
							</div>
						</div> -->

						<!-- <div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type">
								<span>Phone (or) Mobile </span>
							</label>
							<div class="xoouserultra-field-value">
								<input class="xoouserultra-input" name='user-phone' id='user-phone' onclick='clearError()' type="text">
								
							</div>
						</div> -->


						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show" id='searchErrDiv' style="display:none";>
							<label for="user_login" class="xoouserultra-field-type" >&nbsp;</label>
							<div class="xoouserultra-field-value" style="float: unset;
    width: 100%;">
								<div id='searchErr' style="color:red; "></div>
							</div>
						</div>						

						
						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label class="xoouserultra-field-type">&nbsp;</label>
							<div class="xoouserultra-field-value" style="float:unset">
								<input type="button" onclick='return checkMail();' name='validate' value="Validate" class="" name="xoouserultra-login" id="validate">
							</div>
						</div>
						<div  id="subscribed-div" style='display:none;color:red;text-align: center;
   '><p class="" id="subscribed"></p></div>	
					<div id="sub-pending-div" style='display:none;color:red;text-align: center;
    '><p id="sub-pending"></p></div>
					<div id='usernotfoundiv' style='color:red;display:none;text-align: center;
    '></div>
						<div class="xoouserultra-clear"></div>
					</div>

					<!-- <div class="xoouserultra-clear"></div> -->
					

					
					<!-- <div class="xoouserultra-clear"></div> -->

					<!--<form class='paypal' action='Paypal/payments.php' method='post' id='paypal_form' target='_self' style='display:none;border 1px solid grey;'>-->	

<!-- <div style="float:left;width:50%;padding:0px 10px;margin:-110px auto"> -->

<div id="selection" style="float: left;
    width: 100%;
    padding: 0px 30px;
    margin: 30px;border: 1px solid #ddd; box-shadow: 0 1px 2px -1px #ccc;display:none"> 
						<form class='paypal' method='post' id='paypal_form' target='_self' style='display:none;border 1px solid grey;'>
						<div id='userdiv' style='color:black;'></div><br>
			
						
						<input type='hidden' name='subscriber' id='subscriber' />
	
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type" style="margin:12px 0px !important">
								<span>Bouquet: </span>
							</label>
							<div class="xoouserultra-field-value">
								<select style="color:black;" name='bouquet' required><option value=''>Select Bouquet</option><option value='1'>Bangla Bouquet</option></select>								
							</div>
						</div>	

						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type" style="margin:12px 0px !important">
								<span>Plan: </span>
							</label>
							<div class="xoouserultra-field-value">
								<select style="color:black;" name='item_number' required><option value=''> --Select Plan-- </option>								
								<?php
								$sql2 = "SELECT id, name, billing_amount FROM wp_pmpro_membership_levels where allow_signups=1";		
								try {
									$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
									$stmt2->execute();
									$result2 = $stmt2->fetchall(PDO::FETCH_ASSOC);
									$stmt2 = null;
									foreach($result2 as $plan){
										$planid = $plan["id"];
										$planname = $plan["name"];
										if($plan["billing_amount"] > 0){
											echo "<option value='".$planid."'>".$planname."</option>";
										}
									}
								}catch (PDOException $e){
									print $e->getMessage();
								}
								?>
								</select>
							</div>
						</div>
						
						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label class="xoouserultra-field-type">&nbsp;</label>
							<div class="xoouserultra-field-value" style="padding-left: 10px;float:unsetwidth:100%">
								<!--<input type='submit' name='submit' value='Pay' id='Paypal' />-->
								<input type='submit' name='add_to_pay' value='Add to Pay List' id='add_to_pay' />
							</div>
						</div>
						<div class="xoouserultra-clear"></div>

						<input type='hidden' name='payer_email' value=''  />	
						<input type='hidden' name='agent_id' value="<?php echo $agent_id; ?>" / ><br>
					</form></div>
				<!-- </div> -->
			</div>
		</div>

		<!--<div class="xoouserultra-wrap xoouserultra-login" style="float:left;width:50%;padding:0px 10px">
			<div class="xoouserultra-inner xoouserultra-login-wrapper">
				<div class="xoouserultra-main"> -->
		<div id="PayReady" style="max-width: 100%;">

		<center><h2>Payment Pending List</h2></center>
<?php 
echo '

	<div class="clear"></div>
		<form id="frm1" style="max-width: 100%;" method="post">
		<div class="table-responsive"	style="max-height: 300px; overflow-y: scroll;">
		<table id="pending_list" class="widefat membership-levels" style="max-width:100% !important;width:100% !important;">
			<thead>
				<tr>					
					<th>UserName(E-mail)</th>
					<th>Bouquet</th>
					<th>PlanName</th>
					<th>Amount Paid(USD)</th>
					<th>Paid Date</th>
					<th>Status</th>
					<th>Action</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			$sql99="SELECT a.id,a.subscriber_id,c.user_login,c.user_email,a.bouquet_id,a.plan_id,b.name,b.billing_amount,a.added_datetime,a.status FROM agent_remittence_pending a inner join wp_pmpro_membership_levels b on b.id=a.plan_id inner join wp_users c on c.ID=a.subscriber_id where a.agent_id=".$agent_id." and status='pending'";
			$stmt99 = $dbcon->prepare($sql99, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt99->execute();
			$result99 = $stmt99->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($result99)==0)
			{
			echo "<style>.no_pending{display:none}</style>";
			echo "<tr style='text-align:center'><td colspan='7'>No Pending Remittances to Show</td></tr>";
			}
						
			foreach($result99 as $row99){

				
				$id=$row99['id'];
				//$aid=$row99['agent_id'];

				//if($aid!=$agent_id)
				//	continue;
				$userid=$row99['subscriber_id'];
				$username=$row99['user_login'];
				
				$email=$row99['user_email'];
				$boq=$row99['bouquet_id'];
				if($boq==1)
				{$boq_name="Bangla Bouquet";}

				$plan_name=$row99['name'];
				$plan_amount=$row99['billing_amount'];
				$status=$row99['status'];

				$time=$row99['added_datetime'];

				//if($status=="read")
				//{
				//echo "<style>#CR-".$id."{background-color:green !important;color:black !important;}</style>";
				//echo "<style>#readCR-".$id."{display:none !important}</style>";
				//}
				//else
				//{
				//echo "<style>#F-".$id."{background-color:yellow !important;color:red !important;}</style>";
				//}
				
			
			echo 	'<tr id="PR-'.$id.'" style="" class="ui-sortable-handle">							
				<td style="width: 192px;" class="level_name">'.$username.'('.$email.')</td>
				<td style="width: 192px;">'.$boq_name.'</td>
				<td style="width: 184px;">'.$plan_name.'</td>
				<td style="width: 184px;">'.$plan_amount.'</td>
				<td style="width: 184px;">'.$time.'</td>
				<td style="width: 184px;">'.$status.'</td>
				<td style="width: 280px;">
				<input type="hidden" id="PRid" name="PRid" value="'.$id.'">
				<input type="hidden" id="delPR" name="delPR" value="false">
				<input style="padding: 1px 4px !important;
    background: none !important;
    border: 0px transparent !important;
    box-shadow: none !important;
    color: #2a85e8 !important;
    font-size: 15px;
    font-weight: bold;
    margin: 0px 5px !important;" type="button" id="removePR" name="removePR" value="Remove" onclick="return confirmDelPR('.$id.')">
				
				
						<input type="hidden" name="cmd" value="_xclick"/>
						<input type="hidden" name="no_note" value="1"/>
						<input type="hidden" name="lc" value="IND"/>
						<input type="hidden" name="currency_code" value="USD"/>
						<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest"/>
						<input type="hidden" name="first_name" value=""/>
						<input type="hidden" name="last_name" value=""/>
						<input type="hidden" name="item_name1" value="Paying: '.$username.'"/>
						<!-- <input type="hidden" name="amount1" id="amount1" value="'.$plan_amount.'"/> -->
						<input type="hidden" name="agent_id" value="'.$agent_id.'">
						<input type="hidden" name="user_id" value="'.$userid.'">
						<input type="hidden" name="bulk1" value="false">						
						<input style="padding: 1px 4px !important;
    background: none !important;
    border: 0px transparent !important;
    box-shadow: none !important;
    color: #2a85e8 !important;
    font-size: 15px;
    font-weight: bold;
    margin: 0px 5px !important;" type="submit" name="payPR" value="Pay" onclick="this.form.action=\'Paypal/payments.php?Rid='.$id.'&amount='.$plan_amount.'&item_name=Paying: '.$username.'&bulk=false\'">
						<input type="hidden" name="payer_email" value="">							
						
				<!-- <a class="link_btn" class="link_btn" style="cursor: pointer;" title="delete" name="removePR-'.$id.'" id="removePR-'.$id.'" onclick="callConfirmationPR(\'agent-main.php?delPR=true&id='.$id.'\');" class="button-secondary">Remove</a>-->
</td>
				
				</tr>';
			 } 
			
			echo '</tbody>
		</table></div>
		</form>
	';


?>
	
		<!--</div> -->
		<!-- </div></div>-->
		<!-- <div> -->
		<form class='paypal' action='Paypal/payments.php' method='post' id='paypal_formAll' target='_self' style=''>
						<input type='hidden' name='cmd' value='_xclick'/>
						<input type='hidden' name='no_note' value='1'/>
						<input type='hidden' name='lc' value='IND'/>
						<input type='hidden' name='currency_code' value='USD'/>
						<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest'/>
						<input type='hidden' name='first_name' value=''/>
						<input type='hidden' name='last_name' value=''/>
						<input type='hidden' name='item_name' value='Bulk Pay'/>
						<input type='hidden' name='total_amount' id="total_amount" value=''/>
						
						
							
						<input class="no_pending" type='submit' name='submit' value='Pay All' id='Paypal' style="float:right;margin:15px" />
						<label class="no_pending" style="float: right;
    margin: 25px;
    font-size: 18px;">Total Amount :  $ <span style="color:black" id="total_amount1"></span>  USD</label>						
																			
						<div class="xoouserultra-clear"></div>
						<input type='hidden' name='payer_email' value=''  />	
						<input type='hidden' name='agent_id' value="<?php echo $agent_id; ?>" / >
						
		
		</form>
		<!-- </div> -->
		</div></div>
		<!--</div>-->
		<br>
	<div id="SubscriberList" class="tabcontent mx">
	<div align="center" style="margin:0 auto;">
		Search using any criteria: <br /> <input placeholder="Enter user-name" id="searchUN" type="text" name="searchUN" /> <span class="_or"> </span>   
		<input placeholder="Enter user-email" id="searchEM" type="text" name="searchEM" /> <span class="_or"> </span>
		<input placeholder="Enter user-firstname" id="searchFN" type="text" name="searchFN" />  <span class="_or"> </span>
		<input placeholder="Enter user-lastname" id="searchLN" type="text" name="searchLN" /> <span class="_or"> </span>  
		<input placeholder="Enter user-phone" id="searchPH" type="text" name="searchPH" />
		</div>
		<div class="pmpro_box" id="pmpro_account-invoices">
			<h2 style="text-align:center">List of Subscribers</h2>
		<div class="table-responsive"	style="max-height: 400px; overflow-y: scroll;">
			<table id="sub_list1" style="overflow-x:auto;min-width:50%;max-width:80% !important;" width="80%" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th>UserName</th>
						<th>Email</th>
						<th>FirstName</th>
						<th>LastName</th>
						<th>Phone</th>
						<th>Bouquet</th>
						<th>Plan</th>
						<th>Amount</th>
						<th>Start date</th>
						<th>End date</th>
						<th>Paid date</th>
						<th>Plan Status</th>
					</tr>
				</thead>
				<tbody id="sub_list">
				<?php

				$sql3 = "SELECT * FROM agent_vs_subscription_credit_info where  agent_id=? order by credited_datetime desc";		
				try {
					$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
					$stmt3->execute(array($agent_id));
					$result3 = $stmt3->fetchall(PDO::FETCH_ASSOC);
			 		$stmt3 = null;
					if(count($result3) > 0){
						foreach($result3 as $credit){
							$ID=$credit["id"];
							$subid=$credit["subscriber_id"];
							$boqid=$credit["bouquet_id"];
							$planid=$credit["plan_id"];

							$planName = getPlanName($planid);

							$amount=$credit["amount"];
							$start=$credit["subscription_start_from"];
							$end=$credit["subscription_end_on"];
							$paid=$credit["credited_datetime"];

		
							$sql4 = "SELECT * FROM wp_users where ID=?";				
							$stmt4 = $dbcon->prepare($sql4, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt4->execute(array($subid));
							$result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
							$user_email=$result4['user_email'];
							$user_login=$result4['user_login'];
							$user_phone=$result4['phone'];
							
							$sql41 = "select meta_value from wp_usermeta where user_id=? and (meta_key='first_name' or meta_key='last_name')";
							$stmt41 = $dbcon->prepare($sql41, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt41->execute(array($subid));
							$result41 = $stmt41->fetchAll(PDO::FETCH_ASSOC);
							
							//echo var_dump($result41);
							$user_fn=$result41[0]['meta_value'];
							$user_ln=$result41[1]['meta_value'];
							

							$date1=new DateTime("now");
							$date2=new DateTime($end);

							if($date1<$date2)
							{
								$plan_status="active";
								//echo "<style>#credit-$ID{background-color:#D5F288;}</style>";
								echo "<style>#status-$ID{background-color:#D5F288;}</style>";
							}
							else
							{
								$plan_status="expired";
							}

							if(count($result4) > 1){
								echo "<tr class='ui-sortable-handle'>			
										<td>".$user_login."</td>
										<td>".$user_email."</td>
										<td>".$user_fn."</td>
										<td>".$user_ln."</td>
										<td>".$user_phone."</td>
										<td>Bangla Bouquet</td>
										<td>".$planName."</td>
										<td>".$amount."</td>
										<td>".date("Y-m-d",strtotime($start))."</td>
										<td>".date("Y-m-d",strtotime($end))."</td>
										<td>".date("Y-m-d",strtotime($paid))."</td>
										<td id='status-".$ID."'>".$plan_status."</td>
									</tr>";
							}

						}
					}else{
						echo "<tr style='' class='ui-sortable-handle'><td colspan='12'><center>No subscription found</center></td></tr>";
					}	
		
				}catch (PDOException $e){
					print $e->getMessage();
				}
				?>					
				</tbody>
			<tr id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><td colspan="12" id="no_res" style="margin:0 auto;text-align:center"></td></tr>
			</table></div>
			
		</div>		
	</div>	
<div id="CreditRequest" class="tabcontent mx" style="display:block;">
<?php
echo '
<div class="msg" align="center" style="display:block"><h4>'.$msg.'</h4></div> 	
	<br />
	<div class="clear"></div>
		<form id="frm2" method="post">
		<div class="table-responsive"	style="max-height: 500px; overflow-y: scroll;">
		<table class="widefat membership-levels" style="width:100% !important;">
			<thead>
				<tr>	<!-- <th>Read Status</th>				 -->
					<th>Name</th>
					<th>E-mail</th>
					<th>Phone</th>
					<th>Requested DateTime</th>
					<!-- th>Validation/User-subscription status</th -->
					<th>Action</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			$stmt2 = $dbcon->prepare("SELECT * FROM agent_credit_requests where agent_id=".$agent_id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt2->execute();
			$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($result2)==0)
			{
			echo "<tr style='text-align:center'><td colspan='6'>No Subscribe Requests to Show</td></tr>";
			}
						
			foreach($result2 as $row2){

				
				$id=$row2['id'];
				$aid=$row2['agent_id'];

				if($aid!=$agent_id)
					continue;
				$name=$row2['user_name'];
				
				$email=$row2['user_email'];
				$phone=$row2['phone'];
				$status=$row2['status'];

				$time=$row2['requested_time'];
				$user_status="";

				$go_url=SITE_URL."/qp/uservalidation_check.php";
				$data1 = sendPost( array('action'=>'searchuser','emailUN'=>$email),$go_url );
				//echo $data1;
				$usr = json_decode($data1);
								
				$data2 = sendPost( array('action'=>'useractive','user_id'=>$usr->{'ID'}),$go_url );
				if($data2!=="0") {
					$user_status.="User Subscribed \n".$data2."\n"; 
					echo "<style>#payCR-".$id."{pointer-events:none;opacity:0.5}</style>";
						}
				$data3 = sendPost( array('action'=>'userpending','user_id'=>$usr->{'ID'}),$go_url );
				if($data3!=="0")  {
					if($data3==$aid)
						$user_status.="User in your pending remittance list";
					else 
						$user_status.="User in other Agent's list";
					echo "<style>#payCR-".$id."{pointer-events:none;opacity:0.5}</style>";
					}

				if($data2=="0" and $data3=="0")	
					{
						$user_status.="Available";
					}			
				
				
				if($status=="read")
				{
				//echo "<style>#CR-".$id."{background-color:green !important;color:black !important;}</style>";
				echo "<style>#readCR-".$id."{  pointer-events: none;  cursor: default;background-color: #CCCCCC;border:#CCCCCC;}#gr_img-".$id."{display:block !important}</style>";
				}
				else
				{
				//echo "<style>#F-".$id."{background-color:yellow !important;color:red !important;}</style>";
				}
				
			
			echo 	'<tr id="CR-'.$id.'" style="" class="ui-sortable-handle">			
				<!-- <td style="width: 50px;" ><img id="gr_img-'.$id.'" src="'.SITE_URL.'/qp/green_tick.png" style="display:none;width:30px;"/></td> -->				
				<td style="width: 192px;" class="level_name">'.$name.'</td>
				<td style="width: 192px;">'.$email.'</td>
				<td style="width: 184px;">'.$phone.'</td>
				<td style="width: 184px;">'.$time.'</td>
				<!-- td style="width: 184px;">'.$user_status.'</td -->
				<td style="width: 332px;"><!-- <a class="link_btn" style="cursor: pointer;" id="readCR-'.$id.'" href="agent-main.php?readCR=true&id='.$id.'" title="read" name="Read-'.$id.'" class="button-primary">Read</a> -->
				
<a href="#" style="color:#2a85e8 !important" id="removeCR" name="removeCR" value="" onclick="return confirmDelCR(true,'.$id.')" >Remove</a> &nbsp; &nbsp;
<a href="#" style="color:#2a85e8 !important" id="payCR-'.$id.'" name="payCR" value="" onclick="return confirmPayCR(true,\''.$email.'\','.$id.')" >Subscribe<a/>

<!-- <a class="link_btn" style="cursor: pointer;" title="delete" name="removeCR-'.$id.'" id="removeCR-'.$id.'" onclick="callConfirmationCR(\'agent-main.php?delCR=true&id='.$id.'\');" class="button-secondary">Remove</a> -->

<!-- <a class="link_btn" style="cursor: pointer;" title="pay" name="payCR-'.$id.'" id="payCR-'.$id.'" onclick="payConfirmationCR(\'agent-main.php?payCR=true&email_CR='.$email.'&id='.$id.'\');" class="button-secondary">Subscribe</a> --> </td>
				
				</tr>';
			 } 
			
			echo '</tbody>
		</table></div>
		</form>
	</div>';

if($userCR==1)
{
echo '<script>
		document.getElementById("SL").setAttribute("class","tablinks");
		document.getElementById("PR").setAttribute("class","tablinks");
		document.getElementById("CR").setAttribute("class","tablinks active");
		document.getElementById("CreditRequest").setAttribute("class","tabcontent mx show");
		document.getElementById("PendingRemittance").setAttribute("class","tabcontent mx hide");
		document.getElementById("SubscriberList").setAttribute("class","tabcontent mx hide");
</script>';

	
}

?>
</div>
</div>	
											

	<script>
	function clearError(){
		document.getElementById('searchErr').innerHTML = '';
		document.getElementById('searchErrDiv').style.display = 'none';
	}

	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	function checkMail(){	
		
		//var email = document.getElementById('user-email').value;
		var emailUN = document.getElementById('user-emailUN').value;
		//var firstname = document.getElementById('user-firstname').value;
		//var lastname = document.getElementById('user-lastname').value;
		//var username = document.getElementById('user-username').value;
		//var phone = document.getElementById('user-phone').value;

		//if(email == "" && firstname == "" && lastname == "" && username == "" && phone == ""){
		if(emailUN == ""){
			document.getElementById('searchErrDiv').style.display = 'block';
			document.getElementById('searchErr').innerHTML = 'Please enter any one of Username or Email';
			return false;
		}else{
		
			/*if(email != ""){
				var emailValidateCheck = validateEmail(email);

				if(!emailValidateCheck){

					document.getElementById('searchErrDiv').style.display = 'block';
					document.getElementById('searchErr').innerHTML='Please enter valid Email-id';
					return false;
				}	
			}*/

			//var values = {'action':'searchuser', 'email':email, 'firstname':firstname, 'lastname':lastname, 'username':username, 'phone':phone};
			var values = {'action':'searchuser', 'emailUN':emailUN};
			
			jQuery.ajax({url: 'uservalidation_check.php',
			type: 'post',
			data: values,
			success: function(response){  
					
					if(response == ""){	
						document.getElementById('usernotfoundiv').style.display = 'block';						
						document.getElementById('usernotfoundiv').innerHTML = '<b>No subscriber found</b>';
					}else{
						
						document.getElementById('usernotfoundiv').innerHTML = '';
						document.getElementById('usernotfoundiv').style.display = 'none';
												
						var obj = jQuery.parseJSON(response);	
						if(obj.ID != ""){

							document.getElementById('subscriber').value = obj.ID;
							document.getElementById('userdiv').innerHTML = '<Br><b>Subscriber: </b> ' +obj.user_email+'('+obj.user_login+')';	

							var values1 = {'action':'useractive', 'user_id':obj.ID};
			
							jQuery.ajax({url: 'uservalidation_check.php',
							type: 'post',
							data: values1,
							success: function(res){
							if(res==0) 
								{
								

								var values2 = {'action':'userpending', 'user_id':obj.ID};
			
								jQuery.ajax({url: 'uservalidation_check.php',
								type: 'post',
								data: values2,
								success: function(re){
								if(re==0) 
								{
								
								
								document.getElementById('paypal_form').style.display = 'block';
								document.getElementById('emailchecking').style.display = 'none';
								document.getElementById('selection').style.display = 'block';
								a1=document.getElementById("paypal_form").style.display;
								//divWide(a1);
								document.getElementById('subscribed-div').style.display="none";
								document.getElementById('sub-pending-div').style.display="none";
								document.getElementById('subscribed').innerHTML ="";
								document.getElementById('sub-pending').innerHTML ="";
								document.getElementById('user-emailUN').value="";
								//document.getElementById('user-email').value="";
								//document.getElementById('user-username').value="";
								//document.getElementById('user-phone').value="";
								}
								else
								{
								document.getElementById('paypal_form').style.display = 'none';
								document.getElementById('sub-pending-div').style.display = 'block';
									if(re==document.getElementById("AID").value)
									{
									document.getElementById('sub-pending').innerHTML = '<b>This user already in your Payment Pending List.<br /> Remove and Try Again. ';									}
									else
									{
									document.getElementById('sub-pending').innerHTML = '<b>This user already in other Agent\'s Payment Pending List.<br /> You can\'t proceed.';									}
									
								document.getElementById('subscribed').innerHTML ="";
								document.getElementById('subscribed-div').style.display = 'none';
								}
								}
							});
							
								}
							else
								{document.getElementById('paypal_form').style.display = 'none';
								document.getElementById('subscribed-div').style.display = 'block';
								document.getElementById('subscribed').innerHTML = '<b>This user already subscribed. You cant proceed with him/her.</b>';		document.getElementById('sub-pending-div').style.display = 'none';	
								document.getElementById('sub-pending').innerHTML ="";
								}
							}
							});
						}
						
					}
				}
			});				
			
		}
	}
	</script>

<script>

function delConfPR()
{

swal({   title: " ",   text: "Do you really want to remove this Pending Remittance?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Delete",   cancelButtonText: "Cancel",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) {   return true;     } else {      return false;   } });

}

jQuery( "#user-email, #user-username,#user-emailUN" ).click(function() {
  
  jQuery("#sub-pending").html("");
jQuery("#subscribed").html("");
jQuery("#usernotfoundiv").html("");
jQuery("#user-emailUN").val("");

});

jQuery( "#user-email, #user-username,#user-emailUN" ).focus(function() {
  
  jQuery("#sub-pending").html("");
jQuery("#subscribed").html("");
jQuery("#usernotfoundiv").html("");
jQuery("#user-emailUN").val("");

});


var sum=colSum("pending_list",3);

jQuery("#total_amount1").html(sum);
jQuery("#total_amount").val(sum);
//alert(s);
function colSum(tableId, colNumber)
{
  // find the table with id attribute tableId
  // return the total of the numerical elements in column colNumber
  // skip the top row (headers) and bottom row (where the total will go)
	var debugScript=false;	
  var result = 0;
		
  try
  {
    var tableElem = window.document.getElementById(tableId); 	
	   
    var tableBody = tableElem.getElementsByTagName("tbody").item(0);
    var i;
    var howManyRows = tableBody.rows.length;
	//alert(howManyRows);	
    for (i=0; i<howManyRows; i++) // skip first and last row (hence i=1, and howManyRows-1)
    {
       var thisTrElem = tableBody.rows[i];
	//alert(thisTrElem);
       var thisTdElem = thisTrElem.cells[colNumber];			
       var thisTextNode = thisTdElem.childNodes.item(0);
       if (debugScript)
       {
          window.alert("text is " + thisTextNode.data);
       } // end if

       // try to convert text to numeric
       var thisNumber = parseFloat(thisTextNode.data);
       // if you didn't get back the value NaN (i.e. not a number), add into result
       if (!isNaN(thisNumber))
         result += thisNumber;
	 } // end for
		 
  } // end try
  catch (ex)
  {
     //window.alert("Exception in function computeTableColumnTotal()\n" + ex);
     result = 0;
  }
  finally
  {
     return Math.round(result * 100) / 100;
  }
	
}



function agentTabs(evt, cityName) {
    // Declare all variables

	//alert(evt);
	//alert(cityName);
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

	ele=document.getElementsByClassName("tabcontent mx show");
	 for (i = 0; i < ele.length; i++) {
        ele[i].setAttribute("class","tabcontent mx hide");;
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
	document.getElementById(cityName).setAttribute("class","tabcontent mx show");
    evt.currentTarget.className += " active";

}


function callConfirmationCR(url){
		
		var txt1="delete";
		var txt2="credit request";
		var btn="Delete";
		my_alert(txt1,txt2,btn,url);
		//var ans = confirm("Sure, do you want to delete this credit request?");
		//if(ans){
		//	window.location.href = url;
		//}
	}

function payConfirmationCR(url){

		var txt1="process";
		var txt2="credit request";
		var btn="Pay";
		my_alert(txt1,txt2,btn,url);

		//var ans = confirm("Sure, do you want to process this credit request?");
		//if(ans){
		//	window.location.href = url;
		//}
	}


function callConfirmationPR(url){

		//var ans = confirm("Sure, do you want to delete this Pending Remittance?");
		var txt1="delete";
		var txt2="Pending Remittance";
		var btn="Delete";
		my_alert(txt1,txt2,btn,url);
					
		
	}


function confirmDelPR(PRid)
{
//alert(PRid);
  //  e.preventDefault();

    // do lots of stuff
swal({
 		title:' ', 
  text: 'Do you really want to remove this Pending Remittance?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Delete",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "PRids").val(PRid);
jQuery('#frm1').append(jQuery(input));
 jQuery("#frm1").submit();

}); 
   

/* if(confirm('Do you really want to delete this Pending Remittance?'))
{
return true;
}
else
return false;
*/

}

function confirmDelCR(delCR,CRid)
{

 swal({
 		title:' ', 
  text: 'Do you really want to remove this Subscribe Request?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Delete",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "CRids").val(CRid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delCRs").val(delCR);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 
   

/* if(confirm('Do you really want to delete this Pending Remittance?'))
{
return true;
}
else
return false;
*/

}

function confirmPayCR(payCR,CRemail,CRid)
{

 swal({
 		title:' ', 
  text: 'Do you want to process for Payment?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Proceed",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "pCRids").val(CRid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "payCRs").val(payCR);
var input3 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "CRemails").val(CRemail);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
jQuery('#frm2').append(jQuery(input3));
 jQuery("#frm2").submit();

}); 
   

/* if(confirm('Do you really want to delete this Pending Remittance?'))
{
return true;
}
else
return false;
*/

}


function my_alert(txt1,txt2,btn,url)
{

return swal({
 		title:' ', 
  text: 'Do you want to '+txt1+' this '+txt2+'?',
  type: 'warning',
  
  showCancelButton: true, allowEscapeKey: false,
  confirmButtonColor: '#DD6B55',
  confirmButtonText: btn,
  cancelButtonText: 'Cancel',
  closeOnConfirm: true
},function(){ window.location.href=url;}); 
}


function my_alert2(txt1,txt2,btn,url)
{

return swal({
 		title:' ', 
  text: 'Do you really want to '+txt1+' this '+txt2+'?',
  type: 'warning',
  
  showCancelButton: true, allowEscapeKey: false,
  confirmButtonColor: '#DD6B55',
  confirmButtonText: btn,
  cancelButtonText: 'Cancel',
  closeOnConfirm: true
},function(){ }); 
}

setTimeout(function(){
jQuery(".msg").slideUp();
},3000);


/* var rows = jQuery('#sub_list tr').not('thead tr');
jQuery('#search').keyup(function() {
    var val = jQuery.trim(jQuery(this).val()).replace(/ +/g, ' ').toLowerCase();

    rows.show().filter(function() {
        var text = jQuery(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();

var x = document.getElementById("sub_list").rows.length;
//alert("x"+x);
var cnt=0;

for(i=0;i<x;i=i+1)
{
 var y=document.getElementById("sub_list").rows[i].style.display;

if(y=="none")
cnt=cnt+1;
}

//alert("cnt:"+cnt);
if(x==(cnt+1))
{
//document.getElementById("no_res").innerHTML="NO SEARCH RESULTS";
jQuery("#no_res").html("NO SEARCH RESULTS");
jQuery("#no_res_div").show();
}
else
{
jQuery("#no_res").html("");
jQuery("#no_res_div").hide();
}

});*/


a1=document.getElementById("paypal_form").style.display;

divWide(a1);
function divWide(a1){
if(a1=="none")
{

document.getElementById("emailchkdiv").setAttribute("style","float: none; padding: 0px 10px;max-width: 100% !important; width: 50%;");
document.getElementById("emailchecking").setAttribute("style","float: left;    width: 100%;    padding: 0px 10px; display:none;border: 1px solid #ddd; box-shadow: 0 1px 2px -1px #ccc;");

}
else
{

document.getElementById("emailchkdiv").setAttribute("style","float: left; padding: 0px 10px;max-width: 100% !important; width: 100%;");
document.getElementById("emailchecking").setAttribute("style","float: left;  width: 50%; padding: 0px 10px; display:none;border: 1px solid #ddd; box-shadow: 0 1px 2px -1px #ccc;");

}
}


if(jQuery("#no_res").html()=="")
{jQuery("#no_res_div").hide();}




function findRows(table,searchText1,col1,searchText2,col2,searchText3,col3,searchText4,col4,searchText5,col5) {

//var table=document.querySelector("#sub_list > tr:visible");
//var table=$('#sub_list tr:visible');
    var rows = table.rows,
        r = 0,
        found = false,
        anyFound = false;
       // var found1,found2,found3;
        //alert(rows.length);
        //alert(searchText1+"in"+col1);
       // alert(searchText2+"in"+col2);
       // alert(searchText3+"in"+col3);
       //  alert(searchText4+"in"+col4);
       // alert(searchText5+"in"+col5);
        //alert(searchText1+"in"+col1);

//alert($('#sub_list tr:visible').length);
	
  var found1=false,found2=false,found3=false,found4=false,found5=false;
  
    for (; r < rows.length; r += 1) {
        row = rows.item(r);
          
     // var i=column;
       // alert(searchText1+"in col:"+col1+"  row::"+r);
        
        if(col1!=99)
        found1 = (row.cells.item(col1).textContent.toLowerCase().indexOf(searchText1.toLowerCase().trim()) !== -1);
        else
        found1=true;
         if(col2!=99)
          found2 = (row.cells.item(col2).textContent.toLowerCase().indexOf(searchText2.toLowerCase().trim()) !== -1);else
        found2=true;
          if(col3!=99)
            found3 = (row.cells.item(col3).textContent.toLowerCase().indexOf(searchText3.toLowerCase().trim()) !== -1);else
        found3=true;
          if(col4!=99)
          found4 = (row.cells.item(col4).textContent.toLowerCase().indexOf(searchText4.toLowerCase().trim()) !== -1);else
        found4=true;
          if(col5!=99)
            found5 = (row.cells.item(col5).textContent.toLowerCase().indexOf(searchText5.toLowerCase().trim()) !== -1);else
        found5=true;
            
            found=found1 && found2 && found3 && found4 && found5;
        anyFound = anyFound || found;
//alert(found);
			//if(row.style.display=="none")
      	//	found=false;
        row.style.display = found ? "table-row" : "none";
       }
        
	   
	if(col1==99 && col2==99 && col3==99 && col4==99 && col5==99)
	{
		for (; r < rows.length; r += 1) {
			row = rows.item(r);
			 row.style.display = "table-row" ;}
	}
    //document.getElementById('no_res').style.display = anyFound ? "none" : "block";
var x = document.getElementById("sub_list").rows.length;
//alert("x"+x);
var cnt=0;

for(i=0;i<x;i=i+1)
{
 var y=document.getElementById("sub_list").rows[i].style.display;

if(y=="none")
cnt=cnt+1;
}

//alert("cnt:"+cnt);
if(x==(cnt))
{
document.getElementById("no_res").innerHTML="NO SEARCH RESULTS";
jQuery("#no_res").html("NO SEARCH RESULTS");
jQuery("#no_res_div").show();
}
else
{
jQuery("#no_res").html("");
jQuery("#no_res_div").hide();
}

}

function performSearch() {
    var searchText1 = document.getElementById('searchUN').value,
        searchText2 = document.getElementById('searchEM').value,
        searchText3 = document.getElementById('searchFN').value,
        searchText4 = document.getElementById('searchLN').value,
        searchText5 = document.getElementById('searchPH').value,
        targetTable = document.getElementById('sub_list');
	var searchText=[],col;
	
	if(searchText1!="")
	{
	searchText[0]=searchText1;
	col1=0;
	}
  else{col1=99;}
  
  
	if(searchText2!="")
	{
	searchText[1]=searchText2;
	col2=1;
	}
  else{col2=99;}
  
  
 if(searchText3!="")
	{
	searchText[2]=searchText3;
	col3=2;
	}
  else{col3=99;
}

if(searchText4!="")
	{
	searchText[3]=searchText4;
	col4=3;
	}
  else{col4=99;}
  
  
 if(searchText5!="")
	{
	searchText[4]=searchText5;
	col5=4;
	}
  else{col5=99;
}
  	

	//alert(searchText);
    findRows(targetTable,searchText1,col1,searchText2,col2,searchText3,col3,searchText4,col4,searchText5,col5);

}

//document.getElementById("search").onclick = performSearch;
document.getElementById("searchUN").onkeyup = performSearch;
document.getElementById("searchEM").onkeyup = performSearch;
document.getElementById("searchFN").onkeyup = performSearch;
document.getElementById("searchLN").onkeyup = performSearch;
document.getElementById("searchPH").onkeyup = performSearch;

function other_user_btn()
{
document.getElementById('user-emailUN').value="";
jQuery("#sub-pending").html("");
jQuery("#subscribed").html("");
jQuery("#usernotfoundiv").html("");
jQuery("#selection").hide();
var x=document.getElementById('emailchecking');
if(x.style.display=="none")
x.style.display='block';
else
x.style.display='none';



}


var z=document.getElementById('selection');
if(z.style.display=="none")
z.style.border='none';
else
z.style.display='border: 1px solid #ddd;';


</script>
	
<?php
if($userPR==1)
{
echo '<script>
		document.getElementById("SL").setAttribute("class","tablinks");
		document.getElementById("PR").setAttribute("class","tablinks active");
		document.getElementById("CR").setAttribute("class","tablinks");
		document.getElementById("CreditRequest").setAttribute("class","tabcontent mx hide");
		document.getElementById("PendingRemittance").setAttribute("class","tabcontent mx show");
		document.getElementById("SubscriberList").setAttribute("class","tabcontent mx hide");
</script>';

	if($CR_to_PR==1)
	{
	echo "<script>jQuery('#user-emailUN').val('".$email_CR."');
				jQuery('#validate').click();</script>";
	}

	
}

if($added_hide==1)
{
echo '<script>jQuery("#paypal_form").hide();
jQuery("#emailchecking").hide();
jQuery("#selection").hide();
</script>';

}
?>

	<?php	

	include("footer-agent.php");
	
}
?>

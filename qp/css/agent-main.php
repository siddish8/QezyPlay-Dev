<?php

include("agent-config.php");

//Access your POST variables
$agent_id = $_SESSION['agentid'];
//echo "id:".$agent_id;
//Unset the useless session variable


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
	
	include("header.php");
	?>
		
	<div id="content" role="main" style="min-height:500px;">
		<div class="xoouserultra-wrap xoouserultra-login">
			<div class="xoouserultra-inner xoouserultra-login-wrapper">
				<div class="xoouserultra-main">
					<div id="emailchecking">
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type">
								<span>Enter subscriber's E-mail: </span>
							</label>
							<div class="xoouserultra-field-value">
								<input class="xoouserultra-input" required="required" name='user-mail' id='user-mail' onclick='clearError()'>
								<div id='emailErr' style="color:red;padding-left:10px;"></div>
							</div>
						</div>	
						
						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label class="xoouserultra-field-type">&nbsp;</label>
							<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<input type="button" onclick='checkMail();' name='login' value="Validate" class="xoouserultra-button xoouserultra-login" name="xoouserultra-login" id="Login">
							</div>
						</div>
						<div class="xoouserultra-clear"></div>
					</div>

					<div class="xoouserultra-clear"></div>
							


					<div id='emailSuccess' style='color:black;display:none;'></div><br>
					<div class="xoouserultra-clear"></div>

					<form class='paypal' action='Paypal/payments.php' method='post' id='paypal_form' target='_self' style='display:none;'>				
						<input type='hidden' name='cmd' value='_xclick'/>
						<input type='hidden' name='no_note' value='1'/>
						<input type='hidden' name='lc' value='IND'/>
						<input type='hidden' name='currency_code' value='USD'/>
						<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest'/>
						<input type='hidden' name='first_name' value=''/>
						<input type='hidden' name='last_name' value=''/>
						<input type='hidden' name='subscriber' id='sub' />
	
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type">
								<span>Bouquet: </span>
							</label>
							<div class="xoouserultra-field-value">
								<select style="color:black;" name='bouquet' required><option value=''>Select Bouquet</option><option value='1'>Bangla Bouquet</option></select>								
							</div>
						</div>	

						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label for="user_login" class="xoouserultra-field-type">
								<span>Bouquet: </span>
							</label>
							<div class="xoouserultra-field-value">
								<select style="color:black;" name='item_number' required><option value=''> --Select Plan-- </option>								
								<?php
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
							<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<input type='submit' name='submit' value='Pay' id='Paypal' />
							</div>
						</div>
						<div class="xoouserultra-clear"></div>

						<input type='hidden' name='payer_email' value=''  />	
						<input type='hidden' name='agent_id' value="<?php echo $agent_id; ?>" / >
					</form>
				</div>
			</div>
		</div>


		<div class="pmpro_box" id="pmpro_account-invoices">
			<h2 style="text-align:center">List of Subscriptions</h2>
			<table style="overflow-x:auto;min-width:50%;max-width:80% !important;" width="80%" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th>Subscriber</th>
						<th>Bouquet</th>
						<th>Plan</th>
						<th>Amount</th>
						<th>Start date</th>
						<th>End date</th>
						<th>Paid date</th>
					</tr>
				</thead>
				<tbody>
				<?php

				$sql3 = "SELECT * FROM agent_vs_subscription_credit_info where agent_id=?";		
				try {
					$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
					$stmt3->execute(array($agent_id));
					$result3 = $stmt3->fetchall(PDO::FETCH_ASSOC);
			 		$stmt3 = null;
					if(count($result3) > 10){
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
								echo "<tr class='ui-sortable-handle'>			
										<td>".$user_login." (".$user_email.")</td>
										<td>Bangla Bouquet</td>
										<td>".$planName."</td>
										<td>".$amount."</td>
										<td>".$start."</td>
										<td>".$end."</td>
										<td>".$paid."</td>
									</tr>";
							}

						}
					}else{
						echo "<tr style='' class='ui-sortable-handle'><td colspan='7'><center>No subscription found</center></td></tr>";
					}	
		
				}catch (PDOException $e){
					print $e->getMessage();
				}
				?>					
				</tbody>
			</table>
		</div>		
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
							document.getElementById('emailErr').style.display='block';
							document.getElementById('emailErr').innerHTML='Entered Email-id does not exists';
						}else if(response==2){							
							document.getElementById('emailErr').style.display='block';document.getElementById('emailErr').innerHTML='Something wrong! Contact Admin';
						}else{
							document.getElementById('emailchecking').style.display='none';
							document.getElementById('emailSuccess').innerHTML='<b>Subscriber:</b> '+mail;	
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
	
	<?php	

	include("footer.php");
	
}
?>

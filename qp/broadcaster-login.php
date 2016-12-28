<?php

include("db-config.php");

$username = $password = $emailError = $authError = '';

if((int)$_SESSION['customerid'] > 0){	
	//Redirect the user to the next page
	header('LOCATION: broadcaster-main.php'); 
	exit;
}
	
if(isset($_POST['login'])){
	
 	$email = trim($_POST['email']); $authkey = trim($_POST['authkey']);
	$sql1 = "SELECT email FROM customer_info";		
	try {
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute();
		$result1 = $stmt1->fetchall(PDO::FETCH_ASSOC);

		$stmt1 = null;

		$match=0;
		//echo $result1;
		//print_r($result1);
		foreach($result1 as $em){
			if($em['email'] == $email){$match=1;}
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}

	if($match==0){		
		$emailError = 'Entered Email-id does not exists';
	}else{
		$sql2 = "SELECT id,authkey,channel_id FROM customer_info WHERE email = ?";		
		try {
			$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt2->execute(array($email));
			$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

			$stmt2 = null;

			$customer_id = $result2['id'];
			$authkey_db = $result2['authkey'];
			$channel_id = $result2['channel_id'];
			
		}catch (PDOException $e){
			print $e->getMessage();
		}

		if($email === $email && $authkey === $authkey_db){
		
			//Dump your POST variables
			$_SESSION['customerid'] = $customer_id;
			$_SESSION['channelid'] = $channel_id;

			//Redirect the user to the next page
			header('LOCATION: broadcaster-main.php'); exit;

		}


		if($authkey !== $authkey_db)  {
			$authError = 'Please enter correct auth key'; 
		}
	}
}

include("header-broadcaster.php");
?>
<style>

/* @media (min-height: 500px)
.xoouserultra-wrap.xoouserultra-login {
    margin: 6.5% auto !important;
}

@media (max-width: 600px){
.xoouserultra-wrap.xoouserultra-login {
    margin: 40% auto;
}
}

@media (min-width: 601px) and (max-width:1023){
.xoouserultra-wrap.xoouserultra-login {
    margin: 25% auto !important;
}
}

*/
@media (min-width: 1024px){
#content{
    margin-bottom: 80px;
    margin-top: 80px;
}

/*.xoouserultra-wrap.xoouserultra-login {
    margin: 12.5% auto !important;
}*/
}


footer{position: fixed;
    bottom: 0px;
    width: 100%;}

label.xoouserultra-field-type {
     margin: unset !important; 
}
</style>
<div id="content" role="main">
	<div align="center" class="xoouserultra-wrap xoouserultra-login">
		<div class="xoouserultra-inner xoouserultra-login-wrapper">
			<div class="xoouserultra-main">
				<form id="xoouserultra-login-form-1" method='post' onsubmit="return callsubmit();">
					<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						<label for="user_login" class="xoouserultra-field-type">
							<i class="fa fa-user"></i>
							<span>Email </span>
						</label>
						<div class="xoouserultra-field-value">
							<input class="xoouserultra-input" alt="" onfocus='clearError()' onclick='clearError()' type='text' title="" value="<?php echo $email; ?>" id='email' name='email'>
							<div id='emailerror' style='color:red;padding-left:10px;'";><?php echo $emailError; ?></div>
						</div>
					</div>		
					<div class="xoouserultra-clear"></div>
					<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						<label for="login_user_pass" class="xoouserultra-field-type">
							<i class="fa fa-lock"></i>
							<span>Auth Key</span>
						</label>
	
						<div class="xoouserultra-field-value">
							<input title="" alt="" onfocus='clearError()' onclick='clearError()' type='password' value="" id='authkey' name='authkey'>					
							<div id='keyerror' style='color:red;padding-left:10px;'";><?php echo $authError; ?></div>
						</div>
					</div>
					<div class="xoouserultra-clear"></div>
					<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						<label class="xoouserultra-field-type">&nbsp;</label>
						<div class="xoouserultra-field-value" style="padding-left: 10px;">
							<input type="submit" name='login' value="Log In" class="" name="xoouserultra-login" id="Login">
						</div>
					</div>
					<div class="xoouserultra-clear"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

function clearError(){	
	document.getElementById("emailerror").innerHTML="";
	document.getElementById("keyerror").innerHTML="";
}

function callsubmit(){	
	
	var email = jQuery("#email").val();
	var authkey = jQuery("#authkey").val();

	if(email == ""){
		jQuery("#emailerror").html("Please enter email");
		return false;		
	}

	if (!validateEmail(email)) {
				
			jQuery("#emailerror").html("Please enter valid emaild address");
			return false;	
		}
	
	if(authkey == ""){
		jQuery("#keyerror").html("Please enter auth key");
		return false;		
	}
	if(email != ""){
		if (validateEmail(email)) {
			 if(authkey != ""){
				return true;		
			}
			
		} else {
			jQuery("#emailerror").html("Please enter valid emaild address");
			return false;	
		}
	}
	return false;
}
</script>
<?php

include("footer-broadcaster.php");
?>

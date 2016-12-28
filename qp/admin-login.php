<?php

include("db-config.php");

$username = $password = $emailError = $authError = '';

if((int)$_SESSION['adminid'] > 0){	
	//Redirect the user to the next page
	header('LOCATION: admin-main.php'); 
	exit;
}
	
if(isset($_POST['login'])){
	
 	$email = trim($_POST['email']); $authkey = trim($_POST['authkey']);
	$sql1 = "SELECT name FROM admin_portal_users";		
	try {
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute();
		$result1 = $stmt1->fetchall(PDO::FETCH_ASSOC);

		$stmt1 = null;

		$match=0;
		//echo $result1;
		//print_r($result1);
		foreach($result1 as $em){
			if($em['name'] == $email){
				$match=1;
			}
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}

	if($match==0){		
		$emailError = 'Entered Username does not exists';
	}else{
		$sql2 = "SELECT id,password,name,admin_level FROM admin_portal_users WHERE name = ?";		
		try {
			$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt2->execute(array($email));
			$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

			$stmt2 = null;

			$customer_id = $result2['id'];
			$authkey_db = $result2['password'];
			$customername = $result2['name'];
			$adminlevel=$result2['admin_level'];



		}catch (PDOException $e){
			print $e->getMessage();
		}

		if($email === $email && $authkey === $authkey_db){
		
			//Dump your POST variables
			$_SESSION['adminid'] = $customer_id;
			$_SESSION['adminname'] = $customername;
			$_SESSION['adminlevel'] = $adminlevel;

			//Redirect the user to the next page
			header('LOCATION: admin-main.php'); exit;

		}


		if($authkey !== $authkey_db)  {
			$authError = 'Please enter correct password'; 
		}
	}
}

include("header-admin.php");
?>
<style>
@media (min-width: 1200px){
#content{
    margin-bottom: 80px;
    margin-top: 80px;
}
footer{position: fixed;
    bottom: 0px;
    width: 100%;}
#bottom-nav_1{position: fixed;
    bottom: 165px;
    width: 100%;}
/*.xoouserultra-wrap.xoouserultra-login {
    margin: 12.5% auto;
}*/
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
							<span>Username </span>
						</label>
						<div class="xoouserultra-field-value">
							<input class="xoouserultra-input" alt="" onclick='clearError()' onfocus='clearError()' type='text' title="" value="<?php echo $email; ?>" id='email' name='email'>
							<div id='emailerror' style='color:red;padding-left:10px;'";><?php echo $emailError; ?></div>
						</div>
					</div>		
					<div class="xoouserultra-clear"></div>
					<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						<label for="login_user_pass" class="xoouserultra-field-type">
							<i class="fa fa-lock"></i>
							<span>Password</span>
						</label>
	
						<div class="xoouserultra-field-value">
							<input title="" alt="" onclick='clearError()' onfocus='clearError()' type='password' value="" id='authkey' name='authkey'>					
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
		jQuery("#emailerror").html("Please enter username");	
		return false;	
	}
	if(authkey == ""){
		jQuery("#keyerror").html("Please enter password");		
		return false;
	}

	if(email != "" && authkey != ""){
		return true;		
	}
	return false;
}
</script>
<?php

include("footer-admin.php");
?>

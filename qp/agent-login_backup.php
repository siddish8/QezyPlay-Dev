<?php
ob_start();
session_start(); 

include("agent-config.php");

$username = $password = $userError = $passError = '';

if($_SESSION['POST'] > 0){	
	//Redirect the user to the next page
	header('LOCATION: agent-main.php'); 
	exit;
}
	

if(isset($_POST['login'])){
	
 	$email = trim($_POST['email']); $authkey = trim($_POST['authkey']);
	$sql1 = "SELECT email FROM agent_info";		
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
		$sql2 = "SELECT id,authkey FROM agent_info WHERE email = ?";		
		try {
			$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt2->execute(array($email));
			$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

			$stmt2 = null;

			$agent_id=$result2['id'];
			$authkey_db=$result2['authkey'];
		}catch (PDOException $e){
			print $e->getMessage();
		}

		if($email === $email && $authkey === $authkey_db){
		
			//Dump your POST variables
			$_SESSION['POST'] = $agent_id;

			//Redirect the user to the next page
			header('LOCATION: agent-main.php'); exit;

		}


		if($authkey !== $authkey_db)  {
			$authError = 'Invalid key'; 
		}
	}
}

include("header.php");

echo "<div align='center' class='row' style='margin:0 auto;padding:12.5% 10px;background-color:white !important;height:90px;color:#fff !important;max-width:1500px;'>
	<form name='input' action='{$_SERVER['PHP_SELF']}' method='post'>
		<table>
			<tr><td> <label style='font-size: 15px;' for='email'>Email</label></td><td><input onclick='document.getElementById(\"emailerror\").innerHTML=\"\"' type='email' value='$email' id='email' name='email' required /></td></tr>
			<tr><td></td><td><div id='emailerror' class='error' style='color:red;'>$emailError</div></td></tr>
			<tr><td><label style='font-size: 15px;'  for='authkey'>Auth Key</label></td><td><input onclick='document.getElementById(\"keyerror\").innerHTML=\"\"' type='password' value='$authkey' id='authkey' name='authkey' required /></td></tr>
			<tr><td></td><td><div id='keyerror' class='error' style='color:red;'>$authError</div></td></tr>
			<tr><td></td><td><input type='submit' value='Login' name='login' /></td></tr>
		</table>
	</form>
</div>";

include("footer.php");
?>

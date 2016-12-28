<?php

$agentid = $_GET['go'];

include("../db-config.php");

if(isset($_POST['home'])){
	session_start();
	$_SESSION['agentid'] = $_POST['agentid'];

	//Redirect the user to the next page
	
	header('LOCATION: ../agent-home.php');
	//header('LOCATION: ../agent-subscribers-list.php');
	 die();
	die();
}

include("../header-agent.php");
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
    bottom: 50px;
    width: 100%;}
</style>
<div id="content" role="main">
	<div class="xoouserultra-wrap xoouserultra-login">
		<div class="xoouserultra-inner xoouserultra-login-wrapper">
			<div class="xoouserultra-main">
				<h1 align="center">Payment Success</h1><br>
				<p align="center">Your payment was successful. Thank you.</p><br>
				<form method="post" align="center"; style="margin: 0 auto; ">
					<input type="hidden" name="agentid" value="<?php echo $agentid; ?>">
					<center><input type="submit" value="Go HOME" name="home"></center>
				</form>
			</div>
		</div>
	</div>
</div>


<?php
include("../footer-agent.php");
?>

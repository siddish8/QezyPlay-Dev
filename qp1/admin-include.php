<?php


if(isset($_GET['logout'])){

	unset($_SESSION['adminid']);

	header('Location:login.php');
	exit;
}



if($_SESSION['adminid'] == ""){
	
	header('Location:login.php');
	exit;
	
}
	

?>

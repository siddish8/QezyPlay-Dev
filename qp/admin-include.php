<?php


if(isset($_GET['logout'])){

	unset($_SESSION['adminid']);

	header('Location: admin-login.php');
	exit;
}



if((int)$_SESSION['adminid'] <= 0){
	
	header('Location: admin-login.php');
	exit;
	
}
	

?>

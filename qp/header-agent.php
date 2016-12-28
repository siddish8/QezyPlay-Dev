<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
	<head>
		<meta http-equiv='content-type' content='text/html;charset=utf-8' />
		<title>Agent Portal</title>	
		<link rel='stylesheet' href='<?php echo SITE_URL;?>/qp/css/globals.css'>
		<!--link rel='stylesheet' href='css/grid.css'!-->
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>/qp/css/Qp.css"> 
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>/qp/css/ag.css"> 
		<link rel="stylesheet" href="<?php echo SITE_URL;?>/qp/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>/wp-content/themes/truemag/style.css?ver=4.5.3"> 
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>/wp-content/plugins/users-ultra/templates/basic/css/default.css?ver=4.5.3">

		<link rel='stylesheet' id='fontawsome-css-css' href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css?ver=3.1.0' type='text/css' media='all'>
		<link rel='stylesheet' type='text/css' href="<?php echo SITE_URL; ?>/qp/sweetalert-master/dist/sweetalert.css">
		<script language='javascript' type='text/javascript' src="<?php echo SITE_URL; ?>/qp/js/jquery.js"></script>
		
	</head>
	<style>
	a{ color: red; font-weight: bold;}
	input[type="text"], input[type="password"],select{color:black !important;}
	</style>
	<body>
	<script src="<?php echo SITE_URL;?>/qp/js/bootstrap.min.js"></script>
	<script src="<?php echo SITE_URL;?>/qp/sweetalert-master/dist/sweetalert.min.js"></script>
		<!-- <div class='row' style='padding:0px 10px;background-color:#000 !important;color:#fff !important;max-width:1500px;'>-->
			<div class='row' style='padding:0px 10px;background-color:#000 !important;color:#fff !important;'>
			<div class='twelve columns'>
				<header>
					<div class='three columns'>
						<div id='logo'>
							<img src="<?php echo SITE_URL;?>/qp/qezyplay-logo.png" alt='QezyPlay' height='60' width='160'>
						</div>
					</div>
					<div class='three columns'>
						<div id='hedtext'>							
							<center><h4 style='color:#fff !important;'>AGENT PORTAL</h4></center>
						</div>
					</div>					
					<div class='three columns'>
						<div id='hedtext'>
							<h4 style='color:#fff !important;'><center>
								<?php if($_SESSION['agentid'] > 0){ ?>
									Welcome <?php echo $agent_name; ?>,&nbsp;&nbsp;<span><a id='logout' href='?logout=true'>Logout</a></span>
								<?php }?>
							</center></h4>
						</div>
					</div>					
				</header>
			</div>
		</div>
		<div class='clear'></div>		

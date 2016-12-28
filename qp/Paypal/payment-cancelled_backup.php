<?php

$agentid=$_GET['go'];

if(isset($_POST['home']))
{
session_start();
$_SESSION['POST'] = $_POST['input'];

//Redirect the user to the next page
header('LOCATION:../agent-main.php'); die();
}

echo '<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Cancelled</title>
	<link rel="stylesheet" href="../globals.css">
	<link rel="stylesheet" href="../grid.css">
	<link rel="stylesheet" href="../ag.css">
	<link rel="stylesheet" id="fontawsome-css-css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css?ver=3.1.0" type="text/css" media="all">
</head>
<body>
	<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-1.6.1.js"></script>

<div class="row" style="padding:0px 10px;background-color:#000 !important;height:90px;color:#fff !important;max-width:1500px;">

	<div class="twelve columns">
		<header>
						<div class="four columns">
							<div id="logo">
							<img src="http://ideabytestraining.com/demoqezyplay/qp/qezyplay-logo.png" alt="QezyPlay" height="73" width="173">
							</div>
						</div>
						<div class="four columns">
							<div id="hedtext" style="margin-top:3%;">								
								<h3 style="color:#fff !important;margin-top: -3%;margin-left: 41%;">AGENT PORTAL</h3>
							</div>
						</div>

						<div class="four columns">
							<div id="hedtext">
								
							</div>
						</div>
						
				  </header>
	</div>
</div>
	<h1 align="center">Payment Cancelled</h1>
	<p align="center">Your payment was cancelled.</p>
	<form method="post" align="center"; style="margin: 0 auto; "><input type="hidden" name="input">'.$agentid.'</input><input type="submit" name="home" value="Go HOME" /></form>
	<!--<script>window.setTimeout(function(){ window.location = "../qp/agent-main.php"; },5000);</script>-->
<div id="footernew" style="width:100%;background-color:black !important;font-size: 15px; position:fixed;">
		<div class="container">
			<div class="row">
				<div class="twelve columns">
<div class="copyright col-md-6 ver_sep" style="width:30%; color: white;   margin-top: 2%;margin-bottom: -1%;
    margin-left: 4%;">Copyright © 2016  <a style="color:white;" target="_blank" href="http://www.qezymedia.com" target="_blank">Qezy Media</a> All rights reserved.</div>
					<div id="copyright">
						<!-- <img src="../qp/ib_newlogo.jpeg" alt="logo" height="63" width="150"> -->
					</div>
<div id="other_links" align="center" style="width:40%;list-style-type: none;margin-top: -2%;float: right;display: inline-flex;">
<ul style="display:inline-flex;">
<li style="padding-left: 12px;line-height: 2em;position: relative;"><a style="color:white;" target="_blank" href="http://ideabytestraining.com/demoqezyplay/about-us/">About Us</a></li>
<li style="padding-left: 12px;line-height: 2em;position: relative;"><a style="color:white;"target="_blank"  href="http://ideabytestraining.com/demoqezyplay/feedback/">Feedback</a></li>
<li style="padding-left: 12px;line-height: 2em;position: relative;"><a style="color:white;" target="_blank" href="http://ideabytestraining.com/demoqezyplay/terms-of-service/">Terms of Service</a></li>
<li style="padding-left: 12px;line-height: 2em;position: relative;"><a style="color:white;" target="_blank" href="http://ideabytestraining.com/demoqezyplay/privacy-policy/">Privacy Policy</a></li>
</ul>
</div>
					<div id="social_icons" class="ver_sep" align="center" style="width:10%;list-style-type: none;margin-top: -2%;
display: inline-flex;
margin-left: 40%;"><ul class="social" style="display:inline-flex;">
<li style="padding-left: 12px;font-size:25px;position: relative;"><a style="color:white;" href="https://www.facebook.com/QezyPlay/"><i class="fa fa-facebook-square"></i></a></li>
<li style="padding-left: 12px;font-size:25px;position: relative;"><a style="color:white;" href="https://www.linkedin.com/company/qezy-play?trk=biz-companies-cym"><i class="fa fa-linkedin-square"></i></a></li>
<li style="padding-left: 12px;font-size:25px;position: relative;"><a style="color:white;" href="https://twitter.com/QezyPlay"><i class="fa fa-twitter-square"></i></a></li>
</ul> </div>
				</div>
			</div>
		</div>
        </div>
</body>
</html>';

?>

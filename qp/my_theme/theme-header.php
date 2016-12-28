<?php
include("agent-config.php");
include("function_common.php");
?>
<!DOCTYPE HTML>
<!--
	Admin side hidden Functionalities
-->
<html>
	<head>
		<title>Admin | QezyPlay</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="<?php echo SITE_URL ?>/qp/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body class="homepage">
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header-wrapper">
					<header id="header" class="container">

						<!-- Logo -->
							<div id="logo">
								<h1><a href="">QezyPlay</a></h1>
								<span>ADMIN</span>
							</div>

						<!-- Nav -->
							<nav id="nav">
								<ul>
									<li class="current"><a href="my.php">HOME</a></li>
									<li>
										<a href="#">Fns</a>
										<ul>
											<li><a href="#">Fn1</a></li>
											<li><a href="#">Fn2</a></li>
											<li>
												<a href="#">Fn3</a>
												<ul>
													<li><a href="#">Fn3.1</a></li>
													<li><a href="#">Fn3.2</a></li>
													<li><a href="#">Fn3.3</a></li>
													<li><a href="#">Fn3.4</a></li>
												</ul>
											</li>
											<li><a href="#">Fn4</a></li>
										</ul>
									</li>
									<!-- <li><a href="left-sidebar.html">Left Sidebar</a></li>
									<li><a href="right-sidebar.html">Right Sidebar</a></li>
									<li><a href="no-sidebar.html">No Sidebar</a></li>-->
								</ul>
							</nav>

					</header>
				</div>

			<!-- Banner -->
				<div id="banner-wrapper">
					<div id="banner" class="box container">
						<div class="row">
							<div class="7u 12u(medium)">
								<h2>Welcome to Admin Portal.</h2>
								<p>Handle the functions with care</p>
							</div>
							<div class="5u 12u(medium)">
								<ul>
									<li><a href="delete_uinfo.php" class="button big icon fa-arrow-circle-right">Deleting User Info</a></li>
									<li><a href="exec_query.php" class="button alt big icon fa-question-circle">QUERY Exexution</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>


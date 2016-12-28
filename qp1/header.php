<?php
include("db-config.php");
include("admin-include.php");
include("function_common.php");

//Access your POST variables
$customer_id = $_SESSION['adminid'];
$admin_level = $_SESSION['adminlevel'];
$admin=$_SESSION['adminname'];
//echo "id:".$agent_id;
//Unset the useless session variable

?>
<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> Admin Portal | QezyPlay </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google" content="notranslate" />
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="css/vendor.css">
        <!-- Theme initialization -->
        <script>
            var themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
            {};
            var themeName = themeSettings.themeName || '';
            if (themeName)
            {
                document.write('<link rel="stylesheet" id="theme-style" href="css/app-' + themeName + '.css">');
            }
            else
            {
                document.write('<link rel="stylesheet" id="theme-style" href="css/app.css">');
            }
        </script>
	<link rel="stylesheet" href="css/jquery-ui.css">

	<script src="js/jquery.js"></script>
	<!-- <script src="js/jquery-ui.js"></script>-->
	
	<link rel='stylesheet' type='text/css' href="css/sweetalert.css">
	<script src="js/sweetalert.min.js"></script>

	<link rel='stylesheet' type='text/css' href="css/morris.css">
	<script src="js/morris.min.js"></script>
	<script src="js/raphael-min.js"></script>

	<style>
	.goog-te-banner-frame.skiptranslate {display: none !important;} 
body { top: 0px !important; }
	</style>

    </head>

    <body>
        <div class="main-wrapper">
            <div class="app" id="app">
                <header class="header">
                    <div class="header-block header-block-collapse hidden-lg-up"> <button class="collapse-btn" id="sidebar-collapse-btn">
    			<i class="fa fa-bars"></i>
    		</button> </div>
                    <div class="header-block header-block-search hidden-sm-down">
                        <!--form role="search">
                            <div class="input-container"> <i class="fa fa-search"></i> <input type="search" placeholder="Search">
                                <div class="underline"></div>
                            </div>
                        </form-->
                    </div>
                    <div class="header-block header-block-buttons">
                        <!--a href="https://github.com/modularcode/modular-admin-html" class="btn btn-oval btn-sm rounded-s header-btn"> <i class="fa fa-github-alt"></i> View on GitHub </a>
                        <a href="https://github.com/modularcode/modular-admin-html/releases/download/v1.0.1/modular-admin-html-1.0.1.zip" class="btn btn-oval btn-sm rounded-s header-btn"> <i class="fa fa-cloud-download"></i> Download .zip </a-->
                    </div>
                    <div class="header-block header-block-nav">
                        <ul class="nav-profile">
                            <!--<li class="notifications new">
                                <a href="" data-toggle="dropdown"> <i class="fa fa-bell-o"></i> <sup>
    			      <span class="counter">8</span>
    			    </sup> </a>
                                <div class="dropdown-menu notifications-dropdown-menu">
                                    <ul class="notifications-container">
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/3.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Zack Alien</span> pushed new commit: <span class="accent">Fix page load performance issue</span>. </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/5.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Amaya Hatsumi</span> started new task: <span class="accent">Dashboard UI design.</span>. </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/8.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Andy Nouman</span> deployed new version of <span class="accent">NodeJS REST Api V3</span> </p>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <footer>
                                        <ul>
                                            <li> <a href="">
    			            View All
    			          </a> </li>
                                        </ul>
                                    </footer>
                                </div>
                            </li>-->
                            <li class="profile dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <div class="img" style="background-image: url('../qp1/assets/images/admin2.png')"> </div> <span class="name">
    			     <?php echo $admin?>
    			    </span> </a>
				
                                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <!--<a class="dropdown-item" href="#"> <i class="fa fa-user icon"></i> Profile </a>
                                    <a class="dropdown-item" href="#"> <i class="fa fa-bell icon"></i> Notifications </a>-->
                                   <!-- <a class="dropdown-item" href="#"> <i class="fa fa-gear icon"></i> Settings </a> -->
					<!--<a class="dropdown-item" href="#" data-toggle="modal" data-target="#CustomizeModal"> <i class="fa fa-cog"></i> Settings </a>		-->			  
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="?logout=true"> <i class="fa fa-power-off icon"></i> Logout </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </header>
                <aside class="sidebar">
                    <div class="sidebar-container">
                        <div class="sidebar-header">
                              <div class="brand">
				<img class="logo1" alt="" src='../qp1/assets/images/qp_logo.png' style="width: 100px; height: 50px">
                                <!--<div class="logo"> <span class="l l1"></span> <span class="l l2"></span> <span class="l l3"></span> <span class="l l4"></span> <span class="l l5"></span> </div--> AdminPortal </div>
                        </div>
                        <nav class="menu">
                            <nav class="menu">
                            <ul class="nav metismenu" id="sidebar-menu">
                                <li class="active">
                                    <a href="home.php"> <i class="fa fa-home"></i> Dashboard </a>
                                </li>
	
				<li>
                                    <a href="promocodes-groups.php"> <i class="fa fa-th-large"></i>PromoCodes - Groups <i class="fa arrow"></i> </a>
                                    
                                </li>

				<?php
				if((int)$_SESSION['adminlevel'] <= 1)
						{
					?>

				 <li>
                                    <a href="geo-maps.php"> <i class="fa fa-th-large"></i>LIVE Geo-Map <i class="fa arrow"></i> </a>
                                    
                                </li>
				
				 <?php
					}
					?>
				<?php
				if((int)$_SESSION['adminlevel']==-1)
						{
					?>
                <li>
                                    <a href=""> <i class="fa fa-th-large"></i> Channel Streaming Status <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a id="chk_status" href="check_stream">
    								Check Status
    							</a> </li>
                                        <li> <a id="set_status" href="channel_streaming_status">
    								Set Status
    							</a> </li>
                                    </ul>
                                </li>
				 <li>
                                    <a href=""> <i class="fa fa-th-large"></i> Module Management <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="agent-management.php">
    								Agent Management
    							</a> </li>
                                        <li> <a href="broadcaster-management.php">
    								Broadcaster Management
    							</a> </li>
					<li> <a href="EPG-management.php">
    								EPG Management
    							</a> </li>
                                    </ul>
                                </li>
				 <li>
                                    <a href=""> <i class="fa fa-th-large"></i> Statistics <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="admin-statistics-all.php">
    								All Statistics
    							</a> </li>
                                        <li> <a href="admin-statistics-by-channel.php">
    								Statistics By Channel
    							</a> </li>
					<li> <a href="admin-statistics-lastweek.php">
    								Last Week Statistics
    							</a> </li>
                                    </ul>
                                </li>
				 <li>
                                    <a href="user-enquiries.php"> <i class="fa fa-th-large"></i> User Enquiries <i class="fa arrow"></i> </a>
                                    
                                </li>
				<li>
                                    <a href="user-stats-info.php"> <i class="fa fa-th-large"></i> User Statistics Info/Table <i class="fa arrow"></i> </a>
                                    
                                </li>

				<li>
                                    <a href="extra_params.php"> <i class="fa fa-th-large"></i> Extra params <i class="fa arrow"></i> </a>
                                    
                                </li>

				<li>
                                    <a href=""> <i class="fa fa-th-large"></i> Core Modules Management <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="channel_info_management.php.php">
    								Channel Info Management
    							</a> </li>
                                        <li> <a href="admin_bouquet_management.php">
    								BOUQUET Management
    							</a> </li>
					 <li> <a href="admin_channel_management.php">
    								CHANNEL Management
    							</a> </li>
                                    </ul>
                                </li>

				<li>
                                    <a href="admin-extra-analytics.php"> <i class="fa fa-th-large"></i> Extra Analytics<i class="fa arrow"></i> </a>
                                    
                                </li>

				<!--<li>
                                    <a href="admin-main3.php"> <i class="fa fa-th-large"></i> Last Week graph <i class="fa arrow"></i> </a>
                                    
                                </li>-->

				<li>
                                    <a href="exec_query.php"> <i class="fa fa-th-large"></i> Execute Query<i class="fa arrow"></i> </a>
                                    
                                </li>

				<li>
                                    <a href="delete_uinfo.php"> <i class="fa fa-th-large"></i> Delete User Info<i class="fa arrow"></i> </a>
                                    
                                </li>

				

				<li>
                                    <a href="admin_user_control.php"> <i class="fa fa-th-large"></i>Admin Users' Control <i class="fa arrow"></i> </a>
                                    
                                </li>
				<?php
					}?>
                              <!--  <li>
                                    <a href=""> <i class="fa fa-th-large"></i> Items Manager <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="items-list.php">
    								Items List
    							</a> </li>
                                        <li> <a href="item-editor.html">
    								Item Editor
    							</a> </li>
                                    </ul>
                                </li>
				
                                <li>
                                    <a href=""> <i class="fa fa-bar-chart"></i> Charts <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="charts-flot.html">
    								Flot Charts
    							</a> </li>
                                        <li> <a href="charts-morris.html">
    								Morris Charts
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-table"></i> Tables <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="static-tables.html">
    								Static Tables
    							</a> </li>
                                        <li> <a href="responsive-tables.html">
    								Responsive Tables
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="forms.html"> <i class="fa fa-pencil-square-o"></i> Forms </a>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-desktop"></i> UI Elements <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="buttons.html">
    								Buttons
    							</a> </li>
                                        <li> <a href="cards.html">
    								Cards
    							</a> </li>
                                        <li> <a href="typography.html">
    								Typography
    							</a> </li>
                                        <li> <a href="icons.html">
    								Icons
    							</a> </li>
                                        <li> <a href="grid.html">
    								Grid
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-file-text-o"></i> Pages <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="login.html">
    								Login
    							</a> </li>
                                        <li> <a href="signup.html">
    								Sign Up
    							</a> </li>
                                        <li> <a href="reset.html">
    								Reset
    							</a> </li>
                                        <li> <a href="error-404.html">
    								Error 404 App
    							</a> </li>
                                        <li> <a href="error-404-alt.html">
    								Error 404 Global
    							</a> </li>
                                        <li> <a href="error-500.html">
    								Error 500 App
    							</a> </li>
                                        <li> <a href="error-500-alt.html">
    								Error 500 Global
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="https://github.com/modularcode/modular-admin-html"> <i class="fa fa-github-alt"></i> Theme Docs </a>
                                </li>-->
                            </ul>
                        </nav>
                    </div>
                    <footer class="sidebar-footer">
                        <ul class="nav metismenu" id="customize-menu">
                            <li>
                                <ul>
                                    <li class="customize">
                                        <div class="customize-item">
                                            <div class="row customize-header">
                                                <div class="col-xs-4"> </div>
                                                <div class="col-xs-4"> <label class="title">fixed</label> </div>
                                                <div class="col-xs-4"> <label class="title">static</label> </div>
                                            </div>
                                            <div class="row hidden-md-down">
                                                <div class="col-xs-4"> <label class="title">Sidebar:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="sidebarPosition" value="sidebar-fixed" >
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="sidebarPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4"> <label class="title">Header:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="headerPosition" value="header-fixed">
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="headerPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4"> <label class="title">Footer:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="footerPosition" value="footer-fixed">
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="footerPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                        </div>
                                        <div class="customize-item">
                                            <ul class="customize-colors">
                                                <li> <span class="color-item color-red" data-theme="red"></span> </li>
                                                <li> <span class="color-item color-orange" data-theme="orange"></span> </li>
                                                <li> <span class="color-item color-green" data-theme="green"></span> </li>
                                                <li> <span class="color-item color-seagreen" data-theme="seagreen"></span> </li>
                                                <li> <span class="color-item color-blue active" data-theme=""></span> </li>
                                                <li> <span class="color-item color-purple" data-theme="purple"></span> </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                                <a href=""> <i class="fa fa-cog"></i> Theme Settings </a>
                            </li>
				<li>
                                <ul>
                                    <li class="customize">
                                                                                    
                                            <div class="row">
						<div id="google_translate_element"></div>
						<script type="text/javascript">
						    function googleTranslateElementInit() {
						        new google.translate.TranslateElement({
						            pageLanguage: 'en',
						            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
						            autoDisplay: false
						        }, 'google_translate_element');
						    }
						</script>
						<script type="text/javascript"
						        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
					    </div>

                                           <!--  <div class="row">
                                                     <div class="col-xs-12"> <button type="button" class="btn btn-primary">Save changes</button> </div>                               </div> -->
                                         
                                    </li>
                                </ul>
                                <a href=""> <i class="fa fa-cog"></i> Language Settings </a>
                            </li>
                        </ul>
			
			<div class="modal fade" id="CustomizeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
				<div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				        <span aria-hidden="true">&times;</span>
				    </button>
				    <h4 class="modal-title" id="myModalLabel">Customize</h4>
				</div>
							<div class="modal-body">
					    <div class="row well">
						<div id="google_translate_element">Select Your Language</div>
						<script type="text/javascript">
						    function googleTranslateElementInit() {
						        new google.translate.TranslateElement({
						            pageLanguage: 'en',
						            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
						            autoDisplay: false
						        }, 'google_translate_element');
						    }
						</script>
						<script type="text/javascript"
						        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
					    </div>
					 </div>
					<div class="modal-footer">
					    
					</div>
            </div>
        </div>
    </div>
                    </footer>
			
                </aside>
		<script>var $ = jQuery.noConflict();</script>
                <div class="sidebar-overlay" id="sidebar-overlay"></div>


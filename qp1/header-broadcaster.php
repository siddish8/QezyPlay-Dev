<?php
include("db-config.php");
include("function_common.php");


if(isset($_GET['isadmin'])){

	if($_GET['isadmin'] == 1)
		$_SESSION['customerid'] = $_GET['customer_id'];
		$_SESSION['channelid'] = $_GET['channel_id'];
}

//Access your POST variables
$broadcaster_id = $_SESSION['customerid'];
$channelID=$channel_id=$_SESSION['channelid'];

$logo=$_SESSION['logo'];
$cName = $_SESSION['cName'] ;
//echo "id:".$agent_id;
//Unset the useless session variable

if($_SESSION['customerid'] == ""){	
	//Redirect the user to the next page
	header('Location:broadcaster-login.php'); 
	exit;
}

if(isset($_GET['logout'])){

	unset($_SESSION['customerid']);

	header('Location: broadcaster-login.php');
	exit;
}



?>
<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> Broadcaster Portal | QezyPlay </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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


    </head>

    <body>
	
        <div class="main-wrapper">
            <div class="app" id="app">
                <header class="header">
                    <div class="header-block header-block-collapse hidden-lg-up"> <button class="collapse-btn" id="sidebar-collapse-btn">
    			<i class="fa fa-bars"></i>
    		</button> </div>
                    <div class="header-block header-block-search hidden-sm-down">
                        <!-- form role="search">
                            <div class="input-container"> <i class="fa fa-search"></i> <input type="search" placeholder="Search">
                                <div class="underline"></div>
                            </div>
                        </form -->
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
                                    <div class="img" style="width:60px;height:35px;background-image: url('../qp1/customer_logo/<?php echo $logo;?>')"> </div> <span class="name">
    			     <?php echo $cName; ?>
    			    </span> </a>
                                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
					<div ><img style="max-width:100%" src="../qp1/customer_logo/<?php echo $logo;?>" /></div>
                                   <!-- <a class="dropdown-item" href="#"> <i class="fa fa-user icon"></i> Profile </a>
                                    <a class="dropdown-item" href="#"> <i class="fa fa-bell icon"></i> Notifications </a>
                                    <a class="dropdown-item" href="#"> <i class="fa fa-gear icon"></i> Settings </a>-->
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
                                <!--<div class="logo"> <span class="l l1"></span> <span class="l l2"></span> <span class="l l3"></span> <span class="l l4"></span> <span class="l l5"></span> </div--> BroadcasterPortal </div>
                        </div>
                        <nav class="menu">
                            <nav class="menu">
                            <ul class="nav metismenu" id="sidebar-menu">
                                <li class="active">
                                    <a href="broadcaster-main.php"> <i class="fa fa-home"></i> Dashboard </a>
                                </li>
				
				 <li><a href="broadcaster_channel_analytics.php"><i class="fa fa-th-large"></i>Channel Analytics<i class="fa arrow"></i> </a>
                                    
                                </li>
				 <li><a href="broadcaster_daily_statistics.php"><i class="fa fa-th-large"></i>Daily Statistics<i class="fa arrow"></i> </a>
                                    
                                </li>
				 <li><a href="broadcaster_monthly_statistics.php"><i class="fa fa-th-large"></i>Monthly Statistics<i class="fa arrow"></i> </a>
                                    
                                </li>
				<li><a href="broadcaster_yearly_statistics.php"><i class="fa fa-th-large"></i>Yearly Statistics<i class="fa arrow"></i> </a>
                                    
                                </li>
				 <li><a href="broadcaster_statistics_by_region.php"><i class="fa fa-th-large"></i>Statistics by Region<i class="fa arrow"></i> </a>
                                    
                                </li>
				 <li><a href="broadcaster_statistics_by_device.php"><i class="fa fa-th-large"></i>Statistics by Device<i class="fa arrow"></i> </a>
                                    
                                </li>
      		 
				<?php
				if((int)$_SESSION['adminlevel']==-1)
						{
					?>
						
				<li>
                                    <a href="broadcaster_user_statistics.php"> <i class="fa fa-th-large"></i>User Statistics <i class="fa arrow"></i> </a>
                                    
                                </li>
				<?php
					}?>
                               
                               
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
                                <a href=""> <i class="fa fa-cog"></i> Customize </a>
                            </li>
                        </ul>
                    </footer>
                </aside>
                <div class="sidebar-overlay" id="sidebar-overlay"></div>


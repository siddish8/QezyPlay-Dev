<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
	<head>
		<meta http-equiv='content-type' content='text/html;charset=utf-8' />
		<title>BROADCASTER PORTAL</title>	
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
		<link rel="stylesheet" href="../qp/css/jquery-ui.css">
 	<script src="../qp/js/jquery-ui.js"></script>
		
	</head>
	<style>
	a{ color: red; font-weight: bold;}
	input[type="text"], input[type="password"],select{color:black !important;}
	</style>
	<body>
	<script src="<?php echo SITE_URL;?>/qp/js/bootstrap.min.js"></script>
	<script src="<?php echo SITE_URL;?>/qp/sweetalert-master/dist/sweetalert.min.js"></script>
		<div class='row' style='padding:0px 0px;background-color:#000 !important;color:#fff !important;ma-width:1500px;'>
			<div class='twelve columns'>
				<header>
					<div class='three columns'>
						<div id='logo'>
							<img src="<?php echo SITE_URL;?>/qp/qezyplay-logo.png" alt='QezyPlay' height='60' width='160'>
						</div>
					</div>
					<div class='three columns'>
						<div id='hedtext'>							
							<center><h2 style='color:#fff !important;font-weight:bolder;'>BROADCASTER PORTAL<h2></center>
						</div>
					</div>					
					<div class='three columns'>
							<?php if($_SESSION['customerid'] > 0){ 
								
									$sql = "SELECT customer_logo_url,customername FROM customer_info WHERE id = ?";		
									try {
										$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
										$stmt->execute(array($customer_id));
										$result = $stmt->fetch(PDO::FETCH_ASSOC);
								    		$stmt = null;
										
										$customer_logo_url = $result['customer_logo_url'];
										$customer_name = $result['customername'];
		
									}catch (PDOException $e){
										print $e->getMessage();
								   	}
								?>
							<div id="chan_logo" style="float:right;padding-right:30px;margin-left:10px">
								<img style="max-height:60px !important;border:1px solid rgba(255, 255, 255, 0.66);border-radius:2px;" height="70" width="160" src="customer_logo/<?php echo $customer_logo_url; ?>">
							</div>

					<div id='hedtext' style="float:right;">							
							<center><h2><h4 style='color:#eee !important;'>
							
									Welcome <?php echo $customer_name?> ,&nbsp;&nbsp;<span><a id='logout' style='cursor:pointer' href='?logout=true'>Logout</a></span>
								<?php }?></h4></center>
						</div>
						</div>
					</div>					
				</header>
			</div>
		</div>

<?php if((int)$_SESSION['customerid'] > 0){ ?>
		<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
     <!-- <a class="navbar-brand" href="#">WebSiteName</a> -->
    </div>
    <ul class="nav navbar-nav">
      <li id="Custhome"><a href="<?php echo SITE_URL;?>/qp/broadcaster-main.php">Home</a></li>
      <li id="chan_ana"><a href="<?php echo SITE_URL;?>/qp/broadcaster_channel_analytics.php">Channel Analytics</a></li>
      <li id="daily_stat"><a href="<?php echo SITE_URL;?>/qp/broadcaster_daily_statistics.php">Daily Statistics</a></li> 
      <li id="month_stat"><a href="<?php echo SITE_URL;?>/qp/broadcaster_monthly_statistics.php">Monthly Statistics</a></li> 
      <li id="year_stat"><a href="<?php echo SITE_URL;?>/qp/broadcaster_yearly_statistics.php">Yearly Statistics</a></li> 
      <li id="region_stat"><a href="<?php echo SITE_URL;?>/qp/broadcaster_statistics_by_region.php">Statistics by Region</a></li> 
      <li id="device_stat"><a href="<?php echo SITE_URL;?>/qp/broadcaster_statistics_by_device.php">Statistics by Device</a></li> 
    </ul>
  </div>
</nav>
<?php }  ?>
		<?php if((int)$_SESSION['customerid'] > 0){ ?>
		<div class='row' style='padding:0px 0px;ma-width:1500px;'>
			<form name="f_timezone" action="" style="float:right;color:black !important;">    
				<select style="" name="timezone" id="DropDownTimezone1" onchange="this.form.submit();">
					<option value="-12.0"<?php echo (@$coockie == "-12:00") ? "selected" : ""; ?>>(GMT -12:00) Eniwetok, Kwajalein</option>
					<option value="-11.0"<?php echo (@$coockie == "-11:00") ? "selected" : ""; ?>>(GMT -11:00) Midway Island, Samoa</option>
					<option value="-10.0"<?php echo (@$coockie == "-10:00") ? "selected" : ""; ?>>(GMT -10:00) Hawaii</option>
					<option value="-9.0"<?php echo (@$coockie == "-09:00") ? "selected" : ""; ?>>(GMT -9:00) Alaska</option>
					<option value="-8.0"<?php echo (@$coockie == "-08:00") ? "selected" : ""; ?>>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
					<option value="-7.0"<?php echo (@$coockie == "-07:00") ? "selected" : ""; ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
					<option value="-6.0"<?php echo (@$coockie == "-06:00") ? "selected" : ""; ?>>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
					<option value="-5.0"<?php echo (@$coockie == "-05:00") ? "selected" : ""; ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
					<option value="-4.0"<?php echo (@$coockie == "-04:00") ? "selected" : ""; ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
					<option value="-3.5"<?php echo (@$coockie == "-03:30") ? "selected" : ""; ?>>(GMT -3:30) Newfoundland</option>
					<option value="-3.0"<?php echo (@$coockie == "-03:00") ? "selected" : ""; ?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
					<option value="-2.0"<?php echo (@$coockie == "-02:00") ? "selected" : ""; ?>>(GMT -2:00) Mid-Atlantic</option>
					<option value="-1.0"<?php echo (@$coockie == "-01:00") ? "selected" : ""; ?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
					<option value="0.0"<?php echo (@$coockie == "+00:00") ? "selected" : ""; ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
					<option value="1.0"<?php echo (@$coockie == "+01:00") ? "selected" : ""; ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
					<option value="2.0"<?php echo (@$coockie == "+02:00") ? "selected" : ""; ?>>(GMT +2:00) Kaliningrad, South Africa</option>
					<option value="3.0"<?php echo (@$coockie == "+03:00") ? "selected" : ""; ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
					<option value="3.5"<?php echo (@$coockie == "+03:30") ? "selected" : ""; ?>>(GMT +3:30) Tehran</option>
					<option value="4.0"<?php echo (@$coockie == "+04:00") ? "selected" : ""; ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>				
					<option value="4.5"<?php echo (@$coockie == "+04:30") ? "selected" : ""; ?>>(GMT +4:30) Kabul</option>
					<option value="5.0"<?php echo (@$coockie == "+05:00") ? "selected" : ""; ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
					<option value="5.5"<?php echo (@$coockie == "+05:30") ? "selected" : ""; ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
					<option value="5.75"<?php echo (@$coockie == "+05:45") ? "selected" : ""; ?>>(GMT +5:45) Kathmandu</option>
					<option value="6.0"<?php echo (@$coockie == "+06:00") ? "selected" : ""; ?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
					<option value="7.0"<?php echo (@$coockie == "+07:00") ? "selected" : ""; ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
					<option value="8.0"<?php echo (@$coockie == "+08:00") ? "selected" : ""; ?>>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
					<option value="9.0"<?php echo (@$coockie == "+09:00") ? "selected" : ""; ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
					<option value="9.5"<?php echo (@$coockie == "+09:30") ? "selected" : ""; ?>>(GMT +9:30) Adelaide, Darwin</option>
					<option value="10.0"<?php echo (@$coockie == "+10:00") ? "selected" : ""; ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
					<option value="11.0"<?php echo (@$coockie == "+11:00") ? "selected" : ""; ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
					<option value="12.0"<?php echo (@$coockie == "+12:00") ? "selected" : ""; ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
				</select>
			</form>
		<div>
		<?php } ?>
		<div class='clear'></div>		

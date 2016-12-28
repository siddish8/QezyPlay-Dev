<?php

include("db-config.php");

include("function_common.php");

//Access your POST variables
$customer_id = $_SESSION['customerid'];
//echo "id:".$agent_id;
//Unset the useless session variable



if(isset($_GET['logout'])){

	unset($_SESSION['customerid']);

	header('Location: broadcaster-login.php');
	exit;
}



if((int)$_SESSION['customerid'] <= 0){
	
	header('Location: broadcaster-login.php');
	exit;
	
}else{
	$searchstring = "";
	
	$start_limit = 0;
	@$page = isset($_GET['page']) ? $_GET['page'] : $_POST['page'];
	if (!isset($page))
	    $page = 1;
	if ($page > 1)
	    $start_limit = (($page * ROW_PER_PAGE) - ROW_PER_PAGE);

	$mobileselected =  $pcselected = $cond = "";
	if($_REQUEST['device'] == "pc"){
		$cond .= " AND device = 'Personal Computer'";
		$pcselected = "selected='selected'";		
	}elseif($_REQUEST['device'] == "mobile"){
		$cond .= " AND device = 'Mobile'";
		$mobileselected = "selected='selected'";		
	}

	if($_REQUEST['country'] != ""){
		$cond .= " AND country_code = '".$_REQUEST['country']."'";		
	}
	if($_REQUEST['state'] != ""){
		$cond .= " AND state = '".$_REQUEST['state']."'";
		$stateselected = "<option selected='selected' value='".$_REQUEST['state']."'>".$_REQUEST['state']."</option>";		
	}
	if($_REQUEST['city'] != ""){
		$cond .= " AND city = '".$_REQUEST['city']."'";
		$cityselected = "<option selected='selected' value='".$_REQUEST['city']."'>".$_REQUEST['city']."</option>";		
	}
	if($_REQUEST['startdate'] != ""){
		$cond .= " AND DATE(CONVERT_TZ(start_datetime,'+00:00','".$coockie."')) >= '".$_REQUEST['startdate']."'";
		$startdate = $_REQUEST['startdate'];		
	}
	if($_REQUEST['enddate'] != ""){
		$cond .= " AND DATE(CONVERT_TZ(end_datetime,'+00:00','".$coockie."')) <= '".$_REQUEST['enddate']."'";
		$enddate = $_REQUEST['enddate'];		
	}

	$channelId = $_SESSION['channelid'];
	$cond .= " AND page_id = ".$channelId;

	$sql1 = "SELECT count(*) as count,DATE_FORMAT(CONVERT_TZ(start_datetime,'+00:00','".$coockie."'),'%Y-%m') as month FROM visitors_info WHERE 1".$cond." GROUP BY DATE_FORMAT(CONVERT_TZ(start_datetime,'+00:00','".$coockie."'),'%Y-%m')";	
	$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$hits = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	$sql2 = "SELECT sum(duration) as duration,DATE_FORMAT(CONVERT_TZ(start_datetime,'+00:00','".$coockie."'),'%Y-%m') as month FROM visitors_info WHERE 1 AND duration > 0 AND play = 1".$cond." GROUP BY DATE_FORMAT(CONVERT_TZ(start_datetime,'+00:00','".$coockie."'),'%Y-%m')";	
	$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt2->execute();
	$durations = $stmt2->fetchAll(PDO::FETCH_ASSOC);

	$countries = getCountries();
	
	include("header-broadcaster.php");
	?>
	<style>
	.xoouserultra-field-value, .xoouserultra-field-type{
		width: unset !important;
		padding: 5px;
	}
	</style>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/morris.css">
	<script src="js/jquery-ui.js"></script>
	
	<script src="js/raphael-min.js"></script>
	<script src="js/morris.min.js"></script>
 	
	<div id="content" role="main" style="min-height:500px;margin: 2% 5%;">
		<!-- h4 style="float:right;"><a alt="click to dashboard" href="customer-main.php">Dashborad</a></h4 -->
		<div class="pmpro_box" id="pmpro_account-invoices">
			<h2 style="text-align:center">Monthly Statistics</h2>
			<div class="xoouserultra-wrap" style="max-width:100% !important;">
				<div class="xoouserultra-inner xoouserultra-login-wrapper">
					<div class="xoouserultra-main">
						<form id="xoouserultra-login-form-1" method='post'>
							<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
								<label class="xoouserultra-field-type">	
									<span>Device:</span>
								</label>
								<div class="xoouserultra-field-value">
									<select name="device">
										<option value=''>All</option>
										<option value='mobile' <?php echo $mobileselected; ?>>Mobile</option>
										<option value='pc' <?php echo $pcselected; ?>>Personal Computer</option>
									</select>	
								</div>
								&nbsp;&nbsp;&nbsp;
								<label class="xoouserultra-field-type">	
									<span>Country:</span>
								</label>
								<div class="xoouserultra-field-value">
									<select onchange="return getStates(this.value);" name="country" name="country">
										<option value=''>All</option>
										<?php
										foreach($countries as $country){
											$countryselected = ($country['country_code'] == $_REQUEST['country']) ? 'selected="selected" ' : "";
											echo "<option ".$countryselected."value='".$country['country_code']."'>".$country['country']."</option>";
										}
										?>
									</select>
								</div>
								&nbsp;&nbsp;&nbsp;
								<label class="xoouserultra-field-type">	
									<span>State:</span>
								</label>
								<div class="xoouserultra-field-value">
									<select onchange="return getCities(this.value);" name="state" id="state"><option value=''>All</option>
									<?php
									if($_REQUEST['country'] != ""){ 
										$states = getStates($_REQUEST['country']);
										foreach($states as $state){
											$stateselected = ($state['state'] == $_REQUEST['state']) ? 'selected="selected" ' : "";
											echo "<option ".$stateselected."value='".$state['state']."'>".$state['state']."</option>";
										}
										echo $stateselected; 
									} 
									?></select>
								</div>
							</div>		
							<div class="xoouserultra-clear"></div>
							<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							
								<label class="xoouserultra-field-type">	
									<span>City:</span>
								</label>
								<div class="xoouserultra-field-value">
									<select name="city" id="city"><option value=''>All</option>
									<?php
									if($_REQUEST['state'] != ""){ 
										$cities = getCities($_REQUEST['state']);
										foreach($cities as $city){
											$cityselected = ($city['city'] == $_REQUEST['city']) ? 'selected="selected" ' : "";
											echo "<option ".$cityselected."value='".$city['city']."'>".$city['city']."</option>";
										}
										echo $cityselected; 
									} 
									?></select>
								</div>
								&nbsp;&nbsp;&nbsp;
								<label class="xoouserultra-field-type">	
									<span>Start date:</span>
								</label>

								<div class="xoouserultra-field-value">
									<input autocomplete="off" type="text" required value="<?php echo $startdate; ?>" id='startdate' name='startdate'>
								</div>
								&nbsp;&nbsp;&nbsp;
								<label for="user_login" class="xoouserultra-field-type">	
									<span>End Date:</span>
								</label>
								<div class="xoouserultra-field-value">
									<input autocomplete="off" type="text" required value="<?php echo $enddate; ?>" id='enddate' name='enddate'>	
								</div>
							</div>
							<div class="xoouserultra-clear"></div>
							<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							
								<div class="xoouserultra-field-value" style="padding-left: 10px;">
									<input type="hidden" name='page' value="1" id="page">
									<input type="submit" name='filter' value="Get Statistics" class="" name="xoouserultra-login" id="filter" onclick="return checkDates()"><label style="margin: 20px 10px;color:red !important" id="dateErr"></label>
								</div>
							</div>
							<div class="xoouserultra-clear"></div>
						</form>
					</div>
				</div>

			</div>
			
			<?php if($startdate!="" && $enddate!=""){ ?>

			<h3><u>Statistics for page hits</u></h3><br>
			<div id="hitschart"></div>
			<br><br>
			<h3><u>Statistics for video played duration</u></h3><br>
			<div id="durationchart"></div>

			<?php }else{ echo "<div><center><h2>Please choose the date</h2></center></div>"; }  ?>
			
		</div>
	</div>
	<script>
	jQuery(function() {
		jQuery( "#startdate" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
			maxDate: '0',
			onSelect: function (selected) {
			    var dt = new Date(selected);
			    dt.setDate(dt.getDate() + 1);
			    jQuery("#enddate").datepicker("option", "minDate", dt);
			},
			onClose: function( selectedDate ) {
				jQuery( "#enddate" ).datepicker( "option", "minDate", selectedDate );				
			
			}
		});
		jQuery( "#enddate" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
			maxDate: '0',
			onClose: function( selectedDate ) {
				jQuery( "#startdate" ).datepicker( "option", "maxDate", selectedDate );				
			}
		});
		
	});

	function getStates(countrycode){

		jQuery('#city').html("<option value=''>All</option>");
		jQuery('#state').html("<option value=''>All</option>");

		if(countrycode!=""){
			jQuery.ajax({  
				type: "POST",
				url : "ajax-info.php",
				data: jQuery.param({ 'action': "getState", 'country_code' : countrycode}) ,
				cache: false,
				success: function(data)
				{
					jQuery('#state').append(data);						
				}
			});	
		}

		return false;
	
	}

	function getCities(state){

		jQuery('#city').html("<option value=''>All</option>");

		if(state!=""){
			jQuery.ajax({  
				type: "POST",
				url : "ajax-info.php",
				data: jQuery.param({ 'action': "getCity", 'state' : state}) ,
				cache: false,
				success: function(data)
				{
					jQuery('#city').append(data);	
				}
			});	
		}

		return false;
	
	}

	</script>

	<?php if($startdate!="" && $enddate!=""){ 
		
		if(count($hits) > 0){ 

			$hitsdata = "";
			foreach($hits as $hit){
				$hitsdata .= '{month:"'.$hit["month"].'",value:"'.$hit["count"].'"},';		
			}
			$hitsdata = substr($hitsdata,0,(strlen($hitsdata) - 1));

		?>
			<script>	
			new Morris.Line({
				element:'hitschart',
				data:[<?php echo $hitsdata; ?>],
				xkey:'month',
				ykeys:['value'],
				labels:['Hits'],
				xLabels: 'month'
			});
			</script>
		<?php
		} else { echo "<script>document.getElementById('hitschart').innerHTML = 'No analytics found';</script>"; } 

		if(count($durations) > 0){ 

			$durationsdata = "";
			foreach($durations as $duration){
				$playedduration = $duration['duration'] / 60;
				$playedduration=round($playedduration, 1, PHP_ROUND_HALF_DOWN); //added	

				$durationsdata .= '{month:"'.$duration["month"].'",value:"'.$playedduration.'"},';		
			}
			$durationsdata = substr($durationsdata,0,(strlen($durationsdata) - 1));

		?>
			<script>	
			new Morris.Line({
				element:'durationchart',
				data:[<?php echo $durationsdata; ?>],
				xkey:'month',
				ykeys:['value'],
				labels:['Duration(in minutes)'],
				xLabels: 'month'
			});
			</script>	
	<?php
		} else { echo "<script>document.getElementById('durationchart').innerHTML = 'No analytics found';</script>"; } 
	}

	include("footer-broadcaster.php");
	
}
?>

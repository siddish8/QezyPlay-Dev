<?php

include("header-broadcaster.php");
?>
<article>
<?php
	$searchstring = "";
	
	$start_limit = 0;
	@$page = isset($_GET['page']) ? $_GET['page'] : $_POST['page'];
	if (!isset($page))
	    $page = 1;
	if ($page > 1)
	    $start_limit = (($page * ROW_PER_PAGE) - ROW_PER_PAGE);

	$cond = "";	
	if($_REQUEST['startdate'] != ""){
		$cond .= " AND DATE(CONVERT_TZ(start_datetime,'+00:00','".$coockie."')) >= '".$_REQUEST['startdate']."'";
		$startdate = $_REQUEST['startdate'];		
	}
	if($_REQUEST['enddate'] != ""){
		$cond .= " AND DATE(CONVERT_TZ(end_datetime,'+00:00','".$coockie."')) <= '".$_REQUEST['enddate']."'";
		$enddate = $_REQUEST['enddate'];		
	}

	$channelId = $_SESSION['channelid'];

	$sql1 = "SELECT count(*) as count,date(CONVERT_TZ(start_datetime,'+00:00','".$coockie."')) as date FROM visitors_info WHERE 1".$cond." GROUP BY date(CONVERT_TZ(start_datetime,'+00:00','".$coockie."'))";	
	$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$hits = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	$sql2 = "SELECT sum(duration) as duration,date(CONVERT_TZ(start_datetime,'+00:00','".$coockie."')) as date FROM visitors_info WHERE 1".$cond." GROUP BY date(CONVERT_TZ(start_datetime,'+00:00','".$coockie."'))";	
	$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt2->execute();
	$durations = $stmt2->fetchAll(PDO::FETCH_ASSOC);
	
	
	?>
	<style>
	.xoouserultra-field-value, .xoouserultra-field-type{
		width: unset !important;
		padding: 5px;
	}
	.paging{		
		float:left;
	}
	</style>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/morris.css">
	<script src="js/jquery-ui.js"></script>
	
	<script src="js/raphael-min.js"></script>
	<script src="js/morris.min.js"></script>
 	
	<div id="content" role="main" style="min-height:500px;margin: 2% 5%;">
		<h4 style="float:right;"><a alt="click to dashboard" href="admin-main.php">Dashborad</a></h4>
		<div class="pmpro_box" id="pmpro_account-invoices">
			<h2 style="text-align:center">Daily statistics</h2>
			<div class="xoouserultra-wrap" style="max-width:100% !important;">
				<div class="xoouserultra-inner xoouserultra-login-wrapper">
					<div class="xoouserultra-main">
						<form id="xoouserultra-login-form-1" method='post'>
							
							<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							
								<label class="xoouserultra-field-type">	
									<span>Start date:</span>
								</label>

								<div class="xoouserultra-field-value">
									<input autocomplete="off" type="text" value="<?php echo $startdate; ?>" id='startdate' name='startdate'>
								</div>
								&nbsp;&nbsp;&nbsp;
								<label for="user_login" class="xoouserultra-field-type">	
									<span>End Date:</span>
								</label>
								<div class="xoouserultra-field-value">
									<input autocomplete="off" type="text" value="<?php echo $enddate; ?>" id='enddate' name='enddate'>	
								</div>
								&nbsp;&nbsp;&nbsp;
								<div class="xoouserultra-field-type" style="padding-left: 10px;">
									<input type="hidden" name='page' value="1" id="page">
									<input type="submit" name='filter' value="Get Statistics" class="xoouserultra-button xoouserultra-login" name="xoouserultra-login" id="filter">
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

	</script>

	<?php if($startdate!="" && $enddate!=""){ 
		
		if(count($hits) > 0){ 

			$hitsdata = "";
			foreach($hits as $hit){
				$hitsdata .= '{date:"'.$hit["date"].'",value:"'.$hit["count"].'"},';		
			}
			$hitsdata = substr($hitsdata,0,(strlen($hitsdata) - 1));

		?>
			<script>	
			new Morris.Line({
				element:'hitschart',
				data:[<?php echo $hitsdata; ?>],
				xkey:'date',
				ykeys:['value'],
				labels:['Hits'],
				xLabels: 'day'
			});
			</script>
		<?php
		} 

		if(count($durations) > 0){ 

			$durationsdata = "";
			foreach($durations as $duration){
				$playedduration = $duration['duration'] / 60;
				$durationsdata .= '{date:"'.$duration["date"].'",value:"'.$playedduration.'"},';		
			}
			$durationsdata = substr($durationsdata,0,(strlen($durationsdata) - 1));

		?>
			<script>	
			new Morris.Line({
				element:'durationchart',
				data:[<?php echo $durationsdata; ?>],
				xkey:'date',
				ykeys:['value'],
				labels:['Duration(in minutes)'],
				xLabels: 'day'
			});
			</script>	
	<?php
		} 
	}


?>
</article>
<?php
	include("footer-broadcaster.php");

?>

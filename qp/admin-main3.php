<?php

include("db-config.php");
include("admin-include.php");
include("function_common.php");

//Access your POST variables
$customer_id = $_SESSION['adminid'];
$admin_level = $_SESSION['adminlevel'];
//echo "id:".$agent_id;
//Unset the useless session variable
if(isset($_GET['logout'])){

	unset($_SESSION['adminid']);

	header('Location: admin-login.php');
	exit;
}



if((int)$_SESSION['adminid'] <= 0){
	
	header('Location: admin-login.php');
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
	
	$datenow=new DateTime("now");
	$datenow=$datenow->format("Y-m-d H:i:s");
	$datewkbck=$date = date('Y-m-d H:i:s', strtotime('-1 week +1 day')); 
	$datewkbckcal=$date = date('Y-m-d H:i:s', strtotime('-1 week'));



	//if($_REQUEST['startdate'] != ""){
		$cond .= " AND DATE(CONVERT_TZ(start_datetime,'+00:00','".$coockie."')) >= '".$datewkbckcal."'";
		$startdate = $datewkbck;		
	//}
	//if($_REQUEST['enddate'] != ""){
		$cond .= " AND DATE(CONVERT_TZ(end_datetime,'+00:00','".$coockie."')) <= '".$datenow."'";
		$enddate = $datenow;		
	//}

	//$channelId = $_SESSION['channelid'];
	
	//$channelId = 135;
	//$cond .= " AND page_id = ".$channelId;

	$sql1 = "SELECT count(*) as count,date(CONVERT_TZ(start_datetime,'+00:00','".$coockie."')) as date FROM visitors_info WHERE 1".$cond." GROUP BY date(CONVERT_TZ(start_datetime,'+00:00','".$coockie."'))";	
	$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$hits = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	$sql2 = "SELECT sum(duration) as duration,date(CONVERT_TZ(start_datetime,'+00:00','".$coockie."')) as date FROM visitors_info WHERE 1 AND duration > 0 AND play = 1".$cond." GROUP BY date(CONVERT_TZ(start_datetime,'+00:00','".$coockie."'))";	
	$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt2->execute();
	$durations = $stmt2->fetchAll(PDO::FETCH_ASSOC);

	//$countries = getCountries();
	
include("header-admin.php");
?>
	<style>
	.menudiv{
		background: lightgrey;
		border: 2px solid black;
		text-align:center;
		width: 300px;
		margin: 1%;
		color: hsl(0, 0%, 0%);
		/*float: left;*/
		display: inline-block;
		height: 100px;
		padding: 2% 3% 3% 3%;
	}
	form[name="f_timezone"] {
    display: none;
}.xoouserultra-field-value, .xoouserultra-field-type{
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
		<center><h2>HOME</h2></center><br>
		<div class="pmpro_box" id="pmpro_account-invoices">
			<h2 style="text-align:center"></h2>
			
			
			<?php if($startdate!="" && $enddate!=""){ ?>

			<h3><u>Statistics for page hits</u></h3><br>
			<div id="hitschart"></div>
			<br><br>
			<h3><u>Statistics for video played duration</u></h3><br>
			<div id="durationchart"></div>

			<?php }else{ echo "<div><center><h2>Please choose the date</h2></center></div>"; }  ?>
			
		</div>
	
	</div>
	
<?php	if($startdate!="" && $enddate!=""){ 
		
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
				 events: ['<?php echo $datewkbckcal ?>'],
    				eventStrokeWidth: 0,
   				 resize: true,
				xkey:['date'],
				ykeys:['value'],
				labels:['Hits'],
				xLabels: ['day'],
				/*xLabelMargin: 10,
				xLabelAngle:90,*/
				xLabelFormat: function(d) {
    return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov', 'Dec'][d.getMonth()] + ' ' + d.getDate();
	
}
			});
			</script>
		<?php
		} else { echo "<script>document.getElementById('hitschart').innerHTML = 'No analytics found';</script>"; } 

		if(count($durations) > 0){ 

			$durationsdata = "";
			foreach($durations as $duration){
				$playedduration = $duration['duration'] / 60;
				$playedduration=round($playedduration, 1, PHP_ROUND_HALF_DOWN); //added	
				$durationsdata .= '{date:"'.$duration["date"].'",value:"'.$playedduration.'"},';		
			}
			$durationsdata = substr($durationsdata,0,(strlen($durationsdata) - 1));

		?>
			<script>	
			new Morris.Line({
				element:'durationchart',
				data:[<?php echo $durationsdata; ?>],
				 events: ['<?php echo $datewkbckcal ?>'],
    				eventStrokeWidth: 0,
   				 resize: true,
				xkey:['date'],
				ykeys:['value'],
				labels:['Duration(in minutes)'],
				xLabels: ['day'],
				/*xLabelMargin: 10,
				xLabelAngle:90,*/
				xLabelFormat: function(d) {
    return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov', 'Dec'][d.getMonth()] + ' ' + d.getDate();}
			});
			</script>	
	<?php
		} else { echo "<script>document.getElementById('durationchart').innerHTML = 'No analytics found';</script>"; } 
	}

include("footer-admin.php");
	}
?>

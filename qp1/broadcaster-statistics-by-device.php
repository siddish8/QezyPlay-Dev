<?php
include("header-broadcaster.php");
?>
<article class="content forms-page charts-morris-page">
<?php
	$searchstring = "";
	
	$start_limit = 0;
	@$page = isset($_GET['page']) ? $_GET['page'] : $_POST['page'];
	if (!isset($page))
	    $page = 1;
	if ($page > 1)
	    $start_limit = (($page * ROW_PER_PAGE) - ROW_PER_PAGE);

	
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

	$sql1 = "SELECT count(*) as count,os_name,device FROM visitors_info WHERE 1".$cond." GROUP BY os_name";	
	$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$hits = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	$sql2 = "SELECT sum(duration) as duration,os_name,device FROM visitors_info WHERE 1 AND duration > 0 AND play = 1".$cond." GROUP BY os_name";	
	$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt2->execute();
	$durations = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		
	
	?>
	<style>
	.xoouserultra-field-value, .xoouserultra-field-type{
		width: unset !important;
		padding: 5px;
	}
	
	</style>
	 	
	<section class="section">
                <div class="row sameheight-container">
                    <div class="col-md-12">
                        <div class="card card-block sameheight-item" style="height: 140px;">
                            <div class="title-block">
                                <h3 class="title">
						Choose a Date Range
					</h3> </div>
                            <form class="form-inline" role="form" id="xoouserultra-login-form-1" method="post">
                                <div class="form-group"> <label for="startdate">Start Date</label> <input class="form-control" autocomplete="off" type="text" value="<?php echo $startdate; ?>" id="startdate" name="startdate"> </div>
                                <div class="form-group"> <label for="enddate">End Date</label> <input class="form-control" autocomplete="off" type="text" value="<?php echo $enddate; ?>" id="enddate" name="enddate"> </div>
				<input type="hidden" name="page" value="1" id="page">
                                 <button type="submit" class="btn btn-primary" name="filter" value="Get Statistics" id="filter" onclick="return checkDates()">Get Statistics</button> <label style="margin: 20px 10px;color:red !important" id="dateErr"></label></form>
                        </div>
                    </div>
                </div>
            </section>
<?php if($startdate!="" && $enddate!=""){ ?>
	<section class="section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
             Statistics for page hits
            </h3> </div>
                                        <section class="example">
                                            <div id="hitschart"></div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
              Statistics for video played duration
            </h3> </div>
                                        <section class="example">
                                            <div id="durationchart"></div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
			
		<?php }else{ } ?>	

			

			 
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
				$hitsdata .= '{device:"'.$hit["os_name"].'('.$hit["device"][0].')",value:"'.$hit["count"].'"},';		
			}
			$hitsdata = substr($hitsdata,0,(strlen($hitsdata) - 1));

		?>
			<script>	
			new Morris.Bar({
				element:'hitschart',
				data:[<?php echo $hitsdata; ?>],
				xkey:'device',
				ykeys:['value'],
				labels:['Hits'],
				xLabelAngle: 45,
				resize:true
				
			});

			</script>
		<?php
		} else { echo "<script>document.getElementById('hitschart').innerHTML = 'No analytics found';</script>"; } 

		if(count($durations) > 0){ 

			$durationsdata = "";
			foreach($durations as $duration){
				$playedduration = $duration['duration'] / 60;

				$playedduration=round($playedduration, 1, PHP_ROUND_HALF_DOWN); //added				
			
				$durationsdata .= '{device:"'.$duration["os_name"].'('.$duration["device"][0].')",value:"'.$playedduration.'"},';		
			}
			$durationsdata = substr($durationsdata,0,(strlen($durationsdata) - 1));

		?>
			<script>	
			new Morris.Bar({
				element:'durationchart',
				data:[<?php echo $durationsdata; ?>],	
				xkey:'device',			
				ykeys:['value'],
				labels:['Duration(in minutes)'],
				xLabelAngle: 45,
				resize:true
				
			});
			</script>	
	<?php
		} else { echo "<script>document.getElementById('durationchart').innerHTML = 'No analytics found';</script>"; } 
	}


?>
</article>
<?php
	include("footer-broadcaster.php");
	

?>

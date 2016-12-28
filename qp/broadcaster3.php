<?php

include("db-config.php");
include("function_common.php");

if(isset($_GET['isadmin'])){

	if($_GET['isadmin'] == 1)
		$_SESSION['customerid'] = $_GET['customer_id'];
		$_SESSION['channelid'] = $_GET['channel_id'];
}


//Access your POST variables
$customer_id = $_SESSION['customerid'];
$channelID=$_SESSION['channelid'];
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
	
}	
	
include("header-broadcaster.php");
function min_hr($time)
{
//echo "original:".$time."\n";
$time=$time/60;
//echo "By 60 in min:".$time."\n";
$notation="min";
if($time>60)
{
//echo "more than a hr";
$time=$time/60;
$notation="hr";
//echo "so in hrs:".$time."\n";
}

$time=round($time, 1, PHP_ROUND_HALF_DOWN);

return array($time,$notation);
}
?>
<style>

.menudiv{
	background: lightgrey;
	border: 2px solid black;
	text-align:center;
	width: 300px;
	margin: 1%;
	color: hsl(0, 0%, 0%);
	float: left;
	height: 100px;
	padding: 2% 3% 3% 3%;
}
form[name="f_timezone"] {
    display: none;
}

footer{width:100%;position:relative;bottom:0}

@media (min-width:768px) and (max-width:768px) 
{
footer{position:fixed !important}

} 

@media (max-width:600px) 
{
footer{position:fixed !important}

} 
@media (min-width:768px) and (max-width:1200px) and (orientation:landscape)
{
footer{position:fixed !important}
}
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
</style>

<div id="content" role="main" style="min-height:500px;margin: 2% 5%;">
		<center><h2>HOME</h2><h3>ALL TIME BROADCASTER</h3></center><br>
<?php


$datenow=new DateTime("now");
$datenow=$datenow->format("Y-m-d H:i:s");
//$datehrbck=$date = date('Y-m-d H:i:s', strtotime('-1 hour')); 
$datehrbck=$date = date('Y-m-d H:i:s', strtotime('-1 minutes'));
//echo "<script>console.log('1:".$datehrbck ."')</script>";
$datehrbckcal=$date = date('Y-m-d H:i:s', strtotime('-1 minutes 30 seconds'));
//echo "<script>console.log('1:".$datehrbckcal ."')</script>";
//$start_or_end="start_datetime";
$start_or_end="end_datetime"; 
$cond = " AND start_datetime >= '".$datehrbckcal."'";
$startdate = $datewkbck;		
$cond .= " AND end_datetime <= '".$datenow."'";
$enddate = $datenow;	

$condition=" AND page_id=".$channelID.""	;
$conditionA=" AND a.page_id=".$channelID.""	;
$wherecond=" where page_id=".$channelID.""	;	

echo "<h4>KNOWN USERS: ";
echo $all_users=get_var("SELECT count(*) as count FROM(SELECT * FROM visitors_info where user_id>0 ".$condition." group by user_id order by id desc) a")."</h4>";
echo '<button id="hide" onclick="show(this.id)" value="HIDE">HIDE ALL</button><br><button id="hits" onclick="show(this.id)" value="HITS">HITS</button><br><button id="dur" onclick="show(this.id)" >DURATION</button>';

echo "<div id='hits_div' style='display:none'>";
echo "<center><h2>Page Hits</h2></center><br>";
echo '<center><!-- button id="chan_hits" onclick="showTable(this.id)" >CHANNEL-HITS</button -->&nbsp;
		<button id="user_chan_hits" onclick="showTable(this.id)" >USER-CHANNEL-HITS</button>&nbsp;
		<!-- button id="user_all_hits" onclick="showTable(this.id)" >USER-ALL-HITS</button -->&nbsp;
		<button id="hide_all_hits" onclick="showTable(this.id)" >HIDE-ALL-HITS</button></center>';

$play_hits=get_var("SELECT count(*) as hits FROM visitors_info where user_id>0 and play=1 ".$condition." ");
if($play_hits>0)
{

$sql1="select a.page_id,b.post_title,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id where b.post_status='publish' and b.post_type='post' ".$conditionA." group by a.page_id order by hits desc" ;
$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt1->execute();
$res1=$stmt1->fetchAll(PDO::FETCH_ASSOC);
echo "<table id='chan_hits_div'><caption> CHANNEL-WISE HITS</caption><thead>";
echo "<tr><th>Channel</th><th>Hits</th></tr>";
echo "</thead><tbody>";
foreach($res1 as $r1)
{
echo "<tr>";
echo "<td>".$r1['post_title']."</td><td>".$r1['hits']."</td>";
echo "</tr>";
}
echo "</tbody></table>";
}
$non_play_hits=get_var("SELECT count(*) as hits FROM visitors_info where user_id>0 and play=0 ".$condition." ");
$total_channel_page_hits=get_var("SELECT count(*) as hits FROM visitors_info where user_id>0 ".$condition." ");
$total_channel_page_hits=$play_hits+$non_play_hits;
$total_all_page_hits=get_var("SELECT count(*) as hits FROM visitors_info ".$wherecond."");


$sql9="select a.user_id,a.user_name,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id where a.user_id>0 and a.play=1 and b.post_status='publish' and b.post_type='post' ".$conditionA." group by a.user_name order by hits desc";
$stmt9 = $dbcon->prepare($sql9, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt9->execute();
$res9=$stmt9->fetchAll(PDO::FETCH_ASSOC);
$hits=$res9;
echo "<table id='user_chan_hits_div'><caption>Users Channel-viewing hits:</caption><thead>";
echo "<tr><th>User</th><th>Hits</th></tr>";
echo "</thead><tbody>";
foreach($res9 as $r9)
{
echo "<tr>";
echo "<td>".$r9['user_name']."</td>";
$sql91="select a.user_name,a.page_id,b.post_title,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id where a.user_id>0 and b.post_status='publish' and b.post_type='post' and a.play=1 and user_id=".$r9['user_id']." ".$conditionA." group by a.user_name,a.page_id order by hits desc"; 
$stmt91 = $dbcon->prepare($sql91, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt91->execute();
$res91=$stmt91->fetchAll(PDO::FETCH_ASSOC);
$hitsInfo="";
if(count($res91)>0)
{
$hitsInfo="<strong><u><CHANNEL:</u> </strong><u> HITS</u> <br>";
}
foreach($res91 as $r91)
{

$hitsInfo.= "<strong>".$r91['post_title'].": </strong>".$r91['hits']."<br>";
}
echo "<td style='width:150px'><a href='javascript:void(0)' class=''>".$r9['hits']."</a></td>";
echo "</tr>";
}
echo "</tbody></table>";

/*$sql10="select a.user_name,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id ".$conditionA." group by a.user_name order by hits desc";
$stmt10 = $dbcon->prepare($sql10, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt10->execute();
$res10=$stmt10->fetchAll(PDO::FETCH_ASSOC);
echo "<table id='user_all_hits_div'><caption>Users all page hits:</caption><thead>";
echo "<tr><th>User</th><th>Hits</th></tr>";
echo "</thead><tbody>";
foreach($res10 as $r10)
{
echo "<tr>";
echo "<td>".$r10['user_name']."</td><td>".$r10['hits']."</td>";
echo "</tr>";
}

echo "</tbody></table>";*/

echo "</div>";

echo '<div id="hitschart"></div>
			<br><br>';
if(count($hits) > 0){ 

			$hitsdata = "";
			foreach($hits as $hit){
				$hitsdata .= '{date:"'.$hit["user_name"].'",value:"'.$hit["hits"].'"},';	

				
			}
			$hitsdata = substr($hitsdata,0,(strlen($hitsdata) - 1));

			
		?>
			<script>	
			new Morris.Bar({
				element:'hitschart',
				data:[<?php echo $hitsdata; ?>],
				 /*events: ['<?php echo $datewkbckcal ?>'],*/
    				eventStrokeWidth: 0,
   				 resize: true,
				xkey:['date'],
				ykeys:['value'],
				labels:['Hits'],
				xLabels: ['day'],
				/*xLabelMargin: 10,
				xLabelAngle:90,*/
				/*xLabelFormat: function(d) {
    return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov', 'Dec'][d.getMonth()] + ' ' + d.getDate();
	
}*/
			});
			</script>
		<?php
		} else { echo "<script>document.getElementById('hitschart').innerHTML = 'No analytics found';</script>"; } 



echo "<div class='panel-body'>";
echo "<h4>All Channel Hits:".$total_channel_page_hits."</h4>";
echo "<h4>Channel Play Hits:".$play_hits."</h4>";
echo "<h4>Channel Non-Play Hits:".$non_play_hits."</h4>";
//echo "<h4>All Page Hits:".$total_all_page_hits."</h4>";
echo "</div>";

echo "<div id='dur_div' style='display:none'>";
echo "<h2>Durations</h2>";
echo '<center>
	<!-- button id="chan_dur" onclick="showTable(this.id)" >CHANNEL-DURATIONS</button -->&nbsp;
	<button id="user_chan_dur" onclick="showTable(this.id)" >USER-CHANNEL-DURATIONS</button>&nbsp;
	<!-- button id="user_all_dur" onclick="showTable(this.id)" >USER-ALL-DURATIONS</button -->&nbsp;
	<button id="hide_all_dur" onclick="showTable(this.id)" >HIDE-ALL-DURATIONS</button></center>';

$play_duration=get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0 and play=1 ".$condition."");
if($play_duration>0)
{
$sql2="select a.page_id,b.post_title,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id where b.post_status='publish' and b.post_type='post' ".$conditionA." group by a.page_id order by duration desc" ;
$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt2->execute();
$res2=$stmt2->fetchAll(PDO::FETCH_ASSOC);

echo "<table id='chan_dur_div'><caption>CHANNEL-WISE DURATIONS</caption><thead>";
echo "<tr><th>Channel</th><th>Durations</th></tr>";
echo "</thead><tbody>";
foreach($res2 as $r2)
{
echo "<tr>";
$duration = $r2["duration"];
//$duration = ($r2["duration"] / 60);
//$time="min";
//if($duration>60)
//{
//$time="hr";
//$duration = ($r2["duration"] / 3600);
//}
//$duration=round($duration, 1, PHP_ROUND_HALF_DOWN); //added
echo "<td>".$r2['post_title']."</td><td>".min_hr($duration)[0]."(".min_hr($duration)[1].")</td>";
echo "</tr>";
}
echo "</tbody></table>";
}
$nonplay_duration=get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0 and play=0 ".$condition."");
$total_duration=get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0 ".$condition."");
$all_page_duration=get_var("SELECT sum(duration) as duration FROM visitors_info ".$wherecond." and user_id>0");


//$sql6="select a.user_id,a.user_name,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id where a.user_id>0 and a.play=1 and b.post_status='publish' and b.post_type='post' ".$conditionA." group by a.user_name order by duration desc";
$sql6="select a.user_id,a.user_name,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id where a.user_id>0 and a.play=1 and b.post_status='publish' and b.post_type='post' ".$conditionA." group by a.user_name order by duration desc";
$stmt6 = $dbcon->prepare($sql6, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt6->execute();
$res6=$stmt6->fetchAll(PDO::FETCH_ASSOC);
$durations=$res6;
echo "<table id='user_chan_dur_div'><caption>Users Channel-viewing duration:</caption><thead>";
echo "<tr><th>User</th><th>Durations</th></tr>";
//echo "<h4>Users Channel-viewing duration:</h4>";
echo "</thead><tbody>";
$durationsdata = "";
foreach($res6 as $r6)
{
$durationAll=$r6["duration"];
$durationAll = ($r6["duration"] / 60);
$durationAll=round($durationAll, 1, PHP_ROUND_HALF_DOWN); //added
echo "<tr>";
echo "<td>".$r6['user_name']."</td>";
//$sql61="select a.page_id,b.post_title,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id where b.post_status='publish' and b.post_type='post' and user_id=".$r6['user_id']." and a.play=1 and duration>0 ".$conditionA." group by a.page_id order by duration desc" ;
//$stmt61 = $dbcon->prepare($sql61, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
//$stmt61->execute();
//$res61=$stmt61->fetchAll(PDO::FETCH_ASSOC);
$durationInfo="";
//if(count($res61)>0)
//{
//$durationInfo="<strong><u><CHANNEL:</u> </strong><u> DURATIONS</u> <br>";
//}
//foreach($res61 as $r61)
//{
//$durationChan = $r61["duration"];
//$durationChan = ($r61["duration"] / 60);
//$durationChan=round($durationChan, 1, PHP_ROUND_HALF_DOWN); //added
//$durationInfo.= "<strong>".$r61['post_title'].": </strong>".min_hr($durationChan)[0]."(".min_hr($durationChan)[1].")<br>";
//}

//$playedduration = $duration['duration'] / 60;

				//$playedduration=round($playedduration, 1, PHP_ROUND_HALF_DOWN); //added				
			
				$durationsdata .= '{device:"'.$r6['user_name'].'('.$durationAll.')",value:"'.$durationAll.'"},';		
echo "<td style='width:150px'><a href='javascript:void(0)' class=''>".min_hr($durationAll)[0]."(".min_hr($durationAll)[1].")</a></td>";
echo "</tr>";
}
echo "</tbody></table>";

/*$sql7="select a.user_name,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id ".$conditionA." group by a.user_name order by duration desc";
$stmt7 = $dbcon->prepare($sql7, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt7->execute();
$res7=$stmt7->fetchAll(PDO::FETCH_ASSOC);
echo "<table id='user_all_dur_div'><caption>Users all pages duration:</caption><thead>";
echo "<tr><th>User</th><th>Durations</th></tr>";
//echo "<h4>Users all pages duration:</h4>";
echo "</thead><tbody>";
foreach($res7 as $r7)
{
$duration = $r7["duration"];
//$duration = ($r7["duration"] / 60);
//$duration=round($duration, 1, PHP_ROUND_HALF_DOWN); //added
echo "<tr>";
echo "<td>".$r7['user_name']."</td><td>".min_hr($duration)[0]."(".min_hr($duration)[1].")</td>";
echo "</tr>";
}
echo "</tbody></table>";*/

echo "</div>";
echo '<div id="durationchart"></div>';
if(count($durations) > 0){ 

			$durationsdata = "";
			foreach($durations as $duration){
				$playedduration = $duration['duration'] / 60;

				$playedduration=round($playedduration, 1, PHP_ROUND_HALF_DOWN); //added				
			
				$durationsdata .= '{device:"'.$duration["user_name"].'",value:"'.$playedduration.'"},';		
			}
			$durationsdata = substr($durationsdata,0,(strlen($durationsdata) - 1));

?><script>	
			new Morris.Bar({
				element:'durationchart',
				data:[<?php echo $durationsdata; ?>],	
				 /*events: [''],*/
    				eventStrokeWidth: 0,
   				 resize: true,
				xkey:['device'],
				ykeys:['value'],
				labels:['Durations (in min)'],
				xLabels: ['day'],
				/*xLabelMargin: 10,
				xLabelAngle:90,*/
				/*xLabelFormat: function(d) {
    return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov', 'Dec'][d.getMonth()] + ' ' + d.getDate();
	
}*/
				
			});
			</script>	

<?php }

echo  "<div class='panel-body'>";
echo "<h3>Channel Viewing duration:".min_hr($total_duration)[0].min_hr($total_duration)[1]."</h3>";
echo "<h3>Channel Viewing Play-duration:".min_hr($play_duration)[0].min_hr($play_duration)[1]."</h3>";
echo "<h3>Channel Viewing Non-play duration:".min_hr($nonplay_duration)[0].min_hr($nonplay_duration)[1]."</h3>";

//echo "<h3>All Page Viewing duration:".min_hr($all_page_duration)[0].min_hr($all_page_duration)[1]."</h3>";
echo "</div>";

?>
</div>
	<script>
jQuery('#chan_dur_div').hide();
jQuery('#user_chan_dur_div').hide();
jQuery('#user_all_dur_div').hide();
jQuery('#chan_hits_div').hide();
jQuery('#user_chan_hits_div').hide();
jQuery('#user_all_hits_div').hide();

function show(id)
{
var id=id;

if(id=='hits')
{
jQuery('#hits_div').show();
jQuery('#dur_div').hide();
}
else if(id=='dur')
{
jQuery('#hits_div').hide();
jQuery('#dur_div').show();
}
else
{
jQuery('#hits_div').hide();
jQuery('#dur_div').hide();
}
}

function showTable(id)
{
var id=id;
if(id=='chan_hits')
{
jQuery('#chan_hits_div').show();
jQuery('#user_chan_hits_div').hide();
jQuery('#user_all_hits_div').hide();

}
if(id=='user_chan_hits')
{
jQuery('#chan_hits_div').hide();
jQuery('#user_chan_hits_div').show();
jQuery('#user_all_hits_div').hide();
}
if(id=='user_all_hits')
{
jQuery('#chan_hits_div').hide();
jQuery('#user_chan_hits_div').hide();
jQuery('#user_all_hits_div').show();
}
if(id=='hide_all_hits')
{
jQuery('#chan_hits_div').hide();
jQuery('#user_chan_hits_div').hide();
jQuery('#user_all_hits_div').hide();
}
if(id=='chan_dur')
{
jQuery('#chan_dur_div').show();
jQuery('#user_chan_dur_div').hide();
jQuery('#user_all_dur_div').hide();

}
if(id=='user_chan_dur')
{
jQuery('#chan_dur_div').hide();
jQuery('#user_chan_dur_div').show();
jQuery('#user_all_dur_div').hide();
}
if(id=='user_all_dur')
{
jQuery('#chan_dur_div').hide();
jQuery('#user_chan_dur_div').hide();
jQuery('#user_all_dur_div').show();
}
if(id=='hide_all_dur')
{
jQuery('#chan_dur_div').hide();
jQuery('#user_chan_dur_div').hide();
jQuery('#user_all_dur_div').hide();
}

}
</script>
<?php	

include("footer-broadcaster.php");	

?>

<?php
include ("db-config.php");

include ("admin-include.php");

include ("function_common.php");

include ("function_common_ext.php");

// Access your POST variables

$customer_id = $_SESSION['adminid'];
$admin_level = $_SESSION['adminlevel'];

// echo "id:".$agent_id;
// Unset the useless session variable

include ("header-admin.php");

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
}
	</style>
	<div id="content" role="main" style="min-height:500px;margin: 2% 5%;">
		<center><h2>HOME</h2></center><br />

<?php
?>

<?php

// exit;

?>
	
<?php
$datenow = new DateTime("now");
$datenow = $datenow->format("Y-m-d H:i:s");

// $datehrbck=$date = date('Y-m-d H:i:s', strtotime('-1 hour'));

$datehrbck = $date = date('Y-m-d H:i:s', strtotime('-1 minutes'));
echo "<script>console.log('1:" . $datehrbck . "')</script>";
$datehrbckcal = $date = date('Y-m-d H:i:s', strtotime('-1 minutes 30 seconds'));
echo "<script>console.log('1:" . $datehrbckcal . "')</script>";

// $start_or_end="start_datetime";

$start_or_end = "end_datetime";
$cond = " AND start_datetime >= '" . $datehrbckcal . "'";
$startdate = $datewkbck;
$cond.= " AND end_datetime <= '" . $datenow . "'";
$enddate = $datenow;
$condition = " AND (end_datetime between '" . $datehrbck . "' and '" . $datenow . "')";
$wherecond = " where (end_datetime between '" . $datehrbck . "' and '" . $datenow . "')";
echo "<div align='center'>
<input type='button' id='known_users' onclick='show_hide(this.id)' value='KNOWN USERS' />
<input type='button' id='loginlogout' onclick='show_hide(this.id)' value='LOGin/out Duration' />
<input type='button' id='channel_analytics' onclick='show_hide(this.id)' value='CHANNEL-WISE ANALYTICS' />
<input type='button' id='user_analytics' onclick='show_hide(this.id)' value='USER-BASED ANALYTICS' />


</div>";
echo "<div align='center' id='known_users_div' style='display:none'>";
echo "<h4>All-time REGISTERED USERS -FOR ADMIN:</h4>";
echo "<h3>" . $all_reg_users = get_var("SELECT count(*) as count FROM(SELECT * FROM wp_users) a") . "</h3>";
echo "<br />";
echo "<h4>All-time KNOWN USERS -FOR ADMIN:</h4>";
echo "<h3>" . $all_users = get_var("SELECT count(*) as count FROM(SELECT * FROM visitors_info where user_id>0 group by user_id order by id desc) a") . "</h3>";
echo "<br />";
echo "<h4>Active or Live Time KNOWN USERS -FOR ADMIN:";
echo "<h3>" . $all_users = get_var("SELECT count(*) as count FROM(SELECT * FROM visitors_info where user_id>0 " . $condition . " group by user_id order by id desc) a") . "</h3>";
echo "</div>";

if ($_REQUEST['startdate'] != "")
	{
	$cond1.= " AND DATE(CONVERT_TZ(start_datetime,'+00:00','" . $coockie . "')) >= '" . $_REQUEST['startdate'] . "'";
	$startdate = $_REQUEST['startdate'];
	$searchstring.= "&startdate=" . $_REQUEST['startdate'];
	$cond1.= " AND DATE(CONVERT_TZ(end_datetime,'+00:00','" . $coockie . "')) <= '" . $_REQUEST['startdate'] . "'";
	$enddate = $_REQUEST['startdate'];
	$searchstring.= "&enddate=" . $_REQUEST['startdate'];
	}

echo "<div align='center' id='loginlogout_div' style='display:none'>";
?>
<form id="xoouserultra-login-form-1" method='post'>
			<div class="xoouserultra-wrap" style="ma-width:100% !important;">
				<div class="xoouserultra-inner xoouserultra-login-wrapper">
					<div class="xoouserultra-main">
<label class="xoouserultra-field-type">	
									<span>Start date:</span>
								</label>

								<div class="xoouserultra-field-value">
									<input required autocomplete="off" type="text" value="<?php
echo $startdate; ?>" id='startdate' name='startdate'>
								</div>
								&nbsp;&nbsp;&nbsp;
								
	<div class="xoouserultra-clear"></div>
							<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							
								<div class="xoouserultra-field-value" style="padding-left: 10px;">
									<input type="hidden" name='page' value="1" id="page">
									<input type="submit" name='filter' value="Filter" class="" name="xoouserultra-login" id="filter">
								</div>
							</div>
							<div class="xoouserultra-clear"></div>
	</div>
				</div>

			</div>
</form>
<?php

if ($_REQUEST['startdate'] != "")
	{
	$x = 1;
	get_loginlogout($dbcon, $cond1, $startdate);
	}

echo "</div>";
?>

<?php
echo "<div align='center' id='channel_analytics_div' style='display:none'>";
echo "<div align='center'>

<input type='button' id='channel_ana_hits' onclick='show_hide(this.id)' value='HITS' />
<input type='button' id='channel_ana_duration' onclick='show_hide(this.id)' value='DURATION' />


</div>";
echo "<h2>All-time Page hits -FOR ADMIN:</h2>";
$play_hits = get_var("SELECT count(*) as hits FROM visitors_info where user_id>0 and play=1");

if ($play_hits > 0)
	{
	$sql1 = "select a.page_id,b.post_title,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id where b.post_status='publish' and b.post_type='post' group by a.page_id order by hits desc";
	$res1 = get_all($sql1);
	echo "<div id='channel_ana_hits_div' style='display:none'>";
	echo "<table><thead><caption>ALL TIME CHANNEL-WISE HITS</caption><thead>";
	echo "<tr><th>CHANNEL</th><th>HITS</th></tr>";

	echo "</thead><tbody>";
	foreach($res1 as $r1)
		{
		echo "<tr>";
		echo "<td>" . $r1['post_title'] . "</td><td>" . $r1['hits'] . "</td>";
		echo "</tr>";
		}

	echo "</tbody></table>";
	}

$non_play_hits = get_var("SELECT count(*) as hits FROM visitors_info where user_id>0 and play=0");
$total_channel_page_hits = get_var("SELECT count(*) as hits FROM visitors_info where user_id>0");
$total_channel_page_hits = $play_hits + $non_play_hits;
echo "<h3>Channel Hits:" . $total_channel_page_hits . "(" . $play_hits . "+" . $non_play_hits . ")</h3>";
$total_all_page_hits = get_var("SELECT count(*) as hits FROM visitors_info");
echo "<br />";
echo "<h3>All Page Hits:" . $total_all_page_hits . "</h3>";
echo "<h2>Active or Live Time Page hits -FOR ADMIN:</h2>";
$play_hits = get_var("SELECT count(*) as hits FROM visitors_info where user_id>0 and play=1" . $condition . "");

if ($play_hits > 0)
	{
	$sql1 = "select a.page_id,b.post_title,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id where b.post_status='publish' and b.post_type='post' " . $condition . " group by a.page_id order by hits desc";
	$res1 = get_all($sql1);
	echo "<table><thead><caption>LIVE CHANNEL-WISE HITS</caption><thead>";
echo "<tr><th>CHANNEL</th><th>HITS</th></tr>";
	echo "</thead><tbody>";
	foreach($res1 as $r1)
		{
		echo "<tr>";
		echo "<td>" . $r1['post_title'] . "</td><td>" . $r1['hits'];
		echo "</tr>";
		}

	echo "</tbody></table>";
	}

$non_play_hits = get_var("SELECT count(*) as hits FROM visitors_info where user_id>0 and play=0" . $condition . " ");
$total_channel_page_hits = get_var("SELECT count(*) as hits FROM visitors_info where user_id>0" . $condition . " ");
echo "<h3>Channel Hits:" . $total_channel_page_hits . "(" . $play_hits . "+" . $non_play_hits . ")</h3>";
$total_all_page_hits = get_var("SELECT count(*) as hits FROM visitors_info" . $wherecond . "");
echo "<h3>All Page Hits:" . $total_all_page_hits . "</h3>";
echo "</div>";
echo "<div id='channel_ana_duration_div' style='display:none'>";
echo "<h2>All-time durations for ADMIN:</h2>";
$play_duration = get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0 and play=1");

if ($play_duration > 0)
	{
	$sql2 = "select a.page_id,b.post_title,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id where b.post_status='publish' and b.post_type='post' group by a.page_id order by duration desc";
	$stmt2 = $dbcon->prepare($sql2, array(
		PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
	));
	$stmt2->execute();
	$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
	echo "<table><thead><caption>ALL TIME CHANNEL-WISE DURATIONS</caption><thead>";
echo "<tr><th>CHANNEL</th><th>DURATION</th></tr>";
	echo "</thead><tbody>";
	foreach($res2 as $r2)
		{
		echo "<tr>";
		$duration = ($r2["duration"]);
		echo "<td>";
		echo $r2['post_title'];
		echo "</td><td>";
		echo min_hr($duration) [0];
		echo " (";
		echo min_hr($duration) [1];
		echo ")</td>";
		echo "</tr>";
		}

	echo "</tbody></table>";
	}

$nonplay_duration = get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0 and play=0");
$total_duration = get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0");
echo "<h3>Channel Viewing duration:" . min_hr($total_duration) [0] . min_hr($total_duration) [1] . "(" . min_hr($play_duration) [0] . min_hr($play_duration) [1] . "+" . min_hr($nonplay_duration) [0] . min_hr($nonplay_duration) [1] . ")</h3>";
$all_page_duration = get_var("SELECT sum(duration) as duration FROM visitors_info");
echo "<h3>All Page Viewing duration:" . min_hr($all_page_duration) [0] . min_hr($all_page_duration) [1] . "</h3>";
echo "<h2>Active / Live-time durations for ADMIN:</h2>";
$play_duration = get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0 and play=1" . $condition . " ");

if ($play_duration > 0)
	{
	$sql2 = "select a.page_id,b.post_title,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id where  b.post_status='publish' and b.post_type='post' " . $condition . " group by a.page_id order by duration desc";
	$res2 = get_all($sql2);
	echo "<table><thead><caption>ACTIVE / LIVE-TIME CHANNEL-WISE DURATIONS</caption><thead>";
echo "<tr><th>CHANNEL</th><th>DURATION</th></tr>";
	echo "</thead><tbody>";
	foreach($res2 as $r2)
		{
		echo "<tr>";
		$duration = ($r2["duration"]);
		echo "<td>" . $r2['post_title'] . "</td><td>" . min_hr($duration) [0] . " (" . min_hr($duration) [1] . ")</td>";
		}

	echo "<tr>";
	}

echo "</tbody></table>";

$nonplay_duration = get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0 and play=0" . $condition . " ");
$total_duration = get_var("SELECT sum(duration) as duration FROM visitors_info where user_id>0" . $condition . " ");
echo "<h3>Channel Viewing duration:" . min_hr($total_duration)[0].min_hr($total_duration)[1]. "(" . min_hr($play_duration)[0].min_hr($play_duration)[1]. "+" . min_hr($nonplay_duration)[0].min_hr($nonplay_duration)[1] . ")</h3>";

$all_page_duration = get_var("SELECT sum(duration) as duration FROM visitors_info" . $wherecond . " ");
echo "<h3>All Page Viewing duration:" . min_hr($all_page_duration)[0].min_hr($all_page_duration)[1] . "</h3>";
echo "</div>";
echo "</div>";

echo "<div align='center' id='user_analytics_div' style='display:none'>";
echo "<h3>ALL TIME</h3>";
$a = range("A", "Z");
echo "<div align='center' style='border:1px solid;background:whitesmoke'><span style='color:orangered'>SELECT GROUP OF USERS STARTING WITH </span><form name='alpha' id='formAlpha' method='post'>";

foreach($a as $char) echo "<input type='button' onclick='return print(this.value)' name='aplhaC' id='" . $char . "' value='" . $char . "'>" . "\n";
echo "</form></div>";


if (isset($_POST['alphaS']))
	{
	$char = $_POST['alphaS'];
	$z = 1;
	$txt = " For Users starting with : <span style='color:orangered'>'" . $_POST['alphaS'] . "'</span> ";
	$alphaCond = "and user_name like '" . $char . "%' ";
	$alphaCondA = "and a.user_name like '" . $char . "%' ";
	$sql = "SELECT * from wp_users where user_login like '" . $char . "%' ";
	$users = get_all($sql);
	echo "<div style='border:1px solid grey;background:wheat'>";
	if (count($users) > 0)
		{
		echo "<span style='color:green'>SELECT A USER</span><br><form name='alphaX' id='formAlphaX' method='post'>";
		echo "<input type='hidden' name='alphaS' value='".$_POST['alphaS']."' />";
		foreach($users as $user)
			{
			echo "<input type='button' onclick='return printX(this.value)' name='aplhaU' id='" . $user['user_login'] . "' value='" . $user['user_login'] . "'>&nbsp;";
			}

		echo "</form>";
		}
	  else
		{
		echo "No Results <sup>*check hits/durations of deleted users if any</sup>";
		}
	echo "</div>";
	}
if (isset($_POST['alphaU']))
	{
	$user = $_POST['alphaU'];
	$sql = "SELECT id from wp_users where user_login='" . $user . "' ";
	$user = get_var($sql);

	// echo $user;

	$alphaXCond = "and user_id=" . $user . " ";
	$alphaXCondA = "and a.user_id=" . $user . " ";
	$z = 1;
	$txt = " For User : <span style='color:green'>'" . $_POST['alphaU']."'</span> ";
	}
if(!isset($_POST['alphaS']) && !isset($_POST['alphaU']))
	{
	$txt=" For All Users : ";
	}

echo "<div align='center'> <center>" . $txt . "</center>



<input type='button' id='user_ana_hits' onclick='show_hide(this.id)' value='HITS' />
<input type='button' id='user_ana_duration' onclick='show_hide(this.id)' value='DURATION' />


</div>";
$sql6 = "select a.user_id,a.user_name,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id where a.user_id>0 and a.play=1 and b.post_status='publish' and b.post_type='post' " . $alphaCondA . $alphaXCondA . " group by a.user_name order by duration desc";
$res6 = get_all($sql6);
echo "<div id='user_ana_duration_div' style='display:none'>";

//echo "<div style='display:inline-flex'>";
echo "<table><caption>TOTAL CHannel-viewing duration:</caption><thead>";
echo "<tr><th>USER</th><th>DURATION</th></tr>";
echo "</thead><tbody>";

foreach($res6 as $r6)
	{
	$durationAll = ($r6["duration"]);
	$userExists=get_var("select ID from wp_users where ID=".$r6['user_id']." ");
	if((int)$userExists == 0)
	{	
		$user_del="<sup style='color:red'>*deleted</sup>";	
	}
	echo "<tr>";
	echo "<td>" . $r6['user_name'] .$user_del. "</td>";
	$sql61 = "select a.page_id,b.post_title,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id where b.post_status='publish' and b.post_type='post' and user_id=" . $r6['user_id'] . " and a.play=1 and duration>0 group by a.page_id order by duration desc";
	$res61 = get_all($sql61);
	$durationInfo = "";
	foreach($res61 as $r61)
		{
		$durationChan = ($r61["duration"]);
		$durationInfo.= "<strong>" . $r61['post_title'] . ": </strong>" . min_hr($durationChan) [0] . " (" . min_hr($durationChan) [1] . ")<br />";
		}

	echo "<td style='width:150px'><a href='javascript:void(0)' class='tooltip'>" . min_hr($durationAll) [0] . " (" . min_hr($durationAll) [1] . ")<span>" . $durationInfo . "</span></a></td>";
	echo "</tr>";
	}

echo "</tbody></table>";
$sql7 = "select a.user_id,a.user_name,sum(duration) as duration from visitors_info a inner join wp_posts b on b.ID=a.page_id " . $alphaCondA . $alphaXCondA . " group by a.user_name order by duration desc";
$res7 = get_all($sql7);
echo "<table><thead><caption>TOTAL all page duration:</caption><thead>";
echo "<tr><th>USER</th><th>DURATION</th></tr>";
echo "</thead><tbody>";

foreach($res7 as $r7)
	{
	$userExists=get_var("select ID from wp_users where ID=".$r7['user_id']." ");
	if((int)$userExists == 0)
	{	
		$user_del="<sup style='color:red'>*deleted</sup>";	
	}
	$duration = ($r7["duration"]);
	echo "<tr>";
	echo "<td>" . $r7['user_name'] .$user_del. "</td><td>" . min_hr($duration) [0] . " (" . min_hr($duration) [1] . ")</td>";
	echo "</tr>";
	}

echo "</tbody></table>";
//echo "</div>";
echo "</div>";


echo "<div align='center' id='user_ana_hits_div' style='display:none'>";
$sql9 = "select a.user_id,a.user_name,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id where a.user_id>0 and a.play=1 and b.post_status='publish' and b.post_type='post' " . $alphaCondA . $alphaXCondA . " group by a.user_name order by hits desc";
$res9 = get_all($sql9);
//echo "<div style='display:inline-flex'>";
echo "<table><caption>Total Channel-viewing hits:</caption><thead>";
echo "<tr><th>USER</th><th>HITS</th></tr>";
echo "</thead><tbody>";

foreach($res9 as $r9)
	{

	$userExists=get_var("select ID from wp_users where ID=".$r9['user_id']." ");
	if((int)$userExists == 0)
	{	
		$user_del="<sup style='color:red'>*deleted</sup>";	
	}
	echo "<tr>";
	echo "<td>" . $r9['user_name'] .$user_del."</td>";
	$sql91 = "select a.user_name,a.page_id,b.post_title,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id where a.user_id>0 and b.post_status='publish' and b.post_type='post' and a.play=1 and user_id=" . $r9['user_id'] . " group by a.user_name,a.page_id order by hits desc";
	$res91 = get_all($sql91);
	$hitsInfo = "";
	foreach($res91 as $r91)
		{
		$hitsInfo.= "<strong>" . $r91['post_title'] . ": </strong>" . $r91['hits'] . "<br />";
		}

	echo "<td style='width:150px'><a href='javascript:void(0)' class='tooltip'>" . $r9['hits'] . "<span>" . $hitsInfo . "</span></a></td>";
	echo "</tr>";
	}

echo "</tbody></table>";
$sql10 = "select a.user_id,a.user_name,count(*) as hits from visitors_info a inner join wp_posts b on b.ID=a.page_id " . $alphaCondA . $alphaXCondA . " group by a.user_name order by hits desc";
$res10 = get_all($sql10);
echo "<table><caption>TOTAL all page hits:</caption><thead>";
echo "<tr><th>USER</th><th>HITS</th></tr>";
echo "</thead><tbody>";

foreach($res10 as $r10)
	{
	$userExists=get_var("select ID from wp_users where ID=".$r10['user_id']." ");
	if((int)$userExists == 0)
	{	
		$user_del="<sup style='color:red'>*deleted</sup>";	
	}
	echo "<tr>";
	echo "<td>" . $r10['user_name'] .$user_del ."</td><td>" . $r10['hits'] . "</td>";
	echo "</tr>";
	}

echo "</tbody></table>";
//echo "<div>";

echo "</div>";
echo "</div>";
?>
</div>
<script>



function show_hide(id)
{

// alert(id);
// if(jQuery("#"+id+"_div").hide())
// jQuery("#"+id+"_div").show();
// else if(jQuery("#"+id+"_div").show())
// jQuery("#"+id+"_div").hide();

var x=id+"_div";

// alert(x);
// alert(document.getElementById(x).style.display);

if(document.getElementById(x).style.display=="none")
{
hide_all();
jQuery("#"+x).parent().show();
document.getElementById(x).style.display="block";


}else
document.getElementById(x).style.display="none"

}
function hide_all()
{

// document.getElementById("known_users_div").style.display="none";
// document.getElementById("channel_analytics_div").style.display="none";

jQuery("#known_users_div").hide();
jQuery("#channel_analytics_div").hide();
jQuery("#user_analytics_div").hide();
jQuery("#user_ana_hits_div").hide();
jQuery("#channel_ana_duration_div").hide();
jQuery("#channel_ana_hits_div").hide();
jQuery("#user_ana_duration_div").hide();
jQuery("#loginlogout_div").hide();


// jQuery("#known_users_div").hide();
// jQuery("#known_users_div").hide();
// jQuery("#known_users_div").hide();

}
</script>
<script>
function print(alpha)
{
var input = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "alphaS").val(alpha);
jQuery('#formAlpha').append(jQuery(input));
 jQuery("#formAlpha").submit();
}
function printX(user)
{
var input = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "alphaU").val(user);
jQuery('#formAlphaX').append(jQuery(input));
 jQuery("#formAlphaX").submit();
}
</script>
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
<?php

if ($x == 1)
	{
	echo "<script>show_hide('loginlogout')</script>";
	}

if ($z == 1)
	{
	echo "<script>show_hide('user_analytics')</script>";
	}
echo "<div class='clear' style='clear'></div>";
include ("footer-admin.php");

?>

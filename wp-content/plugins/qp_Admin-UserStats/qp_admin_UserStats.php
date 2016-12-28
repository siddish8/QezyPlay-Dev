<?php 

/*
Plugin Name: Admin Display: User Stats
Plugin URI: 
Description: User stats
Author: IB
Version: 1.0
Author URI: ib
*/

add_action('admin_menu', 'admin_user_stats_menu');
 
function admin_user_stats_menu(){
        add_menu_page( 'Admin - UserStats Plugin Page', 'Admin- UserStats', 'manage_options', 'user-stats-plugin', 'userstats_init' );
}
 
function userstats_init(){
	
    echo "<h1>User Stats</h1><br/>";
	user_stats_db();
}

add_shortcode('UserStatsAdmin','user_stats_db');

function user_stats_db()
{
global $wpdb;


if(isset($_POST['date_submitInt']))
{
$date_int=$_POST['this_dateInt'];
$res1=$wpdb->get_results("SELECT a.ID,a.user_login,b.membership_id,b.billing_amount,b.startdate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL $date_int DAY");

$res2=$wpdb->get_results("SELECT a.ID,a.user_login,a.user_email,b.meta_value,a.user_registered FROM wp_users a inner join wp_usermeta b on a.ID=b.user_id where a.user_registered>=CURDATE() - INTERVAL $date_int DAY and b.meta_key='phone' order by id desc");

}

else if(isset($_POST['date_submit']))
{
$date=$_POST['this_date'];
$res1=$wpdb->get_results("SELECT a.ID,a.user_login,b.membership_id,b.billing_amount,b.startdate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate between CURDATE() and ".$date."");

$res2=$wpdb->get_results("SELECT a.ID,a.user_login,a.user_email,b.meta_value,a.user_registered FROM wp_users a inner join wp_usermeta b on a.ID=b.user_id where (a.user_registered between CURDATE() and ".$date.") and (b.meta_key='phone') order by id desc");
}
 else
{
$date_int=10;
$res1=$wpdb->get_results("SELECT a.ID,a.user_login,b.membership_id,b.billing_amount,b.startdate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL 10 DAY");

$res2=$wpdb->get_results("SELECT a.ID,a.user_login,a.user_email,b.meta_value,a.user_registered FROM wp_users a inner join wp_usermeta b on a.ID=b.user_id where a.user_registered>=CURDATE() - INTERVAL 10 DAY and b.meta_key='phone' order by id desc");
}


//echo $date_int;
$date=$wpdb->get_var("SELECT now()");
//echo $date;
/*echo '

<p id="ct"></p>

<script type="text/javascript"> 

display_ct();

function display_c(){
var refresh=1000; // Refresh rate in milli seconds
mytime=setTimeout("display_ct()",refresh)
}

function display_ct() {
var strcount
 var x = new Date() 
var x = new Date("'.$wpdb->get_var("SELECT now()").'");
document.getElementById("ct").innerHTML = x;
tt=display_c();
}
</script>

';*/


echo '<style>

.mx{max-width:80%;margin:0 auto !important;}
 /* Style the list */
ul.tab {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Float the list items side by side */
ul.tab li {float: left;}

/* Style the links inside the list items */
ul.tab li a {
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
ul.tab li a:hover {background-color: #ddd;}

/* Create an active/current tablink class */
ul.tab li a:focus, .active {background-color: #ccc;}

/* Style the tab content */
.tabcontent {
    display: none;
       border-top: none;
}
</style>';

echo '<div align="center" style="margin:0 auto">
<form method="post" id="dateform">
<label>Type Date</label><input type="text" placeholder="YYYY-mm-dd" id="this_date" name="this_date" />
<br />
<input type="submit" name="date_submit" id="date_submit" value="Submit"/></div>
<br />
<div align="center" style="margin:0 auto">---- OR -----</div>
<br />
<div align="center" style="margin:0 auto">
<form method="post" id="dateIntform">
<label>Type Days</label><input type="text" placeholder="10" id="this_dateInt" name="this_dateInt" />
<br />
<input type="submit" name="date_submitInt" id="date_submitInt" value="Submit"/></div>
<br />
<br />
<br />';

echo '<ul class="tab mx">

<li><a id="UR" href="#" class="tablinks" onclick="userStats(event, \'UserRegs\')">User Registartions List</a></li>
  <li><a id="US" href="#" class="tablinks" onclick="userStats(event, \'UserSubs\')">User Subscriptions List</a></li>
<li><a id="QT" href="#" class="tablinks" onclick="userStats(event, \'QezyTable\')">QezyPlay Daily Table</a></li>
  
 </ul>';

echo '<div id="UserSubs" class="tabcontent mx">
		<table class="widefat membership-levels" style="width:100% !important;">
			<thead>
				<tr>	
					<th>S.No</th>				
					<th>User ID</th>
					<th>User Name</th>
					<th>Plan ID</th>
					<th>Paid Amount</th>
					<th>Paid Date</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			//$res=$wpdb->get_results("SELECT * FROM user_contact"); 

			//$res=$wpdb->get_results("SELECT a.ID,a.user_login,b.membership_id,b.billing_amount,b.startdate FROM qezyplay_newshonar.wp_users a inner join qezyplay_newshonar.wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL $date_int DAY");
			$id=1;
			foreach($res1 as $row){
			
				
				$user_id=$row->ID;
				$user_name=$row->user_login;
				$plan_id=$row->membership_id;
				$paid_amnt=$row->billing_amount;
				$paid_date=$row->startdate;
								
		
			echo 	'<tr style="" class="ui-sortable-handle">	
				<td style="width: 192px;" class="level_name">'.$id.'</td>						
				<td style="width: 192px;" class="level_name">'.$user_id.'</td>
				<td style="width: 192px;" class="level_name">'.$user_name.'</td>
				<td style="width: 192px;">'.$plan_id.'</td>
				<td style="width: 342px;">'.$paid_amnt.'</td>
				<td style="width: 342px;">'.$paid_date.'</td>
				
				</tr>';
			$id=$id+1;
			 } 
			
			echo '</tbody>
		</table>
	</div>
	<div class="clear"></div><br /><br />
	 <div id="UserRegs" class="tabcontent mx">
		<table class="widefat membership-levels" style="width:100% !important;">
			<thead>
				<tr>		
					<th>S.No</th>			
					<th>User ID</th>
					<th>User Name</th>
					<th>User E-mail</th>
					<th>Phone</th>
					<th>Regd. Date</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			//$res=$wpdb->get_results("SELECT * FROM user_feedback"); 

			//$res=$wpdb->get_results("SELECT a.ID,a.user_login,a.user_email,b.meta_value,a.user_registered FROM qezyplay_newshonar.wp_users a inner join qezyplay_newshonar.wp_usermeta b on a.ID=b.user_id where a.user_registered>=CURDATE() - INTERVAL $date_int DAY and b.meta_key='phone' order by id desc");
			$id=1;
			foreach($res2 as $row){

				
				$user_id=$row->ID;
				$user_name=$row->user_login;
				$user_mail=$row->user_email;
				$phone=$row->meta_value;
				$reg_date=$row->user_registered;
								
		
			echo 	'<tr style="" class="ui-sortable-handle">
				<td style="width: 192px;" class="level_name">'.$id.'</td>								
				<td style="width: 192px;" class="level_name">'.$user_id.'</td>
				<td style="width: 192px;" class="level_name">'.$user_name.'</td>
				<td style="width: 192px;" class="level_name">'.$user_mail.'</td>
				<td style="width: 184px;">'.$phone.'</td>
				<td style="width: 342px;">'.$reg_date.'</td>
				
				</tr>';
			$id=$id+1;
			 } 
			
			echo '</tbody>
		</table>
	</div>';

if(isset($_POST['specific_date_submit']))
{
$sp_date=$_POST['specific_date'];
}
else
{
$sp_date=new DateTime("now");
$sp_date=$sp_date->format("Y-m-d");
}

echo '<div id="QezyTable" class="tabcontent mx" align="center">
<form method="post"><label>Specific Date</label><input type="text" placeholder="YYYY-mm-dd" id="specific_date" name="specific_date" />
<br />
<input type="submit" name="specific_date_submit" id="specific_date_submit" value="Submit"/></form>
<br />
<br />
<br />
		<table class="table table-striped" style="text-align:center;width:100% !important;">
			<thead>
				<tr>	
					<th></th>		
					<th>DATE</th>
					<th>No. of REGISTRATIONS</th>
					<th>Free Trails</th>
					<th>Quarterly</th>
					<th>Half-Yearly</th>
					<th>Yearly</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			//$res=$wpdb->get_results("SELECT * FROM user_contact"); 

			$date=$wpdb->get_var("SELECT date(curdate()-1)");
			
			$reg_count=$wpdb->get_var("SELECT count(ID) from wp_users where user_registered between '$date' and curdate()");
			
			$freetrail_count=$wpdb->get_var("SELECT count(membership_id) from wp_pmpro_memberships_users where membership_id=4 and startdate between '$date' and curdate()");
			$quarterly_count=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total=0 and (DATE(timestamp) between '$date' and curdate()) ");
			$quarterly_star=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total>0 and (DATE(timestamp) between '$date' and curdate())");
			$halfyearly_count=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total=0 and (DATE(timestamp) between '$date' and curdate())");
			$halfyearly_star=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total>0 and (DATE(timestamp) between '$date' and curdate())");
			$yearly_count=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total=0 and (DATE(timestamp) between '$date' and curdate())");
			$yearly_star=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total>0 and (DATE(timestamp) between '$date' and curdate())");

				

$q=	$quarterly_count + 	$quarterly_star;
$h=	$halfyearly_count + 	$halfyearly_star;
$y=	$yearly_count + 	$yearly_star;						
		
			echo 	'<tr style="" class="ui-sortable-handle">
				<td style="width: 192px;" class="level_name">Yesterday: </td>		
				<td style="width: 192px;" class="level_name">'.$date.'</td>						
				<td style="width: 192px;" class="level_name">'.$reg_count.'</td>
				<td style="width: 192px;" class="level_name">'.$freetrail_count.'</td>
				<td style="width: 192px;">'.$q.' ('.$quarterly_count.'+'.$quarterly_star.'<sup>*</sup>)</td>
				<td style="width: 342px;">'.$h.' ('.$halfyearly_count.'+'.$halfyearly_star.'<sup>*</sup>)</td>
				<td style="width: 342px;">'.$y.' ('.$yearly_count.'+'.$yearly_star.'<sup>*</sup>)</td>
				
				</tr>';




//echo $sp_date;
			$reg_countS=$wpdb->get_var("SELECT count(ID) from wp_users where date(user_registered)='$sp_date'");
			
			$freetrail_countS=$wpdb->get_var("SELECT count(membership_id) from wp_pmpro_memberships_users where membership_id=4 and date(startdate)='$sp_date'");
			$quarterly_countS=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total=0 and (DATE(timestamp)='$sp_date')");
			$quarterly_starS=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total>0 and (DATE(timestamp)='$sp_date')");
			$halfyearly_countS=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total=0 and (DATE(timestamp)='$sp_date')");
			$halfyearly_starS=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total>0 and (DATE(timestamp)='$sp_date')");
			$yearly_countS=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total=0 and (DATE(timestamp)='$sp_date')");
			$yearly_starS=$wpdb->get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total>0 and (DATE(timestamp)='$sp_date')");

$qS=	$quarterly_countS + 	$quarterly_starS;
$hS=	$halfyearly_countS + 	$halfyearly_starS;
$yS=	$yearly_countS + 	$yearly_starS;	

			echo 	'<tr style="" class="ui-sortable-handle">
				<td style="width: 192px;" class="level_name">Specific(default:Today): </td>	
				<td style="width: 192px;" class="level_name">'.$sp_date.'</td>						
				<td style="width: 192px;" class="level_name">'.$reg_countS.'</td>
				<td style="width: 192px;" class="level_name">'.$freetrail_countS.'</td>
				<td style="width: 192px;">'.$qS.' ('.$quarterly_countS.'+'.$quarterly_starS.'<sup>*</sup>)</td>
				<td style="width: 342px;">'.$hS.' ('.$halfyearly_countS.'+'.$halfyearly_starS.'<sup>*</sup>)</td>
				<td style="width: 342px;">'.$yS.' ('.$yearly_countS.'+'.$yearly_starS.'<sup>*</sup>)</td>
				
				</tr>';
						
			echo '</tbody>
		</table>*Paid Revenue
	</div>
	<div class="clear"></div><br /><br />';
echo '<script>
function userStats(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";

}
</script>';
}
?>
<?php

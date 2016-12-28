<?php 

include("agent-config.php");
include("customer-include.php");
include("function_common.php");

include("header-admin.php");

function get_var($sql){
	
	global $dbcon;
	
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		$result = $stmt->fetchColumn();		
		//return $result[0];
		return $result;
		
	}catch (PDOException $e){
		print $e->getMessage();
	}
}


$start_limit = 0;
@$page = isset($_GET['page']) ? $_GET['page'] : $_POST['page'];
if (!isset($page))
    $page = 1;
if ($page > 1)
    $start_limit = (($page * ROW_PER_PAGE) - ROW_PER_PAGE);

if(isset($_POST['date_submitInt']))
{
$date_int=$_POST['this_dateInt'];
$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL $date_int DAY", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt1->execute();
$res1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);


//$res2=$wpdb->get_results("SELECT a.ID,a.user_login,a.user_email,b.meta_value,a.user_registered FROM wp_users a inner join wp_usermeta b on a.ID=b.user_id where a.user_registered>=CURDATE() - INTERVAL $date_int DAY and b.meta_key='phone' order by id desc");


$stmt2 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where  a.user_registered>=CURDATE() - INTERVAL $date_int DAY order by id desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt2->execute();
$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

}

else if(isset($_POST['date_submit']))
{
$date=$_POST['this_date'];
$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate between CURDATE() and ".$date."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt1->execute();
$res1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

//$res2=$wpdb->get_results("SELECT a.ID,a.user_login,a.user_email,b.meta_value,a.user_registered FROM wp_users a inner join wp_usermeta b on a.ID=b.user_id where (a.user_registered between CURDATE() and ".$date.") and (b.meta_key='phone') order by id desc");

$stmt2 = $dbcon->prepare("a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where (a.user_registered between CURDATE() and ".$date.") order by a.ID desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt2->execute();
$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

}
 else
{
$date_int=10;
$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL 10 DAY", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt1->execute();
$res1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

//$res2=$wpdb->get_results("SELECT a.ID,a.user_login,a.user_email,b.meta_value,a.user_registered FROM wp_users a inner join wp_usermeta b on a.ID=b.user_id where a.user_registered>=CURDATE() - INTERVAL 10 DAY and b.meta_key='phone' order by id desc");

$stmt2 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where a.user_registered>=CURDATE() - INTERVAL 10 DAY order by a.ID desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt2->execute();
$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

}


//echo $date_int;
$stmt3 = $dbcon->prepare("SELECT now()");
$stmt3->execute();
$date = $stmt3->fetch(PDO::FETCH_ASSOC);

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

echo '<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.js"></script>';

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
		<!-- <table class="widefat membership-levels" style="width:100% !important;"> -->
		<table style="overflow-x:auto;min-width:50%;max-width:100% !important;" width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>	
					<th>S.No</th>				
					<th>User ID</th>
					<th>User Name(E-mail)</th>
					<th>Phone</th>
					<th>Plan ID</th>
					<th>Paid Amount</th>
					<th>Paid Date</th>
					<th>End Date</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			//$res=$wpdb->get_results("SELECT * FROM user_contact"); 

			//$res=$wpdb->get_results("SELECT a.ID,a.user_login,b.membership_id,b.billing_amount,b.startdate FROM qezyplay_newshonar.wp_users a inner join qezyplay_newshonar.wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL $date_int DAY");
			$id=1;
			foreach($res1 as $row){
			
				
				$user_id=$row['ID'];
				$user_name=$row['user_login'];
				$user_email=$row['user_email'];
				$phone_no=$row['phone'];
				$fb=$row['user_url'];
				if($phone!="xxxxxxxxxxx")
				{
				$contact=$phone_no;
				}				
			        else
				{
				$contact=$fb;
				}
				
				$plan_id=$row['membership_id'];
				$paid_amnt=$row['billing_amount'];
				$paid_date=$row['startdate'];
				$end_date=$row['enddate'];
								
		

		//$phone_no=$wpdb->get_var("SELECT meta_value FROM wp_usermeta where meta_key='phone' and user_id=$user_id ");

			echo 	'<tr style="" class="ui-sortable-handle">	
				<td style="width: 192px;" class="level_name">'.$id.'</td>						
				<td style="width: 192px;" class="level_name">'.$user_id.'</td>
				<td style="width: 342px;" class="level_name">'.$user_name.'('.$user_email.')</td>
				<td style="width: 192px;" class="level_name">'.$contact.'</td>
				<td style="width: 192px;">'.$plan_id.'</td>
				<td style="width: 342px;">'.$paid_amnt.'</td>
				<td style="width: 342px;">'.$paid_date.'</td>
				<td style="width: 342px;">'.$end_date.'</td>
				
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
					<th>IP Address</th>
					<th>City(State,Country)</th>
					<th>Regd. Date</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			//$res=$wpdb->get_results("SELECT * FROM user_feedback"); 

			//$res=$wpdb->get_results("SELECT a.ID,a.user_login,a.user_email,b.meta_value,a.user_registered FROM qezyplay_newshonar.wp_users a inner join qezyplay_newshonar.wp_usermeta b on a.ID=b.user_id where a.user_registered>=CURDATE() - INTERVAL $date_int DAY and b.meta_key='phone' order by id desc");
			$id=1;
			foreach($res2 as $row){

				
				$user_id=$row['ID'];
				$user_name=$row['user_login'];
				$user_mail=$row['user_email'];
				//$phone=$row['meta_value'];
				$phone=$row['phone'];
				$fb=$row['user_url'];

				if($phone!="xxxxxxxxxxx")
				{
				$contact=$phone;
				}				
			        else
				{
				$contact=$fb;
				}
				$reg_date=$row['user_registered'];

				

				$user_ip=get_var('SELECT meta_value FROM wp_usermeta where meta_key="uultra_user_registered_ip" and user_id='.$user_id.' ');
				
				//$user_ip = $user_ip_arr['meta_value'];

				$user_city=get_var('SELECT city FROM visitors_info where ip_address="'.$user_ip.'" ');
				//$user_city=$user_city_arr['city'];
				$user_state=get_var('SELECT state FROM visitors_info where ip_address="'.$user_ip.'" ');
				//$user_state=$user_state_arr['state'];
				$user_country=get_var('SELECT country FROM visitors_info where ip_address="'.$user_ip.'" ');
				//$user_country=$user_country_arr['country'];
								
		
			echo 	'<tr style="" class="ui-sortable-handle">
				<td style="width: 192px;" class="level_name">'.$id.'</td>								
				<td style="width: 192px;" class="level_name">'.$user_id.'</td>
				<td style="width: 192px;" class="level_name">'.$user_name.'</td>
				<td style="width: 192px;" class="level_name">'.$user_mail.'</td>
				<td style="width: 184px;">'.$contact.'</td>
				<td style="width: 184px;">'.$user_ip.'</td>
				<td style="width: 342px;">'.$user_city.'('.$user_state.','.$user_country.')</td>
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
					<th>Half-Yearly*</th>
					<th>Yearly*</th>
					<th>Quarterly</th>
					<th>Half-Yearly</th>
					<th>Yearly</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			//$res=$wpdb->get_results("SELECT * FROM user_contact"); 

			$date=get_var("SELECT date(curdate()-1)");
			//$date=$date_arr['dateS'];
			
			$reg_count=get_var("SELECT count(ID) from wp_users where user_registered between '$date' and curdate()");		
			//$reg_count=$reg_count_arr['counterR'];
			
			$freetrail_count=get_var("SELECT count(membership_id) from wp_pmpro_memberships_users where membership_id=4 and startdate between '$date' and curdate()");
			//$freetrail_count=$freetrail_count_arr['counterF'];

			$halfyearly_countFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=6 and status='success' and total=0 and (DATE(timestamp) between '$date' and curdate())");
			$halfyearly_starFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=6 and status='success' and total>0 and (DATE(timestamp) between '$date' and curdate())");

			$yearly_countFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=7 and status='success' and total=0 and (DATE(timestamp) between '$date' and curdate())");
			$yearly_starFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=7 and status='success' and total>0 and (DATE(timestamp) between '$date' and curdate())");



			$quarterly_count=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total=0 and (DATE(timestamp) between '$date' and curdate()) ");
			$quarterly_star=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total>0 and (DATE(timestamp) between '$date' and curdate())");



			$halfyearly_count=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total=0 and (DATE(timestamp) between '$date' and curdate())");
			$halfyearly_star=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total>0 and (DATE(timestamp) between '$date' and curdate())");
			
			$yearly_count=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total=0 and (DATE(timestamp) between '$date' and curdate())");
			$yearly_star=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total>0 and (DATE(timestamp) between '$date' and curdate())");

				

$q=	$quarterly_count + 	$quarterly_star;
$h=	$halfyearly_count + 	$halfyearly_star;
$y=	$yearly_count + 	$yearly_star;	
$hFO=	$halfyearly_countFO + 	$halfyearly_starFO;
$yFO=	$yearly_countFO + 	$yearly_starFO;						
		
			echo 	'<tr style="" class="ui-sortable-handle">
				<td style="width: 192px;" class="level_name">Yesterday: </td>		
				<td style="width: 192px;" class="level_name">'.$date.'</td>						
				<td style="width: 192px;" class="level_name">'.$reg_count.'</td>
				<td style="width: 192px;" class="level_name">'.$freetrail_count.'</td>
				<td style="width: 342px;">'.$hFO.' ('.$halfyearly_countFO.'+'.$halfyearly_starFO.'<sup>*</sup>)</td>
				<td style="width: 342px;">'.$yFO.' ('.$yearly_countFO.'+'.$yearly_starFO.'<sup>*</sup>)</td>
				<td style="width: 192px;">'.$q.' ('.$quarterly_count.'+'.$quarterly_star.'<sup>*</sup>)</td>
				<td style="width: 342px;">'.$h.' ('.$halfyearly_count.'+'.$halfyearly_star.'<sup>*</sup>)</td>
				<td style="width: 342px;">'.$y.' ('.$yearly_count.'+'.$yearly_star.'<sup>*</sup>)</td>
				
				</tr>';




//echo $sp_date;
			$reg_countS=get_var("SELECT count(ID) from wp_users where date(user_registered)='$sp_date'");
			
			$freetrail_countS=get_var("SELECT count(membership_id) from wp_pmpro_memberships_users where membership_id=4 and date(startdate)='$sp_date'");

			$halfyearly_countSFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=6 and status='success' and total=0 and (DATE(timestamp)='$sp_date')");
			$halfyearly_starSFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=6 and status='success' and total>0 and (DATE(timestamp)='$sp_date')");

			$yearly_countSFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=7 and status='success' and total=0 and (DATE(timestamp)='$sp_date')");
			$yearly_starSFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=7 and status='success' and total>0 and (DATE(timestamp)='$sp_date')");

			$quarterly_countS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total=0 and (DATE(timestamp)='$sp_date')");
			$quarterly_starS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total>0 and (DATE(timestamp)='$sp_date')");

			$halfyearly_countS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total=0 and (DATE(timestamp)='$sp_date')");
			$halfyearly_starS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total>0 and (DATE(timestamp)='$sp_date')");

			$yearly_countS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total=0 and (DATE(timestamp)='$sp_date')");
			$yearly_starS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total>0 and (DATE(timestamp)='$sp_date')");

$qS=	$quarterly_countS + 	$quarterly_starS;
$hS=	$halfyearly_countS + 	$halfyearly_starS;
$yS=	$yearly_countS + 	$yearly_starS;	
$hSFO=	$halfyearly_countSFO + 	$halfyearly_starSFO;
$ySFO=	$yearly_countSFO + 	$yearly_starSFO;	

			echo 	'<tr style="" class="ui-sortable-handle">
				<td style="width: 192px;" class="level_name">Specific(default:Today): </td>	
				<td style="width: 192px;" class="level_name">'.$sp_date.'</td>						
				<td style="width: 192px;" class="level_name">'.$reg_countS.'</td>
				<td style="width: 192px;" class="level_name">'.$freetrail_countS.'</td>
				<td style="width: 342px;">'.$hSFO.' ('.$halfyearly_countSFO.'+'.$halfyearly_starSFO.'<sup>*</sup>)</td>
				<td style="width: 342px;">'.$ySFO.' ('.$yearly_countSFO.'+'.$yearly_starSFO.'<sup>*</sup>)</td>
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

 include("footer-admin.php"); ?>

<?php 

include("db-config.php");
include("admin-include.php");
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

$page_url=$_SERVER['PHP_SELF'];

$num_rec_per_page=10;




if (isset($_GET["page"])) 
{ $page  = $_GET["page"];

$sno= $_GET["last"]-1;


	if (isset($_GET["tb"])) {
			if($_GET["tb"]=="R"){$userUR=1;
			}
			elseif($_GET["tb"]=="S"){$userUS=1;
				}
		}
} 
else { $page=1;

 }; 
$start_from = ($page-1) * $num_rec_per_page; 


//if(isset($_REQUEST['date_submitInt']))
if( (isset($_REQUEST['this_dateInt']) or isset($_REQUEST['date_submitInt'])) and  $_REQUEST['this_dateInt']!="")
{
$date_int=$_REQUEST['this_dateInt'];

 $_SESSION['date']="";
$_SESSION['days']=$date_int;

//$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL $date_int DAY order by b.startdate desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id as plan,b.billing_amount as amt,b.startdate as my_date,b.enddate as endD FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where (b.startdate >=CURDATE() - INTERVAL $date_int DAY) UNION SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,c.plan_id as plan,c.amount as amt,c.credited_datetime as my_date,c.subscription_end_on as endD FROM wp_users a inner join agent_vs_subscription_credit_info c on a.id=c.subscriber_id where (c.credited_datetime >=CURDATE() - INTERVAL $date_int DAY) order by my_date desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	

$stmt1->execute();
$res1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

//$stmtT1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL $date_int DAY order by b.startdate desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmtT1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id as plan,b.billing_amount as amt,b.startdate as my_date,b.enddate as endD FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where (b.startdate >=CURDATE() - INTERVAL $date_int DAY) UNION SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,c.plan_id as plan,c.amount as amt,c.credited_datetime as my_date,c.subscription_end_on as endD FROM wp_users a inner join agent_vs_subscription_credit_info c on a.id=c.subscriber_id where (c.credited_datetime >=CURDATE() - INTERVAL $date_int DAY) order by my_date desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmtT1->execute();
$resT1 = $stmtT1->fetchAll(PDO::FETCH_ASSOC);



$stmt2 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where  a.user_registered>=CURDATE() - INTERVAL $date_int DAY order by id desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt2->execute();
$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmtT2 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where  a.user_registered>=CURDATE() - INTERVAL $date_int DAY order by id desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmtT2->execute();
$resT2 = $stmtT2->fetchAll(PDO::FETCH_ASSOC);

}

//else if(isset($_REQUEST['date_submit']))
else if( (isset($_REQUEST['this_date']) or isset($_REQUEST['date_submit'])) and $_REQUEST['this_date']!="")
{
$date=$_REQUEST['this_date'];
//echo $date;

 $_SESSION['days']="";
$_SESSION['date']=$date;

//$date="2016-08-10";
//$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate between '".$date."' and CURDATE() order by b.startdate desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	

$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id as plan,b.billing_amount as amt,b.startdate as my_date,b.enddate as endD FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where (b.startdate between '".$date."' and CURDATE()) UNION SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,c.plan_id as plan,c.amount as amt,c.credited_datetime as my_date,c.subscription_end_on as endD FROM wp_users a inner join agent_vs_subscription_credit_info c on a.id=c.subscriber_id where (c.credited_datetime between '".$date."' and CURDATE()) order by my_date desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt1->execute();
$res1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);


//$stmtT1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate between '".$date."' and CURDATE() order by b.startdate desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmtT1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id as plan,b.billing_amount as amt,b.startdate as my_date,b.enddate as endD FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where (b.startdate between '".$date."' and CURDATE()) UNION SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,c.plan_id as plan,c.amount as amt,c.credited_datetime as my_date,c.subscription_end_on as endD FROM wp_users a inner join agent_vs_subscription_credit_info c on a.id=c.subscriber_id where (c.credited_datetime between '".$date."' and CURDATE()) order by my_date desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	
$stmtT1->execute();
$resT1 = $stmtT1->fetchAll(PDO::FETCH_ASSOC);



$stmt2 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where (a.user_registered between '".$date."' and CURDATE()) order by a.ID desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt2->execute();
$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmtT2 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where (a.user_registered between '".$date."' and CURDATE()) order by a.ID desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmtT2->execute();
$resT2 = $stmtT2->fetchAll(PDO::FETCH_ASSOC);

}
 else
{
$date_int=10;


//$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL 10 DAY order by b.startdate desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id as plan,b.billing_amount as amt,b.startdate as my_date,b.enddate as endD FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where (b.startdate >=CURDATE() - INTERVAL $date_int DAY) UNION SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,c.plan_id as plan,c.amount as amt,c.credited_datetime as my_date,c.subscription_end_on as endD FROM wp_users a inner join agent_vs_subscription_credit_info c on a.id=c.subscriber_id where (c.credited_datetime >=CURDATE() - INTERVAL $date_int DAY) order by my_date desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt1->execute();
$res1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

//$stmtT1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id,b.billing_amount,b.startdate,b.enddate FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL 10 DAY order by b.startdate desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmtT1 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,b.membership_id as plan,b.billing_amount as amt,b.startdate as my_date,b.enddate as endD FROM wp_users a inner join wp_pmpro_memberships_users b on a.id=b.user_id where (b.startdate >=CURDATE() - INTERVAL $date_int DAY) UNION SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,c.plan_id as plan,c.amount as amt,c.credited_datetime as my_date,c.subscription_end_on as endD FROM wp_users a inner join agent_vs_subscription_credit_info c on a.id=c.subscriber_id where (c.credited_datetime >=CURDATE() - INTERVAL $date_int DAY) order by my_date desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmtT1->execute();
$resT1 = $stmtT1->fetchAll(PDO::FETCH_ASSOC);


$stmt2 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where a.user_registered>=CURDATE() - INTERVAL 10 DAY order by a.ID desc LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt2->execute();
$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmtT2 = $dbcon->prepare("SELECT a.ID,a.user_login,a.user_email,a.phone,a.user_url,a.user_registered FROM wp_users a where a.user_registered>=CURDATE() - INTERVAL 10 DAY order by a.ID desc", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmtT2->execute();
$resT2 = $stmtT2->fetchAll(PDO::FETCH_ASSOC);

}




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

.show{display:block !important}
.hide{display:none !important}
input
{text-align:center}

.link_btn:hover{
    background: #000;
    color: #fff;
    border: solid 1px #000;
}

.link_btn{
    border-radius: 3px;
    line-height: 1.5;
    text-align: center;
    margin: 1px;
    padding: 4px 8px;
    outline: none;
    background: #4141a0;
    border: solid 1px #4141a0;
    color: #fff;
    transition: all .2s ease;
    text-decoration:none;
}

th{color:whitesmoke !important;text-align: center;border: 1px solid rgba(255,255,255,.15) !important;}
thead{background-color: #4141a0}
table td {
    color: #444;
border-bottom: 1px solid #777 !important;
	text-align: center;}
td:nth-child(even) {background: #FFF}
td:nth-child(odd) {background: #DDD}

</style>';

echo '<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.js"></script>';

echo '<div id="other_tabs" align="center" style="margin:0 auto;max-width:50%;text-align:center">';
echo '<div align="center" style="margin:0 auto;max-width:200px;display:inline-block">
<form method="post" id="dateform">
<label>Select Date</label><input required type="text" placeholder="YYYY-mm-dd" id="this_date" name="this_date" />(shows all results from this date to TODAY)
<br />
<input type="submit" name="date_submit" id="date_submit" value="Submit"/></form></div>

<div align="center" style="margin:0 auto;max-width:200px;display:inline-block">---- OR -----</div>

<div align="center" style="margin:0 auto;max-width:200px;display:inline-block">
<form method="post" id="dateIntform">
<label>Enter Days</label><input required type="number" min="1" max="30" placeholder="10" id="this_dateInt" name="this_dateInt" />(shows all results from last no. of days)
<br />
<input type="submit" name="date_submitInt" id="date_submitInt" value="Submit"/></form></div>
</div>

<br />
<br />';

echo '<ul class="tab mx">

<li><a id="UR" href="#" class="tablinks" onclick="userStats(event, \'UserRegs\')">User Registartions List</a></li>
  <li><a id="US" href="#" class="tablinks" onclick="userStats(event, \'UserSubs\')">User Subscriptions List</a></li>
<li><a id="QT" href="#" class="tablinks" onclick="userStats(event, \'QezyTable\')">QezyPlay Daily Table</a></li>
<!-- <li><a id="QT" href="'.$page_url.'#" class="tablinks" onclick="userStats(event, \'QezyTable\')">QezyPlay Daily Table</a></li>-->
  
 </ul>';

echo '<div id="UserSubs" class="tabcontent mx">
		<!-- <table class="widefat membership-levels" style="width:100% !important;"> -->
		<table style="overflow-x:auto;min-width:50%;max-width:100% !important;word-break: break-all;
    max-width: 100%;margin-bottom: 25px;" width="100%;" border="0" cellspacing="0" cellpadding="0";>
			<thead>
				<tr>	
					<th>S.No</th>				
					<th>User ID</th>
					<th>User Name(E-mail)</th>
					<th>Phone</th>
					<th>Plan Name(Plan ID)</th>
					<th>Paid Amount</th>
					<th>Paid Date</th>
					<th>End Date</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			//$res=$wpdb->get_results("SELECT * FROM user_contact"); 

			//$res=$wpdb->get_results("SELECT a.ID,a.user_login,b.membership_id,b.billing_amount,b.startdate FROM qezyplay_newshonar.wp_users a inner join qezyplay_newshonar.wp_pmpro_memberships_users b on a.id=b.user_id where b.startdate >=CURDATE() - INTERVAL $date_int DAY");
			//$id=1+$sno;
			$id=$start_from+1;
			foreach($res1 as $row){
			
				
				$user_id=$row['ID'];
				$user_name=$row['user_login'];
				$user_email=$row['user_email'];
				$phone_no=$row['phone'];
				$fb=$row['user_url'];
				if($phone_no!="xxxxxxxxxxx")
				{
				$contact=$phone_no;
				}				
			        else
				{
				$contact=$fb;
				}
				
				//$plan_id=$row['membership_id'];
				$plan_id=$row['plan'];

				$plan_name=get_var("SELECT name from wp_pmpro_membership_levels where id=$plan_id ");

				//$paid_amnt=$row['billing_amount'];
				$paid_amnt=$row['amt'];
				//$paid_date=$row['startdate'];
				$paid_date=$row['my_date'];
				//$end_date=$row['enddate'];
				$end_date=$row['endD'];
								
		

		//$phone_no=$wpdb->get_var("SELECT meta_value FROM wp_usermeta where meta_key='phone' and user_id=$user_id ");

			echo 	'<tr style="" class="ui-sortable-handle">	
				<td style="width: 192px;" class="level_name">'.$id.'</td>						
				<td style="width: 192px;" class="level_name">'.$user_id.'</td>
				<td style="width: 342px;" class="level_name">'.$user_name.'('.$user_email.')</td>
				<td style="width: 192px;" class="level_name">'.$contact.'</td>
				<td style="width: 192px;">'.$plan_name.'('.$plan_id.')</td>
				<td style="width: 342px;">'.$paid_amnt.'</td>
				<td style="width: 342px;">'.$paid_date.'</td>
				<td style="width: 342px;">'.$end_date.'</td>
				
				</tr>';
			$id=$id+1;
			 } 
			
			echo '</tbody>
		</table>';

$total_records = count($resT1);  //count number of records
$total_pages = ceil($total_records / $num_rec_per_page); 
echo "<span style='color:black;font-size:16px'>Total:$total_records </span> <a class='link_btn' href='?page=1&tb=S'>".'<'."</a> "; // Goto 1st page  
for ($i=1; $i<=$total_pages; $i++) { 
		if(isset($_SESSION['date']) and $_SESSION['date']!="")
		 echo "<a class='link_btn' href='?page=".$i."&tb=S&this_date=".$_SESSION['date']."'>".$i."</a> ";
		elseif(isset($_SESSION['days']) and $_SESSION['days']!="")
		 echo "<a class='link_btn' href='?page=".$i."&tb=S&this_dateInt=".$_SESSION['days']."'>".$i."</a> ";  
		else
            echo "<a class='link_btn' href='?page=".$i."&tb=S'>".$i."</a> "; 
}; 

echo "<a class='link_btn' href='?page=$total_pages&tb=S'>".'>'."</a> "; // Goto last page
echo	'</div>
<div class="clear"></div>
	 <div id="UserRegs" class="tabcontent mx">
		<table class="widefat membership-levels" style="width:100% !important;word-break: break-all;
    max-width: 100%;   margin-bottom: 25px;">
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
			$id=$start_from+1;
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
		</table>';

$total_records = count($resT2);  //count number of records
$total_pages = ceil($total_records / $num_rec_per_page); 
echo "<span style='color:black;font-size:16px'>Total:$total_records </span> <a class='link_btn' href='?page=1&tb=R'>".'<'."</a> "; // Goto 1st page  
for ($i=1; $i<=$total_pages; $i++) { 

		if(isset($_SESSION['date']) and $_SESSION['date']!="")
		 echo "<a class='link_btn' href='?page=".$i."&tb=R&this_date=".$_SESSION['date']."'>".$i."</a> ";
		elseif(isset($_SESSION['days']) and $_SESSION['days']!="")
		 echo "<a class='link_btn' href='?page=".$i."&tb=R&this_dateInt=".$_SESSION['days']."'>".$i."</a> "; 
		else
            echo "<a class='link_btn' href='?page=".$i."&tb=R'>".$i."</a> "; 
}; 

echo "<a class='link_btn' href='?page=$total_pages&tb=R'>".'>'."</a> "; // Goto last page
echo	'</div>';

if(isset($_POST['specific_date_submit']))
{
$sp_date=$_POST['specific_date'];
if($sp_date!="")
{
$QT=1;
}
}
else
{
$sp_date=new DateTime("now");
$sp_date=$sp_date->format("Y-m-d");
}

echo '<div id="QezyTable" class="tabcontent mx" align="center" style="display:block">
		<table class="table table-striped" style="text-align:center;width:100% !important;word-break: break-all;
    max-width: 100%;     margin-bottom: 25px;">
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




//echo $sp_date; //SPECIFIC DATE
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


			//TOTAL from 15th jul,2016

	$st_date="2016-07-15";
			$reg_countS=get_var("SELECT count(ID) from wp_users where date(user_registered)>='$st_date'");
			
			$freetrail_countS=get_var("SELECT count(membership_id) from wp_pmpro_memberships_users where membership_id=4 and date(startdate)>='$st_date'");

			$halfyearly_countSFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=6 and status='success' and total=0 and (DATE(timestamp)>='$st_date')");
			$halfyearly_starSFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=6 and status='success' and total>0 and (DATE(timestamp)>='$st_date')");
			$halfyearly_AgentSFO=get_var("SELECT count(plan_id) FROM agent_vs_subscription_credit_info where plan_id=6 and (DATE(credited_datetime)>='$st_date')");

			$yearly_countSFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=7 and status='success' and total=0 and (DATE(timestamp)>='$st_date')");
			$yearly_starSFO=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=7 and status='success' and total>0 and (DATE(timestamp)>='$st_date')");
			$yearly_AgentSFO=get_var("SELECT count(plan_id) FROM agent_vs_subscription_credit_info where plan_id=7 and (DATE(credited_datetime)>='$st_date')");


			$quarterly_countS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total=0 and (DATE(timestamp)>='$st_date')");
			$quarterly_starS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=3 and status='success' and total>0 and (DATE(timestamp)>='$st_date')");
			$quarterly_AgentS=get_var("SELECT count(plan_id) FROM agent_vs_subscription_credit_info where plan_id=3 and (DATE(credited_datetime)>='$st_date')");

			$halfyearly_countS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total=0 and (DATE(timestamp)>='$st_date')");
			$halfyearly_starS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=2 and status='success' and total>0 and (DATE(timestamp)>='$st_date')");
			$halfyearly_AgentS=get_var("SELECT count(plan_id) FROM agent_vs_subscription_credit_info where plan_id=2 and (DATE(credited_datetime)>='$st_date')");

			$yearly_countS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total=0 and (DATE(timestamp)>='$st_date')");
			$yearly_starS=get_var("SELECT count(membership_id) FROM wp_pmpro_membership_orders where membership_id=1 and status='success' and total>0 and (DATE(timestamp)>='$st_date')");
			$yearly_AgentS=get_var("SELECT count(plan_id) FROM agent_vs_subscription_credit_info where plan_id=1 and (DATE(credited_datetime)>='$st_date')");

$qS=	$quarterly_countS + 	$quarterly_starS + $quarterly_AgentS;
$hS=	$halfyearly_countS + 	$halfyearly_starS + $halfyearly_AgentS;
$yS=	$yearly_countS + 	$yearly_starS + $yearly_AgentS;	
$hSFO=	$halfyearly_countSFO + 	$halfyearly_starSFO + $halfyearly_AgentSFO;
$ySFO=	$yearly_countSFO + 	$yearly_starSFO + $quarterly_AgentSFO;	

			echo 	'<tr style="" class="ui-sortable-handle">
				<td style="width: 192px;" class="level_name">Total(from Jul-15,2016): </td>	
				<td style="width: 192px;" class="level_name"></td>						
				<td style="width: 192px;" class="level_name">'.$reg_countS.'</td>
				<td style="width: 192px;" class="level_name">'.$freetrail_countS.'</td>
				<td style="width: 342px;">'.$hSFO.' ('.$halfyearly_countSFO.'+'.$halfyearly_starSFO.'<sup>*</sup>+'.$halfyearly_AgentSFO.'<sup>A</sup>)</td>
				<td style="width: 342px;">'.$ySFO.' ('.$yearly_countSFO.'+'.$yearly_starSFO.'<sup>*</sup>+'.$yearly_AgentSFO.'<sup>A</sup>)</td>
				<td style="width: 192px;">'.$qS.' ('.$quarterly_countS.'+'.$quarterly_starS.'<sup>*</sup>+'.$quarterly_AgentS.'<sup>A</sup>)</td>
				<td style="width: 342px;">'.$hS.' ('.$halfyearly_countS.'+'.$halfyearly_starS.'<sup>*</sup>+'.$halfyearly_AgentS.'<sup>A</sup>)</td>
				<td style="width: 342px;">'.$yS.' ('.$yearly_countS.'+'.$yearly_starS.'<sup>*</sup>+'.$yearly_AgentS.'<sup>A</sup>)</td>
				
				</tr>';
						
			echo '</tbody>
		</table><div style="float: right;
    display: inline-block;"><p>*: Paid Revenue</p><p>A: Paid by Agent</p></div>
<div align="center" style="max-width:200px;margin:0 auto;">
<form method="post"><label>Select a Specific Date</label><input required type="text" placeholder="YYYY-mm-dd" id="specific_date" name="specific_date" />
<br />
<input type="submit" name="specific_date_submit" id="specific_date_submit" value="Submit"/></form>
</div>

	</div>
	<div class="clear"></div><br /><br />';
if($userPR==1)
{
echo '<script>
		document.getElementById("UR").setAttribute("class","tablinks");
		document.getElementById("US").setAttribute("class","tablinks");
		document.getElementById("QT").setAttribute("class","tablinks active");
		document.getElementById("UserSubs").setAttribute("class","tabcontent mx hide");
		document.getElementById("UserRegs").setAttribute("class","tabcontent mx hide");
		document.getElementById("QezyTable").setAttribute("class","tabcontent mx show");
</script>';}



echo '<script>

var otherhide=document.getElementById("QezyTable").style.display;
if(otherhide=="block")
{
document.getElementById("other_tabs").style.display="none";
}


jQuery( function() {
    jQuery( "#specific_date" ).datepicker({changeMonth: true,changeYear: true,
		 minDate: new Date(2016, 10-4, 15),maxDate: "0",dateFormat: "yy-mm-dd"});
jQuery( "#specific_date" ).keyup(function()  { this.value = this.value.substring(0,this.value.length -1); });
  } );

jQuery( function() {
    jQuery( "#this_date" ).datepicker({changeMonth: true,changeYear: true,
		 maxDate: "0",dateFormat: "yy-mm-dd"});
 jQuery( "#this_date" ).keyup(function()  { this.value = this.value.substring(0,this.value.length -1); });
  } );

function userStats(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks,US,UR;

	if(cityName=="QezyTable")
	{
	jQuery("#other_tabs").hide();
	}
	else
	{
	jQuery("#other_tabs").show();
	}

	/*if(cityName="UserSubs")
	{
	US=1;
	}
	if(cityName="UserRegs")
	{
	UR=1;
	}*/

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
	 
    }

	ele=document.getElementsByClassName("tabcontent mx show");
	 for (i = 0; i < ele.length; i++) {
        ele[i].setAttribute("class","tabcontent mx hide");;
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
	document.getElementById(cityName).setAttribute("class","tabcontent mx show");
    evt.currentTarget.className += " active";

}

/* if(UR==1)
{
document.getElementById("UR").setAttribute("class","tablinks active");
		document.getElementById("US").setAttribute("class","tablinks");
		document.getElementById("QT").setAttribute("class","tablinks");
		document.getElementById("UserSubs").setAttribute("class","tabcontent mx hide");
		document.getElementById("UserRegs").setAttribute("class","tabcontent mx show");
		document.getElementById("QezyTable").setAttribute("class","tabcontent mx hide");
}

if(US==1)
{
document.getElementById("UR").setAttribute("class","tablinks");
		document.getElementById("US").setAttribute("class","tablinks active");
		document.getElementById("QT").setAttribute("class","tablinks");
		document.getElementById("UserSubs").setAttribute("class","tabcontent mx show");
		document.getElementById("UserRegs").setAttribute("class","tabcontent mx hide");
		document.getElementById("QezyTable").setAttribute("class","tabcontent mx hide");
}
*/
</script>';

if($userUR==1)
{
echo '<script>
		document.getElementById("UR").setAttribute("class","tablinks active");
		document.getElementById("US").setAttribute("class","tablinks");
		document.getElementById("QT").setAttribute("class","tablinks");
		document.getElementById("UserSubs").setAttribute("class","tabcontent mx hide");
		document.getElementById("UserRegs").setAttribute("class","tabcontent mx show");
		document.getElementById("QezyTable").setAttribute("class","tabcontent mx hide");
</script>';

	
}

if($userUS==1)
{
echo '<script>
		document.getElementById("UR").setAttribute("class","tablinks");
		document.getElementById("US").setAttribute("class","tablinks active");
		document.getElementById("QT").setAttribute("class","tablinks");
		document.getElementById("UserSubs").setAttribute("class","tabcontent mx show");
		document.getElementById("UserRegs").setAttribute("class","tabcontent mx hide");
		document.getElementById("QezyTable").setAttribute("class","tabcontent mx hide");
</script>';

	
}

 include("footer-admin.php"); ?>

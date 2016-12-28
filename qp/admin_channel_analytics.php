<?php

include("db-config.php");
include("admin-include.php");
include("function_common.php");

//Access your POST variables
$adminid = $_SESSION['adminid'];
//echo "id:".$agent_id;
//Unset the useless session variable


$searchstring = "";



$start_limit = 0;
@$page = isset($_POST['page']) ? $_POST['page'] : $_GET['page'];
if (!isset($page))
    $page = 1;
if ($page > 1)
    $start_limit = (($page * ROW_PER_PAGE) - ROW_PER_PAGE);

 $subselected = $mobileselected =  $pcselected = $cond = "";
if($_REQUEST['sub_users'] == "sub"){
	$sql0="SELECT user_id FROM wp_pmpro_memberships_users where status='active' union SELECT subscriber_id from agent_vs_subscription_credit_info where subscription_end_on>=CURDATE()";
	$stmt0 = $dbcon->prepare($sql0, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt0->execute();
	$result0 = $stmt0->fetchAll(PDO::FETCH_ASSOC);

	$cond .= " AND user_id IN (";	
	foreach($result0 as $res0)
	{
	$cond .= $res0['user_id'].",";
	}
	$cond=rtrim($cond,",");
	$cond .= ")";
	
	$subselected = "selected='selected'";
	$searchstring .= "&sub_users=sub";
}

if($_REQUEST['device'] == "pc"){
	$cond .= " AND device = 'Personal Computer'";
	$pcselected = "selected='selected'";
	$searchstring .= "&device=pc";
}elseif($_REQUEST['device'] == "mobile"){
	$cond .= " AND device = 'Mobile'";
	$mobileselected = "selected='selected'";
	$searchstring .= "&device=mobile";
}

if($_REQUEST['country'] != ""){
	$cond .= " AND country_code = '".$_REQUEST['country']."'";
	$searchstring .= "&country=".$_REQUEST['country'];
}
if($_REQUEST['state'] != ""){
	$cond .= " AND state = '".$_REQUEST['state']."'";
	//$stateselected = "<option selected='selected' value='".$_REQUEST['state']."'>".$_REQUEST['state']."</option>";
	$searchstring .= "&state=".$_REQUEST['state'];
}
if($_REQUEST['city'] != ""){
	$cond .= " AND city = '".$_REQUEST['city']."'";
	//$cityselected = "<option selected='selected' value='".$_REQUEST['city']."'>".$_REQUEST['city']."</option>";
	$searchstring .= "&city=".$_REQUEST['city'];
}
if($_REQUEST['startdate'] != ""){
	$cond .= " AND DATE(CONVERT_TZ(start_datetime,'+00:00','".$coockie."')) >= '".$_REQUEST['startdate']."'";
	$startdate = $_REQUEST['startdate'];
	$searchstring .= "&startdate=".$_REQUEST['startdate'];
}
if($_REQUEST['enddate'] != ""){
	$cond .= " AND DATE(CONVERT_TZ(end_datetime,'+00:00','".$coockie."')) <= '".$_REQUEST['enddate']."'";
	$enddate = $_REQUEST['enddate'];
	$searchstring .= "&enddate=".$_REQUEST['enddate'];
}

$channelId = $_SESSION['customerid'];
$cond .= " AND play = 1";


$sql1 = "SELECT count(*) as count FROM visitors_info WHERE 1 AND duration > 0 AND user_name !=''".$cond;
$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
$stmt1->execute();
$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
$stmt1 = null;
$count = $result1['count'];

$countries = getCountries();
	
include("header-admin.php");
?>
<style>
.xoouserultra-field-value, .xoouserultra-field-type{
	width: unset !important;
	padding: 5px;
}

.paging{
	float:left;
}
td:nth-child(odd) {
    background: #DDD;
    color: #444;
}
thead{background-color: #4141a0;}
th{color: white !important;
    border: 1px solid #c6c6c6;}
</style>
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.js"></script>
<div id="content" role="main" style="min-height:500px;margin: 2% 5%;">
	<div class="pmpro_box" id="pmpro_account-invoices">
		<h2 style="text-align:center">Channel Statistics</h2>
		<form id="xoouserultra-login-form-1" method='post'>
		<div class="xoouserultra-wrap" style="max-width:100% !important;">
			<div class="xoouserultra-inner xoouserultra-login-wrapper">
				<div class="xoouserultra-main">
					
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label class="xoouserultra-field-type">	
								<span>Users:</span>
							</label>
							<div class="xoouserultra-field-value">
								<select name="sub_users">
									<option value=''>All Users</option>
									<option value='sub' <?php echo $subselected; ?>>Subscription Active Users</option>
									
								</select>	
							</div>
							&nbsp;&nbsp;&nbsp;
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
								<input autocomplete="off" type="text" value="<?php echo $startdate; ?>" id='startdate' name='startdate'>
							</div>
							&nbsp;&nbsp;&nbsp;
							<label for="user_login" class="xoouserultra-field-type">	
								<span>End Date:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input autocomplete="off" type="text" value="<?php echo $enddate; ?>" id='enddate' name='enddate'>	
							</div>
						</div>
						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						
							<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<input type="hidden" name='page' value="1" id="page">
								<input type="submit" name='filter' value="Filter" class="" name="xoouserultra-login" id="filter">
							</div>
						</div>
						<div class="xoouserultra-clear"></div>
					<!-- /form -->
				</div>
			</div>

		</div>

		
		<table style="overflow-x:auto;min-width:50%;max-width:100% !important;" width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th>Subscriber</th>
					<th>Device</th>	
					<th>Geo Info</th>						
					<th>Start time</th>
					<th>End time</th>
					<th>Duration(min)</th>
					<th>Channel</th>
				</tr>
			</thead>
			<tbody>
			<?php if ($count > 0) { 

				//$sql3 = "SELECT * FROM visitors_info WHERE page_id=? AND play = 1 AND duration > 0 AND user_name !=''".$cond." ORDER BY id desc LIMIT " . $start_limit . "," . ROW_PER_PAGE;
				$sql3 = "SELECT *,CONVERT_TZ(start_datetime,'+00:00','".$coockie."') as start_datetime,CONVERT_TZ(end_datetime,'+00:00','".$coockie."') as end_datetime FROM visitors_info WHERE 1 AND duration > 0 AND user_name !=''".$cond." ORDER BY id desc LIMIT " . $start_limit . "," . ROW_PER_PAGE;

				try { //echo "<script>console.log('".$sql3."')</script>";
					$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
					//$stmt3->execute(array($channelId));
					$stmt3->execute();
					$result3 = $stmt3->fetchall(PDO::FETCH_ASSOC);
			 		$stmt3 = null;
					
					foreach($result3 as $analytics){
						$username=$analytics["user_name"];
						$ipaddress=$analytics["ip_address"];
						$device=$analytics["device"];						
						$pagereferer=$analytics["page_referer"];
						$start=$analytics["start_datetime"];
						$end=$analytics["end_datetime"];

						$page_id=$analytics["page_id"];

						$duration = ($analytics["duration"] / 60);
						
						$duration=round($duration, 1, PHP_ROUND_HALF_DOWN); //added
					
					
						//$useremail = $analytics["user_email"];
						$useremail=get_var("SELECT user_email from wp_users where user_login='".$username."' ");

						$channel = get_var("SELECT post_title from wp_posts where ID=".$page_id." ");

						$deviceInfo = "<strong>OS Name: </strong>".$analytics["os_name"];
						$deviceInfo .= "<br><strong>OS Version: </strong>".$analytics["os_version"];
						$deviceInfo .= "<br><strong>Browser Name: </strong>".$analytics["browser_name"];
						$deviceInfo .= "<br><strong>Browser Version: </strong>".$analytics["browser_version"];

						$geoInfo = "<strong>Country: </strong>".$analytics["country"];
						$geoInfo .= "<br><strong>State: </strong>".$analytics["state"];
						$geoInfo .= "<br><strong>City: </strong>".$analytics["city"];
											
						if(count($result3) > 1){
							echo "<tr class='ui-sortable-handle'>			
									<td>".$username." (".$useremail.")</td>
									<td style='width:150px'><a href='#' class='tooltip'>".$device."<span>".$deviceInfo."</span></a></td>
									<td style='width:120px'><a href='#' class='tooltip'>".$ipaddress."<span>".$geoInfo."</span></a></td>											
									<td>".date("Y-m-d H:i:s",strtotime($start))."</td>
									<td>".date("Y-m-d H:i:s",strtotime($end))."</td>
									<td>".$duration."</td>
									<td>".$channel."</td>
								</tr>";
						}
					
					}	
	
				}catch (PDOException $e){
					print $e->getMessage();
				}
				
			}else{
				echo "<tr style='' class='ui-sortable-handle'><td colspan='7'><center>No analytics found</center></td></tr>";
				echo "<style>#gotop{display:none}</style>";			
			}
			?>					
			</tbody>
		</table>
		<br>
		<?php

		//Display pagging
		if($count > 0){					
			//echo doPages(ROW_PER_PAGE, 'customer_channel_statistics.php', $searchstring, $count);
			echo doPages(ROW_PER_PAGE, 'admin_channel_analytics.php', $searchstring, $count);
		}
		?>
		<div id="gotop" style="float:"><!-- form style="margin:0px" id="xoouserultra-login-form-1" method='post' -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input onclick="return checkGoto(<?php echo ceil($count/10);  ?>)" type="submit" name='goto' style="width:200px;" value="Go to Page" class="" name="xoouserultra-login" id="goto">&nbsp;<input type="number" name='page' id="page1" style="width:120px;margin:0px 10px !important;padding:0px !important;text-align:center;"><span id="pageErr" style="color:red"></span><!-- /form --></div><br>
		</form></div>
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

jQuery("#page1").focus(function(){
jQuery("#pageErr").hide();
})
function checkGoto(maxP)
{

var page_no=jQuery("#page1").val();
jQuery("#pageErr").show();
if(page_no=="")
{
jQuery("#pageErr").html("Please enter a Page no to proceed");
return false;
}else if(page_no<1)
{
jQuery("#pageErr").html("Please enter valid Page no. Min no is 1");
return false;
}else if(page_no>maxP)
{
jQuery("#pageErr").html("Please enter valid Page no. Max no is "+maxP);
return false;
}
else{
jQuery("#pageErr").hide();
return true;
}
}
</script>
<?php	

include("footer-admin.php");
	
?>

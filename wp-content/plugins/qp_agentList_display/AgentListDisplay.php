<?php 

/*
Plugin Name: AgentList Display
Plugin URI: 
Description: Displays the agent list to the users based on the area
Author: IB
Version: 1.0
Author URI: ib
*/

ob_start();
session_start();

add_shortcode('agentlist','agent_list_display');

function agent_list_display(){

	global $current_user, $wpdb;
	
	get_currentuserinfo();
	$user_id = get_current_user_id();

	if((int)$user_id<1)
	{
	header('Location:../');
	}


	
	if ((strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false)) {
		//ios_app_user_agent_name
    		echo "<style>header, #bottom-nav_1, footer, .blog-heading {display:none;}</style>";}

	if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.ideabytes.qezyplay.qezyplay_new"){ //android_app_package_name
		echo "<style>header, #bottom-nav_1, footer, .blog-heading {display:none;}</style>";}


	$user_info=get_userdata($user_id);
	
	
	$username = $user_info->user_login;
	$usermail = $user_info->user_email;
	$userphone = $user_info->phone;
	


	function req_to_db($agentId,$username,$usermail,$userphone)

	{
		global $wpdb;
		$wpdb->insert("agent_credit_requests", array(   
					"agent_id" => $agentId,
					"user_name" => $username,
					"user_email" => $usermail,		
					"phone" => $userphone)
					);
	}

	//USER INTERSTED
	if (isset($_GET['aid'])){

		//$agent=$_GET['name'];
		//global $wpdb; 
		//$mob=$wpdb->get_var("SELECT mobile FROM agent_info where agentname='".$agent."'");
		$agentId = $_GET['aid'];
		
		$res = $wpdb->get_results("SELECT * FROM agent_info WHERE id = ".$agentId);			
	
		if($agentId == 0){
			
			$msg = "User Visited. Please contact if he doesn't. \n User: $username \n Email: $usermail \n Phone: $userphone";
			$msg1 = "User Visited. Please contact if he doesn't. <br><br> User: $username <br> Email: $usermail <br> Phone: $userphone";
			
			$superagent_mobileno = get_option("superagent_mobileno");
			$superagent_mobileno_alternative = get_option("superagent_mobileno_alternative");

			$superagent_email = get_option("superagent_email");
			$superagent_email_alternative = get_option("superagent_email_alternative");

			$superagent_name = get_option("superagent_name");
			$superagent_name_alternative = get_option("superagent_name_alternative");
		
			$receipents = array( 
							array("name" => $superagent_name, "email" => $superagent_email, "mobile" => $superagent_mobileno),
							array("name" => $superagent_name_alternative, "email" => $superagent_email_alternative, "mobile" => $superagent_mobileno_alternative)
						);

			
			if(!isset($_SESSION['disablethispage'])){
				$_SESSION['disablethispage'] = true;
				// serving the page first time
				if($_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit" or $_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit/?go"){
					
					
					foreach($receipents as $receipent){
						sendSMS($receipent['mobile'], $msg);

						$message = "Hi ".$receipent['name'].", <br><br>";
						$message .= $msg1;
						sendMail($receipent['email'], $message);	

						req_to_db($agentId,$username,$usermail,$userphone);					
					}
					
					echo "<script>swal('Super Agent will contact you soon');</script>";
				}

			}else{
				// visited before or page was refreshed
				echo "<script>swal('Super Agent already got your details');</script>";
			}
			
		}else if(count($res) == 1){				
			
			$agent = $res[0];
			
			$mob = $agent->mobile; $email = $agent->email; $name = $agent->agentname;

			$msg = "User interested \n User: $username \n Email: $usermail \n Phone: $userphone \n Get in Touch asap";
			$msg1 = "User interested <br><br> User: $username <br> Email: $usermail <br> Phone: $userphone <br><br> Get in Touch asap";

			if(!isset($_SESSION['disablethispage1'])){
				$_SESSION['disablethispage1'] = true;
				// serving the page first time
				if($_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit" or $_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit/?go"){

					sendSMS($mob,$msg);				

					$message = "Hi ".$name.", <br><br>";
					$message .= $msg1;
					sendMail($email, $message);
					
					req_to_db($agentId,$username,$usermail,$userphone);
			

					echo "<script>swal('Our Agent will contact you soon, by mail');</script>";
				}

			}else{
				// visited before or page was refreshed
				echo "<script>swal('Agent already got your details');</script>";
			}
		}else{					
			// visited before or page was refreshed
			echo "<script>swal('Agent not found');</script>";			
		}
		
		
	}
	//END of user interested


	$ip = $_SERVER['REMOTE_ADDR']; //user's ip address

	/** 
	$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
	echo $details->country;
	echo $details->region; 
	echo $details->city;
	**/

	/** alt method **/

	$geoinfo = "http://api.ipinfodb.com/v3/ip-city/?key=13ebc6d8740ab89e93e615530a59dd0f22df559274089129135f83188578f84d&ip=$ip&format=json";

	$ch_geoinfo = curl_init($geoinfo); 	
	curl_setopt($ch_geoinfo, CURLOPT_HEADER, 0);         	
	curl_setopt($ch_geoinfo, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch_geoinfo, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch_geoinfo, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch_geoinfo, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch_geoinfo,CURLOPT_CONNECTTIMEOUT,60);
	curl_setopt($ch_geoinfo, CURLOPT_FAILONERROR, 1);

	$execute_geoinfo = curl_exec($ch_geoinfo);

	if(!curl_errno($ch_geoinfo)){					
		$json_geoinfo = str_replace('\\', '\\\\', $execute_geoinfo);
		$json_decode_geoinfo = json_decode($json_geoinfo, true);   

		$country_name = $json_decode_geoinfo["countryName"];
		$city_name = $json_decode_geoinfo["cityName"];
		$country_code = $json_decode_geoinfo["countryCode"];			
		$state = $json_decode_geoinfo["regionName"];

	}

	//echo "alt:".$country_name.$city_name.$state;
	/** alt method end **/

//$country_name = $wpdb->get_var("SELECT country FROM visitors_info where ip_address=".$ip." ");
//$city_name = $wpdb->get_var("SELECT city FROM visitors_info where ip_address=".$ip." ");
//$state = $wpdb->get_var("SELECT state FROM visitors_info where ip_address=".$ip." ");

$ip = $_SERVER['REMOTE_ADDR'];
//$ip=$wpdb->get_var("SELECT ip_address FROM visitors_info where user_id=".$user_id." ");
if($ip=="")
{
$ip = $_SERVER['REMOTE_ADDR'];
}

$cn=$wpdb->get_var("SELECT country FROM visitors_info where ip_address='".$ip."' order by id desc limit 1");

$st=$wpdb->get_var("SELECT state FROM visitors_info where ip_address='".$ip."' order by id desc limit 1");
$ci=$wpdb->get_var("SELECT city FROM visitors_info where ip_address='".$ip."' order by id desc limit 1");

$country_name = $cn;
$city_name = $ci;
//$country_code = $json_decode_geoinfo["countryCode"];			
$state = $st;

//echo "<script>swal('".$ip.$cn.$st.$ci."')</script>";


	$html="";
	$html.='<div id="AgentList"><h2> Our Agents near by you:</h2> <br />
	<form>
		<div class="table-responsive">
                                                <table style="max-width:700px" id="pending_list" class="table table-striped table-bordered table-hover">
		<!-- <table class="widefat membership-levels" style="width:95% !important;background-color: rgba(255, 250, 250, 0.8);"> -->
			<thead>
				<tr>
					<th>Agent Name</th>
					<th>Mobile</th>
					<th>E-mail</th>
					<th>Location</th>
					<th class="status">Status</th>

				</tr>
			</thead>
			<tbody class="ui-sortable">';

	global $wpdb;
	$res=$wpdb->get_results("SELECT distinct(location) FROM agent_location_info");
	foreach($res as $row){
		if(strtolower($city_name)==strtolower($row->location)){
			//echo "city identified:".$city_name = $row->location ;
			$city_name = $row->location ;
			$cityok = 1;
			break;
		}
	}
	
	$res=$wpdb->get_results("SELECT distinct(region) FROM agent_location_info");
	foreach($res as $row){
		if(strtolower($state)==strtolower($row->region)){
			//echo "region identified";
			$state=$row->region;
			$regionok = 1;
			break;
		}
	}

	$res=$wpdb->get_results("SELECT distinct(country) FROM agent_location_info");
	foreach($res as $row){
		if(strtolower($country_name)==strtolower($row->country)){
			//echo "country identified";
			$country_name=$row->country;
			$countryok = 1;
			break;
		}
	}
	
	if($cityok){
			//echo "City details Searching...";
		$res=$wpdb->get_results("SELECT agent_id,location FROM agent_location_info where location='".$city_name."'"); 

		foreach($res as $row){
			
			//echo $row->agent_id;
			$loc=$row->location;
			//echo $loc;
			//echo $row->agent_id;
			$res1=$wpdb->get_results("SELECT * FROM agent_info where id=".$row->agent_id."");
			$mobiles = array();
			
			foreach($res1 as $row){
				$html .= '<tr><td><a style="color:black !important;font-weight:bolder;" href="?aid='.$row->id.'">'.$row->agentname.'</a></td><td>'.$row->mobile.'</td><td>'.$row->email.'</td><td>'.$loc.'</td><td class="status" id="stat-'.$row->id.'"></td></tr>';
				//array_push($mobiles,$row->mobile);
			}
		}

		/*	
		$msg = "User Visited. Please contact if he doesn't. \n User: $username \n Email: $usermail \n Phone: $userphone";		
		if(!isset($_SESSION['disablethispage'])){
			$_SESSION['disablethispage'] = true;
			// serving the page first time
			if($_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit" or $_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit/?go"){
				foreach($mobiles as $mob){
					sendSMS($mob,$msg);
					
					$message = "Hi ".$receipent['name'].", <br><br>";
					$message .= $msg;
					sendMail($receipent['email'], $msg);

					req_to_db($agentId,$username,$usermail,$userphone);
				}
			}
		}
		*/


	}elseif($regionok){

		$res=$wpdb->get_results("SELECT agent_id,location FROM agent_location_info where region='".$state."'"); 
		foreach($res as $row){
			//echo $row->agent_id;
			$loc=$row->location;
			$mobiles = array();
			$res1=$wpdb->get_results("SELECT * FROM agent_info where id=".$row->agent_id." ");
			foreach($res1 as $row){
					$html.='<tr><td><a style="color:black !important;font-weight:bolder;" href="?aid='.$row->id.'">'.$row->agentname.'</a></td><td>'.$row->mobile.'</td><td>'.$row->email.'</td><td>'.$loc.'</td><td class="status" id="stat-'.$row->id.'"></td></tr>';
					array_push($mobiles,$row->mobile);		
			}
		}

		/*
		$msg="User Visited. Please contact if he doesn't. \n User:$username \n Email: $usermail \n Phone: $userphone";		
		if(!isset($_SESSION['disablethispage'])){
			$_SESSION['disablethispage'] = true;
			// serving the page first time
			if($_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit" or $_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit/?go"){
				foreach($mobiles as $mob){ 					
					sendSMS($mob,$msg);
					
					$message = "Hi ".$receipent['name'].", <br><br>";
					$message .= $msg;
					sendMail($receipent['email'], $msg);

					req_to_db($agentId,$username,$usermail,$userphone);
				}
			}

		}
		*/

	}elseif($countryok){

		$res=$wpdb->get_results("SELECT agent_id,location FROM agent_location_info where country='".$country_name."'"); 
		foreach($res as $row){

			//echo $row->agent_id;
			$loc=$row->location;
			$mobiles=array();

			$res1=$wpdb->get_results("SELECT * FROM agent_info where id='".$row->agent_id."'");
			foreach($res1 as $row){
				$html.='<tr><td><a style="color:black !important;font-weight:bolder;" href="?aid='.$row->id.'">'.$row->agentname.'</a></td><td>'.$row->mobile.'</td><td>'.$row->email.'</td><td>'.$loc.'</td><td class="status" id="stat-'.$row->id.'"></td></tr>';
				//array_push($mobiles,$row->mobile);		
			}

		}
		
		/*
		$msg="User Visited. Please contact if he doesn't. \n User:$username \n Email: $usermail \n Phone: $userphone";		
		if(!isset($_SESSION['disablethispage'])){
			$_SESSION['disablethispage'] = true;
			// serving the page first time
			if($_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit" or $_SERVER['HTTP_REFERER']!=home_url()."/get-qezycredit/?go"){
				foreach($mobiles as $mob){
					sendSMS($mob,$msg);
					
					$message = "Hi ".$receipent['name'].", <br><br>";
					$message .= $msg;
					sendMail($receipent['email'], $msg);

					req_to_db($agentId,$username,$usermail,$userphone);
				}
			}
		}
		*/

	}else {
		
		$superagent_mobileno = get_option("superagent_mobileno");
		$superagent_mobileno_alternative = get_option("superagent_mobileno_alternative");
		
		$superagent_email = get_option("superagent_email");
		$superagent_email_alternative = get_option("superagent_email_alternative");
		
		$superagent_name = get_option("superagent_name");
		$superagent_name_alternative = get_option("superagent_name_alternative");
		
		$html.='<tr>Sorry, we are unable to get the agents near you right now. Contact our SuperAgent, he will redirect you. Thank You.</tr>';
		
		
		$receipents = array( 
							array("name" => $superagent_name, "email" => $superagent_email, "mobile" => $superagent_mobileno),
							array("name" => $superagent_name_alternative, "email" => $superagent_email_alternative, "mobile" => $superagent_mobileno_alternative)
						);

		
		$html.='<tr><td><a style="color:black !important;font-weight:bolder;" href="?aid=0">SuperAgent('.$superagent_name.')</a></td><td>'.$superagent_mobileno.'</td><td>'.$superagent_email.'</td><td>&nbsp;</td><td class="status" id="stat-0"></td></tr>';
		
		
	}

	
	$html.='</tbody>
		</table></div></form>
	</div>
	<div class="clear"></div>';
	


	

$status1=$wpdb->get_var("SELECT status FROM agent_remittence_pending where subscriber_id='".$user_id."' order by id desc limit 1");
$AG_ID1=$wpdb->get_var("SELECT agent_id FROM agent_remittence_pending where subscriber_id='".$user_id."' order by id desc limit 1");
$status2=$wpdb->get_var("SELECT status FROM agent_credit_requests where user_name='".$username."' order by id desc limit 1");
$AG_ID2=$wpdb->get_var("SELECT agent_id FROM agent_credit_requests where user_name='".$username."' order by id desc limit 1");
if($status1=="")
{

	if($status2=="")
	{
		$status=" ";
		
		
	}
	else
	{
		$status="Requested";
		$AG_ID=$AG_ID2;
	}

}
else
{
$status=$status1;
$AG_ID=$AG_ID1;
}

$html.='<script>jQuery("#stat-'.$AG_ID.'").html("'.$status.'")</script>';

echo $html;
}

function sendMail($email, $message){
	
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: Admin - QezyPlay <admin@qezyplay.com>' . "\r\n";	
	
	mail($email, "Qezyplay | Subscription request", $message, $headers);
	
}

function sendSMS($mobileNumber,$message){

	//Your authentication key
	$authKey = "100665AWYS0qYu56794700";//SMS_AUTHKEY;
	
	//Sender ID,While using route4 sender id should be 6 characters long.
	$senderId = "QEZYMD";//SMS_SENDER_ID;

	//Your message to send, Add URL encoding here.
	$message = urlencode($message);

	//Define route 
	$route = 4;
	//Prepare you post parameters
	$postData = array(
	    'authkey' => $authKey,
	    'mobiles' => $mobileNumber,
	    'message' => $message,
	    'sender' => $senderId,
	    'route' => $route
	);

	//API URL
	$url = "http://api.msg91.com/api/sendhttp.php";//SMS_API_URL;
	//echo $url.$message;
	// init the resource
	$ch = curl_init();
	curl_setopt_array($ch, array(
	    CURLOPT_URL => $url,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => $postData
	    //,CURLOPT_FOLLOWLOCATION => true
	));

	//Ignore SSL certificate verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	//get response
	$output = curl_exec($ch);

	//Print error if any
	if(curl_errno($ch)){
	    echo 'error:' . curl_error($ch);
	}

	curl_close($ch);
	return $output;
}

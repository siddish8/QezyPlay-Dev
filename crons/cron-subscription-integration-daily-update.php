<?php

if(defined('STDIN') ){
  //echo("Running from CLI");
}else{
  //echo("Not Running from CLI");
  //exit();
}

include("cron-config.php");


function get_var($sql){
	
	global $dbcon;
	
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);		
		//return $result[0];
		return $result;
		
	}catch (PDOException $e){
		print $e->getMessage();
	}
}

function getDataById($user_id){
	
	global $dbcon;
	
	try {
		
		$sql = "SELECT `record_updateddate`, `plan_id`, `next_paydate`, `plan_startdate`,`plan_enddate`, `id` FROM pmpro_dates_chk1 WHERE `user_id` = ".$user_id." ORDER BY `id` DESC";		
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);		
		return $result;
		
	}catch (PDOException $e){
		print $e->getMessage();
	}
}



try {
	
	$sql0 = "SELECT * FROM pmpro_dates_chk1 GROUP BY user_id";
	$stmt0 = $dbcon->prepare($sql0, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt0->execute();
	$users = $stmt0->fetchAll(PDO::FETCH_ASSOC);		
	$stmt0 = null;
}catch (PDOException $e){
	print $e->getMessage();
}

if(count($users) > 0){
	
	foreach($users as $user){		
		
		$user_id = $user['user_id'];

		//echo "\n user:".$user_id;
		
		$chkData = getDataById($user_id);		
			 
		$last_updatedate = $chkData['record_updateddate'];	

		//echo "\r last_updtd:".$last_updatedate;	

		$last_updatedate1 = new DateTime($last_updatedate);

		$today = new DateTime("now");
		$today = $today->format("Y-m-d");

		$today = new DateTime($today);

		$update= ($today > $last_updatedate1) ? true : false;

		//echo "\r updtd:".$update;	

		if(!$last_updatedate){
			$update=true;
		}

		$plan = $chkData['plan_id'];
		$agent_arr = get_var("select agent from pmpro_dates_chk1 where user_id=".$user_id." and plan_id=".$plan." order by id desc");
			$agent=$agent_arr['agent'];

		//echo "\r plan:".$plan;	
		echo "\r agent:".$agent;	
		//$plan_arr=get_var("select plan_id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc"); 
		//$plan=$plan_arr['plan_id'];
		//$user_arr=get_var("select user_id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc"); 
		//$user=$user_arr['user_id'];
		if($plan=="" and $user_id==""){
			$update=false;
			//echo "<script>console.log('upd:".$update."')</script>";
		}

		if($update){
			
			//dbl_chk			
			$plan = $chkData['plan_id'];	
			//echo "plan2:".$plan;		
			
			$plan_period_no_arr = get_var("select cycle_number from wp_pmpro_membership_levels where id = ".$plan." ");
			$plan_period_no = $plan_period_no_arr['cycle_number'];
			//echo "plan_period_no:".$plan_period_no;
			$plan_period_arr = get_var("select cycle_period from wp_pmpro_membership_levels where id = ".$plan." ");
			$plan_period = $plan_period_arr['cycle_period'];
			//echo "plan_period:".$plan_period;
			
			$agent_arr = get_var("select agent from pmpro_dates_chk1 where user_id=".$user_id." and plan_id=".$plan." order by id desc");
			$agent=$agent_arr['agent'];
			//echo "agent2:".$agent;
			$next_paydate = $chkData['next_paydate'];
			$enddate_chk = $chkData['plan_enddate'];
			//echo "prev(next paydate):".$next_paydate;
			if($next_paydate=="0000-00-00 00:00:00")
			{
			echo "<script>console.log('plan cancelled: no change in next_paydate')</script>";
			}
			else
			{
				if($agent=="no"){
					//echo "agent:".$agent;
						if(($plan_period_no != "") && ($plan_period != "")){

								$next_paydate = new DateTime($next_paydate);
								while($today >= $next_paydate){
									$next_paydate = $next_paydate->add(new DateInterval('P'.$plan_period_no.$plan_period[0]));
									print_r($next_paydate);
									}
							$next_paydate = $next_paydate->format("Y-m-d");	
					//echo "final(next paydate):".$next_paydate;	
								}
						}
			}


			
			$this_id = $chkData['id'];
			$plan_startdate = $chkData['plan_startdate'];

			if($agent=="no"){
	
					if( (new DateTime($plan_startdate)) > $today ){
						$delayeddate = $plan_startdate;
					}else{
						if($next_paydate=="0000-00-00 00:00:00")
								{$delayeddate=$enddate_chk;}
							else $delayeddate=$next_paydate;
					}
			}
			else
			{
					if($next_paydate=="0000-00-00 00:00:00")
								{$delayeddate=$enddate_chk;}
							else $delayeddate=$next_paydate;
			}			
		
			$delayeddate = new DateTime($delayeddate);
			$temp = date_diff($today,$delayeddate);
			$delayUpd = $temp->format('%R%a');

			if($delayUpd < 0){
				$delayUpd = 0;
			}else{
				$delayUpd = $temp->format('%a');
			}

			//db update
			$last_updatedate = new DateTime("now");
			$last_updatedate = $last_updatedate->format("Y-m-d");

			try {
				$sql = "UPDATE `pmpro_dates_chk1` SET `next_paydate` = '".$next_paydate."', `delay` = ".$delayUpd.", `record_updateddate` = '".$last_updatedate."' WHERE `id` = ".$this_id;
				$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
				$stmt->execute();
				$stmt = null;	

			}catch (PDOException $e){
				print $e->getMessage();
			}	
			
			
			try {
				$sql1 = "UPDATE `wp_options` SET `option_value` = '".$delayUpd."' WHERE `option_name` = 'pmpro_sub_support_delay_" . $user_id."'";
				$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
				$stmt1->execute();
				$stmt1 = null;

			}catch (PDOException $e){
				print $e->getMessage();
			}	
			
			
			if(defined('STDIN') ){
				echo "\nUpdated for user($user_id)  ---> `next_paydate` = '".$next_paydate."', `delay` = ".$delayUpd.", `record_updateddate` = '".$last_updatedate."'\n";
			}else{
				echo "<br>Updated for user($user_id)  ---> `next_paydate` = '".$next_paydate."', `delay` = ".$delayUpd.", `record_updateddate` = '".$last_updatedate."' <br>";
			}				
			
		}	
		
	}
}


?>

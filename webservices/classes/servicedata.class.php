<?php

Class ServiceData extends DBCon
	{

	// function processRegistration($username, $email, $phone){

	function processRegistration($username, $email, $password, $phone, $cc, $ip)
		{
		if ($username == "")
			{
			return "Username is empty";
			}
		  else
			{
			$userexit = $this->get_var('SELECT ID FROM wp_users WHERE user_login = "' . $username . '" ');
			if ((int)$userexit > 0)
				{
				return "Username already exist";
				}
			}

		if ($email == "")
			{
			return "Email is empty";
			}
		  else
			{
			$emexist = $this->get_var('SELECT ID FROM wp_users WHERE user_email = "' . $email . '"');
			if ((int)$emexist > 0)
				{
				return "Email already exist";
				}
			}

		if ($password == "") return "Password is empty";
		if ($phone == "") return "Phone is empty";
		if ($cc != "")
			{

			// check for coupon valid or not

			$url = "http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php";
			$data = $this->sendPost(array(
				'action' => 'couponCheck',
				'cc' => $cc
			) , $url);

			// $cpn = json_decode($data);

			$cpn = $data[0];
			$info1.= "cpn" . $cpn;
			if ($cpn == "1" or $cpn == "2")
				{
				if ($cpn == "1") return "Please enter a valid Couponcode/Promocode";
				if ($cpn == "2") return "Couponcode/Promocode  expired";
				}
			}

		// inputs ok

		try
			{
			$result23 = $this->get_all("SHOW TABLE status LIKE 'wp_users'");
			foreach($result23 as $res)
				{
				$userid = $res['Auto_increment'];
				}

			$date = new DateTime("now");
			$date = $date->format("Y-m-d H:i:s");
			$pass = md5($password);

			// $pass=md5($username.$userid);

			$meta_key = "uultra_user_registered_ip";
			$ipaddr = $ip;
			$sql22 = 'INSERT INTO wp_users(user_login,user_pass,user_email,user_nicename,phone,user_registered,display_name) VALUES("' . $username . '","' . $pass . '","' . $email . '","' . $username . '","' . $phone . '","' . $date . '","' . $username . '")';
			$exec22 = $this->sql_execute($sql22);

			$sql24 = 'INSERT INTO wp_usermeta(user_id,meta_key,meta_value) VALUES("' . $userid . '","' . $meta_key . '","' . $ipaddr . '")';
			$exec24 = $this->sql_execute($sql24);

			$meta_key = "usersultra_account_status";
			$sql24 = 'INSERT INTO wp_usermeta(user_id,meta_key,meta_value) VALUES("' . $userid . '","' . $meta_key . '","pending")';
			$exec24 = $this->sql_execute($sql24);

			$meta_key = "phone";
			$sql24 = 'INSERT INTO wp_usermeta(user_id,meta_key,meta_value) VALUES("' . $userid . '","' . $meta_key . '","'.$phone.'")';
			$exec24 = $this->sql_execute($sql24);

			 $rand = $this->genRandomString(8);
			$verify_key=session_id()."_".time()."_".$rand;
			$meta_key="xoouser_ultra_very_key";
			$sql24 = 'INSERT INTO wp_usermeta(user_id,meta_key,meta_value) VALUES("' . $userid . '","' . $meta_key . '","' . $verify_key . '")';
			$exec24 = $this->sql_execute($sql24);

			// give subscription here

			$sub_after_reg = $this->new_user_reg_cpn($userid, $cc);
		
	 	    $response['status'] = 1;
			return $response;
			}

		catch(Exception $e)
			{
			return "Error, Please try again";
			}
		}

	function get_act_key($username)
	{

		$key = $this->get_var('SELECT meta_value from wp_usermeta where meta_key="xoouser_ultra_very_key" and user_id=(SELECT ID from wp_users where user_login="'.$username.'")');
		return $key;
	}

	function get_sub_details($username)
	{

		$user_id=$this->get_var("SELECT ID from wp_users where user_login='".$username."'");

		$plan_id=$this->get_var("SELECT membership_id from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");
		$coupon=$this->get_var("SELECT code_id from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");
		$plan_ends=$this->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=$user_id and status='active' order by id desc limit 1");


		//$plan_ends=new Date($plan_ends);
		$plan_ends=new DateTime($plan_ends);
		$plan_ends=$plan_ends->format("d-m-Y");

		//$plan_details=$wpdb->get_var("SELECT * from wp_pmpro_membership_levels where id=$plan_id");

		
		$plan_name=$this->get_var("SELECT name from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_no=$this->get_var("SELECT cycle_number from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_prd=$this->get_var("SELECT cycle_period from wp_pmpro_membership_levels where id=$plan_id");

		$delay=$this->get_var("SELECT delay from pmpro_dates_chk1 where user_id=$user_id order by id desc limit 1");
		
		if($coupon!=""){
			if($coupon[0]!="Q" and $coupon[0]!="0")
			$sub_stat.="With Promo Code: $coupon ,";
			elseif($coupon[0]=="Q")
			$sub_stat.="With Coupon Code: $coupon ,";
				}

		if($plan_id==4)
			$sub_stat.="You are awarded $delay-day(s) of Free Trial.";
		else
			$sub_stat.="You are awarded $plan_name plan of $plan_cyc_no $plan_cyc_prd(s), valid till $plan_ends .";

			return $sub_stat;

	}

	public function genRandomString($length) 
  {
		
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ";
		
		$real_string_legnth = strlen($characters) ;
		//$real_string_legnth = $real_string_legnth– 1;
		$string="ID";
		
		for ($p = 0; $p < $length; $p++)
		{
			$string .= $characters[mt_rand(0, $real_string_legnth-1)];
		}
		
		return strtolower($string);
	}

	function getUserInfo($by, $value)
		{
		try
			{
			$sql1 = 'SELECT * FROM wp_users WHERE ' . $by . ' = "' . $value . '"';
			$result21 = $this->get_all($sql1);
			if (count($result21) > 1)
				{
				return $result21;
				}
			  else
				{
				return "User not found";
				}
			}

		catch(Exception $e)
			{
			return "Error, Please try again";
			}
		}

	function get_var($sql)
		{

		// global $dbcon;

		
		try
			{
			$stmt = $this->db->prepare($sql, array(
				PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
			));
			$stmt->execute();
			$result = $stmt->fetchColumn();
			$stmt = null;

			//echo $result;
			return $result;
			}

		catch(PDOException $e)
			{
			print $e->getMessage();
			}
		}

	function get_all($sql)
		{

		// global $dbcon;

		try
			{
			$stmt = $this->db->prepare($sql, array(
				PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
			));
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt = null;

			// $dbcon=null;

			return $result;
			}

		catch(PDOException $e)
			{
			print $e->getMessage();
			}
		}

	function sql_execute($sql)
		{
		try
			{
			$stmt22 = $this->db->prepare($sql, array(
				PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
			));
			$stmt22->execute();
			$stmt22 = null;
			}

		catch(PDOException $e)
			{
			print $e->getMessage();
			}
		}


	//added for curl
function sendPost($data,$url) {

    $ch = curl_init();
    // you should put here url of your getinfo.php script
    curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec ($ch); 
	
    return $result; 
}

//ended

	function processLogin($un)
		{
		echo $un;
		return $this->get_all("SELECT * from wp_users where user_login='" . $un . "'");
		}

	function new_user_reg_cpn($user_id, $cc)
		{

		// Registration Done: Give 7-day free Trail: only if there is no coupon

		if ($cc == "")
			{
			// global $wpdb;
			$status = "active";
			$d = new DateTime("now");
			$startdate = $d->format("Y-m-d H:i:s");
			$e = $d->add(new DateInterval('P7D'));
			$enddate = $e->format("Y-m-d H:i:s");
			$plan_id = 4;

			// $wpdb->insert("wp_pmpro_memberships_users",array('user_id'=>$user_id,'membership_id'=>4,'initial_payment'=>0.00,'billing_amount'=>0.00,'cycle_number'=>0,'cycle_period'=>'','billing_limit'=>0,'trial_amount'=>0.00,'trial_limit'=>0,'status'=>'active','startdate'=>$startdate,'enddate'=>$enddate));

			$sql22 = 'INSERT INTO wp_pmpro_memberships_users(user_id,membership_id,initial_payment,billing_amount,cycle_number,cycle_period,billing_limit,trial_amount,trial_limit,status,startdate,enddate) VALUES(' . $user_id . ',4,0.00,0.00,0,"",0,0.00,0,"active","' . $startdate . '","' . $enddate . '")';
			$exec22 = $this->sql_execute($sql22);
			$coupon_code = '-';
			}
		  else
			{

			// global $wpdb;

			$status = "active";
			$d = new DateTime("now");
			$startdate = $d->format("Y-m-d H:i:s");
			$start = $d->format("Y-m-d");
			$coupon_code = $cc;
			$cpn_details = $this->get_all("SELECT * from buy_a_friend where coupon_code='" . $coupon_code . "' and status='Paid'");
			$promo_cpn_details = $this->get_all("SELECT * from promo_codes where coupon_code='" . $coupon_code . "' and usage_count<max_count");
			if (count($cpn_details) < 1 && count($promo_cpn_details) < 1)
				{
				return "Invalid Code";
				}

			if (count($cpn_details) >= 1)
				{
				foreach($cpn_details as $cpn)
					{
					$plan_id = $cpn['plan_id'];
					}
				}

			if (count($promo_cpn_details) >= 1)
				{
				foreach($promo_cpn_details as $cpn)
					{
					$plan_id = $cpn['plan_id'];
					}
				}

			// $e=$d->add(new DateInterval('P7D'));

			$plan_details = $this->get_all("SELECT * from wp_pmpro_membership_levels where id=" . $plan_id . " ");
			foreach($plan_details as $plan)
				{

				// $plan_id=$plan->plan_id;

				$i_amt = $plan['initial_payment'];
				$b_amt = $plan['billing_amount'];
				$c_no = $plan['cycle_number'];
				$c_prd = $plan['cycle_period'];
				$b_limit = $plan['billing_limit'];
				$t_amt = $plan['trial_amount'];
				$t_limit = $plan['trial_limit'];
				$exp_no = $plan['expiration_number'];
				$exp_prd = $plan['expiration_period'];
				}

			if ($c_no == 0)
				{

				// $plan_id==4 free trial

				$e = $d->add(new DateInterval('P' . $exp_no . $exp_prd[0]));
				if (count($promo_cpn_details) >= 1)
					{
					$e = $e->add(new DateInterval('P2W')); //extra 2weeks as for promo_code
					}
				}
			  else
				{
				$e = $d->add(new DateInterval('P' . $c_no . $c_prd[0]));
				}

			$enddate = $e->format("Y-m-d H:i:s");
			$end = $e->format("Y-m-d");

			// $wpdb->insert("wp_pmpro_memberships_users",array('user_id'=>$user_id,'membership_id'=>$plan_id,'code_id'=>$coupon_code,'initial_payment'=>$i_amt,'billing_amount'=>$b_amt,'cycle_number'=>$c_no, 'cycle_period'=>$c_prd,'billing_limit'=>$b_limit,'trial_amount'=>$t_amt,'trial_limit'=>$t_limit,'status'=>'active','startdate'=>$startdate,'enddate'=>$enddate));

			$sql1 = 'INSERT into wp_pmpro_memberships_users(user_id,membership_id,code_id,initial_payment,billing_amount,cycle_number,cycle_period,billing_limit,trial_amount,trial_limit,status,startdate,enddate) VALUES(' . $user_id . ',' . $plan_id . ',"' . $coupon_code . '",' . $i_amt . ',' . $b_amt . ',' . $c_no . ',"' . $c_prd . '",' . $b_limit . ',' . $t_amt . ',' . $t_limit . ',"active","' . $startdate . '","' . $enddate . '")';
			$exec1 = $this->sql_execute($sql1);
			if (count($promo_cpn_details) >= 1)
				{
				$count = $this->get_var("SELECT usage_count from promo_codes where coupon_code='" . $coupon_code . "' ");
				$cnt = $count + 1;
				$sql1 = "UPDATE promo_codes SET usage_count=" . $cnt . " where coupon_code='" . $coupon_code . "'";
				$exec1 = $this->sql_execute($sql1);

				// $wpdb->update("promo_codes", array("usage_count"=>$count+1), array("coupon_code"=>$coupon_code));

				$sql2 = 'INSERT into promocodes_vs_users(promocode,user_id,start_datetime,end_datetime) VALUES("' . $coupon_code . '",' . $user_id . ',"' . $start . '","' . $end . '")';
				$exec2 = $this->sql_execute($sql2);

				// $wpdb->insert("promocodes_vs_users",array("promocode"=>$coupon_code,"user_id"=>$user_id,"start_datetime"=>$start,"end_datetime"=>$end));

				$sql3 = 'INSERT into wp_options(option_name,option_value) VALUES("promo_used_' . $user_id . '","true")';
				$exec3 = $this->sql_execute($sql3);

				// update_option('promo_used_'.$user_id,'true');

				}
			  else
				{
				$sql1 = 'UPDATE buy_a_friend SET status="Coupon Used" WHERE coupon_code="' . $coupon_code . '"';
				$exec1 = $this->sql_execute($sql1);

				// $wpdb->update("buy_a_friend", array("status"=>"Coupon Used"), array("coupon_code"=>$coupon_code));					}

				}

			}

			$sql3 = 'INSERT into wp_options(option_name,option_value) VALUES("subscribed_' . $user_id . '","true")';
			$exec3 = $this->sql_execute($sql3);

			// update_option("subscribed_".$user_id,"true");
			// other functions after subscription

			$this->new_user_regsub($user_id, $plan_id, $coupon_code, $startdate, $enddate);
		}

		function new_user_regsub($user_id, $plan_id, $coupon_code, $startdate, $enddate)
			{

			// global $wpdb;

			$txt = "---------------- /n ";
			$old_planid = 0;
			if ($old_planid == 0)
				{
				$old_planname = "---";
				}
			  else
				{
				$old_planname = $this->get_var("SELECT name FROM wp_pmpro_membership_levels where id=" . $old_planid . " ");
				}

			$txt.= "oldplanname:" . $old_planname;
			$planid = $plan_id;
			$planname = $this->get_var("SELECT name FROM wp_pmpro_membership_levels where id=" . $planid . " ");
			$txt.= "planid:" . $planid;
			$txt.= "planname:" . $planname;
			if ($planid == 0)
				{
				$cancelled = true;
				$txt.= "cancelled:true";
				}

			$startdate = $startdate;
			$trail_period_no = $this->get_var("SELECT expiration_number FROM wp_pmpro_membership_levels where id=" . $planid . " ");
			$trail_period = $this->get_var("SELECT expiration_period FROM wp_pmpro_membership_levels where id=" . $planid . " ");
			$cycle_period_no = $this->get_var("SELECT cycle_number FROM wp_pmpro_membership_levels where id=" . $planid . " ");
			$cycle_period = $this->get_var("SELECT cycle_period FROM wp_pmpro_membership_levels where id=" . $planid . " ");
			$txt.= "1.startdate:" . $startdate;
			$txt.= "trail_period_no:" . $trail_period_no;
			$txt.= "trail_period:" . $trail_period;
			$delay = $this->get_var("SELECT option_value from wp_options where option_name='pmpro_subscription_delay_" . $planid . "' ");

			// $delay= get_option("pmpro_subscription_delay_" . $planid, "");

			$txt.= "delay:" . $delay;
			if ($delay == "") $free = "free";
			$abc = "new user-" . $free;

			// if delay is null pass 0

			if ($delay == "") $delay = 0;
			$txt.= "delay:" . $delay;
			$txt.= "abc:" . $abc;
			$sql1 = 'INSERT into wp_options(option_name,option_value) VALUES("pmpro_sub_support_prev_delay_' . $user_id . '","' . $delay . '")';
			$exec1 = $this->sql_execute($sql1);

			// update_option("pmpro_sub_support_prev_delay_".$user_id,$delay); //added

			$plan_startdate = $startdate;
			$txt.= "plan_startdate:" . $plan_startdate;
			if ($cycle_period_no == 0 or $cycle_period == "") //$planid == 4 //free trial plan
				{
				$agent = "free-trial/$coupon_code";
				}
			  else
				{
				$agent = $coupon_code;
				}

			$next_paydate = $enddate;
			$txt.= "next_paydate:" . $next_paydate;
			$txt.= "agent:" . $agent;

			// delay update after the new/change plan

			$today = new DateTime("now");
			$today = $today->format("Y-m-d");
			$today = new DateTime($today);
			$delayeddate = $next_paydate;
			$txt.= "delayeddate:" . $delayeddate;
			$delayeddate = new DateTime($delayeddate);
			$temp = date_diff($today, $delayeddate);
			$delayUpd = $temp->format('%R%a');
			if ($delayUpd < 0)
				{
				$delayUpd = 0;
				}
			  else
				{
				$delayUpd = $temp->format('%a');
				}

			$txt.= "delayUpd:" . $delayUpd;

			// insert or update only if the plan is not cancelled ie. plan id exists >0

			if ($planid > 0)
				{
				$sql1 = 'REPLACE into pmpro_dates_chk1(user_id,old_plan_id,old_plan_name,plan_id,plan_name,startdate,plan_startdate,next_paydate,delay,agent) VALUES(' . $user_id . ',' . $old_planid . ',"' . $old_planname . '",' . $planid . ',"' . $planname . '","' . $startdate . '","' . $plan_startdate . '","' . $next_paydate . '",' . $delayUpd . ',"' . $agent . '")';
				$exec1 = $this->sql_execute($sql1);
				/*	$wpdb->replace("pmpro_dates_chk1", array(
				"user_id" => $user_id,
				"old_plan_id" => $old_planid,
				"old_plan_name" => $old_planname,
				"plan_id" => $planid,
				"plan_name" => $planname,
				"startdate" => $startdate,
				"plan_startdate" => $plan_startdate,
				"next_paydate"=>$next_paydate,
				"delay"=>$delayUpd,
				"agent"=>$agent
				)); 		*/

				// copy delayUpd to wp_options
				// update_option("pmpro_sub_support_delay_".$user_id, $delayUpd);

				$sql1 = 'UPDATE wp_options SET option_value="' . $delayUpd . '" WHERE option_name="pmpro_sub_support_delay_' . $user_id . '"';
				$exec1 = $this->sql_execute($sql1);

				// update_option("pmpro_sub_support_delay_" . $current_user->ID, $subscription_delay);
				// $fromdb=get_option("pmpro_sub_support_delay_".$user_id);

				$fromdb = $this->get_var("SELECT option_value from wp_options where option_name='pmpro_subscription_delay_" . $user_id . "' ");
				$txt.= "delay_from_db:" . $fromdb;
				}

			$txt.= "startdate:" . $startdate . "-or--usercount-" . $userCount . "-delay-" . $delay . "-nextpay-" . $next_paydate;
			mail("siddish.gollapelli@ideabytes.com", "Subscription After Registration FBReg", print_r($txt, true));
			$file = fopen("testFBReg.txt", "w");
			fwrite($file, $txt);
			fclose($file);
			}

		// end new_user_regsub

		}



  

 
 

 

 

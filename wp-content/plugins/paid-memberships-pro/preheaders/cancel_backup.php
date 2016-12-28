<?php
	global $besecure;
	$besecure = false;

	global $current_user, $pmpro_msg, $pmpro_msgt, $pmpro_confirm, $pmpro_error;

	//get level information for current user
	if($current_user->ID)
		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);

	//if no user or membership level, redirect to levels page
	if(!isset($current_user->membership_level->ID)) {
		wp_redirect(pmpro_url("levels"));
		exit;
	}

	//are we confirming a cancellation?
	if(isset($_REQUEST['confirm']))
		$pmpro_confirm = $_REQUEST['confirm'];
	else
		$pmpro_confirm = false;

	if($pmpro_confirm) {
		$old_level_id = $current_user->membership_level->id;
        $worked = pmpro_changeMembershipLevel(false, $current_user->ID, 'cancelled');

		
			//added
			//echo "alert('::::Note down the data from alerts:::::');";
			$txt="";
			global $wpdb;
			//$planstartdate=$wpdb->get_var("select plan_start_date from sub_support where user_id=".$current_user->ID." order by id desc limit 1 ");
			//$nextpaydate=$wpdb->get_var("select next_pay_date from sub_support where user_id=".$current_user->ID." order by id desc limit 1 ");

$planstartdate=$wpdb->get_var("select plan_startdate from pmpro_dates_chk1 where user_id=".$current_user->ID." order by id desc limit 1 ");
$nextpaydate=$wpdb->get_var("select next_paydate from pmpro_dates_chk1 where user_id=".$current_user->ID." order by id desc limit 1 ");
				$today=new DateTime("now");
				$today=$today->format("Y-m-d");
				$today=new DateTime($today);
				$planstartdate=new DateTime($planstartdate);
			
				if($planstartdate > $today )
				{
					$enddate=$planstartdate->format("Y-m-d");
				}
				else
				{
					$enddate=$nextpaydate;
				}

			$txt.=$enddate;
			
			$enddateUpd=$enddate;
			
			$enddateUpd=date('Y-m-d', strtotime($enddateUpd. ' -1 days'));
			
			$this_id=$wpdb->get_var("SELECT id FROM wp_pmpro_memberships_users where user_id=".$current_user->ID." order by id desc limit 1");
			$txt.=$this_id;
			//$updated=$wpdb->update("wp_pmpro_memberships_users",array(   "status" => "active",   "enddate"=>$enddateUpd), array("id"=>$this_id)); 
			
			$updated=$wpdb->query($wpdb->prepare("UPDATE wp_pmpro_memberships_users SET status=%s, enddate=%s WHERE id=%d",'active',$enddateUpd,$this_id));

			//added for change of plan
				
				$today=new DateTime("now");
				$today=$today->format("Y-m-d");
				$today=new DateTime($today);

				$enddate=new DateTime($enddate);
				$enddate=$enddate->format("Y-m-d");
				$enddate=new DateTime($enddate);


				$temp = date_diff($today,$enddate);
				$delay=$temp->format('%R%a');

				if($delay < 0)
 					{$delay=0;}
				else
				{$delay=$temp->format('%a');}

				$subscription_delay=$delay;
				//echo "<script>alert('sub_delay'+'".$subscription_delay."')</script>";
				update_option("pmpro_sub_support_delay_" . $current_user->ID, $subscription_delay); //save new delay for this user on change of plan
					$chk=get_option('pmpro_sub_support_delay_' . $current_user->ID);
				//echo "<script>alert('upd_del:'+'".$chk."')</script>";


$last_updatedate=new DateTime("now");
$last_updatedate=$last_updatedate->format("Y-m-d");
$next_paydate="0000-00-00 00:00:00";

$this_chk_id=$wpdb->get_var("select id from pmpro_dates_chk1 where user_id=".$current_user->ID." order by id desc limit 1 ");
//echo "<script>alert('nxt pay:'+'".$next_paydate."')</script>";

//echo "<script>alert('insertion_col_id from chk:'+'".$this_chk_id."')</script>";

		

$wpdb->update("pmpro_dates_chk1", array(
   "next_paydate" => $next_paydate,
	"delay"=> $subscription_delay,
   "record_updateddate"=>$last_updatedate),array("id"=>$this_chk_id)); 


 						
			//ended			

        if($worked === true && empty($pmpro_error))
		{
			
			
			$pmpro_msg = __("Your membership has been cancelled.", 'pmpro');
			$pmpro_msgt = "pmpro_success";

			//send an email to the member
			$myemail = new PMProEmail();
			$myemail->sendCancelEmail();

			//send an email to the admin
			$myemail = new PMProEmail();
			$myemail->sendCancelAdminEmail($current_user, $old_level_id);
			

			

		} else {
			global $pmpro_error;
			$pmpro_msg = $pmpro_error;
			$pmpro_msgt = "pmpro_error";
		}
	}
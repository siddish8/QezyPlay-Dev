<?php
/*
Plugin Name: Paid Memberships Pro - Subscription Delays Addon 
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-subscription-delays/
Description: Add a field to levels and discount codes to delay the start of a subscription by X days. (Add variable-length free trials to your levels.)
Version: .4.3
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
*/

//add subscription delay field to level price settings
function pmprosd_pmpro_membership_level_after_other_settings()
{
	$level_id = intval($_REQUEST['edit']);
	$delay = get_option("pmpro_subscription_delay_" . $level_id, "");
?>
<table>
<tbody class="form-table">
	<tr>
		<td>
			<tr>
				<th scope="row" valign="top"><label for="subscription_delay">Subscription Delay:</label></th>
				<td><input name="subscription_delay" type="text" size="20" value="<?php echo esc_attr($delay);?>" /> <small># of days to delay the start of the subscription. If set, this will override any trial/etc defined above.</small></td>
			</tr>
		</td>
	</tr> 
</tbody>
</table>
<?php
}
add_action("pmpro_membership_level_after_other_settings", "pmprosd_pmpro_membership_level_after_other_settings");

//save subscription delays for the code when the code is saved/added
function pmprosd_pmpro_save_membership_level($level_id)
{
	$subscription_delay = $_REQUEST['subscription_delay'];	//subscription delays for levels checked
	update_option("pmpro_subscription_delay_" . $level_id, $subscription_delay);
}
add_action("pmpro_save_membership_level", "pmprosd_pmpro_save_membership_level");

//add subscription delay field to level price settings
function pmprosd_pmpro_discount_code_after_level_settings($code_id, $level)
{
	$delays = pmpro_getDCSDs($code_id);
	if(!empty($delays[$level->id]))
		$delay = $delays[$level->id];
	else
		$delay = "";
?>
<table>
<tbody class="form-table">
	<tr>
		<td>
			<tr>
				<th scope="row" valign="top"><label for="subscription_delay">Subscription Delay:</label></th>
				<td><input name="subscription_delay[]" type="text" size="20" value="<?php echo esc_attr($delay);?>" /> <small># of days to delay the start of the subscription. If set, this will override any trial/etc defined above.</small></td>
			</tr>
		</td>
	</tr> 
</tbody>
</table>
<?php
}
add_action("pmpro_discount_code_after_level_settings", "pmprosd_pmpro_discount_code_after_level_settings", 10, 2);

//save subscription delays for the code when the code is saved/added
function pmprosd_pmpro_save_discount_code_level($code_id, $level_id)
{
	$all_levels_a = $_REQUEST['all_levels'];							//array of level ids checked for this code
	$subscription_delay_a = $_REQUEST['subscription_delay'];	//subscription delays for levels checked
	
	if(!empty($all_levels_a))
	{	
		$key = array_search($level_id, $all_levels_a);				//which level is it in the list?		
		$delays = pmpro_getDCSDs($code_id);						//get delays for this code		
		$delays[$level_id] = $subscription_delay_a[$key];			//add delay for this level		
		pmpro_saveDCSDs($code_id, $delays);						//save delays		
	}	
}
add_action("pmpro_save_discount_code_level", "pmprosd_pmpro_save_discount_code_level", 10, 2);

//update subscription start date based on the discount code used or levels subscription start date
function pmprosd_pmpro_profile_start_date($start_date, $order)
{	
	$subscription_delay = null;
	
	//if a discount code is used, we default to the setting there
	if(!empty($order->discount_code))
	{
		global $wpdb;
		
		//get code id
		$code_id = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_discount_codes WHERE code = '" . esc_sql($order->discount_code) . "' LIMIT 1");				
		if(!empty($code_id))
		{
			//we have a code
			$delays = pmpro_getDCSDs($code_id);
			if(!empty($delays[$order->membership_id]))
			{
				if(!is_numeric($delays[$order->membership_id]))		
					$subscription_delay = pmprosd_daysUntilDate($delays[$order->membership_id]);
				else
					$subscription_delay = $delays[$order->membership_id];
				
				//we have a delay for this level, set the start date to X days out



				
			//added on 13-06-2016	
			global $current_user;
			global $wpdb;
			get_currentuserinfo();
			$count=0;
			$this_user = get_current_user_id();
			
			// Comented and added on 18-06-2016
			
			$userCount = $wpdb->get_var("SELECT count(user_id) as count FROM wp_pmpro_memberships_users where user_id = ".$this_user); 
			$userAgentCount = $wpdb->get_var("SELECT count(subscriber_id) as countA FROM agent_vs_subscription_credit_info where subscriber_id = ".$this_user); 
		
			$userTotalCount=(int)$userCount+(int)$userAgentCount;
			
			if($userTotalCount >= 1) {
				$subscription_delay=0;
			}
							
				$subscription_delay=$subscription_delay+1;
				$start_date = date("Y-m-d", strtotime("+ " . intval($subscription_delay) . " Days", current_time('timestamp'))) . "T0:0:0";
			}
		}
	}
	else
	{

		global $current_user;
			global $wpdb;
			get_currentuserinfo();
			$count=0;
			$this_user = get_current_user_id();
		$file=fopen("sub_delay_chk.txt","a");
		//check the level for a subscription delay
		$subscription_delay = get_option("pmpro_subscription_delay_" . $order->membership_id, "");
		$txt="delay from level:".$subscription_delay."\n";
		fwrite($file,$txt);
		
		
		if(!is_numeric($subscription_delay))		
			$subscription_delay = pmprosd_daysUntilDate($subscription_delay);
		$txt="delay from level2:".$subscription_delay."\n";
		fwrite($file,$txt);

		if($subscription_delay=="" || $subscription_delay==0)

		$subscription_delay=$wpdb->get_var("select delay from pmpro_dates_chk1 where user_id=".$this_user." order by id desc limit 1");
		$subscription_delay=(int)$subscription_delay;
		$txt="delay from carried plan:".$subscription_delay."\n";
		fwrite($file,$txt);

		//if(!empty($subscription_delay))
		if($subscription_delay >= 0)
		{
			$txt="Inside the IF--:\n";
		fwrite($file,$txt);
			
			//added on 13-06-2016	
			

			
			$userCount = $wpdb->get_var("SELECT count(user_id) as count FROM wp_pmpro_memberships_users where user_id = ".$this_user); 
			$userAgentCount = $wpdb->get_var("SELECT count(subscriber_id) as countA FROM agent_vs_subscription_credit_info where subscriber_id = ".$this_user); 
		
			$userTotalCount=(int)$userCount+(int)$userAgentCount;
			
			$txt="COunt:".$userCount."Agent:".$userAgentCount."Total".$userTotalCount."\n";
		fwrite($file,$txt);

			//$delay_from_support=get_option("pmpro_sub_support_delay_" . $this_user);

			$delay_from_support=$wpdb->get_var("select delay from pmpro_dates_chk1 where user_id=".$this_user." order by id desc limit 1");
						
			//if($userCount >= 1){ //changed on 13-07-2016
			if($userTotalCount >= 1) {
						$change=true;
						$subscription_delay=(int)$delay_from_support;
					
						//$subscription_delay=0;
						//if($delay_from_support != 0)
							//{
								//$subscription_delay=$pmpro_sub_support_delay_."$this_user";
								//	$subscription_delay=$delay_from_support;
							//}
					}
			else
				{
					$change=false;
					$subscription_delay=get_option("pmpro_subscription_delay_" . $order->membership_id);
				}

			$txt="change:".$change."Delay:".$subscription_delay."\n";
		fwrite($file,$txt);
			//end
					//$subscription_delay=$subscription_delay+1;

				$subscription_delay=(int)$subscription_delay; //removed +1 because it is adding extra day @paypal
				

				if($subscription_delay==0)
				{
				//$start_date = new DateTime("now");
				//$start_date=$start_date->format("Y-m-d H:i:s");
				$start_date = date("Y-m-d", strtotime("+ " . intval($subscription_delay) . " Days", current_time('timestamp'))) . "T0:0:0";
				$txt="Delay 0: so start date is today i.e.,".$start_date;
				fwrite($file,$txt);

				}

				else
				{
				$start_date = date("Y-m-d", strtotime("+ " . intval($subscription_delay) . " Days", current_time('timestamp'))) . "T0:0:0";
				$txt="Delay Exists: $subscription_delay so start date is :".$start_date;
				fwrite($file,$txt);
				}
			/*if($change){		
				$start_date = date("Y-m-d", strtotime("+ " . intval($subscription_delay+1) . " Days", current_time('timestamp'))) . "T0:0:0";
				}*/

				$txt="start date:".$start_date."Final Delay1:".$subscription_delay."\n";
		fwrite($file,$txt);
		}
	}
		
	$start_date = apply_filters( 'pmprosd_modify_start_date', $start_date, $order, $subscription_delay );
	$file2=fopen("subs2.txt","a");
	$txt="start date2:".$start_date."Final Delay2:".$subscription_delay."\n";
		fwrite($file2,$txt);
	return $start_date;
}
add_filter("pmpro_profile_start_date", "pmprosd_pmpro_profile_start_date", 10, 2);

/**
 * Save a "pmprosd_trialing_until" user meta after checkout.
 *
 * @since .4
*/
function pmprosd_pmpro_after_checkout($user_id)
{
	$level = pmpro_getMembershipLevelForUser($user_id);;
	if(!empty($level))
	{
		$subscription_delay = get_option("pmpro_subscription_delay_" . $level->id, "");
		if($subscription_delay)
		{
			$trialing_until = strtotime("+" . $subscription_delay . " Days", current_time('timestamp'));
			update_user_meta($user_id, "pmprosd_trialing_until", $trialing_until);
		}
		else
			delete_user_meta($user_id, "pmprosd_trialing_until");
	}
}
add_action('pmpro_after_checkout', 'pmprosd_pmpro_after_checkout');

/**
 * Use the pmprosd_trialing_until value to calculate pmpro_next_payment when applicable
 *
 * @since .4
*/
function pmprosd_pmpro_next_payment($timestamp, $user_id, $order_status)
{
	//find the last order for this user
	if(!empty($user_id) && !empty($timestamp))
	{
		$trialing_until = get_user_meta($user_id, "pmprosd_trialing_until", true);
		if(!empty($trialing_until) && $trialing_until > current_time('timestamp'))
			$timestamp = $trialing_until;
	}
				
	return $timestamp;
}
add_filter('pmpro_next_payment', 'pmprosd_pmpro_next_payment', 10, 3);

/*
	Calculate how many days until a certain date (e.g. in YYYY-MM-DD format)
	
	Some logic taken from: http://stackoverflow.com/a/654378/1154321
*/
function pmprosd_daysUntilDate($date)
{
	//replace vars
	$Y = date("Y");
	$Y2 = intval($Y) + 1;
	$M = date("m");
	if($M == 12)
		$M2 = "01";
	else
		$M2 = str_pad(intval($M) + 1, 2, "0", STR_PAD_LEFT);

	$searches = array("Y-", "Y2-", "M-", "M2-");
	$replacements = array($Y . "-", $Y2 . "-", $M . "-", $M2 . "-");

	$date = str_replace($searches, $replacements, $date);

	$datetime = strtotime($date, current_time('timestamp'));

	$today = current_time('timestamp');
	$diff = $datetime - $today;
	if($diff < 0)
		return 0;
	else
		return floor($diff/60/60/24);
}

/*
	Add discount code and code id to the level object so we can use them later
*/
function pmprosd_pmpro_discount_code_level($level, $code_id)
{
	$level->code_id = $code_id;
	return $level;
}
add_filter("pmpro_discount_code_level", "pmprosd_pmpro_discount_code_level", 10, 2);

/*
	Change the Level Cost Text
*/
function pmprosd_level_cost_text($cost, $level)
{
	if(!empty($level->code_id))
	{
		$all_delays = pmpro_getDCSDs($level->code_id);
		
		if(!empty($all_delays) && !empty($all_delays[$level->id]))
			$subscription_delay = $all_delays[$level->id];
	}
	else
	{
		$subscription_delay = get_option("pmpro_subscription_delay_" . $level->id, "");
	}
	
	$find = array("Year.", "Month.", "Week.", "Year</strong>.", "Month</strong>.", "Week</strong>.", "Years.", "Months.", "Weeks.", "Years</strong>.", "Months</strong>.", "Weeks</strong>.", "payments.", "payments</strong>.");
	$replace = array("Year", "Month", "Week", "Year</strong>", "Month</strong>", "Week</strong>", "Years", "Months", "Weeks", "Years</strong>", "Months</strong>", "Weeks</strong>", "payments", "payments</strong>");

	if (function_exists('pmpro_getCustomLevelCostText')) {
		$custom_text = pmpro_getCustomLevelCostText($level->id);
	} else {
		$custom_text = null;
	}
	
        if ( empty($custom_text) )
        {
                if(!empty($subscription_delay) && is_numeric($subscription_delay))
                {
                        $cost = str_replace($find, $replace, $cost);
                        $cost .= " after your <strong>" . $subscription_delay . " day trial</strong>.";
                }
                elseif(!empty($subscription_delay))
                {
                        $cost = str_replace($find, $replace, $cost);
                        $cost .= " starting " . date_i18n(get_option("date_format"), strtotime($subscription_delay, current_time("timestamp"))) . ".";
                }
        }
        
	return $cost;
}
add_filter("pmpro_level_cost_text", "pmprosd_level_cost_text", 10, 2);

/*
	Let's call these things "discount code subscription delays" or DCSDs.
	
	This function will save an array of delays (level_id => days) into an option storing delays for all code.
*/
function pmpro_saveDCSDs($code_id, $delays)
{	
	$all_delays = get_option("pmpro_discount_code_subscription_delays", array());		
	$all_delays[$code_id] = $delays;
	update_option("pmpro_discount_code_subscription_delays", $all_delays);
}

/*
	This function will return the saved delays for a certain code.
*/
function pmpro_getDCSDs($code_id)
{
	$all_delays = get_option("pmpro_discount_code_subscription_delays", array());		
	if(!empty($all_delays) && !empty($all_delays[$code_id]))
		return $all_delays[$code_id];
	else
		return false;
}

/**
 * Get the delay for a specific level/code combo
 */
function pmprosd_getDelay($level_id, $code_id = NULL) {
	if(!empty($code_id)) {
		$delays = pmpro_getDCSDs($code_id);
		if(!empty($delays[$level_id]))
			return $delays[$level_id];
		else
			return "";
	} else {
		$subscription_delay = get_option("pmpro_subscription_delay_" . $level_id, "");
		return $subscription_delay;
	}
}

/**
 * With Authorize.net, we need to set the trialoccurences to 0
 */
function pmprosd_pmpro_subscribe_order($order, $gateway) {
	if($order->gateway == "authorizenet") {
		if(!empty($order->discount_code_id))
			$subscription_delay = pmprosd_getDelay($order->membership_id, $order->discount_code_id);
		else
			$subscription_delay = pmprosd_getDelay($order->membership_id);
		
		if(!empty($subscription_delay) && $order->TrialBillingCycles == 1)
			$order->TrialBillingCycles = 0;
	}

	return $order;
}
add_filter('pmpro_subscribe_order', 'pmprosd_pmpro_subscribe_order', 10, 2);

/*
Function to add links to the plugin row meta
*/
function pmprosd_plugin_row_meta($links, $file) {
	if(strpos($file, 'pmpro-subscription-delays.php') !== false)
	{
		$new_links = array(
			'<a href="' . esc_url('http://www.paidmembershipspro.com/add-ons/plugins-on-github/subscription-delays/')  . '" title="' . esc_attr( __( 'View Documentation', 'pmpro' ) ) . '">' . __( 'Docs', 'pmpro' ) . '</a>',
			'<a href="' . esc_url('http://paidmembershipspro.com/support/') . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro' ) ) . '">' . __( 'Support', 'pmpro' ) . '</a>',
		);
		$links = array_merge($links, $new_links);
	}
	return $links;
}
add_filter('plugin_row_meta', 'pmprosd_plugin_row_meta', 10, 2);

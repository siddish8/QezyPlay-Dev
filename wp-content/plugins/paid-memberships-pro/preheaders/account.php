<?php

global $wpdb, $current_user, $pmpro_msg, $pmpro_msgt;

if($current_user->ID)
    $current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);

if (isset($_REQUEST['msg'])) {
    if ($_REQUEST['msg'] == 1) {
        $pmpro_msg = __('Your subscription status has been updated - Thank you!', 'pmpro');
    } else {
        $pmpro_msg = __('Sorry, your request could not be completed - please try again in a few moments.', 'pmpro');
        $pmpro_msgt = "pmpro_error";
    }
} else {
    $pmpro_msg = false;
}

//if no user, redirect to levels page
if (empty($current_user->ID)) {
    $redirect = apply_filters("pmpro_account_preheader_no_user_redirect", pmpro_url("levels"));
    if ($redirect) {
        wp_redirect($redirect);
        exit;
    }
}

//if no membership level, redirect to levels page
if (empty($current_user->membership_level->ID)) {
    $redirect = apply_filters("pmpro_account_preheader_redirect", pmpro_url("levels"));
    if ($redirect) {
        //wp_redirect($redirect);
get_header();

		global $current_user;
		global $wpdb;
		get_currentuserinfo();
		$this_user = get_current_user_id();
		$userCount = $wpdb->get_var("SELECT count(user_id) as count FROM wp_pmpro_memberships_users where user_id = ".$this_user); 

		$userCount1 = $wpdb->get_var("SELECT count(subscriber_id) as count FROM agent_vs_subscription_credit_info where subscriber_id = ".$this_user); 
		
		if($userCount > 0 || $userCount1 > 0)
		{

			//echo do_shortcode('[pmpro_account]');
			//echo "hi";

		}
		else
		{
			echo "<div id='body' style='height:100%;'><div align='center' style='font-size:18px;margin:20%'>No Subscriptions. <a  style='font-size:18px;color:blue !important' href='../subscription/'>Subscribe Here</a> </div></div>";
get_footer();
		exit;
		}
        
    }
}

global $pmpro_levels;
$pmpro_levels = pmpro_getAllLevels();

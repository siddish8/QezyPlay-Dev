<?php
/*
	Shortcode to show membership account information
*/

function pmpro_shortcode_account($atts, $content=null, $code=""){
	
	global $wpdb, $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user, $levels;
	
	// $atts    ::= array of attributes
	// $content ::= text within enclosing form of shortcode element
	// $code    ::= the shortcode found, when == callback name
	// examples: [pmpro_account] [pmpro_account sections="membership,profile"/]

	extract(shortcode_atts(array(
		'section' => '',
		'sections' => 'membership,profile,invoices,links,previoussubscriptions,subscriptionsbyagent'		
	), $atts));
	
	//did they use 'section' instead of 'sections'?
	if(!empty($section))
		$sections = $section;
	
	//turn into an array
	$sections = explode(',', $sections);		
	
	ob_start();
	?>

	<div id="pmpro_account">	

		<?php
		//if a member is logged in, show them some info here (1. past invoices. 2. billing information 	with button to update.)

		global $current_user;
		global $wpdb;
		get_currentuserinfo();
		$this_user = get_current_user_id();
		//$userCount = $wpdb->get_var("SELECT count(user_id) as count FROM wp_pmpro_memberships_users where user_id = ".$this_user.""); 
		$expired = $wpdb->get_var("SELECT  count(user_id) as count FROM wp_pmpro_memberships_users where user_id = ".$this_user." and status='expired' order by id desc limit 1"); 
		$curr_plan_status=$wpdb->get_var("SELECT status FROM wp_pmpro_memberships_users where user_id = ".$this_user." order by id desc limit 1");
		$subAgent = $wpdb->get_var("SELECT plan_id FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$this_user. " ORDER BY id DESC LIMIT 1
");
$enddateAgent = $wpdb->get_var("SELECT subscription_end_on FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$this_user. " ORDER BY id DESC LIMIT 1");
echo "<script>console.log('Agent-sub plan:".$subAgent."')</script>";
echo "<script>console.log('endDateAgent:".$enddateAgent."')</script>";

$dateNow = new DateTime("now");
$dateNow=$dateNow->format("Y-m-d");
$dateNow = new DateTime($dateNow);

$enddateAgent=new DateTime($enddateAgent);
$enddateAgent=$enddateAgent->format("Y-m-d");
$enddateAgent = new DateTime($enddateAgent);

$AG_validity=($enddateAgent >= $dateNow)?1:0;


echo "<script>console.log('Validity Agent:".$AG_validity."');</script>";

if($curr_plan_status!='active' and $AG_validity==1)
{
	echo "<script>console.log('Plan Status:".$curr_plan_status."')</script>";
	echo "<style>#pmpro_account-membership{display:none}</style>";
}

		if( (pmpro_hasMembershipLevel()) or $expired ){	
			$ssorder = new MemberOrder();
			$ssorder->getLastMemberOrder();			
			//do_action('sub_sup');
			do_action('daily_update_chk');
		?>	
		
		<style>@media (min-width:769px){#pmpro_account-membership > table, #pmpro_account-invoices > table{max-width:50%;margin:0 auto;}}</style>
		<?php if(in_array('membership', $sections) || in_array('memberships', $sections)) { ?>
		<div id="pmpro_account-membership" class="pmpro_box">
				
			<h2 style="text-align:center"><?php _e("My Active Subscriptions", "pmpro");?></h2>
			<?php																						   	
			if($expired && ($curr_plan_status!='active') ){
				echo "<div align='center' style='font-size:18px;'>No active Subscription. <a  style='font-size:18px;color:blue !important' href='../subscription/'>Subscribe Now</a> </div>"; 	}else{ ?>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<thead>
					<?php 
					$enddate= $wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$current_user->ID." and status='active' ");
					if($enddate != "0000-00-00 00:00:00"){
						$cancelled=true;
					}else{
						$cancelled=false;
					}
					?>
						<tr>
							<?php if(!$cancelled) { ?> <th><?php _e("Plan", "pmpro");?></th>
							<th><?php _e("Billing", "pmpro"); ?></th>
                                                        <th><?php _e("Plan starts on", "pmpro"); ?></th> 								<?php } ?>
                                                        <!--  <th><?php _e("Next payment date", "pmpro"); ?></th> -->
							<?php if($cancelled) { ?>
								 
							<th><?php _e("Free-trial or days left from previous subscription \n", "pmpro"); ?></th>  
							<th><?php _e("Expires on", "pmpro"); ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php
					//TODO: v2.0 will loop through levels here
					$level = $current_user->membership_level;
					?>
						<tr><?php  $old_plan=$wpdb->get_var("select plan_name from pmpro_dates_chk1 where user_id=".$current_user->ID." order by id desc limit 1"); ?>
						<?php
						if(!$cancelled) { ?> 
							<td class="pmpro_account-membership-levelname"> 
							<?php echo $current_user->membership_level->name?> 
							<?php } else { ?>
							<td class="pmpro_account-membership-levelname">
								<?php
								if($enddate != "0000-00-00 00:00:00"){
								$today=new DateTime("now");
								$today=$today->format("Y-m-d");
								$today=new DateTime($today);
								$enddate=new DateTime($enddate);
								$temp = date_diff($today,$enddate);
								$delay=$temp->format('%R%a');
								if($delay < 0){$delay=0;}
								else{$delay=$temp->format('%a');}

								$delay_chk=$delay;
								$enddate=$enddate->format("Y-m-d");
								}else{
									$delay_chk=$wpdb->get_var("select delay from pmpro_dates_chk1 where user_id=".$current_user->ID." order by id desc limit 1");
								}
								?>
								<?php echo $delay_chk; /*echo get_option('pmpro_sub_support_delay_' . $current_user->ID);*/
								echo " days"; echo "<br /> (Prev plan-" ; echo $old_plan; echo ")"; ?> <?php } ?>

								<div class="pmpro_actionlinks">
									<?php do_action("pmpro_member_action_links_before"); ?>
								
									<?php if( $current_user->membership_level->allow_signups && pmpro_isLevelExpiringSoon( $current_user->membership_level) ) { ?>
									<?php if(!$cancelled) { ?>	<a href="<?php echo pmpro_url("checkout", "?level=" . $current_user->membership_level->id, "https")?>"><?php _e("Renew Plan", "pmpro");?></a> <?php } ?>
									<?php } ?>

									<?php if((isset($ssorder->status) && $ssorder->status == "success") && (isset($ssorder->gateway) && in_array($ssorder->gateway, array("authorizenet", "paypal", "stripe", "braintree", "payflow", "cybersource")))) { ?>
										<a href="<?php echo pmpro_url("billing", "", "https")?>"><?php _e("Update Billing Info", "pmpro"); ?></a>
									<?php } ?>
								
									<?php 
										//To do: Only show CHANGE link if this level is in a group that has upgrade/downgrade rules
										if(count($pmpro_levels) > 1 && !defined("PMPRO_DEFAULT_LEVEL")) { ?>
										<?php
											 if($cancelled==false) { ?>
										<a href="<?php echo pmpro_url("levels")?>"><?php _e("Change Plan", "pmpro");?></a>
										<?php } else { ?>
											<a href="<?php echo pmpro_url("levels")?>"><?php _e("Subscribe to a Plan", "pmpro");?></a>
										<?php } ?>
									<?php } ?>

									<?php
									if($cancelled==false) { ?>
									<a href="<?php echo site_url()."/subscription-cancel?level=" . $current_user->membership_level->id ?>"><?php _e("Cancel", "pmpro");?></a> <?php } ?>


									<?php do_action("pmpro_member_action_links_after"); ?>
								</div> <!-- end pmpro_actionlinks -->
							</td>

							<?php if(!$cancelled) { ?>
							<td class="pmpro_account-membership-levelfee">
								<p><?php echo pmpro_getLevelCost($level, true, true);?></p>
							</td> <?php } ?>

							<?php 
							global $wpdb;
							//$res=$wpdb->get_results("select * from sub_support where user_id=".$current_user->ID." order by id desc limit 1");
						 	$res=$wpdb->get_results("select * from pmpro_dates_chk1 where user_id=".$current_user->ID." order by id desc limit 1");
							foreach ($res as $sub){
								// $startdate=$sub->start_date;
								$startdate=$sub->startdate;
								// $planstartdate=$sub->plan_start_date;
								$planstartdate=$sub->plan_startdate;
								//   $nextpaydate=$sub->next_pay_date;
								$nextpaydate=$sub->next_paydate;
								$delay=$sub->delay;
							}
							$today=new DateTime("now");
							$today=$today->format("Y-m-d");
							$today=new DateTime($today);
                             $planstartdate=new DateTime($planstartdate);
							$nextpaydate=new DateTime($nextpaydate);

                             ?>
							<?php if(!$cancelled) { ?>
							<td class="pmpro_account-membership-start">
								 <p><?php echo $planstartdate->format('d-m-Y');echo "<br />"; if($today < $planstartdate) {/*echo "(Days left in trial/carry: ".get_option('pmpro_sub_support_delay_' . $current_user->ID).")";*/ echo "(Days left in trial/carry:".$delay.")";}else {echo "Plan Started";}?></p> <?php } ?>
  							</td>
							<!-- <td class="pmpro_account-membership-next-paydate">
								 <p><?php echo $nextpaydate->format('d-m-Y');?></p> 
  							</td> -->

							<?php if($cancelled) { ?>
							<td class="pmpro_account-membership-expiration">
 							<?php 
 								$enddate=new DateTime($enddate);
								echo $enddate->format('d-m-Y');
								/* if($current_user->membership_level->enddate) 
 									//echo date(get_option('date_format'), $current_user->membership_level->enddate);
									
 								else
									 echo "---"; */
 							?>
 							</td>
							<?php } ?>
						</tr>
 					</tbody>
				</table>
				<?php } ?>
 			
				<?php 
																						   //Todo: If there are multiple levels defined that aren't all in the same group defined as upgrades/downgrades ?>
				<!-- <div class="pmpro_actionlinks">
					<a href="<?php echo pmpro_url("levels")?>"><?php _e("View all Membership Options", "pmpro");?></a>
				</div> -->

			</div> <!-- end pmprosudo apt-get install notepadqq_account-membership -->
			<?php } ?>
		
		
			<?php if(in_array('links', $sections) && (has_filter('pmpro_member_links_top') || has_filter('pmpro_member_links_bottom'))) { ?>
			<div id="pmpro_account-links" class="pmpro_box">
				<h3><?php _e("Member Links", "pmpro");?></h3>
				<ul>
					<?php 
						do_action("pmpro_member_links_top");
					?>

					<?php 
						do_action("pmpro_member_links_bottom");
					?>
				</ul>
			</div> <!-- end pmpro_account-links -->		
			<?php } ?>
	
		<?php } ?>

		<?php

		$previousSubscriptions = $wpdb->get_results("SELECT mu.*,ml.name FROM $wpdb->pmpro_memberships_users as mu JOIN $wpdb->pmpro_membership_levels as ml ON ml.id = mu.membership_id WHERE mu.user_id = ".$current_user->ID." and mu.status in ('inactive','expired','changed') ORDER BY id DESC");	


		if((in_array('previoussubscriptions', $sections) && !empty($previousSubscriptions))) { ?>		
		<div id="pmpro_account-invoices" class="pmpro_box">
			<h2 style="text-align:center"><?php _e("Previous Subscriptions", "pmpro");?></h2>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<th><?php _e("Plan", "pmpro"); ?></th>
						<th><?php _e("Billing", "pmpro"); ?></th>
						<!-- <th><?php _e("Started", "pmpro"); ?></th>
						 <th><?php _e("Ended", "pmpro"); ?></th> -->
						<th><?php _e("Profile Started", "pmpro"); ?></th>
						<th><?php _e("Cancelled / Expired", "pmpro"); ?></th>
						<th><?php _e("Plan Usage", "pmpro"); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php 

				foreach($previousSubscriptions as $subscription){ 		

					$startdate=$subscription->startdate;

					//$plan_start=$wpdb->get_var("select plan_start_date from sub_support where start_date='".$startdate."'");
						$plan_start=$wpdb->get_var("select plan_startdate from pmpro_dates_chk1 where startdate='".$startdate."'");
					$plan_startDate=new DateTime($plan_start);

								$enddate=$subscription->enddate;

								$startdate=new DateTime($startdate);
					$enddate=new DateTime($enddate);										

					?>
					<tr>							
						<td><?php echo $subscription->name; ?></td>
						<td><?php echo "$".$subscription->billing_amount." USD every ".$subscription->cycle_number." ".$subscription->cycle_period."s"; ?></td>
						<td><?php echo $startdate->format('d-m-Y'); ?></td>
						<td><?php echo $enddate->format('d-m-Y'); ?></td>
						<td><?php if($plan_startDate < $enddate) {echo "Used";} else {echo "Not Activated";}?></td>
					</tr>
				<?php }	?>
				</tbody>
			</table>									
		</div> <!-- end pmpro_account-invoices -->
		<?php } ?>
		
		<?php
		$subscriptionsbyagents = $wpdb->get_results("SELECT asci.*,ml.name,ml.billing_amount FROM agent_vs_subscription_credit_info as asci JOIN $wpdb->pmpro_membership_levels as ml ON ml.id = asci.plan_id WHERE asci.subscriber_id = ".$current_user->ID." ORDER BY id DESC");	

		if((in_array('subscriptionsbyagent', $sections) && !empty($subscriptionsbyagents))) { ?>		
		<div id="pmpro_account-invoices" class="pmpro_box">
			<h2 style="text-align:center"><?php _e("Subscriptions by Agent", "pmpro");?></h2>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<th><?php _e("Agent", "pmpro"); ?></th>
						<th><?php _e("Plan", "pmpro"); ?></th>						
						<th><?php _e("Amount", "pmpro"); ?></th>	
						<th><?php _e("Start date", "pmpro"); ?></th>
						<th><?php _e("End date", "pmpro"); ?></th>
						<th><?php _e("Plan Status", "pmpro"); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php 

				foreach($subscriptionsbyagents as $subscription){ 		

					$startdate=$subscription->subscription_start_from;
					$enddate=$subscription->subscription_end_on;					
					
					$agentname=$wpdb->get_var("select agentname from agent_info where id =".$subscription->agent_id."");
									

					$startdate=new DateTime($startdate);
					$enddate=new DateTime($enddate);	
					
					$dateNow = new DateTime("now");
					$dateNow=$dateNow->format("Y-m-d");
					$dateNow = new DateTime($dateNow);



					$Agent_validity=($enddate >= $dateNow)?1:0;	
					if($Agent_validity==1)
					{
						$status="Active";
					}
					else
					{
						$status="Expired";
					}

					?>
					<tr>							
						<td><?php echo $agentname; ?></td>
						<td><?php echo $subscription->name; ?></td>
						<td><?php echo "$".$subscription->billing_amount." USD"; ?></td>
						<td><?php echo $startdate->format('d-m-Y'); ?></td>
						<td><?php echo $enddate->format('d-m-Y'); ?></td>
						<td><?php echo $status; ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>									
		</div> <!-- end pmpro_account-invoices -->
		<?php } ?>
		

	</div> <!-- end pmpro_account -->		
	
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

add_shortcode('pmpro_account', 'pmpro_shortcode_account');

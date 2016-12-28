<?php 
	global $wpdb, $current_user, $pmpro_invoice, $pmpro_msg, $pmpro_msgt;
	
	get_currentuserinfo();
	$user_id = get_current_user_id();
	$user_name = $current_user->user_login;


	if ((strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false))
	{
	$ios=1;
	}

	if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.ideabytes.qezyplay.qezyplay_new")
	{
	$android=1;
	}

	if ((strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false) or ($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.ideabytes.qezyplay.qezyplay_new") or $_SESSION['app']==1) {	
	echo "<style>header, #bottom-nav_1, footer, .blog-heading {display:none;}</style>";
	$player_url="intent:#Intent;action=com.ideabytes.qezyplay.qezyplay_new;end";
	$myaccount_url="intent:#Intent;action=com.ideabytes.qezyplay.qezyplay_new;end";
	$app=1;
   	}
	else{
 	$player_url="../#bangla";
	$myaccount_url=pmpro_url("account"); 
	$app=0;}
	
	if($pmpro_msg)
	{
	?>
		<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
	<?php
	}
	
	if(empty($current_user->membership_level))
		$confirmation_message = "<p style='font-size: 15px;
    font-weight: bold;'>" . __('Your payment has been submitted. Your subscription will be activated shortly.', 'pmpro') . "</p>";
	else
	/*  Your %s subscription is now active , $current_user->membership_level->name */	
	
	if($app==0)
	{

	$confirmation_message = "<p style='font-size: 15px;
    font-weight: bold;'>" . sprintf(__('Thank you for your subscription to %s. Your subscription is now active.', 'pmpro'), get_bloginfo("name")) . "</p>
<script>
swal({
  title: 'Please share our site on Facebook!',
  text: '<button style=\"height: 8px;    width: 10px;    padding: 6px 0px;    position: absolute;    top: -54px;    right: -19px;    background: none !important;    background-color: black !important;    border-radius: 0px !important;    text-align: center;    font-size: 14px;    border: none !important;\" type=\'button\'>x</button>',
  type: 'warning',
  html: 'true',
  showCancelButton: false,
  confirmButtonColor: '#DD6B55',
  confirmButtonText: 'Share',
  closeOnConfirm: true
},function(){ window.open('https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.facebook.com%2Fqezyplay%2F&amp;src=sdkpreparse','_blank',config='height=300,width=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, directories=no, status=no');
});
</script>";	
	}
	else
	{
		$confirmation_message =  "<p style='font-size: 15px;
    font-weight: bold;'>" . sprintf(__('Thank you for your subscription to %s. Your subscription is now active.', 'pmpro'), get_bloginfo("name")) . "</p>
<script>
swal({
  title: 'Thank You for Subscription',text: Please share our site on Facebook!});
</script>";	
	}	
	

	//confirmation message for this level
	$level_message = $wpdb->get_var("SELECT l.confirmation FROM $wpdb->pmpro_membership_levels l LEFT JOIN $wpdb->pmpro_memberships_users mu ON l.id = mu.membership_id WHERE mu.status = 'active' AND mu.user_id = '" . $current_user->ID . "' LIMIT 1");
	if(!empty($level_message))
		$confirmation_message .= "\n" . stripslashes($level_message) . "\n";
?>	

<?php if(!empty($pmpro_invoice) && !empty($pmpro_invoice->id)) { ?>		
	
	<?php
		$pmpro_invoice->getUser();
		$pmpro_invoice->getMembershipLevel();			
				
		$confirmation_message .= "<p font-size: 16px;
    font-weight: bold;>" . sprintf(__('Below are details about your subscription account and a receipt for your subscription invoice. A welcome email with a copy of your subscription invoice has been sent to %s.', 'pmpro'), $pmpro_invoice->user->user_email) . "</p>";
		
		//check instructions		
		if($pmpro_invoice->gateway == "check" && !pmpro_isLevelFree($pmpro_invoice->membership_level))
			$confirmation_message .= wpautop(pmpro_getOption("instructions"));
		
		$confirmation_message = apply_filters("pmpro_confirmation_message", $confirmation_message, $pmpro_invoice);				
		
		echo apply_filters("the_content", $confirmation_message);		
	?>
	
	<?php $invoice_date = $wpdb->get_var("SELECT startdate FROM $wpdb->pmpro_memberships_users where status = 'active' AND user_id = '" . $current_user->ID . "' LIMIT 1");

		$invoice_date=new DateTime($invoice_date);
		$invoice_date=$invoice_date->format("d-m-Y");

 ?>
	<h3 style="color:black !important;">
		<?php /* printf(__('Invoice #%s on %s', 'pmpro'), $pmpro_invoice->code, date_i18n(get_option('date_format'), $pmpro_invoice->timestamp)); */?>	
		<?php  printf(__('Invoice #%s on %s', 'pmpro'), $pmpro_invoice->code, $invoice_date); ?>	
	</h3>
	<!-- <a class="pmpro_a-print" href="javascript:window.print()"><?php _e('Print', 'pmpro');?></a> -->
	<ul>
		<?php do_action("pmpro_invoice_bullets_top", $pmpro_invoice); ?>
		<li><strong><?php _e('Account', 'pmpro');?>:</strong> <?php echo $current_user->user_login?> (<?php echo $current_user->user_email?>)</li>
		<!-- <li><strong><?php _e('Subscription Plan', 'pmpro');?>:</strong> <?php echo $current_user->membership_level->name?></li> -->
	<li><strong><?php _e('Subscription Plan', 'pmpro');?>:</strong> <?php echo $pmpro_invoice->membership_level->name?></li>
		<?php if($current_user->membership_level->enddate) { ?>
			<li><strong><?php _e('Subscription Expires', 'pmpro');?>:</strong> <?php echo date_i18n(get_option('date_format'), $current_user->membership_level->enddate)?></li>
		<?php } ?>
		<?php if($pmpro_invoice->getDiscountCode()) { ?>
			<li><strong><?php _e('Discount Code', 'pmpro');?>:</strong> <?php echo $pmpro_invoice->discount_code->code?></li>
		<?php } ?>
		<?php do_action("pmpro_invoice_bullets_bottom", $pmpro_invoice); ?>
	</ul>
	
<style>@media (min-width:769px){#pmpro_confirmation_table,#nav-below{max-width:50%;margin:0 auto;}}

</style>
	
	<table id="pmpro_confirmation_table" width="100%" class="pmpro_invoice" cellpadding="0" cellspacing="0" border="0" style="font-size:15px;">
		<thead>
			<tr>
				<?php if(!empty($pmpro_invoice->billing->name)) { ?>
				<th><?php _e('Billing Address', 'pmpro');?></th>
				<?php } ?>
				<!-- <th><?php _e('Payment Method', 'pmpro');?></th> -->
				<th><?php _e('Subscription Plan', 'pmpro');?></th>
				<th><?php _e('Total Billed', 'pmpro');?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php if(!empty($pmpro_invoice->billing->name)) { ?>
				<td>
					<?php echo $pmpro_invoice->billing->name?><br />
					<?php echo $pmpro_invoice->billing->street?><br />						
					<?php if($pmpro_invoice->billing->city && $pmpro_invoice->billing->state) { ?>
						<?php echo $pmpro_invoice->billing->city?>, <?php echo $pmpro_invoice->billing->state?> <?php echo $pmpro_invoice->billing->zip?> <?php echo $pmpro_invoice->billing->country?><br />												
					<?php } ?>
					<?php echo formatPhone($pmpro_invoice->billing->phone)?>
				</td>
				<?php } ?>
			<!--	<td>
					<?php if($pmpro_invoice->accountnumber) { ?>
						<?php echo $pmpro_invoice->cardtype?> <?php _e('ending in', 'pmpro');?> <?php echo last4($pmpro_invoice->accountnumber)?><br />
						<small><?php _e('Expiration', 'pmpro');?>: <?php echo $pmpro_invoice->expirationmonth?>/<?php echo $pmpro_invoice->expirationyear?></small>
					<?php } elseif($pmpro_invoice->payment_type) { ?>
						<?php echo $pmpro_invoice->payment_type?>
					<?php } ?>
				</td> -->
				<td><?php echo $pmpro_invoice->membership_level->name?></td>	
				<td><?php if($pmpro_invoice->total) echo pmpro_formatPrice($pmpro_invoice->total); else echo "---";?></td> 
				
			</tr>
		</tbody>
	</table>
<?php 
	} 
	else 
	{
		$confirmation_message .= "<p style='font-size:15px;'>" . sprintf(__('Below are details about your subscription account. A welcome email has been sent to %s.', 'pmpro'), $current_user->user_email) . "</p>";
		
		$confirmation_message = apply_filters("pmpro_confirmation_message", $confirmation_message, false);
		
		echo $confirmation_message;
	?>	
	<ul>
		<li><strong><?php _e('Account', 'pmpro');?>:</strong> <?php echo $current_user->display_name?> (<?php echo $current_user->user_email?>)</li>
		<li><strong><?php _e('Subscription Plan', 'pmpro');?>:</strong> <?php if(!empty($current_user->membership_level)) echo $current_user->membership_level->name; else _e("Pending", "pmpro");?></li>
	</ul>	
<?php 
	} 
?>  
<nav id="nav-below" class="navigation" role="navigation">
	<div class="nav-next align">
		<?php if(!empty($current_user->membership_level)) { ?>

<style>
blink, .blink {
  -webkit-animation: blink 1s step-end infinite;
  -moz-animation: blink 1s step-end infinite;
  -o-animation: blink 1s step-end infinite;
  animation: blink 1s step-end infinite;
}

@-webkit-keyframes blink {
  67% { opacity: 0 }
}

@-moz-keyframes blink {
  67% { opacity: 0 }
}

@-o-keyframes blink {
  67% { opacity: 0 }
}

@keyframes blink {
  67% { opacity: 0 }
}

</style>

<div align="center" style="margin:0 auto;background-color:yellowgreen" ><p>Enjoy Viewing LIVE channels <a style="font-size:18px" class="blink" onclick="return player_url()" href="#">Here</a> </p>	</div>

			<div align="center" style="margin:0 auto" ><span>View </span><a style="color: #4141a0 !important;" onclick="return myaccount_url()" href="#"><?php _e('Subscription Account', 'pmpro');?></a><span> for more details on your plan</span></div>
		<?php 

			//do_action('sub_sup'); //this is for handling profiles if success
			} else { ?>
			<?php _e('If your subscription is not activated within few minutes, refresh twice for 5 minutes and if there is no success message please contact the <a href="mailto:admin@qezyplay.com?Subject=Subscription Confirmation success issue%20again" target="_top">Admin</a>', 'pmpro');?>
		<?php } ?>
	</div>
</nav>
<script>

function player_url()
{

<?php if($app==1) { ?>

<?php if($android==1) { ?>

var x="boq";
Android.BanglaToast(x);
return;
<?php } ?>
<?php if($ios==1) { ?>

var x="boq";
var myAppName = 'myfakeappname';
var myActionType = 'myJavascriptActionType1';
var myActionParameters = {"redirect_url":"boq"}; // put extra info into a dict if you need it

// (separating the actionType from parameters makes it easier to parse in ObjC.)
var jsonString = (JSON.stringify(myActionParameters));
var escapedJsonParameters = escape(jsonString);
var url = myAppName + '://' + myActionType + "#" + escapedJsonParameters;
//alert(url);
document.location.href = url;

// window.open('ios:webToNativeCall');

return;
<?php } ?>


<?php } else { ?>

window.location.href="<?php echo $player_url ?>";
return;
<?php } ?>

}

function myaccount_url()
{

<?php if($app==1) { ?>

<?php if($android==1) { ?>

var x="acc";
Android.BanglaToast(x);
return;
<?php } ?>
<?php if($ios==1) { ?>


var x="acc";
var myAppName = 'myfakeappname';
var myActionType = 'myJavascriptActionType2';
var myActionParameters = {"redirect_url":"acc"}; // put extra info into a dict if you need it

// (separating the actionType from parameters makes it easier to parse in ObjC.)
var jsonString = (JSON.stringify(myActionParameters));
var escapedJsonParameters = escape(jsonString);
var url = myAppName + '://' + myActionType + "#" + escapedJsonParameters;
//alert(url);
document.location.href = url;
// window.location.href  = 'ios:webToNativeCall';

return;
<?php } ?>

<?php } else { ?>

window.location.href="<?php echo $myaccount_url ?>";
return;
<?php } ?>

}

</script>

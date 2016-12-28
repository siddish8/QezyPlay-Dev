<?php
 /*
    Plugin Name: Subscription Page
    Plugin URI: 
    Description: This gives the form and backend-processing of subscription(plans) page
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('sub-plans-page','sub_plans_page_fn');

function sub_plans_page_fn(){

if(isset($_POST['cc_submit']))
{
$cc=$_POST['cc'];
$app=$_POST['app'];

$msg=process_cc($cc,$app);
if($msg[0]=="1" or $msg[0]=="2" or $msg[0]=="3" or  $msg[0]=="5")
	$cc_err=1;


}

?>
<style>
#body * {    
    opacity: 1;}
button:hover, input[type='submit']:hover{color:#fff !important;}
</style>
<a name="cc_link" href="javascript:void(0)" ></a>
<div id="btn_desc_text" align="center" style="margin: 15px auto;">
If you have a Credit-Card/Debit-Card, click on <span style="color:darkgreen"><b>"Buy Now"</b></span> to subscribe the plan for yourself, click on <span style="color:darkgreen"><b>"Buy For a Friend"</b></span> to gift a subscription to friend <span style="color:red;"><b> OR </b></span> If you dont have a credit/debit-card, approach our agent by clicking on <span style="color:darkgreen"><b>"Buy from a Qezy Agent"</b></span>
</div>



<?php if(is_user_logged_in()) {
?>

<div id="agent_list" style="margin:0 auto;" align="center"><a href="<?php echo site_url('qezy-agents') ?>"><button id="agent_list_btn" type="submit" value="Buy from a Qezy Agent">Buy from a Qezy Agent</button></a></div>



<div id="coupon_div" style="margin:0 auto;" align="center"><a href="javascript:void(0)" onclick="return show_coupon_div()"><button id="coupon_code_btn" type="submit" value="">Use Your Coupon/Promo code</button></a></div>

<div align="center" id="cc_div" style="display:none;max-width:500px;margin:0 auto">
<form action="#cc_link" method='post'>
<span id="coupon_code_text">Enter your Coupon/Promo code:</span> <input onclick="return jQuery('#msg').html('');" type="text" id="cc" name="cc" /> <input type="hidden" id="app" name="app" value="0" /> <input type="submit" onclick="return chk_cc()" name="cc_submit" id="cc_submit" value="Submit" />

</form>
</div>
<div align="center" id="msg" style="margin:0 auto"><?php echo $msg[1] ?></div> 

<script>
function chk_cc()
{
var c=jQuery('#cc').val();
if(c=="")
{
	<?php if($_SESSION['app']==1) { ?>  
	
	jQuery('#msg').html('<span style="color:red">Please Enter Bonus code</span>');
	
	<?php } else { ?>
jQuery('#msg').html('<span style="color:red">Please Enter Coupon/Promo code</span>');
<?php } ?>
return false;
}



}

function show_coupon_div()
{
jQuery("#cc_div").show();
}
</script>

<?php 

	if($cc_err==1)
	{
	echo "<script>
	show_coupon_div()</script>";
	}
}
else {
?>
<div id="agent_list" style="margin:0 auto;" align="center"><a href="javascript:void(0)" hr="qezy-agents" onclick="return checkBtn(this.hr)"><button id="agent_list_btn" type="submit" value="Pay via QezyAgents">Buy from QezyAgents</button></a></div>
<div id="coupon_div" style="margin:0 auto;" align="center"><a href="javascript:void(0)" hr="subscription" onclick="return checkBtn(this.hr)"><button id="coupon_code_btn" type="submit" value="">Use Your Coupon/Promo code</button></a></div>
<script>

function checkBtn(id)
{

swal({
         title:' ', 
  text: 'Please Login / Register',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55", cancelButtonColor: "#DD6B55",  confirmButtonText: "Register",   cancelButtonText: "Login"
},function(isConfirm){

if(isConfirm) {
var values = {'action':'lrB', 'q':id};
jQuery.ajax({url: '<?php echo site_url() ?>/qp/uservalidation_check.php',
			type: 'post',
			data: values,
			success: function(response){  
										
			}
		});
window.location.href="../register";
}

else{
var values = {'action':'lrB', 'q':id};
jQuery.ajax({url:  '<?php echo site_url() ?>/qp/uservalidation_check.php',
			type: 'post',
			data: values,
			success: function(response){  
										
			}
		});


window.location.href="../login";

}

}); 
}

</script>
<?php 
	
	} 
?>
<?php if ((strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false) or ($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.ideabytes.qezyplay.qezyplay_new") or $_SESSION['app']==1) {  $app=1 ?>
		
		<script>jQuery('#btn_desc_text').html('If you have a Credit-Card/Debit-Card, click on <span style=\"color:darkgreen\"><b>\"Buy Now\"</b></span> to subscribe the plan for yourself <span style=\"color:red;\"><b> OR </b></span> If you have a "Bonus Code", click on <span style=\"color:darkgreen\"><b>\"Use Your Bonus Code\"</b> </span> and submit for Subscription');

		jQuery('#app').val("1");
		jQuery('#coupon_code_btn').html('Use Your Bonus Code');
		jQuery('#coupon_code_text').html('Enter your Bonus Code:');
	</script>

		<?php } else {
			$app=0;
		}?>
<?php
}

function process_cc($cpn,$app)
{
//Validate 1.Exist 2.Used
	global $current_user, $wpdb;
	
	get_currentuserinfo();
	$user_id = get_current_user_id();

	//$user_id=167;	

	$cc_exist=$wpdb->get_var("SELECT count(*) from buy_a_friend where coupon_code='".$cpn."'");
	$promo_cc_exist=$wpdb->get_var("SELECT count(*) from promo_codes where coupon_code='".$cpn."'");

	if($cc_exist==0 and $promo_cc_exist==0)
	{
	$msg[0]="1";
		if($app==1){

			$msg[1]="<script>swal('Invalid Bonus code')</script>";

		}else{
			$msg[1]="<script>swal('Invalid Coupon/Promo code')</script>";
		}
		
	return $msg;
	}
	elseif($cc_exist>0)
	{
	$cc_used=$wpdb->get_var("SELECT count(*) from buy_a_friend where status='Paid' and coupon_code='".$cpn."'");
	$promo_cc_max_used=$wpdb->get_var("SELECT count(*) from promo_codes where usage_count<max_count and coupon_code='".$cpn."'");
		if($cc_used==0 and $promo_cc_max_used==0)
		{
		$msg[0]="2";
		if($app==1){

			$msg[1]="<script>swal('Bonus code already used')</script>";

		}else{
			$msg[1]="<script>swal('Coupon Code already used')</script>";
		}
		
		return $msg;
		}
		else{
		//$sub_active=get_option("subscribed_".$user_id);
		$u_active=$wpdb->get_var("SELECT count(*) from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");
		$a_active=$wpdb->get_var("SELECT count(*) from agent_vs_subscription_credit_info where subscriber_id=".$user_id." and CURDATE()<date(subscription_end_on)");	

		if($u_active>0 or $a_active>0)
			$sub_active="true";

		


		$cpn_plan=$wpdb->get_var("SELECT plan_id from buy_a_friend where status='Paid' and coupon_code='".$cpn."'");
		$cpn_plan_name=$wpdb->get_var("SELECT name from wp_pmpro_membership_levels where id='".$cpn_plan."'");
		
			if($cpn_plan==4)
			{
				$got_freetrial=$wpdb->get_var("SELECT count(*) from wp_pmpro_memberships_users where membership_id=4 and user_id=".$user_id." ");
				if($got_freetrial>0){
					$msg[0]="3";
					$msg[1]="<script>swal('Oops! You have already used your free trail, you cannot use  free trail coupon code');
</script>";
					return $msg;
				}
			}	
			if($sub_active=="true")
				{
					//check from agent / in-free trial ; if yes process coupon and extend their subscription ;else error
					$agent_plan=$wpdb->get_var("SELECT plan_id from agent_vs_subscription_credit_info where subscriber_id=".$user_id." and CURDATE()<date(subscription_end_on)");

					$agent_enddate=$wpdb->get_var("SELECT subscription_end_on from agent_vs_subscription_credit_info where subscriber_id=".$user_id." and CURDATE()<date(subscription_end_on)");
					$in_free_trial_plan=$wpdb->get_var("SELECT membership_id from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");
					$in_free_trial_enddate=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");

					

					

					if($in_free_trial_plan>0 && $in_free_trial_enddate!="0000-00-00 00:00:00")
					{
						//echo "U";
						//$prev_exp_date=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' order by id desc limit 1");
							$prev_exp_date=$in_free_trial_enddate;
							$prev_exp_date=new DateTime($prev_exp_date);
							$prev_exp_date=$prev_exp_date->format('d-m-Y');

						do_action('process_cc_ext_sub',$cpn,$in_free_trial_enddate,$p="U");

						$curr_exp_date=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' order by id desc limit 1");
						$curr_exp_date=new DateTime($curr_exp_date);
						$curr_exp_date=$curr_exp_date->format('d-m-Y');

						$sub_stat="<p> Hurray! $cpn_plan_name  gift coupon code is activated</p> <p>Your Subscription is extended from $prev_exp_date to $curr_exp_date </p>";
						$msg[0]="4";
					$msg[1]='<script>swal({   title: "",   text: "'.$sub_stat.'",   type: "warning",  html:"true",  showCancelButton: false,   confirmButtonColor: "#DD6B55",   confirmButtonText: "OK",   closeOnConfirm: false }, function(){   window.location.href="subscription"; });</script>';
			
					return $msg;
					}
					elseif($agent_plan>0 && $agent_enddate!="")
					{

					//echo "A";
					$prev_exp_date=$agent_enddate;
					$prev_exp_date=new DateTime($prev_exp_date);
					$prev_exp_date=$prev_exp_date->format('d-m-Y');
						
					do_action('process_cc_ext_sub',$cpn,$agent_enddate,$p="A");

					$curr_exp_date=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' order by id desc limit 1");
					$curr_exp_date=new DateTime($curr_exp_date);
					$curr_exp_date=$curr_exp_date->format('d-m-Y');

					$sub_stat=" <p>Hurray! $cpn_plan_name  gift coupon code is activated</p><p> Your Subscription is extended from $prev_exp_date to $curr_exp_date </p>";
						$msg[0]="4";
					$msg[1]='<script>swal({   title: "",   text: "'.$sub_stat.'",   type: "warning", html:"true",  showCancelButton: false,   confirmButtonColor: "#DD6B55",   confirmButtonText: "OK",   closeOnConfirm: false }, function(){   window.location.href="subscription"; });</script>';
			
					return $msg;
					}
					else
					{

					$msg[0]="5";
					$msg[1]="<script>swal('Oops! You are Already in subscription. Please share this Gift coupon code to your friend/family')</script>";
					return $msg; 
					}
				}
			else
			{
			//do_action('process_cc',$cpn);
					//$msg[0]="6";
					//$msg[1]="<script>swal('Hurray! Subscription Activated')</script>";
					//return $msg;

			do_action('process_cc',$cpn);

			global $current_user;
			global $wpdb;
			$current_user = wp_get_current_user(); $id=$current_user->ID; 

			$plan_id=$wpdb->get_var("SELECT membership_id from wp_pmpro_memberships_users where user_id=$id and status='active' order by id desc limit 1");
			$coupon=$wpdb->get_var("SELECT code_id from wp_pmpro_memberships_users where user_id=$id and status='active' order by id desc limit 1");
						
		$plan_name=$wpdb->get_var("SELECT name from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_no=$wpdb->get_var("SELECT cycle_number from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_prd=$wpdb->get_var("SELECT cycle_period from wp_pmpro_membership_levels where id=$plan_id");

		
		$sub_stat="";

		if($coupon!="")
			//$sub_stat.="With Coupon: $coupon ,"; for $plan_cyc_no $plan_cyc_prd(s)

		if($plan_id==4)
			$sub_stat.="Your Subscription Free Trial activated for 7-days.";
		else
			$sub_stat.="Hurray! $plan_name subscription plan activated .";

					$msg[0]="6";
					$msg[1]='<script>swal({   title: "",   text: "'.$sub_stat.'",   type: "warning",   showCancelButton: false,   confirmButtonColor: "#DD6B55",   confirmButtonText: "OK",   closeOnConfirm: false }, function(){   window.location.href="subscription"; });</script>';
			
					return $msg;
			}//not subscribed else
		}//not used cpn else

	}//valid-normal cpn else
	elseif($promo_cc_exist>0)
	{
		$promo_cc_max_used=$wpdb->get_var("SELECT count(*) from promo_codes where usage_count<max_count and coupon_code='".$cpn."'");
		if($promo_cc_max_used==0)
		{
		$msg[0]="2";
			if($app==1){

				$msg[1]="<script>swal('Bonus code expired')</script>";

			}else{
				$msg[1]="<script>swal('Promo Code expired')</script>";
			}
		
		return $msg;
		}
		else{


		echo $promo_used_user=get_option("promo_used_".$user_id);
		if($promo_used_user=="true")
		{

			$msg[0]="5";
					if($app==1){

					$msg[1]="<script>swal('Oops! You have already used this bonus code. Please share this code to your friends/family')</script>";

					}else{
						$msg[1]="<script>swal('Oops! You have already used this promo code. Please share this code to your friends/family')</script>";
					}
					
					return $msg; 

		}
		
		$u_active=$wpdb->get_var("SELECT count(*) from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");
		$a_active=$wpdb->get_var("SELECT count(*) from agent_vs_subscription_credit_info where subscriber_id=".$user_id." and CURDATE()<date(subscription_end_on)");	

		if($u_active>0 or $a_active>0)
			$sub_active="true";

		


		$cpn_plan=$wpdb->get_var("SELECT plan_id from promo_codes where usage_count<max_count and coupon_code='".$cpn."'");
		$cpn_plan_name=$wpdb->get_var("SELECT name from wp_pmpro_membership_levels where id='".$cpn_plan."'");
		
			/*if($cpn_plan==4)
			{
				$got_freetrial=$wpdb->get_var("SELECT count(*) from wp_pmpro_memberships_users where membership_id=4 and user_id=".$user_id." ");
				if($got_freetrial>0){
					$msg[0]="3";
					$msg[1]="<script>swal('Oops! you have already used your free trail, you cannot use  free trail coupon code');
</script>";
					return $msg;
				}
			}*/	
			if($sub_active=="true")
				{
					//check from agent / in-free trial ; if yes process coupon and extend their subscription ;else error
					$agent_plan=$wpdb->get_var("SELECT plan_id from agent_vs_subscription_credit_info where subscriber_id=".$user_id." and CURDATE()<date(subscription_end_on)");

					$agent_enddate=$wpdb->get_var("SELECT subscription_end_on from agent_vs_subscription_credit_info where subscriber_id=".$user_id." and CURDATE()<date(subscription_end_on)");
					$in_free_trial_plan=$wpdb->get_var("SELECT membership_id from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");
					$in_free_trial_enddate=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");

					

					

					if($in_free_trial_plan>0 && $in_free_trial_enddate!="0000-00-00 00:00:00")
					{
						//echo "U";
						//$prev_exp_date=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' order by id desc limit 1");
							$prev_exp_date=$in_free_trial_enddate;
							$prev_exp_date=new DateTime($prev_exp_date);
							$prev_exp_date=$prev_exp_date->format('d-m-Y');

						do_action('process_cc_ext_sub',$cpn,$in_free_trial_enddate,$p="U");

						$curr_exp_date=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' order by id desc limit 1");
						$curr_exp_date=new DateTime($curr_exp_date);
						$curr_exp_date=$curr_exp_date->format('d-m-Y');

						$agent=$wpdb->get_var("SELECT agent from pmpro_dates_chk1 where user_id=$id order by id desc limit 1");
		if (strpos($agent, 'free-trial/QZ') == false) {
	
		if (strpos($agent, 'free-trial/-') == false) {

						if($app==1){

						 $promo= 'with Bonus code';

					}else{
						 $promo= 'with Promo code';
					}
   				 //$promo= 'with Promo code';
				 $promo1= 'You can share this code with your Friends/Family';
				}
				}

					if($app==1){

						 $sub_stat=" <p>Hurray! $cpn_plan_name  Bonus code is activated</p><p> Your Subscription is extended from $prev_exp_date to $curr_exp_date </p><p>$promo1</p>";

					}else{
						 $sub_stat=" <p>Hurray! $cpn_plan_name  Promo code is activated</p><p> Your Subscription is extended from $prev_exp_date to $curr_exp_date </p><p>$promo1</p>";
					}
					
						$msg[0]="4";
					$msg[1]='<script>swal({   title: "",   text: "'.$sub_stat.'",   type: "warning",  html:"true",  showCancelButton: false,   confirmButtonColor: "#DD6B55",   confirmButtonText: "OK",   closeOnConfirm: false }, function(){   window.location.href="subscription"; });</script>';
			
					return $msg;
					}
					elseif($agent_plan>0 && $agent_enddate!="")
					{

					//echo "A";
					$prev_exp_date=$agent_enddate;
					$prev_exp_date=new DateTime($prev_exp_date);
					$prev_exp_date=$prev_exp_date->format('d-m-Y');
						
					do_action('process_cc_ext_sub',$cpn,$agent_enddate,$p="A");

					$curr_exp_date=$wpdb->get_var("SELECT enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' order by id desc limit 1");
					$curr_exp_date=new DateTime($curr_exp_date);
					$curr_exp_date=$curr_exp_date->format('d-m-Y');

					$agent=$wpdb->get_var("SELECT agent from pmpro_dates_chk1 where user_id=$id order by id desc limit 1");
		if (strpos($agent, 'free-trial/QZ') == false) {
	
		if (strpos($agent, 'free-trial/-') == false) {
   				 if($app==1){

						 $promo= 'with Bonus code';

					}else{
						 $promo= 'with Promo code';
					}
				 $promo1= 'You can share this code with your Friends/Family';
				}
				}

					if($app==1){

						 $sub_stat=" <p>Hurray! $cpn_plan_name  Bonus code is activated</p><p> Your Subscription is extended from $prev_exp_date to $curr_exp_date </p><p>$promo1</p>";

					}else{
						 $sub_stat=" <p>Hurray! $cpn_plan_name  Promo code is activated</p><p> Your Subscription is extended from $prev_exp_date to $curr_exp_date </p><p>$promo1</p>";
					}
					
						$msg[0]="4";
					$msg[1]='<script>swal({   title: "",   text: "'.$sub_stat.'",   type: "warning", html:"true",  showCancelButton: false,   confirmButtonColor: "#DD6B55",   confirmButtonText: "OK",   closeOnConfirm: false }, function(){   window.location.href="subscription"; });</script>';
			
					return $msg;
					}
					else
					{

					$msg[0]="5";
						if($app==1){

						 $msg[1]="<script>swal('Oops! You are Already in subscription. Please share this Bonus code to your friends/family')</script>";

					}else{
						 $msg[1]="<script>swal('Oops! You are Already in subscription. Please share this Promo code to your friends/family')</script>";
					}

					
					return $msg; 
					}
				}
			else
			{
			//do_action('process_cc',$cpn);
					//$msg[0]="6";
					//$msg[1]="<script>swal('Hurray! Subscription Activated')</script>";
					//return $msg;

			do_action('process_cc',$cpn);

			global $current_user;
			global $wpdb;
			$current_user = wp_get_current_user(); $id=$current_user->ID; 

			$plan_id=$wpdb->get_var("SELECT membership_id from wp_pmpro_memberships_users where user_id=$id and status='active' order by id desc limit 1");
			$coupon=$wpdb->get_var("SELECT code_id from wp_pmpro_memberships_users where user_id=$id and status='active' order by id desc limit 1");
						
		$plan_name=$wpdb->get_var("SELECT name from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_no=$wpdb->get_var("SELECT cycle_number from wp_pmpro_membership_levels where id=$plan_id");
		$plan_cyc_prd=$wpdb->get_var("SELECT cycle_period from wp_pmpro_membership_levels where id=$plan_id");

		$delay=$wpdb->get_var("SELECT delay from pmpro_dates_chk1 where user_id=$id order by id desc limit 1");
		$agent=$wpdb->get_var("SELECT agent from pmpro_dates_chk1 where user_id=$id order by id desc limit 1");
		if (strpos($agent, 'free-trial/QZ') == false) {
	
		if (strpos($agent, 'free-trial/-') == false) {
   				if($app==1){

						 $promo= 'with Bonus code';

					}else{
						 $promo= 'with Promo code';
					}
				 $promo1= 'You can share this code with your Friends/Family';
				}
				}

		$sub_stat="";

		if($coupon!="")
			//$sub_stat.="With Coupon: $coupon ,"; for $plan_cyc_no $plan_cyc_prd(s)

		if($plan_id==4)
			$sub_stat.="Your Subscription Free Trial activated for $delay days $promo . $promo1";
		else
			$sub_stat.="Hurray! $plan_name subscription plan activated .$promo1";

					$msg[0]="6";
					$msg[1]='<script>swal({   title: "",   text: "'.$sub_stat.'",   type: "warning",   showCancelButton: false,   confirmButtonColor: "#DD6B55",   confirmButtonText: "OK",   closeOnConfirm: false }, function(){   window.location.href="subscription"; });</script>';
			
					return $msg;
			}//not subscribed else
		}//not used cpn else

	}//valid promo-cpn else

}//function
?>

<?php

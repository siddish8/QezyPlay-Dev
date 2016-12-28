<?php 
global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels = pmpro_getAllLevels(false, true);
$pmpro_level_order = pmpro_getOption('level_order');

echo "<style>.sweet-alert button.cancel {
    background-color: #607D8B !important;
}
.pmpro_btn.frnd:hover {
    background-color: #f9c73d !important;
}
</style>";
get_currentuserinfo();
$this_user = get_current_user_id();
$userCount = $wpdb->get_var("SELECT count(user_id) as count FROM wp_pmpro_memberships_users where user_id = ".$this_user." and user_id<>0"); 
$userAgentCount = $wpdb->get_var("SELECT count(subscriber_id) as count FROM agent_vs_subscription_credit_info where subscriber_id = ".$this_user." and subscriber_id<>0"); 

$delay= $wpdb->get_var("SELECT delay from pmpro_dates_chk1 where user_id=".$this_user." order by id desc");
$promo=	$wpdb->get_var("SELECT code_id from wp_pmpro_memberships_users where user_id=".$this_user." order by id desc");
$promo_usage=get_option('promo_used_'.$this_user);

if( ($promo[0]!='Q' and $promo[0]!='0' and $promo[0]!='') or $promo_usage=="true")
{
$promo_text='<br><sup>*Promo Code Used</sup>';
}
else
{
$promo_text='';
}



$userTotalCount = (int)$userCount + (int)$userAgentCount;

if(!empty($pmpro_level_order))
{
	$order = explode(',',$pmpro_level_order);

	//reorder array
	$reordered_levels = array();
	foreach($order as $level_id) {
		foreach($pmpro_levels as $key=>$level) {
			if($level_id == $level->id)
				$reordered_levels[] = $pmpro_levels[$key];
		}
	}

	$pmpro_levels = $reordered_levels;
}

$pmpro_levels = apply_filters("pmpro_levels_array", $pmpro_levels);

$subscriptionbyAgentID = $wpdb->get_var("SELECT plan_id FROM agent_vs_subscription_credit_info where subscriber_id = ".$this_user." AND ((CURRENT_DATE() >= DATE(subscription_start_from) AND CURRENT_DATE() <= DATE(subscription_end_on)) OR (CURRENT_DATE() >= DATE(credited_datetime) AND CURRENT_DATE() <= DATE(subscription_end_on)))"); 

if($pmpro_msg){
?>
<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
<?php
}
?>
	<div id="pmpro_levels_table" class="pmpro_checkout" style="display:inline-flex;">
	<!-- <thead>
	  <tc>
		<th><?php _e('Plan', 'pmpro');?></th>
		<th><?php _e('Amount', 'pmpro');?></th>	
		<th>&nbsp;</th>
	  </tc>
	</thead> -->
	<!-- <tbody> -->
	<?php	
	$count = 0;
	foreach($pmpro_levels as $level){

	  if(isset($current_user->membership_level->ID)){

		  $current_level = ($current_user->membership_level->ID == $level->id);
		  $levelname = $current_user->membership_level->name;
		  $levelId = $current_user->membership_level->ID;
		//echo "<script>swal('".$levelId."');</script>";

	  }else if($subscriptionbyAgentID > 0 && $subscriptionbyAgentID == $level->id){
		  $current_level = true;	  
		  $levelId = $subscriptionbyAgentID;

		  $levelname = $wpdb->get_var("SELECT name FROM wp_pmpro_membership_levels where id = ".$subscriptionbyAgentID); 
	  }else
		  $current_level = false;
	?>
	<div style="position:relative" class="<?php if($count++ % 2 == 0) { ?>odd<?php } else { ?>even<?php } ?><?php if($current_level == $level) { ?> active<?php } ?>">

		<?php 
			if(pmpro_isLevelFree($level)) { ?>

		<p style="font-size: 18px;"><?php echo $current_level ? "<strong>{$level->name}</strong>" : $level->name?></p>

		<?php } else { ?>

		<p style="font-size: 20px;"><?php echo $current_level ? "<strong>{$level->name}</strong>" : $level->name?></p>
		<?php } ?>

		<p>
		<?php 
			if(pmpro_isLevelFree($level))
				$cost_text = "<strong>" . __("Free Trial", "pmpro") . "</strong>";
			else
				$cost_text = pmpro_getLevelCost($level, true, true);

			if($level->id==6)
			{
			$cost_text="<del>$33</del>  <span>".$cost_text."</span>";
			}
			if($level->id==7)
			{
			$cost_text="<del>$60</del>  <span>".$cost_text."</span>";
			}
			$expiration_text = pmpro_getLevelExpiration($level);
			if(!empty($cost_text) && !empty($expiration_text))
				echo $cost_text . "<br />" . $expiration_text;
				
			elseif(!empty($cost_text))
				echo $cost_text;
			elseif(!empty($expiration_text))
				echo $expiration_text;
		?>
		</p>
		<p>
		<?php
		$buy_now="Buy Now";
		if($level->id==1){
			echo "Save: 13.67%";
		} else if($level->id==2){
			echo "Save: 6.33%";
		} else if($level->id==3){
			echo "Basic Plan";
		} else	if($level->id==4)
			{
			echo "Enjoy Viewing <br> Live Channels ";
			$buy_now="Subscribe Now";
			}
		  else	
			echo "Festival Offer";			?>
		</p>
		<?php if($level->id==1 or $level->id==2 or $level->id==3){ ?>
		<p>
		30 Day Free Trial <br />
		<span style="font-size:10px">*Only with First Subscription</span>
		</p>
		<?php } else if($level->id==4){ echo '<p>
		7 Days <br />
		<span style="font-size:10px">*Only once</span><br>
		</p>';}
			else { echo '<p style="font-size: 11.5px;margin-bottom: 24px;">
		Offer valid for 1st 1000 Users <br />
		<span style="font-size:15px">Subscribe Now</span>
		</p><br>';}
			?>

		<div class="clear"></div>

		<p>
		<?php if(empty($levelId)) { ?>

			<?php	if( ($level->id == 4) && ($userTotalCount >= 1)){

			 echo '<a style="color: black !important;   background-color: white !important;" class="pmpro_btn disabled" id="<?php echo $level->id ?>" href="#">Free Trial Expired <br> Please buy a subscription '.$promo_text.'
</a>';
			} else { ?>
						 <a class="pmpro_btn pmpro_btn-select" oplan="<?php echo $levelname ?>" plan="<?php echo $level->name ?>" onclick="return checkActive(this.id,this.name);" id="<?php echo $level->id ?>" name="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e($buy_now, 'pmpro');?></a>
			<?php } ?>


		<?php } elseif ( !$current_level ) { 
				if( ($level->id == 4) && ($userTotalCount >= 1)){

			 echo '<a style="color: black !important;   background-color: white !important;" class="pmpro_btn disabled" id="<?php echo $level->id ?>" href="#">Free Trial Expired <br> Please buy a subscription '.$promo_text.'
</a>';
				} else {
?>                	
			<a  class="pmpro_btn pmpro_btn-select" oplan="<?php echo $levelname ?>" plan="<?php echo $level->name ?>" onclick="return checkActive(this.id,this.name)" id="<?php echo $level->id ?>" href="#" name="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?> "><?php _e($buy_now, 'pmpro');?></a>
		<?php	 } 
		} elseif($current_level) { ?>      
		
			<?php
			//if it's a one-time-payment level, offer a link to renew				
			if( pmpro_isLevelExpiringSoon( $current_user->membership_level) && $current_user->membership_level->allow_signups ) 				{
				if( ($level->id == 4) && ($userTotalCount >= 1)){

			 echo '<a style="color: black !important;   background-color: white !important;" class="pmpro_btn disabled" id="<?php echo $level->id ?>" href="#">Free Trial Expired <br> Please buy a subscription '.$promo_text.'
</a>';
				} else {
				?>
					<a class="pmpro_btn pmpro_btn-select" style="font-family: comic-sans;" plan="<?php echo $level->name ?>" id="<?php echo $level->id ?>" onclick="return checkActive(this.id,this.name);" href="#" name="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Renew', 'pmpro');?><br>Days left: <?php echo $delay ;?><?php echo $promo_text?></a>
				<?php }
			} else {
				?>	
					<a class="pmpro_btn disabled" style="font-family: comic-sans;" plan="<?php echo $level->name ?>" id="<?php echo $level->id ?>" onclick="return checkActive(this.id,this.name);" href="#" name="<?php echo pmpro_url("account")?>"><?php _e('Your&nbsp;Plan', 'pmpro');?><br>Days left: <?php echo $delay ;?><?php echo $promo_text?></a>
			
				
				<?php
			}
			?>
		

		<style>
		
		a.pmpro_btn.disabled {
		background-color: #009688 !Important;
		color: #fff9f9 !important;
		}

		
		</style>

		
		<?php } ?>
		</p>
<div class="clear"></div>
		<p>
		<form class="frnd" id="frnd<?php echo $level->id ?>" action="../buy-a-friend" method="post">
		<input type="hidden" name="plan_id" value="<?php echo $level->id ?>" />
		<input type="hidden" name="boq_id" value="4" />
		<?php 
			

			if($level->id==4) {

		?>
		
		<a class="pmpro_btn frnd" style="font-family: comic-sans;" plan="<?php echo $level->name ?>" id="<?php echo $level->id ?>" onclick="return checkShare(this.id,this.name);" href="#" name="<?php echo pmpro_url("account")?>"><?php _e('Share with a Friend', 'pmpro');?></a>	

		<?php } else  {
 ?>
	<a class="pmpro_btn frnd" style="font-family: comic-sans;" plan="<?php echo $level->name ?>" id="<?php echo $level->id ?>" onclick="return checkShare(this.id,this.name);" href="#" name="<?php echo pmpro_url("account")?>"><?php _e('Buy For a Friend', 'pmpro');?></a>	

	<?php } ?>

	</form>
		</p>
	</div>
	<?php
	}
	?>
<!-- </tbody> -->
</div>
<nav id="nav-below" class="navigation" role="navigation">
	<div class="nav-previous alignleft">
		<?php if(!empty($levelId)) { ?>
			<a href="<?php echo pmpro_url("account")?>"><?php _e('&larr; Return to Your Account', 'pmpro');?></a>
		<?php } ?>
	</div>
</nav>
<script>
function checkActive(id,name){
			
			<?php if(!is_user_logged_in()) { ?>

			if(id==4)
			swal('Please Register to avail Free -Trial');
			else
			{
				swal({
				  title:'&#x2757;Alert', 
				  text: 'Please Login / Register <a href=\"javascript:void(0)\" ><button onclick=\"swal.cancelAlert()\" style=\"height: 8px; width: 14px; padding: 8px 0px; position: absolute; top: -55px; right: -20px; background: none !important; color: #77909F !important;border-radius: 0px !important; text-align: center; font-size: 18px; border: none !important;\" type=\'button\'>&#x2715;</button></a>',
				  type: 'warning',
				  html:true,
				  showCancelButton: true,  cancelButtonColor:"#607D8B !important", confirmButtonColor: "#DD6B55",   confirmButtonText: "Register",   cancelButtonText: "Login"
				},function(isConfirm){

				if(isConfirm) {
				var values = {'action':'lrB', 'p':name};
				jQuery.ajax({url: 'http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php',
							type: 'post',
							data: values,
							success: function(response){  
										
							}
						});
				window.location.href="../register";


				}

				else{
				var values = {'action':'lrB', 'p':name};
				jQuery.ajax({url: 'http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php',
							type: 'post',
							data: values,
							success: function(response){  
										
							}
						});


				window.location.href="../login";

				}

				}); 

			}
			
			return false;

			<?php } else { ?>

		

			var id=id;
			
			var href = name;
				
			var plan=document.getElementById(id).getAttribute("plan");

			var curr_plan=<?php echo (int)$levelId ?>

			if(curr_plan==0)
			{

				window.location.href = href;
				return;
			}			


			<?php if((int)$levelId!=0) { ?>
			if(id == <?php echo (int)$levelId ?> ){

				if( (id == 4) && (<?php echo $userCount ?> >= 1)){
					swal('You used your Free Trial. Select some other plan');
				}else{

					swal({
					title: 'Plan Change Info',
					text: 'You are trying to renew your previously selected <strong><?php echo $levelname; ?> plan<a href=\"javascript:void(0)\" ><button onclick=\"swal.cancelAlert()\" style=\"height: 8px; width: 14px; padding: 8px 0px; position: absolute; top: -55px; right: -20px; background: none !important; color: #77909F !important;border-radius: 0px !important; text-align: center; font-size: 18px; border: none !important;\" type=\'button\'>&#x2715;</button></a>',
					confirmButtonText: 'Proceed',
					cancelButtonText: 'Cancel',
					showCancelButton: true,
					html: true,
					closeOnConfirm: false, 
					closeOnCancel: true,

					},function(isConfirm){

						if (isConfirm) {     
							window.location.href = href;
						}					
					});
				}
			}else{

				if( (id == 4) && (<?php echo $userCount ?> >= 1)){
					swal('You used your Free Trial. Select some other plan');
				}else{
					
					swal({
						title: 'Plan Change Info',
						text: 'You are trying to change your plan from  <strong><?php echo $levelname; ?></strong> plan to <strong> '+plan+' </strong> plan<a href=\"javascript:void(0)\" ><button onclick=\"swal.cancelAlert()\" style=\"height: 8px; width: 14px; padding: 8px 0px; position: absolute; top: -55px; right: -20px; background: none !important; color: #77909F !important;border-radius: 0px !important; text-align: center; font-size: 18px; border: none !important;\" type=\'button\'>&#x2715;</button></a>',
						confirmButtonText: 'Proceed',
						cancelButtonText: 'Cancel',
						showCancelButton: true,
						html: true,
						closeOnConfirm: false, 
						closeOnCancel: true,

					},function(isConfirm){ 
						if (isConfirm) {     
							window.location.href = href;
						}
					});
				}

			}
		return false;
			<?php } ?>


		<?php } ?>
		}

function checkShare(id,name){
			
			<?php if(!is_user_logged_in()) { ?>

			swal({
          title:'&#x2757;Alert',  
 text: 'Please Login / Register <a href=\"javascript:void(0)\" ><button onclick=\"swal.cancelAlert()\" style=\"height: 8px; width: 14px; padding: 8px 0px; position: absolute; top: -55px; right: -20px; background: none !important; color: #77909F !important;border-radius: 0px !important; text-align: center; font-size: 18px; border: none !important;\" type=\'button\'>&#x2715;</button></a>',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55", cancelButtonColor: "#607D8B !important",  confirmButtonText: "Register",   cancelButtonText: "Login"
},function(isConfirm){

if(isConfirm) {
var values = {'action':'lr', 'p':id};
jQuery.ajax({url: 'http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php',
			type: 'post',
			data: values,
			success: function(response){  
										
			}
		});
window.location.href="../register";


}

else{
var values = {'action':'lr', 'p':id};
jQuery.ajax({url: 'http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php',
			type: 'post',
			data: values,
			success: function(response){  
										
			}
		});


window.location.href="../login";
//alert("sess:"+"<?php echo $_SESSION['log_href']; ?>");
}

}); 
			
			return false;

			<?php } else { ?>

				jQuery('#frnd'+id).submit();

			
			return false;

		<?php } ?>
		}
</script>

<?php 
global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels = pmpro_getAllLevels(false, true);
$pmpro_level_order = pmpro_getOption('level_order');


get_currentuserinfo();
$this_user = get_current_user_id();
$userCount = $wpdb->get_var("SELECT count(user_id) as count FROM wp_pmpro_memberships_users where user_id = ".$this_user); 

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

	  }else if($subscriptionbyAgentID > 0 && $subscriptionbyAgentID == $level->id){
		  $current_level = true;	  
		  $levelId = $subscriptionbyAgentID;

		  $levelname = $wpdb->get_var("SELECT name FROM wp_pmpro_membership_levels where id = ".$subscriptionbyAgentID); 
	  }else
		  $current_level = false;
	?>
	<div  class="<?php if($count++ % 2 == 0) { ?>odd<?php } else { ?>even<?php } ?><?php if($current_level == $level) { ?> active<?php } ?>">
		<p><?php echo $current_level ? "<strong>{$level->name}</strong>" : $level->name?></p>
		<p>
		<?php 
			if(pmpro_isLevelFree($level))
				$cost_text = "<strong>" . __("Free", "pmpro") . "</strong>";
			else
				$cost_text = pmpro_getLevelCost($level, true, true); 
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
		if($level->id==1){
			echo "Save: 13.67%";
		} else if($level->id==2){
			echo "Save: 6.33%";
		} else if($level->id==3){
			echo "Basic Plan";
		} else
			echo "Special Offer";?>
		</p>
		<?php if($level->id==1 or $level->id==2 or $level->id==3){ ?>
		<p>
		30 Day Free Trial <br />
		<span style="font-size:10px">*Only with First Subscription</span>
		</p>
		<?php } else { echo '<p>
		7 Day Free Trial <br />
		<span style="font-size:10px">*Only once</span>
		</p>';}?>

		<p>
		<?php if(empty($levelId)) { ?>

			<?php	if( ($level->id == 4) && ($userCount >= 1)){

			 echo '<a style="color: black !important;   background-color: white !important;" class="pmpro_btn disabled" id="<?php echo $level->id ?>" href="#">Free Trail Used</a>';
			} else { ?>
						 <a class="pmpro_btn pmpro_btn-select" plan="<?php echo $level->name ?>" onclick="return checkActive(this.id,this.name);" id="<?php echo $level->id ?>" name="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'pmpro');?></a>
			<?php } ?>


		<?php } elseif ( !$current_level ) { ?>                	
			<a  class="pmpro_btn pmpro_btn-select" plan="<?php echo $level->name ?>" onclick="return checkActive(this.id,this.name);" id="<?php echo $level->id ?>" href="#" name="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?> "><?php _e('Select', 'pmpro');?></a>
		<?php } elseif($current_level) { ?>      
		
			<?php
			//if it's a one-time-payment level, offer a link to renew				
			if( pmpro_isLevelExpiringSoon( $current_user->membership_level) && $current_user->membership_level->allow_signups ) {
				?>
					<a class="pmpro_btn pmpro_btn-select" plan="<?php echo $level->name ?>" id="<?php echo $level->id ?>" onclick="return checkActive(this.id,this.name);" href="#" name="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Renew', 'pmpro');?></a>
				<?php
			} else {
				?>	
					<a class="pmpro_btn disabled" plan="<?php echo $level->name ?>" id="<?php echo $level->id ?>" onclick="return checkActive(this.id,this.name);" href="#" name="<?php echo pmpro_url("account")?>"><?php _e('Your&nbsp;Plan', 'pmpro');?></a>
			
				
				<?php
			}
			?>
		
		<style>a.pmpro_btn.disabled {
		background-color: #009688 !Important;
		color: #2a2a2a !important;
		}</style>

		<script>
		function checkActive(id,name){
			
			var id=id;
			var href = name;
			var plan=document.getElementById(id).getAttribute("plan");
				
			if(id == <?php echo $levelId ?> ){

				if( (id == 4) && (<?php echo $userCount ?> >= 1)){
					swal('You used your Free Trail. Select some other plan');
				}else{

					swal({
					title: 'Plan Change Info',
					text: 'You are trying to renew your previously selected <strong><?php echo $levelname; ?> plan',
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
					swal('You used your Free Trail. Select some other plan');
				}else{
					
					swal({
						title: 'Plan Change Info',
						text: 'You are trying to change your plan from  <strong><?php echo $levelname; ?></strong> plan to <strong> '+plan+' </strong> plan',
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
		}
		</script>
		<?php } ?>
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

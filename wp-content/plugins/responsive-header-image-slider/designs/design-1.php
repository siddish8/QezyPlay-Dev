<div class="slide-image"><?php the_post_thumbnail('url'); ?>	
<?php $sliderurl = get_post_meta( get_the_ID(),'rsris_slide_link', true );				if($sliderurl != '') { ?>				
<a href="<?php echo $sliderurl; ?>" class="image-link" ><button>TEST</button></a>				
<?php }  
else
{
if(!is_user_logged_in())
{
$sliderurl=site_url('register');
$btn="SIGN UP <br> <span style='font-size:18px'>1 Week Free-Trial</span>";
}
else
{
global $wpdb;
global $current_user;	
get_currentuserinfo();
$user_id = get_current_user_id();
$sub_status = $wpdb->get_var("SELECT status FROM wp_pmpro_memberships_users WHERE user_id =  ".$user_id. " order by id desc limit 1");
$subAgent = $wpdb->get_var("SELECT plan_id FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$user_id. " ORDER BY id DESC LIMIT 1
");
$enddateAgent = $wpdb->get_var("SELECT subscription_end_on FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$user_id. " ORDER BY id DESC LIMIT 1");
//echo "<script>console.log('endDateAgent:".$enddateAgent."')</script>";

$dateNow = new DateTime("now");
$dateNow=$dateNow->format("Y-m-d");
$dateNow = new DateTime($dateNow);

$enddateAgent=new DateTime($enddateAgent);
$enddateAgent=$enddateAgent->format("Y-m-d");
$enddateAgent = new DateTime($enddateAgent);

$validity=($enddateAgent >= $dateNow)?1:0;

	//if ($sub_status=="active") {
	if (($sub_status=="active") or ( (int)$subAgent>0 and $validity==1)){
	update_option("subscribed_".$user_id,"true");
			}
	else{
	update_option("subscribed_".$user_id,"false");
	}

	$chk=get_option("subscribed_".$user_id,"");//get_option("subscribed_".$userid)
	if($chk == "" or $chk =="false")
	{
		$sliderurl=site_url('subscription');
		$btn="Subscribe";
	}
	else
	{
		$sliderurl="";
		$btn="";
		$active=1;
	}	
					



}
?> 
<style>
div[slidesjs-index="0"] > div#slider_content {
   top: 51% !important;
}
div[slidesjs-index="1"] > div#slider_content {
    top: 51% !important;
}
div[slidesjs-index="2"] > div#slider_content {
   top: 51% !important;
}
div[slidesjs-index="3"] > div#slider_content {
    top: 51% !important;
}
div[slidesjs-index="4"] > div#slider_content {
    top: 51% !important;
}

</style>
<?php if(!$active) { ?>
<div id="slider_content" align="center" style="margin:0 auto;left: 30px;
    right: 30px;
    position: absolute;
    
    text-align: center;
    /*padding: 10% 30px;*/"><a href="<?php echo $sliderurl; ?>" class="image-link" ><button class="banner-btn btn btn-primary btn-round-lg btn-lg" style="background-color:#4141a0 !important"><?php echo $btn ?></button></a></div> 
<script>

jQuery('div[slidesjs-index="4"] > button').html("SIGN UP");

</script>		<?php } ?>
<?php
}

?></div>

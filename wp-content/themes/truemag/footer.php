<?php

/**

 * The template for displaying the footer.

 *

 * Contains footer content and the closing of the

 * #main and #page div elements.

 *

 */

?>

<div id="bottom-nav_1">

<div class="ts-section-top-footer">

<div class="ts-top-footer">

<div class="container">

<div class="row">

<div style="font-size:18px" class="col-lg-6 col-md-6 col-sm-6 ts-contact-email-info contact-info">

<div class="pull-left">

<span><i class="fa fa-envelope-o"></i></span>

<!-- <a target="_blank" href="mailto:contact@qezymedia.com">Email us</a>-->
<a href="mailto:admin@qezyplay.com?cc=siddish.gollapelli@ideabytes.com&amp;subject=QezyPlay%20Email%20Us&amp;">
Email us</a>

</div>

</div>

<div style="font-size:18px" class="col-lg-6 col-md-6 col-sm-6 ts-contact-phone-info contact-info">

<div class="pull-right">

<span><i class="fa fa-phone"></i></span>

<p>+91-910 002 9202</p>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

    <footer class="dark-div">

		<?php if ( is_404() && is_active_sidebar( 'footer_404_sidebar' ) ) { ?>

    	<div id="bottom">

            <div class="container">

                <div class="row">

					<?php dynamic_sidebar( 'footer_404_sidebar' ); ?>                    

                </div><!--/row-->

            </div><!--/container-->

        </div><!--/bottom-->

		<?php } elseif ( is_active_sidebar( 'footer_sidebar' ) ) { ?>

    	<div id="bottom">

            <div class="container">

                <div class="row">

					<?php dynamic_sidebar( 'footer_sidebar' ); ?>                    

                </div><!--/row-->

            </div><!--/container-->

        </div><!--/bottom-->

		<?php } ?>

		<?php tm_display_ads('ad_foot');?>

					

       

 <div id="bottom-nav">

        	<div class="container">

                <div class="row">

					<div class="copyright col-md-6"><?php echo ot_get_option('copyright',get_bloginfo('name').' - '.get_bloginfo('description')); ?></div>

					<nav class="col-md-6">

                    	<ul class="bottom-menu list-inline pull-right">

                        	<?php

								if(has_nav_menu( 'footer-navigation' )){

									wp_nav_menu(array(

										'theme_location'  => 'footer-navigation',

										'container' => false,

										'items_wrap' => '%3$s'

									));	

								}?>

                        </ul>

                    </nav>

				</div><!--/row-->

            </div><!--/container-->

        </div>

    </footer>

    <div class="wrap-overlay"></div>

</div><!--wrap-->

<?php if(ot_get_option('mobile_nav',1)){ ?>

<div id="off-canvas">

    <div class="off-canvas-inner">

        <nav class="off-menu">

            <ul>

            <li class="canvas-close"><a href="#"><i class="fa fa-times"></i> <?php _e('Close','cactusthemes'); ?></a></li>

			<?php

				$megamenu = ot_get_option('megamenu', 'off');

				if($megamenu == 'on' && function_exists('mashmenu_load')){

					global $in_mobile_menu;

					$in_mobile_menu = true;

					mashmenu_load();

					$in_mobile_menu = false;

				}elseif(has_nav_menu( 'main-navigation' )){

                    wp_nav_menu(array(

                        'theme_location'  => 'main-navigation',

                        'container' => false,

                        'items_wrap' => '%3$s'

                    ));	

                }else{?>

                    <li><a href="<?php echo home_url(); ?>/"><?php _e('Home','cactusthemes'); ?></a></li>

                    <?php wp_list_pages('title_li=' ); ?>

            <?php } ?>

            <?php

			 	$user_show_info = ot_get_option('user_show_info');

				if ( is_user_logged_in() && $user_show_info =='1') {

				$current_user = wp_get_current_user();

				$link = get_edit_user_link( $current_user->ID );

				?>

                    <li class="menu-item current_us">

                    <?php  

                    echo '<a class="account_cr" href="#">'.$current_user->user_login; 

                    echo get_avatar( $current_user->ID, '25' ).'</a>';

                    ?>

                    <ul class="sub-menu">

                        <li class="menu-item"><a href="<?php echo $link; ?>"><?php _e('Edit Profile','cactusthemes') ?></a></li>

                        <li class="menu-item"><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e('Logout','cactusthemes') ?></a></li>

                    </ul>

                    </li>

				<?php }?>

                <?php //submit menu

				if(ot_get_option('user_submit',1)) {

					$text_bt_submit = ot_get_option('text_bt_submit');

					if($text_bt_submit==''){ $text_bt_submit = 'Submit Video';}

					if(ot_get_option('only_user_submit',1)){

						if(is_user_logged_in()){?>

						<li class="menu-item"><a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>

					<?php }

					} else{

					?>

						<li class="menu-item"><a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>

					<?php 

						

					}

				} ?>

            </ul>

        </nav>

    </div>

</div><!--/off-canvas-->

<script>off_canvas_enable=1;</script>

<?php }?>

<?php if(ot_get_option('theme_layout',false)){ ?>

</div><!--/boxed-container-->

<?php }?>

<div class="bg-ad">

	<div class="container">

    	<div class="bg-ad-left">

			<?php tm_display_ads('ad_bg_left');?>

        </div>

        <div class="bg-ad-right">

			<?php tm_display_ads('ad_bg_right');?>

        </div>

    </div>

</div>

</div><!--/body-wrap-->

<?php

	if(ot_get_option('user_submit',1)) {?>

	<div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	  <div class="modal-dialog">

		<div class="modal-content">

		  <div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

			<h4 class="modal-title" id="myModalLabel"><?php _e('Submit Video','cactusthemes'); ?></h4>

		  </div>

		  <div class="modal-body">

			<?php dynamic_sidebar( 'user_submit_sidebar' ); ?>

		  </div>

		</div>

	  </div>

	</div>

<?php } ?>

<?php

	if( is_single() && ot_get_option('video_report','on')!='off' ) {?>

	<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	  <div class="modal-dialog">

		<div class="modal-content">

		  <div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

			<h4 class="modal-title" id="myModalLabel"><?php _e('Report Video','cactusthemes'); ?></h4>

		  </div>

		  <div class="modal-body">

			<?php echo do_shortcode('[contact-form-7 id="'.ot_get_option('video_report_form','').'"]'); ?>

		  </div>

		</div>

	  </div>

	</div>

<?php } ?>

<?php if(!ot_get_option('theme_layout') && (ot_get_option('adsense_slot_ad_bg_left')||ot_get_option('ad_bg_left')||ot_get_option('adsense_slot_ad_bg_right')||ot_get_option('ad_bg_right')) ){ //fullwidth layout ?>

<script>

	enable_side_ads = true;

</script>

<?php } ?>

<a href="#top" id="gototop" class="notshow" title="Go to top"><i class="fa fa-angle-up"></i></a>

<?php echo ot_get_option('google_analytics_code', ''); ?>

<?php wp_footer(); ?>



<?php



require_once ABSPATH.'/mobile_detect/Mobile_Detect.php';  //LIVE

require_once ABSPATH.'/mobile_detect/more_detection.php';  //LIVE


require_once ABSPATH.'/mobile_detect/secure_token.php';  //LIVE




//require_once $_SERVER['DOCUMENT_ROOT'].'/mobile_detect/more_detection.php'; //DEMO

//require_once $_SERVER['DOCUMENT_ROOT'].'/mobile_detect/Mobile_Detect.php';





global $wpdb;

global $current_user;

get_currentuserinfo();

$user_id = get_current_user_id();

$user_info=get_userdata($user_id);

$user_name=$user_info->user_login;



	



$detect = new Mobile_DetectNew;



if($detect->isMobile()){

	$device="Mobile";}

else{

	$device="Personal Computer";}



$session_id=session_id();

$time_val=time();

$unique_id=$session_id."-".$time_val;



$session_id=$unique_id;



global $post;

$post = $wp_query->post;

// echo "<script>console.log('postId:".get_the_ID()."')</script>";

//echo "<script>console.log('postId:".$post->ID."')</script>";



$post_id=$post->ID;



$postdata = get_post($post_id);

$post_title = $postdata->post_title;

$post_name = $postdata->post_name; 



$ip_address=$_SERVER['REMOTE_ADDR'];
echo "<script>console.log('IP:".$ip_address ."')</script>";

//echo $ip_address;

$user_agent=$ua=$_SERVER['HTTP_USER_AGENT'];
$os_name=getOS($ua);
$browser_array=getBrowser($ua);

$browser_name=$browser_array[0];
$browser_version=$browser_array[1];

$page_referer=$_SERVER['HTTP_REFERER'];

if(is_single()) //is a channel
{
//plan_id from agent-side subscrptn
$subAgent = $wpdb->get_var("SELECT plan_id FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$user_id. " ORDER BY id DESC LIMIT 1

");
//endtime from wp agent_vs_sub
$enddateAgent = $wpdb->get_var("SELECT subscription_end_on FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$user_id. " ORDER BY id DESC LIMIT 1");
//echo "<script>console.log('endDateAgent:".$enddateAgent."')</script>";
$dateNow = new DateTime("now");
//$dateNow1 = new DateTime("2016-05-21");

$enddateAgent=new DateTime($enddateAgent);

$validity2=($enddateAgent >= $dateNow)?1:0;

//echo "<script>console.log('val2:".$validity2."')</script>";



$subStatus = $wpdb->get_var("SELECT count(user_id) FROM wp_pmpro_memberships_users WHERE user_id =  ".$user_id. " and status='active' ORDER BY id DESC LIMIT 1");



//check for video play 


$user_access= $wpdb->get_var("SELECT user_id FROM user_video_accesslist WHERE user_id =  ".$user_id." ");
$user_access=(int)$user_access;
//echo "<script>console.log('acc_user:".$user_access."')</script>";


if(  ( $subStatus==1 or ((int)$subAgent>0 and $validity2==1)) or $user_access) 
{
$play=1;
//echo "<script>console.log('play:".$play."')</script>";
}
else

$play=0;

}

else $play=-1;


$access_token=qezy_enc_web_analytics($user_id,$user_name,$post_id,$post_title,$post_name,$page_referer,$os_name,$browser_name,$browser_version,$session_id,$ip_address,$play,$device);
echo "<script>console.log('AT:".$access_token."')</script>";
echo '<script type="text/javascript">	addonce();

					function addonce(){

										

						/*	var myValues1 = { "action" : "addanalytics","user_id" : "'.$user_id.'","user_name" : "'.$user_name.'", "session_id" : "'.$session_id.'","page_id" : "'.$post_id.'","page_name" : "'.$post_name.'","page_title" : "'.$post_title.'","device":"'.$device.'","os_version":" ","ip_address" : "'.$ip_address.'","country_code":"'.$country_code.'","country":"'.$country_name.'","state":"'.$state.'","city":"'.$city.'","tz":"'.$tz.'","lat":"'.$lat.'","lngt":"'.$lngt.'","geo_info_status":"'.$geoinfo_status.'","os_name" : "'.$os_name.'","browser_name" : "'.$browser_name.'","browser_version" : "'.$browser_version.'","page_referer" : "'.$page_referer.'","play":"'.$play.'","at":"'.$access_token.'"};*/

						var myValues1 = { "action" : "addanalytics","access_token":"'.$access_token.'"};

							jQuery.ajax({

								url: "'.site_url().'/mobile_detect/updatesessionbyapp.php",

								type: "post",

								data: myValues1,

								success: function(data){
									
										//window.alert(data);	
									if(data == "1"){

												//window.alert("ok");

											}

									     	}

								});	

						 }

						

					setInterval( function(){

							//window.alert("30sec");						

							/* var myValues1 = { "action" : "addanalytics","user_id" : "'.$user_id.'","user_name" : "'.$user_name.'", "session_id" : "'.$session_id.'","page_id" : "'.$post_id.'","page_name" : "'.$post_name.'","page_title" : "'.$post_title.'","device":"'.$device.'","os_version":" ","ip_address" : "'.$ip_address.'","country_code":"'.$country_code.'","country":"'.$country_name.'","state":"'.$state.'","city":"'.$city.'","tz":"'.$tz.'","lat":"'.$lat.'","lngt":"'.$lngt.'","geo_info_status":"'.$geoinfo_status.'","os_name" : "'.$os_name.'","browser_name" : "'.$browser_name.'","browser_version" : "'.$browser_version.'","page_referer" : "'.$page_referer.'","play":"'.$play.'"};*/

							var myValues1 = { "action" : "addanalytics","access_token":"'.$access_token.'"};

							jQuery.ajax({

								url: "'.site_url().'/mobile_detect/updatesessionbyapp.php",

								type: "post",

								data: myValues1,

								success: function(data){if(data == "1"){



												//window.alert("ok");

											}

									     	}

								});	

						 }, 30000);					





					</script>'; //change URL twice in ajax call



if(is_user_logged_in())

{

do_action('update_daily');

do_action('subscribed');

}



?>

</body>

</html>
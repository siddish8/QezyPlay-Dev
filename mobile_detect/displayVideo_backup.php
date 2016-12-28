<?php


function displayVideo($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o){

if(!is_user_logged_in()){
header('Location:../');
}
	//require_once $_SERVER['DOCUMENT_ROOT'].'/mobile_detect/more_detection.php'; //LIVE
	require_once $_SERVER['DOCUMENT_ROOT'].'/newqezyplay/mobile_detect/more_detection.php'; //DEMO

	$vidurl=$a;
	$imgurl=$b;
	$session_id=$c;
	$post_id=$d;
	$ip_address=$e;
	$ua=$f;

	$os_name=getOS($ua);
	$browser_array=getBrowser($ua);
	$browser_name=$browser_array[0];
	$browser_version=$browser_array[1];

	$page_referer=$g;
	$post_title=$h;
	$post_name=$i;
	$page_url=$j;

	$country_code=$k;
	$country_name=$l;
	$state=$m;
	$city=$n;
	$geoinfo_status=$o;


	global $wpdb;
	global $current_user;	
get_currentuserinfo();
$user_id = get_current_user_id();
$user_name = $current_user->user_login;
	
	echo '<style>#player{display:block !important;}</style>';
	
//mem_id from wp pmpro users


	//require_once $_SERVER['DOCUMENT_ROOT'].'/mobile_detect/Mobile_Detect.php'; //LIVE
	//require_once getcwd().'/mobile_detect/Mobile_Detect.php'; //DEMO
	require_once $_SERVER['DOCUMENT_ROOT'].'/newqezyplay/mobile_detect/Mobile_Detect.php'; //DEMO
	$detect = new Mobile_DetectNew;



$subAgent = $wpdb->get_var("SELECT plan_id FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$user_id. " ORDER BY id DESC LIMIT 1
");
$enddateAgent = $wpdb->get_var("SELECT subscription_end_on FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$user_id. " ORDER BY id DESC LIMIT 1");
echo "<script>console.log('endDateAgent:".$enddateAgent."')</script>";
$dateNow = new DateTime("now");
$enddateAgent=new DateTime($enddateAgent);

	echo '<style>#player{display:block !important;}</style>';
	
//mem_id from wp pmpro users

$sub_status = $wpdb->get_var("SELECT status FROM wp_pmpro_memberships_users WHERE user_id =  ".$user_id. " order by id desc limit 1");

$validity=($enddateAgent >= $dateNow)?1:0;
//echo "<script>console.log('val1:".$validity1."')</script>";
echo "<script>console.log('val:".$validity."')</script>";
echo "<script>console.log('Direct Sub-status:".$sub_status."')</script>";

if( (int)$subAgent>0 and $validity==1)
{
echo "<script>console.log('Payment through Agent Portal')</script>";
}

//if(  ( (int)$sub>0 and $validity1==1) or ( (int)$subAgent>0 and $validity2==1)  or ($user_id==4) ) 
if (($sub_status=="active") or ( (int)$subAgent>0 and $validity==1)){

echo "<script>console.log('Subscribed.. Can watch')</script>";
update_option("subscribed_".$user_id,"true");
echo "<script>console.log('subscribed_".$user_id.":".get_option("subscribed_".$user_id,"")."')</script>";
	//if(is_user_logged_in()){

		if($detect->isMobile()){

			$app_analytics_update_duration = get_option('app_analytics_update_duration');
			$app_force_update_status = get_option('app_force_update_status');
			$app_shutdown_wait_timeout = get_option('app_shutdown_wait_timeout');

			if($detect->isiOS())
				$device = "ios";
		 
			if($detect->isAndroidOS())
				$device = "android";
		
		 	
			if($detect->isAndroidOS()){
					//Android

				if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.ib.qezyplay"){ 
					//echo '<script>swal("isAndroidApp=true")</script>';
				 	$app=1; 
				}else{
					//echo '<script>swal("isAndroidApp=false")</script>'; 
					$app=0;
				}

				
				$app_android_version = get_option('app_android_version');
				

			  	echo '<script>	
					function playinAndroidApp(intent){	
											
							var app = "'.$app.'";					
							var intent = intent;
							var octourl="'.$vidurl.'";
							var aav = "'.$app_android_version.'";
							var aaud = "'.$app_analytics_update_duration.'";
							var afus = "'.$app_force_update_status.'";
							var aswt = "'.$app_shutdown_wait_timeout.'";
										
						if(app == 1){
							Android.playVideo(octourl,aaud,aav,afus,aswt);
						}else{
							var myWindow = window.open(intent,"_blank");
						}
					}				
				
					</script>';					

				echo '<div align="center" id="player_img" style="background-image:url('.$imgurl.') !important;background-size: 100% 100% !important;width:650px;height:360px;background-color: white !important;border-color: black ;color: Orange;" align="center"><img src="'.site_url().'/wp-content/uploads/2016/06/PlayImg.png" onclick="playinAndroidApp(\'intent:#Intent;action=com.ideabytes.qezyplay.LAUNCH_PLAYER;S.page_id='.$post_id.';S.post_title='.$post_title.';S.post_name='.$post_name.';S.user_id='.$user_id.';S.user_name='.$user_name.';S.page_url='.$page_url.';S.page_referer='.$page_referer.';S.browser_name='.$browser_name.';S.browser_version='.$browser_version.';S.session_id='.$session_id.';S.country_code='.$country_code.';S.country='.$country_name.';S.state='.$state.';S.city='.$city.';S.geo_info_status='.$geoinfo_status.';S.imgpath='.$imgurl.';S.octo_url='.$vidurl.';S.app_analytics_update_duration='.$app_analytics_update_duration.';S.app_android_version='.$app_android_version.';S.app_force_update_status='.$app_force_update_status.';S.app_shutdown_wait_timeout='.$app_shutdown_wait_timeout.';package=com.ib.qezyplay;end\')"></img><div>';
						if($browser_name=="UCBrowser")
							{
							echo "<script>swal('This browser is not compatible to play channel');</script>";
							}
			}elseif($detect->isiOS()){

				//For ios code
				$app_ios_version = get_option('app_ios_version');
				
				echo '<script> 
					
					function playinIosApp(app){
												
 							
						var myWindow = window.open(app,"_self");
 
						/*setTimeout(function(){
				 window.open("https://itunes.apple.com/in/app/deccan-tv/id1060975039?mt=8"); 
					window.open("itms-apps://itunes.apple.com/in/app/deccan-tv/id1060975039?mt=8");
						}, 25);*/
					
				
   	
					}
				</script>';
				
	
				echo '<div align="center" id="player_img" style="margin:0 auto;background-image:url('.$imgurl.') !important;background-size: 100% 100% !important;width:650px;height:360px;background-color: white !important;border-color: black ;color: Orange;" align="center"><img src="'.site_url().'/wp-content/uploads/2016/06/PlayImg.png" onclick="playinIosApp(\'iosQezyplayer://myapp?page_id='.$post_id.'&post_title='.$post_title.'&post_name='.$post_name.'&user_id='.$user_id.'&user_name='.$user_name.'&page_url='.$page_url.'&page_referer='.$page_referer.'&browser_name='.$browser_name.'&browser_version='.$browser_version.'&session_id='.$session_id.'&country_code='.$country_code.'&country='.$country_name.'&state='.$state.'&city='.$city.'&geo_info_status='.$geoinfo_status.'&imgpath='.$imgurl.'&octo_url='.$vidurl.'&app_analytics_update_duration='.$app_analytics_update_duration.'&app_ios_version='.$app_ios_version.'&app_force_update_status='.$app_force_update_status.'&app_shutdown_wait_timeout='.$app_shutdown_wait_timeout.'\')"></img><div>';


			}

		}else{		//Desktop
				echo '<script src="/newqezyplay/players/octoshape/swfobject.js" type="text/javascript"></script><script src="/newqezyplay/players/octoshape/jquery-1.6.1.js" type="text/javascript"></script><center><div id="player" ></div></center>
 <script type="text/javascript">// <![CDATA[
		var streamURL = \''.$vidurl.'\';// ]]></script> 

<script src="/newqezyplay/players/octoshape/player.js" type="text/javascript"></script>';
		}

	}else{
		//not subscribed
		update_option("subscribed_".$user_id,"false");
		//echo "<script>console.log('subscribed_".$user_id.":".get_option('subscribed'.$user_id)."')</script>";
		echo "<script>console.log('subscribed_".$user_id.":".get_option("subscribed_".$user_id,"")."')</script>";
		echo '<div id="unreg_msg" style="width:650px;height:360px;background-color: black !important;border-color: black;color: Orange;" align="center"><h1 style="
	    color: orangered;padding-top: 20%;font-size:25px;">Please subscribe with us to view this video. <br> <a style="color:rgb(85, 213, 208);;font-size:18px;" href="../subscriptions">Click here to Subscribe</a></h1></div>';



	}	
	echo '
  <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
  <script src="'.site_url().'/qp/slick-1.6.0/slick/slick.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    $(document).on("ready", function() {
      $(".regular").slick({
        dots: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 2
      });
      
      $(".variable").slick({
        dots: true,
        infinite: true,
        variableWidth: true
      });
    });
  </script>
';
}
?>

<?php 
    /*
    Plugin Name: Channel Code
    Plugin URI: 
    Description: Channel template code
    Author: IB
    Version: 1.0
    Author URI: ib
    */


add_shortcode('qp_channel','channel_template');

function channel_template($atts){

global $post;
$channel_atts = shortcode_atts( array(
        'vidurl' => '48/auto',
       ), $atts );

$args = array( 'vidurl' => $channel_atts['vidurl'] );

$vidurl="octoshape://streams.octoshape.net/ideabytes/live/ib-ch";

//$vidurl.="51/auto";				//Change this
$vidurl.=$channel_atts['vidurl'];

//Base64
//$vidurl=base64_encode($vidurl);
//echo "<script>console.log('".$vidurl."')</script>";

//DONT EDIT FROM HERE
$thumbnail_id = get_post_thumbnail_id();
$thumbnail_url = wp_get_attachment_image_src($thumbnail_id,'thumbnail-size', true);
$imgurl=$thumbnail_url[0]; 


$session_id=session_id();


//$page_url=$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];


$post_id=get_the_id();

$postdata = get_post($post_id);
$post_title = $postdata->post_title; //page_title
$post_name = $postdata->post_name; //page_name



$page_url=get_permalink($post_id); //this page url

$ip_address=$_SERVER['REMOTE_ADDR'];
$geoinfo = "http://api.ipinfodb.com/v3/ip-city/?key=13ebc6d8740ab89e93e615530a59dd0f22df559274089129135f83188578f84d&ip=$ip_address&format=json";

$ch_geoinfo = curl_init($geoinfo); 	
curl_setopt($ch_geoinfo, CURLOPT_HEADER, 0);         	
curl_setopt($ch_geoinfo, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch_geoinfo, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch_geoinfo, CURLOPT_AUTOREFERER, true);
curl_setopt($ch_geoinfo, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch_geoinfo,CURLOPT_CONNECTTIMEOUT,60);
curl_setopt($ch_geoinfo, CURLOPT_FAILONERROR, 1);

$execute_geoinfo = curl_exec($ch_geoinfo);
		
if(!curl_errno($ch_geoinfo)){					
	$json_geoinfo = str_replace('\\', '\\\\', $execute_geoinfo);
	$json_decode_geoinfo = json_decode($json_geoinfo, true);   
			
	$country_name = $json_decode_geoinfo["countryName"]; //country
	$city = $json_decode_geoinfo["cityName"];			//city
	$country_code = $json_decode_geoinfo["countryCode"];	//country_code		
	$state = $json_decode_geoinfo["regionName"];		//state
	//echo "<script>console.log('postId:".$city ."')</script>";
//echo "<script>console.log('postId:".$country_name ."')</script>";
//echo "<script>console.log('postId:".$country_code."')</script>";
//echo "<script>console.log('postId:".$state."')</script>";

	$geoinfo_status=1;	 //geo_info_status		
			}



$user_agent=$_SERVER['HTTP_USER_AGENT'];

$page_ref=$_SERVER['HTTP_REFERER']; //refered page url



//require_once $_SERVER['DOCUMENT_ROOT'].'/mobile_detect/displayVideo.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/newqezyplay/mobile_detect/displayVideo.php';

	displayVideo($vidurl,$imgurl,$session_id,$post_id,$ip_address,$user_agent,$page_ref,$post_title,$post_name,$page_url,$country_code,$country_name,$state,$city,$geoinfo_status);



}
?>
<?php

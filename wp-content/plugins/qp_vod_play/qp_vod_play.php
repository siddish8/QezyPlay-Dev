<?php 
    /*
    Plugin Name: Channel-VOD play
    Plugin URI: 
    Description: This plays the vods bouquet
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('qp_vod','qp_vod_play');

function qp_vod_play($atts){

	global $wpdb;
	$vod_atts = shortcode_atts( array(
		'url' => '',
	    ), $atts );

	$args = array( 'url' => $vod_atts['url']);


$url_full="octoshape://streams.octoshape.net/ideabytes/vod/QP/";
	$url=$url_full.$vod_atts['url'];
//echo '<script>alert("'.$url.'")</script>';
echo '<style>.blog-meta {   display: none;} #body * {  opacity: 1;}</style>';
echo '
		<script src="'.site_url().'/players/octoshape/swfobject.js" type="text/javascript"></script>
		<script src="'.site_url().'/players/octoshape/jquery-1.6.1.js" type="text/javascript"></script>
		<center><div id="player" style="display:block"></div></center>
		<script type="text/javascript">// <![CDATA[
			var streamVID = \''.$url.'\';var streamVOD = \''.$url.'\';// ]]></script> 
		<script src="'.site_url().'/players/octoshape/player.js" type="text/javascript"></script>
		<script>

		var player_jsbridge = {

     				playerevents: {
        				 onPlayerReady: "funcOnPlayerReady",
        				onStop: "funcOnStop",
          				onPause: "funcOnPause",
        				  onPlay: "funcOnPlay",
					onError:"funcOnError"
     						},
     				cuepoints: {
				          onMetaData: "funcOnMetaData"
     						}
					};

					function funcOnStop1()
					{
						setTimeout(function(){
							jQuery("#player").hide();
							jQuery("#media_cont").html("<div id=\'unreg_msg\' style=\'width:650px;height:360px;background-color: black !important;border-color: black;color: Orange;margin-top: -20px;\' align=\'center\'><h1 style=\'color: orangered;padding-top: 20%;font-size:25px;\'>Please subscribe to view this Channel LIVE <br> <a style=\'color:rgb(85, 213,208);font-size:25px;\' href=\'../subscription\'>Click here to Subscribe</a></h1></div>");

},2000);
}


jQuery( window ).load(function() { 

	document.getElementById("player").os_load("Qezy-TV-LIVE");

	});
</script>';



}
?>

<?php	


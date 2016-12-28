<?php 
    /*
    Plugin Name: HOME Page content
    Plugin URI: 
    Description: This displays the content section of HOME Psge
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('qezy_home','qezy_home_page');

function qezy_home_page(){

if ((strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false) or ($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.ideabytes.qezyplay.qezyplay_new")) {
echo "<script> Android.BanglaToast('boq') </script>";
}
echo do_shortcode('
[vc_row]
[vc_column width="4/5"]
[vc_tta_tabs]
[vc_tta_section tab_id="1467179547323-977b19b6-2b7c" title="Shonar Bangla"]
[scb title="Bangla-Bengali channels" layout="small_carousel" column="4" row="2" show_excerpt="0" show_rate="0" show_dur="0" show_view="0" show_com="0" show_like="0" show_aut="0" show_date="0" categories="Bangla Bouquet"]
[/vc_tta_section]
[/vc_tta_tabs]

[vc_tta_tabs][vc_tta_section title="Deccan Free Channels" tab_id="1479291494860-35ab9597-ba10"][scb title="Deccan Channels" layout="small_carousel" column="4" row="2" condition="random" show_excerpt="0" show_rate="0" show_dur="0" show_view="0" show_com="0" show_like="0" show_aut="0" show_date="0" categories="Deccan Free Channels"][/vc_tta_section][/vc_tta_tabs]

[vc_tta_tabs][vc_tta_section title="Trailer VODs" tab_id="1479291494860-35ab9597-ba10"][scb title="VODs" layout="small_carousel" column="4" row="2" condition="random" show_excerpt="0" show_rate="0" show_dur="0" show_view="0" show_com="0" show_like="0" show_aut="0" show_date="0" categories="vods"][/vc_tta_section][/vc_tta_tabs]
[/vc_column]
[/vc_row]');

//tab_id="1479292599019-8cf3ff65-d716"

echo '<script>
$(".video-item .item-thumbnail > a").click( function(e) {e.preventDefault(); 
var href = jQuery(this).attr("href");';

 if(!is_user_logged_in()) { 
	   echo 'swal("Please Log-In to view this channel");';
	}
else { 
		echo 'window.location.href=href';
 } 
echo 'return false; } ); 
$(".item-head h3 a").click( function(e) {e.preventDefault(); 
var href = jQuery(this).attr("href");';
if(!is_user_logged_in()) {
    echo 'swal("Please Log-In to view this channel");';
 }else { 
echo 'window.location.href=href;';
 }  
echo 'return false; } );</script>';

if(!is_user_logged_in()) { 
echo "<script>
jQuery(document).on('ready', function() {document.querySelector('div[slidesjs-index=\"5\"] button').innerHTML='SIGN UP <br> <span style=\"font-size:18px\">Enjoy Free Viewing </span>'});
</script>";}
else
{
echo "<script>
jQuery(document).on('ready', function() {document.querySelector('div[slidesjs-index=\"5\"] button').style.display='none'});
</script>";
} 

}
?>

<?php	


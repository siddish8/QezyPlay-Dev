<?php

include('db-config.php');
include('function_common.php');

require('phpxmlrpc-4.0.0/lib/xmlrpc.inc');
require('Wordpress-XML-RPC-Library-master/edit-post.php');

$globalerr = null;

$xmlrpcurl = 'http://ideabytestraining.com/newqezyplay/xmlrpc.php';
$username = 'qezyplay';
$password = '*&(qezyplay)*';
$title = 'This is a test1';
$content = 'This is some content123';
$categories='Trailer VODs';
$slug="test123";

$postid=1199;

$post_settings='a:1:{s:10:"vc_grid_id";a:0:{}}';
$time_video='LIVE';
$edit_last='1';
$tm_video_code='[qp_channel vidurl=\'51/auto\' vodurl=\'ch51-vod.mp4\']';
$edit_lock="1477674175:1";
$thumbnail_id="";
$slide_template="default";
$tm_multi_link="";
$show_feature_image="2";
$page_layout="def";
$single_ly_ct_video="def";
$ct_bg_repeat="no-repeat";
$wp_old_slug="test-123";

$customfields= 	array(

		array(	"key"=>"_vc_post_settings", "value"=>$post_settings ),
		array(	"key"=>"time_video", "value"=>$time_video ),
		array(	"key"=>"_edit_last", "value"=>$edit_last),
		array(	"key"=>"_edit_lock", "value"=>$edit_lock),
		array(	"key"=>"_thumbnail_id", "value"=>$thumbnail_id	),
		array(	"key"=>"slide_template", "value"=>$slide_template),
		array(	"key"=>"tm_multi_link", "value"=>$tm_multi_link	),
		array(	"key"=>"show_feature_image", "value"=>$show_feature_image ),
		array(	"key"=>"page_layout", "value"=>$page_layout),
		array(	"key"=>"single_ly_ct_video", "value"=>$single_ly_ct_video ),
		array(	"key"=>"ct_bg_repeat", "value"=>$ct_bg_repeat	),
		array(	"key"=>"tm_video_code", "value"=>$tm_video_code	),	
		array(	"key"=>"_wp_old_slug", "value"=>$wp_old_slug),
	

			);

//post-format =2 for video
//featured image
//tags


/*





'a:1:{s:10:\"vc_grid_id\";a:0:{}}'
'LIVE'
'1'
'1477674175:1'
'130'                                    --------------- _wp_attached_file 2016/06/taratv-1.png
'default'				------------- _wp_attachment_metadata a:5:{s:5:"width";i:370;s:6:"height";i:208;s:4:"file";s:20:"2016/06/taratv-1.png";s:5:"sizes";a:11:{s:9:"thumbnail";a:4:{s:4:"file";s:20:"taratv-1-150x150.png";s:5:"width";i:150;s:6:"height";i:150;s:9:"mime-type";s:9:"image/png";}s:6:"medium";a:4:{s:4:"file";s:20:"taratv-1-300x169.png";s:5:"width";i:300;s:6:"height";i:169;s:9:"mime-type";s:9:"image/png";}s:12:"thumb_139x89";a:4:{s:4:"file";s:19:"taratv-1-139x89.png";s:5:"width";i:139;s:6:"height";i:89;s:9:"mime-type";s:9:"image/png";}s:13:"thumb_365x235";a:4:{s:4:"file";s:20:"taratv-1-365x208.png";s:5:"width";i:365;s:6:"height";i:208;s:9:"mime-type";s:9:"image/png";}s:13:"thumb_196x126";a:4:{s:4:"file";s:20:"taratv-1-196x126.png";s:5:"width";i:196;s:6:"height";i:126;s:9:"mime-type";s:9:"image/png";}s:13:"thumb_260x146";a:4:{s:4:"file";s:20:"taratv-1-260x146.png";s:5:"width";i:260;s:6:"height";i:146;s:9:"mime-type";s:9:"image/png";}s:13:"thumb_356x200";a:4:{s:4:"file";s:20:"taratv-1-356x200.png";s:5:"width";i:356;s:6:"height";i:200;s:9:"mime-type";s:9:"image/png";}s:13:"thumb_180x101";a:4:{s:4:"file";s:20:"taratv-1-180x101.png";s:5:"width";i:180;s:6:"height";i:101;s:9:"mime-type";s:9:"image/png";}s:12:"thumb_130x73";a:4:{s:4:"file";s:19:"taratv-1-130x73.png";s:5:"width";i:130;s:6:"height";i:73;s:9:"mime-type";s:9:"image/png";}s:11:"thumb_72x72";a:4:{s:4:"file";s:18:"taratv-1-72x72.png";s:5:"width";i:72;s:6:"height";i:72;s:9:"mime-type";s:9:"image/png";}s:13:"thumb_358x242";a:4:{s:4:"file";s:20:"taratv-1-358x208.png";s:5:"width";i:358;s:6:"height";i:208;s:9:"mime-type";s:9:"image/png";}}s:10:"image_meta";a:12:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}}}
NULL								------------ _edit_lock 1478166947:1
'2'
'def'
'def'
'no-repeat'



*/



$post = wordpress_edit_post($xmlrpcurl, $postid, $username, $password, $title, $content, $categories, $excerpt, $text_more, $keywords, $pingurls, $date_created, $customfields, $publish=1, $proxyipports = "");
if($post == false){
    echo $globalerr."\n";
    die();
}
else {
    print_r($post);   

//post-format =2 for video




}
?>




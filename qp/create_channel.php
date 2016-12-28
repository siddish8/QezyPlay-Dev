<?php

include('db-config.php');
include('function_common.php');

require('phpxmlrpc-4.0.0/lib/xmlrpc.inc');
require('Wordpress-XML-RPC-Library-master/new-post.php');

include('../wp-admin/includes/image.php');

$globalerr = null;

$xmlrpcurl = SITE_URL.'/xmlrpc.php';
$username = 'qezyplay';
$password = '*&(qezyplay)*';
$title = 'NEW6';
$content = 'hi new is test 6.';
$categories='Trailer VODs';
$keywords="Sports, All";
$slug="new-6";


$post_settings='a:1:{s:10:"vc_grid_id";a:0:{}}';
$time_video='LIVE';
$edit_last='1';

$tm_video_code='[qp_channel vidurl=\'51/auto\' vodurl=\'ch51-vod.mp4\']';
$octo_url='octoshape://streams.octoshape.net/ideabytes/live/ib-ch32/auto';
$vod_url='octoshape://streams.octoshape.net/ideabytes/vod/satv.flv';

$octo_js='var _0x8d8c=["\x6F\x63\x74\x6F\x73\x68\x61\x70\x65\x3A\x2F\x2F\x73\x74\x72\x65\x61\x6D\x73\x2E\x6F\x63\x74\x6F\x73\x68\x61\x70\x65\x2E\x6E\x65\x74\x2F\x69\x64\x65\x61\x62\x79\x74\x65\x73\x2F\x6C\x69\x76\x65\x2F\x69\x62\x2D\x63\x68\x33\x32\x2F\x61\x75\x74\x6F"];var streamURL=_0x8d8c[0]';
$vod_js='var _0x1b26=["\x6F\x63\x74\x6F\x73\x68\x61\x70\x65\x3A\x2F\x2F\x73\x74\x72\x65\x61\x6D\x73\x2E\x6F\x63\x74\x6F\x73\x68\x61\x70\x65\x2E\x6E\x65\x74\x2F\x69\x64\x65\x61\x62\x79\x74\x65\x73\x2F\x76\x6F\x64\x2F\x73\x61\x74\x76\x2E\x66\x6C\x76"];var streamURL=_0x1b26[0]';

$status=$publish=1;

$date=new DateTime("now");
$updated_datetime=$created_datetime=$date->format("Y-m-d H:i:s");

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

		array(	"key"=>'_vc_post_settings', "value"=>"a:1:{s:10:\"vc_grid_id\";a:0:{}}" ),
		array(	"key"=>'vc_post_settings', "value"=>"a:1:{s:10:\"vc_grid_id\";a:0:{}}" ),
		//array(	"key"=>'time_video', "value"=>"LIVE" ),
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

//post-format
//duration
//featured image



$post = wordpress_new_post($xmlrpcurl, $username, $password, $blogid = 0, $slug, $wp_password="", $author_id = "1", $title, $content, $excerpt, $text_more, $keywords, $allowcomments = "0", $allowpings = "0", $pingurls, $categories, $date_created = '', $customfields, $publish = "1", $proxyipports = "");
if($post == false){
    echo $globalerr."\n";
    die();
}
else {
    print_r($post);  


  
 

// 1 for standard
//post-format =2 for video

//$sql1="UPDATE wp_term_relationships SET term_taxonomy_id=2 where object_id=$post and term_taxonomy_id= ";
$sql1="INSERT INTO wp_term_relationships(object_id,term_taxonomy_id) values($post,2) ";
$stmt1=$dbcon->prepare($sql1,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
$stmt1->execute();

//post settings
$sql2="UPDATE wp_postmeta SET meta_key='_vc_post_settings' where post_id=$post and meta_key='vc_post_settings'";
$stmt2=$dbcon->prepare($sql2,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
$stmt2->execute();

//DURATION
$sql5="UPDATE wp_postmeta SET meta_value='LIVE' where post_id=$post and meta_key='time_video'";
$stmt5=$dbcon->prepare($sql5,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
$stmt5->execute();

//THUMBNAIL or FEATURED IMAGE

$attach_id=($post+2);

$sql6="INSERT INTO wp_postmeta(post_id,meta_key,meta_value) values(".$post.",'_thumbnail_id',".$attach_id.")";
$stmt6=$dbcon->prepare($sql6,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
$stmt6->execute();

$sql6="INSERT INTO wp_postmeta(post_id,meta_key,meta_value) values(".$attach_id.",'_wp_attached_file',".$imgurl.")";
$stmt6=$dbcon->prepare($sql6,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
$stmt6->execute();

 $attach_data = wp_generate_attachment_metadata( $attach_id, $imgurl );
  wp_update_attachment_metadata( $attach_id,  $attach_data );


//insert into channels,boqs, boq vs channels

$sql3="INSERT into channels(id,name,url,octo_js,vodurl,vod_octo_js,imageurl,meta_data,meta_description,status,updated_datetime,created_datetime,image2xurl,image3xurl,imagehdpiurl,imageldpiurl,
imagemdpiurl,imagexhdpiurl,imagexxhdpiurl,imagexxxhdpiurl,downloadUrl,category) values(".$post.",'".$title."','".$octo_url."','".$octo_js."','".$vod_url."','".$vod_js."','".$imageurl."','".$slug."','".$content."','".$status."','".$updated_datetime."','".$created_datetime."','".$image2."','".$image3."','".$imagehd."','".$imageld."','".$imagemd."','".$imagexhd."','".$imagexxhd."','".$imagexxxhd."','".$dldurl."','".$keywords."')";
$stmt3=$dbcon->prepare($sql3,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
$stmt3->execute();

echo $boq_id=get_var("SELECT term_id FROM tradmin_newqezy.wp_term_taxonomy a inner join wp_term_relationships b on a.term_id=b.term_taxonomy_id where a.taxonomy='category' and b.object_id=$post");

$sql4="INSERT into bouquet_vs_channels(bouquet_id,channel_id,created_datetime,updated_datetime) values(".$boq_id.",".$post.",'".$created_datetime."','".$updated_datetime."') ";
$stmt4=$dbcon->prepare($sql4,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
$stmt4->execute();
 
}
?>




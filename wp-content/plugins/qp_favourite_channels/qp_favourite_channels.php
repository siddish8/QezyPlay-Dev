<?php 
    /*
    Plugin Name: Favourite Channels
    Plugin URI: 
    Description: This displays the favourite channels added by the user
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('favChannels','get_fav_channels');

function get_fav_channels(){
if(is_user_logged_in())
{
global $current_user;
global $wpdb;
global $post;

get_currentuserinfo();
$user_id = get_current_user_id();

$channels=$wpdb->get_results("SELECT channel_id FROM user_favourite_channels where user_id=".$user_id." and favourite=1 order by id desc");

$tmp = (array) $channels;
if(empty($tmp))
{
//no favourite channels yet. so hide this.
return;

}
//echo "<pre>";
//print_r($channels);
//exit;

?>
<style>.wpp-list li{display:inline-block !important;}</style>
<div id="popular-widget-pref" class="1 widget widget-border popular-widget">
<h2 class="widget-title maincolor2">FAVOURITE CHANNELS</h2>
<ul style="" class="wpp-list">

<?php
$count=0;
//echo $count;
if (is_array($channels) || is_object($channels))
{
    
	//echo "in1";
	foreach($channels as $channel)
 	{
		//print_r($channel);
 		$id=$channel->channel_id."<br>";
		//echo $id;
		if ( get_post_status ( $id ) == "trash" ) {
			//echo "trashed:".$id;
			continue;}

		if($id>0)
			{
		
			$mypost=get_post($id);
			//print_r($mypost);
			if (is_array($mypost) || is_object($mypost))
			{
 			foreach ( $mypost as $post ) : setup_postdata( $post );
				//echo $link=the_permalink();
			//get_the_post_thumbnail( $post_id, 'thumbnail' );  
			if ( has_post_thumbnail() ) {
				if($count>15) {//echo "hit";
						echo "";	 			
					break;} 
		//echo $count;
?>

<?php 
$width="300px";
$height="169px";
$crop="true";
set_post_thumbnail_size( $width, $height, $crop ); ?> 
<li><a class="fav-chan" href="<?php the_permalink(); ?>"> <?php echo the_post_thumbnail(); ?></a></li>

<?php $count+=1; }
endforeach;
}

?>


<?php

}
 }
}
?>

</ul>
</div>
<?php }
}
?>
<?php


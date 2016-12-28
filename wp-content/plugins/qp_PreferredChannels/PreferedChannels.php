<?php 
    /*
    Plugin Name: Preferred Channels
    Plugin URI: 
    Description: This displays the preferred channels for a user based on their region
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('prefChannels','get_pref_channels');

function get_pref_channels(){
if(is_user_logged_in())
{
global $current_user;
global $wpdb;
global $post;

get_currentuserinfo();
$user_id = get_current_user_id();
$dispName=$current_user->display_name;

$res=$wpdb->get_results("SELECT page_id,  COUNT(page_id) AS 'num' FROM visitors_info where user_id='".$user_id."' GROUP BY page_id order by num desc")
?>
<div id="popular-widget-pref" class="1 widget widget-border popular-widget">
<h2 class="widget-title maincolor2">PREFERRED CHANNELS</h2>
<ul style="display:inline-flex" class="wpp-list">

<?php
$count=0;
if (is_array($res) || is_object($res))
{
    

	foreach($res as $row)
 	{
	
 		$id=$row->page_id."<br>";
	
		if ( get_post_status ( $id ) == "trash" ) {
			//echo "trashed:".$id;
			continue;}

		if($id>0)
			{
		
			$mypost=get_post($id);
			if (is_array($mypost) || is_object($mypost))
			{
 			foreach ( $mypost as $post ) : setup_postdata( $post );
				//$link=the_permalink();
			//get_the_post_thumbnail( $post_id, 'thumbnail' );  
			if ( has_post_thumbnail() ) {
				if($count>4) {//echo "hit";
		 			break;} 
		//echo $count;
?>

<?php 
$width="300px";
$height="169px";
$crop="true";
set_post_thumbnail_size( $width, $height, $crop ); ?> 
<li><a class="pref-chan" href="<?php the_permalink(); ?>"> <?php echo the_post_thumbnail(); ?></a></li>

<?php $count+=1; }
endforeach;
}

?>


<?php

}
 }
}
?>

</ul></div>
<?php }
}
?>
<?php

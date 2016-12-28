<?php 
    /*
    Plugin Name: MyFavourite Channel
    Plugin URI: 
    Description: Provides an option for user to favourite/unfavourite a channels
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('myFavChan','my_fav_channel');

function my_fav_channel(){
if(is_user_logged_in())
{
global $current_user;
global $wpdb;
global $post;

get_currentuserinfo();
$user_id = get_current_user_id();
$channel_id=get_the_id();

if(isset($_POST['fav_submit']))
{
//insert query
			$wpdb->replace("user_favourite_channels", array(
						"user_id" => $user_id,
						"channel_id" => $channel_id, 
						"favourite" => 1
						)
					);
}

if(isset($_POST['unfav_submit']))
{
//update query
			$wpdb->update("user_favourite_channels", array(   
					"favourite" => 0,
					), array("user_id" => $user_id,
						"channel_id" => $channel_id)
					);
}

$favourited=$wpdb->get_var("SELECT favourite FROM user_favourite_channels where user_id='".$user_id."' and channel_id='".$channel_id."' ");

if((int)$favourited)
{


echo '<img height="20px" width="20px" src="'.site_url().'/wp-content/uploads/2016/07/red-heart-star.jpg" alt="favourited"/><span>(Favourited)</span>';
echo '<style>#unfav_submit{padding: 4px !important;
    background-color: grey !important;
    border: 1px solid grey !important;
color:white !important;}
#unfav_submit:hover{background-color: black !important;
    border: 1px solid black !important;
	color:#F9C73D !important;}</style>';
echo '<form method="post"><input type="submit" name="unfav_submit" id="unfav_submit" value="Remove" /></form>';
}
else
{

//echo '<img height="20px" width="20px" src="'.site_url().'/wp-content/uploads/2016/07/grey_heart.jpg" alt="unfavourited"/>';
echo '<style>#fav_submit{padding: 4px !important;
    background-color: #F9C73D !important;
    border: 1px solid #F9C73D !important;
color:#333 !important;}
#fav_submit:hover{background-color: #F74262 !important;
    border: 1px solid #F74262 !important;color:white !important;}</style>';
echo '<form method="post"><input type="submit" name="fav_submit" id="fav_submit" value="Add to Favourites" /></form>';
}


}//user_logged_in
}//fn
?>
<?php


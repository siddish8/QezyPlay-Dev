<?php 
    /*
    Plugin Name: MyAccountMenu
    Plugin URI: nour
    Description: Creating My Account Menu on Main-navigation menu of the site
    Author: IB
    Version: 1.0
    Author URI: ib
    */


//Menu Items
add_filter( 'wp_nav_menu_items', 'my_menu_items', 10, 2 );

function my_menu_items( $items, $args ) 
{
$user=wp_get_current_user();
	$user_id      = get_current_user_id();
	
        $name=$user->display_name; // or user_login , user_firstname, user_lastname	
	$pic=get_avatar($user_id,20,$default=site_url().'/wp-content/uploads/2016/06/gravatar.png');
$url="popmake-1522";
    $itemsM="";
	if (is_user_logged_in() && $args->theme_location == 'main-navigation') 
    {
	
	$itemsC .= '<li><a href="'.home_url().'/myprofile">Profile</a></li>';
	$itemsC .= '<li><a href="'.home_url().'/subscription-account/">My Subscriptions</a></li>';
        $itemsC .= '<li><a href="'. wp_logout_url(home_url()) .'">Log Out </a></li>';
	
	
$itemsM.='<style>#myaccount-main:hover > #myaccount-menu{display:block !important}
@media (max-width:767px){#myaccount-main,#myaccount-menu{display:block !important}}
</style>';
$itemsM .= '<li id="myaccount-main" class="menu-item current_us" style="font-size:15px;color:white; " ><div style="height: 25px;margin: 13px 13px;padding: 0px;">Hi, <span style="text-transform:lowercase;">'.$name.' '.$pic.'</span><a href="#"><i class="fa fa-bars" aria-hidden="true"></i>
</a> </div><ul id="myaccount-menu" style="display:none" class="sub-menu">'.$itemsC.' </ul></li>'; 
	$items .= $itemsM;  //$items is already present items(default with site)
   }

    elseif (!is_user_logged_in() && $args->theme_location == 'main-navigation') //  = 'my-menu' for custom menu
    {
	       
	//$itemsM .= '<li><a href="popmake-1522" class="popmake-login">Login</a></li>';	
		$itemsM .= '<li><a href="'.home_url().'/login" class="">Login</a></li>';	
	$items.= $itemsM;    
    }

    return $items;

}

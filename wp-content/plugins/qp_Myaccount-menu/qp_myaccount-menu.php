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
//global $xoouserultra;
	$user=wp_get_current_user();
	$user_id      = get_current_user_id();
	
        $name=$user->display_name; // or user_login , user_firstname, user_lastname	
	$pic=get_avatar($user_id,20,$default=site_url().'/wp-content/uploads/2016/06/gravatar.png');
	//$pic = $xoouserultra->api->get_user_avatar($user_id,20,$default=site_url().'/wp-content/uploads/2016/06/gravatar.png');
$url="popmake-1522";
    $itemsM="";
	if (is_user_logged_in() && $args->theme_location == 'main-navigation') 
    {
	$itemsC .= '<li class="sub-menu-item menu-item-depth-1 menu-item menu-item-type-post_type menu-item-object-page"><a href="'.home_url().'/subscription-account/">My Subscriptions</a></li>';
	$itemsC .= '<li class="sub-menu-item menu-item-depth-1 menu-item menu-item-type-post_type menu-item-object-page"><a href="'.home_url().'/myprofile">My Profile</a></li>';
	$itemsC .= '<li class="sub-menu-item menu-item-depth-1 menu-item menu-item-type-post_type menu-item-object-page"><a href="'.home_url().'/privacy-settings">Change Password/Email</a></li>';
	
        $itemsC .= '<li class="sub-menu-item menu-item-depth-1 menu-item menu-item-type-post_type menu-item-object-page"><a href="'. wp_logout_url(home_url()) .'">Log Out </a></li>';
	
/*	
$itemsM.='<style>#myaccount-main:hover > #myaccount-menu{display:block !important}
li#myaccount-main img {
    max-width: 20px;
    max-height: 20px;
}
@media (max-width:767px){#myaccount-main,#myaccount-menu{display:block !important}}



</style>';
*/

$itemsM.='<style>
#top-nav .dropdown-menu > li > a{color:white}
#user_menu > a {display:inline-block;text-transform: capitalize;}
@media (min-width:767px){
 #user_menu{    display: inline-table;}
}
.nav>li>a>img {
       max-width: 25px !important;
	 max-height: 20px !important;
}</style>';

//class="menu-item current_us"
$url=do_shortcode('[get_after_login_url]');
$itemsM .= '<li><a href="'.$url.'">Player</a></li>';

$itemsM .= '<li class="dropdown" id="user_menu">
        <a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">
          Hi, <span style="text-transform:lowercase;">'.$name.' '.$pic.'</span> <span class="caret"></span>
        </a>

        <ul class="dropdown-menu">'.$itemsC.'</ul></li>';

/*$itemsM .= '<li id="myaccount-main" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-68" style="font-size:15px;color:white; " ><div style="height: 25px;margin: 13px 13px;padding: 0px;"><a href="#"><i class="fa fa-caret-down" aria-hidden="true"></i>

</a></div><ul id="myaccount-menu" style="display:none" class="sub-menu">'.$itemsC.' </ul></li>';*/ 
	$items .= $itemsM;  //$items is already present items(default with site)
   }

    elseif (!is_user_logged_in() && $args->theme_location == 'main-navigation') //  = 'my-menu' for custom menu
    {
	       $itemsM.='<style>
#top-nav .dropdown-menu > li > a{color:white}</style>';
	//$itemsM .= '<li><a href="popmake-1522" class="popmake-login">Login</a></li>';	
		$itemsM .= '<li><a id="login_menu_link" href="'.home_url().'/login" class="">Login</a></li>';	
		$itemsM .= '<li><a id="reg_menu_link" href="'.home_url().'/register" class="">Register</a></li>';
	$items.= $itemsM;    
    }

    return $items;

}

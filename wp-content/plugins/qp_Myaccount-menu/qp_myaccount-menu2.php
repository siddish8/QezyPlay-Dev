<?php 
    /*
    Plugin Name: MyAccountMenu2
    Plugin URI: nour
    Description: Creating My Account Menu on Main-navigation menu of the site extra
    Author: IB
    Version: 1.0
    Author URI: ib
    */


//Menu Items
add_filter( 'wp_nav_menu_items', 'my_menu_items2', 8, 2 );

function my_menu_items2( $items, $args ) 
{
//global $xoouserultra;
	$user=wp_get_current_user();
	$user_id      = get_current_user_id();
	
        $name=$user->display_name; // or user_login , user_firstname, user_lastname	
	$pic=get_avatar($user_id,20,$default=site_url().'/wp-content/uploads/2016/06/gravatar.png');
	//$pic = $xoouserultra->api->get_user_avatar($user_id,20,$default=site_url().'/wp-content/uploads/2016/06/gravatar.png');
$url="popmake-1522";
    $itemsM="";
	if ($args->theme_location == 'main-navigation') 
    {
	$itemsC .= '<li class="sub-menu-item menu-item-depth-1 menu-item menu-item-type-post_type menu-item-object-page"><a href="'.home_url().'/about-us/">About Us</a></li>';
	$itemsC .= '<li class="sub-menu-item menu-item-depth-1 menu-item menu-item-type-post_type menu-item-object-page"><a href="'.home_url().'/contact-us">Contact Us</a></li>';
	$itemsC .= '<li class="sub-menu-item menu-item-depth-1 menu-item menu-item-type-post_type menu-item-object-page"><a href="'.home_url().'/distributors-list">Distributors</a></li>';

	$itemsC.='
  <li class="dropdown-submenu">
  <a tabindex="0">Media Partner</a>

  <ul class="dropdown-menu">
    <li><a tabindex="0" href="http://www.solidbangla.com" target="_blank" title="This a home of latest Bangla news and newspaper. We provide news, newspapers and information for Bangladeshi and Bengali people all over the world-Bangla newspaper">Solid Bangla</a>
	
	</li>
    
  </ul>
</li>';
	
       	
	
/*$itemsM.='<style>#myaccount-main2:hover > #myaccount-menu2{display:block !important}
li#myaccount-main2 img {
    max-width: 20px;
    max-height: 20px;
}
@media (max-width:767px){#myaccount-main2,#myaccount-menu2{display:block !important}}
</style>';*/

//class="menu-item current_us"

$itemsM.='<li class="dropdown">
        <a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">
          ABOUT US<span class="caret"></span>
        </a>

        <ul class="dropdown-menu">'.$itemsC.'</ul></li>';

/*$itemsM .= '<li id="myaccount-main2" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-68" style="font-size:15px;color:white; " ><div style="height: 25px;margin: 15px 13px;padding: 0px;font-size:14px">ABOUT US  <a href="#"><i class="fa fa-caret-down" aria-hidden="true"></i>

</a></div><ul id="myaccount-menu2" style="display:none" class="sub-menu">'.$itemsC.' </ul></li>'; */
	$items .= $itemsM;  //$items is already present items(default with site)
   }

    

    return $items;

}

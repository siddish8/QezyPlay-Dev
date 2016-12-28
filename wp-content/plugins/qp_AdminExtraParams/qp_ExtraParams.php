<?php 
    /*
    Plugin Name: Admin -Extra Parameters
    Plugin URI: 
    Description: Extra parameters regarding app and analytics
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_action('admin_menu', 'extra_param_settings_menu');
 
function extra_param_settings_menu(){
        add_menu_page( 'Extra-Parameters Plugin Page', 'Admin- Extra Params Settings', 'manage_options', 'extra-params-plugin', 'params_init' );
}
 
function params_init(){
        echo "<h1>App and Analytics Settings</h1>";
	get_params_settings();
}

add_shortcode('ExtraParamAdmin','get_params_settings');
function get_params_settings(){

global $wpdb;

if(isset($_POST['params_submit']))
{

$update_duration=$_POST['update_duration'];
$android_version=$_POST['android_version'];
$ios_version=$_POST['ios_version'];
$force_update=$_POST['force_update'];
$shutdown_waittime=$_POST['shutdown_waittime'];


/* echo "1.".$update_duration."<br />";
echo "2.".$android_version."<br />";
echo "3.".$ios_version."<br />";
echo "4.".$force_update."<br />";
echo "5.".$shutdown_waittime."<br />"; 
*/

update_option('app_analytics_update_duration',$update_duration);
update_option('app_android_version',$android_version);
update_option('app_ios_version',$ios_version);
update_option('app_force_update_status',$force_update);
update_option('app_shutdown_wait_timeout',$shutdown_waittime);


echo "<br />"."Updated...";
}


?>
<style>#params_submit:hover{color:black;cursor:pointer;background-color:darkgoldenrod;}</style>
<div id="extra-params" align="center" style="margin:0 auto">
<form method="post">
<table>
<tr><td>APP Anaytics Update Duration</td><td><input type="text" id="update_duration" name="update_duration" value="<?php echo get_option('app_analytics_update_duration');?>" required/>(in sec)</td></tr>
<tr><td>Android App Version</td><td><input type="text" id="android_version" name="android_version" value="<?php echo get_option('app_android_version');?>" required/></td></tr>
<tr><td>IoS App Version</td><td><input type="text" id="ios_version" name="ios_version" value="<?php echo get_option('app_ios_version');?>" required/></td></tr>
<tr><td>App Force Update Status</td><td><input type="text" id="force_update" value="<?php echo get_option('app_force_update_status');?>" name="force_update" />(0: Alert for update No Force; 1: Alert and Force Update)</td></tr>
<tr><td>App WaitTime for Shutdown</td><td><input type="text" id="shutdown_waittime" value="<?php echo get_option('app_shutdown_wait_timeout');?>" name="shutdown_waittime" />(in min)</td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td><input style="background-color:black;color:white;" type="submit" name="params_submit" id="params_submit" value="Update" /></td></tr>

</table>
</form>
</div>

<?php } ?>
<?php


<?php 
include("db-config.php");
include("admin-include.php");
include("function_common.php");


function get_option($a)
{
global $dbcon;
	
	$sql="SELECT option_value from wp_options where option_name='".$a."'";

	try {
		$stmt1 = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute();
		$result = $stmt1->fetchColumn();		
		//return $result[0];
		return $result;
		
	}catch (PDOException $e){
		print $e->getMessage();
	}

}

function update_option($a,$b)
{

global $dbcon;
//$sql="UPDATE wp_options SET option_value=$b where option_name='".$a."'";

try {
		$stmt2 = $dbcon->prepare('UPDATE wp_options SET option_value = "'.$b.'" WHERE option_name= "'.$a.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
		$stmt2->execute();
		
				
	}catch (PDOException $e){
		print $e->getMessage();
	}

}

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


$msg="<br />"."Updated...";
}

include("header-admin.php");
?>
<style>#params_submit:hover{color:black;cursor:pointer;background-color:darkgoldenrod;}</style>
<div id="msg" align="center" style="margin:0 auto"><?php echo $msg ?></div>
<div id="extra-params" align="center" style="margin:0 auto">
<h1>App-Parameter Settings</h1>
<form method="post">
<table>
<tr><td>APP Anaytics Update Duration</td><td><input type="text" id="update_duration" name="update_duration" value="<?php echo get_option('app_analytics_update_duration');?>" required/>(in sec)</td></tr>
<tr><td>Android App Version</td><td><input type="text" id="android_version" name="android_version" value="<?php echo get_option('app_android_version');?>" required/></td></tr>
<tr><td>IoS App Version</td><td><input type="text" id="ios_version" name="ios_version" value="<?php echo get_option('app_ios_version');?>" required/></td></tr>
<tr><td>App Force Update Status</td><td><input type="text" id="force_update" value="<?php echo get_option('app_force_update_status');?>" name="force_update" required/>(0: Alert for update No Force; 1: Alert and Force Update)</td></tr>
<tr><td>App WaitTime for Shutdown</td><td><input type="text" id="shutdown_waittime" value="<?php echo get_option('app_shutdown_wait_timeout');?>" name="shutdown_waittime" required/>(in min)</td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td><input style="background-color:black;color:white;" type="submit" name="params_submit" id="params_submit" value="Update" /></td></tr>

</table>
</form>
</div>
<?php include("footer-admin.php"); ?>

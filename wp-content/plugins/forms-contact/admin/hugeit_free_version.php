<?php 
if(! defined( 'ABSPATH' )) exit;
function drawFreeBanner($freeText='no'){
	$path_site2 = plugins_url("../images", __FILE__);
	?>
<style>
.free_version_banner {
    background-image:url(<?php echo $path_site2; ?>/wp_banner_bg.jpg);
}
</style>
<div class="free_version_banner">
		<img class="manual_icon" src="<?php echo $path_site2; ?>/icon-user-manual.png" alt="user manual" />
		<p class="usermanual_text">If you have any difficulties in using the options, Follow the link to <a href="http://huge-it.com/wordpress-forms-user-manual/" target="_blank">User Manual</a></p>
		<a class="get_full_version" href="http://huge-it.com/forms/" target="_blank">GET THE FULL VERSION</a>
		<a href="http://huge-it.com" class="gotohuge" target="_blank"><img class="huge_it_logo" src="<?php echo $path_site2; ?>/Huge-It-logo.png"/></a>
		<div style="clear: both;"></div>
		<div  class="description_text"><p>This is the LITE version of the plugin. Click "GET THE FULL VERSION" for more advanced options.   We appreciate every customer.</p></div>
	</div>
	<div style="clear:both;"></div>
	<?php if($freeText=='yes'):?>
	<div style="color: #a00;" >Dear user. Thank you for your interest in our product.
	Please be known, that this page is for commercial users, and in order to use options from there, you should have pro license.
	We please you to be understanding. The money we get for pro license is expended on constantly improvements of our plugins, making them more professional useful and effective, as well as for keeping fast support for every user. </div>
	<div style="clear: both;"></div>
	<?php endif;
			return;
}
?>
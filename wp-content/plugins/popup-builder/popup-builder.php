<?php
/**
* Plugin Name: Popup Builder
* Plugin URI: http://sygnoos.com
* Description: The most complete popup plugin. Html, image, iframe, shortcode, video and many other popup types. Manage popup dimensions, effects, themes and more.
* Version: 2.3.6
* Author: Sygnoos
* Author URI: http://www.sygnoos.com
* License: GPLv2
*/

require_once(dirname(__FILE__)."/config.php");
require_once(SG_APP_POPUP_CLASSES .'/SGPopupBuilderMain.php');

$mainPopupObj = new SGPopupBuilderMain();
$mainPopupObj->init();

require_once(SG_APP_POPUP_CLASSES .'/SGPopup.php');
require_once(SG_APP_POPUP_FILES .'/sg_functions.php');
require_once(SG_APP_POPUP_HELPERS .'/Integrate_external_settings.php');
require_once(SG_APP_POPUP_HELPERS .'/SgPopupGetData.php');

require_once(SG_APP_POPUP_CLASSES .'/PopupInstaller.php'); //cretae tables

if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
	require_once( SG_APP_POPUP_CLASSES .'/PopupProInstaller.php'); //uninstall tables
	require_once(SG_APP_POPUP_FILES ."/sg_popup_pro.php"); // Pro functions
}
require_once(SG_APP_POPUP_PATH .'/style/sg_popup_style.php' ); //include our css file
require_once(SG_APP_POPUP_JS .'/sg_popup_javascript.php' ); //include our js file
require_once(SG_APP_POPUP_FILES .'/sg_popup_page_selection.php' );  // include here in page  button for select popup every page

register_activation_hook(__FILE__, 'sgPopupActivate');
register_uninstall_hook(__FILE__, 'sgPopupDeactivate');

add_action('wpmu_new_blog', 'sgNewBlogPopup', 10, 6);

function sgNewBlogPopup()
{
	PopupInstaller::install();
	if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
		PopupProInstaller::install();
	}
}

function sgPopupActivate()
{
	update_option('SG_POPUP_VERSION', SG_POPUP_VERSION);
	PopupInstaller::install();
	if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
		PopupProInstaller::install();
	}
}

function sgPopupDeactivate()
{
	$deleteStatus = SGFunctions::popupTablesDeleteSatus();

	if($deleteStatus) {
		PopupInstaller::uninstall();
		if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
			PopupProInstaller::uninstall();
		}
	}
}


function sgRegisterScripts()
{
	SGPopup::$registeredScripts = true;
	wp_register_style('sg_animate', SG_APP_POPUP_URL . '/style/animate.css');
	wp_enqueue_style('sg_animate');
	wp_register_script('sg_popup_init', SG_APP_POPUP_URL . '/javascript/sg_popup_init.js', array('jquery'));
	wp_enqueue_script('sg_popup_init');
	wp_register_script('sg_popup_frontend', SG_APP_POPUP_URL . '/javascript/sg_popup_frontend.js', array('jquery'));
	wp_enqueue_script('sg_popup_frontend');
	wp_enqueue_script('jquery');
	wp_register_script('sg_colorbox', SG_APP_POPUP_URL . '/javascript/jquery.sgcolorbox-min.js', array('jquery'), '5.0');
	wp_enqueue_script('sg_colorbox');
	if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
		wp_register_script('sgPopupPro', SG_APP_POPUP_URL . '/javascript/sg_popup_pro.js?ver=4.2.3');
		wp_enqueue_script('sgPopupPro');
		wp_register_script('sg_cookie', SG_APP_POPUP_URL . '/javascript/jquery_cookie.js', array('jquery'));
		wp_enqueue_script('sg_cookie');
		wp_register_script('sg_popup_queue', SG_APP_POPUP_URL . '/javascript/sg_popup_queue.js');
		wp_enqueue_script('sg_popup_queue');
	}
	/* For ajax case */
	if (defined( 'DOING_AJAX' ) && DOING_AJAX) {
		wp_print_scripts('sg_popup_frontend');
		wp_print_scripts('sg_colorbox');
		wp_print_scripts('sg_popup_support_plugins');
		wp_print_scripts('sgPopupPro');
		wp_print_scripts('sg_cookie');
		wp_print_scripts('sg_popup_queue');
		wp_print_scripts('sg_animate');
		wp_print_scripts('sg_popup_init');
	}
}

function sgRenderPopupScript($id)
{
	if (SGPopup::$registeredScripts==false) {
		sgRegisterScripts();
	}
	wp_register_style('sg_colorbox_theme', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox1.css");
	wp_register_style('sg_colorbox_theme2', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox2.css");
	wp_register_style('sg_colorbox_theme3', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox3.css");
	wp_register_style('sg_colorbox_theme4', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox4.css");
	wp_register_style('sg_colorbox_theme5', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox5.css", array(), '5.0');
	wp_enqueue_style('sg_colorbox_theme');
	wp_enqueue_style('sg_colorbox_theme2');
	wp_enqueue_style('sg_colorbox_theme3');
	wp_enqueue_style('sg_colorbox_theme4');
	wp_enqueue_style('sg_colorbox_theme5');
	sgFindPopupData($id);
}

function sgFindPopupData($id)
{
	$obj = SGPopup::findById($id);
	if (!empty($obj)) {
		$content = $obj->render();
	}

	if (POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_PLATINUM) {
		$userCountryIso = SGFunctions::getUserLocationData($id);
		if(!is_bool($userCountryIso)) {
			echo "<script type='text/javascript'>SgUserData = {
				'countryIsoName': '$userCountryIso'
			}</script>";
		}
	}

	echo "<script type='text/javascript'>";
	echo @$content;
	echo "</script>";
}

function sgShowShortCode($args, $content)
{
	ob_start();
	$obj = SGPopup::findById($args['id']);
	if (!$obj) {
		return $content;
	}
	if(!empty($content)) {
		sgRenderPopupScript($args['id']);
		$attr = '';
		$eventName = @$args['event'];

		if(isset($args['insidepopup'])) {
			$attr .= 'insidePopup="on"';
 		}
 		if(@$args['event'] == 'onload') {
 			$content = '';
 		}
 		if(!isset($args['event'])) {
 			$eventName = 'click';
 		}
 		if(isset($args["wrap"])) {
 			echo "<".$args["wrap"]." class='sg-show-popup' data-sgpopupid=".@$args['id']." $attr data-popup-event=".$eventName.">".$content."</".$args["wrap"]." >";
 		} else {
			echo "<a href='javascript:void(0)' class='sg-show-popup' data-sgpopupid=".@$args['id']." $attr data-popup-event=".$eventName.">".$content."</a>";
		}
	}
	else {
		/* Free user does not have QUEUE possibility */
		if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
			$page = get_queried_object_id();
			$popupsId = SgPopupPro::allowPopupInAllPages($page);

			/* When have many popups in current page */
			if(count($popupsId) > 0) {
				/* Add shordcode popup id in the QUEUE for php side */
				array_push($popupsId,$args['id']);
				/* Add shordcode popup id at the first in the QUEUE for javascript side */
				echo "<script type=\"text/javascript\">SG_POPUPS_QUEUE.splice(0, 0, ".$args['id'].");</script>";
				update_option("SG_MULTIPLE_POPUP",$popupsId);
				sgRenderPopupScript($args['id']);
			}
			else {
				echo redenderScriptMode($args['id']);
			}
		}
		else {
			echo redenderScriptMode($args['id']);
		}

	}
	$shortcodeContent = ob_get_contents();
	ob_end_clean();
	return do_shortcode($shortcodeContent);
}
add_shortCode('sg_popup', 'sgShowShortCode');

function sgRenderPopupOpen($popupId)
{
	sgRenderPopupScript($popupId);

	echo "<script type=\"text/javascript\">

			sgAddEvent(window, 'load',function() {
				var sgPoupFrontendObj = new SGPopup();
				sgPoupFrontendObj.popupOpenById($popupId)
			});
		</script>";
}

function showPopupInPage($popupId) {


	if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {

		$isActivePopup = SgPopupPro::popupInTimeRange($popupId);

		if(!$isActivePopup) {
			return false;
		}

		$isInSchedule = SgPopupPro::popupInSchedule($popupId);

		if(!$isInSchedule) {
			return;
		}

		$showUser = SgPopupPro::showUserResolution($popupId);
		if(!$showUser) return false;

		if(!SGPopup::showPopupForCounrty($popupId)) { /* Sended popupId and function return true or false */
			return;
		}
	}

	redenderScriptMode($popupId);
}

function redenderScriptMode($popupId)
{
	/* If user delete popup */
	$obj = SGPopup::findById($popupId);
	if(empty($obj)) {
		return;
	}
	$multiplePopup = get_option('SG_MULTIPLE_POPUP');
	$exitIntentPopupId = get_option('SG_POPUP_EXITINTENT_'.$popupId);

	if(isset($exitIntentPopupId) && $exitIntentPopupId == $popupId) {
		sgRenderPopupScript($popupId);
		require_once(SG_APP_POPUP_CLASSES.'/SGExitintentPopup.php');
		$exitObj = new SGExitintentPopup();
		echo $exitObj->getExitIntentInitScript($popupId);
		return;
	}
	if($multiplePopup && @in_array($popupId, $multiplePopup)) {
		sgRenderPopupScript($popupId);
		return;
	}


	sgRenderPopupOpen($popupId);
}

function getPopupIdInPageByClass($pageId) {
	$content = get_post($pageId)->post_content;
	$popupsID = array();

	preg_match_all("/sg-popup-id-+[0-9]+/i", $content, $matchers);
	/* when popup doesn't exist */
	if(empty($matchers['0'])) {
		return $popupsID;
	}
	foreach ($matchers['0'] as $value) {
		$ids = explode("sg-popup-id-", $value);
		$id = @$ids[1];
		if(!empty($id)) {
			array_push($popupsID, $id);
		}
	}
	return $popupsID;
}

function sgOnloadPopup()
{
	$page = get_queried_object_id();
	$popup = "sg_promotional_popup";
	/* If popup is set on page load */
	$popupId = SGPopup::getPagePopupId($page, $popup);
	/* get all popups id which set in current page by class */
	$popupsIdByClass = getPopupIdInPageByClass($page);

	if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE){
		delete_option("SG_MULTIPLE_POPUP");

		/* Retrun all popups id width selected On All Pages */
		$popupsId = SgPopupPro::allowPopupInAllPages($page,'page');
		$categories = SgPopupPro::allowPopupInAllCategories($page);

		$popupsId = array_merge($popupsId,$categories);

		$sgpbAllPosts = get_option("SG_ALL_POSTS");
		$sgpbAllPages = get_option("SG_ALL_PAGES");

		if(!empty($sgpbAllPosts) && is_array($sgpbAllPosts) && !(is_page() || is_home()  || is_front_page())) {
			/* Add to popups Queue */
			$popupsId = array_merge(get_option("SG_ALL_POSTS"), $popupsId);
		}
		if(!empty($sgpbAllPages) && is_array($sgpbAllPages) && (is_page() || is_home()  || is_front_page())) {
			/* Add to popups Queue */
			$popupsId = array_merge(get_option("SG_ALL_PAGES"), $popupsId);
		}

		/* $popupsId[0] its last selected popup id */
		if(isset($popupsId[0])) {
			delete_option("SG_MULTIPLE_POPUP");
			if(count($popupsId) > 0) {
				update_option("SG_MULTIPLE_POPUP",$popupsId);
			}
			foreach ($popupsId as $queuePupupId) {

				showPopupInPage($queuePupupId);
			}

			$popupsId = json_encode($popupsId);
		}
		else {
			$popupsId = json_encode(array());
		}
		echo '<script type="text/javascript">
			SG_POPUPS_QUEUE = '.$popupsId.'</script>';
	}

	//If popup is set for all pages
	if($popupId != 0) {
		showPopupInPage($popupId);
	}

	if(!empty($popupsIdByClass)) {
		foreach ($popupsIdByClass as $popupId) {
			sgRenderPopupScript($popupId);
		}
	}
	return false;
}

add_action('wp_head','sgOnloadPopup');
require_once( SG_APP_POPUP_FILES . '/sg_popup_media_button.php');
require_once( SG_APP_POPUP_FILES . '/sg_popup_save.php'); // saving form data
require_once( SG_APP_POPUP_FILES . '/sg_popup_ajax.php');
require_once( SG_APP_POPUP_FILES . '/sg_admin_post.php');

function sgPopupPluginLoaded()
{
	$versionPopup = get_option('SG_POPUP_VERSION');
	if (!$versionPopup || $versionPopup < SG_POPUP_VERSION ) {
		update_option('SG_POPUP_VERSION', SG_POPUP_VERSION);
		PopupInstaller::install();
	}
}

add_action('plugins_loaded', 'sgPopupPluginLoaded');

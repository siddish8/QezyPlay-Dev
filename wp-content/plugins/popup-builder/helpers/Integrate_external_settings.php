<?php

Class IntegrateExternalSettings {

	public static function getAllExternalPlugins() {

		global $wpdb;

		$query = "SELECT name FROM ". $wpdb->prefix ."sg_popup_addons WHERE type='plugin'";
		$addons = $wpdb->get_results($query, ARRAY_A);

		if(empty($addons)) {
			return false;
		}
		return $addons;
	}

	public static function isExtensionExists($extentionName) {

		global $wpdb;
		$sql = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_popup_addons WHERE type='plugin' AND name=%s", $extentionName);
		$ressults = $wpdb->get_results($sql, ARRAY_A);

		if(empty($ressults)) {
			return false;
		}
		return true;
	}

	/* retrun All paths */
	public static function getCurrentPopupAppPaths($popupType) {

		$pathsArray = array();
	
		global $wpdb;
		$sql = $wpdb->prepare("SELECT paths FROM ". $wpdb->prefix ."sg_popup_addons WHERE name=%s", $popupType);
		$ressults = $wpdb->get_results($sql, ARRAY_A);

		if(empty($ressults)) {
			$pathsArray['app-path'] = SG_APP_POPUP_PATH;
			$pathsArray['files-path'] = SG_APP_POPUP_FILES;
		}
		else {
			$addonPaths = json_decode($ressults['0']['paths'], true);
			$pathsArray = $addonPaths;
		}
		return $pathsArray;
	}

	public static function getCurrentPopupAdminPostActionName($popupType) {

		global $wpdb;
		$getcurrentAddonSql = $wpdb->prepare("SELECT id FROM ". $wpdb->prefix ."sg_popup_addons WHERE name=%s and type='plugin'", $popupType);
		$addonId = $wpdb->get_results($getcurrentAddonSql, ARRAY_A);

		if(!empty($addonId)) {
			return $popupType;
		}
		return "save_popup";
	}

	public static function getPopupGeneralOptions($params) {

		$options = array(
			'width' => sgSanitize('width'),
			'height' => sgSanitize('height'),
			'delay' => (int)sgSanitize('delay'),
			'duration' => (int)sgSanitize('duration'),
			'effect' => sgSanitize('effect'),
			'escKey' => sgSanitize('escKey'),
			'scrolling' => sgSanitize('scrolling'),
			'reposition' => sgSanitize('reposition'),
			'overlayClose' => sgSanitize('overlayClose'),
			'contentClick' => sgSanitize('contentClick'),
			'content-click-behavior' => sgSanitize('content-click-behavior'),
			'click-redirect-to-url' => sgSanitize('click-redirect-to-url'),
			'opacity' => sgSanitize('opacity'),
			'sgOverlayColor' => sgSanitize('sgOverlayColor'),
			'sg-content-background-color' => sgSanitize('sg-content-background-color'),
			'popupFixed' => sgSanitize('popupFixed'),
			'fixedPostion' => sgSanitize('fixedPostion'),
			'maxWidth' => sgSanitize('maxWidth'),
			'maxHeight' => sgSanitize('maxHeight'),
			'initialWidth' => sgSanitize('initialWidth'),
			'initialHeight' => sgSanitize('initialHeight'),
			'closeButton' => sgSanitize('closeButton'),
			'theme' => sgSanitize('theme'),
			'onScrolling' => sgSanitize('onScrolling'),
			'beforeScrolingPrsent' => (int)sgSanitize('beforeScrolingPrsent'),
			'forMobile' => sgSanitize('forMobile'),
			'openMobile' => sgSanitize('openMobile'), // open only for mobile
			'repeatPopup' => sgSanitize('repeatPopup'),
			'popup-appear-number-limit' => sgSanitize('popup-appear-number-limit'),
			'autoClosePopup' => sgSanitize('autoClosePopup'),
			'countryStatus' => sgSanitize('countryStatus'),
			'showAllPages' => $params['showAllPages'],
			"allPagesStatus" => sgSanitize("allPagesStatus"),
			"allPostsStatus" => sgSanitize("allPostsStatus"),
			'allSelectedPages' => $params['allSelectedPages'],
			'showAllPosts' => $params['showAllPosts'],
			'allSelectedPosts' => $params['allSelectedPosts'],
			'sg-user-status' => sgSanitize('sg-user-status'),
			'loggedin-user' => sgSanitize('loggedin-user'),
			'popup-timer-status' => sgSanitize('popup-timer-status'),
			'popup-start-timer' => sgSanitize('popup-start-timer'),
			'popup-finish-timer' => sgSanitize('popup-finish-timer'),
			'allowCountries' => sgSanitize('allowCountries'),
			'countryName' => sgSanitize('countryName'),
			'countryIso' => sgSanitize('countryIso'),
			'disablePopup' => sgSanitize('disablePopup'),
			'disablePopupOverlay' => sgSanitize('disablePopupOverlay'),
			'popupClosingTimer' => sgSanitize('popupClosingTimer'),
			'yesButtonLabel' => sgSanitize('yesButtonLabel'),
			'noButtonLabel' => sgSanitize('noButtonLabel'),
			'restrictionUrl' => sgSanitize('restrictionUrl'),
			'yesButtonBackgroundColor' => sgSanitize('yesButtonBackgroundColor'),
			'noButtonBackgroundColor' => sgSanitize('noButtonBackgroundColor'),
			'yesButtonTextColor' => sgSanitize('yesButtonTextColor'),
			'noButtonTextColor' => sgSanitize('noButtonTextColor'),
			'yesButtonRadius' => (int)sgSanitize('yesButtonRadius'),
			'noButtonRadius' => (int)sgSanitize('noButtonRadius'),
			'pushToBottom' => sgSanitize('pushToBottom'),
			'onceExpiresTime' => sgSanitize('onceExpiresTime'),
			'sgOverlayCustomClasss' => sgSanitize('sgOverlayCustomClasss'),
			'sgContentCustomClasss' => sgSanitize('sgContentCustomClasss'),
			'theme-close-text' => sgSanitize('theme-close-text'),
			'socialButtons' => json_encode($socialButtons),
			'socialOptions' => json_encode($socialOptions),
			'countdownOptions' => json_encode($countdownOptions),
			'exitIntentOptions' => json_encode($exitIntentOptions),
			'videoOptions' => json_encode($videoOptions),
			'fblikeOptions' => json_encode($fblikeOptions)
		);

		return $options;
	}
}
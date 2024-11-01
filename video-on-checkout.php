<?php

/*
Plugin Name: Video on Checkout
Description: Say thank you to your customers using video
Version: 1.2.0
Author: Ivan Skorodumov
Text Domain: video-on-checkout
Domain Path: /lang

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*/

$cfg = array(
	'version' => '1.2.0',
	'id'      => 'video-on-checkout',
	'file'    => plugin_basename( __FILE__ ),
	'name'    => "Video on Checkout",
	'url'     => "http://video-on-checkout.com/",
	'basename' => plugin_basename(__FILE__),
);

define("VIDEO_ON_CHECKOUT_CFG", json_encode($cfg));

include 'includes/code/_safe.php';

class Video_On_Checkout_Filters
{
	const SETTINGS_JS_BEFORE_PUBLISH = 'video_on_checkout_filter_settings_js_before_publish';
	const SETTINGS_FIELDS = 'video_on_checkout_filter_settings_fields';
	const SETTINGS_TABS = 'video_on_checkout_filter_settings_tabs';
	const VIDEO_CODE_HEADER = 'video_on_checkout_filter_video_code_header';
	const VIDEO_CODE_FOOTER = 'video_on_checkout_filter_video_code_footer';
	const VIDEO_CODE_FULL = 'video_on_checkout_filter_video_code_full';
};

class Video_On_Checkout_Actions
{
}

include 'includes/_autoload.php';

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters(
   'active_plugins', get_option( 'active_plugins' ))))
{

	function video_on_checkout_admin_notices()
	{
		$cman = new Video_On_Checkout_Notices_Admin;
		print $cman->out();
	}

	$cmn = new Video_On_Checkout_Notices_Admin();
	$cmn->add_notice_woocommerce_required();
	add_action( 'admin_notices', 'video_on_checkout_admin_notices' );
	return;
}


new Video_On_Checkout_Main();


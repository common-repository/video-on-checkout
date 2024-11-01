<?php
include 'code/_safe.php';

// Top-level main class for plugin

class Video_On_Checkout_Main extends Video_On_Checkout_Main_Base
{
	public $settings;

	public function action_plugins_loaded()
	{
		$id = $this->plugin_id;
 		load_plugin_textdomain( $id, false, $id.'/lang/' );

		// Checks if WooCommerce is installed.
		if ( !class_exists( 'WC_Integration' ) )
		{
			$cmn = new Video_On_Checkout_Notices_Admin();
			$cmn->add_notice_woocommerce_required();
			return;
		}
	}

	/**
	* Add a new integration to WooCommerce.
	*/
	public function filter_woocommerce_integrations( $integrations )
	{
		$integrations[] = 'Video_On_Checkout_WC_Integration';
		return $integrations;
	}

	public function components()
	{
		static $res = array();
    if ( !empty( $res ) )
		{
			return $res;
		}

		$cls = array(
			'Frontend',
			'Settings',
		);
		foreach( $cls as $cl )
		{
			$cl = "Video_On_Checkout_" . $cl;
			$res[] = new $cl;
		}

		return $res;
	}
}
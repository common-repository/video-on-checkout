<?php

// Root class for components - object with settings, actions, menus

include dirname(dirname(__FILE__)).'/code/_safe.php';

abstract class Video_On_Checkout_Component extends Video_On_Checkout_Root
{
	public $settings;

	public function __construct()
	{
		parent::__construct();
    $this->settings = new Video_On_Checkout_Settings();
	}

	public function opt( $f )
	{
  	return $this->settings->opt( $f );
	}

	public function cfg_menus()
	{
		return array();
	}
}
<?php

// Root class for include common functions

include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

abstract class Video_On_Checkout_Root
{
	public $cfg_var = 'VIDEO_ON_CHECKOUT_CFG';
	public $plugin_id;
	public $plugin_id_;
	public $plugin_name;
	public $plugin_basename;
	public $plugin_version;
	public $plugin_file;
	public $plugin_url;

	public function root()
	{
  	return $this;
	}

	public function __get($k)
	{
  	throw new Exception("$k attribute is not defined");
	}

	public function __set($k, $v)
	{
  	throw new Exception("$k attribute is not defined");
	}

	public function __call($f, $ps)
	{
  	throw new Exception("$f method is not defined");
	}

	public function hook()
	{

	}

	public function html_a( $lbl, $url, $attrs = array() )
	{
		$attrs['href'] = $url;
		$out           = "<a";
		foreach ( $attrs as $k => $v )
			$out .= " $k = '" . esc_attr( $v ) . "' ";
		$out .= ">";
		$out .= $lbl;
		$out .= "</a>";
		return $out;
	}
		
	public function __construct()
	{
		$cfg = json_decode( constant( $this->cfg_var ), true );
		foreach ( $cfg as $k => $v )
		{
			$f        = 'plugin_' . $k;
			$this->$f = $v;
		}

		$this->plugin_id_ = str_ireplace('-', '_', $this->plugin_id);
	}

	public function version_current()
	{
		return get_option( $this->plugin_id . '_version' );
	}

	public function version_is_requires_updating()
	{
		return $this->version_current() != $this->plugin_version;
	}

	public function version_mark_updated()
	{
		return update_option( $this->plugin_id . '_version', $this->plugin_version );
	}

	public function tpl_path( $f )
	{
		return $this->plugin_dir() . '/templates/' . $f;
	}

	public function url_plugin( $f )
	{
		return plugins_url( $f, $this->plugin_main_file() );
	}

	public function url_css( $f )
	{
		return $this->url_plugin( "css/$f" );
	}

	public function url_js( $f )
	{
		return $this->url_plugin( "js/$f" );
	}

	public function url_image( $f )
	{
		return $this->url_plugin( "images/$f" );
	}

	public function plugin_main_file()
	{
		return $this->plugin_dir() . "/" . $this->plugin_id . '.php';
	}

	public function plugins_dir()
	{
  	return dirname($this->plugin_dir());
	}

	public function plugin_dir()
	{
		return dirname( dirname( dirname( __FILE__ ) ) );
	}

	public function stack( $exit = true )
	{
		xdebug_print_function_stack();
		if ( $exit )
			exit;
	}

	public function hre( $v )
	{
		print "<hr>";
		var_dump( $v );
		exit;
	}

	public function hr( $v, $lbl = '' )
	{
		print "<hr><b>$lbl</b>:<br>";
		var_dump( $v );
	}

	public function install()
	{
	}
}

<?php
include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

// Class for store settings and provide editing interface in WooCommerce Integrations tab in Settings

if ( ! class_exists( 'WC_Integration' ) )
	return;

class Video_On_Checkout_WC_Integration extends WC_Integration
{
	public static $err_st = array();
	public $settings_base;

	public function admin_options() { ?>

    <?php
		  include $this->settings_base->tpl_path("admin/links.tpl.php");
    ?>

		<h3><?php echo isset( $this->method_title ) ? $this->method_title : __( 'Settings', 'woocommerce' ) ; ?></h3>

		<?php echo isset( $this->method_description ) ? wpautop( $this->method_description ) : ''; ?>

		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table>


		<!-- Section -->
		<div><input type="hidden" name="section" value="<?php echo $this->id; ?>" /></div>

		<?php


	}

	public function filter_plugin_action_links_xbasenamex( $links )
	{
		$url = $this->url_settings();
		$settings_link = "<a href='$url'>Settings</a>";
		array_unshift( $links, $settings_link );
		return $links;
	}

	public function __construct()
	{
		$this->settings_base = new Video_On_Checkout_Settings;

		global $woocommerce;
		$this->id = $this->settings_base->plugin_id_ . '_video';
		$this->method_title = $this->settings_base->plugin_name . ' - Video settings';
		$this->method_description = '';

		// Load the settings.
		$this->init_form_fields();
		$this->force_init_settings();

		static $hooked = false;
		if ( !$hooked )
		{
			$hooked = true;
			add_action( 'woocommerce_update_options_integration_' . $this->id, array( &$this, 'process_admin_options' ) );
			add_filter( "plugin_action_links_{$this->settings_base->plugin_file}", array( &$this, 'filter_plugin_action_links_xbasenamex' ) );
		}
	}

	public function process_admin_options()
	{
		if ( !current_user_can( 'manage_woocommerce' ) )
			return;
		$res = parent::process_admin_options();
		if ( $res )
		{
			$this->force_init_settings();

			// save settings in my format
			foreach ( $this->form_fields as $k => $opt )
			{
				$v = $this->get_option($k);
				if ( 'checkbox' == $opt['type'] )
				{
					$v = ('yes' == $v) ? true : false;
				}

				$this->settings_base->opt_set( $k, $v );
			}
		}

		return $res;
	}

	// refresh settings from db
	public function force_init_settings()
	{
		$this->init_settings();
		foreach ( $this->form_fields as $k => $v )
		{
			$this->$k = $this->get_option( $k );
			if ( 'checkbox' == $v['type'] )
				$this->$k = ( 'yes' == $this->$k ) ? true : false;
		}
		$this->settings = array();
	}

	public function url_settings()
	{
		return "admin.php?page=wc-settings&tab=integration";
	}

	public function init_form_fields()
	{
		$opts = $this->settings_base->opts_meta();

		$this->form_fields = array();
		foreach ($opts as $tab)
		{
    	foreach($tab['settings'] as $k=>$opt)
			{
				$el = array();

				$el['key'] = $k;
				$el['title'] = $opt['name'];
				$el['type'] = $opt['type']; // text, checkbox - as is
				$el['default'] = $opt['default']; // text, checkbox - as is

				if (isset($opt['comment']))
				{
					$el['description'] = $opt['comment'];
				}

				$this->form_fields[$el['key']] = $el;
			}
		}
	}

	// overloaded for output error message belonged to field
	public function get_description_html( $data )
	{
		$d = parent::get_description_html( $data );
		if ( isset ( self::$err_st[$data['key']] ) )
			$d .= "<p style='color: red'>" . self::$err_st[$data['key']] . "</p>";
		return $d;
	}

	// add error to errors array and to WC_Admin_Settings class
	public function error_add( $err_msg, $k = '' )
	{
		$err_msg = "Error on saving: " . $err_msg;
		if ( ! $k )
		{
			$this->errors[] = $err_msg;
			self::$err_st[] = $err_msg;
		}
		else
		{
			$this->errors[$k] = $err_msg;
			self::$err_st[$k] = $err_msg;
		}

		// in order to prevent message "Settings saved"
		WC_Admin_Settings::add_error( $err_msg );
	}


	public function display_errors()
	{
		// I am using output in the head by the admin settings WC_Admin_Settings, so this is not need
	}

	public function init_settings()
	{
		parent::init_settings();
	}

	// Validate MailChimp API key
	public function validate_video_url_field( $key )
	{
		$video_url = sanitize_text_field($_POST[$this->plugin_id . $this->id . '_' . $key]);

		$start = "https://www.youtube.com/watch?v=";
		if (substr($video_url, 0, strlen($start)) != $start)
		{
			$this->error_add("YouTube video should start with $start, while $video_url provided", $key);
			return $video_url;
		}

		list(, $ps_amp) = explode( "?", $video_url );
		parse_str($ps_amp, $ps);

		if (!isset($ps['v']))
		{
			$this->error_add(
					"YouTube video URL should have 'v' parameter, while $video_url provided", $key);
		}

		return $video_url;
	}
}
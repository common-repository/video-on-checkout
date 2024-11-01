<?php

// Class for show notices in the admin area

include dirname(dirname(__FILE__)).'/code/_safe.php';

class Video_On_Checkout_Notices_Admin extends Video_On_Checkout_Component
{
	public $var_name;
	
	public function __construct( $ps = array() )
	{
		$this->var_name = $this->plugin_id . "_admin_notices";
	}

	public function add_notice_woocommerce_required()
	{
		$this->add( "Video on Checkout plugin is activated, but can not work if WooCommerce is not activated.", 'error' );
	}

	public function init()
	{
		if ( !session_id() )
		{
			session_start();
    }
	}

	public function out()
	{
		ob_start();
		$notices = $this->get();
		foreach ( $notices as $notice_type => $msgs )
		{
			$class = 'error';
			if ( 'success' == $notice_type )
			{
				$class = 'updated';
			}
			if ( 'error' == $notice_type )
			{
				$class = 'error';
			}

			foreach ( $msgs as $msg )
			{
				?>
      		<div class="<?php echo $class;?> notice is-dismissible">
						<p><?php echo $msg ?></p>
					</div>
				<?php
			}
		}

		$this->clear();
		return ob_get_clean();
	}

	public function clear()
	{
		$this->set( $notices = array( ));
	}

	public function get()
	{
		if ( empty ( $_SESSION[ $this->var_name ] ))
			return array( );
		else
			return $_SESSION[ $this->var_name ];
	}

	public function set( $msgs )
	{
		$_SESSION[ $this->var_name ] = $msgs;
	}

	public function add( $message, $notice_type = 'success' )
	{
		$notices = $this->get();
		$notices[ $notice_type ][] = $message;
		$this->set( $notices );
	}

}

<?php

// autoload classes
function video_on_checkout_autoload( $class )
{
	$class = strtolower( $class );
	$class = str_replace( '_', '-', $class );
  $class2 = str_replace( "video-on-checkout-", '', $class );

	$subdirs = array(
		'',
		'components',
		'core',
		);
	foreach( $subdirs as $sd )
	{
		if ($sd)
			$sd .= "/";

		$inc = dirname( __FILE__ ) . "/{$sd}class-$class.php";
		if ( file_exists( $inc ) )
		{
			include_once $inc;
		}

		$inc2 = dirname( __FILE__ ) . "/{$sd}$class2.php";
		//print "<hr>$inc2<hr>";
		if ( file_exists( $inc2 ) )
		{
			include_once $inc2;
		}

	}
}

spl_autoload_register( 'video_on_checkout_autoload' );
<?php

include dirname(dirname(__FILE__)).'/code/_safe.php';

// base class for main plugin class
// autoconnect actions / filters depending on their names
// e.g. using method action4_abc will be equal to specify
// add_action('abc', array($object, 'action4_abc'), 10, 4);

abstract class Video_On_Checkout_Main_Base extends Video_On_Checkout_Component
{
	public function __construct()
	{
  	parent::__construct();

		static $hooked = false;
		if ($hooked)
			return;
		$hooked = true;

		$cs = $this->components();
		$cs[] = $this;
		$run_install = $this->version_is_requires_updating();

		foreach( $cs as $c )
		{
			$c->hook();

			if ($run_install)
			{
				$c->install();
			}

			$ms = get_class_methods($c);

			foreach($ms as $m)
			{
				$pfs = array( 'action', 'filter' );
				foreach($pfs as $pf)
				{
					$re = "#^{$pf}(\d*)_(.*)#i";
					if (preg_match_all( $re, $m, $matches ))
					{
						// $this->hr($m); $this->hr($matches);
						$f = "add_" . $pf;
						$args_count = 1;
						if (!empty($matches[1][0]))
							$args_count = $matches[1][0];

						$id = $matches[2][0];
						$id = str_ireplace(
							'xbasenamex', $this->plugin_basename, $id );

						$f( $id, array($c, $m), 10, $args_count );

						//$cl = get_class($c); $this->hr("$f($id, array($cl, $m), 10, $args_count);");
					}
				}
			}
		}

		$this->version_mark_updated();
		//$this->hre("Acts OK");

	}
}
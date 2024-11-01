<?php
include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

// Loading javascript for embedding video on checkout page

class Video_On_Checkout_Frontend extends Video_On_Checkout_Component
{
	public function video_code()
	{
		$u = $this->opt( 'video_youtube_url' );
		if (!$u)
		{
			return '';
		}
		if (!strstr($u, '?'))
		{
			return '';
		}

		list(, $ps_amp) = explode( "?", $u );
		parse_str($ps_amp, $ps);

		if (empty($ps['v']))
		{
			return '';
		}

		$h = apply_filters( Video_On_Checkout_Filters::VIDEO_CODE_HEADER, '' );
		$f = apply_filters( Video_On_Checkout_Filters::VIDEO_CODE_FOOTER, '' );
    $u = "https://www.youtube.com/embed/$ps[v]?rel=0";

		$c = "<iframe width='460' height='315' src='".esc_attr($u)."' frameborder='0' allowfullscreen></iframe>";

		$r = $h . $c . $f;
		$r = apply_filters( Video_On_Checkout_Filters::VIDEO_CODE_FULL, $r );
		return $r;
	}

	public function action_wp_enqueue_scripts()
	{
		$v = $this->plugin_version;
		$id_ = $this->plugin_id_;
		$id = $this->plugin_id;
		$script_id = $id . '-frontend';
		$d = $this->settings->values();
		$d['video_code'] = $this->video_code();
		$d = apply_filters( Video_On_Checkout_Filters::SETTINGS_JS_BEFORE_PUBLISH, $d );

		wp_enqueue_script(
			$script_id,
			$this->url_js( 'video-on-checkout.js' ),
			array('jquery'),
			$v
		);

		wp_localize_script( $script_id, $id_ . '_settings', $d );
	}
}
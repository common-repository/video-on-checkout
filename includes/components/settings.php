<?php

include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

// Settings storage

class Video_On_Checkout_Settings extends Video_On_Checkout_Settings_Base
{


	public function opts_meta( $key = '' )
	{
		$id = $this->plugin_id;

		$opts = array(
			'video' => array(
				'title' => __( "Video", 'video-on-checkout' ),
				'settings' => array()
			),
		);

		$k = 'video';

		$opts[$k]['settings']['video_youtube_url'] = array(
			'name' => 'URL',
			'default' => '',
			'type' => 'text',
			'comment' => 'YouTube URL, https://www.youtube.com/watch?v=XXXX',
		);

		/*$opts[$k]['settings']['video_autoplay'] = array(
			'name' => __( 'Autoplay', 'video-on-checkou' ),
			'default' => 0,
			'type' => 'checkbox'
		); */


		$opts = apply_filters(Video_On_Checkout_Filters::SETTINGS_FIELDS, $opts);

		foreach ( $opts as $section_id => $sd )
		{
			if (empty($sd['settings']) || !is_array($sd['settings']))
			{
				unset( $opts[$section_id] );
				continue;
			}

			foreach ( $sd['settings'] as $k => $opt )
			{
				$opts[ $section_id ]['settings'][ $k ]['k_full'] = $this->plugin_id . "_" . $k;
				if ( $key == $k )
				{
					return $opts[ $section_id ]['settings'][ $k ];
				}
			}
		}

		// not found
		if ( $key )
			return null;

		return $opts;
	}

}
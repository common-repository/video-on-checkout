"use strict";

jQuery(document).ready(function()
{
	var $ = jQuery;

	var $thy = $('.woocommerce-thankyou-order-received');

	if (!$thy.length)
		return;

	$thy.after(video_on_checkout_settings.video_code);
});

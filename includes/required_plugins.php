<?php
	require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

	add_action( 'tgmpa_register', 'dotpay_payment_gateway_recommended_plugins' );

	function dotpay_payment_gateway_recommended_plugins() {
		$plugins = array(
			array(
<<<<<<< HEAD
				'name'		=> 'WooCommerce - excelling eCommerce',
				'slug'		=> 'woocommerce',
				'required'	=> true,
				'version'	=> '2.1.0',
=======
				'name'		=> 'Another WordPress Classifieds Plugin',
				'slug'		=> 'another-wordpress-classifieds-plugin',
				'required'	=> false,
				'version'	=> '3.0.0',
>>>>>>> 20776fa9c1d284454b7223a14f089ca0bc953811
			),
		);

		tgmpa( $plugins );
	}
<?php
/*
Plugin Name: WooCommerce Gateway Dotpay
Plugin URI: http://michak.pl/woocommerce-gateway-dotpay
Description: Add a credit card payment gateway for Dotpay (Poland) to WooCommerce
Version: 1.0
Author: Michak
Author URI: http://michak.pl
Text Domain: dotpay-payment-gateway
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once dirname(__FILE__) . '/includes/required_plugins.php';

/**
* payment gateway integration for WooCommerce
* @ref http://www.woothemes.com/woocommerce/
*/
function init_woocommerce_gateway_dotpay() {
	require_once('includes/class.WCGatewayDotpay.php');
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	load_plugin_textdomain( 'dotpay-payment-gateway', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
	
	add_action( 'init', 'init_woocommerce_gateway_dotpay' );

	function add_dotpay_class($methods) {
		$methods[] = 'WC_Gateway_Dotpay';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'add_dotpay_class');	
}
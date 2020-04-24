<?php
/* @wordpress-plugin
 * Plugin Name:       WooCommerce Royal Mail Shipping Calculator
 * Plugin URI:        https://wpruby.com
 * Description:       WooCommerce Royal Mail Shipping Calculator
 * Version:           1.5.2
 * WC requires at least: 3.0
 * WC tested up to: 4.0
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * Text Domain:       wc-royal-mail
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

/**
 * royalmail_is_woocommerce_active function
 * Check whether WooCommerce is active or not, considering WPMU.
 * */	
if(!function_exists('royalmail_is_woocommerce_active')){
	function royalmail_is_woocommerce_active(){
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
}

// check if WooCommerce is installed
if(royalmail_is_woocommerce_active()){

	/**
	 * Add the shipping method to WooCommerce
	 * */
	add_filter('woocommerce_shipping_methods', 'wpruby_add_royal_mail_method');
	function wpruby_add_royal_mail_method( $methods ){
		if(version_compare(WC()->version, '2.6.0', 'lt')){
 			$methods['wpruby_royalmail'] = 'WC_Royal_Mail_Shipping_Method_Legacy';
 		}else{
 			$methods['wpruby_royalmail'] = 'WC_Royal_Mail_Shipping_Method';
 		}
		return $methods; 
	}
	/**
	 * Including the Shipping class.
	 * */
	add_action('woocommerce_shipping_init', 'wpruby_init_royal_mail');
	function wpruby_init_royal_mail(){
		require_once 'class-wc-royal-mail-shipping-method.php';
		if(version_compare(WC()->version, '2.6.0', 'lt')){
 			require_once 'class-wc-royal-mail-shipping-method-legacy.php';
 		}
	}

}// is woocommerce active




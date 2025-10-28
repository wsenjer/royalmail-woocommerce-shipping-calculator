<?php
/* @wordpress-plugin
 * Plugin Name:       Royal Mail Shipping Calculator for WooCommerce
 * Plugin URI:        https://wpruby.com
 * Description:       Royal Mail Shipping Calculator for WooCommerce
 * Version:           1.9.7
 * WC requires at least: 5.0
 * WC tested up to: 10.3
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * Text Domain:       wc-royal-mail
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
namespace WPRubyRoyalMail;

if (!(PHP_VERSION_ID >= 70300)) {
	return;
}

require_once plugin_dir_path(__FILE__) . 'includes/autoloader.php';


class WPRuby_RoyalMail_Lite {

	public function __construct()
	{
		if (!$this->royalmail_is_woocommerce_active()) {
			return;
		}

		add_filter('woocommerce_shipping_methods', [$this, 'add_royal_mail_method']);
        add_action('wp_ajax_dismiss_royalmail_rulehook_notice', [$this, 'dismiss_royalmail_rulehook_promo']);

	}

    /**
     * Handle the AJAX request to dismiss the RuleHook promo
     */
    public function dismiss_royalmail_rulehook_promo() {
        check_ajax_referer('rulehook_royalmail_dismiss_nonce', 'security');

        if (current_user_can('manage_options')) {
            // Store the current timestamp as the dismissed time
            update_user_meta(get_current_user_id(), 'rulehook_royalmail_promo_dismissed', time());
        }

        wp_die();
    }


    /**
	 * Add the shipping method to WooCommerce
	 * */
	public function add_royal_mail_method( $methods )
	{

		$methods['wpruby_royalmail'] = WC_Royal_Mail_Shipping_Method::class;

		return $methods;
	}

	/**
	 * royalmail_is_woocommerce_active function
	 * Check whether WooCommerce is active or not, considering WPMU.
	 * */
	private function royalmail_is_woocommerce_active()
	{
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

new WPRuby_RoyalMail_Lite();

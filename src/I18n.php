<?php
/**
 * Define the internationalization functionality.
 *
 * This file is based on the WordPress Plugin Boilerplate.
 *
 * @see https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/blob/master/plugin-name/includes/class-plugin-name-loader.php
 */

namespace CheckoutFinland\WooCommerceKIS;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    CheckoutFinland\WooCommerceKIS
 */
class I18n {


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'woocommerce-kis',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }
}

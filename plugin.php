<?php
/**
* Plugin Name: OP Kassa for WooCommerce
* Plugin URI: https://github.com/OPMerchantServices/op-kassa-for-woocommerce 
* Description: Connect your OP Kassa and WooCommerce to synchronize products, orders and stock levels between the systems.
* Version: 0.7.7
* Requires at least: 4.9
* Tested up to: 5.3
* Requires PHP: 7.1
* WC requires at least: 3.0
* WC tested up to: 3.9
* Author: OP Merchant Services
* Author URI: https://www.op-kauppiaspalvelut.fi 
* Text Domain: op-kassa-for-woocommerce
* Domain Path: /languages
* License: MIT
* License URI: https://opensource.org/licenses/MIT
* Copyright: OP Merchant Services
*/

namespace CheckoutFinland\WooCommerceKIS;

use CheckoutFinland\WooCommerceKIS\Activation;
use CheckoutFinland\WooCommerceKIS\Admin\Notice;
use CheckoutFinland\WooCommerceKIS\Admin\ProductEAN;
use CheckoutFinland\WooCommerceKIS\Admin\PackageSlip;
use CheckoutFinland\WooCommerceKIS\Admin\SettingsPage;
use CheckoutFinland\WooCommerceKIS\Admin\OrderInfoMetaBox;
use CheckoutFinland\WooCommerceKIS\Admin\DeletedProductTracker;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once( __DIR__ . '/vendor/autoload.php' );
}

/**
 * Initializes plugin functionalities.
 *
 * @since      1.0.0
 * @package    CheckoutFinland\WooCommerceKIS
 */
final class Plugin {

    /**
     * Holds the plugin singleton.
     *
     * @since    0.0.0
     * @access   private
     * @var Plugin
     */
    private static $instance;

    /**
     * The notice instance handles displaying all admin notices.
     *
     * @since    0.0.0
     * @access   private
     * @var      Notice    $notice    Handles all admin notices.
     */
    protected $notice;

    /**
     * The WooCommerce REST API modifications class.
     *
     * @var Api
     */
    protected $api;

    /**
     * Order info box renderer for orders
     *
     * @since 0.4.0
     * @var OrderInfoMetaBox
     */
    protected $order_box;

    /**
     * Handler for product EAN related stuff
     *
     * @since 0.5.0
     * @var ProductEAN
     */
    protected $product_ean;

    /**
     * Package slip metabox render class
     *
     * @since 0.5.0
     * @var PackageSlip
     */
    protected $package_slip;

    /**
     * Tracker for deleted and trashed posts
     *
     * @since 0.4.0
     * @var DeletedProductTracker
     */
    protected $deleted_product_tracker;

    /**
     * Define all WooCommerce post types here
     * since WC does not bother to define them.
     */
    const WC_POST_TYPES = [
        'product',
        'product_variation',
        'product_visibility',
        'shop_order',
        'shop_coupon',
        'shop_webhook',
    ];

    /**
     * Private constructor for the plugin singleton.
     */
    public function init() {
        $this->set_constants();

        $this->notice                  = new Notice();
        $this->api                     = new Api();
        $this->order_box               = new OrderInfoMetaBox();
        $this->deleted_product_tracker = new DeletedProductTracker();
        $this->product_ean             = new ProductEAN();
        $this->package_slip            = new PackageSlip();

        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_global_hooks();
    }

    /**
     * Define plugin constants.
     */
    private function set_constants() {
        $plugin_headers = [
            'Version' => 'Version',
        ];

        if ( function_exists( 'get_file_data' ) ) {
            $plugin_data = \get_file_data( __FILE__, $plugin_headers, 'plugin' );

            $kis_version = $plugin_data['Version'];
        }
        else {
            $kis_version = 'unknown';
        }

        define( 'WOOCOMMERCE_KIS_VERSION', $kis_version );

        if ( ! defined( 'KIS_WOOCOMMERCE_OAUTH_URL' ) ) {
            define( 'KIS_WOOCOMMERCE_OAUTH_URL', 'https://woocommerce.prod.op-kassa.fi/prod/woo-oauth-initiate' );
        }

        if ( ! defined( 'KIS_KASSA_OAUTH_URL' ) ) {
            define( 'KIS_KASSA_OAUTH_URL', 'https://woocommerce.prod.op-kassa.fi/prod/kassa-oauth-initiate' );
        }

        if ( ! defined( 'KIS_WOOCOMMERCE_OAUTH_CALLBACK_URL' ) ) {
            define( 'KIS_WOOCOMMERCE_OAUTH_CALLBACK_URL', 'https://woocommerce.prod.op-kassa.fi/prod/woo-oauth-callback' );
        }

        if ( ! defined( 'KIS_WOOCOMMERCE_WEBHOOK_URL' ) ) {
            define( 'KIS_WOOCOMMERCE_WEBHOOK_URL', 'https://woocommerce.prod.op-kassa.fi/prod/woo-webhook' );
        }
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    0.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new I18n();

        add_action( 'plugins_loaded', [ $plugin_i18n, 'load_plugin_textdomain' ] );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    0.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        /**
         * Disable the redundant Mandatory-plugin check. This is handled in the \SystemAudit.php
         */ 
        // Register the settings page for WooCommerce if OAuth plugin is enabled.
        // if ( $this->oauth_enabled() ) {
        add_filter(
            'woocommerce_get_settings_pages', [ SettingsPage::class, 'include_settings_page' ], 1, 1
        );
        // }
        // Display admin notice for the missing plugin.
        // else {
        //     add_filter( 'admin_notices', [ $this->notice, 'oauth_plugin_notice' ] );
        // }

        // Hook for displaying OAUTH errors.
        add_filter(
            'admin_notices', [ $this->notice, 'oauth_error' ]
        );

        // Create a new metabox for orders
        add_action('add_meta_boxes', [$this->order_box, 'orderbox_metabox']);

        // Hook to add order metadata to order
        add_action('post_updated', [$this->order_box, 'save_metabox_data'], 10, 3);

        // Create a new metabox for package slip
        add_action('add_meta_boxes', [$this->package_slip, 'render_metabox']);

        // To track if posts are deleted, trashed or untrashed
        add_action('delete_post', [$this->deleted_product_tracker, 'post_deleted_or_trashed'], 10, 1);
        add_action('wp_trash_post', [$this->deleted_product_tracker, 'post_deleted_or_trashed'], 10, 1);
        add_action('untrash_post', [$this->deleted_product_tracker, 'post_untrashed'], 10, 1);

        // WooCommerce refund hook to update the order
        add_action('woocommerce_order_refunded', [$this, 'refresh_order_on_refund'], 10, 2 );

        // WooCommerce products EAN hooks
        add_action('woocommerce_product_options_sku', [$this->product_ean, 'add_ean_input_to_product']);
        add_action('woocommerce_process_product_meta', [$this->product_ean, 'save_product_ean']);

        // WooCommerce product variation EAN hooks
        add_action('woocommerce_variation_options', [$this->product_ean, 'add_ean_input_to_variation'], 10, 3);
        add_action('woocommerce_save_product_variation', [$this->product_ean, 'save_variation_ean'], 10, 2);
    }

    /**
     * Register all hooks run globally.
     *
     * @since    0.0.0
     * @access   private
     */
    private function define_global_hooks() {
        $notice = $this->notice;

        // Hook to the OAuth server plugin's token data hook.
        $oauth = new OAuth( $notice );
        add_filter(
            'json_oauth1_access_token_data', [ $oauth, 'handle_json_access_token_data' ], 1, 1
        );

        add_action( 'admin_init', [ $oauth, 'handle_kassa_oauth_response' ] );

        // Filter WooCommerce REST API query for all WC post types.
        $post_types = static::WC_POST_TYPES;
        array_walk(
            $post_types, function( $post_type ) {
                add_filter(
                    "woocommerce_rest_{$post_type}_object_query",
                    [ $this->api, 'filter_wc_rest_query' ],
                    PHP_INT_MAX,
                    2
                );
            }
        );

        // Add a custom route for the deleted products
        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'wc/v3',
                    'deleted_products',
                    [
                        'methods'  => 'GET',
                        'callback' => [$this->deleted_product_tracker, 'get_deleted_products_callback'],
                        'permission_callback' => function() {
                            return \current_user_can('manage_woocommerce');
                        }
                    ]
                );
            }
        );
    }

    /**
     * Check if the 'WP REST API - OAuth 1.0a Server' plugin is enabled.
     *
     * @see https://wordpress.org/plugins/rest-api-oauth1/
     *
     * @return bool
     */
    private function oauth_enabled() {
        return class_exists( 'WP_REST_OAuth1' );
    }

    /**
     * Get the plugin assets url.
     *
     * @return string
     */
    public function get_assets_url() : string {
        return plugin_dir_url( __FILE__ ) . 'assets/';
    }

    /**
     * Get the plugin assets path.
     *
     * @return string
     */
    public function get_assets_path() : string {
        return plugin_dir_path( __FILE__ ) . 'assets';
    }

    /**
     * Initializes the plugin once and returns the instance.
     */
    public static function instance() {
        if ( empty( static::$instance ) ) {
            static::$instance = new Plugin();

            return static::$instance;
        }

        return static::$instance;
    }

    /**
     * Updates order post modified timestamp when order is refunded
     *
     * @param int $order_id
     * @param int $refund_id
     * @return void
     */
    public function refresh_order_on_refund($order_id, $refund_id) {
        $order = get_post($order_id);
        $err = null;
        wp_update_post($order, $err);
    }
}

/**
 * A global method for getting the plugin singleton.
 *
 * @package CheckoutFinland\WooCommerceKIS
 * @since   0.0.0
 * @return  Plugin
 */
function plugin() {
    return Plugin::instance();
}

// Begin plugin excecution by creating the singleton.
plugin()->init();

/**
 * Register an Activation hook for handling the plugin system audit
 */
include_once dirname( __FILE__ ) . '/src/Admin/SystemAudit.php';
register_activation_hook( __FILE__, array( 'CheckoutFinland\WooCommerceKIS\Admin\SystemAudit', 'perform_system_audit' ) );
add_action( 'admin_notices', array( 'CheckoutFinland\WooCommerceKIS\Admin\SystemAudit', 'display_system_audit_admin_notice' ) );

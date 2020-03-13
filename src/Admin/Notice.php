<?php
/**
 * This class handles all admin notices.
 */

namespace CheckoutFinland\WooCommerceKIS\Admin;

use CheckoutFinland\WooCommerceKIS\OAuth;

/**
 * This class handles all admin notices.
 *
 * @since      0.0.0
 * @package    CheckoutFinland\WooCommerceKIS\Admin
 */
class Notice {

    const OAUTH_PLUGIN_URL = 'https://wordpress.org/plugins/rest-api-oauth1/';

    /**
     * Display a general error message.
     *
     * @param string $message Error message.
     */
    public function error( string $message = '' ) {
        add_action( 'admin_notices', function() use ( $message ) {
            ?>
            <div class="notice notice-error">
                <p>WooCommerce KIS: <?php echo esc_html( $message ); ?></p>
            </div>
            <?php
        } );
    }

    /**
     * Display a success message.
     *
     * @param string $message The message.
     */
    public function success( string $message = '' ) {
        add_action( 'admin_notices', function() use ( $message ) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p>WooCommerce KIS: <?php echo esc_html( $message ); ?></p>
            </div>
            <?php
        } );
    }

    /**
     * Prints out the missing OAuth plugin notice.
     *
     * @since 0.0.0
     */
    public function oauth_plugin_notice() {
        ?>
        <div class="notice notice-error">
            <p>
                <?php
                printf(
                    // translators: Placeholders are the html anchor tags for the plugin resource.
                    esc_html__(
                        // phpcs:disable
                        'WooCommerce KIS: The %1$sWordPress REST API – OAuth 1.0a Server%2$s plugin must be installed and activated!',
                        // phpcs:enable
                        'woocommerce-kis'
                    ),
                    '<a href=" ' . esc_url( self::OAUTH_PLUGIN_URL ) . '" target="_blank">',
                    '</a>'
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * Display OAUTH errors.
     *
     * @since 0.0.0
     */
    public function oauth_error() {
        $error = filter_input( INPUT_GET, OAuth::KIS_OAUTH_ERROR_CMD, FILTER_SANITIZE_STRING );

        if ( empty( $error ) ) {
            return;
        }

        switch ( $error ) {
            case 'wc':
                $error = __(
                    'WooCommerce KIS: An error occurred while authenticating the integration to WooCommerce. Please try again later!', // phpcs:ignore
                    'woocommerce-kis'
                );
                break;
            case 'kassa':
                $error = __(
                    'WooCommerce KIS: An error occurred while authenticating the integration to OP Kassa. Please try again later!', // phpcs:ignore
                    'woocommerce-kis'
                );
                break;
        }
        ?>
        <div class="notice notice-error">
            <p><?php echo esc_html( $error ); ?></p>
        </div>
        <?php
    }

    /**
     * Display the error notice for a denied OAuth connection cancellation attempt.
     */
    public function oauth_cancel_deny_notice() {
        add_action(
            'admin_notices', function() {
                ?>
            <div class="notice notice-error">
                <p>
                    <?php
                    printf(
                        // translators: The placeholder is for a user display name variable.
                        esc_html__(
                            // phpcs:disable
                            'WooCommerce KIS: You are not authorized to disconnect the integration!',
                            // phpcs:enable
                            'woocommerce-kis'
                        ),
                        esc_html( $user_name )
                    );
                    ?>
                </p>
            </div>
                <?php
            }
        );
    }

}

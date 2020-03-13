<?php
/**
 * Webhook controller class
 */

namespace CheckoutFinland\WooCommerceKIS;

/**
 * Class Webhooks
 *
 * @package CheckoutFinland\WooCommerceKIS
 */
class Webhooks {

    /**
     * Create a new WooCommerce webhook.
     *
     * @param array $args The arguments with which to create the webhook.
     * @return \WC_Webhook The created webhook.
     */
    public static function create( array $args ) : \WC_Webhook {

        $current_user = wp_get_current_user();

        // Function default arguments
        $defaults = [
            'name'         => null,
            'status'       => 'active',
            'topic'        => '',
            'delivery_url' => '',
            'secret'       => '',
            'api_version'  => '2',
        ];

        // Parse incoming arguments and merge it with defaults.
        $args = \wp_parse_args( $args, $defaults );

        $webhook = new \WC_Webhook();
        $webhook->set_name( $args['name'] );
        $webhook->set_user_id( $current_user->ID );
        $webhook->set_status( $args['status'] );
        $webhook->set_topic( $args['topic'] );
        $webhook->set_delivery_url( $args['delivery_url'] );
        $webhook->set_secret( ! empty( $args['secret'] ) ? $args['secret'] : wp_generate_password( 50, true, true ) );
        $webhook->set_api_version( $args['api_version'] );

        $webhook->save();

        return $webhook;
    }

    /**
     * Delete all webhooks of the WooCommerce KIS integration.
     *
     * @return void
     */
    public static function delete_all() {
        // Get the webhooks.
        $data_store = \WC_Data_Store::load( 'webhook' );
        $webhooks   = $data_store->search_webhooks([
            'search' => 'WooCommerce KIS',
            'limit'  => 500,
            'offset' => 0,
        ]);
        $webhooks   = array_map( 'wc_get_webhook', $webhooks );

        array_walk( $webhooks, function( $webhook ) {
            $webhook->delete( true );
        });
    }
}

<?php
/**
 * This class controls modifications of the WooCommerce REST API.
 */

namespace CheckoutFinland\WooCommerceKIS;

use WP_REST_Request;

/**
 * Class Api
 *
 * This class controls modifications of the WooCommerce REST API.
 *
 * @package CheckoutFinland\WooCommerceKIS
 */
class Api {

    /**
     * This query var is used to create a modified by date query.
     */
    const MODIFIED_AFTER_QUERY_VAR = 'kis_modified_after';

    /**
     * This query var is used to create a meta query
     * to match for only objects created by KIS.
     */
    const KIS_OBJECT_TYPE_QUERY_VAR = 'kis_object_type';

    /**
     * Customize the WooCommerce REST API query.
     *
     * This method is hooked to the following hook
     * where post types are the WooCommerce post types:
     * "woocommerce_rest_{$post_type}_object_query"
     *
     * @param array           $args    Key value array of query var to query value.
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array A prepared date query array.
     */
    public function filter_wc_rest_query( array $args, WP_REST_Request $request ) : array {
        $args = $this->add_modified_by_date_query( $args, $request );
        $args = $this->add_object_type_meta_query( $args, $request );

        return $args;
    }

    /**
     * Add a modified by date query if the query parameters match.
     *
     * This is used to force the time handling required by KIS.
     * Results are always ordered in ascending order by the modified date.
     *
     * @see https://github.com/woocommerce/wc-api-dev/issues/65
     *
     * @param array           $args    Key value array of query var to query value.
     * @param WP_REST_Request $request Full details about the request.
     * @return array A prepared date query array.
     */
    protected function add_modified_by_date_query( array $args, WP_REST_Request $request ) : array {
        $param = $request->get_param( static::MODIFIED_AFTER_QUERY_VAR );
        if ( $param !== null ) {
            $unix_timestamp = (int) filter_var( $param, FILTER_SANITIZE_NUMBER_INT );

            $modified_after = date( 'c', $unix_timestamp );

            $args['date_query'] = [
                'after'  => $modified_after,
                'column' => 'post_modified_gmt',
            ];

            // Order by modified time in an ascending order.
            $args['orderby'] = 'modified';
            $args['order']   = 'ASC';
        }

        return $args;
    }

    /**
     * Add a meta query if the query parameters match.
     *
     * This is used to find a Woo object through the API mathcing a specific Kassa type.
     * For instance, Kassa purchases are marked with a meta key that is used
     * for fetching all orders created by KIS.
     *
     * @param array           $args    Key value array of query var to query value.
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array A prepared date query array.
     */
    protected function add_object_type_meta_query( array $args, WP_REST_Request $request ) : array {
        $param = $request->get_param( static::KIS_OBJECT_TYPE_QUERY_VAR );
        if ( $param ) {

            if ( ! isset( $args['meta_query'] ) ) {
                // phpcs:ignore -- Ignore the meta query.
                $args['meta_query'] = [];
            }
            $args['meta_query'][] = [
                'key'     => filter_var( $param, FILTER_SANITIZE_STRING ),
                'compare' => 'EXISTS',
            ];
        }

        return $args;
    }

}

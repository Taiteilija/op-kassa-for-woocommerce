<?php
/**
 * This class contains static utility methods.
 */

namespace CheckoutFinland\WooCommerceKIS;

/**
 * Class Ulitity
 *
 * @package CheckoutFinland\WooCommerceKIS
 */
class Utility {

    /**
     * Remove a wanted parameter from given url.
     *
     * @param string $url       The URL to handle.
     * @param string $parameter The parameter to remove.
     * @return string
     */
    public static function remove_query_parameter( string $url, string $parameter ) : string {
        $current = \wp_parse_url( $url );

        parse_str( $current['query'], $query_string );

        unset( $query_string[ $parameter ] );

        $current['query'] = http_build_query( $query_string );

        return static::unparse_url( $current );
    }

    /**
     * Add a parameter to given url.
     *
     * @param string $url       The URL to handle.
     * @param string $parameter The parameter to add.
     * @param string $value     The value to add.
     * @return string
     */
    public static function add_query_parameter( string $url, string $parameter, string $value ) : string {
        $current = \wp_parse_url( $url );

        parse_str( $current['query'] ?? '', $query_string );

        $query_string[ $parameter ] = $value;

        $current['query'] = http_build_query( $query_string );

        return static::unparse_url( $current );
    }

    /**
     * Get the current admin url.
     *
     * @return string
     */
    public static function get_current_admin_url(): string {
        $protocol    = \is_ssl() ? 'https://' : 'http://';
        $server_name = static::get_server_name();
        // @codingStandardsIgnoreStart - a safer way to access server variables on PHP7.x
        $request_uri  = filter_var( $_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL ) ?: '';
        // @codingStandardsIgnoreEnd

        $current_url = \wp_parse_url( $protocol . $server_name . $request_uri );

        $current_url['query'] = 'page=wc-settings&tab=kis';

        return self::unparse_url( $current_url );
    }


    /**
     * Get the server name using Worpress value from:
     * 'Settings' -> 'General' -> 'WordPress address (URL)'
     *
     * @return string
     */
    public static function get_server_name(): string {
        $server_name = filter_var( \wp_parse_url( get_site_url(), PHP_URL_HOST ), FILTER_SANITIZE_URL ) ?: '';
        
        return $server_name;
    }

    /**
     * Unparse a URL.
     *
     * @see http://php.net/manual/en/function.parse-url.php#106731
     *
     * @param array $parsed_url The parse_url end result to stitch back together.
     * @return string The URL as a string.
     */
    public static function unparse_url( array $parsed_url ) : string {
        $scheme   = isset( $parsed_url['scheme'] ) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset( $parsed_url['host'] ) ? $parsed_url['host'] : '';
        $port     = isset( $parsed_url['port'] ) ? ':' . $parsed_url['port'] : '';
        $user     = isset( $parsed_url['user'] ) ? $parsed_url['user'] : '';
        $pass     = isset( $parsed_url['pass'] ) ? ':' . $parsed_url['pass'] : '';
        $pass     = ( $user || $pass ) ? "$pass@" : '';
        $path     = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '';
        $query    = isset( $parsed_url['query'] ) ? '?' . $parsed_url['query'] : '';
        $fragment = isset( $parsed_url['fragment'] ) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }
}

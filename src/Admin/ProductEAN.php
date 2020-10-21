<?php
/**
 * This class contains methods to render and save EAN data for products and variants
 */

namespace CheckoutFinland\WooCommerceKIS\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Class ProductEAN
 *
 * @since      0.5.0
 * @package CheckoutFinland\WooCommerceKIS
 */
final class ProductEAN {

    /**
     * Meta key name for the ean
     */
    const META_KEY_NAME = '_kassa_ean';

    /**
     * Callback for the 'woocommerce_product_options_sku' action
     *
     * @return void
     */
    public function add_ean_input_to_product() {
        global $post;

        woocommerce_wp_text_input([
            'label' => 'EAN',
            'placeholder' => '12345678',
            'class' => 'ean short',
            'id' => self::META_KEY_NAME,
            'description' => '',
            'value' => get_post_meta($post->ID, self::META_KEY_NAME, true)
        ]);
    }

    /**
     * Callback for the 'woocommerce_variation_options_pricing' action
     *
     * @param array $loop
     * @param array $variation_data Variation data
     * @param WP_Post $variation Current variantion post
     * @return void
     */
    public function add_ean_input_to_variation($loop, $variation_data, $variation) {
        woocommerce_wp_text_input([
            'label' => 'EAN',
            'placeholder' => '12345678',
            'class' => 'ean short',
            'id' => self::META_KEY_NAME . $loop,
            'name' => self::META_KEY_NAME . "[{$loop}]",
            'wrapper_class' => 'form-row',
            'description' => '',
            'value' => get_post_meta($variation->ID, self::META_KEY_NAME, true)
        ]);
    }

    /**
     * Callback for the 'woocommerce_process_product_meta' action
     *
     * @param int $post_id Post ID
     * @return void
     */
    public function save_product_ean($post_id) {
        // Sanitize input
        $ean = filter_input(INPUT_POST, self::META_KEY_NAME, FILTER_SANITIZE_STRING);

        // Get the product
        $product = wc_get_product($post_id);

        // Save the post and meta value
        $product->update_meta_data(self::META_KEY_NAME, $ean);
        $product->save();
    }

    /**
     * Callback for the 'woocommerce_save_product_variation' action
     *
     * @param int $variation_id Variation ID
     * @param int $i The i.
     * @return void
     */
    public function save_variation_ean($variation_id, $i) {
        // Sanitize input
        // Returns eg. {"_kassa_ean":["ean_1","ean_2","ean_3"]}
        $ean_raw_array_wrapper = filter_input_array(
            INPUT_POST, 
            array(
                self::META_KEY_NAME => array(
                    'filter' => FILTER_SANITIZE_STRING,
                    'flags'  => FILTER_REQUIRE_ARRAY,
                )
            )
        );
        
        $ean = '';
        try {
            $ean = $ean_raw_array_wrapper[self::META_KEY_NAME][$i];
        }
        catch (\RuntimeException $e) {
            // Ignore errors and save an empty value
        }

        // Save the variant and meta value
        update_post_meta($variation_id, self::META_KEY_NAME, $ean);
    }
}


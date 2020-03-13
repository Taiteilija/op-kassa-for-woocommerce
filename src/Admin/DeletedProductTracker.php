<?php
/**
 * This class tracks deleted and trashed products so KIS knows about them without querying all products
 */

namespace CheckoutFinland\WooCommerceKIS\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Class DeletedProductTracker
 *
 * @since      0.4.0
 * @package CheckoutFinland\WooCommerceKIS
 */
final class DeletedProductTracker {
    const DELETED_PRODUCTS_OPTION = 'kis_deleted_products';
    const PRODUCT_POST_TYPE = 'product';

    /**
     * If post was deleted or trashed, add it to the list
     *
     * @param int $post_id Post ID that was deleted or trashed
     */
    public function post_deleted_or_trashed($post_id) {
        if ( \get_post_type($post_id) !== self::PRODUCT_POST_TYPE ) {
            return;
        }

        // Add the product to the list, if does not exist
        $deleted_products = $this->get_deleted_products();
        if ( ! isset($deleted_products[$post_id]) ) {
            $deleted_products[$post_id] = time();
            $this->set_deleted_products(array_unique($deleted_products));
        }
    }

    /**
     * Remove untrashed posts from the list
     *
     * @param int $post_id Post ID that was untrashed
     */
    public function post_untrashed($post_id) {
        if ( \get_post_type($post_id) !== self::PRODUCT_POST_TYPE ) {
            return;
        }

        // Delete the untrashed product from the list
        $deleted_products = $this->get_deleted_products();
        unset($deleted_products[$post_id]);
        $this->set_deleted_products(array_unique($deleted_products));
    }

    /**
     * Callback for the api route
     *
     * @return Array Deleted product IDs
     */
    public function get_deleted_products_callback() {
        $deleted_after = filter_input(INPUT_GET, 'deleted_after', FILTER_SANITIZE_NUMBER_INT);

        if ($deleted_after) {
            return $this->get_deleted_products_after_timestamp($deleted_after);
        }

        return $this->get_deleted_products();
    }

    /**
     * Getter for the deleted product IDs list
     *
     * @return Array List of deleted or trashed product IDs
     */
    public function get_deleted_products() {
        $delete_products = (array) (\get_option(self::DELETED_PRODUCTS_OPTION) ?: []);

        return apply_filters('kis_get_deleted_product_ids', $delete_products);
    }

    /**
     * Get all products that are deleted after the given timestamp
     *
     * @param int $deleted_at Timestamp when the post was deleted
     * @return Array List of deleted or trashed product IDs that were deleted after given time
     */
    public function get_deleted_products_after_timestamp($deleted_at) {
        $delete_products = (array) (\get_option(self::DELETED_PRODUCTS_OPTION) ?: []);

        $delete_products = array_filter($delete_products, function($timestamp) use ($deleted_at) {
            return $timestamp > $deleted_at;
        });

        return apply_filters('kis_get_deleted_product_ids_filtered', $delete_products, $deleted_at);

    }

    /**
     * Setter for the deleted product IDs list
     *
     * @param Array $productIDs List of deleted Product IDs
     */
    private function set_deleted_products($product_ids) {
        $product_ids = apply_filters('kis_save_deleted_product_ids', $product_ids);
        return \update_option(self::DELETED_PRODUCTS_OPTION, $product_ids, false);
    }
}

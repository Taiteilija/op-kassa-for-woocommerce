<?php
/**
 * This class contains methods to render package slip metabox for orders
 */

namespace CheckoutFinland\WooCommerceKIS\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Class PackageSlip
 *
 * @since      0.4.0
 * @package CheckoutFinland\WooCommerceKIS
 */
final class PackageSlip {
    /**
     * Unique identifier for the metabox
     */
    const META_BOX_IDENTIFIER = 'woocommerce-kis-package-slip';

    /**
     * Metabox header title
     */
    const META_BOX_TITLE = 'Stockmann package slip';

    /**
     * Link text for the package slip pdf download
     */
    const PACKAGE_SLIP_LINK_TEXT = 'Download package slip';

    /**
     * Meta key name for package slip
     */
    const PACKAGE_SLIP_META_KEY = '_kassa_package_slip';

    /**
     * Add a new metabox to shop_order post page
     *
     * @return void
     */
    public function render_metabox() {
        global $post;
        $packageSlipUrl = get_post_meta($post->ID, self::PACKAGE_SLIP_META_KEY, true);
        if ($packageSlipUrl) {
            add_meta_box(
                self::META_BOX_IDENTIFIER,
                __(self::META_BOX_TITLE, 'woocommerce-kis'),
                [$this, 'metabox_render_callback'],
                'shop_order',
                'side'
            );
        }
    }

    /**
     * Callback for the metabox render on shop_order post page
     *
     * @return void
     */
    public function metabox_render_callback() {
        global $post;
        $packageSlipUrl = get_post_meta($post->ID, self::PACKAGE_SLIP_META_KEY, true);
?>
        <div class="panel-wrap woocommerce">
            <div class="panel">
                <p class="form-field form-field-wide">
                    <a href="<?= $packageSlipUrl ?>" target="_blank"><?= __(self::PACKAGE_SLIP_LINK_TEXT, 'woocommerce-kis') ?></a>
                </p>
            </div>
        </div>
<?php
    }
}

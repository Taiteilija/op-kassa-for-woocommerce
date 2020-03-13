=== OP Kassa for WooCommerce ===

Connect your OP Kassa and WooCommerce to synchronize products, orders and stock levels between the systems.

- Contributors:
    - [Geniem](https://github.com/devgeniem)
    - [Miika Arponen](https://github.com/nomafin)
    - [Ville Siltala](https://github.com/villesiltala)
    - [Tomi Henttinen](https://github.com/tomihenttinen)
    - [Indre Solodov](https://github.com/Indre87)
    - [Aki Salmi](https://github.com/rinkkasatiainen)
- Tags: wordpress, woocommerce
- Requires:
    - WordPress 4.9.0 and up. -> https://wordpress.org/download/
    - WooCommerce 3.0.0 and up. -> https://wordpress.org/plugins/woocommerce/
    - WordPress REST API – OAuth 1.0a Server -> https://wordpress.org/plugins/rest-api-oauth1/
    - Requires PHP: 7.1
    - License: MIT
    - License URI: https://opensource.org/licenses/MIT

== Description ==

This plugin integrates WooCommerce to OP Kassa.

== Installation ==

Install with Composer: (You need composer for this -> https://getcomposer.org/)

```
1. Clone the repository to wordpress 'wp-content/plugins' directory.
2. Run `composer install` inside the directory and wait for it to finish
3. Activate the Plugin in Woocommerce Plugin management

```

Install by uploading Zip trough Wordpress : Get the zip from here -> https://github.com/OPMerchantServices/OP Kassa-for-woocommerce/archive/master.zip

```
1. Download the latest Zip package
2. Go to WordPress admin area and visit Plugins » Add New page.
3. After that, click on the ‘Upload Plugin’ button on top of the page.
4. This will reveal the plugin upload form. Here you need to click on the ‘Choose File’ button and select the plugin Zip file you downloaded earlier to your computer.
5. After you have selected the file, you need to click on the ‘Install Now’ button.
6. Once installed, you need to click on the Activate Plugin link to start using the plugin.

```

== Configurations ==

### Constants [Only for developers and testing]

The following constants should be set for development and test environments. If the constants are not set, they point to the production version of OP Kassa. For additional information please kontalt OP Kassa support.

- **KIS_WOOCOMMERCE_OAUTH_URL**: Defines the OP Kassa to WooCommerce OAuth initialization URL in OP Kassa.
- **KIS_KASSA_OAUTH_URL**: Defines the OP Kassa to Kassa OAuth initialization URL in OP Kassa.
- **KIS_WOOCOMMERCE_WEBHOOK_URL**: Defines the URL in OP Kassa for WooCommerce webhooks.
- **KIS_WOOCOMMERCE_OAUTH_CALLBACK_URL**
- **KIS_WOOCOMMERCE_SYSTEM_AUDIT_CONFIG_URL**: Defines the S3 API Gateway URL from where to fetch the configuration file for the plugin system audit

### Admin

This plugin creates an admin page in WooCommerce settings. The page is be found in the following URL (if the site follows Wordpress admin URL conventions):
```
/wp-admin/admin.php?page=wc-settings&tab=kis
```
On this settings tab, the user can activate OAuth connections required by OP Kassa. Merchant details are found on the page after the connections are created successfully.

### System Audit

The Plugin has a system audit-feature which is ran on plugin activation and may be also ran manually from plugin settings page.

The system audit checks for the following:

1. The system settings requirements are met ('limit'-value needs to be met or exceeded):
    a. memory_limit
    b. max_execution_time
2. WordPress-options are configured properly ('value'-value needs to match the Wordpress configuration):
    a. permalink_structure
3. Mandatory plugins are installed/activated
4. Incompatible plugins are not installed (may issue an warning or error)
5. System has connection to target systems

If the system audit fails or shows warnings, please contact OP Kassa support. And attach screenshot of the result with your message.

== Frequently Asked Questions ==

TBA

== Changelog ==

= 0.7 =
* First version published at WordPress.org
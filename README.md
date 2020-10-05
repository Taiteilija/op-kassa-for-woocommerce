# OP Kassa for WooCommerce

**Contributors:** [Miika Arponen](https://github.com/nomafin), [Ville Siltala](https://github.com/villesiltala), [Tomi Henttinen](https://github.com/tomihenttinen), [Indre Solodov](https://github.com/Indre87) & [Joonas Loueranta](https://github.com/loueranta)

**Requires**
- [WordPress](https://wordpress.org/download/): 4.9.0 and up
- [WooCommerce](https://wordpress.org/plugins/woocommerce/): 3.0.0 and up
- [WordPress REST API – OAuth 1.0a Server](https://wordpress.org/plugins/rest-api-oauth1/)
- PHP: 7.1 and up

## Description

Connect your [OP Kassa](https://www.op-kassa.fi) and WooCommerce to synchronize products, orders and stock levels between the systems.

## Installation

1. Download the latest OP Kassa for Woocommerce plugin from the [Releases](https://github.com/OPMerchantServices/op-kassa-for-woocommerce/releases) page.
2. Log on to WordPress admin area and navigate to Plugins -> Add New.
3. Click on the "Upload Plugin" button on top of the page and install + activate the zip packaged plugin you downloaded.
4. Go back to Plugins -> Add New and search for "[WordPress REST API – OAuth 1.0a Server](https://wordpress.org/plugins/rest-api-oauth1/)" and install + activate it.
5. To finish setup head over to WooCommerce -> Settings and click on the "OP Kassa" tab to configure the plugin.

### System Audit

The Plugin has a System Audit feature which is ran on plugin activation and may be also ran manually from plugin settings page.

The System Audit checks for the following:

1. The system settings requirements are met ('limit'-value needs to be met or exceeded):
    1. memory_limit
    2. max_execution_time
2. WordPress-options are configured properly ('value'-value needs to match the Wordpress configuration):
    1. permalink_structure
    2. woocommerce_calc_taxes (warn only)
3. Mandatory plugins are installed/activated
4. Incompatible plugins are not installed (may issue an warning or error)
5. System has connection to target systems

If the System Audit fails or shows warnings, please contact OP Kassa support. And attach screenshot of the result with your message.

## Configuration

The plugin adds OP Kassa configuration tab to WooCommerce settings. 

The standard URL for this configuration tab is:
```
/wp-admin/admin.php?page=wc-settings&tab=kis
```

### Connecting to OP Kassa

On the OP Kassa settings tab, the user can activate connections required by OP Kassa.

Merchant details are found on the page after the connections are created successfully.

### Settings

There are currently two settings available for configuration on the OP Kassa tab in WooCommerce Settings: Product export direction and Order export direction.

For **Product export** you can choose to disable it (default setting) or choose which way you want the product data to be synchronized, from WooCommerce to OP Kassa or vice versa. 

For **Order export** you can choose to disable it (default setting) or choose to sychronize the order data both ways or just one way, from WooCommerce to OP Kassa or vice versa. 

Choosing the Stock export setting is currently disabled and is linked to Product export setting.

Please note that when you change the settings and hit save, it will take couple of minutes for the synchronization to start.

### QA/Test environment

If you are using OP Kassa QA environment, you need to select "Connect to OP Kassa Test Environment" on the settings tab. Please use this setting only if you know what you are doing as it will disable the connection to the production environment of OP Kassa.

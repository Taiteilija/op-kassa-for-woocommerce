# OP Kassa for WooCommerce

**Contributors:** [Geniem](https://github.com/devgeniem), [Miika Arponen](https://github.com/nomafin), [Ville Siltala](https://github.com/villesiltala), [Tomi Henttinen](https://github.com/tomihenttinen), [Indre Solodov](https://github.com/Indre87) & [Joonas Loueranta](https://github.com/loueranta)

**Requires**
- [WordPress](https://wordpress.org/download/): 4.9.0 and up
- [WooCommerce](https://wordpress.org/plugins/woocommerce/): 3.0.0 and up
- [WordPress REST API – OAuth 1.0a Server](https://wordpress.org/plugins/rest-api-oauth1/)
- PHP: 7.1 and up

## Description

Connect your [OP Kassa](https://www.op-kassa.fi) and WooCommerce to synchronize products, orders and stock levels between the systems.

## Installation

You can download the latest release from the [Releases](https://github.com/OPMerchantServices/op-kassa-for-woocommerce/releases) page.

```
1. Download the latest Zip package
2. Go to WordPress admin area and visit Plugins » Add New page.
3. After that, click on the ‘Upload Plugin’ button on top of the page.
4. This will reveal the plugin upload form. Here you need to click on the ‘Choose File’ button and select the plugin Zip file you downloaded earlier to your computer.
5. After you have selected the file, you need to click on the ‘Install Now’ button.
6. Once installed, you need to click on the Activate Plugin link to start using the plugin.
```

## Configuration

The plugin adds OP Kassa configuration tab to WooCommerce settings. 

The standard URL for this configuration tab is:
```
/wp-admin/admin.php?page=wc-settings&tab=kis
```

### System Audit

The Plugin has a System Audit feature which is ran on plugin activation and may be also ran manually from plugin settings page.

The System Audit checks for the following:

1. The system settings requirements are met ('limit'-value needs to be met or exceeded):
    a. memory_limit
    b. max_execution_time
2. WordPress-options are configured properly ('value'-value needs to match the Wordpress configuration):
    a. permalink_structure
    b. woocommerce_calc_taxes (warn only)
3. Mandatory plugins are installed/activated
4. Incompatible plugins are not installed (may issue an warning or error)
5. System has connection to target systems

If the System Audit fails or shows warnings, please contact OP Kassa support. And attach screenshot of the result with your message.

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

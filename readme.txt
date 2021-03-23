=== OP Kassa for WooCommerce ===
Contributors: loueranta
Donate link: https://www.op-kauppiaspalvelut.fi
Tags: woocommerce
Requires at least: 4.9
Tested up to: 5.6
Stable tag: 2.0.0
Requires PHP: 7.1
License: MIT
License URI: https://opensource.org/licenses/MIT

Connect your [OP Kassa](https://www.op-kassa.fi) and WooCommerce to synchronize products, orders and stock levels between the systems.

== Description ==

[OP Kassa](https://www.op-kassa.fi) is easy to use point of sale system for your omnichannel business with modern payment terminals, fast tablet based cashier and online admin system with extensive reporting. OP Kassa for WooCommerce allows you to synchronize products, orders and stock levels in realtime between your WooCommerce based online store and your physical stores.

== Installation ==

Follow these easy steps to install the plugin:

1. Log on to WordPress admin area and navigate to Plugins -> Add New.
1. Type "OP Kassa for WooCommerce" to search field.
1. Install and activate the plugin from search results.
1. Head over to WooCommerce -> Settings and click on the "OP Kassa" tab to configure the plugin.

== Frequently Asked Questions ==

= I can't connect to OP Kassa? =

Head over to OP Kassa admin panel and make sure that you have activated the WooCommerce addon.

== Changelog ==

= 2.0.0 =
* Replaced OAuth based authentication with the WooCommerce Rest API authentication.

= 1.0.6 =
* It is now possible to choose whether Woo tax calculation is used on orders synced from OP Kassa or if OP Kassa tax calculation is used instead.

= 1.0.5 =
* OP Kassa is now disconnected gracefully if the Woo instance domain is changed. 

= 1.0.4 =
* Fixed a bug relating to Kassa oauth callback url 

= 1.0.3 =
* Updated installation instructions

= 1.0.2 =
* Released to WordPress.org directory

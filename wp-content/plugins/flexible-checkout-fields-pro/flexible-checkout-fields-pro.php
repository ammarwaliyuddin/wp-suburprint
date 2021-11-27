<?php
/**
 * Plugin Name: Flexible Checkout Fields PRO
 * Plugin URI: https://www.wpdesk.net/products/flexible-checkout-fields-pro-woocommerce/
 * Description: Extension to the free version. Adds new field types, custom sections and more.
 * Version: 3.0.8
 * Author: WP Desk
 * Author URI: https://www.wpdesk.net/
 * Text Domain: flexible-checkout-fields-pro
 * Domain Path: /lang/
 * Requires at least: 5.2
 * Tested up to: 5.8
 * WC requires at least: 5.2
 * WC tested up to: 5.5
 * Requires PHP: 7.0
 *
 * Copyright 2018 WP Desk Ltd.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package Flexible Checkout Fields PRO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* THIS VARIABLE CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '3.0.8';

/*
 * Compatible version with base version of FCF plugin:
 * - older major version: no compatibility (disables plugin)
 * - older minor version: compatibility problems (displays notice)
 */
$plugin_version_dev = '1.1';

define( 'FLEXIBLE_CHECKOUT_FIELDS_PRO_VERSION', $plugin_version );
define( 'FLEXIBLE_CHECKOUT_FIELDS_PRO_VERSION_DEV', $plugin_version_dev );

$plugin_name        = 'Flexible Checkout Fields PRO';
$plugin_class_name  = 'Flexible_Checkout_Fields_Pro_Plugin';
$plugin_text_domain = 'flexible-checkout-fields-pro';
$product_id         = 'WooCommerce Flexible Checkout Fields';
$plugin_file        = __FILE__;
$plugin_dir         = dirname( __FILE__ );

define( $plugin_class_name, $plugin_version );

$requirements = array(
	'php'     => '7.0',
	'wp'      => '5.2',
	'plugins' => array(
		array(
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
		),
	),
	'repo_plugins' => array(
		array(
			'name'      => 'flexible-checkout-fields/flexible-checkout-fields.php',
			'nice_name' => 'Flexible Checkout Fields',
			'version'   => '2.1.4',
		),
	),
);

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/plugin-init-php52.php';
require_once __DIR__ . '/inc/wpdesk-woo27-functions.php';

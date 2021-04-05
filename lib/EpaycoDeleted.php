<?php
/**
 * @since             1.0.0
 * @package           ePayco_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       ePayco WooCommerce
 * Description:       Plugin ePayco WooCommerce.
 * Version:           5.1.x
 * Author:            ePayco
 * Author URI:        http://epayco.co
 *Lice
 * Text Domain:       epayco-woocommerce
 * Domain Path:       /languages
 */

require_once(dirname(__FILE__) . '/EpaycoDokan.php');
global $wpdb;
$epayco_id = trim($_REQUEST['epayco_id']);
$result = EpaycoDokan::deleteVendor($epayco_id);
var_dump($result);
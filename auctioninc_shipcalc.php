<?php
/*
  Plugin Name: AuctionInc ShippingCalc for WP E-Commerce
  Plugin URI:
  Description: Obtain shipping rates dynamically via the AuctionInc Shipping API for your orders.
  Version: 1.0
  Author: AuctionInc
  Author URI: http://www.auctioninc.com/
 */

/*
 * Required files
 */
if (!class_exists('ShipRateAPI')) {
    require_once('inc/shiprateapi/ShipRateAPI.inc');
}

require_once('admin/product/product_meta.php');
require_once('admin/purchase/purchase_meta.php');
require_once('inc/auctioninc_shipcalc.php');

/**
 * Localization
 */
load_plugin_textdomain('auctioninc', false, dirname(plugin_basename(__FILE__)) . '/languages/');

/**
 * Plugin page links
 */
function wpec_auctioninc_plugin_links($links) {

    $plugin_links = array(
        '<a href="' . admin_url('options-general.php?page=wpsc-settings&tab=shipping') . '">' . __('Settings', 'wpec_auctioninc') . '</a>',
        '<a href="http://auctioninc.helpserve.com">' . __('Support', 'wpec_auctioninc') . '</a>',
        '<a href= "http://www.auctioninc.com/info/page/ShippingCalc_for_WP_eCommerce" >' . __('Docs', 'wpec_auctioninc') . '</a>',
    );

    return array_merge($plugin_links, $links);
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpec_auctioninc_plugin_links');

/**
 * wpec_auctioninc_admin_scripts function.
 *
 * @access public
 * @return void
 */
function wpec_auctioninc_admin_scripts() {
    $screen = get_current_screen();

    if ($screen->base == 'post') {
        wp_enqueue_script('admin-auctioninc-product', plugins_url('admin/js/auctioninc-shipcalc-product.js', __FILE__), array('jquery'), null, true);
    } elseif ($screen->base == 'settings_page_wpsc-settings') {
        wp_enqueue_script('admin-auctioninc', plugins_url('admin/js/auctioninc-shipcalc-settings.js', __FILE__), array('jquery'), null, true);
    }
}

add_action('admin_enqueue_scripts', 'wpec_auctioninc_admin_scripts');

/**
 * wpec_auctioninc_admin_scripts function.
 *
 * @access public
 * @return void
 */
function wpec_auctioninc_scripts() {
    wp_enqueue_style('auctioninc', plugins_url('frontend/css/auctioninc_shipcalc.css', __FILE__), array(), null);
}

add_action('wp_enqueue_scripts', 'wpec_auctioninc_scripts');

/**
 * wc_auctioninc_admin_notice function.
 *
 * @access public
 * @return void
 */
function wpec_auctioninc_admin_notice() {

    $auctioninc_settings = get_option('wpec_auctioninc');

    if (empty($auctioninc_settings['id'])) {
        echo '<div class="error">
             <p>' . __('An') . ' <a href="http://www.auctioninc.com/info/page/ShippingCalc_for_WP_eCommerce" target="_blank">' . __('AuctionInc', 'wpec_auctioninc') . '</a> ' . __('account is required to use this plugin.', 'wpec_auctioninc') . '</p>
         </div>';
    }
}

add_action('admin_notices', 'wpec_auctioninc_admin_notice');

/**
 * Register the shipping module
 */
function wpec_auctioninc_shipcalc_add($wpsc_shipping_modules) {
    global $auctioninc_shipcalc;
    $auctioninc_shipcalc = new auctioninc_shipcalc();

    $wpsc_shipping_modules[$auctioninc_shipcalc->getInternalName()] = $auctioninc_shipcalc;

    return $wpsc_shipping_modules;
}

add_filter('wpsc_shipping_modules', 'wpec_auctioninc_shipcalc_add');

/**
 * Add shipping meta to purchase log after checkout
 */
function wpec_auctioninc_shipcalc_update_order($purchase_log) {

    // Get shipping data from user's session
    $ship_rates = $_SESSION['auctioninc_response'];
    foreach ($ship_rates['ShipRate'] as $ship_rate) {
        if ($ship_rate['ServiceName'] == $purchase_log->get('shipping_option')) {
            wpsc_add_purchase_meta($purchase_log->get('id'), 'auctioninc_order_shipping_meta', $ship_rate);
            break;
        }
    }

    // Delete session data
    unset($_SESSION['auctioninc_response']);
}

add_action('wpsc_purchase_log_insert', 'wpec_auctioninc_shipcalc_update_order');

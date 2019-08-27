<?php
/**
 * Plugin Name: WooCommerce currency by country
 * Description: Add multi-currency support when WooCommerce
 * Version:     1.0
 * Author:      godofdream
 * Text Domain:
 * Domain Path: /languages
 * License:     BSD3
 */

/* Set Country from cloudflare */
add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );
function change_default_checkout_country() {
  $countrycode = sanitize_text_field($_SERVER["HTTP_CF_IPCOUNTRY"]) ?: 'DE';
  if ($countrycode == 'XX' ) {
    return 'DE';
  }
  return $countrycode;
}


/* Set currency */
add_filter('woocommerce_currency', 'woocommerce_cbc_currency', 1001);

/* Don't sync price / stock-related information in Polylang for WC */
add_filter('pllwc_copy_post_metas', 'pll_mc_remove_metadata_sync', 10, 5);

function woocommerce_cbc_currency($currency) {
    $countrycode = change_default_checkout_country();
    switch ($i) {
    case "DE":
    case "FR":
    case "IT":
    case "AT":
    case "ES":
    case "PT":
    case "IE":
    case "BE":
    case "LU":
    case "GR":
    case "NL":
    case "IE":
    case "FI":
    case "LV":
    case "MT":
    case "SK":
    case "SL":
        return 'EUR';
    case "GB":
        return 'GBP';
    case "CH":
        return 'CHF';
    case "US":
        return 'USD';
    default:
        return $currency;
    }
}


function pll_mc_remove_metadata_sync($to_copy, $sync, $from, $to, $lang) {
    $remove_fields = array(
        '_featured',
        '_manage_stock',
        '_max_price_variation_id',
        '_max_regular_price_variation_id',
        '_max_sale_price_variation_id',
        '_max_variation_price',
        '_max_variation_regular_price',
        '_max_variation_sale_price',
        '_min_price_variation_id',
        '_min_regular_price_variation_id',
        '_min_sale_price_variation_id',
        '_min_variation_price',
        '_min_variation_regular_price',
        '_min_variation_sale_price',
        '_regular_price',
        '_sale_price',
        '_sale_price_dates_from',
        '_sale_price_dates_to',
        '_sold_individually',
        '_tax_class',
        '_tax_status',
        '_price',
        '_stock',
        '_stock_status',
        '_tax_class',
        '_tax_status',
        '_visibility',
        'total_sales',
    );

    foreach($remove_fields as $key_num => $key_to_remove) {
        if(($key = array_search($key_to_remove, $to_copy)) !== false) {
            unset($to_copy[$key]);
        }
    }

    return $to_copy;
}

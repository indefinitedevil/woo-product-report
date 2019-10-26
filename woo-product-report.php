<?php
/*
  Plugin Name: WooCommerce Product Report
  Plugin URI: https://www.drunkoncaffeine.com/
  Description: Generates a report on WooCommerce product sales
  Author: indefinitedevil
  Version: 1.0
  Author URI: https://www.drunkoncaffeine.com/
 */
 
class Woo_Product_Report {
    public static function setup() {
        add_action('admin_menu', [self::class, 'admin_menu']);
    }
    
    public static function admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('Product Sales', 'woo_product_report'),
            __('Product Sales', 'woo_product_report'),
            'manage_woocommerce',
            'woo_product_report',
            [self::class, 'admin_page']
        );
    }
    
    public static function admin_page() {
        $products = array_map([self::class, 'get_product_option'], self::get_products());
        echo '<select>' . $products . '</select>';
    }
    
    public static function get_products() {
        return wc_get_products([
            'limit' => -1
        ]);
    }
    
    public static function get_product_option($product) {
        return '<option value="' . $product->getId() . '">' . $product->getName() . '(#' . $product->getId() . ')</option>';
    }
}
 
add_action('plugins_loaded', array(Woo_Product_Report::class, 'setup'));

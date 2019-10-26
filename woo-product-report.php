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
    }
}
 
add_action('plugins_loaded', array(Woo_Product_Report::class, 'setup'));

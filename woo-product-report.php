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
        add_action('admin_enqueue_scripts', [self::class, 'admin_scripts']);
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
        global $plugin_page;
        $products = array_map([self::class, 'get_product_option'], self::get_products());
?>
<form action="" method="get">
    <input name="page" type="hidden" value="<?php echo $plugin_page; ?>"/>
    <label for="product">Select a product</label>
    <select id="product" name="product"><option/><?php echo implode('', $products); ?></select>
    <button type="submit">View</button>
</form>
<script type="text/javascript">jQuery('#product').selectWoo();</script>
<hr/>
<table class="wp-list-table widefat striped">
<thead>
    <tr>
        <th><?php _e('Order', 'woo_product_report'); ?></th>
        <th><?php _e('Date', 'woo_product_report'); ?></th>
        <th><?php _e('Status', 'woo_product_report'); ?></th>
        <th><?php _e('Name', 'woo_product_report'); ?></th>
        <th><?php _e('Qty', 'woo_product_report'); ?></th>
        <th><?php _e('Notes', 'woo_product_report'); ?></th>
    </tr>
</thead>
<tbody>
<?php
$orders = self::get_orders_from_product($_REQUEST['product'] ?? '');
if (count($orders)): ?>
    <?php foreach ($orders as $order): ?>
        <?php foreach ($order->get_items() as $orderItem): ?>
    <tr>
        <td>#<?php echo $order->get_order_number(); ?></td>
        <td><?php echo ($order->is_paid() ? $order->get_date_completed() : $order->get_date_created())->format('d/m/Y H:i:s'); ?></td>
        <td><?php echo wc_get_order_status_name($order->get_status()); ?></td>
        <td><?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></td>
        <td><?php echo $orderItem->get_quantity(); ?></td>
        <td><?php echo $order->get_customer_note(); ?></td>
    </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="6"><?php _e('No orders for this product', 'woo_product_report'); ?></td>
    </tr>
<?php endif; ?>
</tbody>
</table>
<?php
    }

    public static function admin_scripts() {
        global $plugin_page;
        if ('woo_product_report' == $plugin_page) {
            wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
            wp_enqueue_style('woocommerce_admin_styles');

            wp_register_script('select2', WC()->plugin_url() . '/assets/js/select2/select2.full.min.js', array('jquery'));
            wp_enqueue_script('select2');

            wp_register_script('selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array('jquery'));
            wp_enqueue_script('selectWoo');

            wp_register_script('wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select.min.js', array('jquery', 'selectWoo'));
            wp_enqueue_script('wc-enhanced-select');
        }
    }
    
    public static function get_products() {
        return wc_get_products([
            'limit' => -1
        ]);
    }
    
    public static function get_product_option($product) {
        $selected = !empty($_REQUEST['product']) && $product->get_id() == $_REQUEST['product'] ? ' selected="selected"' : '';
        return '<option value="' . $product->get_id() . '"' . $selected . '>' . $product->get_name() . '(#' . $product->get_id() . ')</option>';
    }

    public static function get_orders_from_product($product_id = null) {
        if ($product_id) {
        }
        return [];
    }
}
 
add_action('plugins_loaded', array(Woo_Product_Report::class, 'setup'));

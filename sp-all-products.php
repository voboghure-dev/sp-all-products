<?php
/**
 * Plugin Name:       SP All Products
 * Description:       WooCommerce product grid for StorePress Themes
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            StorePress
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sp-all-products
 *
 * @package           create-block
 */

function store_press_sp_all_products_block_init() {
	$blocks = array(
		'product-layout/',
	);

	foreach( $blocks as $block ) {
		register_block_type( plugin_dir_path( __FILE__ ) . 'src/blocks/' . $block );
	}
}
add_action( 'init', 'store_press_sp_all_products_block_init' );

/**
 * Create custom category
 */
function store_press_block_categories( $categories ) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'store-press-blocks',
				'title' => __( 'StorePress Blocks', 'sp-all-products' ),
			],
		]
	);
}
add_filter( 'block_categories_all', 'store_press_block_categories' );

/**
 * Log function to view any data in wp-content/debug.log
 * uses: log_it($variable);
 */
if ( ! function_exists( 'log_it' ) ) {
	function log_it( $message ) {
		if ( WP_DEBUG === true ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( "\r\n" . print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}

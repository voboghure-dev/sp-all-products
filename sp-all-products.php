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
  $blocks = [
    'product-layout',
  ];

  foreach ( $blocks as $block ) {
    // log_it( $block );
    if ( $block == 'product-layout' ) {
      register_block_type(
        plugin_dir_path( __FILE__ ) . 'src/blocks/' . $block,
        ['render_callback' => 'render_callback_product_layout']
      );
    } else {
      register_block_type( plugin_dir_path( __FILE__ ) . 'src/blocks/' . $block );
    }
  }
}

add_action( 'init', 'store_press_sp_all_products_block_init' );

/**
 * Create custom category
 */
function store_press_block_categories( $block_categories ) {
  return array_merge(
    $block_categories,
    [
      [
        'slug'  => 'sp-block-category',
        'title' => __( 'StorePress Blocks', 'sp-all-products' ),
      ],
    ]
  );
}

add_filter( 'block_categories_all', 'store_press_block_categories' );

/**
 * Render callback for product layout
 */
function render_callback_product_layout( $attributes, $content ) {
  // Get external products.
  $limit = (int) $attributes['gridColumns'] * (int) $attributes['gridRows'];
  // log_it($limit);
  $args = [
    // 'type'  => 'product',
    'limit' => $limit,
    'order' => 'DESC',
  ];
  $products = wc_get_products( $args );
  // log_it($products);
  ob_start();
  echo '<style> :root { --item-size: ' . (int) $attributes['gridColumns'] . '; }</style>';
  echo '<div class="container">';
  echo '<ul class="products">';
  foreach ( $products as $product ) {
    echo '<li class="products__item">';

    echo '<h3 class="product-title">';
    echo $product->get_name();
    echo '</h3>';

    echo '<div class="product-img">';
    echo $product->get_image( 'woocommerce_thumbnail', ['class' => 'bundle_image'] );
    echo '</div>';

    echo '<p>';
    echo $product->get_short_description();
    echo '</p>';

    echo '<h3 class="product-price">';
    echo $product->get_price();
    echo '</h3>';

    echo '<div class="add-to-card"><a href="#">Add to Card</a></div>';

    echo '</li>';
  }
  echo '</ul>';
  echo '</div>';
  // log_it($attributes);
  return ob_get_clean();
}

/**
 * Debug log function to view any data in wp-content/debug.log
 * Uses: log_it($variable);
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

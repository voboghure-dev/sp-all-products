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
  $layout = ( $attributes['layout'] == 'grid' ) ? 'product-view-grid' : 'product-view-list';

  $limit = (int) $attributes['gridColumns'] * (int) $attributes['gridRows'];
  $args  = [
    'limit' => $limit,
    'order' => 'DESC',
  ];
  $products = wc_get_products( $args );

  ob_start();
  echo '<style>';
  echo ':root { --item-size: ' . (int) $attributes['gridColumns'] . '; } ';
  echo '.container .products { grid-gap: ' . $attributes['gridGap'] . 'px; } ';
  echo '</style>';
  echo '<div class="container">';
  echo '<ul class="products ' . $layout . '">';
  foreach ( $products as $product ) {
    echo '<li class="products__item">';

    echo '<div class="product-img">';
    echo $product->get_image( 'woocommerce_thumbnail' );
    echo '</div>';

    echo '<div class="wrapper-content"><div class="content">';

    if ( $attributes['toggleTitle'] ) {
      echo '<h3 class="product-title">' . $product->get_name() . '</h3>';
    }

    if ( $attributes['toggleDescription'] ) {
      echo '<p>' . $product->get_short_description() . '</p>';
    }

    if ( $attributes['togglePrice'] ) {
      echo '<h3 class="product-price">' . $product->get_price_html() . '</h3>';
    }

    if ( $attributes['toggleRating'] ) {
      echo '<div>Review: ' . $product->get_average_rating() . '</div>';
    }

    if ( $attributes['toggleAddToCart'] ) {
      echo '<div class="add-to-card"><a href="#">Add to Card</a></div>';
    }
    echo '</div></div>';

    echo '</li>';
  }
  echo '</ul>';
  echo '</div>';
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

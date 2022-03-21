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

function sp_all_products_block_init() {
  $blocks = [
    'product-grid',
		'product-list',
  ];

  foreach ( $blocks as $block ) {
    // log_it( $block );
    if ( $block == 'product-grid' ) {
      register_block_type(
        plugin_dir_path( __FILE__ ) . 'src/blocks/' . $block,
        ['render_callback' => 'render_callback_product_grid']
      );
    } else if ( $block == 'product-list' ) {
      register_block_type(
        plugin_dir_path( __FILE__ ) . 'src/blocks/' . $block,
        ['render_callback' => 'render_callback_product_list']
      );
    } else {
      register_block_type( plugin_dir_path( __FILE__ ) . 'src/blocks/' . $block );
    }
  }
}

add_action( 'init', 'sp_all_products_block_init' );

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
 * Render callback for product grid
 */
function render_callback_product_grid( $attributes, $content ) {
  // Grid column
  $columns = (int) $attributes['gridColumns'];

  // Number of product (limit = Columns * Rows)
  $limit = (int) $attributes['gridColumns'] * (int) $attributes['gridRows'];

  // Grid gap by pixel
  $gridGap = $attributes['gridGap'] . 'px;';

  // Product categories
  $tax_query = '';
  if ( isset( $attributes['productCategories'] ) ) {
    $productCategories = json_decode( $attributes['productCategories'] );
    if ( ! empty( $productCategories ) ) {
      foreach ( $productCategories as $cat ) {
        $cat_id[] = $cat->value;
      }
      $tax_query = [
        [
          'taxonomy' => 'product_cat',
          'field'    => 'term_id',
          'terms'    => $cat_id,
          'operator' => 'IN',
        ],
      ];
    }
  }

  // Product offset
  $offset = $attributes['productOffset'];

  // Product order by
  $order_by = $attributes['productOrderBy'];

  // Product order
  $order = $attributes['productOrder'];

  // Get products using arguments
  $args = [
    'tax_query' => is_array( $tax_query ) ? $tax_query : null,
    'offset'    => $offset,
    'limit'     => $limit ? $limit : 0,
    'orderby'   => $order_by ? $order_by : 'title',
    'order'     => $order ? $order : 'ASC',
  ];

  $products = wc_get_products( $args );

  ob_start();
  echo '<div class="sp-container" style="--item-size: ' . $columns . '; --grid-gap: ' . $gridGap . '">';
  echo '<ul class="sp-products sp-product-view-grid">';
  global $product;
  foreach ( $products as $product ) {
    echo '<li class="sp-products__item">';
    // <span class="sp-sales">Sales</span> For sales tag

    echo $product->get_image( 'woocommerce_thumbnail', ['class' => 'sp-product-img'] );

    if ( $attributes['toggleCategory'] ) {
      echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'sp-all-products' ) . ' ', '</span>' );
    }

    if ( $attributes['toggleTitle'] ) {
      echo '<h2 class="sp-product-title">' . $product->get_name() . '</h2>';
    }

    if ( $attributes['toggleRating'] ) {
      echo '<div>Review: ' . wc_get_template( 'single-product/rating.php' ) . '</div>';
    }

    if ( $attributes['toggleDescription'] ) {
      echo '<p>' . $product->get_short_description() . '</p>';
    }

    if ( $attributes['togglePrice'] ) {
      echo '<h3 class="sp-product-price">' . $product->get_price_html() . '</h3>';
    }

    if ( $attributes['toggleAddToCart'] ) {
      echo '<div class="sp-add-to-card"><a href="#">Add to Card</a></div>';
    }

    echo '</li>';
  }
  echo '</ul>';
  echo '</div>';
  return ob_get_clean();
}

/**
 * Render callback for product grid
 */
function render_callback_product_list( $attributes, $content ) {
  // Grid column
  $columns = (int) $attributes['gridColumns'];

  // Number of product (limit = Columns * Rows)
  $limit = (int) $attributes['gridColumns'] * (int) $attributes['gridRows'];

  // Grid gap by pixel
  $gridGap = $attributes['gridGap'] . 'px;';

  // Product categories
  $tax_query = '';
  if ( isset( $attributes['productCategories'] ) ) {
    $productCategories = json_decode( $attributes['productCategories'] );
    if ( ! empty( $productCategories ) ) {
      foreach ( $productCategories as $cat ) {
        $cat_id[] = $cat->value;
      }
      $tax_query = [
        [
          'taxonomy' => 'product_cat',
          'field'    => 'term_id',
          'terms'    => $cat_id,
          'operator' => 'IN',
        ],
      ];
    }
  }

  // Product offset
  $offset = $attributes['productOffset'];

  // Product order by
  $order_by = $attributes['productOrderBy'];

  // Product order
  $order = $attributes['productOrder'];

  // Get products using arguments
  $args = [
    'tax_query' => is_array( $tax_query ) ? $tax_query : null,
    'offset'    => $offset,
    'limit'     => $limit ? $limit : 0,
    'orderby'   => $order_by ? $order_by : 'title',
    'order'     => $order ? $order : 'ASC',
  ];

  $products = wc_get_products( $args );

  ob_start();
  echo '<div class="sp-container" style="--item-size: ' . $columns . '; --grid-gap: ' . $gridGap . '">';
  echo '<ul class="sp-product-view-list">';
  global $product;
  foreach ( $products as $product ) {
    echo '<li class="sp-products__item">';
    // <span class="sp-sales">Sales</span> For sales tag

		echo '<div class="sp-product-img">';
    echo $product->get_image( 'woocommerce_thumbnail' );
		echo '</div>';

		echo '<div class="sp-wrapper-content">';
		echo '<div class="content">';

    if ( $attributes['toggleTitle'] ) {
      echo '<h3 class="sp-product-title">' . $product->get_name() . '</h3>';
    }

    if ( $attributes['toggleDescription'] ) {
      echo '<p>' . $product->get_short_description() . '</p>';
    }

    if ( $attributes['togglePrice'] ) {
      echo '<h3 class="sp-product-price">' . $product->get_price_html() . '</h3>';
    }

    if ( $attributes['toggleAddToCart'] ) {
      echo '<div class="sp-add-to-card"><a href="#">Add to Card</a></div>';
    }
		echo '</div>';
		echo '</div>';
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

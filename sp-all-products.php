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

/**
 * Check WooCommerce dependency
 */
function woocommerce_loaded() {
  if ( ! class_exists( 'WooCommerce' ) ) {
    add_action( 'admin_notices', 'display_admin_notice' );
    // Simple Call A Hook for Deactivate our plugin
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
  }
}

add_action( 'plugins_loaded', 'woocommerce_loaded' );

/**
 * Display an error message when dependency is missing
 */
function display_admin_notice() {
  echo '<div class="error notice">';
  echo '<p>';
  _e( '<strong>Error:</strong>', 'sp-all-products' );
  _e( 'The <em>SP All Products</em> plugin won\'t execute because the following required plugin is not active: <em>WooCommerce</em>.
			Please activate these <a href="plugins.php">plugin</a>.', 'sp-all-products' );
  echo '</p>';
  echo '</div>';
  echo '<div class="updated notice is-dismissible"><p>' . __( 'The <em>SP All Products</em> plugin deactivated.', 'sp-all-products' ) . '</p></div>';
}

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
 * Enqueue css for theme support
 */
function theme_support_styles() {
	$dir = __DIR__;
  wp_enqueue_style(
    'sp-theme-support',
    plugins_url( 'theme-support.css', __FILE__ ), '', filemtime( $dir . '/theme-support.css' )
  );
}

add_action( 'wp_enqueue_scripts', 'theme_support_styles' );

/**
 * Custom class name added to body tag
 */
function add_custom_theme_class() {
	$theme_slug = get_stylesheet();
	$body_class = 'sp-' . $theme_slug;
	$classes[] = $body_class;

	return $classes;
}

add_action( 'body_class', 'add_custom_theme_class' );

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
    'offset'    => $offset ? $offset : 0,
    'limit'     => $limit ? $limit : 0,
    'orderby'   => $order_by ? $order_by : 'title',
    'order'     => $order ? $order : 'ASC',
  ];

  $products = wc_get_products( $args );

  ob_start();
  echo '<section class="sp-wrapper" style="--grid-item-size: ' . $columns . '; --grid-gap: ' . $gridGap . '">';
  echo '<div class="sp-card-grid">';
  global $product;
  foreach ( $products as $product ) {
    echo '<div class="sp-card-grid__item">';
    // <span class="sp-card-grid__item__sale">Sales</span>

		echo '<figure class="sp-card-grid__item__images">';
    echo $product->get_image( 'woocommerce_thumbnail', ['class' => 'sp-card-grid__item__images__image'] );
		echo '</figure>';

    echo '<div class="sp-card-grid__item__content">';

    if ( $attributes['toggleCategory'] ) {
      echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="sp-card-grid__item__content__category">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'sp-all-products' ) . ' ', '</span>' );
    }

    $permalink = $product->get_permalink();
    if ( $attributes['toggleTitle'] ) {
      echo '<a href="' . $permalink . '" alt="' . $product->get_name() . '"><h3 class="sp-card-grid__item__content__title">' . $product->get_name() . '</h3></a>';
    }

    if ( $attributes['toggleRating'] ) {
      echo '<div class="sp-card-grid__item__content__review">';
      wc_get_template( 'single-product/rating.php' );
      echo '</div>';
    }

    if ( $attributes['toggleDescription'] ) {
      echo '<p class="sp-card-grid__item__content__description">' . $product->get_short_description() . '</p>';
    }

    if ( $attributes['togglePrice'] ) {
      echo '<span class="sp-card-grid__item__content__price">' . $product->get_price_html() . '</span>';
    }

    if ( $attributes['toggleAddToCart'] ) {
      echo '<a href="#" class="sp-card-grid__item__content__btn">Add to Card</a>';
    }

    echo '</div>';
    echo '</div>';
  }
  echo '</div>';
  echo '</section>';
  return ob_get_clean();
}

/**
 * Render callback for product list
 */
function render_callback_product_list( $attributes, $content ) {
  // Number of product
  $limit = (int) $attributes['numberOfItem'];

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
    'offset'    => $offset ? $offset : 0,
    'limit'     => $limit ? $limit : 0,
    'orderby'   => $order_by ? $order_by : 'title',
    'order'     => $order ? $order : 'ASC',
  ];

  $products = wc_get_products( $args );

  ob_start();
  echo '<section style="--grid-gap: ' . $gridGap . '">';
  echo '<div class="sp-card-list">';
  global $product;
  foreach ( $products as $product ) {
    echo '<div class="sp-card-list__item">';
    // <span class="sp-card-list__item__sale">Sales</span>

    echo '<figure class="sp-card-list__item__images">';
    echo $product->get_image( 'woocommerce_thumbnail', ['class' => 'sp-card-list__item__images__image'] );
    echo '</figure>';

    echo '<div class="sp-card-list__item__content">';
    echo '<div class="sp-card-list__item__content__item">';

    if ( $attributes['toggleCategory'] ) {
      echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="sp-card-list__item__content__category">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'sp-all-products' ) . ' ', '</span>' );
    }

    $permalink = $product->get_permalink();
    if ( $attributes['toggleTitle'] ) {
      echo '<a href="' . $permalink . '" alt="' . $product->get_name() . '"><h3 class="sp-card-list__item__content__title">' . $product->get_name() . '</h3></a>';
    }

    if ( $attributes['toggleRating'] ) {
      echo '<div class="sp-card-list__item__content__review">';
      wc_get_template( 'single-product/rating.php' );
      echo '</div>';
    }

    if ( $attributes['toggleDescription'] ) {
      echo '<p class="sp-card-list__item__content__description">' . $product->get_short_description() . '</p>';
    }

    if ( $attributes['togglePrice'] ) {
      echo '<span class="sp-card-list__item__content__price">' . $product->get_price_html() . '</span>';
    }

    if ( $attributes['toggleAddToCart'] ) {
      echo '<a href="#" class="sp-card-list__item__content__btn">Add to Card</a>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
  echo '</div>';
  echo '</section>';
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

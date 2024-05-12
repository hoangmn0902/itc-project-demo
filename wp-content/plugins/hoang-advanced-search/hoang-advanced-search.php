<?php
/**
 * Plugin Name:       Hoang Advanced Search
 * Description:       Display your site&#39;s copyright date.
 * Version:           0.1.0
 * Requires at least: 6.2
 * Requires PHP:      7.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hoang-advanced-search
 *
 * @package           create-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ITC_PLUGIN_ADV_SEARCH_URI', plugin_dir_url( __FILE__ ));
define( 'ITC_PLUGIN_ADV_SEARCH_PATH', plugin_dir_path( __FILE__ ));

require_once (ITC_PLUGIN_ADV_SEARCH_PATH.'/inc/itc-functions.php');

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_hoang_advanced_search_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_hoang_advanced_search_init' );

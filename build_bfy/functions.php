<?php
/**
 * understrap functions and definitions
 *
 * @package kenza
 */

$debug = false;
$version = wp_get_theme()['Version'];
$template_location_uri = get_template_directory_uri();
$template_location = get_template_directory();
$environment = getenv("APPLICATION_ENV");

/**
* Load functions to secure your WP install.
*/

require $template_location . '/inc/security.php';

/**
 * New types.
 */
require $template_location . '/inc/ojoho_custom.php';

\ojoho\set_prefix('kenza');

require $template_location . '/inc/kenza.php';
require $template_location . '/inc/addbannerimage.php';

require $template_location . '/inc/widgets.php';

require $template_location . '/inc/widgets/script.php';
require $template_location . '/inc/widgets/short-code.php';
require $template_location . '/inc/widgets/text.php';
require $template_location . '/inc/widgets/chat.php';

/**
 * Scripts
 */
require $template_location . '/inc/scripts.php';

add_action('widgets_init', 'unregister_default_wp_widgets');
add_action('widgets_init', 'kenza_setup_sidebars');
add_action('widgets_init', 'kenza_load_widget');

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');

//add_action('wp_enqueue_scripts', 'custom_theme_assets', 100);
remove_action('template_redirect', 'rest_output_link_header', 11);
//add_action('wp_footer', 'kenza_deregister_scripts');
add_filter('script_loader_tag', 'kenza_defer_attribute', 10, 3);

add_action('wp_enqueue_scripts', 'kenza_scripts', 1);
add_action('admin_enqueue_scripts', 'kenza_admin_scripts', 1);
add_action('init', 'kenza_add_editor_styles');

//if ($environment == "development") {
remove_filter('template_redirect', 'redirect_canonical');
//}

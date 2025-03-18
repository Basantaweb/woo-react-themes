<?php
/**
 * WooCommerce React Theme
 */
function enqueue_react_app() {
    wp_enqueue_script('react-app', get_stylesheet_directory_uri() . '/react-app/build/static/js/main.417691cb.js', array(), '1.0.0', true);
    wp_enqueue_style('react-app-css', get_stylesheet_directory_uri() . '/react-app/build/static/css/main.4d297454.css');

    
}
add_action('wp_enqueue_scripts', 'enqueue_react_app');

function register_custom_menus() {
    register_nav_menus([
        'main-menu' => __('Main Menu', 'your-theme-textdomain'),
    ]);
}
add_action('after_setup_theme', 'register_custom_menus');

function expose_menu_rest_api() {
    register_rest_route('menus/v1', '/main-menu', array(
        'methods'  => 'GET',
        'callback' => 'get_main_menu',
        'permission_callback' => '__return_true',
    ));
}

function get_main_menu() {
    $menu_locations = get_nav_menu_locations();
    $menu_id = $menu_locations['main-menu'] ?? null;
    if (!$menu_id) return [];

    $menu_items = wp_get_nav_menu_items($menu_id);
    $formatted_menu = [];

    foreach ($menu_items as $item) {
        $formatted_menu[] = [
            'id'    => $item->ID,
            'title' => $item->title,
            'url'   => $item->url,
        ];
    }

    return rest_ensure_response(['items' => $formatted_menu]);
}

add_action('rest_api_init', 'expose_menu_rest_api');

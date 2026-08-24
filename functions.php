<?php
/**
 * UpscaleEra Links child theme functions.
 * Elementor-first setup.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_stylesheet_directory() . '/inc/elementor-home-seeder.php';
require_once get_stylesheet_directory() . '/inc/page12-logo-fix.php';

function ue_links_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('custom-logo', array(
        'height'      => 220,
        'width'       => 520,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'ue_links_setup', 20);

/**
 * Keep the child theme lightweight and Elementor-friendly.
 */
function ue_links_child_assets() {
    wp_enqueue_style(
        'ue-links-child',
        get_stylesheet_uri(),
        array(),
        '1.6.0'
    );

    wp_add_inline_style(
        'ue-links-child',
        'html,body.ue-elementor-site{margin:0;padding:0;background:#FFF8F0;} .ue-elementor-home{width:100%;margin:0;padding:0;overflow:hidden;} .ue-elementor-home .elementor{width:100%;} .ue-brand-logo img{max-width:245px;width:100%;height:auto;}'
    );
}
add_action('wp_enqueue_scripts', 'ue_links_child_assets', 50);

function ue_links_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'ue-elementor-site';
    }
    return $classes;
}
add_filter('body_class', 'ue_links_body_classes');

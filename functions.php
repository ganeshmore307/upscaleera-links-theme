<?php
/**
 * UpscaleEra Links child theme functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_stylesheet_directory() . '/inc/customizer.php';

function ue_links_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 220,
        'width'       => 520,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'ue_links_setup', 20);

function ue_links_assets() {
    if (!is_front_page()) {
        return;
    }

    $parent_styles = array(
        'noryx-google-fonts', 'bootstrap', 'e-animations', 'fontawesome',
        'magnific-popup', 'nice-select', 'swiper-noryx', 'noryx-main',
        'noryx-style', 'noryx-rtl'
    );
    foreach ($parent_styles as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
    }

    $parent_scripts = array(
        'bootstrap-bundle', 'swiper-bundle', 'wow', 'appear', 'SplitText',
        'split-type', 'gsap', 'pixi', 'magnific-popup', 'CustomEase',
        'ScrollTrigger', 'counterup', 'waypoints', 'marquee', 'lenis',
        'script', 'noryx-rtl'
    );
    foreach ($parent_scripts as $handle) {
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
    }

    wp_enqueue_style(
        'ue-links-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Manrope:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'ue-links-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'ue-links-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array(),
        '1.0.0',
        true
    );

    $primary = get_theme_mod('ue_primary_color', '#f26622');
    $background = get_theme_mod('ue_background_color', '#fff8f0');
    $ink = get_theme_mod('ue_ink_color', '#151515');

    $inline_css = ':root{' .
        '--ue-primary:' . esc_attr($primary) . ';' .
        '--ue-bg:' . esc_attr($background) . ';' .
        '--ue-ink:' . esc_attr($ink) . ';' .
    '}';
    wp_add_inline_style('ue-links-main', $inline_css);
}
add_action('wp_enqueue_scripts', 'ue_links_assets', 100);

function ue_mod($key, $default = '') {
    return get_theme_mod($key, $default);
}

function ue_logo_url() {
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $src = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($src) {
            return $src;
        }
    }
    return '';
}

function ue_icon($name) {
    $icons = array(
        'website' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 4.5 6 4.5 9S15 18 12 21M12 3C9 6 7.5 9 7.5 12S9 18 12 21"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>',
        'whatsapp' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11.6a8 8 0 0 1-11.7 7.1L4 20l1.3-4.1A8 8 0 1 1 20 11.6Z"/><path d="M9 8.5c.5 2.5 2.1 4.2 4.6 5.1M14 13.6l1.5-1.3M9 8.5 7.8 9.8"/></svg>',
        'linkedin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9v10M6 5.5v.1M10 19v-6c0-2.2 1.4-3.6 3.4-3.6 2.2 0 3.6 1.5 3.6 4.1V19M10 10v9"/></svg>',
        'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.5 21v-8h2.8l.5-3h-3.3V8.2c0-.9.4-1.8 1.9-1.8H18V3.7c-.7-.1-1.5-.2-2.3-.2-2.4 0-4.1 1.5-4.1 4.3V10H9v3h2.6v8"/></svg>',
        'growth' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 17 9 12l3 3 7-8"/><path d="M15 7h4v4"/></svg>',
        'strategy' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18h6M10 21h4"/><path d="M8.5 15.2A6 6 0 1 1 15.5 15c-.9.7-1.5 1.5-1.7 2.5h-3.6c-.2-1-.8-1.7-1.7-2.3Z"/></svg>',
        'web' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 9h18M7 7h.01M10 7h.01"/></svg>',
        'ai' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="7" width="14" height="11" rx="3"/><path d="M12 4v3M9 12h.01M15 12h.01M9 15h6"/></svg>',
    );

    return isset($icons[$name]) ? $icons[$name] : '';
}

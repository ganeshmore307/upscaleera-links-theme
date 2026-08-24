<?php
/**
 * Plugin Name: UpscaleEra Links Route Fix
 * Description: Forces /links/ to resolve to WordPress page ID 1999 on upscaleera.com.
 * Version: 1.1.0
 */

if (!defined('ABSPATH')) { exit; }

/**
 * The real Links page on the main UpscaleEra WordPress installation.
 */
function ue_links_route_page_id() {
    $known = get_post(1999);
    if ($known && $known->post_type === 'page') {
        return 1999;
    }

    $page = get_page_by_path('links', OBJECT, 'page');
    if ($page) {
        return (int) $page->ID;
    }

    return 0;
}

/**
 * Keep page 1999 published with the clean /links/ slug and register a direct rewrite.
 */
function ue_links_force_route() {
    $id = ue_links_route_page_id();
    if (!$id) {
        return;
    }

    $post = get_post($id);
    if ($post && ($post->post_status !== 'publish' || $post->post_name !== 'links' || (int) $post->post_parent !== 0)) {
        wp_update_post(array(
            'ID'          => $id,
            'post_title'  => 'Links',
            'post_status' => 'publish',
            'post_name'   => 'links',
            'post_parent' => 0,
        ));
    }

    update_option('ue_main_links_page_id', $id, false);
    add_rewrite_rule('^links/?$', 'index.php?page_id=' . $id, 'top');
}
add_action('init', 'ue_links_force_route', 1);

/**
 * Bypass stale rewrite tables completely. If WordPress receives /links/,
 * force the main query to page ID 1999 before WP_Query runs.
 */
function ue_links_force_request_to_page($query_vars) {
    $path = wp_parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH);
    if (untrailingslashit((string) $path) === '/links') {
        return array('page_id' => ue_links_route_page_id());
    }
    return $query_vars;
}
add_filter('request', 'ue_links_force_request_to_page', 1);

/**
 * Flush permalink rules once for this version.
 */
function ue_links_flush_route_once() {
    $version = '1.1.0';
    if (get_option('ue_links_route_fix_version') === $version) {
        return;
    }

    $id = ue_links_route_page_id();
    if (!$id) {
        return;
    }

    clean_post_cache($id);
    flush_rewrite_rules(false);
    update_option('ue_links_route_fix_version', $version, false);
}
add_action('wp_loaded', 'ue_links_flush_route_once', 999);

/**
 * Don't let WordPress canonical redirects undo the forced route.
 */
function ue_links_disable_canonical_for_links($redirect_url, $requested_url) {
    $path = wp_parse_url($requested_url, PHP_URL_PATH);
    if (untrailingslashit((string) $path) === '/links') {
        return false;
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'ue_links_disable_canonical_for_links', 10, 2);

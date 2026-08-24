<?php
/**
 * Plugin Name: UpscaleEra Links Route Fix
 * Description: Forces /links/ to resolve to the existing UpscaleEra Links Elementor page and refreshes rewrite rules once.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) { exit; }

function ue_links_route_page_id() {
    $id = (int) get_option('ue_main_links_page_id');
    if ($id && get_post($id) && get_post_type($id) === 'page') {
        return $id;
    }

    $page = get_page_by_path('links', OBJECT, 'page');
    if ($page) {
        return (int) $page->ID;
    }

    // Known page created on the main UpscaleEra site during setup.
    $known = get_post(1999);
    if ($known && $known->post_type === 'page') {
        return 1999;
    }

    return 0;
}

function ue_links_force_route() {
    $id = ue_links_route_page_id();
    if (!$id) {
        return;
    }

    // Keep the page published and request the clean slug where possible.
    $post = get_post($id);
    if ($post && ($post->post_status !== 'publish' || $post->post_name !== 'links')) {
        wp_update_post(array(
            'ID' => $id,
            'post_status' => 'publish',
            'post_name' => 'links',
        ));
    }

    update_option('ue_main_links_page_id', $id, false);

    // Explicit mapping makes /links/ work even if another plugin/theme caused a slug/rewrite conflict.
    add_rewrite_rule('^links/?$', 'index.php?page_id=' . $id, 'top');
}
add_action('init', 'ue_links_force_route', 1);

function ue_links_flush_route_once() {
    $version = '1.0.0';
    if (get_option('ue_links_route_fix_version') === $version) {
        return;
    }

    $id = ue_links_route_page_id();
    if (!$id) {
        return;
    }

    flush_rewrite_rules(false);
    clean_post_cache($id);
    update_option('ue_links_route_fix_version', $version, false);
}
add_action('wp_loaded', 'ue_links_flush_route_once', 999);

// Avoid a canonical redirect undoing the explicit /links/ route on this one page.
function ue_links_disable_canonical_for_links($redirect_url, $requested_url) {
    $path = wp_parse_url($requested_url, PHP_URL_PATH);
    if (untrailingslashit((string) $path) === '/links') {
        return false;
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'ue_links_disable_canonical_for_links', 10, 2);

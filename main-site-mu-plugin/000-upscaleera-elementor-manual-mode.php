<?php
/**
 * Plugin Name: UpscaleEra Links Elementor Manual Mode
 * Description: Stops legacy seed/repair routines from overwriting Elementor edits on page ID 1999. Keeps /links/ editable and publishable from Elementor.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) { exit; }

/**
 * All legacy builders are loaded as MU plugins. Remove their write hooks only
 * after every MU plugin has loaded, but before init/wp_loaded/template_redirect.
 */
function ue_links_enable_elementor_manual_mode() {
    // Stop initial/repeated Elementor database seeders.
    remove_action('init', 'ue_main_create_links_page', 30);
    remove_action('wp_loaded', 'ue_main_links_force_repair', 99);
    remove_action('wp_loaded', 'ue_force_page_1999_links', 200);
    remove_action('wp_loaded', 'ue_force_links_page_1999', 1000);

    // Stop the static frontend fallback so the live URL shows Elementor itself.
    remove_action('template_redirect', 'ue_links_fallback_render', -9999);
}
add_action('muplugins_loaded', 'ue_links_enable_elementor_manual_mode', PHP_INT_MAX);

/**
 * Keep only the page identity/routing stable. Do NOT touch Elementor content.
 */
function ue_links_keep_manual_page_live() {
    $id = 1999;
    $page = get_post($id);
    if (!$page || $page->post_type !== 'page') {
        return;
    }

    $changes = array('ID' => $id);
    $needs_update = false;

    if ($page->post_status !== 'publish') {
        $changes['post_status'] = 'publish';
        $needs_update = true;
    }
    if ($page->post_name !== 'links') {
        $changes['post_name'] = 'links';
        $needs_update = true;
    }
    if ((int) $page->post_parent !== 0) {
        $changes['post_parent'] = 0;
        $needs_update = true;
    }

    if ($needs_update) {
        wp_update_post($changes);
    }

    update_option('ue_main_links_page_id', $id, false);

    // Keep the Elementor canvas setting, but never replace Elementor data.
    update_post_meta($id, '_elementor_edit_mode', 'builder');
    update_post_meta($id, '_elementor_template_type', 'wp-page');
    update_post_meta($id, '_wp_page_template', 'elementor_canvas');
}
add_action('init', 'ue_links_keep_manual_page_live', 2);

/** Always route /links/ to the real Elementor page. */
function ue_links_manual_route($wp) {
    $request = isset($wp->request) ? trim((string) $wp->request, '/') : '';
    if ($request === 'links') {
        $wp->query_vars = array('page_id' => 1999);
    }
}
add_action('parse_request', 'ue_links_manual_route', -1000);

/** One-time permalink refresh for manual mode. */
function ue_links_manual_mode_flush_once() {
    $version = '1.0.0';
    if (get_option('ue_links_manual_mode_version') === $version) {
        return;
    }
    flush_rewrite_rules(false);
    clean_post_cache(1999);
    update_option('ue_links_manual_mode_version', $version, false);
}
add_action('wp_loaded', 'ue_links_manual_mode_flush_once', 2000);

/**
 * Clear Elementor generated CSS after a real Elementor save, without altering data.
 */
function ue_links_after_elementor_save($post_id) {
    if ((int) $post_id !== 1999) {
        return;
    }
    delete_post_meta(1999, '_elementor_css');
    clean_post_cache(1999);

    if (class_exists('\\Elementor\\Plugin')) {
        $elementor = \Elementor\Plugin::instance();
        if ($elementor && isset($elementor->files_manager)) {
            $elementor->files_manager->clear_cache();
        }
    }
}
add_action('elementor/editor/after_save', 'ue_links_after_elementor_save', 10, 1);

function ue_links_manual_mode_notice() {
    if (!current_user_can('manage_options')) { return; }
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra Links:</strong> Elementor manual-edit mode is active. Page 1999 will no longer be overwritten by GitHub seed/repair routines.</p></div>';
}
add_action('admin_notices', 'ue_links_manual_mode_notice');

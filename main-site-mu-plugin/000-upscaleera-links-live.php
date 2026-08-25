<?php
/**
 * Plugin Name: UpscaleEra Links Live
 * Description: Keeps /links/ mapped to Elementor page 1999 without overwriting Elementor content.
 * Version: 2.0.0
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Keep the known Links page public and on the expected slug.
 * IMPORTANT: this never writes _elementor_data or post_content.
 */
function ue_links_live_keep_page_identity() {
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
    update_post_meta($id, '_elementor_edit_mode', 'builder');
    update_post_meta($id, '_elementor_template_type', 'wp-page');
    update_post_meta($id, '_wp_page_template', 'elementor_canvas');
}
add_action('init', 'ue_links_live_keep_page_identity', 2);

/** Route /links/ directly to page 1999. */
function ue_links_live_route($wp) {
    $request = isset($wp->request) ? trim((string) $wp->request, '/') : '';
    if ($request === 'links') {
        $wp->query_vars = array('page_id' => 1999);
    }
}
add_action('parse_request', 'ue_links_live_route', -1000);

/** Do not let canonical redirects undo the explicit route. */
function ue_links_live_disable_canonical($redirect_url, $requested_url) {
    $path = wp_parse_url($requested_url, PHP_URL_PATH);
    if (untrailingslashit((string) $path) === '/links') {
        return false;
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'ue_links_live_disable_canonical', 1, 2);

/** One-time permalink refresh. */
function ue_links_live_flush_once() {
    $version = '2.0.0';
    if (get_option('ue_links_live_version') === $version) {
        return;
    }
    flush_rewrite_rules(false);
    clean_post_cache(1999);
    update_option('ue_links_live_version', $version, false);
}
add_action('wp_loaded', 'ue_links_live_flush_once', 2000);

/**
 * After the user clicks Update/Publish in Elementor, clear generated/cached output
 * only. Never change the saved Elementor document.
 */
function ue_links_live_after_elementor_save($post_id) {
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

    // LiteSpeed listens to this action when installed.
    do_action('litespeed_purge_all');
}
add_action('elementor/editor/after_save', 'ue_links_live_after_elementor_save', 10, 1);

/** Also purge caches when page 1999 is saved through normal WordPress. */
function ue_links_live_after_post_save($post_id, $post, $update) {
    if ((int) $post_id !== 1999 || wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }
    clean_post_cache(1999);
    do_action('litespeed_purge_all');
}
add_action('save_post_page', 'ue_links_live_after_post_save', 20, 3);

function ue_links_live_admin_notice() {
    if (!current_user_can('manage_options')) { return; }
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra Links:</strong> Elementor is now the only source of content for page 1999. GitHub will not overwrite your Elementor edits.</p></div>';
}
add_action('admin_notices', 'ue_links_live_admin_notice');

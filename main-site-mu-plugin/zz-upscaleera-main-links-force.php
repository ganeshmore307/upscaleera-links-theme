<?php
/**
 * Plugin Name: UpscaleEra Main Links Force Repair
 * Description: Forces page ID 1999 to be the /links/ Elementor page and saves its layout through Elementor's own DB API.
 * Version: 1.3.0
 */

if (!defined('ABSPATH')) { exit; }

function ue_force_links_page_1999() {
    $id = 1999;
    $post = get_post($id);
    if (!$post || $post->post_type !== 'page') {
        return;
    }

    // Make the exact known page the canonical /links/ page.
    wp_update_post(array(
        'ID'          => $id,
        'post_title'  => 'Links',
        'post_name'   => 'links',
        'post_status' => 'publish',
        'post_parent' => 0,
        'post_content'=> '',
    ));

    update_option('ue_main_links_page_id', $id, false);
    update_post_meta($id, '_elementor_edit_mode', 'builder');
    update_post_meta($id, '_elementor_template_type', 'wp-page');
    update_post_meta($id, '_wp_page_template', 'elementor_canvas');
    update_post_meta($id, '_elementor_page_settings', array('hide_title' => 'yes'));

    if (!function_exists('ue_main_links_repair_data')) {
        return;
    }

    $data = ue_main_links_repair_data();
    if (!is_array($data) || count($data) < 5) {
        return;
    }

    $saved = false;

    // Preferred method: let Elementor save and normalize its own document data.
    if (class_exists('\\Elementor\\Plugin')) {
        $elementor = \Elementor\Plugin::instance();

        if ($elementor && isset($elementor->db) && method_exists($elementor->db, 'save_editor')) {
            try {
                $result = $elementor->db->save_editor($id, $data);
                $saved = ($result !== false);
            } catch (Throwable $e) {
                $saved = false;
            }
        }

        if ($elementor && isset($elementor->files_manager)) {
            $elementor->files_manager->clear_cache();
        }
    }

    // Fallback for Elementor versions where save_editor behaves differently.
    if (!$saved) {
        $json = wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json) {
            update_post_meta($id, '_elementor_data', wp_slash($json));
        }
    }

    delete_post_meta($id, '_elementor_css');
    delete_post_meta($id, '_elementor_controls_usage');
    clean_post_cache($id);

    // Verify that Elementor data is actually present before marking repair complete.
    $stored = get_post_meta($id, '_elementor_data', true);
    $decoded = is_string($stored) ? json_decode($stored, true) : $stored;

    if (is_array($decoded) && count($decoded) >= 5) {
        update_option('ue_main_links_force_version', '1.3.0', false);
    } else {
        // Do not mark complete; the routine will retry on the next request.
        delete_option('ue_main_links_force_version');
    }

    // Rebuild permalink rules after forcing the slug.
    if (get_option('ue_main_links_rewrite_version') !== '1.3.0') {
        flush_rewrite_rules(false);
        update_option('ue_main_links_rewrite_version', '1.3.0', false);
    }
}
add_action('wp_loaded', 'ue_force_links_page_1999', 1000);

// Force /links/ to the known page even if the theme/plugin rewrite table is stale.
function ue_force_links_parse_request($wp) {
    $request = isset($wp->request) ? trim((string) $wp->request, '/') : '';
    if ($request === 'links') {
        $wp->query_vars = array('page_id' => 1999);
    }
}
add_action('parse_request', 'ue_force_links_parse_request', 0);

function ue_force_links_disable_canonical($redirect_url, $requested_url) {
    $path = wp_parse_url($requested_url, PHP_URL_PATH);
    if (untrailingslashit((string) $path) === '/links') {
        return false;
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'ue_force_links_disable_canonical', 1, 2);

// Admin notice gives a visible confirmation that page 1999 contains Elementor data.
function ue_force_links_admin_notice() {
    if (!current_user_can('manage_options')) { return; }
    $stored = get_post_meta(1999, '_elementor_data', true);
    $decoded = is_string($stored) ? json_decode($stored, true) : $stored;
    if (is_array($decoded) && count($decoded) >= 5) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra Links:</strong> Elementor layout is loaded on page ID 1999.</p></div>';
    }
}
add_action('admin_notices', 'ue_force_links_admin_notice');

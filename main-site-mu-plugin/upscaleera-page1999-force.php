<?php
/**
 * Plugin Name: UpscaleEra Page 1999 Force
 * Description: One-time hard repair for the real UpscaleEra Links page (ID 1999).
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) { exit; }

function ue_force_page_1999_links() {
    $version = '1.0.0';
    if (get_option('ue_page1999_force_version') === $version) {
        return;
    }

    $id = 1999;
    $page = get_post($id);
    if (!$page || $page->post_type !== 'page') {
        return;
    }

    // Make this exact page the public /links/ page.
    wp_update_post(array(
        'ID'          => $id,
        'post_title'  => 'Links',
        'post_name'   => 'links',
        'post_status' => 'publish',
        'post_parent' => 0,
        'post_content'=> '',
    ));

    // Rebuild native Elementor data using the same design generator already deployed.
    if (function_exists('ue_main_links_repair_data')) {
        $data = ue_main_links_repair_data();
    } elseif (function_exists('ue_main_links_data')) {
        $data = ue_main_links_data();
    } else {
        return;
    }

    $json = wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (!$json || !is_array(json_decode($json, true)) || count(json_decode($json, true)) < 5) {
        return;
    }

    delete_post_meta($id, '_elementor_css');
    delete_post_meta($id, '_elementor_controls_usage');

    update_post_meta($id, '_elementor_edit_mode', 'builder');
    update_post_meta($id, '_elementor_template_type', 'wp-page');
    update_post_meta($id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
    update_post_meta($id, '_elementor_page_settings', array('hide_title' => 'yes'));
    update_post_meta($id, '_wp_page_template', 'elementor_canvas');

    // Elementor data needs correct slash handling on save.
    update_post_meta($id, '_elementor_data', wp_slash($json));
    $stored = get_post_meta($id, '_elementor_data', true);
    $decoded = json_decode($stored, true);

    // Fallback for hosts/plugins that preserve slashes differently.
    if (!is_array($decoded) || count($decoded) < 5) {
        update_post_meta($id, '_elementor_data', $json);
        $stored = get_post_meta($id, '_elementor_data', true);
        $decoded = json_decode($stored, true);
    }

    if (!is_array($decoded) || count($decoded) < 5) {
        return;
    }

    update_option('ue_main_links_page_id', $id, false);
    update_option('ue_main_links_seed_version', 'page1999-forced', false);
    update_option('ue_main_links_repair_version', 'page1999-forced', false);

    clean_post_cache($id);
    flush_rewrite_rules(false);

    if (class_exists('\\Elementor\\Plugin')) {
        $elementor = \Elementor\Plugin::instance();
        if ($elementor && isset($elementor->files_manager)) {
            $elementor->files_manager->clear_cache();
        }
    }

    update_option('ue_page1999_force_version', $version, false);
}
add_action('wp_loaded', 'ue_force_page_1999_links', 200);

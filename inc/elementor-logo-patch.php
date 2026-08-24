<?php
/**
 * Replaces the text wordmark at the top of the Elementor Home page
 * with the official UpscaleEra logo image deployed with the child theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_patch_elementor_home_logo() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    $patch_version = '1.0.0';
    if (get_option('ue_elementor_logo_patch_version') === $patch_version) {
        return;
    }

    $front_id = (int) get_option('page_on_front');
    if (!$front_id) {
        return;
    }

    $raw = get_post_meta($front_id, '_elementor_data', true);
    if (!$raw) {
        return;
    }

    $data = json_decode(wp_unslash($raw), true);
    if (!is_array($data) || empty($data[0]['elements'][0]['elements'])) {
        return;
    }

    $logo_url = trailingslashit(get_stylesheet_directory_uri()) . 'assets/images/upscaleera-logo.png';

    $logo_widget = array(
        'id' => substr(md5('ue-logo-' . $front_id), 0, 8),
        'elType' => 'widget',
        'widgetType' => 'image',
        'settings' => array(
            'image' => array(
                'url' => $logo_url,
                'id' => '',
                'alt' => 'UpscaleEra',
                'source' => 'library',
            ),
            'image_size' => 'full',
            'align' => 'center',
            'width' => array('unit' => '%', 'size' => 46, 'sizes' => array()),
            'width_tablet' => array('unit' => '%', 'size' => 52, 'sizes' => array()),
            'width_mobile' => array('unit' => '%', 'size' => 62, 'sizes' => array()),
            '_margin' => array(
                'unit' => 'px',
                'top' => '0',
                'right' => '0',
                'bottom' => '14',
                'left' => '0',
                'isLinked' => false,
            ),
        ),
        'elements' => array(),
        'isInner' => false,
    );

    $first_widget = $data[0]['elements'][0]['elements'][0] ?? array();
    $is_wordmark = isset($first_widget['widgetType']) && $first_widget['widgetType'] === 'heading';
    $title = $first_widget['settings']['title'] ?? '';

    if ($is_wordmark && stripos($title, 'upscale') !== false) {
        $data[0]['elements'][0]['elements'][0] = $logo_widget;
    } else {
        array_unshift($data[0]['elements'][0]['elements'], $logo_widget);
    }

    update_post_meta($front_id, '_elementor_data', wp_slash(wp_json_encode($data)));
    delete_post_meta($front_id, '_elementor_css');
    delete_post_meta($front_id, '_elementor_controls_usage');
    update_option('ue_elementor_logo_patch_version', $patch_version, false);

    if (class_exists('\\Elementor\\Plugin')) {
        $elementor = \Elementor\Plugin::instance();
        if ($elementor && isset($elementor->files_manager)) {
            $elementor->files_manager->clear_cache();
        }
    }

    set_transient('ue_logo_patched_notice', 1, 120);
}
add_action('admin_init', 'ue_patch_elementor_home_logo', 20);

function ue_logo_patched_admin_notice() {
    if (!get_transient('ue_logo_patched_notice')) {
        return;
    }

    delete_transient('ue_logo_patched_notice');
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra logo updated:</strong> the text wordmark on Home was replaced with the official logo image.</p></div>';
}
add_action('admin_notices', 'ue_logo_patched_admin_notice');

<?php
/**
 * One-time direct fix for UpscaleEra Home page ID 12.
 * Replaces the 'upscaleEra' heading widget with the real logo Image widget.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_page12_logo_widget() {
    $logo_url = trailingslashit(get_stylesheet_directory_uri()) . 'assets/images/upscaleera-logo.png';

    return array(
        'id' => substr(md5('ue-page12-logo-v1'), 0, 8),
        'elType' => 'widget',
        'widgetType' => 'image',
        'settings' => array(
            'image' => array(
                'url' => $logo_url,
                'id' => 0,
                'alt' => 'UpscaleEra Logo',
            ),
            'image_size' => 'full',
            'align' => 'center',
            'width' => array('unit' => 'px', 'size' => 245, 'sizes' => array()),
            'width_tablet' => array('unit' => 'px', 'size' => 220, 'sizes' => array()),
            'width_mobile' => array('unit' => 'px', 'size' => 190, 'sizes' => array()),
            '_css_classes' => 'ue-brand-logo',
            '_margin' => array(
                'unit' => 'px',
                'top' => '0',
                'right' => '0',
                'bottom' => '16',
                'left' => '0',
                'isLinked' => false,
            ),
        ),
        'elements' => array(),
        'isInner' => false,
    );
}

function ue_page12_replace_wordmark(&$items, &$changed) {
    if (!is_array($items)) {
        return;
    }

    foreach ($items as &$item) {
        if (!is_array($item)) {
            continue;
        }

        $type = isset($item['widgetType']) ? (string) $item['widgetType'] : '';
        $title = isset($item['settings']['title']) ? wp_strip_all_tags((string) $item['settings']['title']) : '';

        if ($type === 'heading' && stripos($title, 'upscale') !== false) {
            $item = ue_page12_logo_widget();
            $changed = true;
            return;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            ue_page12_replace_wordmark($item['elements'], $changed);
            if ($changed) {
                return;
            }
        }
    }
}

function ue_force_page12_logo() {
    if (!is_admin() || !current_user_can('edit_pages')) {
        return;
    }

    $page_id = 12;
    $version = '12.0.1';

    if (get_option('ue_page12_logo_fix_version') === $version) {
        return;
    }

    $raw = get_post_meta($page_id, '_elementor_data', true);
    if (!$raw) {
        return;
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        $data = json_decode(wp_unslash($raw), true);
    }
    if (!is_array($data)) {
        return;
    }

    $changed = false;
    ue_page12_replace_wordmark($data, $changed);

    if (!$changed) {
        return;
    }

    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data)));
    delete_post_meta($page_id, '_elementor_css');
    delete_post_meta($page_id, '_elementor_controls_usage');
    update_option('ue_page12_logo_fix_version', $version, false);

    if (class_exists('\\Elementor\\Plugin')) {
        $elementor = \Elementor\Plugin::instance();
        if ($elementor && isset($elementor->files_manager)) {
            $elementor->files_manager->clear_cache();
        }
    }

    set_transient('ue_page12_logo_fix_notice', 1, 180);
}
add_action('admin_init', 'ue_force_page12_logo', 999);

function ue_page12_logo_fix_notice() {
    if (!get_transient('ue_page12_logo_fix_notice')) {
        return;
    }
    delete_transient('ue_page12_logo_fix_notice');
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra logo fixed:</strong> page 12 wordmark was replaced by an editable Elementor Image widget.</p></div>';
}
add_action('admin_notices', 'ue_page12_logo_fix_notice');

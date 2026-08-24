<?php
/**
 * One-time direct fix for UpscaleEra Home page ID 12.
 * Removes the text wordmark and inserts the real logo as the first Elementor widget.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_page12_logo_widget() {
    $logo_url = trailingslashit(get_stylesheet_directory_uri()) . 'assets/images/upscaleera-logo.png';

    return array(
        'id' => substr(md5('ue-page12-logo-v13'), 0, 8),
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

function ue_page12_remove_old_brand_widgets(&$items) {
    if (!is_array($items)) {
        return;
    }

    foreach ($items as $index => &$item) {
        if (!is_array($item)) {
            continue;
        }

        $type = isset($item['widgetType']) ? (string) $item['widgetType'] : '';
        $settings = isset($item['settings']) && is_array($item['settings']) ? $item['settings'] : array();
        $remove = false;

        if ($type === 'heading' && !empty($settings['title']) && stripos((string) $settings['title'], 'upscale') !== false) {
            $remove = true;
        }

        if ($type === 'image') {
            $url = isset($settings['image']['url']) ? (string) $settings['image']['url'] : '';
            $classes = isset($settings['_css_classes']) ? (string) $settings['_css_classes'] : '';
            if (stripos($url, 'upscaleera-logo') !== false || stripos($classes, 'ue-brand-logo') !== false) {
                $remove = true;
            }
        }

        if ($remove) {
            unset($items[$index]);
            continue;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            ue_page12_remove_old_brand_widgets($item['elements']);
        }
    }

    $items = array_values($items);
}

function ue_page12_insert_logo_first(&$items) {
    if (!is_array($items)) {
        return false;
    }

    foreach ($items as &$item) {
        if (!is_array($item)) {
            continue;
        }

        $el_type = isset($item['elType']) ? (string) $item['elType'] : '';

        if ($el_type === 'column' || $el_type === 'container') {
            if (!isset($item['elements']) || !is_array($item['elements'])) {
                $item['elements'] = array();
            }
            array_unshift($item['elements'], ue_page12_logo_widget());
            return true;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            if (ue_page12_insert_logo_first($item['elements'])) {
                return true;
            }
        }
    }

    return false;
}

function ue_force_page12_logo() {
    if (!is_admin() || !current_user_can('edit_pages')) {
        return;
    }

    $page_id = 12;
    $version = '13.0.0';

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

    ue_page12_remove_old_brand_widgets($data);

    if (!ue_page12_insert_logo_first($data)) {
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
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra logo placed:</strong> the actual logo is now the first Elementor widget on Home page ID 12.</p></div>';
}
add_action('admin_notices', 'ue_page12_logo_fix_notice');

<?php
/**
 * Replaces the UpscaleEra text wordmark anywhere in the Elementor Home page
 * with the official UpscaleEra logo image deployed with the child theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_logo_widget_for_elementor($front_id) {
    $logo_url = content_url('/themes/upscaleera-links-theme/assets/images/upscaleera-logo.png');

    return array(
        'id' => substr(md5('ue-logo-' . $front_id . '-v2'), 0, 8),
        'elType' => 'widget',
        'widgetType' => 'image',
        'settings' => array(
            'image' => array(
                'url' => $logo_url,
                'id' => 0,
                'alt' => 'UpscaleEra',
            ),
            'image_size' => 'full',
            'align' => 'center',
            'width' => array('unit' => 'px', 'size' => 245, 'sizes' => array()),
            'width_tablet' => array('unit' => 'px', 'size' => 220, 'sizes' => array()),
            'width_mobile' => array('unit' => 'px', 'size' => 195, 'sizes' => array()),
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

function ue_replace_wordmark_recursive(&$items, $front_id, &$replaced) {
    if (!is_array($items)) {
        return;
    }

    foreach ($items as $index => &$item) {
        if (!is_array($item)) {
            continue;
        }

        if (
            isset($item['widgetType']) &&
            $item['widgetType'] === 'heading' &&
            isset($item['settings']['title']) &&
            stripos((string) $item['settings']['title'], 'upscale') !== false
        ) {
            $item = ue_logo_widget_for_elementor($front_id);
            $replaced = true;
            continue;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            ue_replace_wordmark_recursive($item['elements'], $front_id, $replaced);
        }
    }
}

function ue_patch_elementor_home_logo() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    $patch_version = '2.0.0';
    if (get_option('ue_elementor_logo_patch_version') === $patch_version) {
        return;
    }

    $front_id = (int) get_option('page_on_front');

    if (!$front_id) {
        $home = get_page_by_path('home');
        if ($home) {
            $front_id = (int) $home->ID;
        }
    }

    if (!$front_id) {
        return;
    }

    $raw = get_post_meta($front_id, '_elementor_data', true);
    if (!$raw) {
        return;
    }

    $data = json_decode(wp_unslash($raw), true);
    if (!is_array($data)) {
        return;
    }

    $replaced = false;
    ue_replace_wordmark_recursive($data, $front_id, $replaced);

    if (!$replaced) {
        // If no text wordmark exists, add the logo to the top of the first column.
        if (!empty($data[0]['elements'][0]['elements']) && is_array($data[0]['elements'][0]['elements'])) {
            array_unshift($data[0]['elements'][0]['elements'], ue_logo_widget_for_elementor($front_id));
            $replaced = true;
        }
    }

    if ($replaced) {
        update_post_meta($front_id, '_elementor_data', wp_slash(wp_json_encode($data)));
        delete_post_meta($front_id, '_elementor_css');
        delete_post_meta($front_id, '_elementor_controls_usage');

        if (class_exists('\\Elementor\\Plugin')) {
            $elementor = \Elementor\Plugin::instance();
            if ($elementor && isset($elementor->files_manager)) {
                $elementor->files_manager->clear_cache();
            }
        }
    }

    update_option('ue_elementor_logo_patch_version', $patch_version, false);
    set_transient('ue_logo_patched_notice', 1, 120);
}
add_action('admin_init', 'ue_patch_elementor_home_logo', 50);

function ue_logo_patched_admin_notice() {
    if (!get_transient('ue_logo_patched_notice')) {
        return;
    }

    delete_transient('ue_logo_patched_notice');
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra logo patch applied:</strong> refresh Home or reopen Edit with Elementor.</p></div>';
}
add_action('admin_notices', 'ue_logo_patched_admin_notice');

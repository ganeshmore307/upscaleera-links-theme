<?php
/**
 * Adds a blank, editable Elementor Image widget at the top of the UpscaleEra
 * Home page so the logo can be uploaded/replaced directly in Elementor.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_logo_upload_slot_widget($page_id) {
    return array(
        'id' => substr(md5('ue-logo-upload-slot-' . $page_id), 0, 8),
        'elType' => 'widget',
        'widgetType' => 'image',
        'settings' => array(
            'image' => array(
                'url' => '',
                'id' => '',
                'alt' => 'Upload UpscaleEra Logo',
            ),
            'image_size' => 'full',
            'align' => 'center',
            'width' => array('unit' => 'px', 'size' => 245, 'sizes' => array()),
            'width_tablet' => array('unit' => 'px', 'size' => 220, 'sizes' => array()),
            'width_mobile' => array('unit' => 'px', 'size' => 190, 'sizes' => array()),
            '_css_classes' => 'ue-logo-upload-slot',
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

function ue_page_data_is_upscaleera_home($items) {
    if (!is_array($items)) {
        return false;
    }

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $settings = isset($item['settings']) && is_array($item['settings']) ? $item['settings'] : array();
        foreach (array('title', 'editor', 'description_text', 'title_text') as $key) {
            if (!empty($settings[$key])) {
                $text = wp_strip_all_tags((string) $settings[$key]);
                if (stripos($text, 'Performance. Creativity. Growth.') !== false || stripos($text, 'DIGITAL GROWTH AGENCY') !== false) {
                    return true;
                }
            }
        }

        if (!empty($item['elements']) && ue_page_data_is_upscaleera_home($item['elements'])) {
            return true;
        }
    }

    return false;
}

function ue_remove_logo_area_widgets(&$items) {
    if (!is_array($items)) {
        return;
    }

    foreach ($items as $index => &$item) {
        if (!is_array($item)) {
            continue;
        }

        $type = isset($item['widgetType']) ? $item['widgetType'] : '';
        $settings = isset($item['settings']) && is_array($item['settings']) ? $item['settings'] : array();
        $remove = false;

        if ($type === 'heading' && !empty($settings['title']) && stripos((string) $settings['title'], 'upscale') !== false) {
            $remove = true;
        }

        if ($type === 'image') {
            $url = isset($settings['image']['url']) ? (string) $settings['image']['url'] : '';
            $classes = isset($settings['_css_classes']) ? (string) $settings['_css_classes'] : '';
            if (stripos($url, 'upscaleera-logo') !== false || stripos($classes, 'ue-logo-upload-slot') !== false) {
                $remove = true;
            }
        }

        if ($remove) {
            unset($items[$index]);
            continue;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            ue_remove_logo_area_widgets($item['elements']);
        }
    }

    $items = array_values($items);
}

function ue_insert_logo_upload_slot(&$items, $page_id) {
    if (!is_array($items)) {
        return false;
    }

    foreach ($items as &$item) {
        if (!is_array($item)) {
            continue;
        }

        if (($item['elType'] ?? '') === 'column' || ($item['elType'] ?? '') === 'container') {
            if (!isset($item['elements']) || !is_array($item['elements'])) {
                $item['elements'] = array();
            }
            array_unshift($item['elements'], ue_logo_upload_slot_widget($page_id));
            return true;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            if (ue_insert_logo_upload_slot($item['elements'], $page_id)) {
                return true;
            }
        }
    }

    return false;
}

function ue_create_elementor_logo_upload_slot() {
    if (!is_admin() || !current_user_can('edit_pages')) {
        return;
    }

    $patch_version = '1.0.1';
    if (get_option('ue_elementor_logo_upload_slot_version') === $patch_version) {
        return;
    }

    $page_ids = get_posts(array(
        'post_type' => 'page',
        'post_status' => array('publish', 'draft', 'private', 'pending'),
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => '_elementor_data',
                'compare' => 'EXISTS',
            ),
        ),
    ));

    foreach ($page_ids as $page_id) {
        $raw = get_post_meta($page_id, '_elementor_data', true);
        if (!$raw) {
            continue;
        }

        $data = json_decode(wp_unslash($raw), true);
        if (!is_array($data) || !ue_page_data_is_upscaleera_home($data)) {
            continue;
        }

        ue_remove_logo_area_widgets($data);

        if (!ue_insert_logo_upload_slot($data, (int) $page_id)) {
            continue;
        }

        update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data)));
        delete_post_meta($page_id, '_elementor_css');
        delete_post_meta($page_id, '_elementor_controls_usage');
        update_option('ue_elementor_logo_upload_slot_version', $patch_version, false);
        update_option('ue_elementor_logo_upload_slot_page_id', (int) $page_id, false);

        if (class_exists('\\Elementor\\Plugin')) {
            $elementor = \Elementor\Plugin::instance();
            if ($elementor && isset($elementor->files_manager)) {
                $elementor->files_manager->clear_cache();
            }
        }

        set_transient('ue_logo_upload_slot_notice', (int) $page_id, 180);
        break;
    }
}
add_action('admin_init', 'ue_create_elementor_logo_upload_slot', 200);

function ue_logo_upload_slot_admin_notice() {
    $page_id = (int) get_transient('ue_logo_upload_slot_notice');
    if (!$page_id) {
        return;
    }

    delete_transient('ue_logo_upload_slot_notice');
    echo '<div class="notice notice-success is-dismissible"><p><strong>Logo upload area added:</strong> open page ID ' . esc_html($page_id) . ' with Elementor. The first widget is now an empty Image widget. Click it → Choose Image → Upload Files.</p></div>';
}
add_action('admin_notices', 'ue_logo_upload_slot_admin_notice');

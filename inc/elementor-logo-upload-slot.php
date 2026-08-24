<?php
/**
 * Final one-time UpscaleEra Elementor logo placement.
 * Places the actual UpscaleEra logo at the top of the hero as an editable Image widget.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_final_logo_widget($page_id) {
    $logo_url = trailingslashit(get_stylesheet_directory_uri()) . 'assets/images/upscaleera-logo.png';

    return array(
        'id' => substr(md5('ue-final-logo-' . $page_id), 0, 8),
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
                'bottom' => '18',
                'left' => '0',
                'isLinked' => false,
            ),
        ),
        'elements' => array(),
        'isInner' => false,
    );
}

function ue_final_item_has_hero_marker($item) {
    if (!is_array($item)) {
        return false;
    }

    $settings = isset($item['settings']) && is_array($item['settings']) ? $item['settings'] : array();
    foreach (array('title', 'editor', 'title_text', 'description_text') as $key) {
        if (!empty($settings[$key])) {
            $text = wp_strip_all_tags((string) $settings[$key]);
            if (
                stripos($text, 'Performance. Creativity. Growth.') !== false ||
                stripos($text, 'DIGITAL GROWTH AGENCY') !== false ||
                stripos($text, 'DIGITAL GROWTH STUDIO') !== false
            ) {
                return true;
            }
        }
    }

    if (!empty($item['elements']) && is_array($item['elements'])) {
        foreach ($item['elements'] as $child) {
            if (ue_final_item_has_hero_marker($child)) {
                return true;
            }
        }
    }

    return false;
}

function ue_final_page_is_target($data) {
    if (!is_array($data)) {
        return false;
    }

    foreach ($data as $item) {
        if (ue_final_item_has_hero_marker($item)) {
            return true;
        }
    }

    return false;
}

function ue_final_remove_old_logo_widgets(&$items) {
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
            if (
                stripos($url, 'upscaleera-logo') !== false ||
                stripos($classes, 'ue-logo-upload-slot') !== false ||
                stripos($classes, 'ue-brand-logo') !== false
            ) {
                $remove = true;
            }
        }

        if ($remove) {
            unset($items[$index]);
            continue;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            ue_final_remove_old_logo_widgets($item['elements']);
        }
    }

    $items = array_values($items);
}

function ue_final_insert_logo_in_hero(&$items, $page_id) {
    if (!is_array($items)) {
        return false;
    }

    foreach ($items as &$item) {
        if (!is_array($item)) {
            continue;
        }

        $el_type = isset($item['elType']) ? (string) $item['elType'] : '';
        if (($el_type === 'column' || $el_type === 'container') && ue_final_item_has_hero_marker($item)) {
            if (!isset($item['elements']) || !is_array($item['elements'])) {
                $item['elements'] = array();
            }
            array_unshift($item['elements'], ue_final_logo_widget($page_id));
            return true;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            if (ue_final_insert_logo_in_hero($item['elements'], $page_id)) {
                return true;
            }
        }
    }

    return false;
}

function ue_apply_final_home_logo() {
    if (!is_admin() || !current_user_can('edit_pages')) {
        return;
    }

    $patch_version = '10.0.0';
    if (get_option('ue_final_home_logo_version') === $patch_version) {
        return;
    }

    $candidate_ids = array();

    $front_id = (int) get_option('page_on_front');
    if ($front_id) {
        $candidate_ids[] = $front_id;
    }

    $home = get_page_by_path('home');
    if ($home) {
        $candidate_ids[] = (int) $home->ID;
    }

    $elementor_pages = get_posts(array(
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

    $candidate_ids = array_values(array_unique(array_merge($candidate_ids, array_map('intval', $elementor_pages))));

    foreach ($candidate_ids as $page_id) {
        $raw = get_post_meta($page_id, '_elementor_data', true);
        if (!$raw) {
            continue;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            $data = json_decode(wp_unslash($raw), true);
        }

        if (!is_array($data) || !ue_final_page_is_target($data)) {
            continue;
        }

        ue_final_remove_old_logo_widgets($data);

        if (!ue_final_insert_logo_in_hero($data, $page_id)) {
            continue;
        }

        update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data)));
        delete_post_meta($page_id, '_elementor_css');
        delete_post_meta($page_id, '_elementor_controls_usage');
        update_option('ue_final_home_logo_version', $patch_version, false);
        update_option('ue_final_home_logo_page_id', $page_id, false);

        if (class_exists('\\Elementor\\Plugin')) {
            $elementor = \Elementor\Plugin::instance();
            if ($elementor && isset($elementor->files_manager)) {
                $elementor->files_manager->clear_cache();
            }
        }

        set_transient('ue_final_home_logo_notice', $page_id, 180);
        break;
    }
}
add_action('admin_init', 'ue_apply_final_home_logo', 300);

function ue_final_home_logo_notice() {
    $page_id = (int) get_transient('ue_final_home_logo_notice');
    if (!$page_id) {
        return;
    }

    delete_transient('ue_final_home_logo_notice');
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra logo placed:</strong> the actual logo is now the first editable Elementor widget on page ID ' . esc_html($page_id) . '.</p></div>';
}
add_action('admin_notices', 'ue_final_home_logo_notice');

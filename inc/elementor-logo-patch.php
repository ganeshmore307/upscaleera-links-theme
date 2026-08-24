<?php
/**
 * Ensures the actual UpscaleEra Elementor Home page has a real editable
 * Image widget for the logo at the top of the hero.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_logo_widget_for_elementor($page_id) {
    $logo_url = content_url('/themes/upscaleera-links-theme/assets/images/upscaleera-logo.png');

    return array(
        'id' => substr(md5('ue-logo-widget-' . $page_id . '-v3'), 0, 8),
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

function ue_elementor_data_contains_upscaleera($items) {
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
                if (stripos($text, 'upscale') !== false || stripos($text, 'Performance. Creativity. Growth.') !== false) {
                    return true;
                }
            }
        }

        if (!empty($item['elements']) && ue_elementor_data_contains_upscaleera($item['elements'])) {
            return true;
        }
    }

    return false;
}

function ue_remove_wordmark_and_existing_logo_recursive(&$items) {
    if (!is_array($items)) {
        return;
    }

    foreach ($items as $index => &$item) {
        if (!is_array($item)) {
            continue;
        }

        $widget_type = isset($item['widgetType']) ? $item['widgetType'] : '';
        $settings = isset($item['settings']) && is_array($item['settings']) ? $item['settings'] : array();

        if ($widget_type === 'heading' && !empty($settings['title']) && stripos((string) $settings['title'], 'upscale') !== false) {
            unset($items[$index]);
            continue;
        }

        if ($widget_type === 'image' && !empty($settings['image']['url']) && stripos((string) $settings['image']['url'], 'upscaleera-logo') !== false) {
            unset($items[$index]);
            continue;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            ue_remove_wordmark_and_existing_logo_recursive($item['elements']);
        }
    }

    $items = array_values($items);
}

function ue_insert_logo_into_first_content_container(&$items, $page_id) {
    if (!is_array($items)) {
        return false;
    }

    foreach ($items as &$item) {
        if (!is_array($item)) {
            continue;
        }

        if (!empty($item['elements']) && is_array($item['elements'])) {
            // Classic Elementor section -> column -> widgets.
            if (($item['elType'] ?? '') === 'column') {
                array_unshift($item['elements'], ue_logo_widget_for_elementor($page_id));
                return true;
            }

            // Elementor Containers/Flexbox.
            if (($item['elType'] ?? '') === 'container') {
                array_unshift($item['elements'], ue_logo_widget_for_elementor($page_id));
                return true;
            }

            if (ue_insert_logo_into_first_content_container($item['elements'], $page_id)) {
                return true;
            }
        }
    }

    return false;
}

function ue_patch_actual_elementor_home_logo() {
    if (!is_admin() || !current_user_can('edit_pages')) {
        return;
    }

    $patch_version = '3.0.0';
    if (get_option('ue_elementor_logo_patch_version') === $patch_version) {
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
    $patched_page_id = 0;

    foreach ($candidate_ids as $page_id) {
        $raw = get_post_meta($page_id, '_elementor_data', true);
        if (!$raw) {
            continue;
        }

        $data = json_decode(wp_unslash($raw), true);
        if (!is_array($data) || !ue_elementor_data_contains_upscaleera($data)) {
            continue;
        }

        ue_remove_wordmark_and_existing_logo_recursive($data);

        if (!ue_insert_logo_into_first_content_container($data, $page_id)) {
            continue;
        }

        update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data)));
        delete_post_meta($page_id, '_elementor_css');
        delete_post_meta($page_id, '_elementor_controls_usage');
        $patched_page_id = $page_id;
        break;
    }

    if ($patched_page_id && class_exists('\\Elementor\\Plugin')) {
        $elementor = \Elementor\Plugin::instance();
        if ($elementor && isset($elementor->files_manager)) {
            $elementor->files_manager->clear_cache();
        }
    }

    if ($patched_page_id) {
        update_option('ue_elementor_logo_patch_version', $patch_version, false);
        update_option('ue_elementor_logo_patched_page_id', $patched_page_id, false);
        set_transient('ue_logo_patched_notice', $patched_page_id, 180);
    }
}
add_action('admin_init', 'ue_patch_actual_elementor_home_logo', 100);

function ue_logo_patched_admin_notice() {
    $page_id = (int) get_transient('ue_logo_patched_notice');
    if (!$page_id) {
        return;
    }

    delete_transient('ue_logo_patched_notice');
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra logo area created:</strong> a real Elementor Image widget was added to page ID ' . esc_html($page_id) . '. Open that page with Elementor and click the logo to replace/edit it.</p></div>';
}
add_action('admin_notices', 'ue_logo_patched_admin_notice');

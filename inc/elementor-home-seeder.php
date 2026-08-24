<?php
/**
 * One-time native Elementor homepage installer for UpscaleEra Links.
 * Writes directly to the Home page's Elementor data. No template import required.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_el_id() {
    return substr(md5(uniqid((string) wp_rand(), true)), 0, 8);
}

function ue_el_size($size) {
    return array('unit' => 'px', 'size' => $size, 'sizes' => array());
}

function ue_el_edge($top = 0, $right = 0, $bottom = 0, $left = 0, $linked = false) {
    return array(
        'unit' => 'px',
        'top' => (string) $top,
        'right' => (string) $right,
        'bottom' => (string) $bottom,
        'left' => (string) $left,
        'isLinked' => $linked,
    );
}

function ue_el_widget($type, $settings) {
    return array(
        'id' => ue_el_id(),
        'elType' => 'widget',
        'widgetType' => $type,
        'settings' => $settings,
        'elements' => array(),
        'isInner' => false,
    );
}

function ue_el_heading($text, $size = 40, $color = '#151515', $align = 'center', $tag = 'h2', $family = 'DM Serif Display') {
    return ue_el_widget('heading', array(
        'title' => $text,
        'header_size' => $tag,
        'align' => $align,
        'title_color' => $color,
        'typography_typography' => 'custom',
        'typography_font_family' => $family,
        'typography_font_size' => ue_el_size($size),
        'typography_font_weight' => $family === 'Manrope' ? '700' : '400',
        'typography_line_height' => array('unit' => 'em', 'size' => 1.08, 'sizes' => array()),
        '_margin' => ue_el_edge(0, 0, 0, 0),
    ));
}

function ue_el_text($html, $size = 14, $color = '#756E66', $align = 'center') {
    return ue_el_widget('text-editor', array(
        'editor' => $html,
        'align' => $align,
        'text_color' => $color,
        'typography_typography' => 'custom',
        'typography_font_family' => 'Manrope',
        'typography_font_size' => ue_el_size($size),
        'typography_line_height' => array('unit' => 'em', 'size' => 1.55, 'sizes' => array()),
        '_margin' => ue_el_edge(0, 0, 0, 0),
    ));
}

function ue_el_button($label, $url, $background = '#F26622', $color = '#FFFFFF') {
    return ue_el_widget('button', array(
        'text' => $label,
        'link' => array('url' => $url, 'is_external' => 'on', 'nofollow' => ''),
        'align' => 'justify',
        'size' => 'md',
        'button_text_color' => $color,
        'background_color' => $background,
        'typography_typography' => 'custom',
        'typography_font_family' => 'Manrope',
        'typography_font_size' => ue_el_size(14),
        'typography_font_weight' => '700',
        'border_radius' => array('unit' => 'px', 'top' => '13', 'right' => '13', 'bottom' => '13', 'left' => '13', 'isLinked' => true),
        'text_padding' => ue_el_edge(16, 22, 16, 22),
        '_margin' => ue_el_edge(0, 0, 0, 0),
    ));
}

function ue_el_icon_box($title, $description, $url, $icon, $library = 'fa-solid') {
    return ue_el_widget('icon-box', array(
        'selected_icon' => array('value' => $icon, 'library' => $library),
        'title_text' => $title,
        'description_text' => $description,
        'link' => array('url' => $url, 'is_external' => 'on', 'nofollow' => ''),
        'position' => 'left',
        'title_size' => 'h4',
        'primary_color' => '#F26622',
        'secondary_color' => '#FFF3E8',
        'title_color' => '#151515',
        'description_color' => '#82796F',
        'title_typography_typography' => 'custom',
        'title_typography_font_family' => 'Manrope',
        'title_typography_font_size' => ue_el_size(14),
        'title_typography_font_weight' => '700',
        'description_typography_typography' => 'custom',
        'description_typography_font_family' => 'Manrope',
        'description_typography_font_size' => ue_el_size(11),
        '_background_background' => 'classic',
        '_background_color' => '#FFFFFF',
        '_border_border' => 'solid',
        '_border_width' => array('unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true),
        '_border_color' => '#EFE4D8',
        '_border_radius' => array('unit' => 'px', 'top' => '18', 'right' => '18', 'bottom' => '18', 'left' => '18', 'isLinked' => true),
        '_padding' => ue_el_edge(16, 16, 16, 16),
        '_margin' => ue_el_edge(0, 0, 10, 0),
    ));
}

function ue_el_section($elements, $outer_background = '#FFF8F0', $column_background = '', $padding = null, $margin = null, $radius = 0) {
    $column_settings = array('_column_size' => 100);

    if ($column_background) {
        $column_settings['_background_background'] = 'classic';
        $column_settings['_background_color'] = $column_background;
        $column_settings['_padding'] = $padding ?: ue_el_edge(20, 20, 20, 20);
        $column_settings['_border_radius'] = array(
            'unit' => 'px', 'top' => (string) $radius, 'right' => (string) $radius,
            'bottom' => (string) $radius, 'left' => (string) $radius, 'isLinked' => true
        );
    }

    return array(
        'id' => ue_el_id(),
        'elType' => 'section',
        'settings' => array(
            'content_width' => 'boxed',
            'boxed_width' => array('unit' => 'px', 'size' => 560, 'sizes' => array()),
            'gap' => 'no',
            'background_background' => 'classic',
            'background_color' => $outer_background,
            'padding' => $column_background ? ue_el_edge(0, 18, 0, 18) : ($padding ?: ue_el_edge(0, 18, 0, 18)),
            'margin' => $margin ?: ue_el_edge(0, 0, 0, 0),
        ),
        'elements' => array(array(
            'id' => ue_el_id(),
            'elType' => 'column',
            'settings' => $column_settings,
            'elements' => $elements,
            'isInner' => false,
        )),
        'isInner' => false,
    );
}

function ue_build_elementor_home_data() {
    $data = array();

    $data[] = ue_el_section(array(
        ue_el_heading('upscaleEra', 31, '#151515', 'center', 'div', 'Manrope'),
        ue_el_text('<strong style="letter-spacing:.24em;color:#F26622;font-size:10px;">DIGITAL GROWTH AGENCY</strong>', 10, '#F26622'),
        ue_el_heading('Performance. Creativity. Growth.', 42, '#151515', 'center', 'h1'),
        ue_el_text('We combine performance marketing, creative, web and automation to build connected growth systems for ambitious brands.', 13, '#746E67'),
    ), '#FFF8F0', '', ue_el_edge(42, 22, 24, 22));

    $data[] = ue_el_section(array(
        ue_el_text('<strong style="font-size:18px;color:#fff;">Ready to grow your brand?</strong><br><span style="font-size:12px;color:#BEB6AE;">Tell us what you want to scale.</span>', 13, '#FFFFFF', 'left'),
        ue_el_button('Chat on WhatsApp  →', 'https://wa.me/919764970030', '#F26622', '#FFFFFF'),
    ), '#FFF8F0', '#151515', ue_el_edge(19, 19, 19, 19), ue_el_edge(0, 0, 14, 0), 22);

    $links = array(
        array('Visit Our Website', 'Explore our work and services', 'https://upscaleera.com/', 'fas fa-globe', 'fa-solid'),
        array('Instagram', 'Creative work, campaigns and updates', 'https://www.instagram.com/upscaleera.agency/', 'fab fa-instagram', 'fa-brands'),
        array('WhatsApp', 'Start a direct conversation with us', 'https://wa.me/919764970030', 'fab fa-whatsapp', 'fa-brands'),
        array('LinkedIn', 'Agency insights and professional updates', 'https://www.linkedin.com/company/upscaleera/', 'fab fa-linkedin-in', 'fa-brands'),
        array('Facebook', 'Updates, work and announcements', 'https://www.facebook.com/UpscaleEra/', 'fab fa-facebook-f', 'fa-brands'),
    );

    foreach ($links as $link) {
        $data[] = ue_el_section(array(
            ue_el_icon_box($link[0], $link[1], $link[2], $link[3], $link[4]),
        ), '#FFF8F0', '', ue_el_edge(0, 18, 0, 18));
    }

    $data[] = ue_el_section(array(
        ue_el_text('<strong style="letter-spacing:.24em;color:#F26622;font-size:10px;">WHAT WE DO</strong>', 10, '#F26622'),
    ), '#FFF8F0', '', ue_el_edge(24, 18, 8, 18));

    $services = array(
        array('Performance Marketing', 'Paid media focused on profitable growth.', 'fas fa-chart-line'),
        array('Creative Strategy', 'Creative built around attention and conversion.', 'fas fa-lightbulb'),
        array('Web Development', 'Fast, premium, conversion-focused websites.', 'fas fa-laptop-code'),
        array('AI & Automation', 'Smarter marketing and business workflows.', 'fas fa-robot'),
    );

    foreach ($services as $service) {
        $box = ue_el_icon_box($service[0], $service[1], '', $service[2], 'fa-solid');
        $box['settings']['link'] = array('url' => '', 'is_external' => '', 'nofollow' => '');
        $box['settings']['_background_color'] = '#FFFDF9';
        $data[] = ue_el_section(array($box), '#FFF8F0', '', ue_el_edge(0, 18, 0, 18));
    }

    $data[] = ue_el_section(array(
        ue_el_text('<strong style="letter-spacing:.22em;color:#F26622;font-size:10px;">BUILT FOR GROWTH</strong>', 10, '#F26622', 'left'),
        ue_el_heading('Build a brand that performs.', 35, '#FFFFFF', 'left', 'h2'),
        ue_el_text('Strategy, creative, campaigns, landing journeys, tracking and automation — working together.', 12, '#C9C2BB', 'left'),
        ue_el_button('Start a Conversation  →', 'https://wa.me/919764970030', '#FFFFFF', '#151515'),
    ), '#FFF8F0', '#151515', ue_el_edge(24, 22, 24, 22), ue_el_edge(22, 0, 18, 0), 22);

    $data[] = ue_el_section(array(
        ue_el_text('© 2026 <span style="color:#F26622;">UpscaleEra</span>. All rights reserved.', 10, '#8B837B'),
    ), '#FFF8F0', '', ue_el_edge(0, 18, 28, 18));

    return $data;
}

function ue_install_elementor_homepage() {
    if (!is_admin()) {
        return;
    }

    // 5.0.0 deliberately replaces the old imported-looking layout exactly once.
    $seed_version = '5.0.0';
    if (get_option('ue_elementor_home_seed_version') === $seed_version) {
        return;
    }

    $front_id = (int) get_option('page_on_front');
    $page = $front_id ? get_post($front_id) : null;

    if (!$page || $page->post_type !== 'page') {
        $existing = get_page_by_path('home');
        if ($existing) {
            $front_id = (int) $existing->ID;
        } else {
            $front_id = wp_insert_post(array(
                'post_title' => 'Home',
                'post_name' => 'home',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
        }
    }

    if (!$front_id || is_wp_error($front_id)) {
        return;
    }

    wp_update_post(array(
        'ID' => $front_id,
        'post_title' => 'Home',
        'post_status' => 'publish',
        'post_content' => '',
    ));

    delete_post_meta($front_id, '_elementor_css');
    delete_post_meta($front_id, '_elementor_controls_usage');

    update_post_meta($front_id, '_elementor_edit_mode', 'builder');
    update_post_meta($front_id, '_elementor_template_type', 'wp-page');
    update_post_meta($front_id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
    update_post_meta($front_id, '_elementor_data', wp_slash(wp_json_encode(ue_build_elementor_home_data())));
    update_post_meta($front_id, '_elementor_page_settings', array('hide_title' => 'yes'));
    update_post_meta($front_id, '_wp_page_template', 'elementor_canvas');

    update_option('show_on_front', 'page');
    update_option('page_on_front', $front_id);
    update_option('ue_elementor_home_seed_version', $seed_version, false);
    update_option('ue_elementor_home_seeded_page_id', $front_id, false);

    if (class_exists('\\Elementor\\Plugin')) {
        $elementor = \Elementor\Plugin::instance();
        if ($elementor && isset($elementor->files_manager)) {
            $elementor->files_manager->clear_cache();
        }
    }

    set_transient('ue_home_seeded_notice', 1, 120);
}
add_action('admin_init', 'ue_install_elementor_homepage', 5);

function ue_home_seeded_admin_notice() {
    if (!get_transient('ue_home_seeded_notice')) {
        return;
    }
    delete_transient('ue_home_seeded_notice');
    echo '<div class="notice notice-success is-dismissible"><p><strong>UpscaleEra Home updated:</strong> the previous layout was replaced with the new native Elementor homepage. Open Pages → Home → Edit with Elementor.</p></div>';
}
add_action('admin_notices', 'ue_home_seeded_admin_notice');

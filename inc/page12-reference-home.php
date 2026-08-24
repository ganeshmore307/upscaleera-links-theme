<?php
/**
 * Rebuilds WordPress page ID 12 to match the approved UpscaleEra mobile link-page reference.
 * The result is stored as native Elementor page data and remains editable in Elementor.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_ref_id($seed) {
    return substr(md5('ue-ref-' . $seed), 0, 8);
}

function ue_ref_size($size, $unit = 'px') {
    return array('unit' => $unit, 'size' => $size, 'sizes' => array());
}

function ue_ref_edge($top = 0, $right = 0, $bottom = 0, $left = 0, $linked = false) {
    return array(
        'unit' => 'px',
        'top' => (string) $top,
        'right' => (string) $right,
        'bottom' => (string) $bottom,
        'left' => (string) $left,
        'isLinked' => $linked,
    );
}

function ue_ref_widget($seed, $type, $settings) {
    return array(
        'id' => ue_ref_id($seed),
        'elType' => 'widget',
        'widgetType' => $type,
        'settings' => $settings,
        'elements' => array(),
        'isInner' => false,
    );
}

function ue_ref_image($seed, $url, $width = 255) {
    return ue_ref_widget($seed, 'image', array(
        'image' => array('url' => $url, 'id' => 0, 'alt' => 'UpscaleEra Logo'),
        'image_size' => 'full',
        'align' => 'center',
        'width' => ue_ref_size($width),
        'width_tablet' => ue_ref_size(235),
        'width_mobile' => ue_ref_size(215),
        '_css_classes' => 'ue-brand-logo',
        '_margin' => ue_ref_edge(0, 0, 8, 0),
    ));
}

function ue_ref_heading($seed, $text, $size, $color = '#1B1B1B', $family = 'Manrope', $weight = '600', $align = 'center', $tag = 'h2') {
    return ue_ref_widget($seed, 'heading', array(
        'title' => $text,
        'header_size' => $tag,
        'align' => $align,
        'title_color' => $color,
        'typography_typography' => 'custom',
        'typography_font_family' => $family,
        'typography_font_size' => ue_ref_size($size),
        'typography_font_size_mobile' => ue_ref_size($size),
        'typography_font_weight' => $weight,
        'typography_line_height' => array('unit' => 'em', 'size' => 1.2, 'sizes' => array()),
        '_margin' => ue_ref_edge(0, 0, 0, 0),
    ));
}

function ue_ref_text($seed, $html, $size = 15, $color = '#1B1B1B', $align = 'center', $weight = '400') {
    return ue_ref_widget($seed, 'text-editor', array(
        'editor' => $html,
        'align' => $align,
        'text_color' => $color,
        'typography_typography' => 'custom',
        'typography_font_family' => 'Manrope',
        'typography_font_size' => ue_ref_size($size),
        'typography_font_size_mobile' => ue_ref_size($size),
        'typography_font_weight' => $weight,
        'typography_line_height' => array('unit' => 'em', 'size' => 1.45, 'sizes' => array()),
        '_margin' => ue_ref_edge(0, 0, 0, 0),
    ));
}

function ue_ref_button($seed, $label, $url, $background = '#F26A21', $color = '#FFFFFF', $border_color = '', $outlined = false) {
    $settings = array(
        'text' => $label,
        'link' => array('url' => $url, 'is_external' => 'on', 'nofollow' => ''),
        'align' => 'justify',
        'size' => 'md',
        'button_text_color' => $color,
        'typography_typography' => 'custom',
        'typography_font_family' => 'Manrope',
        'typography_font_size' => ue_ref_size(16),
        'typography_font_weight' => '700',
        'border_radius' => array('unit' => 'px', 'top' => '14', 'right' => '14', 'bottom' => '14', 'left' => '14', 'isLinked' => true),
        'text_padding' => ue_ref_edge(16, 20, 16, 20),
        '_margin' => ue_ref_edge(0, 0, 0, 0),
    );

    if ($outlined) {
        $settings['background_color'] = 'rgba(255,255,255,0)';
        $settings['border_border'] = 'solid';
        $settings['border_width'] = array('unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true);
        $settings['border_color'] = $border_color ?: '#F26A21';
    } else {
        $settings['background_color'] = $background;
        $settings['_box_shadow_box_shadow_type'] = 'yes';
        $settings['_box_shadow_box_shadow'] = array('horizontal' => 0, 'vertical' => 8, 'blur' => 22, 'spread' => 0, 'color' => 'rgba(242,106,33,0.20)');
    }

    return ue_ref_widget($seed, 'button', $settings);
}

function ue_ref_icon_box($seed, $title, $url, $icon, $library, $icon_color, $class = 'ue-link-card') {
    return ue_ref_widget($seed, 'icon-box', array(
        'selected_icon' => array('value' => $icon, 'library' => $library),
        'title_text' => $title,
        'description_text' => '',
        'link' => array('url' => $url, 'is_external' => 'on', 'nofollow' => ''),
        'position' => 'left',
        'title_size' => 'h4',
        'primary_color' => $icon_color,
        'secondary_color' => '#FFFFFF',
        'icon_size' => ue_ref_size(31),
        'icon_padding' => ue_ref_size(0),
        'title_color' => '#1B1B1B',
        'title_typography_typography' => 'custom',
        'title_typography_font_family' => 'Manrope',
        'title_typography_font_size' => ue_ref_size(16),
        'title_typography_font_weight' => '500',
        '_background_background' => 'classic',
        '_background_color' => '#FFFDF9',
        '_border_border' => 'solid',
        '_border_width' => array('unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true),
        '_border_color' => '#E6D6C6',
        '_border_radius' => array('unit' => 'px', 'top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'isLinked' => true),
        '_padding' => ue_ref_edge(17, 18, 17, 18),
        '_margin' => ue_ref_edge(0, 0, 10, 0),
        '_css_classes' => $class,
    ));
}

function ue_ref_service_box($seed, $title, $icon) {
    return ue_ref_widget($seed, 'icon-box', array(
        'selected_icon' => array('value' => $icon, 'library' => 'fa-solid'),
        'title_text' => $title,
        'description_text' => '',
        'link' => array('url' => '', 'is_external' => '', 'nofollow' => ''),
        'position' => 'left',
        'title_size' => 'h5',
        'primary_color' => '#F26A21',
        'secondary_color' => '#FFFFFF',
        'icon_size' => ue_ref_size(18),
        'icon_padding' => ue_ref_size(0),
        'title_color' => '#1B1B1B',
        'title_typography_typography' => 'custom',
        'title_typography_font_family' => 'Manrope',
        'title_typography_font_size' => ue_ref_size(12),
        'title_typography_font_weight' => '600',
        '_background_background' => 'classic',
        '_background_color' => '#FFFDF9',
        '_border_border' => 'solid',
        '_border_width' => array('unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true),
        '_border_color' => '#E6D6C6',
        '_border_radius' => array('unit' => 'px', 'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999', 'isLinked' => true),
        '_padding' => ue_ref_edge(12, 14, 12, 14),
        '_css_classes' => 'ue-service-pill',
    ));
}

function ue_ref_icon($seed, $icon, $url = '', $color = '#1B1B1B', $class = '') {
    $settings = array(
        'selected_icon' => array('value' => $icon, 'library' => (strpos($icon, 'fab ') === 0 ? 'fa-brands' : 'fa-solid')),
        'primary_color' => $color,
        'secondary_color' => '#FFFFFF',
        'size' => ue_ref_size(19),
        'align' => 'center',
        '_css_classes' => $class,
    );
    if ($url) {
        $settings['link'] = array('url' => $url, 'is_external' => 'on', 'nofollow' => '');
    }
    return ue_ref_widget($seed, 'icon', $settings);
}

function ue_ref_section($seed, $widgets, $padding = null, $margin = null, $class = '') {
    return array(
        'id' => ue_ref_id('section-' . $seed),
        'elType' => 'section',
        'settings' => array(
            'content_width' => 'boxed',
            'boxed_width' => array('unit' => 'px', 'size' => 430, 'sizes' => array()),
            'gap' => 'no',
            'padding' => $padding ?: ue_ref_edge(0, 18, 0, 18),
            'margin' => $margin ?: ue_ref_edge(0, 0, 0, 0),
            '_css_classes' => $class,
        ),
        'elements' => array(array(
            'id' => ue_ref_id('column-' . $seed),
            'elType' => 'column',
            'settings' => array('_column_size' => 100),
            'elements' => $widgets,
            'isInner' => false,
        )),
        'isInner' => false,
    );
}

function ue_ref_two_col_row($seed, $left_widget, $right_widget) {
    return array(
        'id' => ue_ref_id('row-' . $seed),
        'elType' => 'section',
        'settings' => array(
            'content_width' => 'boxed',
            'boxed_width' => array('unit' => 'px', 'size' => 430, 'sizes' => array()),
            'gap' => 'narrow',
            'padding' => ue_ref_edge(0, 18, 10, 18),
            '_css_classes' => 'ue-services-row',
        ),
        'elements' => array(
            array(
                'id' => ue_ref_id('row-left-' . $seed),
                'elType' => 'column',
                'settings' => array('_column_size' => 50, '_inline_size' => 50, '_inline_size_mobile' => 50),
                'elements' => array($left_widget),
                'isInner' => false,
            ),
            array(
                'id' => ue_ref_id('row-right-' . $seed),
                'elType' => 'column',
                'settings' => array('_column_size' => 50, '_inline_size' => 50, '_inline_size_mobile' => 50),
                'elements' => array($right_widget),
                'isInner' => false,
            ),
        ),
        'isInner' => false,
    );
}

function ue_ref_social_row() {
    $items = array(
        array('ig', 'fab fa-instagram', 'https://www.instagram.com/upscaleera.agency/'),
        array('li', 'fab fa-linkedin-in', 'https://www.linkedin.com/company/upscaleera/'),
        array('wa', 'fab fa-whatsapp', 'https://wa.me/919764970030'),
        array('fb', 'fab fa-facebook-f', 'https://www.facebook.com/UpscaleEra/'),
    );

    $columns = array();
    foreach ($items as $item) {
        $columns[] = array(
            'id' => ue_ref_id('social-col-' . $item[0]),
            'elType' => 'column',
            'settings' => array('_column_size' => 25, '_inline_size' => 25, '_inline_size_mobile' => 25),
            'elements' => array(ue_ref_icon('social-' . $item[0], $item[1], $item[2], '#1B1B1B', 'ue-social-circle')),
            'isInner' => false,
        );
    }

    return array(
        'id' => ue_ref_id('social-row'),
        'elType' => 'section',
        'settings' => array(
            'content_width' => 'boxed',
            'boxed_width' => array('unit' => 'px', 'size' => 260, 'sizes' => array()),
            'gap' => 'narrow',
            'padding' => ue_ref_edge(4, 18, 8, 18),
            '_css_classes' => 'ue-social-row',
        ),
        'elements' => $columns,
        'isInner' => false,
    );
}

function ue_reference_home_data() {
    $logo_url = trailingslashit(get_stylesheet_directory_uri()) . 'assets/images/upscaleera-logo.png';
    $wa = 'https://wa.me/919764970030';

    $data = array();

    $data[] = ue_ref_section('hero', array(
        ue_ref_image('logo', $logo_url, 255),
        ue_ref_heading('tagline', 'Performance. Creativity. Growth.', 21, '#F26A21', 'Manrope', '500', 'center', 'div'),
        ue_ref_text('service-line-1', 'Digital Marketing&nbsp;&nbsp; <span style="color:#F26A21">•</span> &nbsp;&nbsp;Performance Marketing', 14, '#1B1B1B'),
        ue_ref_text('service-line-2', 'Web Development&nbsp;&nbsp; <span style="color:#F26A21">•</span> &nbsp;&nbsp;AI Automation', 14, '#1B1B1B'),
    ), ue_ref_edge(30, 18, 18, 18), ue_ref_edge(0, 0, 0, 0), 'ue-hero');

    $data[] = ue_ref_section('main-cta', array(
        ue_ref_button('grow-button', 'Let’s Grow Your Business   →', $wa),
    ), ue_ref_edge(0, 18, 16, 18));

    $links = array(
        array('web', 'Visit Our Website', 'https://upscaleera.com/', 'fas fa-globe', 'fa-solid', '#F26A21'),
        array('instagram', 'Follow on Instagram', 'https://www.instagram.com/upscaleera.agency/', 'fab fa-instagram', 'fa-brands', '#E1306C'),
        array('whatsapp', 'Chat on WhatsApp', $wa, 'fab fa-whatsapp', 'fa-brands', '#25D366'),
        array('linkedin', 'Connect on LinkedIn', 'https://www.linkedin.com/company/upscaleera/', 'fab fa-linkedin-in', 'fa-brands', '#0A66C2'),
        array('facebook', 'Follow on Facebook', 'https://www.facebook.com/UpscaleEra/', 'fab fa-facebook-f', 'fa-brands', '#1877F2'),
    );
    foreach ($links as $link) {
        $data[] = ue_ref_section('link-' . $link[0], array(
            ue_ref_icon_box('link-box-' . $link[0], $link[1], $link[2], $link[3], $link[4], $link[5]),
        ), ue_ref_edge(0, 18, 0, 18));
    }

    $data[] = ue_ref_section('what-we-do-title', array(
        ue_ref_heading('what-we-do', 'WHAT WE DO', 13, '#D45C20', 'Manrope', '700', 'center', 'div'),
    ), ue_ref_edge(20, 18, 12, 18), null, 'ue-what-we-do-title');

    $data[] = ue_ref_two_col_row('services-1',
        ue_ref_service_box('service-performance', 'Performance Marketing', 'fas fa-chart-line'),
        ue_ref_service_box('service-social', 'Social Media', 'fas fa-users')
    );
    $data[] = ue_ref_two_col_row('services-2',
        ue_ref_service_box('service-web', 'Web Development', 'fas fa-code'),
        ue_ref_service_box('service-ai', 'AI Automation', 'fas fa-robot')
    );

    $rocket = ue_ref_icon('rocket', 'fas fa-rocket', '', '#F26A21', 'ue-rocket-icon');
    $cta_heading = ue_ref_heading('bottom-cta-heading', 'Ready to scale your brand?', 25, '#1B1B1B', 'DM Serif Display', '400', 'center', 'h2');
    $cta_button = ue_ref_button('bottom-cta-button', 'Start a Conversation   →', $wa, '#FFFFFF', '#F26A21', '#F26A21', true);
    $cta_button['settings']['_margin'] = ue_ref_edge(4, 26, 0, 26);

    $bottom_section = ue_ref_section('bottom-cta', array($rocket, $cta_heading, $cta_button), ue_ref_edge(20, 18, 16, 18), ue_ref_edge(6, 0, 12, 0), 'ue-bottom-cta-wrap');
    $bottom_section['elements'][0]['settings']['_background_background'] = 'classic';
    $bottom_section['elements'][0]['settings']['_background_color'] = '#FFF7EE';
    $bottom_section['elements'][0]['settings']['_border_border'] = 'solid';
    $bottom_section['elements'][0]['settings']['_border_width'] = array('unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true);
    $bottom_section['elements'][0]['settings']['_border_color'] = '#E6D6C6';
    $bottom_section['elements'][0]['settings']['_border_radius'] = array('unit' => 'px', 'top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'isLinked' => true);
    $bottom_section['elements'][0]['settings']['_padding'] = ue_ref_edge(22, 16, 22, 16);
    $data[] = $bottom_section;

    $data[] = ue_ref_social_row();
    $data[] = ue_ref_section('footer', array(
        ue_ref_text('footer-text', '© 2026 UpscaleEra', 12, '#5F5A55'),
    ), ue_ref_edge(0, 18, 28, 18));

    return $data;
}

function ue_apply_reference_homepage() {
    $page_id = 12;
    $version = '2026.08.24-reference-v2-icon-fix';

    if (get_option('ue_reference_home_version') === $version) {
        return;
    }

    $page = get_post($page_id);
    if (!$page || $page->post_type !== 'page') {
        return;
    }

    wp_update_post(array(
        'ID' => $page_id,
        'post_title' => 'Home',
        'post_status' => 'publish',
        'post_content' => '',
    ));

    delete_post_meta($page_id, '_elementor_css');
    delete_post_meta($page_id, '_elementor_controls_usage');

    update_post_meta($page_id, '_elementor_edit_mode', 'builder');
    update_post_meta($page_id, '_elementor_template_type', 'wp-page');
    update_post_meta($page_id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode(ue_reference_home_data())));
    update_post_meta($page_id, '_elementor_page_settings', array('hide_title' => 'yes'));
    update_post_meta($page_id, '_wp_page_template', 'elementor_canvas');

    update_option('show_on_front', 'page');
    update_option('page_on_front', $page_id);
    update_option('ue_reference_home_version', $version, false);

    if (class_exists('\\Elementor\\Plugin')) {
        $elementor = \Elementor\Plugin::instance();
        if ($elementor && isset($elementor->files_manager)) {
            $elementor->files_manager->clear_cache();
        }
    }
}
add_action('init', 'ue_apply_reference_homepage', 30);

<?php
/**
 * Customizer settings for UpscaleEra Links.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_customize_register($wp_customize) {
    $wp_customize->add_panel('ue_links_panel', array(
        'title'       => __('UpscaleEra Links', 'upscaleera-links'),
        'description' => __('Edit the content, links and colors of the UpscaleEra links page.', 'upscaleera-links'),
        'priority'    => 30,
    ));

    $wp_customize->add_section('ue_hero_section', array(
        'title' => __('Hero', 'upscaleera-links'),
        'panel' => 'ue_links_panel',
    ));

    $hero_fields = array(
        'ue_kicker' => array('Agency Label', 'DIGITAL GROWTH AGENCY'),
        'ue_heading' => array('Main Heading', 'Performance. Creativity. Growth.'),
        'ue_intro' => array('Intro Text', 'Helping ambitious brands turn digital attention into measurable growth.'),
        'ue_primary_title' => array('Primary CTA Heading', 'Ready to grow your brand?'),
        'ue_primary_text' => array('Primary CTA Description', 'Let’s turn attention into measurable growth.'),
        'ue_primary_button' => array('Primary CTA Button', 'Start a Conversation'),
        'ue_primary_url' => array('Primary CTA URL', 'https://wa.me/919764970030'),
    );

    foreach ($hero_fields as $id => $field) {
        $wp_customize->add_setting($id, array(
            'default'           => $field[1],
            'sanitize_callback' => $id === 'ue_primary_url' ? 'esc_url_raw' : 'sanitize_text_field',
        ));
        $wp_customize->add_control($id, array(
            'label'   => __($field[0], 'upscaleera-links'),
            'section' => 'ue_hero_section',
            'type'    => $id === 'ue_intro' || $id === 'ue_primary_text' ? 'textarea' : 'text',
        ));
    }

    $wp_customize->add_section('ue_links_section', array(
        'title' => __('Main Links', 'upscaleera-links'),
        'panel' => 'ue_links_panel',
    ));

    $links = array(
        'website' => array('Website', 'Visit Our Website', 'Explore UpscaleEra & our services', 'https://upscaleera.com/'),
        'instagram' => array('Instagram', 'Follow us on Instagram', 'Creative work, insights & updates', 'https://www.instagram.com/upscaleera.agency/?hl=en'),
        'whatsapp' => array('WhatsApp', 'Chat on WhatsApp', 'Tell us what you want to grow', 'https://wa.me/919764970030'),
        'linkedin' => array('LinkedIn', 'Connect on LinkedIn', 'Professional updates & agency insights', 'https://www.linkedin.com/company/upscaleera/posts/?feedView=all'),
        'facebook' => array('Facebook', 'Follow on Facebook', 'News, updates & announcements', 'https://www.facebook.com/UpscaleEra/'),
    );

    foreach ($links as $key => $data) {
        $wp_customize->add_setting("ue_{$key}_label", array(
            'default'           => $data[1],
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("ue_{$key}_label", array(
            'label'   => __($data[0] . ' Button Label', 'upscaleera-links'),
            'section' => 'ue_links_section',
            'type'    => 'text',
        ));

        $wp_customize->add_setting("ue_{$key}_description", array(
            'default'           => $data[2],
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("ue_{$key}_description", array(
            'label'   => __($data[0] . ' Description', 'upscaleera-links'),
            'section' => 'ue_links_section',
            'type'    => 'text',
        ));

        $wp_customize->add_setting("ue_{$key}_url", array(
            'default'           => $data[3],
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control("ue_{$key}_url", array(
            'label'   => __($data[0] . ' URL', 'upscaleera-links'),
            'section' => 'ue_links_section',
            'type'    => 'url',
        ));
    }

    $wp_customize->add_section('ue_services_section', array(
        'title' => __('Services', 'upscaleera-links'),
        'panel' => 'ue_links_panel',
    ));

    $services = array(
        'service_1' => 'Performance Marketing',
        'service_2' => 'Creative Strategy',
        'service_3' => 'Web & Landing Pages',
        'service_4' => 'AI & Automation',
    );

    foreach ($services as $id => $default) {
        $wp_customize->add_setting("ue_{$id}", array(
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("ue_{$id}", array(
            'label'   => __(ucwords(str_replace('_', ' ', $id)), 'upscaleera-links'),
            'section' => 'ue_services_section',
            'type'    => 'text',
        ));
    }

    $wp_customize->add_section('ue_bottom_cta_section', array(
        'title' => __('Bottom CTA', 'upscaleera-links'),
        'panel' => 'ue_links_panel',
    ));

    $bottom_fields = array(
        'ue_bottom_eyebrow' => array('Eyebrow', 'BUILT FOR GROWTH'),
        'ue_bottom_heading' => array('Heading', 'Built for brands ready to scale.'),
        'ue_bottom_text' => array('Description', 'Strategy, creative and technology working together as one connected growth system.'),
        'ue_bottom_button' => array('Button Text', 'Let’s Work Together'),
        'ue_bottom_url' => array('Button URL', 'https://wa.me/919764970030'),
        'ue_footer_text' => array('Footer Text', 'UpscaleEra. All rights reserved.'),
    );

    foreach ($bottom_fields as $id => $field) {
        $wp_customize->add_setting($id, array(
            'default'           => $field[1],
            'sanitize_callback' => $id === 'ue_bottom_url' ? 'esc_url_raw' : 'sanitize_text_field',
        ));
        $wp_customize->add_control($id, array(
            'label'   => __($field[0], 'upscaleera-links'),
            'section' => 'ue_bottom_cta_section',
            'type'    => $id === 'ue_bottom_text' ? 'textarea' : 'text',
        ));
    }

    $wp_customize->add_section('ue_design_section', array(
        'title' => __('Brand Colors', 'upscaleera-links'),
        'panel' => 'ue_links_panel',
    ));

    $colors = array(
        'ue_primary_color' => array('Primary Orange', '#f26622'),
        'ue_background_color' => array('Background', '#fff8f0'),
        'ue_ink_color' => array('Text / Dark', '#151515'),
    );

    foreach ($colors as $id => $data) {
        $wp_customize->add_setting($id, array(
            'default'           => $data[1],
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, array(
            'label'   => __($data[0], 'upscaleera-links'),
            'section' => 'ue_design_section',
        )));
    }
}
add_action('customize_register', 'ue_customize_register');

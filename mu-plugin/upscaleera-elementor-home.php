<?php
/**
 * Plugin Name: UpscaleEra Elementor Home Bridge
 * Description: Loads the GitHub-managed reference-matched Elementor homepage builder for WordPress page ID 12.
 * Version: 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$ue_home_builder = WP_CONTENT_DIR . '/themes/upscaleera-links-theme/inc/page12-reference-home.php';

if (file_exists($ue_home_builder)) {
    require_once $ue_home_builder;
}

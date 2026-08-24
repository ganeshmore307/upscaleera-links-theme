<?php
/**
 * Plugin Name: UpscaleEra Elementor Home Bridge
 * Description: Loads the one-time UpscaleEra Elementor homepage installer from the deployed child theme.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$ue_seeder = WP_CONTENT_DIR . '/themes/upscaleera-links-theme/inc/elementor-home-seeder.php';

if (file_exists($ue_seeder)) {
    require_once $ue_seeder;
}

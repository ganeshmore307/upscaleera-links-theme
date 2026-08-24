<?php
/**
 * Elementor-ready front page template for UpscaleEra Links.
 *
 * The WordPress page assigned as the static homepage is rendered through
 * the_content(), allowing the entire homepage to be edited visually with
 * Elementor.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('ue-elementor-site'); ?>>
<?php wp_body_open(); ?>

<main id="primary" class="ue-elementor-home">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php wp_footer(); ?>
</body>
</html>

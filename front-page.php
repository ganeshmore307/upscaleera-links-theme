<?php
/**
 * Front page template for UpscaleEra Links.
 */

if (!defined('ABSPATH')) {
    exit;
}

$logo = ue_logo_url();
$primary_url = ue_mod('ue_primary_url', 'https://wa.me/919764970030');

$links = array(
    array('website', ue_mod('ue_website_label', 'Visit Our Website'), ue_mod('ue_website_description', 'Explore UpscaleEra & our services'), ue_mod('ue_website_url', 'https://upscaleera.com/')),
    array('instagram', ue_mod('ue_instagram_label', 'Follow us on Instagram'), ue_mod('ue_instagram_description', 'Creative work, insights & updates'), ue_mod('ue_instagram_url', 'https://www.instagram.com/upscaleera.agency/?hl=en')),
    array('whatsapp', ue_mod('ue_whatsapp_label', 'Chat on WhatsApp'), ue_mod('ue_whatsapp_description', 'Tell us what you want to grow'), ue_mod('ue_whatsapp_url', 'https://wa.me/919764970030')),
    array('linkedin', ue_mod('ue_linkedin_label', 'Connect on LinkedIn'), ue_mod('ue_linkedin_description', 'Professional updates & agency insights'), ue_mod('ue_linkedin_url', 'https://www.linkedin.com/company/upscaleera/posts/?feedView=all')),
    array('facebook', ue_mod('ue_facebook_label', 'Follow on Facebook'), ue_mod('ue_facebook_description', 'News, updates & announcements'), ue_mod('ue_facebook_url', 'https://www.facebook.com/UpscaleEra/')),
);

$services = array(
    array('01', 'growth', ue_mod('ue_service_1', 'Performance Marketing')),
    array('02', 'strategy', ue_mod('ue_service_2', 'Creative Strategy')),
    array('03', 'web', ue_mod('ue_service_3', 'Web & Landing Pages')),
    array('04', 'ai', ue_mod('ue_service_4', 'AI & Automation')),
);

$hero_heading = ue_mod('ue_heading', 'Performance. Creativity. Growth.');
$hero_heading_html = preg_replace('/(\S+)$/u', '<em>$1</em>', esc_html($hero_heading));
$bottom_heading = ue_mod('ue_bottom_heading', 'Built for brands ready to scale.');
$bottom_heading_html = preg_replace('/(\S+)$/u', '<em>$1</em>', esc_html($bottom_heading));
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('ue-link-site'); ?>>
<?php wp_body_open(); ?>

<main class="ue-links" id="main-content">
    <div class="ue-ambient ue-ambient--top" aria-hidden="true"></div>
    <div class="ue-ambient ue-ambient--bottom" aria-hidden="true"></div>

    <div class="ue-shell">
        <header class="ue-hero ue-reveal">
            <?php if ($logo) : ?>
                <img class="ue-logo" src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
            <?php else : ?>
                <div class="ue-wordmark" aria-label="UpscaleEra">upscale<span>Era</span></div>
            <?php endif; ?>

            <p class="ue-kicker"><?php echo esc_html(ue_mod('ue_kicker', 'DIGITAL GROWTH AGENCY')); ?></p>
            <h1><?php echo wp_kses($hero_heading_html, array('em' => array())); ?></h1>
            <p class="ue-intro"><?php echo esc_html(ue_mod('ue_intro', 'Helping ambitious brands turn digital attention into measurable growth.')); ?></p>
        </header>

        <a class="ue-primary-card ue-reveal" href="<?php echo esc_url($primary_url); ?>" target="_blank" rel="noopener noreferrer">
            <div class="ue-primary-copy">
                <span class="ue-primary-icon"><?php echo ue_icon('growth'); ?></span>
                <span>
                    <strong><?php echo esc_html(ue_mod('ue_primary_title', 'Ready to grow your brand?')); ?></strong>
                    <small><?php echo esc_html(ue_mod('ue_primary_text', 'Let’s turn attention into measurable growth.')); ?></small>
                </span>
            </div>
            <span class="ue-primary-button">
                <?php echo esc_html(ue_mod('ue_primary_button', 'Start a Conversation')); ?>
                <b aria-hidden="true">→</b>
            </span>
        </a>

        <nav class="ue-link-stack" aria-label="UpscaleEra primary links">
            <?php foreach ($links as $item) : ?>
                <?php if (empty($item[3])) continue; ?>
                <a class="ue-link-card ue-reveal" href="<?php echo esc_url($item[3]); ?>" target="_blank" rel="noopener noreferrer">
                    <span class="ue-link-icon"><?php echo ue_icon($item[0]); ?></span>
                    <span class="ue-link-copy">
                        <strong><?php echo esc_html($item[1]); ?></strong>
                        <small><?php echo esc_html($item[2]); ?></small>
                    </span>
                    <span class="ue-arrow" aria-hidden="true">→</span>
                </a>
            <?php endforeach; ?>
        </nav>

        <section class="ue-services ue-reveal" aria-labelledby="ue-services-title">
            <div class="ue-section-title">
                <span></span>
                <p id="ue-services-title">WHAT WE DO</p>
                <span></span>
            </div>

            <div class="ue-service-grid">
                <?php foreach ($services as $service) : ?>
                    <article class="ue-service-card">
                        <span class="ue-service-number"><?php echo esc_html($service[0]); ?></span>
                        <span class="ue-service-icon"><?php echo ue_icon($service[1]); ?></span>
                        <strong><?php echo esc_html($service[2]); ?></strong>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="ue-bottom-cta ue-reveal">
            <div class="ue-bottom-content">
                <p class="ue-bottom-eyebrow"><?php echo esc_html(ue_mod('ue_bottom_eyebrow', 'BUILT FOR GROWTH')); ?></p>
                <h2><?php echo wp_kses($bottom_heading_html, array('em' => array())); ?></h2>
                <p class="ue-bottom-copy"><?php echo esc_html(ue_mod('ue_bottom_text', 'Strategy, creative and technology working together as one connected growth system.')); ?></p>
                <a class="ue-bottom-button" href="<?php echo esc_url(ue_mod('ue_bottom_url', $primary_url)); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html(ue_mod('ue_bottom_button', 'Let’s Work Together')); ?>
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="ue-growth-visual" aria-hidden="true">
                <div class="ue-grid-lines"></div>
                <i style="--bar:24%"></i>
                <i style="--bar:38%"></i>
                <i style="--bar:55%"></i>
                <i style="--bar:72%"></i>
                <i style="--bar:91%"></i>
                <b></b>
            </div>
        </section>

        <footer class="ue-footer ue-reveal">
            <div class="ue-footer-socials" aria-label="Social links">
                <?php foreach (array('instagram', 'linkedin', 'whatsapp', 'facebook') as $social) : ?>
                    <?php
                    $social_url = ue_mod('ue_' . $social . '_url', '');
                    if (!$social_url) continue;
                    ?>
                    <a href="<?php echo esc_url($social_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr(ucfirst($social)); ?>">
                        <?php echo ue_icon($social); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <p>© <?php echo esc_html(wp_date('Y')); ?> <span><?php echo esc_html(ue_mod('ue_footer_text', 'UpscaleEra. All rights reserved.')); ?></span></p>
        </footer>
    </div>
</main>

<?php wp_footer(); ?>
</body>
</html>

<?php
/**
 * Plugin Name: UpscaleEra Links Frontend Fallback
 * Description: Prevents /links/ from ever showing a blank or 404 page while Elementor page 1999 is being repaired.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) { exit; }

function ue_links_fallback_should_render() {
    if (is_admin()) return false;
    if (isset($_GET['elementor-preview']) || isset($_GET['elementor_library'])) return false;

    $path = wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if (untrailingslashit((string) $path) !== '/links') return false;

    $post = get_post(1999);
    $stored = get_post_meta(1999, '_elementor_data', true);
    $decoded = is_string($stored) ? json_decode($stored, true) : $stored;

    // If the real Elementor page is healthy and published, let WordPress render it normally.
    if ($post && $post->post_status === 'publish' && is_array($decoded) && count($decoded) >= 5) {
        return false;
    }

    return true;
}

function ue_links_fallback_icon($name) {
    $icons = array(
        'globe' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>',
        'whatsapp' => '<svg viewBox="0 0 24 24"><path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5Z"/><path d="M9 8.7c.4 2.3 2.1 4 4.4 4.7l1.2-1.1c.3-.2.6-.2.9 0l1.6.8c.3.1.4.5.3.8-.4 1.1-1.5 1.8-2.7 1.7-4.3-.4-7.7-3.8-8.1-8.1-.1-1.2.6-2.3 1.7-2.7.3-.1.7 0 .8.3l.8 1.6c.1.3.1.6-.1.9L9 8.7Z"/></svg>',
        'linkedin' => '<svg viewBox="0 0 24 24"><path d="M6 9v10M6 5.5v.1M10 19v-6c0-2 1.3-3.5 3.3-3.5 2.2 0 3.7 1.5 3.7 4V19M10 10v9"/></svg>',
        'facebook' => '<svg viewBox="0 0 24 24"><path d="M14 8h3V4h-3c-3 0-5 2-5 5v2H6v4h3v5h4v-5h3l1-4h-4V9c0-.7.3-1 1-1Z"/></svg>',
        'chart' => '<svg viewBox="0 0 24 24"><path d="M4 19h16M6 16l4-4 3 2 5-6M18 8h-4M18 8v4"/></svg>',
        'users' => '<svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><circle cx="16" cy="9" r="2.5"/><path d="M3 19c0-3 2.5-5 6-5s6 2 6 5M14 14.5c3 0 5 1.7 5 4.5"/></svg>',
        'code' => '<svg viewBox="0 0 24 24"><path d="m8 8-4 4 4 4M16 8l4 4-4 4M14 5l-4 14"/></svg>',
        'spark' => '<svg viewBox="0 0 24 24"><path d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3ZM18 14l.8 2.2L21 17l-2.2.8L18 20l-.8-2.2L15 17l2.2-.8L18 14Z"/></svg>',
        'rocket' => '<svg viewBox="0 0 24 24"><path d="M14 4c3-2 6-1 6-1s1 3-1 6l-5 5-4-4 4-6Z"/><path d="m10 10-4 1-2 3 5 1M14 14l-1 4-3 2-1-5M8 16l-2 2"/></svg>'
    );
    return $icons[$name] ?? '';
}

function ue_links_fallback_render() {
    if (!ue_links_fallback_should_render()) return;

    status_header(200);
    nocache_headers();

    $logo = 'https://links.upscaleera.com/wp-content/themes/upscaleera-links-theme/assets/images/upscaleera-logo.png';
    $custom_logo_id = (int) get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $custom_logo = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($custom_logo) $logo = $custom_logo;
    }

    $links = array(
        array('globe','Visit Our Website','https://upscaleera.com/','orange'),
        array('instagram','Follow on Instagram','https://www.instagram.com/upscaleera.agency/','pink'),
        array('whatsapp','Chat on WhatsApp','https://wa.me/919764970030','green'),
        array('linkedin','Connect on LinkedIn','https://www.linkedin.com/company/upscaleera/','blue'),
        array('facebook','Follow on Facebook','https://www.facebook.com/UpscaleEra/','blue2'),
    );

    ?><!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>UpscaleEra Links</title>
<style>
*{box-sizing:border-box}html,body{margin:0;padding:0}body{font-family:Arial,Helvetica,sans-serif;background:#eadbc9;color:#171717}.ue-wrap{width:min(100%,470px);min-height:100vh;margin:0 auto;padding:34px 22px 28px;background:#fff8f0;position:relative;overflow:hidden;box-shadow:0 24px 70px rgba(74,45,24,.12)}.ue-wrap:before,.ue-wrap:after{content:"";position:absolute;border:3px solid rgba(242,106,33,.13);border-radius:50%;pointer-events:none}.ue-wrap:before{width:330px;height:330px;left:-260px;top:18px}.ue-wrap:after{width:420px;height:420px;right:-330px;top:475px}.ue-content{position:relative;z-index:2}.ue-logo{display:block;width:255px;max-width:76%;height:auto;margin:0 auto 16px}.ue-tag{margin:0 0 16px;text-align:center;font-size:21px;line-height:1.25;font-weight:500;color:#f26a21}.ue-service-lines{text-align:center;font-size:14px;line-height:1.7;margin-bottom:19px}.ue-dot{color:#f26a21;padding:0 8px}.ue-primary{display:flex;align-items:center;justify-content:center;width:100%;min-height:50px;border-radius:14px;background:#ff661e;color:#fff;text-decoration:none;font-size:16px;font-weight:700;margin:0 0 17px;box-shadow:0 9px 24px rgba(242,106,33,.18)}.ue-links{display:grid;gap:10px}.ue-card{display:grid;grid-template-columns:42px 1fr 26px;align-items:center;gap:10px;min-height:68px;padding:13px 17px;border:1px solid #e8d8c7;border-radius:16px;background:#fffdfa;text-decoration:none;color:#171717}.ue-card .ico,.ue-pill .ico,.ue-social .ico,.ue-rocket{display:flex;align-items:center;justify-content:center}.ue-card svg{width:31px;height:31px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}.ue-card .label{font-size:16px;font-weight:500}.ue-card .arrow{font-size:27px;line-height:1;color:#f26a21;text-align:right}.orange{color:#f26a21}.pink{color:#e1306c}.green{color:#25d366}.blue{color:#0a66c2}.blue2{color:#1877f2}.ue-what{display:flex;align-items:center;gap:13px;margin:24px 0 13px;color:#d45c20;font-size:13px;font-weight:700;letter-spacing:2.2px;justify-content:center}.ue-what:before,.ue-what:after{content:"";height:1px;width:54px;background:#d9a984}.ue-pills{display:grid;grid-template-columns:1fr 1fr;gap:10px}.ue-pill{display:flex;align-items:center;gap:8px;min-height:52px;padding:11px 13px;border:1px solid #e8d8c7;border-radius:999px;background:#fffdfa;font-size:12px;font-weight:600;white-space:nowrap}.ue-pill svg{width:19px;height:19px;fill:none;stroke:#f26a21;stroke-width:1.9;stroke-linecap:round;stroke-linejoin:round}.ue-cta{margin-top:22px;padding:22px 18px;border:1px solid #e8d8c7;border-radius:20px;background:#fff7ee;text-align:center}.ue-rocket svg{width:29px;height:29px;fill:none;stroke:#f26a21;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;margin-bottom:8px}.ue-cta h2{font-family:Georgia,'Times New Roman',serif;font-size:27px;line-height:1.15;margin:4px 0 17px}.ue-secondary{display:flex;align-items:center;justify-content:center;min-height:52px;border:1.5px solid #f26a21;border-radius:14px;color:#f26a21;text-decoration:none;font-size:16px;font-weight:700}.ue-socials{display:flex;justify-content:center;gap:18px;margin:20px 0 12px}.ue-social{display:flex;width:42px;height:42px;align-items:center;justify-content:center;border:1px solid #e8d8c7;border-radius:50%;background:#fffaf4;color:#202020;text-decoration:none}.ue-social svg{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}.ue-copy{text-align:center;font-size:12px;color:#69625c}@media(max-width:380px){.ue-wrap{padding-left:15px;padding-right:15px}.ue-tag{font-size:19px}.ue-card{grid-template-columns:36px 1fr 24px;padding-left:14px;padding-right:14px}.ue-card .label{font-size:15px}.ue-pill{font-size:10.5px;padding:10px 10px}.ue-pill svg{width:17px;height:17px}}
</style></head><body><main class="ue-wrap"><div class="ue-content">
<img class="ue-logo" src="<?php echo esc_url($logo); ?>" alt="UpscaleEra">
<div class="ue-tag">Performance. Creativity. Growth.</div>
<div class="ue-service-lines">Digital Marketing <span class="ue-dot">•</span> Performance Marketing<br>Web Development <span class="ue-dot">•</span> AI Automation</div>
<a class="ue-primary" href="https://wa.me/919764970030">Let’s Grow Your Business&nbsp; →</a>
<div class="ue-links">
<?php foreach($links as $item): ?><a class="ue-card" href="<?php echo esc_url($item[2]); ?>"><span class="ico <?php echo esc_attr($item[3]); ?>"><?php echo ue_links_fallback_icon($item[0]); ?></span><span class="label"><?php echo esc_html($item[1]); ?></span><span class="arrow">→</span></a><?php endforeach; ?>
</div>
<div class="ue-what">WHAT WE DO</div>
<div class="ue-pills">
<div class="ue-pill"><span class="ico"><?php echo ue_links_fallback_icon('chart'); ?></span>Performance Marketing</div>
<div class="ue-pill"><span class="ico"><?php echo ue_links_fallback_icon('users'); ?></span>Social Media</div>
<div class="ue-pill"><span class="ico"><?php echo ue_links_fallback_icon('code'); ?></span>Web Development</div>
<div class="ue-pill"><span class="ico"><?php echo ue_links_fallback_icon('spark'); ?></span>AI Automation</div>
</div>
<div class="ue-cta"><div class="ue-rocket"><?php echo ue_links_fallback_icon('rocket'); ?></div><h2>Ready to scale your brand?</h2><a class="ue-secondary" href="https://wa.me/919764970030">Start a Conversation&nbsp; →</a></div>
<div class="ue-socials">
<a class="ue-social" href="https://www.instagram.com/upscaleera.agency/"><?php echo ue_links_fallback_icon('instagram'); ?></a>
<a class="ue-social" href="https://www.linkedin.com/company/upscaleera/"><?php echo ue_links_fallback_icon('linkedin'); ?></a>
<a class="ue-social" href="https://wa.me/919764970030"><?php echo ue_links_fallback_icon('whatsapp'); ?></a>
<a class="ue-social" href="https://www.facebook.com/UpscaleEra/"><?php echo ue_links_fallback_icon('facebook'); ?></a>
</div><div class="ue-copy">© 2026 UpscaleEra</div>
</div></main></body></html><?php
    exit;
}
add_action('template_redirect','ue_links_fallback_render',-9999);

<?php
/**
 * Dedicated admin editor for the UpscaleEra Links theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ue_links_admin_menu() {
    add_menu_page(
        __('UpscaleEra Links', 'upscaleera-links'),
        __('UpscaleEra Links', 'upscaleera-links'),
        'edit_theme_options',
        'upscaleera-links',
        'ue_links_admin_page',
        'dashicons-admin-links',
        3
    );
}
add_action('admin_menu', 'ue_links_admin_menu');

function ue_links_admin_assets($hook) {
    if ($hook !== 'toplevel_page_upscaleera-links') {
        return;
    }

    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'ue_links_admin_assets');

function ue_links_text_fields() {
    return array(
        'ue_kicker',
        'ue_heading',
        'ue_intro',
        'ue_primary_title',
        'ue_primary_text',
        'ue_primary_button',
        'ue_website_label',
        'ue_website_description',
        'ue_instagram_label',
        'ue_instagram_description',
        'ue_whatsapp_label',
        'ue_whatsapp_description',
        'ue_linkedin_label',
        'ue_linkedin_description',
        'ue_facebook_label',
        'ue_facebook_description',
        'ue_service_1',
        'ue_service_2',
        'ue_service_3',
        'ue_service_4',
        'ue_bottom_eyebrow',
        'ue_bottom_heading',
        'ue_bottom_text',
        'ue_bottom_button',
        'ue_footer_text',
    );
}

function ue_links_url_fields() {
    return array(
        'ue_primary_url',
        'ue_website_url',
        'ue_instagram_url',
        'ue_whatsapp_url',
        'ue_linkedin_url',
        'ue_facebook_url',
        'ue_bottom_url',
    );
}

function ue_links_color_fields() {
    return array(
        'ue_primary_color',
        'ue_background_color',
        'ue_ink_color',
    );
}

function ue_links_save_admin_settings() {
    if (!current_user_can('edit_theme_options')) {
        return;
    }

    if (empty($_POST['ue_links_save']) || empty($_POST['ue_links_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ue_links_nonce'])), 'ue_links_save_settings')) {
        return;
    }

    foreach (ue_links_text_fields() as $field) {
        if (isset($_POST[$field])) {
            set_theme_mod($field, sanitize_textarea_field(wp_unslash($_POST[$field])));
        }
    }

    foreach (ue_links_url_fields() as $field) {
        if (isset($_POST[$field])) {
            set_theme_mod($field, esc_url_raw(wp_unslash($_POST[$field])));
        }
    }

    foreach (ue_links_color_fields() as $field) {
        if (isset($_POST[$field])) {
            $color = sanitize_hex_color(wp_unslash($_POST[$field]));
            if ($color) {
                set_theme_mod($field, $color);
            }
        }
    }

    if (isset($_POST['ue_logo_id'])) {
        $logo_id = absint($_POST['ue_logo_id']);
        if ($logo_id) {
            set_theme_mod('custom_logo', $logo_id);
        } else {
            remove_theme_mod('custom_logo');
        }
    }

    add_settings_error(
        'ue_links_messages',
        'ue_links_saved',
        __('UpscaleEra Links updated successfully.', 'upscaleera-links'),
        'updated'
    );
}

function ue_links_field($id, $label, $default = '', $type = 'text', $description = '') {
    $value = get_theme_mod($id, $default);
    ?>
    <div class="ue-admin-field">
        <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
        <?php if ($type === 'textarea') : ?>
            <textarea id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" rows="3"><?php echo esc_textarea($value); ?></textarea>
        <?php elseif ($type === 'color') : ?>
            <div class="ue-color-row">
                <input id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" type="color" value="<?php echo esc_attr($value); ?>">
                <input class="ue-color-text" type="text" value="<?php echo esc_attr($value); ?>" data-color-target="<?php echo esc_attr($id); ?>">
            </div>
        <?php else : ?>
            <input id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" type="<?php echo esc_attr($type); ?>" value="<?php echo esc_attr($value); ?>">
        <?php endif; ?>
        <?php if ($description) : ?>
            <p class="description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

function ue_links_admin_page() {
    ue_links_save_admin_settings();

    $logo_id  = absint(get_theme_mod('custom_logo'));
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';
    ?>
    <div class="wrap ue-admin-wrap">
        <div class="ue-admin-head">
            <div>
                <span class="ue-admin-kicker">UPSCALERA</span>
                <h1><?php esc_html_e('Links Website Editor', 'upscaleera-links'); ?></h1>
                <p>Edit the live links page from one place. Your saved content stays in WordPress even when the theme code is updated from GitHub.</p>
            </div>
            <a class="button button-secondary ue-preview-button" href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noopener noreferrer">Preview Website ↗</a>
        </div>

        <?php settings_errors('ue_links_messages'); ?>

        <form method="post">
            <?php wp_nonce_field('ue_links_save_settings', 'ue_links_nonce'); ?>

            <div class="ue-admin-grid">
                <section class="ue-admin-card ue-admin-card-wide">
                    <div class="ue-card-title">
                        <span>01</span>
                        <div><h2>Branding & Hero</h2><p>Logo and the first content visitors see.</p></div>
                    </div>

                    <div class="ue-admin-field">
                        <label>UpscaleEra Logo</label>
                        <div class="ue-logo-editor">
                            <div class="ue-logo-preview <?php echo $logo_url ? 'has-logo' : ''; ?>" id="ue-logo-preview">
                                <?php if ($logo_url) : ?>
                                    <img src="<?php echo esc_url($logo_url); ?>" alt="UpscaleEra logo preview">
                                <?php else : ?>
                                    <span>No logo selected</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <input type="hidden" name="ue_logo_id" id="ue_logo_id" value="<?php echo esc_attr($logo_id); ?>">
                                <button type="button" class="button" id="ue-select-logo">Choose Logo</button>
                                <button type="button" class="button-link-delete" id="ue-remove-logo">Remove</button>
                            </div>
                        </div>
                    </div>

                    <div class="ue-fields-two">
                        <?php ue_links_field('ue_kicker', 'Agency Label', 'DIGITAL GROWTH AGENCY'); ?>
                        <?php ue_links_field('ue_heading', 'Main Heading', 'Performance. Creativity. Growth.'); ?>
                    </div>
                    <?php ue_links_field('ue_intro', 'Intro Text', 'Helping ambitious brands turn digital attention into measurable growth.', 'textarea'); ?>
                </section>

                <section class="ue-admin-card">
                    <div class="ue-card-title"><span>02</span><div><h2>Primary CTA</h2><p>Main conversion card.</p></div></div>
                    <?php ue_links_field('ue_primary_title', 'CTA Heading', 'Ready to grow your brand?'); ?>
                    <?php ue_links_field('ue_primary_text', 'CTA Description', 'Let’s turn attention into measurable growth.', 'textarea'); ?>
                    <?php ue_links_field('ue_primary_button', 'Button Text', 'Start a Conversation'); ?>
                    <?php ue_links_field('ue_primary_url', 'Button URL', 'https://wa.me/919764970030', 'url'); ?>
                </section>

                <section class="ue-admin-card ue-admin-card-wide">
                    <div class="ue-card-title"><span>03</span><div><h2>Main Links</h2><p>Edit every social and business link shown on the page.</p></div></div>

                    <div class="ue-link-editor-grid">
                        <div class="ue-mini-card"><h3>🌐 Website</h3><?php ue_links_field('ue_website_label', 'Label', 'Visit Our Website'); ?><?php ue_links_field('ue_website_description', 'Description', 'Explore UpscaleEra & our services'); ?><?php ue_links_field('ue_website_url', 'URL', 'https://upscaleera.com/', 'url'); ?></div>
                        <div class="ue-mini-card"><h3>◎ Instagram</h3><?php ue_links_field('ue_instagram_label', 'Label', 'Follow us on Instagram'); ?><?php ue_links_field('ue_instagram_description', 'Description', 'Creative work, insights & updates'); ?><?php ue_links_field('ue_instagram_url', 'URL', 'https://www.instagram.com/upscaleera.agency/?hl=en', 'url'); ?></div>
                        <div class="ue-mini-card"><h3>◉ WhatsApp</h3><?php ue_links_field('ue_whatsapp_label', 'Label', 'Chat on WhatsApp'); ?><?php ue_links_field('ue_whatsapp_description', 'Description', 'Tell us what you want to grow'); ?><?php ue_links_field('ue_whatsapp_url', 'URL', 'https://wa.me/919764970030', 'url'); ?></div>
                        <div class="ue-mini-card"><h3>in LinkedIn</h3><?php ue_links_field('ue_linkedin_label', 'Label', 'Connect on LinkedIn'); ?><?php ue_links_field('ue_linkedin_description', 'Description', 'Professional updates & agency insights'); ?><?php ue_links_field('ue_linkedin_url', 'URL', 'https://www.linkedin.com/company/upscaleera/posts/?feedView=all', 'url'); ?></div>
                        <div class="ue-mini-card"><h3>f Facebook</h3><?php ue_links_field('ue_facebook_label', 'Label', 'Follow on Facebook'); ?><?php ue_links_field('ue_facebook_description', 'Description', 'News, updates & announcements'); ?><?php ue_links_field('ue_facebook_url', 'URL', 'https://www.facebook.com/UpscaleEra/', 'url'); ?></div>
                    </div>
                </section>

                <section class="ue-admin-card">
                    <div class="ue-card-title"><span>04</span><div><h2>What We Do</h2><p>Four service cards.</p></div></div>
                    <?php ue_links_field('ue_service_1', 'Service 01', 'Performance Marketing'); ?>
                    <?php ue_links_field('ue_service_2', 'Service 02', 'Creative Strategy'); ?>
                    <?php ue_links_field('ue_service_3', 'Service 03', 'Web & Landing Pages'); ?>
                    <?php ue_links_field('ue_service_4', 'Service 04', 'AI & Automation'); ?>
                </section>

                <section class="ue-admin-card">
                    <div class="ue-card-title"><span>05</span><div><h2>Bottom CTA</h2><p>Final conversion section.</p></div></div>
                    <?php ue_links_field('ue_bottom_eyebrow', 'Small Label', 'BUILT FOR GROWTH'); ?>
                    <?php ue_links_field('ue_bottom_heading', 'Heading', 'Built for brands ready to scale.'); ?>
                    <?php ue_links_field('ue_bottom_text', 'Description', 'Strategy, creative and technology working together as one connected growth system.', 'textarea'); ?>
                    <?php ue_links_field('ue_bottom_button', 'Button Text', 'Let’s Work Together'); ?>
                    <?php ue_links_field('ue_bottom_url', 'Button URL', 'https://wa.me/919764970030', 'url'); ?>
                    <?php ue_links_field('ue_footer_text', 'Footer Text', 'UpscaleEra. All rights reserved.'); ?>
                </section>

                <section class="ue-admin-card ue-admin-card-wide">
                    <div class="ue-card-title"><span>06</span><div><h2>Brand Colors</h2><p>Keep these aligned with the UpscaleEra identity.</p></div></div>
                    <div class="ue-fields-three">
                        <?php ue_links_field('ue_primary_color', 'UpscaleEra Orange', '#f26622', 'color'); ?>
                        <?php ue_links_field('ue_background_color', 'Background', '#fff8f0', 'color'); ?>
                        <?php ue_links_field('ue_ink_color', 'Text / Black', '#151515', 'color'); ?>
                    </div>
                </section>
            </div>

            <div class="ue-save-bar">
                <div><strong>Ready?</strong><span>Save your changes, then open the live site to review them.</span></div>
                <button type="submit" name="ue_links_save" value="1" class="button button-primary button-hero">Save Changes</button>
            </div>
        </form>
    </div>

    <style>
        .ue-admin-wrap{max-width:1180px;margin:28px 28px 70px 10px;color:#171717}.ue-admin-head{display:flex;justify-content:space-between;gap:24px;align-items:flex-start;margin-bottom:24px}.ue-admin-kicker{display:inline-block;color:#f26622;font-size:11px;font-weight:800;letter-spacing:.24em;margin-bottom:8px}.ue-admin-head h1{font-size:30px;line-height:1.1;margin:0 0 8px}.ue-admin-head p{max-width:680px;margin:0;color:#646464;font-size:14px;line-height:1.6}.ue-preview-button{margin-top:8px}.ue-admin-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.ue-admin-card{background:#fff;border:1px solid #e7e2db;border-radius:14px;padding:22px;box-shadow:0 7px 24px rgba(35,24,13,.035)}.ue-admin-card-wide{grid-column:1/-1}.ue-card-title{display:flex;gap:13px;align-items:flex-start;padding-bottom:18px;margin-bottom:18px;border-bottom:1px solid #eee9e2}.ue-card-title>span{display:grid;place-items:center;min-width:34px;height:34px;border-radius:9px;background:#fff3ea;color:#f26622;font-weight:800;font-size:11px}.ue-card-title h2{margin:0 0 4px;font-size:17px}.ue-card-title p{margin:0;color:#777;font-size:12px}.ue-admin-field{margin-bottom:15px}.ue-admin-field:last-child{margin-bottom:0}.ue-admin-field>label{display:block;font-weight:650;font-size:12px;margin-bottom:6px}.ue-admin-field input[type=text],.ue-admin-field input[type=url],.ue-admin-field textarea{width:100%;max-width:none;border-color:#ddd6cd;border-radius:8px;padding:8px 10px;min-height:40px}.ue-admin-field textarea{resize:vertical}.ue-admin-field input:focus,.ue-admin-field textarea:focus{border-color:#f26622;box-shadow:0 0 0 1px #f26622}.ue-fields-two{display:grid;grid-template-columns:1fr 1fr;gap:16px}.ue-fields-three{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}.ue-link-editor-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.ue-mini-card{padding:17px;background:#fbfaf8;border:1px solid #eee8e0;border-radius:12px}.ue-mini-card h3{margin:0 0 15px;font-size:14px}.ue-logo-editor{display:flex;align-items:center;gap:18px}.ue-logo-preview{width:190px;min-height:74px;display:grid;place-items:center;border:1px dashed #d8d0c6;border-radius:10px;background:#fbfaf8;color:#8a837b;font-size:12px;padding:10px}.ue-logo-preview img{max-width:100%;max-height:65px;display:block}.ue-logo-editor .button-link-delete{margin-left:10px}.ue-color-row{display:flex;gap:9px;align-items:center}.ue-color-row input[type=color]{width:48px;height:40px;border:1px solid #ddd6cd;border-radius:8px;padding:3px;background:#fff}.ue-color-text{flex:1}.ue-save-bar{position:sticky;bottom:14px;z-index:20;display:flex;align-items:center;justify-content:space-between;gap:20px;margin-top:20px;padding:15px 18px;border:1px solid #ded7ce;border-radius:12px;background:rgba(255,255,255,.96);box-shadow:0 15px 40px rgba(24,16,8,.11);backdrop-filter:blur(10px)}.ue-save-bar div{display:flex;flex-direction:column;gap:2px}.ue-save-bar span{color:#777;font-size:12px}.ue-save-bar .button-primary{background:#f26622;border-color:#f26622}.ue-save-bar .button-primary:hover{background:#dc5519;border-color:#dc5519}@media(max-width:900px){.ue-admin-grid,.ue-link-editor-grid,.ue-fields-two,.ue-fields-three{grid-template-columns:1fr}.ue-admin-card-wide{grid-column:auto}.ue-admin-head{flex-direction:column}.ue-save-bar{align-items:stretch;flex-direction:column}.ue-save-bar .button{width:100%}}
    </style>

    <script>
    jQuery(function($){
        var mediaFrame;
        $('#ue-select-logo').on('click', function(e){
            e.preventDefault();
            if(mediaFrame){ mediaFrame.open(); return; }
            mediaFrame = wp.media({ title:'Choose UpscaleEra Logo', button:{text:'Use this logo'}, multiple:false });
            mediaFrame.on('select', function(){
                var attachment = mediaFrame.state().get('selection').first().toJSON();
                $('#ue_logo_id').val(attachment.id);
                var src = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                $('#ue-logo-preview').addClass('has-logo').html($('<img>',{src:src,alt:'UpscaleEra logo preview'}));
            });
            mediaFrame.open();
        });
        $('#ue-remove-logo').on('click', function(e){ e.preventDefault(); $('#ue_logo_id').val(''); $('#ue-logo-preview').removeClass('has-logo').html('<span>No logo selected</span>'); });
        $('.ue-color-text').on('input change', function(){ var id=$(this).data('color-target'); var val=$(this).val(); if(/^#[0-9a-fA-F]{6}$/.test(val)){ $('#'+id).val(val); } });
        $('input[type=color]').on('input change', function(){ $('[data-color-target="'+this.id+'"]').val(this.value); });
    });
    </script>
    <?php
}

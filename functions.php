<?php
/**
 * UpscaleEra Links child theme functions.
 * GitHub deploys the complete Elementor Home page to WordPress page ID 12.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_stylesheet_directory() . '/inc/page12-reference-home.php';

function ue_links_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'ue_links_setup', 20);

function ue_links_child_assets() {
    wp_enqueue_style(
        'ue-links-child',
        get_stylesheet_uri(),
        array(),
        '2.0.1'
    );

    $css = <<<'CSS'
body.page-id-12{
    margin:0;
    background:#eadbc9;
}
body.page-id-12 .elementor.elementor-12{
    width:100%;
    max-width:470px;
    min-height:100vh;
    margin:0 auto;
    overflow:hidden;
    position:relative;
    background-color:#fff8f0;
    background-image:
        radial-gradient(circle at -30% 22%, transparent 0 210px, rgba(242,106,33,.16) 212px, rgba(242,106,33,.16) 214px, transparent 216px),
        radial-gradient(circle at 132% 66%, transparent 0 250px, rgba(242,106,33,.13) 252px, rgba(242,106,33,.13) 254px, transparent 256px),
        radial-gradient(circle at -15% 93%, transparent 0 170px, rgba(242,106,33,.11) 172px, rgba(242,106,33,.11) 174px, transparent 176px);
    box-shadow:0 24px 70px rgba(74,45,24,.13);
}
body.page-id-12 .elementor-section{
    background:transparent;
}
.ue-brand-logo img{
    width:100%;
    max-width:255px;
    height:auto;
    display:block;
    margin:0 auto;
}
.ue-hero .elementor-widget:not(:last-child){
    margin-bottom:8px;
}
.ue-link-card{
    transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.ue-link-card:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 24px rgba(71,43,20,.08);
    border-color:#f0b68f!important;
}

/* Link cards: icon | text | arrow in a single row on ALL breakpoints. */
body.page-id-12 .ue-link-card.elementor-widget-icon-box .elementor-icon-box-wrapper,
body.page-id-12 .ue-link-card .elementor-icon-box-wrapper{
    display:flex!important;
    flex-direction:row!important;
    flex-wrap:nowrap!important;
    align-items:center!important;
    justify-content:flex-start!important;
    text-align:left!important;
}
body.page-id-12 .ue-link-card .elementor-icon-box-icon{
    display:flex!important;
    align-items:center!important;
    justify-content:center!important;
    flex:0 0 auto!important;
    margin:0 16px 0 0!important;
    padding:0!important;
}
body.page-id-12 .ue-link-card .elementor-icon-box-content{
    display:block!important;
    flex:1 1 auto!important;
    width:auto!important;
    min-width:0!important;
    margin:0!important;
}
body.page-id-12 .ue-link-card .elementor-icon-box-title{
    margin:0!important;
    width:100%!important;
}
body.page-id-12 .ue-link-card .elementor-icon-box-title a{
    display:flex!important;
    flex-direction:row!important;
    align-items:center!important;
    justify-content:space-between!important;
    gap:14px!important;
    width:100%!important;
    min-width:0!important;
    color:#1b1b1b!important;
    white-space:nowrap!important;
}
body.page-id-12 .ue-link-card .elementor-icon-box-title a:after{
    content:'→';
    flex:0 0 auto;
    margin-left:auto;
    color:#f26a21;
    font-size:26px;
    line-height:1;
    font-weight:400;
}

.ue-what-we-do-title .elementor-heading-title{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:14px;
    letter-spacing:2.4px;
}
.ue-what-we-do-title .elementor-heading-title:before,
.ue-what-we-do-title .elementor-heading-title:after{
    content:'';
    display:block;
    width:56px;
    height:1px;
    background:linear-gradient(90deg, transparent, #d9a984);
}
.ue-what-we-do-title .elementor-heading-title:after{
    background:linear-gradient(90deg, #d9a984, transparent);
}
.ue-services-row>.elementor-container{
    display:flex!important;
    flex-wrap:nowrap!important;
    gap:10px;
}
.ue-services-row .elementor-column{
    width:50%!important;
    flex:0 0 calc(50% - 5px)!important;
}
.ue-service-pill .elementor-icon-box-wrapper{
    display:flex!important;
    align-items:center!important;
    text-align:left!important;
}
.ue-service-pill .elementor-icon-box-icon{
    margin:0 9px 0 0!important;
    flex:0 0 auto;
}
.ue-service-pill .elementor-icon-box-title{
    margin:0!important;
    white-space:nowrap;
}
.ue-bottom-cta-wrap>.elementor-container>.elementor-column{
    box-shadow:0 12px 28px rgba(82,47,21,.05);
}
.ue-rocket-icon .elementor-icon{
    font-size:28px!important;
}
.ue-social-row>.elementor-container{
    display:flex!important;
    flex-wrap:nowrap!important;
    justify-content:center;
}
.ue-social-row .elementor-column{
    width:25%!important;
    flex:0 0 25%!important;
}
.ue-social-circle .elementor-icon{
    width:42px;
    height:42px;
    display:inline-flex!important;
    align-items:center;
    justify-content:center;
    border:1px solid #e6d6c6;
    border-radius:50%;
    background:#fffaf4;
    transition:transform .2s ease, border-color .2s ease;
}
.ue-social-circle .elementor-icon:hover{
    transform:translateY(-2px);
    border-color:#f26a21;
}

@media (max-width:767px){
    body.page-id-12 .elementor.elementor-12{
        max-width:100%;
        box-shadow:none;
    }
    .ue-brand-logo img{
        max-width:215px;
    }

    /* Override Elementor's mobile icon-box stacking rules. */
    body.page-id-12 .ue-link-card.elementor-widget-icon-box .elementor-icon-box-wrapper,
    body.page-id-12 .ue-link-card.elementor-widget-icon-box.elementor-position-top .elementor-icon-box-wrapper,
    body.page-id-12 .ue-link-card.elementor-widget-icon-box.elementor-mobile-position-top .elementor-icon-box-wrapper,
    body.page-id-12 .ue-link-card .elementor-icon-box-wrapper{
        display:flex!important;
        flex-direction:row!important;
        flex-wrap:nowrap!important;
        align-items:center!important;
        text-align:left!important;
    }
    body.page-id-12 .ue-link-card .elementor-icon-box-icon{
        margin:0 14px 0 0!important;
        padding:0!important;
        flex:0 0 34px!important;
    }
    body.page-id-12 .ue-link-card .elementor-icon-box-content{
        flex:1 1 auto!important;
        width:calc(100% - 48px)!important;
    }
    body.page-id-12 .ue-link-card .elementor-icon-box-title,
    body.page-id-12 .ue-link-card .elementor-icon-box-title a{
        margin:0!important;
        width:100%!important;
    }
    body.page-id-12 .ue-link-card .elementor-icon-box-title a{
        display:flex!important;
        flex-direction:row!important;
        align-items:center!important;
        justify-content:space-between!important;
        white-space:nowrap!important;
    }

    .ue-service-pill .elementor-icon-box-title{
        font-size:11px!important;
    }
    .ue-service-pill .elementor-icon-box-icon{
        margin-right:7px!important;
    }
}
CSS;

    wp_add_inline_style('ue-links-child', $css);
}
add_action('wp_enqueue_scripts', 'ue_links_child_assets', 50);

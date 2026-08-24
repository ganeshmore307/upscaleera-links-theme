<?php
/**
 * Plugin Name: UpscaleEra Main Links Page
 * Description: Creates the editable /links/ Elementor page on upscaleera.com without changing the active theme or homepage.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) { exit; }

function ue_main_id($seed){ return substr(md5('ue-main-'.$seed),0,8); }
function ue_main_size($n,$unit='px'){ return array('unit'=>$unit,'size'=>$n,'sizes'=>array()); }
function ue_main_edge($t=0,$r=0,$b=0,$l=0,$linked=false){ return array('unit'=>'px','top'=>(string)$t,'right'=>(string)$r,'bottom'=>(string)$b,'left'=>(string)$l,'isLinked'=>$linked); }
function ue_main_widget($seed,$type,$settings){ return array('id'=>ue_main_id($seed),'elType'=>'widget','widgetType'=>$type,'settings'=>$settings,'elements'=>array(),'isInner'=>false); }

function ue_main_logo_url(){
    $custom_logo_id = (int) get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $url = wp_get_attachment_image_url($custom_logo_id,'full');
        if ($url) return $url;
    }
    return 'https://links.upscaleera.com/wp-content/themes/upscaleera-links-theme/assets/images/upscaleera-logo.png';
}

function ue_main_image($seed,$url,$width=255){
    return ue_main_widget($seed,'image',array(
        'image'=>array('url'=>$url,'id'=>0,'alt'=>'UpscaleEra Logo'),
        'image_size'=>'full','align'=>'center','width'=>ue_main_size($width),
        'width_tablet'=>ue_main_size(235),'width_mobile'=>ue_main_size(215),
        '_css_classes'=>'ue-main-brand-logo','_margin'=>ue_main_edge(0,0,8,0)
    ));
}
function ue_main_heading($seed,$text,$size,$color='#1B1B1B',$family='Manrope',$weight='600',$align='center',$tag='h2'){
    return ue_main_widget($seed,'heading',array(
        'title'=>$text,'header_size'=>$tag,'align'=>$align,'title_color'=>$color,
        'typography_typography'=>'custom','typography_font_family'=>$family,
        'typography_font_size'=>ue_main_size($size),'typography_font_size_mobile'=>ue_main_size($size),
        'typography_font_weight'=>$weight,'typography_line_height'=>array('unit'=>'em','size'=>1.2,'sizes'=>array()),
        '_margin'=>ue_main_edge(0,0,0,0)
    ));
}
function ue_main_text($seed,$html,$size=15,$color='#1B1B1B',$align='center',$weight='400'){
    return ue_main_widget($seed,'text-editor',array(
        'editor'=>$html,'align'=>$align,'text_color'=>$color,
        'typography_typography'=>'custom','typography_font_family'=>'Manrope',
        'typography_font_size'=>ue_main_size($size),'typography_font_size_mobile'=>ue_main_size($size),
        'typography_font_weight'=>$weight,'typography_line_height'=>array('unit'=>'em','size'=>1.45,'sizes'=>array()),
        '_margin'=>ue_main_edge(0,0,0,0)
    ));
}
function ue_main_button($seed,$label,$url,$background='#F26A21',$color='#FFFFFF',$border_color='',$outlined=false){
    $s=array(
        'text'=>$label,'link'=>array('url'=>$url,'is_external'=>'on','nofollow'=>''),'align'=>'justify','size'=>'md',
        'button_text_color'=>$color,'typography_typography'=>'custom','typography_font_family'=>'Manrope',
        'typography_font_size'=>ue_main_size(16),'typography_font_weight'=>'700',
        'border_radius'=>array('unit'=>'px','top'=>'14','right'=>'14','bottom'=>'14','left'=>'14','isLinked'=>true),
        'text_padding'=>ue_main_edge(16,20,16,20),'_margin'=>ue_main_edge(0,0,0,0)
    );
    if($outlined){
        $s['background_color']='rgba(255,255,255,0)';$s['border_border']='solid';
        $s['border_width']=array('unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true);
        $s['border_color']=$border_color?:'#F26A21';
    }else{
        $s['background_color']=$background;$s['_box_shadow_box_shadow_type']='yes';
        $s['_box_shadow_box_shadow']=array('horizontal'=>0,'vertical'=>8,'blur'=>22,'spread'=>0,'color'=>'rgba(242,106,33,0.20)');
    }
    return ue_main_widget($seed,'button',$s);
}
function ue_main_icon_box($seed,$title,$url,$icon,$library,$icon_color,$class='ue-main-link-card'){
    return ue_main_widget($seed,'icon-box',array(
        'selected_icon'=>array('value'=>$icon,'library'=>$library),'title_text'=>$title,'description_text'=>'',
        'link'=>array('url'=>$url,'is_external'=>'on','nofollow'=>''),'position'=>'left','title_size'=>'h4',
        'primary_color'=>$icon_color,'secondary_color'=>'#FFFFFF','icon_size'=>ue_main_size(31),'icon_padding'=>ue_main_size(0),
        'title_color'=>'#1B1B1B','title_typography_typography'=>'custom','title_typography_font_family'=>'Manrope',
        'title_typography_font_size'=>ue_main_size(16),'title_typography_font_weight'=>'500',
        '_background_background'=>'classic','_background_color'=>'#FFFDF9','_border_border'=>'solid',
        '_border_width'=>array('unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true),
        '_border_color'=>'#E6D6C6','_border_radius'=>array('unit'=>'px','top'=>'16','right'=>'16','bottom'=>'16','left'=>'16','isLinked'=>true),
        '_padding'=>ue_main_edge(17,18,17,18),'_margin'=>ue_main_edge(0,0,10,0),'_css_classes'=>$class
    ));
}
function ue_main_service_box($seed,$title,$icon){
    return ue_main_widget($seed,'icon-box',array(
        'selected_icon'=>array('value'=>$icon,'library'=>'fa-solid'),'title_text'=>$title,'description_text'=>'',
        'link'=>array('url'=>'','is_external'=>'','nofollow'=>''),'position'=>'left','title_size'=>'h5',
        'primary_color'=>'#F26A21','secondary_color'=>'#FFFFFF','icon_size'=>ue_main_size(18),'icon_padding'=>ue_main_size(0),
        'title_color'=>'#1B1B1B','title_typography_typography'=>'custom','title_typography_font_family'=>'Manrope',
        'title_typography_font_size'=>ue_main_size(12),'title_typography_font_weight'=>'600',
        '_background_background'=>'classic','_background_color'=>'#FFFDF9','_border_border'=>'solid',
        '_border_width'=>array('unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true),
        '_border_color'=>'#E6D6C6','_border_radius'=>array('unit'=>'px','top'=>'999','right'=>'999','bottom'=>'999','left'=>'999','isLinked'=>true),
        '_padding'=>ue_main_edge(12,14,12,14),'_css_classes'=>'ue-main-service-pill'
    ));
}
function ue_main_icon($seed,$icon,$url='',$color='#1B1B1B',$class=''){
    $s=array('selected_icon'=>array('value'=>$icon,'library'=>(strpos($icon,'fab ')===0?'fa-brands':'fa-solid')),
        'primary_color'=>$color,'secondary_color'=>'#FFFFFF','size'=>ue_main_size(19),'align'=>'center','_css_classes'=>$class);
    if($url){$s['link']=array('url'=>$url,'is_external'=>'on','nofollow'=>'');}
    return ue_main_widget($seed,'icon',$s);
}
function ue_main_section($seed,$widgets,$padding=null,$margin=null,$class=''){
    return array('id'=>ue_main_id('section-'.$seed),'elType'=>'section','settings'=>array(
        'content_width'=>'boxed','boxed_width'=>array('unit'=>'px','size'=>430,'sizes'=>array()),'gap'=>'no',
        'padding'=>$padding?:ue_main_edge(0,18,0,18),'margin'=>$margin?:ue_main_edge(0,0,0,0),'_css_classes'=>$class),
        'elements'=>array(array('id'=>ue_main_id('column-'.$seed),'elType'=>'column','settings'=>array('_column_size'=>100),
            'elements'=>$widgets,'isInner'=>false)),'isInner'=>false);
}
function ue_main_two_col_row($seed,$left,$right){
    return array('id'=>ue_main_id('row-'.$seed),'elType'=>'section','settings'=>array(
        'content_width'=>'boxed','boxed_width'=>array('unit'=>'px','size'=>430,'sizes'=>array()),'gap'=>'narrow',
        'padding'=>ue_main_edge(0,18,10,18),'_css_classes'=>'ue-main-services-row'),
        'elements'=>array(
            array('id'=>ue_main_id('left-'.$seed),'elType'=>'column','settings'=>array('_column_size'=>50,'_inline_size'=>50,'_inline_size_mobile'=>50),'elements'=>array($left),'isInner'=>false),
            array('id'=>ue_main_id('right-'.$seed),'elType'=>'column','settings'=>array('_column_size'=>50,'_inline_size'=>50,'_inline_size_mobile'=>50),'elements'=>array($right),'isInner'=>false)
        ),'isInner'=>false);
}
function ue_main_social_row(){
    $items=array(
        array('ig','fab fa-instagram','https://www.instagram.com/upscaleera.agency/'),
        array('li','fab fa-linkedin-in','https://www.linkedin.com/company/upscaleera/'),
        array('wa','fab fa-whatsapp','https://wa.me/919764970030'),
        array('fb','fab fa-facebook-f','https://www.facebook.com/UpscaleEra/')
    );
    $cols=array();
    foreach($items as $i){$cols[]=array('id'=>ue_main_id('social-'.$i[0]),'elType'=>'column','settings'=>array('_column_size'=>25,'_inline_size'=>25,'_inline_size_mobile'=>25),'elements'=>array(ue_main_icon('social-icon-'.$i[0],$i[1],$i[2],'#1B1B1B','ue-main-social-circle')),'isInner'=>false);}
    return array('id'=>ue_main_id('social-row'),'elType'=>'section','settings'=>array('content_width'=>'boxed','boxed_width'=>array('unit'=>'px','size'=>260,'sizes'=>array()),'gap'=>'narrow','padding'=>ue_main_edge(4,18,8,18),'_css_classes'=>'ue-main-social-row'),'elements'=>$cols,'isInner'=>false);
}

function ue_main_links_data(){
    $wa='https://wa.me/919764970030';
    $data=array();
    $data[]=ue_main_section('hero',array(
        ue_main_image('logo',ue_main_logo_url(),255),
        ue_main_heading('tagline','Performance. Creativity. Growth.',21,'#F26A21','Manrope','500','center','div'),
        ue_main_text('services1','Digital Marketing&nbsp;&nbsp; <span style="color:#F26A21">•</span> &nbsp;&nbsp;Performance Marketing',14,'#1B1B1B'),
        ue_main_text('services2','Web Development&nbsp;&nbsp; <span style="color:#F26A21">•</span> &nbsp;&nbsp;AI Automation',14,'#1B1B1B')
    ),ue_main_edge(30,18,18,18),ue_main_edge(0,0,0,0),'ue-main-hero');
    $data[]=ue_main_section('main-cta',array(ue_main_button('grow','Let’s Grow Your Business   →',$wa)),ue_main_edge(0,18,16,18));
    $links=array(
        array('web','Visit Our Website','https://upscaleera.com/','fas fa-globe','fa-solid','#F26A21'),
        array('instagram','Follow on Instagram','https://www.instagram.com/upscaleera.agency/','fab fa-instagram','fa-brands','#E1306C'),
        array('whatsapp','Chat on WhatsApp',$wa,'fab fa-whatsapp','fa-brands','#25D366'),
        array('linkedin','Connect on LinkedIn','https://www.linkedin.com/company/upscaleera/','fab fa-linkedin-in','fa-brands','#0A66C2'),
        array('facebook','Follow on Facebook','https://www.facebook.com/UpscaleEra/','fab fa-facebook-f','fa-brands','#1877F2')
    );
    foreach($links as $l){$data[]=ue_main_section('link-'.$l[0],array(ue_main_icon_box('linkbox-'.$l[0],$l[1],$l[2],$l[3],$l[4],$l[5])),ue_main_edge(0,18,0,18));}
    $data[]=ue_main_section('what',array(ue_main_heading('what-title','WHAT WE DO',13,'#D45C20','Manrope','700','center','div')),ue_main_edge(20,18,12,18),null,'ue-main-what-title');
    $data[]=ue_main_two_col_row('s1',ue_main_service_box('performance','Performance Marketing','fas fa-chart-line'),ue_main_service_box('social','Social Media','fas fa-users'));
    $data[]=ue_main_two_col_row('s2',ue_main_service_box('webdev','Web Development','fas fa-code'),ue_main_service_box('ai','AI Automation','fas fa-robot'));
    $rocket=ue_main_icon('rocket','fas fa-rocket','','#F26A21','ue-main-rocket');
    $cta_h=ue_main_heading('cta-h','Ready to scale your brand?',25,'#1B1B1B','DM Serif Display','400','center','h2');
    $cta_b=ue_main_button('cta-b','Start a Conversation   →',$wa,'#FFFFFF','#F26A21','#F26A21',true);$cta_b['settings']['_margin']=ue_main_edge(4,26,0,26);
    $bottom=ue_main_section('bottom',array($rocket,$cta_h,$cta_b),ue_main_edge(20,18,16,18),ue_main_edge(6,0,12,0),'ue-main-bottom');
    $bottom['elements'][0]['settings']['_background_background']='classic';$bottom['elements'][0]['settings']['_background_color']='#FFF7EE';
    $bottom['elements'][0]['settings']['_border_border']='solid';$bottom['elements'][0]['settings']['_border_width']=array('unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true);
    $bottom['elements'][0]['settings']['_border_color']='#E6D6C6';$bottom['elements'][0]['settings']['_border_radius']=array('unit'=>'px','top'=>'20','right'=>'20','bottom'=>'20','left'=>'20','isLinked'=>true);$bottom['elements'][0]['settings']['_padding']=ue_main_edge(22,16,22,16);
    $data[]=$bottom;$data[]=ue_main_social_row();$data[]=ue_main_section('footer',array(ue_main_text('footer','© 2026 UpscaleEra',12,'#5F5A55')),ue_main_edge(0,18,28,18));
    return $data;
}

function ue_main_create_links_page(){
    if (get_option('ue_main_links_seed_version')==='1.0.0') return;
    $page=get_page_by_path('links');
    if(!$page){$id=wp_insert_post(array('post_title'=>'Links','post_name'=>'links','post_status'=>'publish','post_type'=>'page'));if(is_wp_error($id)||!$id)return;}else{$id=(int)$page->ID;}
    wp_update_post(array('ID'=>$id,'post_title'=>'Links','post_status'=>'publish','post_content'=>''));
    update_post_meta($id,'_elementor_edit_mode','builder');update_post_meta($id,'_elementor_template_type','wp-page');
    update_post_meta($id,'_elementor_version',defined('ELEMENTOR_VERSION')?ELEMENTOR_VERSION:'3.0.0');
    update_post_meta($id,'_elementor_data',wp_slash(wp_json_encode(ue_main_links_data())));
    update_post_meta($id,'_elementor_page_settings',array('hide_title'=>'yes'));update_post_meta($id,'_wp_page_template','elementor_canvas');
    delete_post_meta($id,'_elementor_css');delete_post_meta($id,'_elementor_controls_usage');
    update_option('ue_main_links_page_id',$id,false);update_option('ue_main_links_seed_version','1.0.0',false);
    if(class_exists('\\Elementor\\Plugin')){ $e=\Elementor\Plugin::instance(); if($e&&isset($e->files_manager))$e->files_manager->clear_cache(); }
}
add_action('init','ue_main_create_links_page',30);

function ue_main_body_class($classes){$id=(int)get_option('ue_main_links_page_id');if($id&&is_page($id))$classes[]='ue-main-links-page';return $classes;}
add_filter('body_class','ue_main_body_class');

function ue_main_links_css(){
    if(!in_array('ue-main-links-page',get_body_class(),true))return;
    wp_register_style('ue-main-links-inline',false,array(), '1.0.0');wp_enqueue_style('ue-main-links-inline');
    $css=<<<'CSS'
body.ue-main-links-page{margin:0;background:#eadbc9;}body.ue-main-links-page .elementor{width:100%;max-width:470px;min-height:100vh;margin:0 auto;overflow:hidden;position:relative;background-color:#fff8f0;background-image:radial-gradient(circle at -30% 22%,transparent 0 210px,rgba(242,106,33,.16) 212px,rgba(242,106,33,.16) 214px,transparent 216px),radial-gradient(circle at 132% 66%,transparent 0 250px,rgba(242,106,33,.13) 252px,rgba(242,106,33,.13) 254px,transparent 256px),radial-gradient(circle at -15% 93%,transparent 0 170px,rgba(242,106,33,.11) 172px,rgba(242,106,33,.11) 174px,transparent 176px);box-shadow:0 24px 70px rgba(74,45,24,.13)}body.ue-main-links-page .elementor-section{background:transparent}.ue-main-brand-logo img{width:100%;max-width:255px;height:auto;display:block;margin:0 auto}.ue-main-hero .elementor-widget:not(:last-child){margin-bottom:8px}.ue-main-link-card{transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease}.ue-main-link-card:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(71,43,20,.08);border-color:#f0b68f!important}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-wrapper{display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;align-items:center!important;text-align:left!important}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-icon{display:flex!important;align-items:center!important;justify-content:center!important;flex:0 0 auto!important;margin:0 16px 0 0!important;padding:0!important}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-content{flex:1 1 auto!important;width:auto!important;min-width:0!important;margin:0!important}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-title{margin:0!important;width:100%!important}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-title a{display:flex!important;flex-direction:row!important;align-items:center!important;justify-content:space-between!important;gap:14px!important;width:100%!important;color:#1b1b1b!important;white-space:nowrap!important}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-title a:after{content:'→';flex:0 0 auto;margin-left:auto;color:#f26a21;font-size:26px;line-height:1}.ue-main-what-title .elementor-heading-title{display:flex;align-items:center;justify-content:center;gap:14px;letter-spacing:2.4px}.ue-main-what-title .elementor-heading-title:before,.ue-main-what-title .elementor-heading-title:after{content:'';display:block;width:56px;height:1px;background:linear-gradient(90deg,transparent,#d9a984)}.ue-main-what-title .elementor-heading-title:after{background:linear-gradient(90deg,#d9a984,transparent)}.ue-main-services-row>.elementor-container{display:flex!important;flex-wrap:nowrap!important;gap:10px}.ue-main-services-row .elementor-column{width:50%!important;flex:0 0 calc(50% - 5px)!important}body.ue-main-links-page .ue-main-service-pill .elementor-icon-box-wrapper{display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;align-items:center!important;text-align:left!important}body.ue-main-links-page .ue-main-service-pill .elementor-icon-box-icon{display:flex!important;align-items:center!important;justify-content:center!important;flex:0 0 auto!important;margin:0 9px 0 0!important;padding:0!important}body.ue-main-links-page .ue-main-service-pill .elementor-icon-box-content{flex:1 1 auto!important;width:auto!important;min-width:0!important;margin:0!important}body.ue-main-links-page .ue-main-service-pill .elementor-icon-box-title{margin:0!important;white-space:nowrap!important;line-height:1.15!important}.ue-main-bottom>.elementor-container>.elementor-column{box-shadow:0 12px 28px rgba(82,47,21,.05)}.ue-main-rocket .elementor-icon{font-size:28px!important}.ue-main-social-row>.elementor-container{display:flex!important;flex-wrap:nowrap!important;justify-content:center}.ue-main-social-row .elementor-column{width:25%!important;flex:0 0 25%!important}.ue-main-social-circle .elementor-icon{width:42px;height:42px;display:inline-flex!important;align-items:center;justify-content:center;border:1px solid #e6d6c6;border-radius:50%;background:#fffaf4;transition:transform .2s ease,border-color .2s ease}.ue-main-social-circle .elementor-icon:hover{transform:translateY(-2px);border-color:#f26a21}@media(max-width:767px){body.ue-main-links-page .elementor{max-width:100%;box-shadow:none}.ue-main-brand-logo img{max-width:215px}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-wrapper{display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;align-items:center!important;text-align:left!important}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-icon{margin:0 14px 0 0!important;flex:0 0 34px!important}body.ue-main-links-page .ue-main-link-card .elementor-icon-box-content{flex:1 1 auto!important;width:calc(100% - 48px)!important}body.ue-main-links-page .ue-main-service-pill .elementor-icon-box-wrapper{display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;align-items:center!important;text-align:left!important}body.ue-main-links-page .ue-main-service-pill .elementor-icon-box-icon{margin:0 7px 0 0!important;flex:0 0 20px!important}body.ue-main-links-page .ue-main-service-pill .elementor-icon-box-title{font-size:11px!important;white-space:nowrap!important;line-height:1.1!important}}
CSS;
    wp_add_inline_style('ue-main-links-inline',$css);
}
add_action('wp_enqueue_scripts','ue_main_links_css',99);

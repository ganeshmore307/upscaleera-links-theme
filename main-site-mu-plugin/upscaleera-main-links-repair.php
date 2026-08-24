<?php
/**
 * Plugin Name: UpscaleEra Main Links Repair
 * Description: One-time repair for the /links/ Elementor page if it was created blank.
 * Version: 1.1.0
 */

if (!defined('ABSPATH')) { exit; }

function ue_main_links_repair_id($seed){ return substr(md5('ue-repair-'.$seed),0,8); }
function ue_main_links_repair_size($n,$unit='px'){ return array('unit'=>$unit,'size'=>$n,'sizes'=>array()); }
function ue_main_links_repair_edge($t=0,$r=0,$b=0,$l=0,$linked=false){ return array('unit'=>'px','top'=>(string)$t,'right'=>(string)$r,'bottom'=>(string)$b,'left'=>(string)$l,'isLinked'=>$linked); }
function ue_main_links_repair_widget($seed,$type,$settings){ return array('id'=>ue_main_links_repair_id($seed),'elType'=>'widget','widgetType'=>$type,'settings'=>$settings,'elements'=>array(),'isInner'=>false); }

function ue_main_links_repair_image($url){
    return ue_main_links_repair_widget('logo','image',array(
        'image'=>array('url'=>$url,'id'=>0,'alt'=>'UpscaleEra Logo'),'image_size'=>'full','align'=>'center',
        'width'=>ue_main_links_repair_size(255),'width_tablet'=>ue_main_links_repair_size(235),'width_mobile'=>ue_main_links_repair_size(215),
        '_css_classes'=>'ue-main-brand-logo','_margin'=>ue_main_links_repair_edge(0,0,8,0)
    ));
}
function ue_main_links_repair_heading($seed,$text,$size,$color='#1B1B1B',$family='Manrope',$weight='600',$tag='h2'){
    return ue_main_links_repair_widget($seed,'heading',array(
        'title'=>$text,'header_size'=>$tag,'align'=>'center','title_color'=>$color,
        'typography_typography'=>'custom','typography_font_family'=>$family,
        'typography_font_size'=>ue_main_links_repair_size($size),'typography_font_size_mobile'=>ue_main_links_repair_size($size),
        'typography_font_weight'=>$weight,'typography_line_height'=>array('unit'=>'em','size'=>1.2,'sizes'=>array()),'_margin'=>ue_main_links_repair_edge()
    ));
}
function ue_main_links_repair_text($seed,$html,$size=14,$color='#1B1B1B'){
    return ue_main_links_repair_widget($seed,'text-editor',array(
        'editor'=>$html,'align'=>'center','text_color'=>$color,'typography_typography'=>'custom','typography_font_family'=>'Manrope',
        'typography_font_size'=>ue_main_links_repair_size($size),'typography_font_size_mobile'=>ue_main_links_repair_size($size),
        'typography_font_weight'=>'400','typography_line_height'=>array('unit'=>'em','size'=>1.45,'sizes'=>array()),'_margin'=>ue_main_links_repair_edge()
    ));
}
function ue_main_links_repair_button($seed,$text,$url,$outlined=false){
    $s=array('text'=>$text,'link'=>array('url'=>$url,'is_external'=>'on','nofollow'=>''),'align'=>'justify','size'=>'md',
        'button_text_color'=>$outlined?'#F26A21':'#FFFFFF','background_color'=>$outlined?'rgba(255,255,255,0)':'#F26A21',
        'typography_typography'=>'custom','typography_font_family'=>'Manrope','typography_font_size'=>ue_main_links_repair_size(16),'typography_font_weight'=>'700',
        'border_radius'=>array('unit'=>'px','top'=>'14','right'=>'14','bottom'=>'14','left'=>'14','isLinked'=>true),'text_padding'=>ue_main_links_repair_edge(16,20,16,20));
    if($outlined){ $s['border_border']='solid'; $s['border_width']=array('unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true); $s['border_color']='#F26A21'; }
    return ue_main_links_repair_widget($seed,'button',$s);
}
function ue_main_links_repair_icon_box($seed,$title,$url,$icon,$library,$color,$class='ue-main-link-card'){
    return ue_main_links_repair_widget($seed,'icon-box',array(
        'selected_icon'=>array('value'=>$icon,'library'=>$library),'title_text'=>$title,'description_text'=>'',
        'link'=>array('url'=>$url,'is_external'=>'on','nofollow'=>''),'position'=>'left','title_size'=>'h4',
        'primary_color'=>$color,'secondary_color'=>'#FFFFFF','icon_size'=>ue_main_links_repair_size(31),'icon_padding'=>ue_main_links_repair_size(0),
        'title_color'=>'#1B1B1B','title_typography_typography'=>'custom','title_typography_font_family'=>'Manrope','title_typography_font_size'=>ue_main_links_repair_size(16),'title_typography_font_weight'=>'500',
        '_background_background'=>'classic','_background_color'=>'#FFFDF9','_border_border'=>'solid','_border_width'=>array('unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true),
        '_border_color'=>'#E6D6C6','_border_radius'=>array('unit'=>'px','top'=>'16','right'=>'16','bottom'=>'16','left'=>'16','isLinked'=>true),
        '_padding'=>ue_main_links_repair_edge(17,18,17,18),'_margin'=>ue_main_links_repair_edge(0,0,10,0),'_css_classes'=>$class
    ));
}
function ue_main_links_repair_service($seed,$title,$icon){
    return ue_main_links_repair_widget($seed,'icon-box',array(
        'selected_icon'=>array('value'=>$icon,'library'=>'fa-solid'),'title_text'=>$title,'description_text'=>'','link'=>array('url'=>'','is_external'=>'','nofollow'=>''),
        'position'=>'left','title_size'=>'h5','primary_color'=>'#F26A21','secondary_color'=>'#FFFFFF','icon_size'=>ue_main_links_repair_size(18),'icon_padding'=>ue_main_links_repair_size(0),
        'title_color'=>'#1B1B1B','title_typography_typography'=>'custom','title_typography_font_family'=>'Manrope','title_typography_font_size'=>ue_main_links_repair_size(12),'title_typography_font_weight'=>'600',
        '_background_background'=>'classic','_background_color'=>'#FFFDF9','_border_border'=>'solid','_border_width'=>array('unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true),
        '_border_color'=>'#E6D6C6','_border_radius'=>array('unit'=>'px','top'=>'999','right'=>'999','bottom'=>'999','left'=>'999','isLinked'=>true),'_padding'=>ue_main_links_repair_edge(12,14,12,14),'_css_classes'=>'ue-main-service-pill'
    ));
}
function ue_main_links_repair_icon($seed,$icon,$url='',$class=''){
    $s=array('selected_icon'=>array('value'=>$icon,'library'=>(strpos($icon,'fab ')===0?'fa-brands':'fa-solid')),'primary_color'=>'#1B1B1B','secondary_color'=>'#FFFFFF','size'=>ue_main_links_repair_size(19),'align'=>'center','_css_classes'=>$class);
    if($url){$s['link']=array('url'=>$url,'is_external'=>'on','nofollow'=>'');}
    return ue_main_links_repair_widget($seed,'icon',$s);
}
function ue_main_links_repair_section($seed,$widgets,$padding=null,$class=''){
    return array('id'=>ue_main_links_repair_id('sec-'.$seed),'elType'=>'section','settings'=>array(
        'content_width'=>'boxed','boxed_width'=>array('unit'=>'px','size'=>430,'sizes'=>array()),'gap'=>'no','padding'=>$padding?:ue_main_links_repair_edge(0,18,0,18),'_css_classes'=>$class),
        'elements'=>array(array('id'=>ue_main_links_repair_id('col-'.$seed),'elType'=>'column','settings'=>array('_column_size'=>100),'elements'=>$widgets,'isInner'=>false)),'isInner'=>false);
}
function ue_main_links_repair_row($seed,$left,$right){
    return array('id'=>ue_main_links_repair_id('row-'.$seed),'elType'=>'section','settings'=>array('content_width'=>'boxed','boxed_width'=>array('unit'=>'px','size'=>430,'sizes'=>array()),'gap'=>'narrow','padding'=>ue_main_links_repair_edge(0,18,10,18),'_css_classes'=>'ue-main-services-row'),'elements'=>array(
        array('id'=>ue_main_links_repair_id('l-'.$seed),'elType'=>'column','settings'=>array('_column_size'=>50,'_inline_size'=>50,'_inline_size_mobile'=>50),'elements'=>array($left),'isInner'=>false),
        array('id'=>ue_main_links_repair_id('r-'.$seed),'elType'=>'column','settings'=>array('_column_size'=>50,'_inline_size'=>50,'_inline_size_mobile'=>50),'elements'=>array($right),'isInner'=>false)
    ),'isInner'=>false);
}
function ue_main_links_repair_data(){
    $wa='https://wa.me/919764970030';
    $logo='https://links.upscaleera.com/wp-content/themes/upscaleera-links-theme/assets/images/upscaleera-logo.png';
    $custom=(int)get_theme_mod('custom_logo'); if($custom){$u=wp_get_attachment_image_url($custom,'full'); if($u)$logo=$u;}
    $d=array();
    $d[]=ue_main_links_repair_section('hero',array(ue_main_links_repair_image($logo),ue_main_links_repair_heading('tag','Performance. Creativity. Growth.',21,'#F26A21','Manrope','500','div'),ue_main_links_repair_text('s1','Digital Marketing&nbsp;&nbsp; <span style="color:#F26A21">•</span> &nbsp;&nbsp;Performance Marketing'),ue_main_links_repair_text('s2','Web Development&nbsp;&nbsp; <span style="color:#F26A21">•</span> &nbsp;&nbsp;AI Automation')),ue_main_links_repair_edge(30,18,18,18),'ue-main-hero');
    $d[]=ue_main_links_repair_section('cta',array(ue_main_links_repair_button('grow','Let’s Grow Your Business   →',$wa)),ue_main_links_repair_edge(0,18,16,18));
    foreach(array(
        array('web','Visit Our Website','https://upscaleera.com/','fas fa-globe','fa-solid','#F26A21'),array('ig','Follow on Instagram','https://www.instagram.com/upscaleera.agency/','fab fa-instagram','fa-brands','#E1306C'),array('wa','Chat on WhatsApp',$wa,'fab fa-whatsapp','fa-brands','#25D366'),array('li','Connect on LinkedIn','https://www.linkedin.com/company/upscaleera/','fab fa-linkedin-in','fa-brands','#0A66C2'),array('fb','Follow on Facebook','https://www.facebook.com/UpscaleEra/','fab fa-facebook-f','fa-brands','#1877F2')
    ) as $x){$d[]=ue_main_links_repair_section('link-'.$x[0],array(ue_main_links_repair_icon_box('box-'.$x[0],$x[1],$x[2],$x[3],$x[4],$x[5])),ue_main_links_repair_edge(0,18,0,18));}
    $d[]=ue_main_links_repair_section('what',array(ue_main_links_repair_heading('what-h','WHAT WE DO',13,'#D45C20','Manrope','700','div')),ue_main_links_repair_edge(20,18,12,18),'ue-main-what-title');
    $d[]=ue_main_links_repair_row('one',ue_main_links_repair_service('pm','Performance Marketing','fas fa-chart-line'),ue_main_links_repair_service('sm','Social Media','fas fa-users'));
    $d[]=ue_main_links_repair_row('two',ue_main_links_repair_service('web','Web Development','fas fa-code'),ue_main_links_repair_service('ai','AI Automation','fas fa-robot'));
    $bottom=ue_main_links_repair_section('bottom',array(ue_main_links_repair_icon('rocket','fas fa-rocket','','ue-main-rocket'),ue_main_links_repair_heading('ready','Ready to scale your brand?',25,'#1B1B1B','DM Serif Display','400','h2'),ue_main_links_repair_button('start','Start a Conversation   →',$wa,true)),ue_main_links_repair_edge(22,18,22,18),'ue-main-bottom');
    $bottom['elements'][0]['settings']['_background_background']='classic';$bottom['elements'][0]['settings']['_background_color']='#FFF7EE';$bottom['elements'][0]['settings']['_border_border']='solid';$bottom['elements'][0]['settings']['_border_width']=array('unit'=>'px','top'=>'1','right'=>'1','bottom'=>'1','left'=>'1','isLinked'=>true);$bottom['elements'][0]['settings']['_border_color']='#E6D6C6';$bottom['elements'][0]['settings']['_border_radius']=array('unit'=>'px','top'=>'20','right'=>'20','bottom'=>'20','left'=>'20','isLinked'=>true);$d[]=$bottom;
    $social=array(); foreach(array(array('ig','fab fa-instagram','https://www.instagram.com/upscaleera.agency/'),array('li','fab fa-linkedin-in','https://www.linkedin.com/company/upscaleera/'),array('wa','fab fa-whatsapp',$wa),array('fb','fab fa-facebook-f','https://www.facebook.com/UpscaleEra/')) as $x){$social[]=array('id'=>ue_main_links_repair_id('sc-'.$x[0]),'elType'=>'column','settings'=>array('_column_size'=>25,'_inline_size'=>25,'_inline_size_mobile'=>25),'elements'=>array(ue_main_links_repair_icon('sci-'.$x[0],$x[1],$x[2],'ue-main-social-circle')),'isInner'=>false);} $d[]=array('id'=>ue_main_links_repair_id('social'),'elType'=>'section','settings'=>array('content_width'=>'boxed','boxed_width'=>array('unit'=>'px','size'=>260,'sizes'=>array()),'gap'=>'narrow','padding'=>ue_main_links_repair_edge(4,18,8,18),'_css_classes'=>'ue-main-social-row'),'elements'=>$social,'isInner'=>false);
    $d[]=ue_main_links_repair_section('footer',array(ue_main_links_repair_text('foot','© 2026 UpscaleEra',12,'#5F5A55')),ue_main_links_repair_edge(0,18,28,18));
    return $d;
}

function ue_main_links_force_repair(){
    if(get_option('ue_main_links_repair_version')==='1.1.0') return;
    $page=get_page_by_path('links');
    if(!$page){ return; }
    $id=(int)$page->ID;
    $data=ue_main_links_repair_data();
    $json=wp_json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    if(!$json || !is_array(json_decode($json,true)) || count(json_decode($json,true))<5){ return; }

    wp_update_post(array('ID'=>$id,'post_status'=>'publish','post_content'=>''));
    delete_post_meta($id,'_elementor_css'); delete_post_meta($id,'_elementor_controls_usage');
    update_post_meta($id,'_elementor_edit_mode','builder');
    update_post_meta($id,'_elementor_template_type','wp-page');
    update_post_meta($id,'_elementor_version',defined('ELEMENTOR_VERSION')?ELEMENTOR_VERSION:'3.0.0');
    update_post_meta($id,'_elementor_data',wp_slash($json));
    update_post_meta($id,'_elementor_page_settings',array('hide_title'=>'yes'));
    update_post_meta($id,'_wp_page_template','elementor_canvas');

    // Verify the data really survived WordPress metadata slashing.
    $stored=get_post_meta($id,'_elementor_data',true);
    $decoded=json_decode($stored,true);
    if(!is_array($decoded) || count($decoded)<5){
        // Retry without pre-slashing for hosts/plugins that preserve slashes differently.
        update_post_meta($id,'_elementor_data',$json);
        $stored=get_post_meta($id,'_elementor_data',true);
        $decoded=json_decode($stored,true);
    }
    if(is_array($decoded) && count($decoded)>=5){
        update_option('ue_main_links_page_id',$id,false);
        update_option('ue_main_links_repair_version','1.1.0',false);
    }

    if(class_exists('\\Elementor\\Plugin')){
        $e=\Elementor\Plugin::instance();
        if($e && isset($e->files_manager)) $e->files_manager->clear_cache();
    }
}
add_action('wp_loaded','ue_main_links_force_repair',99);

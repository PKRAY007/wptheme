<?php
$output = $color = $el_class = $bg_color = $css_animation = '';
extract(shortcode_atts(array(
    'el_class' => '',
    'style' => '',
	'bg_color' =>'',
    'css_animation' => ''
), $atts));
$el_class = $this->getExtraClass($el_class);

$class = "";
//$style = "square_outlined";
$class .= ($style!='') ? ' vc_alert_'.$style : '';
$class .= ( $color != '' && $color != "alert-block") ? ' wpb_'.$color : '';

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_alert wpb_content_element'.$class.$el_class, $this->settings['base']);

$css_class .= $this->getCSSAnimation($css_animation);
?>
<div class="wpb_tab <?php echo $css_class; ?> <?php echo $bg_color; ?>">
	<div class="messagebox_text"><?php echo wpb_js_remove_wpautop($content, true); ?></div>

</div>
<?php echo $this->endBlockComment('alert box')."\n";
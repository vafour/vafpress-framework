<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<input type="text" name="<?php echo $name ?>" class="vp-input input-large" value="<?php echo esc_attr($value); ?>" />

<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>
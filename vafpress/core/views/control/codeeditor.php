<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<textarea class="vp-input" name="<?php echo $name; ?>" style="display: none;"><?php echo $value; ?></textarea>
<div class="vp-js-codeeditor" data-vp-opt="<?php echo $opt; ?>"></div>

<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>
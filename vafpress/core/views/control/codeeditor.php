<?php echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<textarea name="<?php echo $name; ?>" style="display: none;"><?php echo $value; ?></textarea>
<div class="vp-js-codeeditor" data-vp-opt="<?php echo $opt; ?>"></div>

<?php echo VP_View::instance()->load('control/template_control_foot'); ?>
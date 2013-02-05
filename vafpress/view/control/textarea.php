<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<textarea name="<?php echo $name; ?>"><?php echo $value; ?></textarea>

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
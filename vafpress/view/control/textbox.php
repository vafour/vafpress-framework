<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<input type="text" name="<?php echo $name ?>" class="input-large" value="<?php echo $value; ?>" />

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<label class="indicator" for="<?php echo $name; ?>"><span style="background-color: <?php echo $value; ?>;"></span></label>
<input maxlength="7" id="<?php echo $name; ?>" class="vp-js-colorpicker"
	type="text" name="<?php echo $name ?>" value="<?php echo $value; ?>" data-vp-opt="<?php echo $opt; ?>" />

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
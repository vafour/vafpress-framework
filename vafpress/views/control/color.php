<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<label class="indicator" for="<?php echo $name; ?>" style="background-color: <?php echo $value; ?>;" maxlength="7"></label>
<input maxlength="7" id="<?php echo $name; ?>" class="vp-js-colorpicker" type="text" name="<?php echo $name ?>" value="<?php echo $value; ?>" />

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<input type="text" readonly id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
<div class="buttons">
	<input class="vp-js-upload vp-button" type="button" value="<?php _e('Choose File', 'vp_textdomain'); ?>" />
	<input class="vp-js-remove-upload vp-button" type="button" value="x" />
</div>
<div class="image">
	<img src="<?php echo $preview; ?>" alt="" />
</div>

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<!--
<div class="input">-->
	<input type="text" readonly id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
	<input class="vp-js-upload vp-button" type="button" value="<?php _e('Choose File', 'vp_textdomain'); ?>" >
</div>
<div class="image">
	<img src="<?php echo $value; ?>" alt="" />
</div>

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
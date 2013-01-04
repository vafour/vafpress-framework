<tr class="vp-color<?php echo ($container_extra_classes) ? ' ' . $container_extra_classes : '' ?>" <?php echo VP_Util_Text::print_if_exists($validation, 'data-vp-validation="%s"'); ?> id="<?php echo $name; ?>" id="<?php echo $name; ?>">
	<td class="label">
		<label>
			<?php echo $label; ?>
			<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
		</label>
	</td>
	<td class="fields">
		<p>
			<label for="<?php echo $name; ?>" style="background-color: <?php echo $value; ?>;" maxlength="7"></label>
			<input maxlength="7" id="<?php echo $name; ?>" class="vp-js-colorpicker" type="text" name="<?php echo $name ?>" value="<?php echo $value; ?>" />
		</p>
		<div class="validation-msgs"><ul></ul></div>
	</td>
</tr>
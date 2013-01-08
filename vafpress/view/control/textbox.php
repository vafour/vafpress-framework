<tr class="vp-field vp-textbox<?php echo ($container_extra_classes) ? ' ' . $container_extra_classes : '' ?>" data-vp-type="vp-textbox" <?php echo VP_Util_Text::print_if_exists($validation, 'data-vp-validation="%s"'); ?> id="<?php echo $name; ?>">
	<td class="label">
		<label>
			<?php echo $label; ?>
			<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
		</label>
	</td>
	<td class="fields">
		<div class="input">
			<input type="text" name="<?php echo $name ?>" class="input-large" value="<?php echo $value; ?>" />
		</div>
		<div class="validation-msgs"><ul></ul></div>
	</td>
</tr>
<tr class="vp-textarea<?php echo ($container_extra_classes) ? ' ' . $container_extra_classes : '' ?>" <?php echo VP_Util_Text::print_if_exists($validation, 'data-vp-validation="%s"'); ?> id="<?php echo $name; ?>">
	<td class="label">
		<label>
			<?php echo $label; ?>
			<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
		</label>
	</td>
	<td class="fields">
		<div class="vp-control">
			<textarea name="<?php echo $name; ?>"><?php echo $value; ?></textarea>
		</div>
		<div class="validation-msgs"><ul></ul></div>
	</td>
</tr>
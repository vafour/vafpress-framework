<tr class="vp-field vp-checkbox<?php echo ($container_extra_classes) ? ' ' . $container_extra_classes : '' ?>" data-vp-type="vp-checkbox" <?php echo VP_Util_Text::print_if_exists($validation, 'data-vp-validation="%s"'); ?> id="<?php echo $name; ?>" id="<?php echo $name; ?>">
	<td class="label">
		<label>
			<?php echo $label; ?>
			<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
		</label>
	</td>
	<td class="fields">
		<div class="input">
			<?php foreach ($items as $item): ?>
			<label>
				<input <?php if(in_array($item->value, $value)) echo "checked" ?> type="checkbox" name="<?php echo $name; ?>" value="<?php echo $item->value; ?>" />
				<span></span><?php echo $item->label; ?>
			</label>
			<?php endforeach; ?>
		</div>
		<div class="validation-msgs"><ul></ul></div>
	</td>
</tr>
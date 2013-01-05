<tr class="vp-checkimage<?php echo ($container_extra_classes) ? ' ' . $container_extra_classes : '' ?>" <?php echo VP_Util_Text::print_if_exists($validation, 'data-vp-validation="%s"'); ?> id="<?php echo $name; ?>" id="<?php echo $name; ?>">
	<td class="label">
		<label>
			<?php echo $label; ?>
			<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
		</label>
	</td>
	<td class="fields">
		<div class="vp-control">
			<?php foreach ($items as $item): ?>
			<label>
				<input <?php if(in_array($item->value, $value)) echo "checked" ?> type="checkbox" name="<?php echo $name; ?>" value="<?php echo $item->value; ?>" />
				<img src="<?php echo VP_Util_Res::img($item->img); ?>" alt="<?php echo $item->label; ?>" class="vp-js-tipsy image-item" style="<?php VP_Util_Text::print_if_exists($item_max_width, 'max-width: %spx; '); ?><?php VP_Util_Text::print_if_exists($item_max_height, 'max-height: %spx; '); ?>" original-title="<?php echo $item->label; ?>" />
			</label>
			<?php endforeach; ?>
		</div>
		<div class="validation-msgs"><ul></ul></div>
	</td>
</tr>
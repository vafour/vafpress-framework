<tr class="vp-slider<?php echo ($container_extra_classes) ? ' ' . $container_extra_classes : '' ?>" <?php echo VP_Util_Text::print_if_exists($validation, 'data-vp-validation="%s"'); ?> id="<?php echo $name; ?>">
	<td class="label">
		<label>
			<?php echo $label; ?>
			<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
		</label>
	</td>
	<td class="fields">
		<div class="vp-control">
			<input type="text" name="<?php echo $name; ?>" class="slideinput vp-js-tipsy" original-title="Range between <?php echo $opt_raw['min']; ?> and <?php echo $opt_raw['max']; ?>" value="<?php echo $value; ?>" />
			<div class="vp-js-slider slidebar" id="<?php echo $name; ?>" data-vp-opt="<?php echo $opt; ?>"></div>
		</div>
		<div class="validation-msgs"><ul></ul></div>
	</td>
</tr>
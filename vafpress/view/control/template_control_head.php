<tr class="vp-field <?php echo $type; echo ($container_extra_classes) ? ' ' . $container_extra_classes : '' ?>" data-vp-type="<?php echo $type; ?>" <?php echo VP_Util_Text::print_if_exists($validation, 'data-vp-validation="%s"'); ?> <?php echo VP_Util_Text::print_if_exists(isset($bind) ? $bind : '', 'data-vp-bind="%s"'); ?> id="<?php echo $name; ?>">
	<td class="label">
		<label>
			<?php echo $label; ?>
			<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
		</label>
	</td>
	<td class="fields">
		<div class="input">
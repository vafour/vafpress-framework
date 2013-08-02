<div class="vp-field <?php echo $type; ?><?php echo !empty($container_extra_classes) ? (' ' . $container_extra_classes) : ''; ?>"
	data-vp-type="<?php echo $type; ?>"
	<?php echo VP_Util_Text::print_if_exists($validation, 'data-vp-validation="%s"'); ?>
	<?php echo VP_Util_Text::print_if_exists(isset($binding) ? $binding : '', 'data-vp-bind="%s"'); ?>
	<?php echo VP_Util_Text::print_if_exists(isset($items_binding) ? $items_binding : '', 'data-vp-items-bind="%s"'); ?>
	<?php echo VP_Util_Text::print_if_exists(isset($dependency) ? $dependency : '', 'data-vp-dependency="%s"'); ?>
	id="<?php echo $name; ?>">
	<div class="label">
		<label><?php echo $label; ?></label>
		<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
	</div>
	<div class="field">
		<div class="input">
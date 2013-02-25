<?php extract($head_info); ?>
<div class="vp-field <?php echo $type; ?><?php echo !empty($container_extra_classes) ? (' ' . $container_extra_classes) : ''; ?>"
	data-vp-type="<?php echo $type; ?>"
	<?php echo VP_Util_Text::print_if_exists(isset($dependency) ? $dependency : '', 'data-vp-dependency="%s"'); ?>
	<?php echo $is_hidden ? 'style="display: none;"' : ''; ?>
	id="<?php echo $name; ?>">
	<div class="label"><?php echo $label; ?></div>
	<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>
</div>
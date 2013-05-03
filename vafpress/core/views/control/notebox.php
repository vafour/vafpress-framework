<?php extract($head_info); ?>

<?php if(!$is_compact): ?>
<div class="vp-field <?php echo $type; ?><?php echo !empty($container_extra_classes) ? (' ' . $container_extra_classes) : ''; ?>"
	data-vp-type="<?php echo $type; ?>"
	<?php echo VP_Util_Text::print_if_exists(isset($dependency) ? $dependency : '', 'data-vp-dependency="%s"'); ?>
	<?php echo $is_hidden ? 'style="display: none;"' : ''; ?>
	id="<?php echo $name; ?>">
<?php endif; ?>
	<?php switch ($status) {
		case 'normal':
			$icon_class = 'icon-lightbulb';
			break;
		case 'info':
			$icon_class = 'icon-info-sign';
			break;
		case 'success':
			$icon_class = 'icon-ok-sign';
			break;
		case 'warning':
			$icon_class = 'icon-warning-sign';
			break;
		case 'error':
			$icon_class = 'icon-remove-sign';
			break;
		default:
			$icon_class = 'icon-lightbulb';
			break;
	} ?>
	<i class="<?php echo $icon_class; ?>"></i>
	<div class="label"><?php echo $label; ?></div>
	<?php VP_Util_Text::print_if_exists($description, '<div class="description">%s</div>'); ?>

<?php if(!$is_compact): ?>
</div>
<?php endif; ?>
<div <?php VP_Util_Text::print_if_exists($section->get_name(), 'id="%s" '); ?>
	class="vp-section<?php echo !empty($container_extra_classes) ? (' ' . $container_extra_classes) : ''; ?>"
	<?php echo VP_Util_Text::print_if_exists($section->get_dependency(), 'data-vp-dependency="%s"'); ?> >
	<?php VP_Util_Text::print_if_exists($section->get_title(), '<h3>%s</h3>'); ?>
	<?php VP_Util_Text::print_if_exists($section->get_description(), '<span class="description vp-js-tipsy" original-title="%s"></span>'); ?>
	<div class="vp-controls">
		<?php foreach ($section->get_fields() as $field): ?>
		<?php echo $field->render(); ?>
		<?php endforeach; ?>
	</div>
</div>
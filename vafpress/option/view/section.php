<div id="<?php echo $section->get_name(); ?>" class="vp-section">
	<h3><?php echo $section->get_title(); ?></h3>
	<?php VP_Util_Text::print_if_exists($section->get_description(), '<span class="description vp-js-tipsy" original-title="%s"></span>'); ?>
	<table>
		<tbody>
			<?php foreach ($section->get_fields() as $field): ?>
			<?php echo $field->render(); ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
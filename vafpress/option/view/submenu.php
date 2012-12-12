<div id="<?php echo $submenu->get_name(); ?>" class="vp-tab-panel<?php if($current) echo ' vp-current'; ?>">
	<?php foreach ($submenu->get_sections() as $section): ?>
	<?php echo $section->render(); ?>
	<?php endforeach; ?>
</div>
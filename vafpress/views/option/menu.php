<?php $submenus = $menu->get_menus(); ?>
<?php if (!empty($submenus)): ?>
	<?php foreach ($submenus as $submenu): ?>
	<?php $sub_current = ($submenu === reset($submenus)) ? 1 : 0; ?>
	<?php echo $submenu->render(array('current' => $current, 'sub_current' => $sub_current)); ?>
	<?php endforeach; ?>
<?php else: ?>
<div id="<?php echo $menu->get_name(); ?>" class="vp-panel<?php if((isset($sub_current) and $sub_current) or (!isset($sub_current) and $current)) echo ' vp-current'; ?>">
	<h2><span><?php echo $menu->get_title(); ?></span></h2>
	<?php foreach ($menu->get_controls() as $control): ?>
	<?php echo $control->render(); ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>
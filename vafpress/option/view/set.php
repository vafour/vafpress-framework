<div class="wrap">
	<h2><?php echo $set->get_title(); ?></h2>
	<?php if (VP_Util_config::get_instance()->load('vafpress', 'dev_mode') == 1): ?>
	<div class="vp-dev-notif updated"><p><?php _e('Development Mode is active', 'vp_textdomain'); ?></p></div>
	<?php endif; ?>
	<div id="vp-wrap" class="vp-wrap">
		<div id="vp-option-panel" class="vp-option-panel">
			<div class="vp-left-panel">
				<div id="vp-logo" class="vp-logo">
					<img src="<?php echo VP_Util_Res::img($set->get_logo()); ?>" alt="<?php echo $set->get_title(); ?>" />
				</div>
				<div id="vp-menus" class="vp-menus">
					<ul class="vp-menu-level-1">
						<?php foreach ($set->get_menus() as $menu): ?>
						<?php if ($menu === reset($set->get_menus())): ?>
						<li class="vp-current">
						<?php else: ?>
						<li>
						<?php endif; ?>
							<?php if ($menu->get_menus()): ?>
							<a href="#<?php echo $menu->get_name(); ?>" class="vp-js-menu-dropdown vp-menu-dropdown">
							<?php else: ?>
							<a href="#<?php echo $menu->get_name(); ?>" class="vp-js-menu-goto vp-menu-goto">
							<?php endif; ?>
								<?php $icon = VP_Util_Res::img($menu->get_icon()); ?>
								<?php VP_Util_Text::print_if_exists($icon, '<div class="vp-menu-image"><img src="%s" alt="' . $menu->get_title() . '" /></div>'); ?>
								<div class="vp-menu-text"><?php echo $menu->get_title(); ?></div>
							</a>
							<?php if ($menu->get_menus()): ?>
							<ul class="vp-menu-level-2">
								<?php foreach ($menu->get_menus() as $submenu): ?>
								<?php if ($submenu === reset($menu->get_menus())): ?>
								<li class="vp-current">
								<?php else: ?>
								<li>
								<?php endif; ?>
									<a href="#<?php echo $submenu->get_name(); ?>" class="vp-js-menu-goto vp-menu-goto">
										<?php $sub_icon = VP_Util_Res::img($submenu->get_icon()); ?>
										<?php VP_Util_Text::print_if_exists($sub_icon, '<div class="vp-menu-image"><img src="%s" alt="' . $submenu->get_title() . '" /></div>'); ?>
										<div class="vp-menu-text"><?php echo $submenu->get_title(); ?></div>
									</a>
								</li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="vp-right-panel">
				<div id="vp-submit" class="vp-submit">
					<input class="vp-js-save vp-button" type="button" value="<?php _e('Save Changes', 'vp_textdomain'); ?>" />
					<p class="vp-js-save-status save-status" style="display: none;"></p>
				</div>
				<form id="vp-option-form" class="vp-option-form">
				<?php foreach ($set->get_menus() as $menu): ?>
				<?php if ($menu === reset($set->get_menus())): ?>
					<?php echo $menu->render(array('current' => 1)); ?>
				<?php else: ?>
					<?php echo $menu->render(array('current' => 0)); ?>
				<?php endif; ?>
				<?php endforeach; ?>
				</form>
				<div id="vp-copyright" class="vp-copyright">
					<?php printf(__('This option panel is built using <a href="http://vafpress.com">Vafpress Framework %s</a> powered by <a href="http://vafour.com">Vafour</a>', 'vp_textdomain'), VP_VERSION); ?>
				</div>
			</div>
		</div>
		<div id="vp-overlay" class="vp-overlay hidden">
			<div id="vp-loading" class="vp-loading">
				<div class="anim-spin"></div>
				<div class="anim-fade">Saving</div>
			</div>
		</div>
	</div>
</div>
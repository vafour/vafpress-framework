<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<div id="multitext-<?php echo $name; ?>">
<?php if(!empty($value)): ?>
	<?php foreach($value as $v): ?>
	<div>
		<input type="text" value="<?php echo $v; ?>" name="<?php echo $name ?>" class="vp-input input-large" />
		<a href="javascript:void(0);" class="vp-deletion vp-js-multitextbox-remove" rel-id="multitext-<?php echo $name; ?>" rel-name="<?php echo $name ?>"><?php _e('Remove', 'vp_textdomain'); ?></a>
	</div>
	<?php endforeach; ?>
<?php else: ?>
	<?php if(!empty($items)): ?>
		<?php foreach($items as $key => $v): ?>
		<div>
			<input type="text" value="<?php echo $v->value; ?>" name="<?php echo $name ?>" class="vp-input input-large" />
			<a href="javascript:void(0);" class="vp-deletion vp-js-multitextbox-remove" rel-id="multitext-<?php echo $name; ?>" rel-name="<?php echo $name ?>"><?php _e('Remove', 'vp_textdomain'); ?></a>
		</div>
		<?php endforeach; ?>
	<?php else: ?>
		<input type="text" name="<?php echo $name ?>" class="vp-input input-large" />
	<?php endif; ?>
<?php endif; ?>
</div>
<p>
	<a href="javascript:void(0);" class="button button-primary vp-js-multitextbox-add" rel-id="multitext-<?php echo $name; ?>" rel-name="<?php echo $name ?>"><?php _e('Add more', 'vp_textdomain'); ?></a>
</p>


<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>
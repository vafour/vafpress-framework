<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<?php foreach ($items as $item): ?>
<label>
	<input <?php if(in_array($item->value, $value)) echo "checked" ?> type="checkbox" name="<?php echo $name; ?>" value="<?php echo $item->value; ?>" />
	<img src="<?php echo VP_Util_Res::img($item->img); ?>" alt="<?php echo $item->label; ?>" class="vp-js-tipsy image-item" style="<?php VP_Util_Text::print_if_exists($item_max_width, 'max-width: %spx; '); ?><?php VP_Util_Text::print_if_exists($item_max_height, 'max-height: %spx; '); ?>" original-title="<?php echo $item->label; ?>" />
</label>
<?php endforeach; ?>

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
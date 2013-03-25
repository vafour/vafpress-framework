<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<?php foreach ($items as $item): ?>
<label>
	<input type="radio" <?php if($item->value == $value) echo 'checked class="checked"'; ?> name="<?php echo $name; ?>" value="<?php echo $item->value; ?>" />
	<img src="<?php echo VP_Util_Res::img($item->img); ?>" alt="<?php echo $item->label; ?>" class="vp-js-tipsy image-item" style="<?php VP_Util_Text::print_if_exists($item_max_width, 'max-width: %spx; '); ?><?php VP_Util_Text::print_if_exists($item_max_height, 'max-height: %spx; '); ?>" original-title="<?php echo $item->label; ?>" />
</label>
<?php endforeach; ?>

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
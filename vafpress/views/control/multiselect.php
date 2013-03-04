<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<select multiple name="<?php echo $name; ?>" class="vp-js-chosen">
	<?php foreach ($items as $item): ?>
	<option <?php if(in_array($item->value, $value)) echo "selected" ?> value="<?php echo $item->value; ?>"><?php echo $item->label; ?></option>
	<?php endforeach; ?>
</select>

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
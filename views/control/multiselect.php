<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<select multiple name="<?php echo $name; ?>" class="vp-input vp-js-select2" autocomplete="off">
	<?php foreach ($items as $item): ?>
	<option <?php if(is_array($value) && in_array($item->value, $value)) echo "selected" ?> value="<?php echo $item->value; ?>"><?php echo $item->label; ?></option>
	<?php endforeach; ?>
</select>

<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>
<?php echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<select name="<?php echo $name; ?>" class="vp-js-chosen" autocomplete="off">
	<option value=""></option>
	<?php foreach ($items as $item): ?>
	<option <?php if($item->value == $value) echo "selected" ?> value="<?php echo $item->value; ?>"><?php echo $item->label; ?></option>
	<?php endforeach; ?>
</select>

<?php echo VP_View::instance()->load('control/template_control_foot'); ?>
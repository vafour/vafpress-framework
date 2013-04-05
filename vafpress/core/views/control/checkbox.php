<?php echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<?php foreach ($items as $item): ?>
<label>
	<input <?php if(in_array($item->value, $value)) echo 'checked class="checked"'; ?> type="checkbox" name="<?php echo $name; ?>" value="<?php echo $item->value; ?>" />
	<span></span><?php echo $item->label; ?>
</label>
<?php endforeach; ?>

<?php echo VP_View::instance()->load('control/template_control_foot'); ?>

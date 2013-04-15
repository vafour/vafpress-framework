<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<?php foreach ($items as $item): ?>
<label>
	<?php $checked = (in_array($item->value, $value)); ?>
	<input <?php if($checked) echo 'checked'; ?> class="vp-input<?php if($checked) echo " checked"; ?>" type="checkbox" name="<?php echo $name; ?>" value="<?php echo $item->value; ?>" />
	<span></span><?php echo $item->label; ?>
</label>
<?php endforeach; ?>

<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>
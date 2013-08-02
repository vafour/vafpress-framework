<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<div class="customEditor">
	<div class="wp-editor-tools">
		<div class="custom_upload_buttons hide-if-no-js wp-media-buttons"><?php do_action( 'media_buttons' ); ?></div>
	</div>
	<textarea class="vp-input vp-js-wpeditor" id="<?php echo $name . '_ce'; ?>" data-vp-opt="<?php echo $opt; ?>" rows="10" cols="50" name="<?php echo $name; ?>" rows="3"><?php echo stripslashes(apply_filters('the_editor_content', html_entity_decode($value, ENT_COMPAT, 'UTF-8'))); ?></textarea>
</div>

<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>
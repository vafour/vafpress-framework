<div class="vp-field">
	<div class="label">
		<label>
			<?php _e('Restore Default Options', 'vp_textdomain') ?>
			<p class="description"><?php _e('Restore options to initial default values.', 'vp_textdomain') ?></p>
		</label>
	</div>
	<div class="field">
		<div class="input">
			<div class="buttons">
				<input class="vp-js-restore vp-button button button-primary" type="button" value="<?php _e('Restore Default', 'vp_textdomain') ?>" />
				<span style="margin-left: 10px;">
					<span class="vp-field-loader vp-js-loader" style="display: none;"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" style="vertical-align: middle;"></span>
					<span class="vp-js-status" style="display: none;"></span>
				</span>
			</div>
		</div>
	</div>
</div>
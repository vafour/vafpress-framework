<tr class="vp-field vp-textarea" data-vp-type="vp-textarea">
	<td class="label">
		<label>
			<?php _e('Import', 'vp_textdomain') ?>
			<p class="description"><?php _e('Import Options', 'vp_textdomain') ?></p>
		</label>
	</td>
	<td class="fields">
		<div class="input">
			<textarea id="vp-js-import_text"></textarea>
			<div class="buttons">
				<input id="vp-js-import" class="vp-button" type="button" value="<?php _e('Import', 'vp_textdomain') ?>" />
				<span style="margin-left: 10px;">
					<span id="vp-js-import-loader" class="hidden"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>"></span>
					<span id="vp-js-import-status" class="hidden"></span>
				</span>
			</div>
		</div>
	</td>
</tr>

<tr class="vp-field vp-textarea" data-vp-type="vp-textarea">
	<td class="label">
		<label>
			<?php _e('Export', 'vp_textdomain') ?>
			<p class="description"><?php _e('Export Options', 'vp_textdomain') ?></p>
		</label>
	</td>
	<td class="fields">
		<div class="input">
			<textarea id="vp-js-export_text" onclick="this.focus();this.select()" readonly="readonly"></textarea>
			<div class="buttons">
				<input id="vp-js-export" class="vp-button" type="button" value="<?php _e('Export', 'vp_textdomain') ?>" />
				<span style="margin-left: 10px;">
					<span id="vp-js-export-loader" class="hidden"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" style="position:absolute; vertical-align: middle;"></span>
					<span id="vp-js-export-status" class="hidden"></span>
				</span>
			</div>
		</div>
	</td>
</tr>
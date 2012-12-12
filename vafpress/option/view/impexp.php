<tr class="vp-textarea">
	<td class="label">
		<label>
			<?php _e('Import', VP_TEXTDOMAIN) ?>
			<p class="description"><?php _e('Import Options', VP_TEXTDOMAIN) ?></p>
		</label>
	</td>
	<td class="fields">
		<textarea id="vp-js-import_text"></textarea>
		<div class="buttons">
			<input id="vp-js-import" class="vp-button" type="button" value="<?php _e('Import', VP_TEXTDOMAIN) ?>" />
			<span style="margin-left: 10px;">
				<span id="vp-js-import-loader" class="hidden"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>"></span>
				<span id="vp-js-import-status" class="hidden"></span>
			</span>
		</div>
	</td>
</tr>

<tr class="vp-textarea">
	<td class="label">
		<label>
			<?php _e('Export', VP_TEXTDOMAIN) ?>
			<p class="description"><?php _e('Export Options', VP_TEXTDOMAIN) ?></p>
		</label>
	</td>
	<td class="fields">
		<textarea id="vp-js-export_text" onclick="this.focus();this.select()" readonly="readonly"></textarea>
		<div class="buttons">
			<input id="vp-js-export" class="vp-button" type="button" value="<?php _e('Export', VP_TEXTDOMAIN) ?>" />
			<span style="margin-left: 10px;">
				<span id="vp-js-export-loader" class="hidden"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" style="position:absolute; vertical-align: middle;"></span>
				<span id="vp-js-export-status" class="hidden"></span>
			</span>
		</div>
	</td>
</tr>
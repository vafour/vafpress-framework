<?php echo VP_View::get_instance()->load('control/template_control_head', $head_info); ?>

<input type="text" name="<?php echo $name; ?>" class="slideinput vp-js-tipsy" original-title="Range between <?php echo $opt_raw['min']; ?> and <?php echo $opt_raw['max']; ?>" value="<?php echo $value; ?>" />
<div class="vp-js-slider slidebar" id="<?php echo $name; ?>" data-vp-opt="<?php echo $opt; ?>"></div>

<?php echo VP_View::get_instance()->load('control/template_control_foot'); ?>
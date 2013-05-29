<?php

/**
 * The App main file, this file will be loaded by vafpress bootstrap file
 * call your hooks here, at this point extensions also been loaded.
 */

add_action( 'admin_footer', 'vp_debug' );

function vp_debug()
{
	VP_Util_Profiler::show_memtime();
}

/**
 * EOF
 */
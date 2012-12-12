<?php

class VP_Util_Profiler
{

	public static function show_memtime()
	{
		$time_elapsed = microtime(true) - VP_START_TIME;
		$mem_usage    = memory_get_peak_usage() - VP_START_MEM;
		$time_elapsed = round($bm[0], 4);
		$mem_usage    = round($bm[1] / pow(1024, 2), 3);
		echo "Time Elapsed: " . $time_elapsed . ' s';
		echo "Mem Usage   : " . $mem_usage . ' mb';
	}

}
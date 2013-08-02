<?php

class VP_Util_Profiler
{

	public static function show_memtime()
	{
		$time_elapsed = microtime(true) - VP_START_TIME;
		$mem_usage    = memory_get_peak_usage() - VP_START_MEM;
		$time_elapsed = round($time_elapsed, 4);
		$mem_usage    = round($mem_usage / pow(1024, 2), 3);
		echo "Time Elapsed: " . $time_elapsed . ' s' . "<br/>";
		echo "Mem Usage   : " . $mem_usage . ' mb' . "<br/>";
	}

}
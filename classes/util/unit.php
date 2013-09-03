<?php

class VP_Util_Unit {


	/**
	 * Give default measurement unit
	 * @param  string $value the value
	 * @param  string $unit  default measurement unit
	 * @return string        the correct value
	 */
	public static function grant_default_unit($value, $unit) {
		if (is_numeric($value)) {
			return $value . $unit;
		}
		return $value;
	}

	/**
	 * Calculate Percentage from given Value and Range
	 * @param  float $min    minimal
	 * @param  float $max    maximum
	 * @param  float $value  value
	 * @param  integer $ndec number of decimal allowed
	 * @return string        the percentage
	 */
	public static function calculate_percentage($min, $max, $value, $ndec = 0) {
		return round(($value - $min) / ($max - $min) * 100, $ndec) . '%';
	}
}
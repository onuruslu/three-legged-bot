<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Utils;

class CommonUtils{
	public static function array_multi_key_exists($arrNeedles, $arrHaystack)
	{
		$needle = array_shift($arrNeedles);

		if(count($arrNeedles) == 0) {
			return(array_key_exists($needle, $arrHaystack));
		}
		else {
			if(! array_key_exists($needle, $arrHaystack)) {
				return false;
			} else {
				return self::array_multi_key_exists($arrNeedles, $arrHaystack[$needle]);
			}
		}
	}
}

?>
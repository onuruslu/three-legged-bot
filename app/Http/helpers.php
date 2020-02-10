<?php

if (! function_exists('getFullUrl')) {
    function getFullUrl($path, $currentUrl = null) {
    	if( !$currentUrl )
    		return $path;

    	// if it's already a full url, return
    	if(mb_strstr($path, '://') !== false)
    		return $path;

    	// convert "index.php?id=1234" to "http://comp.eng.ankara.edu.tr/index.php?id=1234"
    	return $currentUrl . '/' . $path;
    }
}
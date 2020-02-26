<?php

if(! function_exists('studly_case')){
	// backwward compability for irazasyed telegram bot
	function studly_case($str){
		return Str::studly($str);
	}
}
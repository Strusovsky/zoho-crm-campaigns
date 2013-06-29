<?php

define('BASEPATH', (string) (__DIR__ . '/'));

function autoload($class)
{
	
	
	$path  = (string) get_include_path();
	$path .= (string) (PATH_SEPARATOR . BASEPATH . 'classes/');
	
	set_include_path($path);

	include_once($class . '.php');
}

spl_autoload_register('autoload');
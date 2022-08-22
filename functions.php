<?php
define('LIB_DIR', __DIR__ . '/lib/');

spl_autoload_register(function($class) {
	
	$a = explode('\\', $class);
	$last = array_pop($a);
	$fn = $class . '/'. $last . '.php';
	$fn = LIB_DIR . str_replace('\\', '/', $fn);
	
	if (file_exists($fn)) require $fn; 
});

if(!function_exists('base_site_url')) {
	function base_site_url() {
		$url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';
		$url = $url . $_SERVER['SERVER_NAME'];
		return $url;
	}
}

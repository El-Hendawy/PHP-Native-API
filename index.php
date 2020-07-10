<?php
use Views\Home;

//Check if Json PHP Extension Avilable

if (!function_exists('json_decode')) {

	throw new Exception('JSON PHP extension is required');

}

//Check if simplexml_load_string PHP Extension Avilable

if (!function_exists('simplexml_load_string')) {

	throw new Exception('simplexml PHP extension is required');

}

//Load the auto loader file

require_once(dirname(__FILE__) . '/Core/auto_loader.php');

//Load the home view file
new Home();
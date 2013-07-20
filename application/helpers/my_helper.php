<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function db_current_date(){
	return date("Y-m-d H:i:s");
}

function get_app_key(){
	$keys = Array("171680182893568");
	return $keys[0];
}
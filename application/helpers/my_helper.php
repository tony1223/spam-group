<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function db_current_date(){
	return date("Y-m-d H:i:s");
}

function get_app_key(){
	$keys = Array("339479699496192","339103652860490","135395566627129");
	return $keys[time() % 3];
}
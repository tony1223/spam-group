<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function db_current_date(){
	return date("Y-m-d H:i:s");
}

function get_app_key(){
	$keys = Array("171680182893568");
	return $keys[0];
}


function doPagination($total_rows,$pageSize= 20,$segment = 3,$base_url){
	$config = Array();
	$config['uri_segment'] = $segment;
	$config['base_url'] = $base_url;
	//取得總數量
	$config['total_rows'] = $total_rows;
	$config['cur_tag_open'] = '<li class="disabled"><a href="javascript:void 0;">';
	$config['use_page_numbers'] = TRUE;
	$config['num_links'] = 5;
	$config['cur_tag_close'] = '</a></li>';
	$config['full_tag_open'] = '<div class="pagination"><ul>';
	$config['full_tag_close'] = '</ul></div>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	$config['next_link'] = '下一頁';
	$config['prev_link'] = '上一頁';
	$config['first_link'] = "第一頁";
	$config['last_link'] = "最後一頁";
	$config['per_page'] = $pageSize;

	$gets = http_build_query($_GET, '', "&");
	if(!empty($gets)){
		$config['suffix'] = '?'.$gets;
	}
	return $config;
}


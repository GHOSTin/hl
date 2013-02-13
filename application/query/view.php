<?php
class view_query{


	public static function private_show_default_page($args){
		$time = getdate($_SESSION['filters']['query']['time_interval']['begin']);
		$args['timeline']['days'] = date('t', $time[0]);
		$args['timeline']['month'] = $time['month'];
		$args['timeline']['first_day'] = mktime(12, 0, 0, $time['mon'], 1, $time['year']);
		return load_template('query.private_show_default_page', $args);
	}

	public static function private_get_query_content($args){
		return load_template('query.private_get_query_content', ['query' => $args]);
	}	

	public static function private_get_query_title($args){
		return load_template('query.private_get_query_title', ['query' => $args]);
	}		
}
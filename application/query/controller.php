<?php
class controller_query{

	public static function private_get_day(){
		$time = getdate($_GET['time']);
		$args['time_interval']['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$args['time_interval']['end'] = $args['time_interval']['begin'] + 86399;
		$queries = model_query::get_queries($args);
		$args['queries'] = $queries;
		return view_query::private_get_day($args);
	}	

	public static function private_get_query_content(){
		$args = ['query_id' => $_GET['id']];
		$query = model_query::get_query($args);
		return view_query::private_get_query_content($query);
	}

	public static function private_get_query_title(){
		$args = ['query_id' => $_GET['id']];
		$query = model_query::get_query($args);
		return view_query::private_get_query_title($query);
	}	
	public static function private_get_search(){
		return view_query::private_get_search();
	}
	public static function private_show_default_page(){
		$queries = model_query::get_queries($_SESSION['filters']['query']);
		$args['queries'] = $queries;
		return view_query::private_show_default_page($args);
	}
}
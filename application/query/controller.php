<?php
class controller_query{
	public static function private_clear_filters(){
		$time = getdate();
		$args['time_interval']['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$args['time_interval']['end'] = $args['time_interval']['begin'] + 86399;
		$args['statuses'] = [];
		$queries = model_query::get_queries($args);
		$args['queries'] = $queries;
		return $args;
	}		
	public static function private_get_day(){
		$time = getdate($_GET['time']);
		$args['time_interval']['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$args['time_interval']['end'] = $args['time_interval']['begin'] + 86399;
		$queries = model_query::get_queries($args);
		$args['queries'] = $queries;
		return $args;
	}	
	public static function private_get_query_content(){
		$args = ['query_id' => $_GET['id']];
		$query = model_query::get_query($args);
		return $args;
	}
	public static function private_get_query_title(){
		$args = ['query_id' => $_GET['id']];
		$query = model_query::get_query($args);
		return $args;
	}
	public static function private_get_search(){
		return view_query::private_get_search();
	}
	public static function private_get_search_result(){
		$args['number'] = (int) $_GET['param'];
		$args['queries'] = ($args['number'] === 0)?
			false:
			model_query::get_queries($args);
		return $args;
	}
	public static function private_set_status(){
		$args['statuses'] = [(string) $_GET['value']];
		$args['queries'] = model_query::get_queries($args);
		return $args;
	}
	public static function private_show_default_page(){
		$queries = model_query::get_queries([]);
		$args['queries'] = $queries;
		$args['filters'] = $_SESSION['filters']['query'];
		return $args;
	}
}
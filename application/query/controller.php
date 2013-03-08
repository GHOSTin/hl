<?php
class controller_query{
	static $name = 'Заявки';
	static $rules = [];
	public static function private_clear_filters(){
		$time = getdate();
		$args['time_interval']['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$args['time_interval']['end'] = $args['time_interval']['begin'] + 86399;
		$args['statuses'] = [];
		$args['queries'] = model_query::get_queries($args);
		return $args;
	}		
	public static function private_get_day(){
		$time = getdate($_GET['time']);
		$args['time_interval']['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$args['time_interval']['end'] = $args['time_interval']['begin'] + 86399;
		$args['queries'] = model_query::get_queries($args);
		return $args;
	}	
	public static function private_get_dialog_create_query(){
		return true;
	}
	public static function private_get_dialog_initiator(){
		$args['streets'] = model_street::get_streets();
		$args['initiator'] = $_GET['value'];
		return $args;
	}
	public static function private_get_initiator(){
		$types = model_query_work_type::get_query_work_types();
		switch($_GET['initiator']){
			case 'number':
				$args['number_id'] = $_GET['id'];
				return ['initiator' => 'number',
						'number' => model_number::get_number($args),
						'query_work_types' => $types];
			break;
			case 'house':
				$args['house_id'] = $_GET['id'];
				return ['initiator' => 'house',
						'house' => model_house::get_house($args),
						'query_work_types' => $types];
			break;
			default:
				return ['initiator' => false];		
		}
	}		
	public static function private_get_houses(){
		$args['street_id'] = $_GET['id'];
		return model_street::get_houses($args);
	}	
	public static function private_get_numbers(){
		$args['house_id'] = $_GET['id'];
		return model_house::get_numbers($args);
	}		
	public static function private_get_query_content(){
		$args = ['query_id' => $_GET['id']];
		return model_query::get_query($args);
	}
	public static function private_get_query_title(){
		return model_query::get_query(['query_id' => $_GET['id']]);
	}
	public static function private_get_search(){
		return true;
	}
	public static function private_get_search_result(){
		$args['number'] = (int) $_GET['param'];
		if($args['number'] === 0)
			return ['queries' => false];
			return ['queries' => model_query::get_queries($args)];
	}
	public static function private_set_status(){
		$args['statuses'] = [(string) $_GET['value']];
		return ['queries' => model_query::get_queries($args)];
	}
	public static function private_show_default_page(){
		$queries = model_query::get_queries([]);
		$args['queries'] = $queries;
		$args['filters'] = $_SESSION['filters']['query'];
		$args['rules'] = $_SESSION['rules']['query'];
		return $args;
	}
}
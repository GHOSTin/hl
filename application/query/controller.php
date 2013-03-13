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
	public static function private_create_query(){
		if($_GET['initiator'] === 'number'){
			$initiator = new data_number();
		}elseif($_GET['house'] === 'house'){
			$initiator = new data_house();
		}
		$initiator->id = (int) $_GET['id'];
		$query = new data_query();
		$query->description = htmlspecialchars($_GET['description']);
		$query->contact_fio = htmlspecialchars($_GET['fio']);
		$query->contact_telephone = htmlspecialchars($_GET['telephone']);
		$query->contact_cellphone = htmlspecialchars($_GET['cellphone']);
		var_dump(model_query::create_query($query, $initiator, $_SESSION['user']));
		exit();
	}	
	public static function private_get_day(){
		$time = getdate($_GET['time']);
		$query = new data_query();
		$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		return ['queries' => model_query::get_queries($query)];
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
				$number = new data_number();
				$number->id = $_GET['id'];
				return ['initiator' => 'number',
						'number' => model_number::get_number($number),
						'query_work_types' => $types];
			break;
			case 'house':
				$house = new data_house();
				$house->id = $_GET['id'];
				return ['initiator' => 'house',
						'house' => model_house::get_house($house),
						'query_work_types' => $types];
			break;
			default:
				return ['initiator' => false];		
		}
	}		
	public static function private_get_houses(){
		$street = new data_street();
		$street->id = $_GET['id'];
		return ['houses' => model_street::get_houses($street)];
	}	
	public static function private_get_numbers(){
		$house = new data_house();
		$house->id = $_GET['id'];
		return ['numbers' => model_house::get_numbers($house)];
	}		
	public static function private_get_query_content(){
		$args = ['query_id' => $_GET['id']];
		return ['query' => model_query::get_query($args)];
	}
	public static function private_get_query_title(){
		return ['query' => model_query::get_query(['query_id' => $_GET['id']])];
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
		return ['queries' => model_query::get_queries(new data_query()),
			'filters' => $_SESSION['filters']['query']];
	}
}
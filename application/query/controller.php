<?php
class controller_query{
	static $name = 'Заявки';
	static $rules = [];
	public static function private_clear_filters(){
		$time = getdate();
		$query = new data_query();
		$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		return ['queries' => model_query::get_queries($query)];
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
		return ['streets' => model_street::get_streets(),
				'initiator' => $_GET['value']];
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
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_get_query_title(){
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_get_search(){
		return true;
	}
	public static function private_get_search_result(){
		$query = new data_query();
		$query->number = $_GET['param'];
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_set_status(){
		$query = new data_query();
		$query->status = $_GET['value'];
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_get_timeline(){
		$time = (int) $_GET['time'];
		$query = new data_query();
		if($time < 0)
			$time = getdate();
			$time = getdate($time);
			$time = mktime(0, 0, 0, $time['mon'], 1, $time['year']);
			switch ($_GET['act']) {
				case 'next':
					$query->time_open['begin'] = strtotime("+1 month", $time);
					$timeline = strtotime("+1 month +12 hours", $time);
				break;
				case 'previous':
					$query->time_open['begin'] = strtotime("-1 day", $time);
					$timeline = strtotime("-1 month +12 hours", $time);
				break;
				default:
					return false;
			}
			$query->time_open['end'] = $query->time_open['begin'] + 86399;
		return ['queries' => model_query::get_queries($query),
			'timeline' => $timeline];
	}
	public static function private_show_default_page(){
		$time = getdate($_SESSION['filters']['query']->time_open['begin']);
		return ['queries' => model_query::get_queries(new data_query()),
			'filters' => $_SESSION['filters']['query'],
			'timeline' =>  mktime(12, 0, 0, $time['mon'], 1, $time['year'])];
	}
}
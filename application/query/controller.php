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
		if($_GET['initiator'] === 'number')
			$initiator = new data_number();
		elseif($_GET['initiator'] === 'house')
			$initiator = new data_house();
		$initiator->id = (int) $_GET['id'];
		$query = new data_query();
		$query->description = htmlspecialchars($_GET['description']);
		$query->contact_fio = htmlspecialchars($_GET['fio']);
		$query->contact_telephone = htmlspecialchars($_GET['telephone']);
		$query->contact_cellphone = htmlspecialchars($_GET['cellphone']);
		$queries[] = model_query::create_query($query, $initiator, $_SESSION['user']);
		return ['queries' => $queries];
	}	
	public static function private_get_documents(){
		return ['query_id' => $_GET['id']];
	}
	public static function private_get_day(){
		$time = getdate($_GET['time']);
		$query = new data_query();
		$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		return ['queries' => model_query::get_queries($query),
			'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
	}	
	public static function private_get_dialog_create_query(){
		return true;
	}
	public static function private_get_dialog_edit_description(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new exception('Недостаточно параметров.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_get_dialog_edit_contact_information(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new exception('Недостаточно параметров.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_get_dialog_edit_payment_status(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('Проблема с идентификатором заявки.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_get_dialog_edit_warning_status(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('Проблема с идентификатором заявки.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query)];
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
	public static function private_print_query(){
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_get_query_content(){
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query),
			'users' => model_query::get_users($query, $_SESSION['user']),
			'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
	}
	public static function private_get_query_title(){
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query)];
	}
	public static function private_get_query_numbers(){
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query),
				'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
	}
	public static function private_get_query_users(){
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query),
				'users' => model_query::get_users($query, $_SESSION['user'])];
	}
	public static function private_get_query_works(){
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query),
				'works' => model_query::get_works($query, $_SESSION['user'])];
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
		else
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
		if($_SESSION['filters']['query'] instanceof data_query)
			$time = getdate($_SESSION['filters']['query']->time_open['begin']);
		else
			$time = getdate();
		$query = new data_query();
		return ['queries' => model_query::get_queries($query),
			'filters' => $_SESSION['filters']['query'],
			'timeline' =>  mktime(12, 0, 0, $time['mon'], 1, $time['year']),
			'streets' => model_street::get_streets(),
			'users' => model_user::get_users([]),
			'departments' => model_department::get_departments($_SESSION['user']),
			'numbers' => model_query::get_numbers($query, $_SESSION['user']),
			'query_work_types' => model_query_work_type::get_query_work_types()];
	}
	public static function private_update_description(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->description = $_GET['description'];
		return ['queries' => model_query::update_description($query, $_SESSION['user'])];
	}	
	public static function private_update_contact_information(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->contact_fio = $_GET['fio'];
		$query->contact_telephone = $_GET['telephone'];
		$query->contact_cellphone = $_GET['cellphone'];
		return ['queries' => model_query::update_contact_information($query, $_SESSION['user'])];
	}	
	public static function private_update_payment_status(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->payment_status = $_GET['status'];
		return ['queries' => model_query::update_payment_status($query, $_SESSION['user'])];
	}
	public static function private_update_warning_status(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->warning_status = $_GET['status'];
		return ['queries' => model_query::update_warning_status($query, $_SESSION['user'])];
	}	
}
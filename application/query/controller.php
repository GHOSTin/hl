<?php
/*
* Контроллер компонента заявок.
*/
class controller_query{
	
	static $name = 'Заявки';
	static $rules = [];

	public static function private_add_user(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$user = new data_user();
		$user->id = $_GET['user_id'];
		$class = $_GET['type'];
		return ['queries' => model_query::add_user($query, $user, $class, $_SESSION['user']),
				'users' => model_query::get_users($query, $_SESSION['user'])];
	}

	public static function private_add_work(){
		$begin_hours = (int) $_GET['begin_hours'];
		$begin_minutes = (int) $_GET['begin_minutes'];
		$begin_date = (string) $_GET['begin_date'];
		$end_hours = (int) $_GET['end_hours'];
		$end_minutes = (int) $_GET['end_minutes'];
		$end_date = (string) $_GET['end_date'];
		$begin_time = strtotime($begin_hours.':'.$begin_minutes.' '.$begin_date);
		$end_time = strtotime($end_hours.':'.$end_minutes.' '.$end_date);
		$query = new data_query();
		$query->id = $_GET['id'];
		$work = new data_work();
		$work->id = $_GET['work_id'];
		return ['queries' => model_query::add_work($query, $work, $begin_time, $end_time, $_SESSION['user']),
				'works' => model_query::get_works($query, $_SESSION['user'])];
	}

	public static function private_clear_filters(){
		$time = getdate();
		$query = new data_query();
		$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		$query->status = 'all';
		$query->street_id = 'all';
		$query->department_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], $_SESSION['restrictions']['query']);
		return ['queries' => model_query::get_queries($query),
				'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
	}

	public static function private_close_query(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->close_reason = $_GET['reason'];
		return ['queries' => model_query::close_query($query, $_SESSION['user']),
			'users' => model_query::get_users($query, $_SESSION['user']),
			'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
	}

	public static function private_to_working_query(){
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::to_working_query($query, $_SESSION['user']),
			'users' => model_query::get_users($query, $_SESSION['user']),
			'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
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
		$query = new data_query();
		$query->id = $_GET['id'];
		return ['queries' => model_query::get_queries($query, $_SESSION['user'])];
	}

	public static function private_get_day(){
		$time = getdate($_GET['time']);
		$query = new data_query();
		$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], $_SESSION['restrictions']['query']);
		return ['queries' => model_query::get_queries($query),
			'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
	}

	public static function private_get_dialog_add_user(){
		$id = (int) $_GET['id'];
		$type = (string) $_GET['type'];
		if(empty($id))
			throw new e_model('Проблема с идентификатором заявки.');
		if(array_search($type, ['manager', 'performer']) === false)
			throw new e_model('Проблема с типом.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query),
			'groups' => model_group::get_groups(new data_group(), $_SESSION['user']),
			'type' => $type];
	}

	public static function private_get_dialog_add_work(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('id заявки задан не верно.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query),
			'workgroups' => model_workgroup::get_workgroups(new data_workgroup(), $_SESSION['user'])];
	}	

	public static function private_get_dialog_create_query(){
		return true;
	}

	public static function private_get_dialog_close_query(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('id заявки зада не верно.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query)];
	}

	public static function private_get_dialog_to_working_query(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('id заявки зада не верно.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query)];
	}

	public static function private_get_dialog_edit_description(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('Недостаточно параметров.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query)];
	}

	public static function private_get_dialog_edit_contact_information(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('Недостаточно параметров.');
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

	public static function private_get_dialog_edit_work_type(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('Проблема с идентификатором заявки.');
		$query = new data_query();
		$query->id = $id;
		return ['queries' => model_query::get_queries($query),
			'work_types' => model_query_work_type::get_query_work_types(new data_query_work_type(), $_SESSION['user'])];
	}

	public static function private_get_dialog_initiator(){
		return ['streets' => model_street::get_streets(),
				'initiator' => $_GET['value']];
	}

	public static function private_get_dialog_remove_user(){
		$id = (int) $_GET['id'];
		$user_id = (int) $_GET['user_id'];
		$type = (string) $_GET['type'];
		if(empty($id))
			throw new e_model('id задан не верно.');
		if(empty($user_id))
			throw new e_model('user задан не верно.');
		if(array_search($type, ['manager', 'performer']) === false)
			throw new e_model('Проблема с типом.');
		$query = new data_query();
		$query->id = $id;
		$user = new data_user();
		$user->id = $user_id;
		return ['queries' => model_query::get_queries($query),
				'users' => model_user::get_users($user),
				'type' => $type];
	}	

	public static function private_get_dialog_remove_work(){
		$id = (int) $_GET['id'];
		$work_id = (int) $_GET['work_id'];
		if(empty($id))
			throw new e_model('id заявки задан не верно.');
		if(empty($work_id))
			throw new e_model('id работы задан не верно.');
				$query = new data_query();
		$query->id = $id;
		$work = new data_work();
		$work->id = $work_id;
		return ['queries' => model_query::get_queries($query),
				'works' => model_work::get_works($work, $_SESSION['user'])];
	}

	public static function private_get_initiator(){
		$types = model_query_work_type::get_query_work_types(new data_query_work_type(), $_SESSION['user']);
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
		return ['queries' => model_query::get_queries($query),
			'users' => model_query::get_users($query, $_SESSION['user']),
			'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
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
		return ['queries' => model_query::get_queries($query),
				'numbers' => model_query::get_numbers($query, $_SESSION['user'])];
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
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], $_SESSION['restrictions']['query']);
		return ['queries' => model_query::get_queries($query)];
	}

	public static function private_set_street(){
		$query = new data_query();
		$query->street_id = $_GET['value'];
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], $_SESSION['restrictions']['query']);
		return ['queries' => model_query::get_queries($query)];
	}

	public static function private_set_department(){
		$query = new data_query();
		$query->department_id = $_GET['value'];
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], $_SESSION['restrictions']['query']);
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
				$timeline = strtotime("-12 hours", $time);
			break;
			default:
				return false;
		}
		$now = getdate();
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], $_SESSION['restrictions']['query']);
		return ['queries' => model_query::get_queries($query),
			'numbers' => model_query::get_numbers($query, $_SESSION['user']),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'timeline' => $timeline];
	}

	public static function private_get_user_options(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('id группы задан не верно.');
		$group = new data_group();
		$group->id = $id;
		return ['users' => model_group::get_users($group, $_SESSION['user'])];
	}

	public static function private_get_work_options(){
		$id = (int) $_GET['id'];
		if(empty($id))
			throw new e_model('id группы задан не верно.');
		$work_group = new data_workgroup();
		$work_group->id = $id;
		return ['works' => model_workgroup::get_works($work_group, $_SESSION['user'])];
	}

	public static function private_show_default_page(){
		$query = new data_query();
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], $_SESSION['restrictions']['query']);
		$time = getdate($query->time_open['begin']);
		$now = getdate();
		return ['queries' => model_query::get_queries($query),
			'filters' => $_SESSION['filters']['query'],
			'timeline' =>  mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'streets' => model_street::get_streets(),
			'users' => model_user::get_users(new data_user()),
			'departments' => model_department::get_departments($_SESSION['user']),
			'numbers' => model_query::get_numbers($query, $_SESSION['user']),
			'query_work_types' => model_query_work_type::get_query_work_types(new data_query_work_type(), $_SESSION['user'])];
	}

	public static function private_remove_user(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$user = new data_user();
		$user->id = $_GET['user_id'];
		$type = $_GET['type'];
		return ['queries' => model_query::remove_user($query, $user, $type, $_SESSION['user']),
				'users' => model_query::get_users($query, $_SESSION['user'])];
	}

	public static function private_remove_work(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$work = new data_work();
		$work->id = $_GET['work_id'];
		return ['queries' => model_query::remove_work($query, $work, $_SESSION['user']),
				'works' => model_query::get_works($query, $_SESSION['user'])];
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

	public static function private_update_work_type(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->worktype_id = $_GET['type'];
		return ['queries' => model_query::update_work_type($query, $_SESSION['user'])];
	}
}
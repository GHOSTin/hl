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
		$company = model_session::get_company();
		return ['queries' => model_query::add_user($company, $query, $user, $class),
				'users' => model_query::get_users($company, $query)];
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
		$company = model_session::get_company();
		return ['queries' => model_query::add_work($company, $query, $work, $begin_time, $end_time),
				'works' => model_query::get_works($company, $query)];
	}

	public static function private_clear_filters(){
		$time = getdate();
		$query = new data_query();
		$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		$query->status = 'all';
		$query->street_id = 'all';
		$query->house_id = 'all';
		$query->department_id = 'all';
		$query->worktype_id = 'all';
		$time = getdate();
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query),
				'timeline' =>  mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']),
				'now' =>  mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year'])];
	}

	public static function private_close_query(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->close_reason = $_GET['reason'];
		$company = model_session::get_company();
		return ['queries' => model_query::close_query($company, $query),
				'users' => model_query::get_users($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_reclose_query(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$company = model_session::get_company();
		return ['queries' => model_query::reclose_query($company, $query),
				'users' => model_query::get_users($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_reopen_query(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$company = model_session::get_company();
		return ['queries' => model_query::reopen_query($company, $query),
				'users' => model_query::get_users($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_to_working_query(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$company = model_session::get_company();
		return ['queries' => model_query::to_working_query($company, $query),
			'users' => model_query::get_users($company, $query),
			'numbers' => model_query::get_numbers($company, $query)];
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
		$query_work_type = new data_query_work_type();
		$query_work_type->id = $_GET['work_type'];
		$queries[] = model_query::create_query(model_session::get_company(), $query, $initiator,
								 $query_work_type, model_session::get_user());
		return ['queries' => $queries];
	}

	public static function private_get_documents(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_day(){
		$time = getdate($_GET['time']);
		$query = new data_query();
		$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
			'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_get_dialog_add_user(){
		$type = (string) $_GET['type'];
		if(array_search($type, ['manager', 'performer']) === false)
			throw new e_model('Проблема с типом.');
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->verify('id');
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
			'groups' => model_group::get_groups($company, new data_group()),
			'type' => $type];
	}

	public static function private_get_dialog_add_work(){
		$id = (int) $_GET['id'];
		$query = new data_query();
		$query->id = $id;
		$query->verify('id');
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
			'workgroups' => model_workgroup::get_workgroups($company, new data_workgroup())];
	}	

	public static function private_get_dialog_create_query(){
		return true;
	}

	public static function private_get_dialog_close_query(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_reclose_query(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_reopen_query(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_to_working_query(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_description(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_reason(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_contact_information(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_payment_status(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_warning_status(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_work_type(){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($_GET['id']);
		return ['query' => $query,
				'work_types' => model_query_work_type::get_query_work_types($company, new data_query_work_type())];
	}

	public static function private_get_dialog_initiator(){
		$query = new data_query();
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->department_id = $query->department_id;
		$company = model_session::get_company();
		return ['streets' => model_street::get_streets($street),
				'initiator' => $_GET['value']];
	}

	public static function private_get_dialog_remove_user(){
		$user = new data_user();
		$user->id = $_GET['user_id'];
		$user->verify('id');
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($_GET['id']);
		return ['query' => $query,
				'user' => model_user::get_users($user)[0],
				'type' => $_GET['type']];
	}	

	public static function private_get_dialog_remove_work(){
		$work = new data_work();
		$work->id = $_GET['work_id'];
		$work->verify('id');
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($_GET['id']);
		return ['query' => $query,
				'work' => model_work::get_works($company, $work)[0]];
	}

	public static function private_get_initiator(){
		$company = model_session::get_company();
		$types = model_query_work_type::get_query_work_types($company, new data_query_work_type());
		switch($_GET['initiator']){
			case 'number':
				$model = new model_number($company);
				return ['initiator' => 'number',
						'number' => $model->get_number($_GET['id']),
						'query_work_types' => $types];
			break;
			case 'house':
				$house = new data_house();
				$house->id = $_GET['id'];
				$house->verify('id');
				return ['initiator' => 'house',
						'house' => model_house::get_houses($house)[0],
						'query_work_types' => $types];
			break;
			default:
				return ['initiator' => false];		
		}
	}

	public static function private_get_houses(){
		$query = new data_query();
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->id = $_GET['id'];
		$street->department_id = $query->department_id;
		$company = model_session::get_company();
		return ['houses' => model_street::get_houses($street)];
	}

	public static function private_get_numbers(){
		$house = new data_house();
		$house->id = $_GET['id'];
		$house->verify('id');
		return ['numbers' => model_house::get_numbers(model_session::get_company(), $house)];
	}

	public static function private_print_query(){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($_GET['id']);
		$model->init_numbers($query);
		return ['query' => $query, 
				'users' => model_query::get_users($company, $query)];
	}

	public static function private_get_query_content(){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($_GET['id']);
		$model->init_numbers($query);
		return ['query' => $query, 
				'users' => model_query::get_users($company, $query)];
	}

	public static function private_get_query_title(){
		$company = model_session::get_company();
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		$model->init_numbers($query);
		return ['query' => $query,
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_get_query_numbers(){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		$model->init_numbers($query);
		return ['query' => $query];
	}

	public static function private_get_query_users(){
		$company = model_session::get_company();
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query,
				'users' => model_query::get_users($company, $query)];
	}

	public static function private_get_query_works(){
		$company = model_session::get_company();
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query,
				'works' => model_query::get_works($company, $query)];
	}

	public static function private_get_search(){
		return true;
	}

	public static function private_get_search_result(){
		$query = new data_query();
		$query->number = $_GET['param'];
		$query->verify('number');
		return ['queries' => model_query::get_queries(model_session::get_company(), $query)];
	}

	public static function private_set_status(){
		$query = new data_query();
		$query->status = $_GET['value'];
		$query->department_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_set_street(){
		$query = new data_query();
		$query->street_id = $_GET['value'];
		$query->department_id = 'all';
		$query->house_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->id = $_GET['value'];
		$street->department_id = $query->department_id;
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query),
				'houses' => model_street::get_houses($street)];
	}

	public static function private_set_house(){
		$query = new data_query();
		$query->house_id = $_GET['value'];
		$query->department_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company , $query),
				'numbers' => model_query::get_numbers($company , $query)];
	}

	public static function private_set_department(){
		$query = new data_query();
		$query->department_id = $_GET['value'];
		$query->street_id = 'all';
		$query->house_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_set_work_type(){
		$query = new data_query();
		$query->worktype_id = $_GET['value'];
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
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
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
			'numbers' => model_query::get_numbers($company, $query),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'timeline' => $timeline];
	}

	public static function private_get_user_options(){
		$group = new data_group();
		$group->id = $_GET['id'];
		$group->verify('id');
		return ['users' => model_group::get_users(model_session::get_company(), $group)];
	}

	public static function private_get_work_options(){
		$work_group = new data_workgroup();
		$work_group->id = $_GET['id'];
		$work_group->verify('id');
		return ['works' => model_workgroup::get_works(model_session::get_company(), $work_group)];
	}

	public static function private_show_default_page(){
		$query = new data_query();
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query,
				$_SESSION['filters']['query'], model_session::get_restrictions()['query']); 
		$time = getdate($query->time_open['begin']);
		$now = getdate();
		$street = new data_street();
		$street->department_id = $query->department_id;
		$houses = [];
		if(!empty($query->street_id)){
			$street->id = $query->street_id;
			$houses = model_street::get_houses($street);
		}
		$department = new data_department();
		$department->id = model_session::get_restrictions()['query']->departments;
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
			'filters' => $_SESSION['filters']['query'],
			'timeline' =>  mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'streets' => model_street::get_streets($street),
			'users' => model_user::get_users(new data_user()),
			'departments' => model_department::get_departments($company, $department),
			'numbers' => model_query::get_numbers($company, $query),
			'query_work_types' => model_query_work_type::get_query_work_types($company, new data_query_work_type()),
			'houses' => $houses];
	}

	public static function private_remove_user(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$user = new data_user();
		$user->id = $_GET['user_id'];
		$type = $_GET['type'];
		$company = model_session::get_company();
		return ['queries' => model_query::remove_user($company, $query, $user, $type),
				'users' => model_query::get_users($company, $query)];
	}

	public static function private_remove_work(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$work = new data_work();
		$work->id = $_GET['work_id'];
		$company = model_session::get_company();
		return ['queries' => model_query::remove_work($company, $query, $work),
				'works' => model_query::get_works($company, $query)];
	}

	public static function private_update_description(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->description = $_GET['description'];
		return ['queries' => model_query::update_description(model_session::get_company(), $query)];
	}

	public static function private_update_reason(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->close_reason = $_GET['reason'];
		return ['queries' => model_query::update_reason(model_session::get_company(), $query)];
	}

	public static function private_update_contact_information(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->contact_fio = $_GET['fio'];
		$query->contact_telephone = $_GET['telephone'];
		$query->contact_cellphone = $_GET['cellphone'];
		return ['queries' => model_query::update_contact_information(model_session::get_company(), $query)];
	}

	public static function private_update_payment_status(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->payment_status = $_GET['status'];
		return ['queries' => model_query::update_payment_status(model_session::get_company(), $query)];
	}

	public static function private_update_warning_status(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->warning_status = $_GET['status'];
		return ['queries' => model_query::update_warning_status(model_session::get_company(), $query)];
	}

	public static function private_update_work_type(){
		$query = new data_query();
		$query->id = $_GET['id'];
		$query->worktype_id = $_GET['type'];
		return ['queries' => model_query::update_work_type(model_session::get_company(), $query)];
	}
}
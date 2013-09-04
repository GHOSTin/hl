<?php
/*
* Контроллер компонента заявок.
*/
class controller_query{
	
	static $name = 'Заявки';
	static $rules = [];

	public static function private_add_user(){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->add_user($_GET['id'], $_GET['user_id'], $_GET['type']);
		return ['query' => $query,
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
		$company = model_session::get_company();
		$query = (new model_query($company))->add_work($_GET['id'],
							$_GET['work_id'], $begin_time, $end_time);
		return ['query' => $query,
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
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->close_query($_GET['id'], $_GET['reason']);
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_change_initiator(){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->change_initiator($_GET['query_id'], $_GET['house_id'], $_GET['number_id']);
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_reclose_query(){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->reclose_query($_GET['id']);
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_reopen_query(){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->reopen_query($_GET['id']);
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_to_working_query(){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->to_working_query($_GET['id']);
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_create_query(){
		$model = new model_query(model_session::get_company());
		$model->create_query($_GET['initiator'], $_GET['id'], $_GET['description'],
												$_GET['work_type'], $_GET['fio'], $_GET['telephone'],
												$_GET['cellphone']);
		return ['queries' => [$query]];
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
		$company = model_session::get_company();
		return ['query' => (new model_query($company))->get_query($_GET['id']),
			'groups' => model_group::get_groups($company, new data_group()),
			'type' => $_GET['type']];
	}

	public static function private_get_dialog_add_work(){
		$company = model_session::get_company();
		return ['query' => (new model_query($company))->get_query($_GET['id']),
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

	public static function private_get_dialog_change_initiator(){
		$_SESSION['filters']['query'] = $query = model_query::build_query_params(new data_query(), $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->department_id = $query->department_id;
		$company = model_session::get_company();
		$streets = model_street::get_streets($street);
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($_GET['id']);
		return ['query' => $query, 'streets' => $streets];
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
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->remove_user($_GET['id'], $_GET['user_id'], $_GET['type']);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_remove_work(){
		$company = model_session::get_company();
		$query = (new model_query($company))->remove_work($_GET['id'], $_GET['work_id']);
		return ['query' => $query,
						'works' => model_query::get_works($company, $query)];
	}

	public static function private_update_description(){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_description($_GET['id'], $_GET['description'])];
	}

	public static function private_update_reason(){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_reason($_GET['id'], $_GET['reason'])];
	}

	public static function private_update_contact_information(){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_contact_information($_GET['id'],
			 			$_GET['fio'], $_GET['telephone'], $_GET['cellphone'])];
	}

	public static function private_update_payment_status(){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_payment_status($_GET['id'], $_GET['status'])];
	}

	public static function private_update_warning_status(){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_warning_status($_GET['id'], $_GET['status'])];
	}

	public static function private_update_work_type(){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_work_type($_GET['id'], $_GET['type'])];
	}
}
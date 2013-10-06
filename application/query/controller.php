<?php
/*
* Контроллер компонента заявок.
*/
class controller_query{
	
	static $name = 'Заявки';
	static $rules = [];

	public static function private_add_user(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->add_user($request->GET('id'), $request->GET('user_id'), $request->GET('type'));
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_add_work(model_request $request){
		$begin_hours = (int) $request->GET('begin_hours');
		$begin_minutes = (int) $request->GET('begin_minutes');
		$begin_date = (string) $request->GET('begin_date');
		$end_hours = (int) $request->GET('end_hours');
		$end_minutes = (int) $request->GET('end_minutes');
		$end_date = (string) $request->GET('end_date');
		$begin_time = strtotime($begin_hours.':'.$begin_minutes.' '.$begin_date);
		$end_time = strtotime($end_hours.':'.$end_minutes.' '.$end_date);
		$company = model_session::get_company();
		$query = (new model_query($company))->add_work($request->GET('id'),
							$request->GET('work_id'), $begin_time, $end_time);
		return ['query' => $query,
						'works' => model_query::get_works($company, $query)];
	}

	public static function private_clear_filters(model_request $request){
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

	public static function private_close_query(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->close_query($request->GET('id'), $request->GET('reason'));
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_change_initiator(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->change_initiator($request->GET('query_id'), $request->GET('house_id'), $request->GET('number_id'));
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_reclose_query(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->reclose_query($request->GET('id'));
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_reopen_query(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->reopen_query($request->GET('id'));
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_to_working_query(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->to_working_query($request->GET('id'));
		$model->init_numbers($query);
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_create_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->create_query($request->GET('initiator'), $request->GET('id'), $request->GET('description'),
												$request->GET('work_type'), $request->GET('fio'), $request->GET('telephone'),
												$request->GET('cellphone'));
		return ['queries' => [$query]];
	}

	public static function private_get_documents(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_day(model_request $request){
		$time = getdate($request->GET('time'));
		$query = new data_query();
		$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$query->time_open['end'] = $query->time_open['begin'] + 86399;
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
			'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_get_dialog_add_user(model_request $request){
		$company = model_session::get_company();
		return ['query' => (new model_query($company))->get_query($request->GET('id')),
			'groups' => model_group::get_groups($company, new data_group()),
			'type' => $request->GET('type')];
	}

	public static function private_get_dialog_add_work(model_request $request){
		$company = model_session::get_company();
		return ['query' => (new model_query($company))->get_query($request->GET('id')),
						'workgroups' => model_workgroup::get_workgroups($company, new data_workgroup())];
	}	

	public static function private_get_dialog_create_query(model_request $request){
		return true;
	}

	public static function private_get_dialog_close_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_reclose_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_reopen_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_to_working_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_change_initiator(model_request $request){
		$_SESSION['filters']['query'] = $query = model_query::build_query_params(new data_query(), $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->department_id = $query->department_id;
		$company = model_session::get_company();
		$streets = model_street::get_streets($street);
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query, 'streets' => $streets];
	}

	public static function private_get_dialog_edit_description(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_reason(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_contact_information(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_payment_status(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_warning_status(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query];
	}

	public static function private_get_dialog_edit_work_type(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query,
				'work_types' => model_query_work_type::get_query_work_types($company, new data_query_work_type())];
	}

	public static function private_get_dialog_initiator(model_request $request){
		$query = new data_query();
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->department_id = $query->department_id;
		$company = model_session::get_company();
		return ['streets' => model_street::get_streets($street),
				'initiator' => $request->GET('value')];
	}

	public static function private_get_dialog_remove_user(model_request $request){
		$user = new data_user();
		$user->id = $request->GET('user_id');
		$user->verify('id');
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query,
				'user' => model_user::get_users($user)[0],
				'type' => $request->GET('type')];
	}	

	public static function private_get_dialog_remove_work(model_request $request){
		$work = new data_work();
		$work->id = $request->GET('work_id');
		$work->verify('id');
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($request->GET('id'));
		return ['query' => $query,
				'work' => model_work::get_works($company, $work)[0]];
	}

	public static function private_get_initiator(model_request $request){
		$company = model_session::get_company();
		$types = model_query_work_type::get_query_work_types($company, new data_query_work_type());
		switch($request->GET('initiator')){
			case 'number':
				$model = new model_number($company);
				return ['initiator' => 'number',
						'number' => $model->get_number($request->GET('id')),
						'query_work_types' => $types];
			break;
			case 'house':
				$house = new data_house();
				$house->id = $request->GET('id');
				$house->verify('id');
				return ['initiator' => 'house',
						'house' => model_house::get_houses($house)[0],
						'query_work_types' => $types];
			break;
			default:
				return ['initiator' => false];		
		}
	}

	public static function private_get_houses(model_request $request){
		$query = new data_query();
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->id = $request->GET('id');
		$street->department_id = $query->department_id;
		$company = model_session::get_company();
		return ['houses' => model_street::get_houses($street)];
	}

	public static function private_get_numbers(model_request $request){
		$house = new data_house();
		$house->id = $request->GET('id');
		$house->verify('id');
		return ['numbers' => model_house::get_numbers(model_session::get_company(), $house)];
	}

	public static function private_print_query(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($request->GET('id'));
		$model->init_numbers($query);
		return ['query' => $query, 
				'users' => model_query::get_users($company, $query)];
	}

	public static function private_get_query_content(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($request->GET('id'));
		$model->init_numbers($query);
		$model->init_users($query);
		return ['query' => $query];
	}

	public static function private_get_query_title(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		$model->init_numbers($query);
		return ['query' => $query];
	}

	public static function private_get_query_numbers(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		$model->init_numbers($query);
		return ['query' => $query];
	}

	public static function private_get_query_users(model_request $request){
		$company = model_session::get_company();
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		$model->init_users($query);
		return ['query' => $query];
	}

	public static function private_get_query_works(model_request $request){
		$company = model_session::get_company();
		$model = new model_query(model_session::get_company());
		$query = $model->get_query($request->GET('id'));
		$model->init_works($query);
		return ['query' => $query];
	}

	public static function private_get_search(model_request $request){
		return true;
	}

	public static function private_get_search_result(model_request $request){
		return ['queries' => (new model_query(model_session::get_company()))
			->get_queries_by_number($request->GET('param'))];
	}

	public static function private_set_status(model_request $request){
		$query = new data_query();
		$query->status = $request->GET('value');
		$query->department_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_set_street(model_request $request){
		$query = new data_query();
		$query->street_id = $request->GET('value');
		$query->department_id = 'all';
		$query->house_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->id = $request->GET('value');
		$street->department_id = $query->department_id;
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query),
				'houses' => model_street::get_houses($street)];
	}

	public static function private_set_house(model_request $request){
		$query = new data_query();
		$query->house_id = $request->GET('value');
		$query->department_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company , $query),
				'numbers' => model_query::get_numbers($company , $query)];
	}

	public static function private_set_department(model_request $request){
		$query = new data_query();
		$query->department_id = $request->GET('value');
		$query->street_id = 'all';
		$query->house_id = 'all';
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_set_work_type(model_request $request){
		$query = new data_query();
		$query->worktype_id = $request->GET('value');
		$_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
				'numbers' => model_query::get_numbers($company, $query)];
	}

	public static function private_get_timeline(model_request $request){
		$time = (int) $request->GET('time');
		$query = new data_query();
		if($time < 0)
			$time = getdate();
		else
			$time = getdate($time);

		$time = mktime(0, 0, 0, $time['mon'], 1, $time['year']);
		switch ($request->GET('act')) {
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

	public static function private_get_user_options(model_request $request){
		$group = new data_group();
		$group->id = $request->GET('id');
		$group->verify('id');
		return ['users' => model_group::get_users(model_session::get_company(), $group)];
	}

	public static function private_get_work_options(model_request $request){
		$work_group = new data_workgroup();
		$work_group->id = $request->GET('id');
		$work_group->verify('id');
		return ['works' => model_workgroup::get_works(model_session::get_company(), $work_group)];
	}

	public static function private_show_default_page(model_request $request){
		$query = new data_query();
		// $_SESSION['filters']['query'] = $query = model_query::build_query_params($query,
		// 		$_SESSION['filters']['query']); 
		$time = getdate($query->get_time_open()['begin']);
		// exit();
		$now = getdate();
		// $street = new data_street();
		// $street->set_department_id($query->get_department_id());
		$houses = [];
		// if(!empty($query->get_street_id())){
		// 	$street->id = $query->street_id;
		// 	$houses = model_street::get_houses($street);
		// }
		// $department = new data_department();
		// $department->setid = model_session::get_restrictions()['query']->departments;
		$company = model_session::get_company();
		return ['queries' => model_query::get_queries($company, $query),
			'filters' => $_SESSION['filters']['query'],
			'timeline' =>  mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'streets' => model_street::get_streets($street),
			// 'users' => (new model_user)->get_users(),
			// 'departments' => model_department::get_departments($company, $department),
			// 'numbers' => model_query::get_numbers($company, $query),
			// 'query_work_types' => model_query_work_type::get_query_work_types($company, new data_query_work_type()),
			'houses' => $houses];
	}

	public static function private_remove_user(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->remove_user($request->GET('id'), $request->GET('user_id'), $request->GET('type'));
		return ['query' => $query,
						'users' => model_query::get_users($company, $query)];
	}

	public static function private_remove_work(model_request $request){
		$company = model_session::get_company();
		$query = (new model_query($company))->remove_work($request->GET('id'), $request->GET('work_id'));
		return ['query' => $query,
						'works' => model_query::get_works($company, $query)];
	}

	public static function private_update_description(model_request $request){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_description($request->GET('id'), $request->GET('description'))];
	}

	public static function private_update_reason(model_request $request){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_reason($request->GET('id'), $request->GET('reason'))];
	}

	public static function private_update_contact_information(model_request $request){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_contact_information($request->GET('id'),
			 			$request->GET('fio'), $request->GET('telephone'), $request->GET('cellphone'))];
	}

	public static function private_update_payment_status(model_request $request){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_payment_status($request->GET('id'), $request->GET('status'))];
	}

	public static function private_update_warning_status(model_request $request){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_warning_status($request->GET('id'), $request->GET('status'))];
	}

	public static function private_update_work_type(model_request $request){
		$model = new model_query(model_session::get_company());
		return ['query' => $model->update_work_type($request->GET('id'), $request->GET('type'))];
	}
}
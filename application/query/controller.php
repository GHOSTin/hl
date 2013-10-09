<?php
/*
* Контроллер компонента заявок.
*/
class controller_query{
	
	static $name = 'Заявки';
	static $rules = [];

	public static function private_add_user(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->add_user($request->GET('id'), $request->GET('user_id'),
			$request->GET('type'))];
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
		return ['query' => $query];
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
		$model = new model_query(model_session::get_company());
		$query = $model->close_query($request->GET('id'), $request->GET('reason'));
		$model->init_numbers($query);
		$model->init_users($query);
		return ['query' => $query];
	}

	public static function private_change_initiator(model_request $request){
		$query = (new model_query(model_session::get_company()))
			->change_initiator($request->GET('query_id'),
			$request->GET('house_id'), $request->GET('number_id'));
		return ['query' => $query];
	}

	public static function private_reclose_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->reclose_query($request->GET('id'));
		$model->init_numbers($query);
		$model->init_users($query);
		return ['query' => $query];
	}

	public static function private_reopen_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->reopen_query($request->GET('id'));
		$model->init_numbers($query);
		$model->init_users($query);
		return ['query' => $query];
	}

	public static function private_to_working_query(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->to_working_query($request->GET('id'));
		$model->init_numbers($query);
		$model->init_users($query);
		return ['query' => $query];
	}

	public static function private_create_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->create_query($request->GET('initiator'), $request->GET('id'), $request->GET('description'),
												$request->GET('work_type'), $request->GET('fio'), $request->GET('telephone'),
												$request->GET('cellphone'));
		return ['queries' => [$query]];
	}

	public static function private_get_documents(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_day(model_request $request){
		$time = getdate($request->GET('time'));
		$begin = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$model = new model_query(model_session::get_company());
		$model->set_param('time_open_begin', $begin);
		$model->set_param('time_open_end', $begin + 86399);
		return ['queries' => $model->get_queries()];
	}

	public static function private_get_dialog_add_user(model_request $request){
		$company = model_session::get_company();
		return ['query' => (new model_query($company))
			->get_query($request->GET('id')),
			'groups' => (new model_group($company))->get_groups()];
	}

	public static function private_get_dialog_add_work(model_request $request){
		return ['workgroups' => (new model_workgroup(model_session::get_company()))
			->get_workgroups()];
	}	

	public static function private_get_dialog_create_query(model_request $request){
		return true;
	}

	public static function private_get_dialog_close_query(model_request $request){
		return true;
	}

	public static function private_get_dialog_reclose_query(model_request $request){
		return true;
	}

	public static function private_get_dialog_reopen_query(model_request $request){
		return true;
	}

	public static function private_get_dialog_to_working_query(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_change_initiator(model_request $request){
		// $_SESSION['filters']['query'] = $query = model_query::build_query_params(new data_query(), $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id')),
			'streets' => (new model_street)->get_streets()];
	}

	public static function private_get_dialog_edit_description(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_reason(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_contact_information(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_payment_status(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_warning_status(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_work_type(model_request $request){
		$company = model_session::get_company();
		return ['query' => (new model_query($company))->get_query($request->GET('id')),
				'work_types' => (new model_query_work_type($company))->get_query_work_types()];
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
		return ['user' => (new model_user)->get_user($request->GET('user_id'))];
	}	

	public static function private_get_dialog_remove_work(model_request $request){
		return ['work' => (new model_work(model_session::get_company()))
			->get_work($request->GET('work_id'))];
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
		// $_SESSION['filters']['query'] = $query = model_query::build_query_params($query, $_SESSION['filters']['query'], model_session::get_restrictions()['query']);
		$street = new data_street();
		$street->set_id($request->GET('id'));
		(new mapper_street2house($street))->init_houses();
		return ['street' => $street];
	}

	public static function private_get_numbers(model_request $request){
		$house = new data_house();
		$house->set_id($request->GET('id'));
		(new model_house2number(model_session::get_company(), $house))
			->init_numbers();
		return ['house' => $house];
	}

	public static function private_print_query(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->get_query($request->GET('id'));
		$model->init_numbers($query);
		$model->init_users($query);
		return ['query' => $query];
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
		$model = new model_query(model_session::get_company());
		$model->set_status($request->GET('value'));
		return ['queries' => $model->get_queries()];
	}

	public static function private_set_street(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->set_department('all');
		$model->set_street($request->GET('value'));
		$model->set_house('all');
		if($request->GET('value') > 0){
			$street = new data_street();
			$street->set_id($request->GET('value'));
			(new mapper_street2house($street))->init_houses();
		}
		return ['queries' => $model->get_queries(), 'street' => $street];
	}

	public static function private_set_house(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->set_house($request->GET('value'));
		return ['queries' => $model->get_queries()];
	}

	public static function private_set_department(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->set_department($request->GET('value'));
		$model->set_street('all');
		$model->set_house('all');
		return ['queries' => $model->get_queries()];
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
		if($time < 0)
			$time = getdate();
		else
			$time = getdate($time);
		$time = mktime(0, 0, 0, $time['mon'], 1, $time['year']);
		$now = getdate();
		$now = mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']);
		$model = new model_query(model_session::get_company());
		switch ($request->GET('act')) {
			case 'next':
				$begin = strtotime("+1 month", $time);
				$timeline = strtotime("+1 month +12 hours", $time);
			break;
			case 'previous':
				$begin = strtotime("-1 day", $time);
				$timeline = strtotime("-12 hours", $time);
			break;
			default:
				return false;
		}
		$model->set_param('time_open_begin', $begin);
		$model->set_param('time_open_end', $begin + 86399);
		$company = model_session::get_company();
		return ['queries' => $model->get_queries(),
			'now' =>  $now,
			'timeline' => $timeline];
	}

	public static function private_get_user_options(model_request $request){
		$group = new data_group();
		$group->set_id($request->GET('id'));
		(new model_group(model_session::get_company()))->init_users($group);
		return ['group' => $group];
	}

	public static function private_get_work_options(model_request $request){
		$work_group = new data_workgroup();
		$work_group->set_id($request->GET('id'));
		(new mapper_workgroup2work(model_session::get_company(), $work_group))->init_works();
		return ['work_group' => $work_group];
	}

	public static function private_show_default_page(model_request $request){
		$query = new data_query();
		$time = getdate();
		$now = getdate();
		$houses = [];
		$company = model_session::get_company();
		return ['queries' => (new model_query($company))->get_queries(),
			'filters' => $_SESSION['filters']['query'],
			'timeline' =>  mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'streets' => model_street::get_streets($street),
			'departments' => (new model_department($company))->get_departments(),
			// 'numbers' => model_query::get_numbers($company, $query),
			// 'query_work_types' => model_query_work_type::get_query_work_types($company, new data_query_work_type()),
			'houses' => $houses];
	}

	public static function private_remove_user(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->remove_user($request->GET('id'), $request->GET('user_id'),
			$request->GET('type'))];
	}

	public static function private_remove_work(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->remove_work($request->GET('id'), $request->GET('work_id'))];
	}

	public static function private_update_description(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->update_description($request->GET('id'), $request->GET('description'))];
	}

	public static function private_update_reason(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->update_reason($request->GET('id'), $request->GET('reason'))];
	}

	public static function private_update_contact_information(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->update_contact_information($request->GET('id'), $request->GET('fio'),
			$request->GET('telephone'), $request->GET('cellphone'))];
	}

	public static function private_update_payment_status(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->update_payment_status($request->GET('id'), $request->GET('status'))];
	}

	public static function private_update_warning_status(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->update_warning_status($request->GET('id'), $request->GET('status'))];
	}

	public static function private_update_work_type(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->update_work_type($request->GET('id'), $request->GET('type'))];
	}
}
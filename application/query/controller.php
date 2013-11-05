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
		$begin = strtotime($begin_hours.':'.$begin_minutes.' '.$begin_date);
		$end = strtotime($end_hours.':'.$end_minutes.' '.$end_date);
		return ['query' => (new model_query(model_session::get_company()))
			->add_work($request->GET('id'), $request->GET('work_id'), $begin, $end)];
	}

	public static function private_clear_filters(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$model->init_params();
		$time = getdate($model->get_params()['time_open_begin']);
		$timeline = mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$collection = new collection_query($company, $model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection,
			'timeline' =>  $timeline, 'now' =>  $timeline];
	}

	public static function private_close_query(model_request $request){
		$model = new model_query(model_session::get_company());
		$query = $model->close_query($request->GET('id'), $request->GET('reason'));
		$model->init_numbers($query);
		$model->init_users($query);
		return ['query' => $query];
	}

	public static function private_change_initiator(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->change_initiator($request->GET('query_id'),
			$request->GET('house_id'), $request->GET('number_id'))];
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
		$model = new model_query(model_session::get_company());
		$query = $model->to_working_query($request->GET('id'));
		$model->init_numbers($query);
		$model->init_users($query);
		return ['query' => $query];
	}

	public static function private_create_query(model_request $request){
		$company = model_session::get_company();
		$model = new model_query($company);
		$query = $model->create_query($request->GET('initiator'),
			$request->GET('id'), $request->GET('description'),
			$request->GET('work_type'), $request->GET('fio'),
			$request->GET('telephone'), $request->GET('cellphone'));
		$collection = new collection_query($company, $model->get_queries());
		return ['queries' => $collection];
	}

	public static function private_get_documents(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_day(model_request $request){
		$time = getdate($request->GET('time'));
		$begin = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$model = new model_query(model_session::get_company());
		$model->set_time_open_begin($begin);
		$model->set_time_open_end($begin + 86399);
		$collection = new collection_query(model_session::get_company(),
		 $model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection];
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

	public static function private_get_dialog_create_query(
		model_request $request){
		return true;
	}

	public static function private_get_dialog_close_query(
		model_request $request){
		return true;
	}

	public static function private_get_dialog_reclose_query(
		model_request $request){
		return true;
	}

	public static function private_get_dialog_reopen_query(
		model_request $request){
		return true;
	}

	public static function private_get_dialog_to_working_query(
		model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_change_initiator(
		model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id')),
			'streets' => (new model_street)->get_streets()];
	}

	public static function private_get_dialog_edit_description(
		model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_reason(model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_contact_information(
		model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_payment_status(
		model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_warning_status(
		model_request $request){
		return ['query' => (new model_query(model_session::get_company()))
			->get_query($request->GET('id'))];
	}

	public static function private_get_dialog_edit_work_type(
		model_request $request){
		$company = model_session::get_company();
		return ['query' => (new model_query($company))
			->get_query($request->GET('id')),
			'work_types' => (new model_query_work_type($company))
			->get_query_work_types()];
	}

	public static function private_get_dialog_initiator(model_request $request){
		return ['streets' => (new model_street)->get_streets()];
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
		$types = (new model_query_work_type($company))->get_query_work_types();
		switch($request->GET('initiator')){
			case 'number':
				return ['number' => (new model_number($company))
					->get_number($request->GET('id')), 'query_work_types' => $types];
			break;
			case 'house':
				return ['house' => (new model_house)->get_house($request->GET('id')),
					'query_work_types' => $types];
			break;
			default:
				throw new e_model('Проблема типа инициатора.');		
		}
	}

	public static function private_get_houses(model_request $request){
		$street = new data_street();
		$street->set_id($request->GET('id'));
		(new mapper_street2house($street))->init_houses();
		return ['street' => $street];
	}

	public static function private_get_numbers(model_request $request){
		$house = (new model_house)->get_house($request->GET('id'));
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
		$model = new model_query(model_session::get_company());
		$collection = new collection_query(model_session::get_company(),
			$model->get_queries_by_number($request->GET('param')));
		return ['queries' => $collection];
	}

	public static function private_set_status(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->set_status($request->GET('value'));
		$collection = new collection_query(model_session::get_company(),
			$model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection];
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
		$collection = new collection_query(model_session::get_company(),
		 $model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection, 'street' => $street];
	}

	public static function private_set_house(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->set_house($request->GET('value'));
		$collection = new collection_query(model_session::get_company(),
		 $model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection];
	}

	public static function private_set_department(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->set_department($request->GET('value'));
		$model->set_street('all');
		$model->set_house('all');
		$collection = new collection_query(model_session::get_company(),
		 $model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection];
	}

	public static function private_set_work_type(model_request $request){
		$model = new model_query(model_session::get_company());
		$model->set_work_type($request->GET('value'));
		$collection = new collection_query(model_session::get_company(),
		 $model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection];
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
		$model->set_time_open_begin($begin);
		$model->set_time_open_end($begin + 86399);
		$company = model_session::get_company();
		$collection = new collection_query($company, $model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection,
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
		(new mapper_workgroup2work(model_session::get_company(), $work_group))
			->init_works();
		return ['work_group' => $work_group];
	}

	public static function private_show_default_page(model_request $request){
		$now = getdate();
		$houses = [];
		$company = model_session::get_company();
		$model = new model_query($company);
		$params = $model->get_params();
		$time = getdate($params['time_open_begin']);
		if($params['street'] > 0){
			$street = new data_street();
			$street->set_id($params['street']);
			(new mapper_street2house($street))->init_houses();
			$houses = $street->get_houses();
		}
		$collection = new collection_query($company, $model->get_queries());
		$collection->init_numbers();
		return ['queries' => $collection,
			'params' => $params,
			'timeline' =>  mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'streets' => model_street::get_streets($street),
			'departments' => (new model_department($company))->get_departments(),
			'query_work_types' => (new model_query_work_type($company))
				->get_query_work_types(),
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

	public static function private_update_contact_information(
		model_request $request){
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
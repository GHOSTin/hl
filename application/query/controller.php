<?php

class controller_query{

	static $name = 'Заявки';
	static $rules = [];

	public static function private_add_user(model_request $request){
		return ['query' => di::get('model_query')
			->add_user($request->GET('id'), $request->GET('user_id'),
			$request->GET('type'))];
	}

	public static function private_add_comment(model_request $request){
		$em = di::get('em');
		$comment = new data_query2comment();
		$comment->set_user(di::get('user'));
		$comment->set_time(time());
		$comment->set_message($request->GET('message'));
		$query = $em->find('data_query', $request->GET('query_id'));
		$query->add_comment($comment);
		$em->flush($query);
		return ['query' => $query];
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
		return ['query' => di::get('model_query')
			->add_work($request->GET('id'), $request->GET('work_id'), $begin, $end)];
	}

	public static function private_clear_filters(model_request $request){
		$model = di::get('model_query');
		$model->init_params();
		$time = getdate($model->get_params()['time_open_begin']);
		$timeline = mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']);
		return ['queries' => $collection,
			'timeline' =>  $timeline, 'now' =>  $timeline];
	}

	public static function private_close_query(model_request $request){
		preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
			$request->GET('reason'), $matches);
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		if(!in_array($query->get_status(), ['working', 'open'], true))
			throw new RuntimeException('Заявка не может быть закрыта.');
		$query->set_status('close');
		$query->set_close_reason(implode('', $matches[0]));
		$query->set_time_close(time());
		$em->flush();
		return ['query' => $query];
	}

	public static function private_change_initiator(model_request $request){
		return ['query' => di::get('model_query')
			->change_initiator($request->GET('query_id'),
			$request->GET('house_id'), $request->GET('number_id'))];
	}

	public static function private_reclose_query(model_request $request){
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		if($query->get_status() !== 'reopen')
			throw new RuntimeException();
		$query->set_status('close');
		$em->flush();
		return ['query' => $query];
	}

	public static function private_reopen_query(model_request $request){
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		if($query->get_status() !== 'close')
			throw new RuntimeException();
		$query->set_status('reopen');
		$em->flush();
		return ['query' => $query];
	}

	public static function private_to_working_query(model_request $request){
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		if($query->get_status() !== 'open')
			throw new RuntimeException();
		$query->set_status('working');
		$query->set_time_work(time());
		$em->flush();
		return ['query' => $query];
	}

	public static function private_create_query(model_request $request){
		preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
			$request->GET('description'), $matches);
		$model = di::get('model_query');
		$query = $model->create_query($request->GET('initiator'),
			$request->GET('id'), implode('', $matches[0]),
			$request->GET('work_type'), $request->GET('fio'),
			$request->GET('telephone'), $request->GET('cellphone'));
		return ['queries' => $collection];
	}


	public static function private_get_documents(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_day(model_request $request){
		$time = getdate($request->GET('time'));
		$params['time_open_begin'] = mktime(0, 0, 0, 5, $time['mday'], $time['year']);
		$params['time_open_end'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		return ['queries' => di::get('em')->getRepository('data_query')
			->findByParams($params)];
	}


	public static function private_get_dialog_add_comment(model_request $request){
		return null;
	}

	public static function private_get_dialog_cancel_client_query(model_request $request){
		return null;
	}


	public static function private_get_dialog_add_user(model_request $request){
		return ['query' => di::get('model_query')->get_query($request->GET('id')),
			'groups' => di::get('em')->getRepository('data_group')->findAll()];
	}


	public static function private_get_dialog_add_work(model_request $request){
		return ['workgroups' => di::get('em')
			->getRepository('data_workgroup')->findAll()];
	}


	public static function private_get_dialog_create_query(
		model_request $request){
		return null;
	}


	public static function private_get_dialog_close_query(
		model_request $request){
		return null;
	}


	public static function private_get_dialog_reclose_query(
		model_request $request){
		return null;
	}


	public static function private_get_dialog_reopen_query(
		model_request $request){
		return null;
	}


	public static function private_get_dialog_to_working_query(
		model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}


	public static function private_get_dialog_change_initiator(
		model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id')),
			'streets' => di::get('em')->getRepository('data_street')->findAll()];
	}


	public static function private_get_dialog_edit_description(
		model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}


	public static function private_get_dialog_edit_reason(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}


	public static function private_get_dialog_edit_contact_information(
		model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}


	public static function private_get_dialog_edit_payment_status(
		model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}


	public static function private_get_dialog_edit_warning_status(
		model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}


	public static function private_get_dialog_edit_work_type(
		model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id')),
			'work_types' => di::get('em')
			->getRepository('data_query_work_type')->findBy([], ['name' => 'ASC'])];
	}


	public static function private_get_dialog_initiator(model_request $request){
		return ['streets' => di::get('em')->getRepository('data_street')->findAll()];
	}


	public static function private_get_dialog_remove_user(model_request $request){
		return ['user' => di::get('em')->getRepository('data_user')
			->findOne($request->GET('user_id'))];
	}


	public static function private_get_dialog_remove_work(model_request $request){
		return ['work' => di::get('model_work')
			->get_work($request->GET('work_id'))];
	}

	public static function private_get_initiator(model_request $request){
		$types = (new model_query_work_type)->get_query_work_types();
		switch($request->GET('initiator')){
			case 'number':
				$number = di::get('em')->find('data_number', $request->GET('id'));
				$house = $number->get_flat()->get_house();
				$queries = di::get('mapper_query')->get_queries_by_house($house);
				return ['number' => $number, 'query_work_types' => $types,
					'queries' => $queries];
			break;
			case 'house':
				$house = di::get('em')->find('data_house', $request->GET('id'));
				$queries = di::get('mapper_query')->get_queries_by_house($house);
				return ['house' => $house, 'query_work_types' => $types,
					'queries' => $queries];
			break;
			default:
				throw new RuntimeException('Проблема типа инициатора.');
		}
	}

	public static function private_get_houses(model_request $request){
		return ['street' => di::get('em')
							->find('data_street', $request->GET('id'))];
	}

	public static function private_get_numbers(model_request $request){
		return ['house' => di::get('em')->find('data_house', $request->GET('id'))];
	}

	public static function private_print_query(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_query_content(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_query_title(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_query_numbers(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_query_users(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_query_works(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_query_comments(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_search(model_request $request){
		return null;
	}

	public static function private_get_search_result(model_request $request){
		return ['queries' => di::get('em')->getRepository('data_query')
			->findByNumber($request->GET('param'))];
	}

	public static function private_set_status(model_request $request){
		$model = di::get('model_query');
		$model->set_status($request->GET('value'));
		return ['queries' => $collection];
	}

	public static function private_set_street(model_request $request){
		$model = di::get('model_query');
		$model->set_department('all');
		$model->set_street($request->GET('value'));
		$model->set_house('all');
		if($request->GET('value') > 0)
			$street = di::get('em')->find('data_street', $request->GET('value'));
		return ['queries' => $collection, 'street' => $street];
	}

	public static function private_set_house(model_request $request){
		$model = di::get('model_query');
		$model->set_house($request->GET('value'));
		return ['queries' => $collection];
	}

	public static function private_set_department(model_request $request){
		$model = di::get('model_query');
		$model->set_department($request->GET('value'));
		$model->set_street('all');
		$model->set_house('all');
		return ['queries' => $collection];
	}

	public static function private_set_work_type(model_request $request){
		$model = di::get('model_query');
		$model->set_work_type($request->GET('value'));
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
		$model = di::get('model_query');
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
		return ['queries' => $collection,
			'now' =>  $now,
			'timeline' => $timeline];
	}

	public static function private_get_user_options(model_request $request){
		return ['group' => di::get('em')->find('data_user', $request->GET('id'))];
	}

	public static function private_get_work_options(model_request $request){
		return ['work_group' => di::get('em')->find('data_workgroup', $request->GET('id'))];
	}

	public static function private_show_default_page(model_request $request){
		$em = di::get('em');
		$now = getdate();
		$time = getdate();
		if($params['street'] > 0){
			$street = di::get('em')->find('data_street', $params['street']);
			$houses = $street->get_houses();
		}else
			$houses = [];
		return ['queries' => $em->getRepository('data_query')->findBy(['status' => 'reopen']), /*'client_queries' => $client_queries,*/
			'params' => $params,
			'timeline' =>  mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'streets' => $em->getRepository('data_street')->findAll(),
			'departments' => $em->getRepository('data_department')->findAll(),
			'query_work_types' => [],
			'houses' => $houses];
	}


	public static function private_remove_user(model_request $request){
		return ['query' => di::get('model_query')->remove_user($request->GET('id'),
			$request->GET('user_id'), $request->GET('type'))];
	}


	public static function private_remove_work(model_request $request){
		return ['query' => di::get('model_query')
			->remove_work($request->GET('id'), $request->GET('work_id'))];
	}

	public static function private_update_description(model_request $request){
		preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
			$request->GET('description'), $matches);
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		$query->set_description(implode('', $matches[0]));
		$em->flush();
		return ['query' => $query];
	}

	public static function private_update_reason(model_request $request){
		preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
			$request->GET('reason'), $matches);
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		$query->set_close_reason(implode('', $matches[0]));
		$em->flush();
		return ['query' => $query];
	}

	public static function private_update_contact_information(
		model_request $request){
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		$query->set_contact_fio($request->GET('fio'));
		$query->set_contact_telephone($request->GET('telephone'));
		$query->set_contact_cellphone($request->GET('cellphone'));
		$em->flush();
		return ['query' => $query];
	}

	public static function private_update_payment_status(model_request $request){
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		$query->set_payment_status($request->GET('status'));
		$em->flush();
		return ['query' => $query];
	}

	public static function private_update_warning_status(model_request $request){
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		$query->set_warning_status($request->GET('status'));
		$em->flush();
		return ['query' => $query];
	}

	public static function private_update_work_type(model_request $request){
		$em = di::get('em');
		$type = $em->find('data_query_work_type', $request->GET('type'));
		$query = $em->find('data_query', $request->GET('id'));
		if(is_null($query))
			throw new RuntimeException();
		$query->add_work_type($type);
		$em->flush();
		return ['query' => $query];
	}
}
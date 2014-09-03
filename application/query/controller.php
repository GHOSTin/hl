<?php
class controller_query{

	static $name = 'Заявки';
	static $rules = [];

	public static function private_add_user(model_request $request){
		$em = di::get('em');
		$user = $em->find('data_user', $request->GET('user_id'));
		$query = $em->find('data_query', $request->GET('id'));
		if(in_array($request->GET('type'),  ['manager', 'performer'], true)){
			$u = new data_query2user($query, $user);
			$u->set_class($request->GET('type'));
			$em->persist($u);
			$em->flush();
		}else
			throw new RuntimeException('Несоответствующие параметры: class.');
		return ['query' => $query];
	}

	public static function private_add_comment(model_request $request){
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('query_id'));
		$comment = new data_query2comment();
		$comment->set_user(di::get('user'));
		$comment->set_query($query);
		$comment->set_time(time());
		$comment->set_message($request->GET('message'));
		$query->add_comment($comment);
		$em->persist($comment);
		$em->flush();
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
		if($begin > $end)
			throw new RuntimeException('wrong time.');
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		$w = $em->find('data_work', $request->GET('work_id'));
		$work = new data_query2work($query, $w);
		$work->set_time_open($begin_time);
		$work->set_time_close($end_time);
		$em->persist($work);
		$em->flush();
		return ['query' => $query];
	}

	public static function private_clear_filters(model_request $request){
		$model = di::get('model_query');
		$model->init_default_params();
		return ['queries' => $model->get_queries(),
			'timeline' =>  $model->get_timeline(), 'now' => $model->get_timeline()];
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
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('query_id'));
		$numbers = $query->get_numbers();
			if(!empty($numbers))
				foreach($numbers as $number)
					$em->remove($number);
		if(!is_null($request->GET('number_id'))){
			$query->set_initiator('number');
			$number = $em->find('data_number', $request->GET('number_id'));
			$query->set_house($number->get_flat()->get_house());
			$query->add_number($number);
		}elseif(!is_null($request->GET('house_id'))){
			$query->set_initiator('house');
			$house = $em->find('data_house', $request->GET('house_id'));
			$query->set_house($house);
		}else
			throw new RuntimeException('initiator wrong');
		$em->flush();
		return ['query' => $query];
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
		$em = di::get('em');
		preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
			$request->GET('description'), $matches);
		$time = getdate();
		$query = new data_query();
		$query->set_contact_fio($request->GET('fio'));
		$query->set_contact_telephone($request->GET('telephone'));
		$query->set_contact_cellphone($request->GET('cellphone'));
		$query->set_description(implode('', $matches[0]));
		$query->set_initiator($request->GET('initiator'));
		$query->set_status('open');
		$query->set_payment_status('unpaid');
		$query->set_warning_status('normal');
		$query->set_time_open($time[0]);
		$query->set_time_work($time[0]);
		if($request->GET('initiator') === 'house'){
			$house = $em->find('data_house', $request->GET('id'));
		}elseif($request->GET('initiator') === 'number'){
			$number = $em->find('data_number', $request->GET('id'));
			$query->add_number($number);
			$house = $number->get_flat()->get_house();
		}
		$query->set_house($house);
		$query->set_department($house->get_department());
		$query->add_work_type($em->find('data_query_work_type',
			$request->GET('work_type')));
		$conn = $em->getConnection();
		$q = $conn->query('SELECT MAX(querynumber) as number FROM `queries`
			WHERE `opentime` > '.mktime(0, 0, 0, 1, 1, $time['year']).'
			AND `opentime` <= '.mktime(23, 59, 59, 23, 59, $time['year']));
		$query->set_number($q->fetch()['number'] + 1);
		$em->persist($query);
		$em->flush();
		$creator = new data_query2user($query, di::get('user'));
		$creator->set_class('creator');
		$manager = new data_query2user($query, di::get('user'));
		$manager->set_class('manager');
		$em->persist($creator);
		$em->persist($manager);
		$em->flush();
		$params['time_open_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
		$params['time_open_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
		return ['queries' => di::get('em')->getRepository('data_query')
			->findByParams($params)];
	}

	public static function private_get_documents(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id'))];
	}

	public static function private_get_day(model_request $request){
		$model = di::get('model_query');
		$model->set_time($request->GET('time'));
		return ['queries' => $model->get_queries()];
	}

	public static function private_get_dialog_add_comment(model_request $request){
		return null;
	}

	public static function private_get_dialog_cancel_client_query(
		model_request $request){
		return null;
	}

	public static function private_get_dialog_add_user(model_request $request){
		return ['query' => di::get('em')->find('data_query', $request->GET('id')),
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
		return ['streets' => di::get('em')
			->getRepository('data_street')->findAll()];
	}


	public static function private_get_dialog_remove_user(model_request $request){
		return ['user' => di::get('em')->getRepository('data_user')
			->find($request->GET('user_id'))];
	}


	public static function private_get_dialog_remove_work(model_request $request){
		return ['work' => di::get('em')
			->find('data_work', $request->GET('work_id'))];
	}

	public static function private_get_initiator(model_request $request){
		$em = di::get('em');
		$types = $em->getRepository('data_query_work_type')
			->findBy([], ['name'=> 'ASC']);
		switch($request->GET('initiator')){
			case 'number':
				$number = $em->find('data_number', $request->GET('id'));
				// $queries = di::get('mapper_query')->get_queries_by_house($house);
				return ['number' => $number, 'query_work_types' => $types,
					'queries' => $queries];
			break;
			case 'house':
				$house = di::get('em')->find('data_house', $request->GET('id'));
				// $queries = di::get('mapper_query')->get_queries_by_house($house);
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
		return ['queries' => $model->get_queries()];
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
		return ['queries' => $model->get_queries()];
	}

	public static function private_set_work_type(model_request $request){
		$model = di::get('model_query');
		$model->set_work_type($request->GET('value'));
		return ['queries' => $model->get_queries()];
	}

	public static function private_get_timeline(model_request $request){
		$now = getdate();
		$now = mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']);
		$time = getdate($request->GET('time'));
		$time = mktime(0, 0, 0, $time['mon'], 1, $time['year']);
		$model = di::get('model_query');
		if($request->GET('act') === 'next')
				$model->set_time(strtotime("+1 month", $time));
		else
				$model->set_time(strtotime("-1 day", $time));
		return ['queries' => $model->get_queries(),
			'now' =>  $now, 'timeline' => $model->get_timeline()];
	}

	public static function private_get_user_options(model_request $request){
		return ['group' => di::get('em')->find('data_group', $request->GET('id'))];
	}

	public static function private_get_work_options(model_request $request){
		return ['work_group' => di::get('em')
			->find('data_workgroup', $request->GET('id'))];
	}

	public static function private_show_default_page(model_request $request){
		$em = di::get('em');
		$now = getdate();
		$model = di::get('model_query');
		$streets = $em->getRepository('data_street')
			->findBy([], ['name' => 'ASC']);
		$types = $em->getRepository('data_query_work_type')
			->findBy([], ['name' => 'ASC']);
		return ['queries' => $model->get_queries(),
			'params' => $model->get_filter_values(),
			'timeline' =>  $model->get_timeline(),
			'now' =>  mktime(12, 0, 0, $now['mon'], $now['mday'], $now['year']),
			'streets' => $streets,
			'departments' => $model->get_departments(),
			'query_work_types' => $types,
			'houses' => $houses];
	}

	public static function private_remove_user(model_request $request){
		$em = di::get('em');
		$u = $em->find('data_user', $request->GET('user_id'));
		$query = $em->find('data_query', $request->GET('id'));
		$users = $query->get_users();
		if(!empty($users))
			foreach($users as $user)
				if($user->get_id() === $u->get_id()
					&& $user->get_class() === $request->GET('type')){
					$em->remove($user);
					$em->flush();
				}
		return ['query' => $query];
	}

	public static function private_remove_work(model_request $request){
		$em = di::get('em');
		$query = $em->find('data_query', $request->GET('id'));
		$work = $em->find('data_work', $request->GET('work_id'));
		$w = $query->remove_work($work);
		$em->remove($w);
		$em->flush();
		return ['query' => $query];
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
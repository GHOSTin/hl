<?php
class model_query{

	private $pdo;
	private $company;
	private $params = [];
	private $restrictions = [];

	public function __construct(data_company $company){
		$this->company = $company;
	  $profile = di::get('profile');
	  if((string) $profile === 'query')
	  	$this->restrictions = $profile->get_restrictions();
    if(!empty($_SESSION['model']) AND $_SESSION['model']['model'] === 'query'){
    	$this->params = $_SESSION['model']['params'];
    }else{
    	$_SESSION['model']['model'] = 'query';
    	$this->init_params();
	  }
	}

	public function init_params(){
  	$time = getdate();
  	$time = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
    $this->params['time_open_begin'] = $time;
    $this->params['time_open_end'] = $time  + 86359;
    $this->params['status'] = null;
    $this->params['street'] = null;
    $this->params['house'] = null;
    $this->params['work_type'] = null;
    $this->params['department'] = null;
		if(!empty($this->restrictions['departments']))
			$this->params['department'] = $this->restrictions['departments'];
		else
			$this->params['department'] = [];
    $_SESSION['model']['params'] = $this->params;
	}

	public function get_params(){
		return $this->params;
	}

	public function set_param($param, $value){
		if(!array_key_exists($param, $this->params))
			throw new RuntimeException('Не существует такого параметра.');
		$this->params[$param] = $value;
		$_SESSION['model']['params'] = $this->params;
	}

	public function set_status($status){
		if(!in_array($status, ['open', 'close', 'reopen', 'working'], true))
			$this->set_param('status', null);
		else
			$this->set_param('status', $status);
	}

	public function set_department($id){
		if($id > 0){
			if(!empty($this->restrictions['departments']))
				if(!in_array($id, $this->restrictions['departments']))
					throw new RuntimeException('Участок не может быть добавлен.');
			$department = di::get('em')->find('data_department', $id);
			$this->set_param('department', [$department->get_id()]);
		}else
		if(!empty($this->restrictions['departments']))
			$this->set_param('department', $this->restrictions['departments']);
		else
			$this->set_param('department', []);
	}

	// test
	public function set_street($id){
		if($id > 0){
			$street = di::get('em')->find('data_street', $id);
			$this->set_param('street', $street->get_id());
		}else
			$this->set_param('street', null);
	}

	// test
	public function set_time_open_begin($time){
		$this->set_param('time_open_begin', (int) $time);
	}

	// test
	public function set_time_open_end($time){
		$this->set_param('time_open_end', (int) $time);
	}

	// test
	public function set_house($id){
		if($id > 0){
			$house = di::get('em')->find('data_house', $id);
			$this->set_param('house', $house->get_id());
		}else
			$this->set_param('house', null);
	}

	// test
	public function set_work_type($id){
		if($id > 0){
			$work_type = di::get('model_query_work_type')
				->get_query_work_type($id);
			$this->set_param('work_type', $work_type->get_id());
		}else
			$this->set_param('work_type', null);
	}

	// test
	public function add_comment($query_id, $message){
		$c_array = ['user' => di::get('user'), 'time' => time(),
			'message' => $message];
		$comment = di::get('factory_query2comment')->build($c_array);
		$query = $this->get_query($query_id);
		$mapper = di::get('mapper_query2comment');
		$mapper->init_comments($this->company, $query);
		$query->add_comment($comment);
		$mapper->update($this->company, $query);
		return $query;
	}

	public function add_user($query_id, $user_id, $class){
		$user = di::get('em')->find('data_user', $user_id);
		$query = $this->get_query($query_id);
		(new mapper_query2user($this->company, $query))->init_users();
		if($class === 'manager')
			$query->add_manager($user);
		elseif($class === 'performer')
			$query->add_performer($user);
		else
			throw new RuntimeException('Несоответствующие параметры: class.');
		(new mapper_query2user($this->company, $query))->update_users();
		return $query;
	}

	/**
	* Добавляет ассоциацию заявка-пользователь.
	*/
	public function remove_user($query_id, $user_id, $class){
		$user = di::get('em')->find('data_user', $user_id);
		$query = $this->get_query($query_id);
		(new mapper_query2user($this->company, $query))->init_users();
		if($class === 'manager')
			$query->remove_manager($user);
		elseif($class === 'performer')
			$query->remove_performer($user);
		else
			throw new RuntimeException('Несоответствующие параметры: class.');
		(new mapper_query2user($this->company, $query))->update_users();
		return $query;
	}

	/**
	* Добавляет ассоциацию заявка-работа.
	*/
	public function add_work($query_id, $work_id, $begin_time, $end_time){
		if(!is_int($begin_time) OR !is_int($end_time))
			throw new RuntimeException('Время задано не верно.');
		if($begin_time > $end_time)
			throw new RuntimeException('Время начала работы не может быть меньше времени закрытия.');
		$query = $this->get_query($query_id);
		$mapper = di::get('mapper_query2work');
		$mapper->init_works($this->company, $query);
		$work = new data_query2work((new model_work($this->company))
			->get_work($work_id));
		$work->set_time_open($begin_time);
		$work->set_time_close($end_time);
		$query->add_work($work);
		$mapper->update_works($this->company, $query);
		return $query;
	}

	// test
	public function close_query($id, $reason){
		$mapper = di::get('mapper_query');
		$query = $mapper->find($id);
		if(is_null($query))
			throw new RuntimeException();
		if(!in_array($query->get_status(), ['working', 'open'], true))
			throw new RuntimeException('Заявка не может быть закрыта.');
		$query->set_status('close');
		$query->set_close_reason($reason);
		$query->set_time_close(time());
		$mapper->update($query);
		return $query;
	}

	public function change_initiator($id, $house_id = null, $number_id = null){
		try{
			$pdo = di::get('pdo');
			$pdo->beginTransaction();
			$company = di::get('company');
			$query = $this->get_query($id);
			$mapper_q2n = new mapper_query2number($company, $query);
			if(!is_null($number_id)){
				$query->set_initiator('number');
				$number = di::get('em')->find('data_number', $number_id);
				$house = di::get('em')->find('data_house', $number->get_flat()->get_house()->get_id());
				$query->set_house($house);
				$query->add_number($number);
				$mapper_q2n->update();
			}elseif(!is_null($house_id)){
				$query->set_initiator('house');
				$house = di::get('em')->find('data_house', $house_id);
				$query->set_house($house);
				if(!empty($house->get_numbers()))
					foreach($house->get_numbers() as $number)
						$query->add_number($number);
				$mapper_q2n->update();
			}else
				throw new RuntimeException('initiator wrong');
			$mapper = di::get('mapper_query');
			$query = $mapper->update($query);
			$pdo->commit();
			return $query;
		}catch(exception $e){
			$pdo->rollBack();
			throw new RuntimeException('Проблема');
		}
	}

	public function reopen_query($id){
		$mapper = di::get('mapper_query');
		$query = $mapper->find($id);
		if(is_null($query))
			throw new RuntimeException();
		if($query->get_status() !== 'close')
			throw new RuntimeException();
		$query->set_status('reopen');
		$mapper->update($query);
		return $query;
	}

	// test
	public function to_working_query($id){
		$mapper = di::get('mapper_query');
		$query = $mapper->find($id);
		if(is_null($query))
			throw new RuntimeException();
		if($query->get_status() !== 'open')
			throw new RuntimeException();
		$query->set_status('working');
		$query->set_time_work(time());
		$mapper->update($query);
		return $query;
	}

	public function create_query($initiator, $id, $description, $work_type,
										$contact_fio, $contact_telephone, $contact_cellphone){
		try{
			$pdo = di::get('pdo');
			if(!$pdo->inTransaction()){
				$pdo->beginTransaction();
				$trans = true;
			}
			$time = time();
			$q_array = ['status' => 'open', 'payment_status' => 'unpaid',
				'warning_status' => 'normal', 'time_open' => $time,
				'time_work' => $time, 'initiator' => $initiator,
				'description' => $description, 'contact_fio' => $contact_fio,
				'contact_telephone' => $contact_telephone,
				'contact_cellphone' => $contact_cellphone
				];
			if($initiator === 'house'){
				$house = di::get('em')->find('data_house', $id);
				$numbers = $house->get_numbers();
			}elseif($initiator === 'number'){
				$number = di::get('em')->find('data_number', $id);
				$house = di::get('em')->find('data_house', $number->get_flat()->get_house()->get_id());
				$numbers[] = $number;
			}
			$q_array['house'] = $house;
			$q_array['department'] = $house->get_department();
			$q_array['type'] = (new model_query_work_type($this->company))
				->get_query_work_type($work_type);
			$mapper = di::get('mapper_query');
			$q_array['id'] = $mapper->get_insert_id();
			$q_array['number'] = $mapper->get_insert_query_number($time);
			$query = di::get('factory_query')->build($q_array);
      $query = $mapper->insert($query);
			if(!empty($numbers))
				foreach($numbers as $number)
					$query->add_number($number);
      (new mapper_query2number($this->company, $query))->update();
			$user = di::get('user');
      $query->add_creator($user);
      $query->add_manager($user);
      $query->add_observer($user);
      (new mapper_query2user($this->company, $query))->update_users();
      if($trans)
				$pdo->commit();
			return $query;
		}catch(exception $e){
			die($e->getMessage());
			$pdo->rollBack();
			if($e instanceof RuntimeException)
				throw new RuntimeException($e->getMessage());
			else
				throw new RuntimeException('Ошибка при создании заявки.');
		}
	}

	public function remove_work($query_id, $work_id){
		$query = $this->get_query($query_id);
		$mapper = di::get('mapper_query2work');
		$mapper->init_works($this->company, $query);
		$work = new data_query2work((new model_work($this->company))
			->get_work($work_id));
		$query->remove_work($work);
		$mapper->update_works($this->company, $query);
		return $query;
	}

	// test
	public function update_work_type($id, $type_id){
		$type = di::get('model_query_work_type')->get_query_work_type($type_id);
		$mapper = di::get('mapper_query');
		$query = $mapper->find($id);
		if(is_null($query))
			throw new RuntimeException();
		$query->add_work_type($type);
		$mapper->update($query);
		return $query;
	}
}
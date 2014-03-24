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
			throw new e_model('Не существует такого параметра.');
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
					throw new e_model('Участок не может быть добавлен.');
			$department = (new model_department($this->company))->get_department($id);
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
			$street = di::get('model_street')->get_street($id);
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
			$house = di::get('model_house')->get_house($id);
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
		$user = (new model_user)->get_user($user_id);
		$query = $this->get_query($query_id);
		(new mapper_query2user($this->company, $query))->init_users();
		if($class === 'manager')
			$query->add_manager($user);
		elseif($class === 'performer')
			$query->add_performer($user);
		else
			throw new e_model('Несоответствующие параметры: class.');
		(new mapper_query2user($this->company, $query))->update_users();
		return $query;
	}

	/**
	* Добавляет ассоциацию заявка-пользователь.
	*/
	public function remove_user($query_id, $user_id, $class){
		$user = (new model_user)->get_user($user_id);
		$query = $this->get_query($query_id);
		(new mapper_query2user($this->company, $query))->init_users();
		if($class === 'manager')
			$query->remove_manager($user);
		elseif($class === 'performer')
			$query->remove_performer($user);
		else
			throw new e_model('Несоответствующие параметры: class.');
		(new mapper_query2user($this->company, $query))->update_users();
		return $query;
	}

	/**
	* Добавляет ассоциацию заявка-работа.
	*/
	public function add_work($query_id, $work_id, $begin_time, $end_time){
		if(!is_int($begin_time) OR !is_int($end_time))
			throw new e_model('Время задано не верно.');
		if($begin_time > $end_time)
			throw new e_model('Время начала работы не может быть меньше времени закрытия.');
		$query = $this->get_query($query_id);
		(new mapper_query2work($this->company, $query))->init_works();
		$work = new data_query2work((new model_work($this->company))
			->get_work($work_id));
		$work->set_time_open($begin_time);
		$work->set_time_close($end_time);
		$query->add_work($work);
		(new mapper_query2work($this->company, $query))->update_works();
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
		return $mapper->update($query);
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
				$number = (new model_number($company))->get_number($number_id);
				$house = (new model_house)
					->get_house($number->get_flat()->get_house()->get_id());
				$query->set_house($house);
				$query->add_number($number);
				$mapper_q2n->update();
			}elseif(!is_null($house_id)){
				$query->set_initiator('house');
				$house = (new model_house)->get_house($house_id);
				$query->set_house($house);
				(new mapper_house2number($company, $house))->init_numbers();
				if(!empty($house->get_numbers()))
					foreach($house->get_numbers() as $number)
						$query->add_number($number);
				$mapper_q2n->update();
			}else
				throw new e_model('initiator wrong');
			$mapper = di::get('mapper_query');
			$query = $mapper->update($query);
			$pdo->commit();
			return $query;
		}catch(exception $e){
			$pdo->rollBack();
			throw new e_model('Проблема');
		}
	}

	// test
	public function reclose_query($id){
		$mapper = di::get('mapper_query');
		$query = $mapper->find($id);
		if(is_null($query))
			throw new RuntimeException();
		if($query->get_status() !== 'reopen')
			throw new RuntimeException();
		$query->set_status('close');
		return $mapper->update($query);
	}

	// test
	public function reopen_query($id){
		$mapper = di::get('mapper_query');
		$query = $mapper->find($id);
		if(is_null($query))
			throw new RuntimeException();
		if($query->get_status() !== 'close')
			throw new RuntimeException();
		$query->set_status('reopen');
		return $mapper->update($query);
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
		return $mapper->update($query);
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
				$house = (new model_house)->get_house($id);
				(new mapper_house2number($this->company, $house))->init_numbers();
				$numbers = $house->get_numbers();
			}elseif($initiator === 'number'){
				$number = (new model_number($this->company))->get_number($id);
				$house = (new model_house)
					->get_house($number->get_flat()->get_house()->get_id());
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
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка при создании заявки.');
		}
	}

	public function get_query($id){
		$query = di::get('mapper_query')->find($id);
		if(!($query instanceof data_query))
			throw new e_model('Проблема при выборке заявки');
		return $query;
	}

	public function init_numbers(data_query $query){
		return (new mapper_query2number($this->company, $query))->init_numbers();
	}

	public function init_users(data_query $query){
		return (new mapper_query2user($this->company, $query))->init_users();
	}

	public function init_works(data_query $query){
		return (new mapper_query2work($this->company, $query))->init_works();
	}

	public function init_comments(data_query $query){
		return di::get('mapper_query2comment')
			->init_comments($this->company, $query);
	}

	/**
	* Возвращает заявки.
	* @return array
	*/
	public function get_queries_by_number($number){
		return di::get('mapper_query')->get_queries_by_number($number);
	}

	/**
	* Возвращает заявки.
	* @return array
	*/
	public function get_queries(){
		$mapper = di::get('mapper_query');
		return $mapper->get_queries($this->params);
	}

	/**
	* Обновляет работу из заявки.
	*/
	public function remove_work($query_id, $work_id){
		$query = $this->get_query($query_id);
		(new mapper_query2work($this->company, $query))->init_works();
		$work = new data_query2work((new model_work($this->company))
			->get_work($work_id));
		$query->remove_work($work);
		(new mapper_query2work($this->company, $query))->update_works();
		return $query;
	}

	/**
	* Обновляет описание заявки.
	*/
	public function update_description($id, $description){
		$query = $this->get_query($id);
		$query->set_description($description);
		$mapper = di::get('mapper_query');
		return $mapper->update($query);
	}

	/**
	* Обновляет описание заявки.
	*/
	public function update_reason($id, $reason){
		$query = $this->get_query($id);
		$query->set_close_reason($reason);
		$mapper = di::get('mapper_query');
		return $mapper->update($query);
	}

	/**
	* Обновляет контактную информацию.
	*/
	public function update_contact_information($id, $fio, $telephone, $cellphone){
		$query = $this->get_query($id);
		$query->set_contact_fio($fio);
		$query->set_contact_telephone($telephone);
		$query->set_contact_cellphone($cellphone);
		$mapper = di::get('mapper_query');
		return $mapper->update($query);
	}

	/**
	* Обновляет статус оплаты.
	*/
	public function update_payment_status($id, $status){
		$query = $this->get_query($id);
		$query->set_payment_status($status);
		$mapper = di::get('mapper_query');
		return $mapper->update($query);
	}

	/**
	* Обновляет статус реакции.
	*/
	public function update_warning_status($id, $status){
		$query = $this->get_query($id);
		$query->set_warning_status($status);
		$mapper = di::get('mapper_query');
		return $mapper->update($query);
	}

	/**
	* Обновляет тип работ.
	*/
	public function update_work_type($id, $type_id){
		$type = (new model_query_work_type(di::get('company')))
			->get_query_work_type($type_id);
		$query = $this->get_query($id);
		$query->add_work_type($type);
		$mapper = di::get('mapper_query');
		return $mapper->update($query);
	}
}
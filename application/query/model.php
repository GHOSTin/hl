<?php
class model_query{

	private $company;
	private $params = [];

	public function __construct(data_company $company){
		$this->company = $company;
    $this->company->verify('id');
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
    $this->params['department'] = null;
    $this->params['street'] = null;
    $this->params['house'] = null;
    $this->params['work_type'] = null;
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
			$department = (new model_department($this->company))->get_department($id);
			$this->set_param('department', $department->get_id());
		}else
			$this->set_param('department', null);
	}

	public function set_street($id){
		if($id > 0){
			$street = (new model_street($this->company))->get_street($id);
			$this->set_param('street', $street->get_id());
		}else
			$this->set_param('street', null);
	}

	public function set_house($id){
		if($id > 0){
			$house = (new model_house($this->company))->get_house($id);
			$this->set_param('house', $house->get_id());
		}else
			$this->set_param('house', null);
	}

	public function set_work_type($id){
		if($id > 0){
			$work_type = (new model_query_work_type($this->company))->get_query_work_type($id);
			$this->set_param('work_type', $work_type->get_id());
		}else
			$this->set_param('work_type', null);
	}

	/**
	* Добавляет ассоциацию заявка-пользователь.
	*/
	public function add_user($query_id, $user_id, $class){
		$user = new data_query2user((new model_user)->get_user($user_id));
		$user->set_class($class);
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
		$user = new data_query2user((new model_user)->get_user($user_id));
		$user->set_class($class);
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
		$work = new data_query2work((new model_work($this->company))->get_work($work_id));
		$work->set_time_open($begin_time);
		$work->set_time_close($end_time);
		$query->add_work($work);
		(new mapper_query2work($this->company, $query))->update_works();
		return $query;
	}

	/**
	* Закрывает заявку.
	*/
	public function close_query($id, $reason){
		$query = $this->get_query($id);
		if(!in_array($query->get_status(), ['working', 'open'], true))
			throw new e_model('Заявка не может быть закрыта.');
		$query->set_status('close');
		$query->set_close_reason($reason);
		$query->set_time_close(time());
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}

	/**
	* Закрывает заявку.
	*/
	public function change_initiator($id, $house_id = null, $number_id = null){
		$sql = new sql();
		$sql->begin();
		try{
			$company = model_session::get_company();
			$query = $this->get_query($id);
			$mapper_q2n = new mapper_query2number($company, $query);
			if(!is_null($number_id)){
				$query->set_initiator('number');
				$number = (new model_number($company))->get_number($number_id);
				$house = (new model_house)->get_house($number->get_house_id());
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
			$mapper = new mapper_query($this->company);
			$query = $mapper->update($query);
			$sql->commit();
			return $query;
		}catch(exception $e){
			$sql->rallback();
			die('Проблема');
		}
	}

	/**
	* Закрывает заявку.
	*/
	public function reclose_query($id){
		$query = $this->get_query($id);
		if($query->get_status() !== 'reopen')
			throw new e_model('Заявка не может быть перезакрыта.');
		$query->set_status('close');
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}

	/**
	* Переоткрывает заявку.
	*/
	public function reopen_query($id){
		$query = $this->get_query($id);
		if($query->get_status() !== 'close')
			throw new e_model('Заявка не в том статусе чтобы её можно было переоткрыть.');
		$query->set_status('reopen');
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}

	/**
	* Передает заявку в работу.
	*/
	public function to_working_query($id){
		$query = $this->get_query($id);
		if($query->get_status() !== 'open')
			throw new e_model('Заявка имеет статус не позволяющий её передать в работу.');
		$query->set_status('working');
		$query->set_time_work(time());
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}

	/**
	* Создает новую заявку.
	* @retrun array из data_query
	*/
	public function create_query($initiator, $id, $description, $work_type,
										$contact_fio, $contact_telephone, $contact_cellphone){
		try{
			sql::begin();
			$time = time();
			$query = new data_query();
      $query->set_company_id($this->company->get_id());
      $query->set_status('open');
      $query->set_payment_status('unpaid');
      $query->set_warning_status('normal');
			$query->set_time_open($time);
			$query->set_time_work($time);
			$query->set_initiator($initiator);
      $query->set_description($description);
      $query->set_contact_fio($contact_fio);
      $query->set_contact_telephone($contact_telephone);
      $query->set_contact_cellphone($contact_cellphone);

      // получение идентификатора дома
			if($initiator === 'house'){
				$house = (new model_house)->get_house($id);
				(new mapper_house2number($this->company, $house))->init_numbers();
				if(!empty($house->get_numbers()))
					foreach($house->get_numbers() as $number)
						$query->add_number($number);
				$department = (new model_department($this->company))
					->get_department($house->get_department_id());
				$query->set_house($house);
        $query->set_department($department);
			}elseif($initiator === 'number'){
				$number = (new model_number($this->company))->get_number($id);
				$house = (new model_house)->get_house($number->get_house_id());
				$department = (new model_department($this->company))
					->get_department($house->get_department_id());
				$query->add_number($number);
				$query->set_house($house);
        $query->set_department($department);
			}else
				throw new e_model('Инициатор не корректен.');
			$query_work_type = (new model_query_work_type($this->company))
				->get_query_work_type($work_type);
      $query->add_work_type($query_work_type);
			$mapper = new mapper_query($this->company);
			$query->set_id($mapper->get_insert_id());
			$query->set_number($mapper->get_insert_query_number($time));
      $query = $mapper->insert($query);
      (new mapper_query2number($this->company, $query))->update();
			$creator = new data_query2user(model_session::get_user());
      $creator->set_class('creator');
			$manager = new data_query2user(model_session::get_user());
      $manager->set_class('manager');
			$observer = new data_query2user(model_session::get_user());
			$observer->set_class('observer');
      $query->add_creator($creator);
      $query->add_manager($manager);
      $query->add_observer($observer);
      (new mapper_query2user($this->company, $query))->update_users();
			sql::commit();
			return $query;
		}catch(exception $e){
			die($e->getMessage());
			sql::rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка при создании заявки.');
		}
	}

	public function get_query($id){
		$query = (new mapper_query($this->company))->find($id);
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

	/**
	* Возвращает заявки.
	* @return array
	*/
	public function get_queries_by_number($number){
		return (new mapper_query($this->company))->get_queries_by_number($number);
	}

	/**
	* Возвращает заявки.
	* @return array
	*/
	public function get_queries(){
		$mapper = new mapper_query($this->company);
		return $mapper->get_queries($this->params);
	}

	/**
	* Возвращает лицевые счета заявки.
	* @return array
	*/
	public static function get_numbers(data_company $company, data_query $query){
		$sql = new sql();
		if(!empty($query->id)){
			die('disabled numbers');
		}else{
			$sql->query("SELECT `query2number`.`query_id`, `query2number`.`default`, `numbers`.`id`,
				`numbers`.`fio`, `numbers`.`number`, `flats`.`flatnumber` as `flat_number`
				FROM `queries`, `query2number`, `numbers`, `flats`
				WHERE `queries`.`company_id` = :company_id
				AND `query2number`.`company_id` = :company_id
				AND `flats`.`company_id` = :company_id
				AND `numbers`.`company_id` = :company_id
				AND `queries`.`id` = `query2number`.`query_id`
				AND `numbers`.`id` = `query2number`.`number_id`
				AND `numbers`.`flat_id` = `flats`.`id`
				AND `opentime` > :time_open
				AND `opentime` <= :time_close
				ORDER BY (`flats`.`flatnumber` + 0)");
			$sql->bind(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$sql->bind(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all'){
				$sql->query(" AND `queries`.`status` = :status");
				$sql->bind(':status', $query->status, PDO::PARAM_STR);
			}
		}
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при выборке лицевых счетов.');
		$result = ['structure' => [], 'numbers' => []];
		while($row = $sql->row()){
			$number = new data_number();
			$number->id = $row['id'];
			$number->fio = $row['fio'];
			$number->number = $row['number'];
			$number->flat_number = $row['flat_number'];
			$result['structure'][$row['query_id']][$row['default']][] = $number->id;
			$result['numbers'][$number->id] = $number;
		}
		$sql->close();
		return $result;
	}

	/**
	* Возвращает пользователей заявки.
	* @return array
	*/
	public static function get_users(data_company $company, data_query $query){
		$sql = new sql();
		if(!empty($query->id)){
			die('disabled users');
		}else{
			$sql->query("SELECT `query2user`.`query_id`,  `query2user`.`class`, `users`.`id`,
				`users`.`firstname`, `users`.`lastname`, `users`.`midlename`
				FROM `queries`, `query2user`, `users`
				WHERE `queries`.`company_id` = :company_id
				AND `query2user`.`company_id` = :company_id
				AND `users`.`id` = `query2user`.`user_id`
				AND `queries`.`id` = `query2user`.`query_id`
				AND `opentime` > :time_open
				AND `opentime` <= :time_close");
			$sql->bind(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$sql->bind(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all'){
				$sql->query(" AND `queries`.`status` = :status");
				$sql->bind(':status', $query->status, PDO::PARAM_STR);
			}
			$sql->query(" ORDER BY `opentime` DESC");
		}
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при выборке пользователей.');
		$result = ['structure' => [], 'users' => []];
		while($row = $sql->row()){
			$user = new data_user();
			$user->id = $row['id'];
			$user->firstname = $row['firstname'];
			$user->lastname = $row['lastname'];
			$user->middlename = $row['midlename'];
			$result['structure'][$row['query_id']][$row['class']][] = $user->id;
			$result['users'][$user->id] = $user;
		}
		$sql->close();
		return $result;
	}

	/**
	* Возвращает работы.
	* @return array
	*/
	public static function get_works(data_company $company, data_query $query){
		$sql = new sql();
		if(!empty($query->id)){
			die('disabled works');
		}else{
			$sql->query("SELECT `query2work`.`query_id`, `query2work`.`opentime` as `time_open`,
				`query2work`.`closetime` as `time_close`, `query2work`.`value`,
				`works`.`id`, `works`.`name`
				FROM `queries`, `query2work`, `works`
				WHERE `queries`.`company_id` = :company_id
				AND `query2work`.`company_id` = :company_id
				AND `works`.`id` = `query2work`.`work_id`
				AND `queries`.`id` = `query2work`.`query_id`
				AND `queries`.`opentime` > :time_open
				AND `queries`.`opentime` <= :time_close");
			$sql->bind(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$sql->bind(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all'){
				$sql->query(" AND `queries`.`status` = :status");
				$sql->bind(':status', $query->status, PDO::PARAM_STR);
			}
			$sql->query(" ORDER BY `queries`.`opentime` DESC");
		}
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при выборке работ.');
		$result = ['structure' => [], 'works' => []];
		while($row = $sql->row()){
			$work = new data_work();
			$work->id = $row['id'];
			$work->name = $row['name'];
			$current = ['work_id' => $work->id, 'time_open' => $row['time_open'],
				'time_close' => $row['time_close'], 'value' => $row['value']];
			$result['structure'][$row['query_id']][] = $current;
			$result['works'][$work->id] = $work ;
		}
		$sql->close();
		return $result;
	}

	/**
	* Обновляет работу из заявки.
	*/
	public function remove_work($query_id, $work_id){
		$query = $this->get_query($query_id);
		(new mapper_query2work($this->company, $query))->init_works();
		$work = new data_query2work((new model_work($this->company))->get_work($work_id));
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
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}

	/**
	* Обновляет описание заявки.
	*/
	public function update_reason($id, $reason){
		$query = $this->get_query($id);
		$query->set_close_reason($reason);
		$mapper = new mapper_query($this->company);
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
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}

	/**
	* Обновляет статус оплаты.
	*/
	public function update_payment_status($id, $status){
		$query = $this->get_query($id);
		$query->set_payment_status($status);
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}

	/**
	* Обновляет статус реакции.
	*/
	public function update_warning_status($id, $status){
		$query = $this->get_query($id);
		$query->set_warning_status($status);
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}
	
	/**
	* Обновляет тип работ.
	*/
	public function update_work_type($id, $type_id){
		$type = (new model_query_work_type(model_session::get_company()))
			->get_query_work_type($type_id);
		$query = $this->get_query($id);
		$query->add_work_type($type);
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}
}
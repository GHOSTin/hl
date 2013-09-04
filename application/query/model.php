<?php
class model_query{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
    $this->company->verify('id');
	}

	/*
	* Зависимая функция.
	* Добавляет ассоциацию заявка-пользователь.
	*/
	private static function __add_user(data_company $company, data_query $query, data_user $user,  $class){
		$query->verify('id');
		$company->verify('id');
		$user->verify('id');
		if(array_search($class, ['creator', 'observer', 'manager', 'performer']) === false)
			throw new e_model('Не соответсвует тип пользователя.');
		$sql = new sql();
		$sql->query("INSERT INTO `query2user` (`query_id`, `user_id`, `company_id`, `class`)
					VALUES (:query_id, :user_id, :company_id, :class)");
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':class', $class, PDO::PARAM_STR);
		$sql->execute('Проблема при добавлении пользователя.');
	}

	/*
	* Зависимая функция.
	* Создает заявку и записывает в лог заявки.
	*/
	private static function __add_query(data_company $company, data_query $query, data_object $initiator, $time){
		$company->verify('id');
		if(!($initiator instanceof data_house OR $initiator instanceof data_number))
			throw new e_model('Инициатор не верного формата.');
		if(empty($initiator->department_id))
			throw new e_model('department_id не указан.');
		if(!is_int($time))
			throw new e_model('Неверная дата.');
		$query->company_id = $company->id;
		$query->status = 'open';
		$query->payment_status = 'unpaid';
		$query->warning_status = 'normal';
		$query->department_id = $initiator->department_id;
		$query->time_open = $query->time_work = $time;
		$query->verify('id', 'company_id', 'status', 'initiator', 'payment_status',
						'warning_status', 'department_id', 'house_id', 'work_type_id',
						'time_open', 'time_work', 'contact_fio', 'contact_telephone',
						'contact_cellphone', 'description', 'number');
		$sql = new sql();
		$sql->query("INSERT INTO `queries` (
					`id`, `company_id`, `status`, `initiator-type`, `payment-status`,
					`warning-type`, `department_id`, `house_id`, `query_worktype_id`,
					`opentime`, `worktime`, `addinfo-name`, `addinfo-telephone`,
					`addinfo-cellphone`, `description-open`, `querynumber`)
					VALUES (:id, :company_id, :status, :initiator, :payment_status, 
					:warning_status,:department_id, :house_id, :worktype_id, :time_open,
					:time_work, :contact_fio, :contact_telephone, :contact_cellphone,
					:description, :number)");
		$sql->bind(':id', $query->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $query->company_id, PDO::PARAM_INT, 3);
		$sql->bind(':status', $query->status, PDO::PARAM_STR);
		$sql->bind(':initiator', $query->initiator, PDO::PARAM_STR);
		$sql->bind(':payment_status', $query->payment_status, PDO::PARAM_STR);
		$sql->bind(':warning_status', $query->warning_status, PDO::PARAM_STR);
		$sql->bind(':department_id', $query->department_id, PDO::PARAM_INT);
		$sql->bind(':house_id', $query->house_id, PDO::PARAM_INT);
		$sql->bind(':worktype_id', $query->worktype_id, PDO::PARAM_INT);
		$sql->bind(':time_open', $query->time_open, PDO::PARAM_INT);
		$sql->bind(':time_work', $query->time_work, PDO::PARAM_INT);
		$sql->bind(':contact_fio', $query->contact_fio, PDO::PARAM_STR);
		$sql->bind(':contact_telephone', $query->contact_telephone, PDO::PARAM_STR);
		$sql->bind(':contact_cellphone', $query->contact_cellphone, PDO::PARAM_STR);
		$sql->bind(':description', $query->description, PDO::PARAM_STR);
		$sql->bind(':number', $query->number, PDO::PARAM_INT);
		$sql->execute('Проблемы при создании заявки.');
		return $query;
	}

	/**
	* Добавляет ассоциацию заявка-пользователь.
	*/
	public function add_user($query_id, $user_id, $class){
		if(array_search($class, ['manager', 'performer']) === false)
			throw new e_model('Несоответствующие параметры: class.');
		$query = $this->get_query($query_id);
		$model = new model_user();
		$user = $model->get_user($user_id);
		$sql = new sql();
		$sql->query("INSERT INTO `query2user` (`query_id`, `user_id`, `company_id`,
				 `class`, `protect`) VALUES (:query_id, :user_id, :company_id,
				 :class, :protect)");
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $query->company_id, PDO::PARAM_INT);
		$sql->bind(':class', $class, PDO::PARAM_STR);
		$sql->bind(':protect', 'false', PDO::PARAM_STR);
		$sql->execute('Ошибка при добавлении пользователя.');
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
		$work = new data_work();
		$work->id = $work_id;
		$work->verify(id);
		$work = model_work::get_works($this->company, $work)[0];
		model_work::is_data_work($work);
		$sql = new sql();
		$sql->query("INSERT INTO `query2work` (`query_id`, `work_id`, `company_id`,
					 `opentime`, `closetime`) VALUES (:query_id, :work_id, :company_id,
					 :time_open, :time_close)");
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->bind(':work_id', $work->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
		$sql->bind(':time_open', $begin_time, PDO::PARAM_INT);
		$sql->bind(':time_close', $end_time, PDO::PARAM_INT);
		$sql->execute('Ошибка при добавлении работы.');
		return $query;
	}

	/*
	* Зависимая функция.
	* Добавляет ассоциацию заявка-лицевой_счет в зависимости от типа инициатора.
	*/
	private static function add_numbers(data_company $company, data_query $query, $initiator){
		if($initiator instanceof data_house){
			$initiator->verify('id');
			$numbers = model_house::get_numbers($company, $initiator);
		}elseif($initiator instanceof data_number){
			$initiator->verify('id');
			$model = new model_number($company);
			$numbers[] = $model->get_number($initiator->id);
		}else
			throw new e_model('Не подходящий тип инициатора.');
		if(count($numbers) < 1)
			throw new e_model('Не соответсвующее количество лицевых счетов.');
		$mapper = new mapper_query2number($company, $query);
		$mapper->init_numbers();
		foreach($numbers as $number)
			$query->add_number($number);
		$mapper->update();
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
				$query->set_house_id($number->get_house_id());
				$query->add_number($number);
				$mapper_q2n->update();
			}elseif(!is_null($house_id)){
				$query->set_initiator('house');
				$house = new data_house();
				$house->id = $house_id;
				if(count(model_house::get_houses($house)) !== 1)
					throw new e_model('Нет такого дома');
				$numbers = model_house::get_numbers($company, $house);
				if(!empty($numbers))
					foreach($numbers as $number)
						$query->add_number($number);
				$mapper_q2n->update();
			}else
				die('initiator wrong');
			$mapper = new mapper_query($this->company);
			$query = $mapper->update($query);
			$sql->commit();
			return $query;
		}catch(exception $e){
			die($e);
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
																			$contact_fio, $contact_telephone,
																			$contact_cellphone){
		try{
			sql::begin();
			$time = time();
			$query = new data_query();
      $query->set_company_id($this->company->id);
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
				$initiator = new data_house();
				$initiator->id = $id;
				$initiator->verify('id');
				$initiator = model_house::get_houses($initiator)[0];
				$query->set_house_id($initiator->id);
        $query->set_department_id($initiator->department_id);
			}elseif($initiator === 'number'){
				$model = new model_number($this->company);
				$initiator = $model->get_number($id);
				$query->set_house_id($initiator->house_id);
        $query->set_department_id($initiator->department_id);
			}else
				throw new e_model('Инициатор не корректен.');
      // проверка на существование типа работ
			$query_work_type_params = new data_query_work_type();
			$query_work_type_params->id = $work_type;
			$query_work_type = model_query_work_type::get_query_work_types($this->company, $query_work_type_params)[0];
			model_query_work_type::is_data_query_work_type($query_work_type);
      $query->set_work_type_id($query_work_type->id);
			$mapper = new mapper_query($this->company);
			$query->set_id($mapper->get_insert_id());
			$query->set_number($mapper->get_insert_query_number($time));
      $query = $mapper->insert($query);
			self::add_numbers($this->company, $query, $initiator);
			self::__add_user($this->company, $query, model_session::get_user(), 'creator');
			self::__add_user($this->company, $query, model_session::get_user(), 'manager');
			self::__add_user($this->company, $query, model_session::get_user(), 'observer');
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
		$mapper = new mapper_query($this->company);
		$query = $mapper->find($id);
		if(!($query instanceof data_query))
			throw new e_model('Проблема при выборке заявки');
		return $query;
	}

	public function init_numbers(data_query $query){
		$mapper = new mapper_query2number($this->company, $query);
		return $mapper->init_numbers();
	}

	/**
	* Возвращает заявки.
	* @return array
	*/
	public static function get_queries(data_company $company, data_query $query){
		$company->verify('id');
 		$sql = new sql();
		if(!empty($query->id)){
			die('disabled query id');
		}elseif(!empty($query->number)){
			$sql->query("SELECT `queries`.`id`, `queries`.`company_id`,
				`queries`.`status`, `queries`.`initiator-type` as `initiator`,
				`queries`.`payment-status` as `payment_status`,
				`queries`.`warning-type` as `warning_status`,
				`queries`.`department_id`, `queries`.`house_id`,
				`queries`.`query_close_reason_id` as `close_reason_id`,
				`queries`.`query_worktype_id` as `worktype_id`,
				`queries`.`opentime` as `time_open`,
				`queries`.`worktime` as `time_work`,
				`queries`.`closetime` as `time_close`,
				`queries`.`addinfo-name` as `contact_fio`,
				`queries`.`addinfo-telephone` as `contact_telephone`,
				`queries`.`addinfo-cellphone` as `contact_cellphone`,
				`queries`.`description-open` as `description`,
				`queries`.`description-close` as `close_reason`,
				`queries`.`querynumber` as `number`,
				`queries`.`query_inspection` as `inspection`, 
				`houses`.`housenumber` as `house_number`,
				`streets`.`name` as `street_name`,
				`query_worktypes`.`name` as `work_type_name`,
				`departments`.`name` as `department_name`
				FROM `queries`, `houses`, `streets`, `query_worktypes` , `departments`
				WHERE `queries`.`company_id` = :company_id
				AND `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
				AND `querynumber` = :number AND `departments`.`company_id` = :company_id
				AND `queries`.`department_id` = `departments`.`id`
				ORDER BY `opentime` DESC");
			$query->verify('number');
			$sql->bind(':number', $query->number, PDO::PARAM_INT);
		}else{
			$sql->query("SELECT `queries`.`id`, `queries`.`company_id`,
				`queries`.`status`, `queries`.`initiator-type` as `initiator`,
				`queries`.`payment-status` as `payment_status`,
				`queries`.`warning-type` as `warning_status`,
				`queries`.`department_id`, `queries`.`house_id`,
				`queries`.`query_close_reason_id` as `close_reason_id`,
				`queries`.`query_worktype_id` as `worktype_id`,
				`queries`.`opentime` as `time_open`,
				`queries`.`worktime` as `time_work`,
				`queries`.`closetime` as `time_close`,
				`queries`.`addinfo-name` as `contact_fio`,
				`queries`.`addinfo-telephone` as `contact_telephone`,
				`queries`.`addinfo-cellphone` as `contact_cellphone`,
				`queries`.`description-open` as `description`,
				`queries`.`description-close` as `close_reason`,
				`queries`.`querynumber` as `number`,
				`queries`.`query_inspection` as `inspection`, 
				`houses`.`housenumber` as `house_number`,
				`streets`.`name` as `street_name`,
				`query_worktypes`.`name` as `work_type_name`,
				`departments`.`name` as `department_name`
				FROM `queries`, `houses`, `streets`, `query_worktypes`, `departments`
				WHERE `queries`.`company_id` = :company_id
				AND `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
				AND `opentime` > :time_open
				AND `opentime` <= :time_close AND `departments`.`company_id` = :company_id
				AND `queries`.`department_id` = `departments`.`id`");
			$sql->bind(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$sql->bind(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all'){
				$sql->query(" AND `queries`.`status` = :status");
				if(!in_array($query->status, ['open', 'close', 'working', 'reopen']))
					throw new e_model('Невозможный статус заявки.');
				$sql->bind(':status', $query->status, PDO::PARAM_STR);
			}
			if(!empty($query->street_id)){
				$sql->query(" AND `houses`.`street_id` = :street_id");
				$sql->bind(':street_id', $query->street_id, PDO::PARAM_INT);
			}
			if(!empty($query->house_id)){
				$sql->query(" AND `queries`.`house_id` = :house_id");
				$sql->bind(':house_id', $query->house_id, PDO::PARAM_INT);
			}
			if(!empty($query->worktype_id)){
				$sql->query(" AND `queries`.`query_worktype_id` = :worktype_id");
				$sql->bind(':worktype_id', $query->worktype_id, PDO::PARAM_INT);
			}
			if(!empty($query->department_id)){
				if(is_array($query->department_id))
					$departments = $query->department_id;
				else
					$departments[] = $query->department_id;
				foreach($departments as $key => $department){
					$p_departments[] = ':department_id'.$key;
					$sql->bind(':department_id'.$key, $department, PDO::PARAM_INT);
				}
				$sql->query(" AND `queries`.`department_id` IN(".implode(',', $p_departments).")");
			}
			$sql->query(" ORDER BY `queries`.`opentime` DESC");
		}
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		return $sql->map(new data_query(), 'Проблема при выборке пользователей.');
	}

	/**
	* Возвращает лицевые счета заявки.
	* @return array
	*/
	public static function get_numbers(data_company $company, data_query $query){
		$sql = new sql();
		if(!empty($query->id)){
			$sql->query("SELECT `query2number`.`query_id`, `query2number`.`default`, `numbers`.`id`,
				`numbers`.`fio`, `numbers`.`number`, `flats`.`flatnumber` as `flat_number`
				FROM `query2number`, `numbers`, `flats`
				WHERE `query2number`.`company_id` = :company_id
				AND `numbers`.`company_id` = :company_id
				AND `numbers`.`id` = `query2number`.`number_id`
				AND `numbers`.`flat_id` = `flats`.`id`
				AND `query2number`.`query_id` = :id
				ORDER BY (`flats`.`flatnumber` + 0)");
			$sql->bind(':id', $query->id, PDO::PARAM_INT);
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
			$sql->query("SELECT `query2user`.`query_id`, `query2user`.`class`, `users`.`id`,
				`users`.`firstname`, `users`.`lastname`, `users`.`midlename`
				FROM `query2user`, `users`
				WHERE `query2user`.`company_id` = :company_id
				AND `users`.`id` = `query2user`.`user_id`
				AND `query2user`.`query_id` = :id");
			$sql->bind(':id', $query->id, PDO::PARAM_INT);
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
			$sql->query("SELECT `query2work`.`query_id`, `query2work`.`opentime` as `time_open`,
				`query2work`.`closetime` as `time_close`, `query2work`.`value`,
				`works`.`id`, `works`.`name`
				FROM `query2work`, `works`
				WHERE `query2work`.`company_id` = :company_id
				AND `works`.`company_id` = :company_id
				AND `works`.`id` = `query2work`.`work_id`
				AND `query2work`.`query_id` = :id");
			$sql->bind(':id', $query->id, PDO::PARAM_INT);
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

	/*
	* Учитывает сессионный фильтры.
	*/
	public static function build_query_params(data_query $query, data_query $query_filter = null, stdClass $restrictions){
		$time = getdate();
		if(!$query_filter instanceof data_query){
			$query = new data_query();
			$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
			$query->time_open['end'] = $query->time_open['begin'] + 86399;
		}
		if(empty($query->time_open))
			$query->time_open = $query_filter->time_open;
		if(empty($query->status))
			$query->status = $query_filter->status;
		// обработка улиц
		if($query->street_id === 'all')
			$query->street_id = null;
		elseif(empty($query->street_id))
			$query->street_id = $query_filter->street_id;
		// обработка дома
		if(!empty($query->street_id)){
			if(empty($query->house_id))
				$query->house_id = $query_filter->house_id;
			else
				if($query->house_id === 'all')
					$query->house_id = null;
		}else
			$query->house_id = null;
		// обработка участка
		if(empty($query->department_id)){
			if(empty($query_filter->department_id))
				$query->department_id = $restrictions->departments;
			else
				$query->department_id = $query_filter->department_id;
		}else{
			if($query->department_id === 'all')
				$query->department_id = $restrictions->departments;
			else
				if(!empty($restrictions->departments))
					if(array_search($query->department_id, $restrictions->departments) === false)
						$query->department_id = $restrictions->departments;
		}
		// обработка типа работ
		if($query->worktype_id === 'all')
			$query->worktype_id = null;
		elseif(empty($query->worktype_id))
			$query->worktype_id = $query_filter->worktype_id;
		return $query;
	}

	/**
	* Удаляет пользователя из заявки.
	*/
	public function remove_user($query_id, $user_id, $class){
		if(array_search($class, ['manager', 'performer']) === false)
			throw new e_model('Несоответствующие параметры: class.');
		$query = $this->get_query($query_id);
		$model = new model_user();
		$user = $model->get_user($user_id);
		$sql = new sql();
		$sql->query("DELETE FROM `query2user`
					WHERE `company_id` = :company_id AND `query_id` = :query_id
					AND `user_id` = :user_id AND `class` = :class");
		$sql->bind(':query_id', $query->id, PDO::PARAM_STR);
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
		$sql->bind(':class', $class, PDO::PARAM_STR);
		$sql->execute('Ошибка при удалении пользователя и заявки.');
		return $query;
	}

	/**
	* Обновляет работу из заявки.
	*/
	public function remove_work($query_id, $work_id){
		$query = $this->get_query($query_id);
		$work = new data_work();
		$work->id = $work_id;
		$work->verify('id');
		$work = model_work::get_works($this->company, $work)[0];
		model_work::is_data_work($work);
		$sql = new sql();
		$sql->query("DELETE FROM `query2work`
					WHERE `company_id` = :company_id AND `query_id` = :query_id
					AND `work_id` = :work_id");
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->bind(':work_id', $work->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при удалении работы из заявки.');
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
	public function update_work_type($id, $type){
		$query_work_type_params = new data_query_work_type();
		$query_work_type_params->id = $type;
		$query_work_type = model_query_work_type::get_query_work_types($this->company,
																										$query_work_type_params)[0];
		model_query_work_type::is_data_query_work_type($query_work_type);
		$query = $this->get_query($id);
		$query->set_work_type_id($query_work_type->id);
		$query->set_work_type_name($query_work_type->name);
		$mapper = new mapper_query($this->company);
		return $mapper->update($query);
	}

	/**
	* Верификация типа объекта заявки.
	*/
	public static function is_data_query($query){
		if(!($query instanceof data_query))
			throw new e_model('Возвращеный объект не является заявкой.');
	}
}
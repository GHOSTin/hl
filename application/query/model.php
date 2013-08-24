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
	public static function add_user(data_company $company, data_query $query_params,
									data_user $user_params, $class){
		$company->verify('id');
		$query_params->verify('id');
		$user_params->verify('id');
		if(array_search($class, ['manager', 'performer']) === false)
			throw new e_model('Несоответствующие параметры: class.');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		$user = model_user::get_users($user_params)[0];
		model_user::is_data_user($user);
		$sql = new sql();
		$sql->query("INSERT INTO `query2user` (`query_id`, `user_id`, `company_id`,
				 `class`, `protect`) VALUES (:query_id, :user_id, :company_id,
				 :class, :protect)");
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':class', $class, PDO::PARAM_STR);
		$sql->bind(':protect', 'false', PDO::PARAM_STR);
		$sql->execute('Ошибка при добавлении пользователя.');
		return [$query];
	}

	/**
	* Добавляет ассоциацию заявка-работа.
	*/
	public static function add_work(data_company $company, data_query $query_params,
										data_work $work_params, $begin_time, $end_time){
		if(!is_int($begin_time) OR !is_int($end_time))
			throw new e_model('Время задано не верно.');
		if($begin_time > $end_time)
			throw new e_model('Время начала работы не может быть меньше времени закрытия.');
		$company->verify('id');
		$query_params->verify('id');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		$work_params->verify('id');
		$work = model_work::get_works($company, $work_params)[0];
		model_work::is_data_work($work);
		$sql = new sql();
		$sql->query("INSERT INTO `query2work` (`query_id`, `work_id`, `company_id`,
					 `opentime`, `closetime`) VALUES (:query_id, :work_id, :company_id,
					 :time_open, :time_close)");
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->bind(':work_id', $work_params->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':time_open', $begin_time, PDO::PARAM_INT);
		$sql->bind(':time_close', $end_time, PDO::PARAM_INT);
		$sql->execute('Ошибка при добавлении работы.');
		return [$query];
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
	public static function close_query(data_company $company, data_query $query_params){
		$query_params->verify('id');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		if($query->status === 'close')
			throw new e_model('Заявка уже закрыта.');
		$query->status = 'close';
		$query->close_reason = $query_params->close_reason;
		$query->time_close = time();
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `description-close` = :reason,
				`status` = :status, `closetime` = :time_close
				WHERE `company_id` = :company_id AND `id` = :query_id");
		$sql->bind(':reason', $query->close_reason, PDO::PARAM_STR);
		$sql->bind(':status', $query->status, PDO::PARAM_STR);
		$sql->bind(':time_close', $query->time_close, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при закрытии заявки.');
		return [$query];
	}

	/**
	* Закрывает заявку.
	*/
	public static function reclose_query(data_company $company, data_query $query_params){
		$query_params->verify('id');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		if($query->status !== 'reopen')
			throw new e_model('Заявка не может быть перезакрыта.');
		$query->status = 'close';
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `status` = :status
				WHERE `company_id` = :company_id AND `id` = :query_id");
		$sql->bind(':status', $query->status, PDO::PARAM_STR);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при перезакрытии заявки.');
		return [$query];
	}

	/**
	* Переоткрывает заявку.
	*/
	public static function reopen_query(data_company $company, data_query $query_params){
		$query_params->verify('id');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		if($query->status !== 'close')
			throw new e_model('Заявка не в том статусе чтобы её можно было переоткрыть.');
		$query->status = 'reopen';
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `status` = :status
					WHERE `company_id` = :company_id AND `id` = :query_id");
		$sql->bind(':status', $query->status, PDO::PARAM_STR);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при переоткрытии заявки.');
		return [$query];
	}

	/**
	* Передает заявку в работу.
	*/
	public static function to_working_query(data_company $company, data_query $query_params){
		$company->verify('id');
		$query_params->verify('id');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		if($query->status !== 'open')
			throw new e_model('Заявка имеет статус не позволяющий её передать в работу.');
		$query->status = 'working';
		$query->time_work = time();
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `status` = :status, `worktime` = :time_work
					 WHERE `company_id` = :company_id AND `id` = :query_id");
		$sql->bind(':status', $query->status, PDO::PARAM_STR);
		$sql->bind(':time_work', $query->time_work, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при передачи в работу заявки.');
		return [$query];
	}

	/**
	* Создает новую заявку.
	* @retrun array из data_query
	*/
	public static function create_query(data_company $company, data_query $query, $initiator, 
				data_query_work_type $query_work_type_params, data_current_user $current_user){
		try{
			sql::begin();
			if(empty($query->description))
				throw new e_model('Пустое описание заявки.');
			if($initiator instanceof data_house){
				$initiator->verify('id');
				$initiator = model_house::get_houses($initiator)[0];
			}elseif($initiator instanceof data_number){
				$initiator->verify('id');
				$model = new model_number($company);
				$initiator = $model->get_number($initiator->id);
			}
			if(!($initiator instanceof data_number OR $initiator instanceof data_house))
				throw new e_model('Инициатор не корректен.');
			if($initiator instanceof data_house){
				$query->initiator = 'house';
				$query->house_id = $initiator->id;
			}else{
				$query->initiator = 'number';
				$query->house_id = $initiator->house_id;
			}
			$query_work_type = model_query_work_type::get_query_work_types($company, $query_work_type_params)[0];
			model_query_work_type::is_data_query_work_type($query_work_type);
			$query->worktype_id = $query_work_type->id;
			if(!is_int($query->id = self::get_insert_id($company)))
				throw new e_model('Не был получени query_id для вставки.');
			$time = time();
			if(!is_int($query->number = self::get_insert_query_number($company, $time)))
				throw new e_model('Инициатор не был сформирован.');
			$query = self::__add_query($company, $query, $initiator, $time);
			if(!($query instanceof data_query))
				throw new e_model('Не корректный объект query');
			self::add_numbers($company, $query, $initiator);
			self::__add_user($company, $query, $current_user, 'creator');
			self::__add_user($company, $query, $current_user, 'manager');
			self::__add_user($company, $query, $current_user, 'observer');
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

	/*
	* Возвращает следующий для вставки идентификатор заявки.
	*/
	private static function get_insert_id(data_company $company){
		$company->verify('id');
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_query_id` FROM `queries`
				WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->execute('Проблема при опредении следующего query_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего query_id.');
		$query_id = (int) $sql->row()['max_query_id'] + 1;
		$sql->close();
		return $query_id;
	}

	/*
	* Возвращает следующий для вставки номер заявки.
	*/
	private static function get_insert_query_number(data_company $company, $time){
		$company->verify('id');
		$time = getdate($time);
		$sql = new sql();
		$sql->query("SELECT MAX(`querynumber`) as `querynumber` FROM `queries`
					WHERE `opentime` > :begin AND `opentime` <= :end
					AND `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':begin', mktime(0, 0, 0, 1, 1, $time['year']), PDO::PARAM_INT);
		$sql->bind(':end', mktime(23, 59, 59, 12, 31, $time['year']), PDO::PARAM_INT);
		$sql->execute('Проблема при опредении следующего querynumber.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего querynumber.');
		$query_number = (int) $sql->row()['querynumber'] + 1;
		$sql->close();
		return $query_number;
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
	public static function remove_user(data_company $company, data_query $query_params, data_user $user_params, $class){
		$company->verify('id');
		$query_params->verify('id');
		$user_params->verify('id');
		if(array_search($class, ['manager', 'performer']) === false)
			throw new e_model('Несоответствующие параметры: class.');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		$user = model_user::get_users($user_params)[0];
		model_user::is_data_user($user);
		$sql = new sql();
		$sql->query("DELETE FROM `query2user`
					WHERE `company_id` = :company_id AND `query_id` = :query_id
					AND `user_id` = :user_id AND `class` = :class");
		$sql->bind(':query_id', $query->id, PDO::PARAM_STR);
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':class', $class, PDO::PARAM_STR);
		$sql->execute('Ошибка при удалении пользователя и заявки.');
		return [$query];
	}

	/**
	* Обновляет работу из заявки.
	*/
	public static function remove_work(data_company $company, data_query $query_params, data_work $work_params){
		$company->verify('id');
		$query_params->verify('id');
		$work_params->verify('id');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		$work = model_work::get_works($company, $work_params)[0];
		model_work::is_data_work($work);
		$sql = new sql();
		$sql->query("DELETE FROM `query2work`
					WHERE `company_id` = :company_id AND `query_id` = :query_id
					AND `work_id` = :work_id");
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->bind(':work_id', $work->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при удалении работы из заявки.');
		return [$query];
	}

	/**
	* Обновляет описание заявки.
	*/
	public static function update_description(data_company $company, data_query $query){
		$company->verify('id');
		$query->verify('id', 'description');
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `description-open` = :description
					WHERE `company_id` = :company_id AND `id` = :query_id");
		$sql->bind(':description', $query->description, PDO::PARAM_STR);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при обновлении описания заявки.');
		return [$query];
	}

	/**
	* Обновляет описание заявки.
	*/
	public static function update_reason(data_company $company, data_query $query){
		$company->verify('id');
		$query->verify('id', 'close_reason');
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `description-close` = :reason
					WHERE `company_id` = :company_id AND `id` = :query_id");
		$sql->bind(':reason', $query->close_reason, PDO::PARAM_STR);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при обновлении причины закрытия заявки.');
		return [$query];
	}

	/**
	* Обновляет контактную информацию.
	*/
	public static function update_contact_information(data_company $company, data_query $query){
		$company->verify('id');
		$query->verify('id');
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `addinfo-name` = :fio, 
					`addinfo-telephone` = :telephone, `addinfo-cellphone` = :cellphone 
					WHERE `company_id` = :company_id AND `id` = :query_id");
		$sql->bind(':fio', $query->contact_fio, PDO::PARAM_STR);
		$sql->bind(':telephone', $query->contact_telephone, PDO::PARAM_STR);
		$sql->bind(':cellphone', $query->contact_cellphone, PDO::PARAM_STR);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при обновлении описания заявки.');
		return [$query];
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
	public static function update_warning_status(data_company $company, data_query $query_params){
		$company->verify('id');
		$query_params->verify('id');
		if(array_search($query_params->warning_status, ['hight', 'normal', 'planned']) === false)
			throw new e_model('Несоответствующие параметры: payment_status.');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		$query->warning_status = $query_params->warning_status;
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `warning-type` = :warning_status
					WHERE `company_id` = :company_id AND `id` = :id");
		$sql->bind(':warning_status', $query->warning_status, PDO::PARAM_STR);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при обновлении статуса реакции.');
		return [$query];
	}
	
	/**
	* Обновляет тип работ.
	*/
	public static function update_work_type(data_company $company, data_query $query_params){
		$company->verify('id');
		$query_params->verify('id', 'work_type_id');
		$query_params->verify('work_type_id');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		$query_work_type_params = new data_query_work_type();
		$query_work_type_params->id = $query_params->worktype_id;
		$query_work_type = model_query_work_type::get_query_work_types($company, $query_work_type_params)[0];
		model_query_work_type::is_data_query_work_type($query_work_type);
		$query->worktype_id = $query_work_type->id;
		$query->work_type_name = $query_work_type->name;
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `query_worktype_id` = :work_type_id
					WHERE `company_id` = :company_id AND `id` = :id");
		$sql->bind(':work_type_id', $query->worktype_id, PDO::PARAM_STR);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при обновлении типа работ.');
		return [$query];
	}

	/**
	* Верификация типа объекта заявки.
	*/
	public static function is_data_query($query){
		if(!($query instanceof data_query))
			throw new e_model('Возвращеный объект не является заявкой.');
	}
}
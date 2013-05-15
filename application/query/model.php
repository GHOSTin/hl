<?php
class model_query{
	/*
	* Зависимая функция.
	* Добавляет ассоциацию заявка-лицевой_счет.
	*/
	private static function __add_number(data_company $company, data_query $query,
											data_number $number, $default){
		self::verify_id($query);
		model_company::verify_id($company);
		model_number::verify_id($number);
		if(array_search($default, ['true', 'false']) === false)
			throw new e_model('Тип лицевого счета задан не верно.');
		$sql = new sql();
		$sql->query("INSERT INTO `query2number` (`query_id`, `number_id`, `company_id`, `default`) 
					VALUES (:query_id, :number_id, :company_id, :default)");
		$sql->bind(':query_id', $query->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':default', $default, PDO::PARAM_STR);
		$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
		$sql->execute('Проблема при добавлении лицевого счета.');
	}
	/*
	* Зависимая функция.
	* Добавляет ассоциацию заявка-пользователь.
	*/
	private static function __add_user(data_company $company, data_query $query, data_user $user,  $class){
		self::verify_id($query);
		model_company::verify_id($company);
		model_user::verify_id($user);
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
		model_company::verify_id($company);
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
		self::verify_id($query);
		self::verify_company_id($query);
		self::verify_status($query);
		self::verify_initiator($query);
		self::verify_payment_status($query);
		self::verify_warning_status($query);
		self::verify_department_id($query);
		self::verify_house_id($query);
		self::verify_work_type_id($query);
		self::verify_time_open($query);
		self::verify_time_work($query);
		self::verify_contact_fio($query);
		self::verify_contact_telephone($query);
		self::verify_contact_cellphone($query);
		self::verify_description($query);
		self::verify_number($query);
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
		model_company::verify_id($company);
		self::verify_id($query_params);
		model_user::verify_id($user_params);
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
		model_company::verify_id($company);
		self::verify_id($query_params);
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		model_work::verify_id($query_params);
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
			model_house::verify_id($initiator);
			$numbers = model_house::get_numbers($company, $initiator);
			$default = 'false';
		}elseif($initiator instanceof data_number){
			model_number::verify_id($initiator);
			$numbers[] = model_number::get_numbers($company, $initiator)[0];
			$default = 'true';
		}else
			throw new e_model('Не подходящий тип инициатора.');
		if(count($numbers) < 1)
			throw new e_model('Не соответсвующее количество лицевых счетов.');
		foreach($numbers as $number){
			self::__add_number($company, $query, $number, $default);
		}
	}
	/**
	* Закрывает заявку.
	*/
	public static function close_query(data_company $company, data_query $query_params){
		self::verify_id($query_params);
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
	* Передает заявку в работу.
	*/
	public static function to_working_query(data_company $company, data_query $query_params){
		model_company::verify_id($company);
		self::verify_id($query_params);
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
				model_house::verify_id($initiator);
				$initiator = model_house::get_houses($initiator)[0];
			}elseif($initiator instanceof data_number){
				model_number::verify_id($initiator);
				$initiator = model_number::get_numbers($company, $initiator)[0];
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
		model_company::verify_id($company);
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
		model_company::verify_id($company);
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
	/**
	* Возвращает заявки.
	* @return array
	*/
	public static function get_queries(data_company $company, data_query $query){
		model_company::verify_id($company);
 		$sql = new sql();
		if(!empty($query->id)){
			self::verify_id($query);
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
				`query_worktypes`.`name` as `work_type_name`
				FROM `queries`, `houses`, `streets`, `query_worktypes`
				WHERE `queries`.`company_id` = :company_id
				AND `queries`.`house_id` = `houses`.`id`
				AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`id` = :id");
			$sql->bind(':id', $query->id, PDO::PARAM_INT);
		}elseif(!empty($query->number)){
			self::verify_number($query);
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
				`query_worktypes`.`name` as `work_type_name`
				FROM `queries`, `houses`, `streets`, `query_worktypes`
				WHERE `queries`.`company_id` = :company_id
				AND `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
				AND `querynumber` = :number
				ORDER BY `opentime` DESC");
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
				`query_worktypes`.`name` as `work_type_name`
				FROM `queries`, `houses`, `streets`, `query_worktypes`
				WHERE `queries`.`company_id` = :company_id
				AND `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
				AND `opentime` > :time_open
				AND `opentime` <= :time_close");
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
			$sql->query(" ORDER BY `opentime` DESC");
			$sql->bind(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$sql->bind(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all'){
				$sql->query(" AND `queries`.`status` = :status");
				$sql->bind(':status', $query->status, PDO::PARAM_STR);
			}
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
		return $query;
	}
	/**
	* Удаляет пользователя из заявки.
	*/
	public static function remove_user(data_company $company, data_query $query_params, data_user $user_params, $class){
		model_company::verify_id($company);
		self::verify_id($query_params);
		model_user::verify_id($user_params);
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
		model_company::verify_id($company);
		self::verify_id($query_params);
		model_work::verify_id($work_params);
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
		model_company::verify_id($company);
		self::verify_id($query);
		self::verify_description($query);
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
	* Обновляет контактную информацию.
	*/
	public static function update_contact_information(data_company $company, data_query $query){
		model_company::verify_id($company);
		self::verify_id($query);
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
	public static function update_payment_status(data_company $company, data_query $query_params){
		model_company::verify_id($company);
		self::verify_id($query_params);
		if(array_search($query_params->payment_status, ['paid', 'unpaid', 'recalculation']) === false)
			throw new e_model('Несоответствующие параметры: payment_status.');
		$query = self::get_queries($company, $query_params)[0];
		self::is_data_query($query);
		$query->payment_status = $query_params->payment_status;
		$sql = new sql();
		$sql->query("UPDATE `queries` SET `payment-status` = :payment_status
					WHERE `company_id` = :company_id AND `id` = :id");
		$sql->bind(':payment_status', $query->payment_status, PDO::PARAM_STR);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':id', $query->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при обновлении статуса оплаты заявки.');
		return [$query];
	}
	/**
	* Обновляет статус реакции.
	*/
	public static function update_warning_status(data_company $company, data_query $query_params){
		model_company::verify_id($company);
		self::verify_id($query_params);
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
		model_company::verify_id($company);
		self::verify_id($query_params);
		self::verify_work_type_id($query_params);
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
	* Верификация идентификатора заявки.
	*/
	public static function verify_id(data_query $query){
		if($query->id < 1)
			throw new e_model('Идентификатор заявки задан не верно.');
	}
	/**
	* Верификация статуса заявки.
	*/
	public static function verify_status(data_query $query){
		if(empty($query->status))
			throw new e_model('Статус заявки задан не верно.');
	}
	/**
	* Верификация инициатора заявки.
	*/
	public static function verify_initiator(data_query $query){
		if(empty($query->initiator))
			throw new e_model('Инициатор заявки задан не верно.');
	}
	/**
	* Верификация статуса оплаты заявки.
	*/
	public static function verify_payment_status(data_query $query){
		if(empty($query->payment_status))
			throw new e_model('Статус оплаты заявки задан не верно.');
	}
	/**
	* Верификация ворнинга заявки.
	*/
	public static function verify_warning_status(data_query $query){
		if(empty($query->warning_status))
			throw new e_model('Статус ворнинга заявки задан не верно.');
	}
	/**
	* Верификация идентификатора участка.
	*/
	public static function verify_department_id(data_query $query){
		if($query->department_id < 1)
			throw new e_model('Идентификатор участка задан не верно.');
	}
	/**
	* Верификация идентификатора дома.
	*/
	public static function verify_house_id(data_query $query){
		if($query->house_id < 1)
			throw new e_model('Идентификатор дома задан не верно.');
	}
	/**
	* Верификация идентификатора причины закрытия.
	*/
	public static function verify_close_reason_id(data_query $query){
		if($query->close_reason_id < 1)
			throw new e_model('Идентификатор причины закрытия задан не верно.');
	}
	/**
	* Верификация идентификатора причины закрытия.
	*/
	public static function verify_work_type_id(data_query $query){
		if($query->worktype_id < 1)
			throw new e_model('Идентификатор типа заявки задан не верно.');
	}
	/**
	* Верификация название типа работы заявки.
	*/
	public static function verify_work_type_name(data_query $query){
		if(empty($query->work_type_name))
			throw new e_model('Название типа работы заявки задано не верно.');
	}
	/**
	* Верификация времени открытия заявки.
	*/
	public static function verify_time_open(data_query $query){
		if($query->time_open < 0)
			throw new e_model('Время открытия заявки задано не верно.');
	}
	/**
	* Верификация времени передачи в работу заявки.
	*/
	public static function verify_time_work(data_query $query){
		if($query->time_work < 0)
			throw new e_model('Время передачи в работу заявки задано не верно.');
	}
	/**
	* Верификация времени закрытия заявки.
	*/
	public static function verify_time_close(data_query $query){
		if($query->time_close < 0)
			throw new e_model('Время закрытия заявки задано не верно.');
	}
	/**
	* Верификация ФИО контакта заявки.
	*/
	public static function verify_contact_fio(data_query $query){
	}
	/**
	* Верификация телефона контакта заявки.
	*/
	public static function verify_contact_telephone(data_query $query){
	}
	/**
	* Верификация сотового телефона контакта заявки.
	*/
	public static function verify_contact_cellphone(data_query $query){
	}
	/**
	* Верификация описания заявки.
	*/
	public static function verify_description(data_query $query){
		if(empty($query->description))
			throw new e_model('Описание заявки заданы не верно.');
	}
	/**
	* Верификация причины закрытия заявки.
	*/
	public static function verify_close_reason(data_query $query){
		if(empty($query->close_reason))
			throw new e_model('Описание закрытия заявки заданы не верно.');
	}
	/**
	* Верификация номера заявки.
	*/
	public static function verify_number(data_query $query){
		if($query->number < 0)
			throw new e_model('Номер заявки задан не верно.');
	}
	/**
	* Верификация инспеции заявки.
	*/
	public static function verify_inspection(data_query $query){
		if(empty($query->inspection))
			throw new e_model('Инспекция заявки задана не верно.');
	}
	/**
	* Верификация номера дома.
	*/
	public static function verify_house_number(data_query $query){
		if(empty($query->house_number))
			throw new e_model('Номер дома задан не верно.');
	}
	/**
	* Верификация названия улицы.
	*/
	public static function verify_street_name(data_query $query){
		if(empty($query->street_name))
			throw new e_model('Название улицы задано не верно.');
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_query $query){
		if($query->company_id < 0)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентификатора улицы.
	*/
	public static function verify_street_id(data_query $query){
		if($query->street_id < 0)
			throw new e_model('Идентификатор улицы задан не верно.');
	}
	/**
	* Верификация типа объекта заявки.
	*/
	public static function is_data_query($query){
		if(!($query instanceof data_query))
			throw new e_model('Возвращеный объект не является заявкой.');
	}
}
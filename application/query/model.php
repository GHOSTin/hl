<?php
class model_query{
	/*
	* Зависимая функция.
	* Добавляет ассоциацию заявка-лицевой_счет.
	*/
	private static function __add_number(data_query $query, data_number $number, $default, data_user $current_user){
		self::verify_query_id($query);
		model_user::verify_user_company_id($current_user);
		model_number::verify_number_id($number);
		$sql = "INSERT INTO `query2number` (`query_id`, `number_id`, `company_id`, `default`) 
				VALUES (:query_id, :number_id, :company_id, :default)";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':default', $default, PDO::PARAM_STR);
		$stm->bindValue(':number_id', $number->id, PDO::PARAM_INT);
		if($stm->execute() === false)
			throw new e_model('Проблема при добавлении лицевого счета.');
	}
	/*
	* Зависимая функция.
	* Добавляет ассоциацию заявка-пользователь.
	*/
	private static function __add_user(data_query $query, data_user $current_user, $class){
		self::verify_query_id($query);
		model_user::verify_user_company_id($current_user);
		model_user::verify_user_id($current_user);
		model_number::verify_number_id($number);
		if(array_search($class, ['creator', 'observer', 'manager', 'performer']) === false)
			throw new e_model('Не соответсвует тип пользователя.');
		$sql = "INSERT INTO `query2user` (`query_id`, `user_id`, `company_id`, `class`)
				VALUES (:query_id, :user_id, :company_id, :class)";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':user_id', $current_user->id, PDO::PARAM_INT);
		$stm->bindValue(':class', $class, PDO::PARAM_STR);
		if($stm->execute() === false)
			throw new e_model('Проблема при добавлении пользователя.');
	}
	/*
	* Зависимая функция.
	* Создает заявку и записывает в лог заявки.
	*/
	private static function __add_query(data_query $query, $initiator, data_user $current_user, $time){
		model_user::verify_user_company_id($current_user);
		if(!($initiator instanceof data_house OR $initiator instanceof data_number))
			throw new e_model('Инициатор не верного формата.');
		if(empty($initiator->department_id))
			throw new e_model('department_id не указан.');
		if(!is_int($time))
			throw new e_model('Неверная дата.');
		$query->company_id = $current_user->company_id;
		$query->status = 'open';
		$query->payment_status = 'unpaid';
		$query->warning_status = 'normal';
		$query->department_id = $initiator->department_id;
		$query->time_open = $query->time_work = $time;
		$sql = "INSERT INTO `queries` (
					`id`, `company_id`, `status`, `initiator-type`,
					`payment-status`, `warning-type`, `department_id`,
					`house_id`, `query_worktype_id`,
					`opentime`, `worktime`, `addinfo-name`,
					`addinfo-telephone`, `addinfo-cellphone`,
					`description-open`, 
					`querynumber`
				) VALUES (
					:id, :company_id, :status, :initiator, :payment_status, 
					:warning_status,
					:department_id, :house_id, :worktype_id, :time_open,
					:time_work, :contact_fio, :contact_telephone,
					:contact_cellphone, :description, :number
				);";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':id', $query->id, PDO::PARAM_INT);
		$stm->bindParam(':company_id', $query->company_id, PDO::PARAM_INT, 3);
		$stm->bindParam(':status', $query->status, PDO::PARAM_STR);
		$stm->bindParam(':initiator', $query->initiator, PDO::PARAM_STR);
		$stm->bindParam(':payment_status', $query->payment_status, PDO::PARAM_STR);
		$stm->bindParam(':warning_status', $query->warning_status, PDO::PARAM_STR);
		$stm->bindParam(':department_id', $query->department_id, PDO::PARAM_INT);
		$stm->bindParam(':house_id', $query->house_id, PDO::PARAM_INT);
		$stm->bindParam(':worktype_id', $query->worktype_id, PDO::PARAM_INT);
		$stm->bindParam(':time_open', $query->time_open, PDO::PARAM_INT);
		$stm->bindParam(':time_work', $query->time_work, PDO::PARAM_INT);
		$stm->bindParam(':contact_fio', $query->contact_fio, PDO::PARAM_STR);
		$stm->bindParam(':contact_telephone', $query->contact_telephone, PDO::PARAM_STR);
		$stm->bindParam(':contact_cellphone', $query->contact_cellphone, PDO::PARAM_STR);
		$stm->bindParam(':description', $query->description, PDO::PARAM_STR);
		$stm->bindParam(':number', $query->number, PDO::PARAM_INT);
		if($stm->execute() === false)
			throw new e_model('Проблемы при создании заявки.');
		return $query;
	}
	/**
	* Добавляет ассоциацию заявка-пользователь.
	*/
	public static function add_user(data_query $query_params, data_user $user_params, $class, data_user $current_user){
		self::verify_query_id($query_params);
		model_user::verify_user_id($user_params);
		if(array_search($class, ['manager', 'performer']) === false)
			throw new e_model('Несоответствующие параметры: class.');
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		$user = model_user::get_users($user_params)[0];
		model_user::is_data_user($user);
		$sql = 'INSERT INTO `query2user` (`query_id`, `user_id`, `company_id`,
				 `class`, `protect`) VALUES (:query_id, :user_id, :company_id,
				 :class, :protect)';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		$stm->bindValue(':user_id', $user->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':class', $class, PDO::PARAM_STR);
		$stm->bindValue(':protect', 'false', PDO::PARAM_STR);
		if($stm->execute() == false)
			throw new e_model('Ошибка при добавлении пользователя.');
		return [$query];
	}
	/**
	* Добавляет ассоциацию заявка-работа.
	*/
	public static function add_work(data_query $query_params, data_work $work_params, $begin_time, $end_time, data_user $current_user){
		self::verify_query_id($query_params);
		if(!is_int($begin_time) OR !is_int($end_time))
			throw new e_model('Время задано не верно.');
		if($begin_time > $end_time)
			throw new e_model('Время начала работы не может быть меньше времени закрытия.');
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		$work = model_work::get_works($work_params, $current_user)[0];
		self::is_data_work($work);
		$sql = 'INSERT INTO `query2work` (`query_id`, `work_id`, `company_id`,
				 `opentime`, `closetime`) VALUES (:query_id, :work_id, :company_id,
				 :time_open, :time_close)';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		$stm->bindValue(':work_id', $work_params->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':time_open', $begin_time, PDO::PARAM_INT);
		$stm->bindValue(':time_close', $end_time, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при добавлении работы.');
		return [$query];
	}
	/*
	* Зависимая функция.
	* Добавляет ассоциацию заявка-лицевой_счет в зависимости от типа инициатора.
	*/
	private static function add_numbers(data_query $query, $initiator, data_user $current_user){
		if($initiator instanceof data_house){
			$numbers = model_house::get_numbers($initiator);
			$default = 'false';
		}elseif($initiator instanceof data_number){
			$numbers[] = model_number::get_number($initiator);
			$default = 'true';
		}else
			throw new e_model('Не подходящий тип инициатора.');
		if(count($numbers) < 1)
			throw new e_model('Не соответсвующее количество лицевых счетов.');
		foreach($numbers as $number){
			self::__add_number($query, $number, $default, $current_user);
		}
	}
	/**
	* Закрывает заявку.
	*/
	public static function close_query(data_query $query_params, data_user $current_user){
		self::verify_query_id($query_params);
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		if($query->status === 'close')
			throw new e_model('Заявка уже закрыта.');
		$query->status = 'close';
		$query->close_reason = $query_params->close_reason;
		$query->time_close = time();
		$sql = "UPDATE `queries` SET `description-close` = :reason,
				`status` = :status, `closetime` = :time_close
				WHERE `company_id` = :company_id AND `id` = :query_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':reason', $query->close_reason, PDO::PARAM_STR);
		$stm->bindValue(':status', $query->status, PDO::PARAM_STR);
		$stm->bindValue(':time_close', $query->time_close, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при закрытии заявки.');
		return [$query];
	}
	/**
	* Передает заявку в работу.
	*/
	public static function to_working_query(data_query $query_params, data_user $current_user){
		self::verify_query_id($query_params);
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		if($query->status !== 'open')
			throw new e_model('Заявка имеет статус не позволяющий её передать в работу.');
		$query->status = 'working';
		$query->time_work = time();
		$sql = "UPDATE `queries` SET `status` = :status, `worktime` = :time_work
				WHERE `company_id` = :company_id AND `id` = :query_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':status', $query->status, PDO::PARAM_STR);
		$stm->bindValue(':time_work', $query->time_work, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при передачи в работу заявки.');
		return [$query];
	}
	/**
	* Создает новую заявку.
	* @retrun array из data_query
	*/
	public static function create_query(data_query $query, $initiator, 
		data_query_work_type $query_work_type_params, data_user $current_user){
		try{
			db::get_handler()->beginTransaction();
			if(empty($query->description))
				throw new e_model('Пустое описание заявки.');
			if($initiator instanceof data_house)
				$initiator = model_house::get_house($initiator);
			elseif($initiator instanceof data_number)
				$initiator = model_number::get_number($initiator);
			if(!($initiator instanceof data_number OR $initiator instanceof data_house))
				throw new e_model('Инициатор не корректен.');
			if($initiator instanceof data_house){
				$query->initiator = 'house';
				$query->house_id = $initiator->id;
			}else{
				$query->initiator = 'number';
				$query->house_id = $initiator->house_id;
			}
			$query_work_type = model_query_work_type::get_query_work_types($query_work_type_params, $current_user)[0];
			if(!($query_work_type instanceof data_query_work_type))
				throw new e_model('Тип работ заявки задан не верно.');
			$query->worktype_id = $query_work_type->id;
			if(!is_int($query->id = self::get_insert_id($current_user)))
				throw new e_model('Не был получени query_id для вставки.');
			$time = time();
			if(!is_int($query->number = self::get_insert_query_number($current_user, $time)))
				throw new e_model('Инициатор не был сформирован.');
			$query = self::__add_query($query, $initiator, $current_user, $time);
			if(!($query instanceof data_query))
				throw new e_model('Не корректный объект query');
			self::add_numbers($query, $initiator, $current_user);
			self::__add_user($query, $current_user, 'creator');
			self::__add_user($query, $current_user, 'manager');
			self::__add_user($query, $current_user, 'observer');
			db::get_handler()->commit();
			return $query;
		}catch(exception $e){
			db::get_handler()->rollBack();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка при создании заявки.');
		}
	}
	/*
	* Возвращает следующий для вставки идентификатор заявки.
	*/
	private static function get_insert_id(data_user $user){
		$sql = "SELECT MAX(`id`) as `max_query_id` FROM `queries`
				WHERE `company_id` = :company_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':company_id', $user->company_id, PDO::PARAM_INT);
		if($stm->execute() === false)
			throw new e_model('Проблема при опредении следующего query_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего query_id.');
		$query_id = (int) $stm->fetch()['max_query_id'] + 1;
		$stm->closeCursor();
		return $query_id;
	}
	/*
	* Возвращает следующий для вставки номер заявки.
	*/
	private static function get_insert_query_number(data_user $user, $time){
		$time = getdate($time);
		$sql = "SELECT MAX(`querynumber`) as `querynumber` FROM `queries`
				WHERE `opentime` > :begin AND `opentime` <= :end
				AND `company_id` = :company_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':company_id', $user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':begin', mktime(0, 0, 0, 1, 1, $time['year']), PDO::PARAM_INT);
		$stm->bindValue(':end', mktime(23, 59, 59, 12, 31, $time['year']), PDO::PARAM_INT);
		if($stm->execute() === false)
			throw new e_model('Проблема при опредении следующего querynumber.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего querynumber.');
		$query_number = (int) $stm->fetch()['querynumber'] + 1;
		$stm->closeCursor();
		return $query_number;
	}
	/**
	* Возвращает заявки.
	* @return array
	*/
	public static function get_queries(data_query $query){
		if(!empty($query->id)){
			$sql = "SELECT `queries`.`id`, `queries`.`company_id`,
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
				WHERE `queries`.`house_id` = `houses`.`id`
				AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`id` = :id";
		}elseif(!empty($query->number)){
			$sql = "SELECT `queries`.`id`, `queries`.`company_id`,
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
				WHERE `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
				AND `querynumber` = :number
				ORDER BY `opentime` DESC";
		}else{
			$sql = "SELECT `queries`.`id`, `queries`.`company_id`,
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
				WHERE `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
				AND `opentime` > :time_open
				AND `opentime` <= :time_close";
				if(!empty($query->status) AND $query->status !== 'all')
					$sql .= " AND `queries`.`status` = :status";
				if(!empty($query->street_id))
					$sql .= " AND `houses`.`street_id` = :street_id";
				if(!empty($query->house_id))
					$sql .= " AND `queries`.`house_id` = :house_id";
				if(!empty($query->department_id)){
					$sql .= " AND `queries`.`department_id` IN(";
					if(is_array($query->department_id)){
						$count = count($query->department_id);
						$i = 1;
						foreach($query->department_id as $key => $department){
							$sql .= ':department_id'.$key;
							if($i++ < $count)
								$sql .= ',';
						}
					}else
						$sql .= ':department_id0';
					$sql .= ")";
				}
			$sql .= " ORDER BY `queries`.`opentime` DESC";
		}
		$stm = db::get_handler()->prepare($sql);
		if(!empty($query->id))
			$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
		elseif(!empty($query->number))
			$stm->bindValue(':number', $query->number, PDO::PARAM_INT);
		else{
			$stm->bindValue(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$stm->bindValue(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all'){
				if(!in_array($query->status, ['open', 'close', 'working', 'reopen']))
					throw new e_model('Невозможный статус заявки.');
				$stm->bindValue(':status', $query->status, PDO::PARAM_STR);
			}
			if(!empty($query->street_id))
				$stm->bindValue(':street_id', $query->street_id, PDO::PARAM_INT);
			if(!empty($query->house_id))
				$stm->bindValue(':house_id', $query->house_id, PDO::PARAM_INT);
			if(!empty($query->department_id))
				if(is_array($query->department_id))
					foreach($query->department_id as $key => $department)
						$stm->bindValue(':department_id'.$key, $department, PDO::PARAM_INT);
				else
					$stm->bindValue(':department_id0', $query->department_id, PDO::PARAM_INT);
		}
		if($stm->execute() == false)
			throw new e_model('Ошибка при выборке заявок.');
		$result = [];
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
		while($query = $stm->fetch())
			$result[] = $query;
		$stm->closeCursor();
		return $result;
	}
	/**
	* Возвращает материалы заявки.
	* @return array
	*/
	public static function get_materials(data_query $query, data_user $current_user){
		$_SESSION['filters']['query'] = $query = self::build_query_filter($query);
		if(!empty($query->id)){
			$sql = "SELECT `query2material`.`query_id`, `query2material`.`amount`,
				`query2material`.`value`, `query2material`.`value`,
				`materials`.`id`, `materials`.`name`
				FROM `query2material`, `materials`
				WHERE `query2material`.`company_id` = :company_id
				AND `materials`.`company_id` = :company_id
				AND `materials`.`id` = `query2material`.`material_id`
				AND `query2material`.`query_id` = :id";
		}else{
			$sql = "SELECT `query2material`.`query_id`, `query2material`.`opentime` as `time_open`,
				`query2material`.`closetime` as `time_close`, `query2material`.`value`,
				`works`.`id`, `works`.`name`
				FROM `queries`, `query2material`, `works`
				WHERE `queries`.`company_id` = :company_id
				AND `query2material`.`company_id` = :company_id
				AND `works`.`id` = `query2material`.`work_id`
				AND `queries`.`id` = `query2material`.`query_id`
				AND `queries`.`opentime` > :time_open
				AND `queries`.`opentime` <= :time_close";
				if(!empty($query->status) AND $query->status !== 'all')
					$sql .= " AND `queries`.`status` = :status";
				$sql .= " ORDER BY `queries`.`opentime` DESC";
		}
		$stm = db::get_handler()->prepare($sql);
		if(!empty($query->id)){
			$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		}else{
			$stm->bindValue(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$stm->bindValue(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all')
				$stm->bindValue(':status', $query->status, PDO::PARAM_STR);
		}
		if($stm->execute() == false)
			throw new exception('Ошибка при выборке материалов.');
		$result = ['structure' => [], 'materials' => []];
		while($row = $stm->fetch()){
			var_dump($row);
			exit();
			$work = new data_work();
			$work->id = $row['id'];
			$work->name = $row['name'];
			$current = ['work_id' => $work->id, 'time_open' => $row['time_open'],
				'time_close' => $row['time_close'], 'value' => $row['value']];
			$result['structure'][$row['query_id']][] = $current;
			$result['works'][$work->id] = $work ;
		}
		$stm->closeCursor();
		return $result;
	}
	/**
	* Возвращает лицевые счета заявки.
	* @return array
	*/
	public static function get_numbers(data_query $query, data_user $current_user){
		if(!empty($query->id)){
			$sql = "SELECT `query2number`.`query_id`, `query2number`.`default`, `numbers`.`id`,
				`numbers`.`fio`, `numbers`.`number`, `flats`.`flatnumber` as `flat_number`
				FROM `query2number`, `numbers`, `flats`
				WHERE `query2number`.`company_id` = :company_id
				AND `numbers`.`company_id` = :company_id
				AND `numbers`.`id` = `query2number`.`number_id`
				AND `numbers`.`flat_id` = `flats`.`id`
				AND `query2number`.`query_id` = :id";
		}else{
			$sql = "SELECT `query2number`.`query_id`, `query2number`.`default`, `numbers`.`id`,
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
				AND `opentime` <= :time_close";
				if(!empty($query->status) AND $query->status !== 'all')
					$sql .= " AND `queries`.`status` = :status";
		}
		$stm = db::get_handler()->prepare($sql);
		if(!empty($query->id)){
			$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		}else{
			$stm->bindValue(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$stm->bindValue(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all')
				$stm->bindValue(':status', $query->status, PDO::PARAM_STR);
		}
		if($stm->execute() == false)
			throw new e_model('Ошибка при выборке пользователей.');
		$result = ['structure' => [], 'numbers' => []];
		while($row = $stm->fetch()){
			$number = new data_number();
			$number->id = $row['id'];
			$number->fio = $row['fio'];
			$number->number = $row['number'];
			$number->flat_number = $row['flat_number'];
			$result['structure'][$row['query_id']][$row['default']][] = $number->id;
			$result['numbers'][$number->id] = $number;
		}
		$stm->closeCursor();
		return $result;
	}
	/**
	* Возвращает пользователей заявки.
	* @return array
	*/
	public static function get_users(data_query $query, data_user $current_user){
		if(!empty($query->id)){
			$sql = "SELECT `query2user`.`query_id`, `query2user`.`class`, `users`.`id`,
				`users`.`firstname`, `users`.`lastname`, `users`.`midlename`
				FROM `query2user`, `users`
				WHERE `query2user`.`company_id` = :company_id
				AND `users`.`id` = `query2user`.`user_id`
				AND `query2user`.`query_id` = :id";
		}else{
			$sql = "SELECT `query2user`.`query_id`,  `query2user`.`class`, `users`.`id`,
				`users`.`firstname`, `users`.`lastname`, `users`.`midlename`
				FROM `queries`, `query2user`, `users`
				WHERE `queries`.`company_id` = :company_id
				AND `query2user`.`company_id` = :company_id
				AND `users`.`id` = `query2user`.`user_id`
				AND `queries`.`id` = `query2user`.`query_id`
				AND `opentime` > :time_open
				AND `opentime` <= :time_close";
				if(!empty($query->status) AND $query->status !== 'all')
					$sql .= " AND `queries`.`status` = :status";
				$sql .= " ORDER BY `opentime` DESC";
		}
		$stm = db::get_handler()->prepare($sql);
		if(!empty($query->id)){
			$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		}else{
			$stm->bindValue(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$stm->bindValue(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all')
				$stm->bindValue(':status', $query->status, PDO::PARAM_STR);
		}
		if($stm->execute() == false)
			throw new e_model('Ошибка при выборке пользователей.');
		$result = ['structure' => [], 'users' => []];
		while($row = $stm->fetch()){
			$user = new data_user();
			$user->id = $row['id'];
			$user->firstname = $row['firstname'];
			$user->lastname = $row['lastname'];
			$user->middlename = $row['midlename'];
			$result['structure'][$row['query_id']][$row['class']][] = $user->id;
			$result['users'][$user->id] = $user;
		}
		$stm->closeCursor();
		return $result;
	}
	/**
	* Возвращает работы.
	* @return array
	*/
	public static function get_works(data_query $query, data_user $current_user){
		if(!empty($query->id)){
			$sql = "SELECT `query2work`.`query_id`, `query2work`.`opentime` as `time_open`,
				`query2work`.`closetime` as `time_close`, `query2work`.`value`,
				`works`.`id`, `works`.`name`
				FROM `query2work`, `works`
				WHERE `query2work`.`company_id` = :company_id
				AND `works`.`company_id` = :company_id
				AND `works`.`id` = `query2work`.`work_id`
				AND `query2work`.`query_id` = :id";
		}else{
			$sql = "SELECT `query2work`.`query_id`, `query2work`.`opentime` as `time_open`,
				`query2work`.`closetime` as `time_close`, `query2work`.`value`,
				`works`.`id`, `works`.`name`
				FROM `queries`, `query2work`, `works`
				WHERE `queries`.`company_id` = :company_id
				AND `query2work`.`company_id` = :company_id
				AND `works`.`id` = `query2work`.`work_id`
				AND `queries`.`id` = `query2work`.`query_id`
				AND `queries`.`opentime` > :time_open
				AND `queries`.`opentime` <= :time_close";
				if(!empty($query->status) AND $query->status !== 'all')
					$sql .= " AND `queries`.`status` = :status";
				$sql .= " ORDER BY `queries`.`opentime` DESC";
		}
		$stm = db::get_handler()->prepare($sql);
		if(!empty($query->id)){
			$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		}else{
			$stm->bindValue(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$stm->bindValue(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
			if(!empty($query->status) AND $query->status !== 'all')
				$stm->bindValue(':status', $query->status, PDO::PARAM_STR);
		}
		if($stm->execute() == false)
			throw new e_model('Ошибка при выборке работ.');
		$result = ['structure' => [], 'works' => []];
		while($row = $stm->fetch()){
			$work = new data_work();
			$work->id = $row['id'];
			$work->name = $row['name'];
			$current = ['work_id' => $work->id, 'time_open' => $row['time_open'],
				'time_close' => $row['time_close'], 'value' => $row['value']];
			$result['structure'][$row['query_id']][] = $current;
			$result['works'][$work->id] = $work ;
		}
		$stm->closeCursor();
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
	public static function remove_user(data_query $query_params, data_user $user_params, $class, data_user $current_user){
		self::verify_query_id($query_params);
		model_user::verify_user_id($user_params)
		if(array_search($class, ['manager', 'performer']) === false)
			throw new e_model('Несоответствующие параметры: class.');
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		$user = model_user::get_users($user_params)[0];
		model_user::is_data_user($user);
		$sql = 'DELETE FROM `query2user`
				WHERE `company_id` = :company_id AND `query_id` = :query_id
				AND `user_id` = :user_id AND `class` = :class';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_STR);
		$stm->bindValue(':user_id', $user->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':class', $class, PDO::PARAM_STR);
		if($stm->execute() == false)
			throw new e_model('Ошибка при удалении пользователя и заявки.');
		return [$query];
	}
	/**
	* Обновляет работу из заявки.
	*/
	public static function remove_work(data_query $query_params, data_work $work_params, data_user $current_user){
		self::verify_query_id($query_params);
		model_work::verify_work_id($work_params);
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		$work = model_work::get_works($work_params, $current_user)[0];
		model_work::is_data_work($work);
		$sql = 'DELETE FROM `query2work`
				WHERE `company_id` = :company_id AND `query_id` = :query_id
				AND `work_id` = :work_id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		$stm->bindValue(':work_id', $work->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при удалении работы из заявки.');
		return [$query];
	}
	/**
	* Обновляет описание заявки.
	*/
	public static function update_description(data_query $query, data_user $current_user){
		self::verify_query_id($query);
		self::verify_query_description($query);
		$sql = 'UPDATE `queries` SET `description-open` = :description
				WHERE `company_id` = :company_id AND `id` = :query_id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':description', $query->description, PDO::PARAM_STR);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при обновлении описания заявки.');
		return [$query];
	}
	/**
	* Обновляет контактную информацию.
	*/
	public static function update_contact_information(data_query $query, data_user $current_user){
		self::verify_query_id($query);
		$sql = 'UPDATE `queries` SET `addinfo-name` = :fio, 
				addinfo-telephone` = :telephone, `addinfo-cellphone` = :cellphone 
				WHERE `company_id` = :company_id AND `id` = :query_id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':fio', $query->contact_fio, PDO::PARAM_STR);
		$stm->bindValue(':telephone', $query->contact_telephone, PDO::PARAM_STR);
		$stm->bindValue(':cellphone', $query->contact_cellphone, PDO::PARAM_STR);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при обновлении описания заявки.');
		return [$query];
	}
	/**
	* Обновляет статус оплаты.
	*/
	public static function update_payment_status(data_query $query_params, data_user $current_user){
		self::verify_query_id($query_params);
		if(array_search($query_params->payment_status, ['paid', 'unpaid', 'recalculation']) === false)
			throw new e_model('Несоответствующие параметры: payment_status.');
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		$query->payment_status = $query_params->payment_status;
		$sql = 'UPDATE `queries` SET `payment-status` = :payment_status
				WHERE `company_id` = :company_id AND `id` = :id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':payment_status', $query->payment_status, PDO::PARAM_STR);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при обновлении статуса оплаты заявки.');
		return [$query];
	}
	/**
	* Обновляет статус реакции.
	*/
	public static function update_warning_status(data_query $query_params, data_user $current_user){
		self::verify_query_id($query_params);
		if(array_search($query_params->warning_status, ['hight', 'normal', 'planned']) === false)
			throw new e_model('Несоответствующие параметры: payment_status.');
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		$query->warning_status = $query_params->warning_status;
		$sql = 'UPDATE `queries` SET `warning-type` = :warning_status
				WHERE `company_id` = :company_id AND `id` = :id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':warning_status', $query->warning_status, PDO::PARAM_STR);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при обновлении статуса реакции.');
		return [$query];
	}
	/**
	* Обновляет тип работ.
	*/
	public static function update_work_type(data_query $query_params, data_user $current_user){
		self::verify_query_id($query_params);
		self::verify_query_work_type_id($query_params);
		$query = self::get_queries($query_params)[0];
		self::is_data_query($query);
		$query_work_type_params = new data_query_work_type();
		$query_work_type_params->id = $query_params->worktype_id;
		$query_work_type = model_query_work_type::get_query_work_types($query_work_type_params, $_SESSION['user'])[0];
		model_query_work_type::is_data_query_work_type($query_work_type);
		$query->worktype_id = $query_work_type->id;
		$query->work_type_name = $query_work_type->name;
		$sql = 'UPDATE `queries` SET `query_worktype_id` = :work_type_id
				WHERE `company_id` = :company_id AND `id` = :id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':work_type_id', $query->worktype_id, PDO::PARAM_STR);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при обновлении типа работ.');
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
		if($query->work_type_id < 1)
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
		if(empty($query->contact_fio))
			throw new e_model('ФИО контакта заявки заданы не верно.');
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
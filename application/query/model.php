<?php
class model_query{
	private static function __add_number(data_query $query, data_number $number, $default, data_user $current_user){
		$sql = "INSERT INTO `query2number` (
				`query_id`, `number_id`, `company_id`, `default`
			) VALUES (
				:query_id, :number_id, :company_id, :default
			)";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':default', $default, PDO::PARAM_STR);
		$stm->bindValue(':number_id', $number->id, PDO::PARAM_INT);
		if($stm->execute() === false)
			throw new e_model('Проблема при добавлении лицевого счета.');
	}
	private static function __add_user(data_query $query, data_user $current_user, $class){
		if(array_search($class, ['creator', 'observer', 'manager', 'performer']) === false)
			throw new e_model('Не соответсвует тип пользователя.');
		$sql = "INSERT INTO `query2user` (
					`query_id`, `user_id`, `company_id`, `class`
				) VALUES (
					:query_id, :user_id, :company_id, :class
				)";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':user_id', $current_user->id, PDO::PARAM_INT);
		$stm->bindValue(':class', $class, PDO::PARAM_STR);
		if($stm->execute() === false)
			throw new e_model('Проблема при добавлении пользователя.');
	}
	private static function __add_query(data_query $query, $initiator, data_user $current_user, $time){
		if(empty($current_user->company_id))
			throw new e_model('company_id не указан.');
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
		$query->worktype_id = 1;
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
	public static function create_query(data_query $query, $initiator, data_user $current_user){
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
			return false;
			throw new e_model('Ошибка при создании заявки.');
		}
	}
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
	private static function get_insert_query_number(data_user $user, $time){
		$time = getdate($time);
		$sql = "SELECT MAX(`querynumber`) as `querynumber` FROM `queries`
		 WHERE `opentime` > :begin
		 AND `opentime` <= :end
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
	* Возвращает заявки
	* @return false or array
	*/
	public static function get_queries(data_query $query){
		$_SESSION['filters']['query'] = $query = self::build_query_filter($query);
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
				if(!empty($query->status)){
					$sql .= " AND `queries`.`status` = :status";
				}
		}
		$stm = db::get_handler()->prepare($sql);
		if(!empty($query->id))
			$stm->bindValue(':id', $query->id, PDO::PARAM_INT);
		elseif(!empty($query->number))
			$stm->bindValue(':number', $query->number, PDO::PARAM_INT);
		else{
			$stm->bindValue(':time_open', $query->time_open['begin'], PDO::PARAM_INT);
			$stm->bindValue(':time_close', $query->time_open['end'], PDO::PARAM_INT);
			if(!empty($query->status))
				$stm->bindValue(':status', $query->status, PDO::PARAM_STR);
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
	* Возвращает материалы заявки 
	* @return false or array
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
				if(!empty($query->status))
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
			if(!empty($query->status))
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
	* Возвращает лицевые счета заявки 
	* @return false or array
	*/
	public static function get_numbers(data_query $query, data_user $current_user){
		$_SESSION['filters']['query'] = $query = self::build_query_filter($query);
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
				if(!empty($query->status))
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
			if(!empty($query->status))
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
	* Возвращает Пользователей заявки 
	* @return false or array
	*/
	public static function get_users(data_query $query, data_user $current_user){
		$_SESSION['filters']['query'] = $query = self::build_query_filter($query);
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
				if(!empty($query->status))
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
			if(!empty($query->status))
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
	* Возвращает работы заявки 
	* @return false or array
	*/
	public static function get_works(data_query $query, data_user $current_user){
		$_SESSION['filters']['query'] = $query = self::build_query_filter($query);
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
				if(!empty($query->status))
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
			if(!empty($query->status))
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
	* Проверяет правильность параметров
	* Скармиливаем массив получаем правильный набор параметров
	*/
	public static function build_query_filter(data_query $query){
		$previous = $_SESSION['filters']['query'];
		$time = getdate();
		if(empty($query->time_open)){
			if($previous instanceof data_query)
				$query->time_open = $previous->time_open;
			else{
				$query->time_open['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
				$query->time_open['end'] = $query->time_open['begin'] + 86399;
			}
		}else{
			if(!is_array($query->time_open))
				throw new e_model('Проблема с временем открытия.');
			if(!is_int($query->time_open['begin']))
				throw new e_model('Проблема с временем открытия.');
			if(!is_int($query->time_open['end']))
				throw new e_model('Проблема с временем открытия.');
			if($query->time_open['end'] < $query->time_open['begin'])
				throw new e_model('Проблема с временем открытия.');
		}
		if(empty($query->status)){
			if($previous instanceof data_query)
				$query->status = $previous->status;
		}else{
			$statuses = ['open', 'working', 'close', 'reopen'];
			if(array_search($query->status, $statuses) === false)
				$query->status = null;
		}
		if(!empty($query->id)){
			if((int) $query->id < 1)
				throw new e_model('Проблема с идентификатором заявки.');
		}
		if(!empty($query->number)){
			if((int) $query->number < 1)
				throw new e_model('Проблема с номером заявки.');
		}
		return $query;
	}
	/**
	* Обновляет описание заявки
	*/
	public static function update_description(data_query $query, data_user $current_user){
		if(empty($query->description) OR empty($query->id))
			throw new e_model('Плохие параметры.');
		$sql = 'UPDATE `queries`
				SET `description-open` = :description
				WHERE `company_id` = :company_id
				AND `id` = :query_id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':description', $query->description, PDO::PARAM_STR);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':query_id', $query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при обновлении описания заявки.');
		return [$query];
	}	
	/**
	* Обновляет контактную информацию
	*/
	public static function update_contact_information(data_query $query, data_user $current_user){
		if(empty($query->id))
			throw new e_model('Плохие параметры.');
		$sql = 'UPDATE `queries`
				SET `addinfo-name` = :fio, `addinfo-telephone` = :telephone, 
				`addinfo-cellphone` = :cellphone 
				WHERE `company_id` = :company_id
				AND `id` = :query_id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':fio', $query->contact_fio, PDO::PARAM_STR);
		$stm->bindValue(':telephone', $query->contact_telephone, PDO::PARAM_STR);
		$stm->bindValue(':cellphone', $query->contact_cellphone, PDO::PARAM_STR);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindValue(':query_id', $current_query->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при обновлении описания заявки.');
		return [$query];
	}	
}

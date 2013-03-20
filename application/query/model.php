<?php
class model_query{
	private static function __add_number(data_query $query, data_number $number, $default, data_user $current_user){
		try{
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
				throw new exception('Проблема при добавлении лицевого счета.');
		}catch(exception $e){
			return false;
			throw new exception('Проблема при добавлении лицевого счета.');
		}
	}
	private static function __add_user(data_query $query, data_user $current_user, $class){
		try{
			if(array_search($class, ['creator', 'observer', 'manager', 'performer']) === false)
				throw new exception('Не соответсвует тип пользователя.');
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
				throw new exception('Проблема при добавлении пользователя.');
		}catch(exception $e){
			return false;
			throw new exception('Проблема при добавлении пользователя.');
		}
	}
	private static function __add_query(data_query $query, $initiator, data_user $current_user, $time){
		try{
			if(empty($current_user->company_id))
				throw new exception('company_id не указан.');
			if(!($initiator instanceof data_house OR $initiator instanceof data_number))
				throw new exception('Инициатор не верного формата.');
			if(empty($initiator->department_id))
				throw new exception('department_id не указан.');
			if(!is_int($time))
				throw new exception('Неверная дата.');
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
				throw new exception('Проблемы при создании заявки.');
			return $query;
		}catch(exception $e){
			return false;
			throw new exception('Инициатор не был сформирован.');
		}
	}	
	private static function add_numbers(data_query $query, $initiator, data_user $current_user){
		try{
			if($initiator instanceof data_house){
				$numbers = model_house::get_numbers($initiator);
				$default = 'false';
			}elseif($initiator instanceof data_number){
				$numbers[] = model_number::get_number($initiator);
				$default = 'true';
			}else
				throw new exception('Не подходящий тип инициатора.');
			if(count($numbers) < 1)
				throw new exception('Не соответсвующее количество лицевых счетов.');
			foreach($numbers as $number){
				self::__add_number($query, $number, $default, $current_user);
			}
		}catch(exception $e){
			return false;
			throw new exception('Проблема при добавлении иницитора.');
		}
	}	
	public static function create_query(data_query $query, $initiator, data_user $current_user){
		try{
			db::get_handler()->beginTransaction();
			if(empty($query->description))
				throw new exception('Пустое описание заявки.');
			if($initiator instanceof data_house)
				$initiator = model_house::get_house($initiator);
			elseif($initiator instanceof data_number)
				$initiator = model_number::get_number($initiator);
			if(!($initiator instanceof data_number OR $initiator instanceof data_house))
				throw new exception('Инициатор не корректен.');
			if($initiator instanceof data_house){
				$query->initiator = 'house';
				$query->house_id = $initiator->id;
			}else{
				$query->initiator = 'number';
				$query->house_id = $initiator->house_id;
			}	
			if(!is_int($query->id = self::get_insert_id($current_user)))
				throw new exception('Не был получени query_id для вставки.');
			$time = time();
			if(!is_int($query->number = self::get_insert_query_number($current_user, $time)))
				throw new exception('Инициатор не был сформирован.');
			$query = self::__add_query($query, $initiator, $current_user, $time);
			if(!($query instanceof data_query))
				throw new exception('Не корректный объект query');
			self::add_numbers($query, $initiator, $current_user);
			self::__add_user($query, $current_user, 'creator');
			self::__add_user($query, $current_user, 'manager');
			self::__add_user($query, $current_user, 'observer');
			db::get_handler()->commit();
			return $query;
		}catch(exception $e){
			db::get_handler()->rollBack();
			return false;
			throw new exception('Ошибка при создании заявки.');
		}
	}
	private static function get_insert_id(data_user $user){
		try{
			$sql = "SELECT MAX(`id`) as `max_query_id` FROM `queries`
				WHERE `company_id` = :company_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $user->company_id, PDO::PARAM_INT);
			if($stm->execute() === false)
				throw new exception('Проблема при опредении следующего query_id.');
			if($stm->rowCount() !== 1)
				throw new exception('Проблема при опредении следующего query_id.');
			$query_id = (int) $stm->fetch()['max_query_id'] + 1;
			$stm->closeCursor();
			return $query_id;
		}catch(exception $e){
			return false;
			throw new exception('Проблема при опредении следующего query_id.');
		}
	}	
	private static function get_insert_query_number(data_user $user, $time){
		try{
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
				throw new exception('Проблема при опредении следующего querynumber.');
			if($stm->rowCount() !== 1)
				throw new exception('Проблема при опредении следующего querynumber.');
			$query_number = (int) $stm->fetch()['querynumber'] + 1;
			$stm->closeCursor();
			return $query_number;
		}catch(exception $e){
			return false;
			throw new exception('Проблема при опредении следующего querynumber.');
		}
	}
	/**
	* Возвращает заявки
	* @return false or array
	*/
	public static function get_queries(data_query $query){
		try{
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
					$sql .= " ORDER BY `opentime` DESC";
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
				throw new exception('Ошибка при выборке заявок.');
			$result = [];
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
			while($query = $stm->fetch())
				$result[] = $query;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			die($e->getMessage());
			throw new exception('Ошибка при выборке заявок.');
		}
	}	
	/*
	* Проверяет правильность параметров
	* Скармиливаем массив получаем правильный набор параметров
	*/
	public static function build_query_filter(data_query $query){
		try{
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
					throw new exception('Проблема с временем открытия.');
				if(!is_int($query->time_open['begin']))
					throw new exception('Проблема с временем открытия.');
				if(!is_int($query->time_open['end']))
					throw new exception('Проблема с временем открытия.');
				if($query->time_open['end'] < $query->time_open['begin'])
					throw new exception('Проблема с временем открытия.');
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
					throw new exception('Проблема с идентификатором заявки.');
			}
			if(!empty($query->number)){
				if((int) $query->number < 1)
					throw new exception('Проблема с номером заявки.');
			}
			return $query;
		}catch(exception $e){
			throw new exception('Ошибка при построении параметром запроса заявок.');
		}
	}
}

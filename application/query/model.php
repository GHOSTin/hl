<?php
class model_query{
	private static function add_initiaor(data_query $query, $initiator){
		try{
			if($initiator instanceof data_house){
				$initiator = model_house::get_house($initiator);
			}elseif($initiator instanceof data_number){
				$initiator = model_number::get_number($initiator);
			}else{
				throw new exception('Не подходящий тип инициатора.');
			}
			$sql = "SELECT MAX(`id`) as `max_query_id` FROM `queries`
				WHERE `company_id` = :company_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $user->company_id, PDO::PARAM_INT);
			$stm->execute();
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['max_query_id'] + 1;
				}else
					return false;
			}
		}catch(exception $e){
			throw new exception('Проблема при добавлении иницитора.');
		}
	}	
	public static function create_query(data_query $query, $initiator, data_user $current_user){
		try{
			if($initiator instanceof data_house){
				$initiator = model_house::get_house($initiator);
			}elseif($initiator instanceof data_number){
				$initiator = model_number::get_number($initiator);
			}else{
				throw new exception('Не подходящий тип инициатора.');
			}
			if($initiator === false)
				return false;
			if($initiator instanceof data_house){
				$query->initiator = 'house';
				$query->house_id = $initiator->id;
			}else{
				$query->initiator = 'number';
				$query->house_id = $initiator->house_id;
			}	
			db::get_handler()->beginTransaction();
			$query_id = self::get_insert_id($current_user);
			if($query_id === false){
				db::get_handler()->rollBack();
				return false;
			}
			$time = getdate();
			$query_number = self::get_insert_query_number($current_user, $time);
			if($query_number === false){
				db::get_handler()->rollBack();
				return false;
			}
			$query->id = $query_id;
			$query->company_id = $current_user->company_id;
			$query->status = 'open';
			$query->number = $query_number;
			$query->payment_status = 'unpaid';
			$query->warning_status = 'normal';
			$query->department_id = $initiator->department_id;
			$query->worktype_id = 1;
			$query->time_open = $time[0];
			$query->time_work = $time[0];
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
			if($stm->execute() === false){
				db::get_handler()->rollBack();
				return false;
			}

			var_dump(self::add_initiator($query, $initiator));

			//db::get_handler()->commit();

			exit();
		}catch(exception $e){
			die($e->getMessage());
			throw new exception('Ошибка при создании заявки.');
		}
	}
	private static function get_insert_id(data_user $user){
		try{
			$sql = "SELECT MAX(`id`) as `max_query_id` FROM `queries`
				WHERE `company_id` = :company_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $user->company_id, PDO::PARAM_INT);
			$stm->execute();
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['max_query_id'] + 1;
				}else
					return false;
			}
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего query_id.');
		}
	}	
	private static function get_insert_query_number(data_user $user, $time){
		try{
			$sql = "SELECT MAX(`querynumber`) as `querynumber` FROM `queries`
			 WHERE `opentime` > :begin
			 AND `opentime` <= :end
			 AND `company_id` = :company_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $user->company_id, PDO::PARAM_INT);
			$stm->bindValue(':begin', mktime(0, 0, 0, 1, 1, $time['year']), PDO::PARAM_INT);
			$stm->bindValue(':end', mktime(23, 59, 59, 12, 31, $time['year']), PDO::PARAM_INT);
			$stm->execute();
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['querynumber'] + 1;
				}else
					return false;
			}
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего querynumber.');
		}

		$time 	= getdate();
		$begin 	= mktime(0,0,0,1,1,$time['year']);
		$end 	= mktime(23,59,59,12,31,$time['year']);
			
		$sql = "SELECT MAX(`querynumber`) as `querynumber` FROM `queries`
			 WHERE `opentime` > ".$begin."
			 AND `opentime` <= ".$end."
			 AND `company_id` = ".environment::$data->user->companyID;
			
		$ctl = self::select($sql);
		$ctl->set_data(intval($ctl->data->self[0]['querynumber']) + 1);
		return $ctl;
	}
	/**
	* Возвращает заявку
	* @return false or data_query
	*/
	public static function get_query($args){
		try{
			$query_id = $args['query_id'];
			if(empty($query_id))
				throw new exception('Wrong parametr');
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
				`streets`.`name` as `street_name`
				FROM `queries`, `houses`, `streets`
				WHERE `queries`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`
				AND `queries`.`id` = :query_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':query_id', $query_id, PDO::PARAM_INT);
			$stm->execute();
			if($stm->rowCount() > 0){
				$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
				return $stm->fetch();
			}else{
				return false;
			}
			$stm->closeCursor();
		}catch(exception $e){
			throw new exception('Ошибка при запросе заявки.');
		}
	}
	/**
	* Возвращает заявки
	* @return false or array
	*/
	public static function get_queries($args){
		$_SESSION['filters']['query'] = $args = self::build_query_filter($args);
		try{
			if(!empty($args['number'])){

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
					`streets`.`name` as `street_name`
					FROM `queries`, `houses`, `streets`
					WHERE `queries`.`house_id` = `houses`.`id`
					AND `houses`.`street_id` = `streets`.`id`
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
					`streets`.`name` as `street_name`
					FROM `queries`, `houses`, `streets`
					WHERE `queries`.`house_id` = `houses`.`id`
					AND `houses`.`street_id` = `streets`.`id`
					AND `opentime` > :time_open
					AND `opentime` <= :time_close";
					if(!empty($args['statuses']) AND is_array($args['statuses'])){
						$sql .= " AND `queries`.`status` IN(:status)";
					}
					$sql .= " ORDER BY `opentime` DESC";
			}
			$stm = db::get_handler()->prepare($sql);
			if(!empty($args['number'])){
				$stm->bindParam(':number', $number, PDO::PARAM_INT, 10);
			}else{
				$stm->bindParam(':time_open', $time_open, PDO::PARAM_INT, 10);
				$stm->bindParam(':time_close', $time_close, PDO::PARAM_INT, 10);
			}
			$time_open = $args['time_interval']['begin'];	
			$time_close = $args['time_interval']['end'];
			$number = $args['number'];
			if(!empty($args['statuses']) AND is_array($args['statuses'])){
				$stm->bindValue(':status', $args['statuses'][0], PDO::PARAM_STR);
			}
			$stm->execute();
			if($stm->rowCount() > 0){
				$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
				while($query = $stm->fetch())
					$result[$query->id] = $query;
				return $result;
			}else{
				return false;
			}
			$stm->closeCursor();
		}catch(exception $e){
			throw new exception('Ошибка при выборке заявок.');
		}
	}	
	/*
	* Проверяет правильность параметров
	* Скармиливаем массив получаем правильный набор параметров
	*/
	public static function build_query_filter($in_args){
		try{
			$s_args = $_SESSION['filters']['query'];
			/*
			* Если нет в in_args то не записываем и в out_args
			*/
			if(!empty($in_args['number'])){
				$out_args['number'] = (int) $in_args['number'];
			}
			/*
			* Если нет в in_args проверяем в сессии и берем оттуда параметры.
			* Если нет в сессии генерируем по умолчанию
			*/
			// проверка интервала времени
			if(empty($in_args['time_interval']['begin']) OR empty($in_args['time_interval']['end'])){
				if(empty($s_args['time_interval']['begin']) OR empty($s_args['time_interval']['end'])){
					$time = getdate();
					$out_args['time_interval']['begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
					$out_args['time_interval']['end'] = $out_args['time_interval']['begin'] + 86399;
				}else{
					$out_args['time_interval']['begin'] = $s_args['time_interval']['begin'];
					$out_args['time_interval']['end'] = $s_args['time_interval']['end'];
				}
			}else{
				$out_args['time_interval']['begin'] = $in_args['time_interval']['begin'];
				$out_args['time_interval']['end'] = $in_args['time_interval']['end'];
			}
			// проверяет статус
			$statuses = ['open', 'working', 'close', 'reopen', 'open+working'];
			if(empty($in_args['statuses'])){
				if(!empty($s_args['statuses'])){
					$out_args['statuses'] = $s_args['statuses'];
				}
			}else{
				if(is_array($in_args['statuses'])){
					foreach ($in_args['statuses'] as $status){
						if(array_search($status, $statuses, true) !== false){
							if($status === 'open+working'){
								$out_args['statuses'][] = 'open';
								$out_args['statuses'][] = 'working';
							}else
								$out_args['statuses'][] = $status;
						}
					}
				}
			}
			return $out_args;
			var_dump($out_args);
			exit();
		}catch(exception $e){
			throw new exception('Ошибка при построении параметром запроса заявок.');
		}
	}
}

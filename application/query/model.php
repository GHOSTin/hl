<?php
class model_query{
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
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
			$query = $stm->fetch();
			$stm->closeCursor();
			return $query;
		}catch(exception $e){
			return false;
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
					AND `opentime` <= :time_close
					ORDER BY `opentime` DESC";
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
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query');
			while($query = $stm->fetch())
				$result[$query->id] = $query;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			return false;
		}
	}	
	/*
	* Проверяет правильность параметров
	* Скармиливаем массив получаем правильный набор параметров
	*/
	public static function build_query_filter($in_args){
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
		return $out_args;
		var_dump($out_args);
		exit();
	}
}

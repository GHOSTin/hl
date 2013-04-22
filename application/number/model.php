<?php
class model_number{
	/**
	* Создает новый лицевой ссчет уникальный для компании и для города.
	* @return object data_number
	*/
	public static function create_number(data_city $city, data_flat $flat, data_number $number, data_user $current_user){
		if(empty($number->number) OR empty($number->fio) OR empty($number->status))
			throw new e_model('number, fio, status заданы не правильно.');
		$number->company_id = $current_user->company_id;
		$number->city_id = $city->id;
		$number->type = 'human';
		$number->house_id = $flat->house_id;
		$number->flat_id = $flat->id;
		$number->id = self::get_insert_id($city);
		$sql = "INSERT INTO `numbers` (
					`id`, `company_id`, `city_id`, `house_id`, `flat_id`, `number`, `type`, `status`,
					`fio`, `telephone`, `cellphone`, `password`, `contact-fio`, `contact-telephone`,
					`contact-cellphone`
				) VALUES (
					:number_id, :company_id, :city_id, :house_id, :flat_id, :number, :type, :status,
					:fio, :telephone, :cellphone, :password, :contact_fio, :contact_telephone,
					:contact_cellphone
				);";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':number_id', $number->id);
		$stm->bindValue(':company_id', $number->company_id);
		$stm->bindValue(':city_id', $number->city_id);
		$stm->bindValue(':house_id', $number->house_id);
		$stm->bindValue(':flat_id', $number->flat_id);
		$stm->bindValue(':number', $number->number);
		$stm->bindValue(':type', $number->type);
		$stm->bindValue(':status', $number->status);
		$stm->bindValue(':fio', $number->fio);
		$stm->bindValue(':telephone', $number->telephone);
		$stm->bindValue(':cellphone', $number->cellphone);
		$stm->bindValue(':password', $number->password);
		$stm->bindValue(':contact_fio', $number->contact_fio);
		$stm->bindValue(':contact_telephone', $number->contact_telephone);
		$stm->bindValue(':contact_cellphone', $number->contact_cellphone);
		if($stm->execute() == false)
			throw new e_model('Проблемы при создании нового лицевого счета.');
		$stm->closeCursor();
		return $number;
	}
	/**
	* Возвращает следующий для вставки идентификатор лицевого счета.
	* @return int
	*/
	private static function get_insert_id(data_city $city){
		$sql = "SELECT MAX(`id`) as `max_number_id` FROM `numbers`
			WHERE `city_id` = :city_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':city_id', $city->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при опредении следующего number_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего number_id.');
		$number_id = (int) $stm->fetch()['max_number_id'] + 1;
		$stm->closeCurscor();
		return $nuber_id;
	}
	/**
	* Возвращает информацию о лицевом счете.
	* @return object data_number
	*/
	public static function get_number(data_number $number){
		if(empty($number->id))
			throw new e_model('id задан не верно.');
		$sql = "SELECT `numbers`.`id`, `numbers`.`company_id`, 
					`numbers`.`city_id`, `numbers`.`house_id`, 
					`numbers`.`flat_id`, `numbers`.`number`,
					`numbers`.`type`, `numbers`.`status`,
					`numbers`.`fio`, `numbers`.`telephone`,
					`numbers`.`cellphone`, `numbers`.`password`,
					`numbers`.`contact-fio` as `contact_fio`,
					`numbers`.`contact-telephone` as `contact_telephone`,
					`numbers`.`contact-cellphone` as `contact_cellphone`,
					`flats`.`flatnumber` as `flat_number`,
					`houses`.`housenumber` as `house_number`,
					`houses`.`department_id`,
					`streets`.`name` as `street_name`
				FROM `numbers`, `flats`, `houses`, `streets`
				WHERE `numbers`.`id` = :number_id
				AND `numbers`.`flat_id` = `flats`.`id`
				AND `numbers`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':number_id', $number->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при запросе лицевого счета.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при запросе лицевого счета.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_number');
		$number = $stm->fetch();
		$stm->closeCursor();
		return $number;
	}
	/*
	* Возвращает список счетчиков лицевого счета
	*/
	public static function get_meters(data_number $number_params, data_user $current_user, data_meter $meter_params = null){
		if(empty($number_params->id))
			throw new e_model('Идентификатор лицевого счета задан не верно.');
		if(empty($current_user->company_id))
			throw new e_model('Идентификатор компании задан не верно.');
		if(!is_null($meter_params))
			if(empty($meter_params->id))
				throw new e_model('Идентификатор счетчики задан не верно.');
		$sql = "SELECT `meters`.`id`,
			`meters`.`name`,
			`services`.`name` as `service`,
			`number2meter`.`serial`,
			`number2meter`.`checktime`
		FROM `meters`, `number2meter`, `services`
		WHERE `number2meter`.`company_id` = :company_id
		AND `meters`.`company_id` = :company_id
		AND `services`.`company_id` = :company_id
		AND `services`.`tag` = 'meter'
		AND `number2meter`.`number_id` = :number_id
		AND `meters`.`id` = `number2meter`.`meter_id`
		AND `number2meter`.`service_id` = `services`.`id`";
		if(!is_null($meter_params))
			$sql .= " AND `number2meter`.`meter_id` = :meter_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':number_id', $number_params->id, PDO::PARAM_INT);
		$stm->bindParam(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if(!is_null($meter_params))
			$stm->bindParam(':meter_id', $meter_params->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при при выборке счетчиков.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_meter');
		$result = [];
		while($meter = $stm->fetch())
			$result[] = $meter;
		$stm->closeCursor();
		return $result;
	}
	/*
	* Возвращает данные счетчика
	*/
	public static function get_meter_data(data_meter $meter, data_number $number, data_user $current_user, $time){
		if(empty($meter->id))
			throw new e_model('Идентификатор счетчика задан не верно.');
		if(empty($meter->serial))
			throw new e_model('Серийный номер счетчика задан не верно.');
		if(empty($number->id))
			throw new e_model('Идентификатор лицевого счета задан не верно.');
		if(empty($current_user->company_id))
			throw new e_model('Идентификатор компании задан не верно.');
		if(empty($time))
			throw new e_model('Время выборки задано не верно.');
		$time = getdate($time);
		$sql = "SELECT `time`, `value` FROM `meter2data`
		WHERE `meter2data`.`company_id` = :company_id
		AND `meter2data`.`number_id` = :number_id
		AND `meter2data`.`meter_id` = :meter_id
		AND `meter2data`.`serial` = :serial
		AND `meter2data`.`time` >= :time_begin
		AND `meter2data`.`time` <= :time_end";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':meter_id', $meter->id, PDO::PARAM_INT);
		$stm->bindParam(':serial', $meter->serial, PDO::PARAM_INT);
		$stm->bindParam(':number_id', $number->id, PDO::PARAM_INT);
		$stm->bindParam(':company_id', $current_user->company_id, PDO::PARAM_INT);
		$stm->bindParam(':time_begin', mktime(0, 0, 0, 1, 1, $time['year']), PDO::PARAM_INT);
		$stm->bindParam(':time_end', mktime(23, 59, 59, 12, 31, $time['year']), PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при при выборки данных счетчика.');
		$result = [];
		while($data = $stm->fetch())
			$result[$data['time']] = json_decode($data['value']);
		$stm->closeCursor();
		return $result;
	}
	/*
	* Возвращает данные счетчика
	*/
	public static function update_meter_data(data_meter $meter, data_number $number, data_user $current_user, $time, $tarif){
		try{
			db::get_handler()->beginTransaction();
			if(empty($meter->id))
				throw new e_model('Идентификатор счетчика задан не верно.');
			if(empty($meter->serial))
				throw new e_model('Серийный номер счетчика задан не верно.');
			if(empty($number->id))
				throw new e_model('Идентификатор лицевого счета задан не верно.');
			if(empty($current_user->company_id))
				throw new e_model('Идентификатор компании задан не верно.');
			if(empty($time))
				throw new e_model('Время выборки задано не верно.');
			if(count($tarif) !== 2)
				throw new e_model('Показания заданы не верно.');
			$number = self::get_number($number);
			if(!($number instanceof data_number))
				throw new e_model('Проблема при выборке лицевого счета.');
			$meters = self::get_meters($number, $current_user, $meter);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			if(!($meter instanceof data_meter))
				throw new e_model('Проблема при выборке счетчика.');
			$sql = "SELECT `time`, `value` FROM `meter2data`
			WHERE `meter2data`.`company_id` = :company_id
			AND `meter2data`.`number_id` = :number_id
			AND `meter2data`.`meter_id` = :meter_id
			AND `meter2data`.`serial` = :serial
			AND `meter2data`.`time` = :time";
			$time = getdate($time);
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':meter_id', $meter->id, PDO::PARAM_INT);
			$stm->bindParam(':serial', $meter->serial, PDO::PARAM_INT);
			$stm->bindParam(':number_id', $number->id, PDO::PARAM_INT);
			$stm->bindParam(':company_id', $current_user->company_id, PDO::PARAM_INT);
			$stm->bindParam(':time', mktime(12, 0, 0, $time['mon'], 1, $time['year']), PDO::PARAM_INT);
			if($stm->execute() == false)
			throw new e_model('Проблема при при выборки данных счетчика.');
			$count = $stm->rowCount();
			if($count === 0)
				$sql = "INSERT INTO `meter2data` (`company_id`, `number_id`,
						`meter_id`, `serial`, `time`, `value`
						) VALUES (:company_id, :number_id, :meter_id, :serial,
						:time, :value)";
			elseif($count === 1)
				$sql = "UPDATE `meter2data` SET `time` = :time, `value` = :value
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial
						AND `time` = :time";
			else
				throw new e_model('Не подходящее количество параметров.');
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':meter_id', $meter->id, PDO::PARAM_INT);
			$stm->bindParam(':serial', $meter->serial, PDO::PARAM_INT);
			$stm->bindParam(':number_id', $number->id, PDO::PARAM_INT);
			$stm->bindParam(':company_id', $current_user->company_id, PDO::PARAM_INT);
			$stm->bindParam(':time', mktime(12, 0, 0, $time['mon'], 1, $time['year']), PDO::PARAM_INT);
			$stm->bindParam(':value', json_encode([round($tarif[0], 2), round($tarif[1], 2)]));
			if($stm->execute() == false)
				throw new e_model('Проблема при при выборки данных счетчика.');
			$stm->closeCursor();
			db::get_handler()->commit();
			return $result;
		}catch(exception $e){

			die($e->getMessage());
		}
	}
	/**
	* Обнавляет номер лицевого счета
	* @return object data_number
	*/
	public static function update_number(data_number $number_params, data_user $current_user){
		if(empty($number_params->id))
			throw new e_model('Идентификатор задан не верно.');
		$number = model_number::get_number($number_params);
		if(!($number instanceof data_number))
			throw new e_model('Неверно выбран лицевой счет.');
		if(empty($number_params->number))
			throw new e_model('Номер лицевого задан не верно.');
		$number->number = $number_params->number;
		try{
			db::get_handler()->beginTransaction();
			$sql = "SELECT `numbers`.`id`, `numbers`.`company_id`, 
						`numbers`.`city_id`, `numbers`.`house_id`, 
						`numbers`.`flat_id`, `numbers`.`number`,
						`numbers`.`type`, `numbers`.`status`,
						`numbers`.`fio`, `numbers`.`telephone`,
						`numbers`.`cellphone`, `numbers`.`password`,
						`numbers`.`contact-fio` as `contact_fio`,
						`numbers`.`contact-telephone` as `contact_telephone`,
						`numbers`.`contact-cellphone` as `contact_cellphone`
					FROM `numbers` WHERE `numbers`.`company_id` = :company_id
					AND `numbers`.`id` = :number_id
					AND `numbers`.`number` = :number
					AND `numbers`.`city_id` = :city_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
			$stm->bindValue(':number_id', $number->id, PDO::PARAM_INT);
			$stm->bindValue(':number', $number->number, PDO::PARAM_STR);
			$stm->bindValue(':city_id', $number->city_id, PDO::PARAM_INT);
			if($stm->execute() == false)
				throw new e_model('Проблема при запросе лицевого счета.');
			if($stm->rowCount() > 0)
				throw new e_model('Счет с таким лицевым уже существует в системе.');
			$stm->closeCursor();
			$sql = "UPDATE `numbers` SET `number` = :number 
					WHERE `company_id` = :company_id
					AND `city_id` = :city_id
					AND `id` = :number_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
			$stm->bindValue(':number_id', $number->id, PDO::PARAM_INT);
			$stm->bindValue(':number', $number->number, PDO::PARAM_STR);
			$stm->bindValue(':city_id', $number->city_id, PDO::PARAM_INT);
			if($stm->execute() == false)
				throw new e_model('Проблема при запросе лицевого счета.');
			db::get_handler()->commit();
			return $number;
		}catch(exception $e){
			db::get_handler()->rollBack();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}
}
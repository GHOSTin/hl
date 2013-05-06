<?php
class model_number{
	/**
	* Создает новый лицевой ссчет уникальный для компании и для города.
	* @return object data_number
	*/
	public static function create_number(data_city $city, data_flat $flat,
		data_number $number, data_current_user $current_user){
		model_city::verify_city_id($city);
		$number->id = self::get_insert_id($city);
		$number->company_id = $current_user->company_id;
		$number->city_id = $city->id;
		$number->type = 'human';
		$number->house_id = $flat->house_id;
		$number->flat_id = $flat->id;
		self::verify_id($number);
		self::verify_company_id($number);
		self::verify_city_id($number);
		self::verify_house_id($number);
		self::verify_flat_id($number);
		self::verify_number($number);
		self::verify_type($number);
		self::verify_status($number);
		self::verify_fio($number);
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
		self::verify_id($number);
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
	/**
	* Возвращает список лицевых счетов.
	* @return array object data_number
	*/
	public static function get_numbers(data_number $number, data_current_user $current_user){
		if(!empty($number->id))
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
					WHERE `numbers`.`company_id` = :company_id
					AND `numbers`.`id` = :number_id
					AND `numbers`.`flat_id` = `flats`.`id`
					AND `numbers`.`house_id` = `houses`.`id`
					AND `houses`.`street_id` = `streets`.`id`";
		elseif(!empty($number->number))
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
					WHERE `numbers`.`company_id` = :company_id
					AND `numbers`.`number` = :number
					AND `numbers`.`flat_id` = `flats`.`id`
					AND `numbers`.`house_id` = `houses`.`id`
					AND `houses`.`street_id` = `streets`.`id`";
		else
			throw new e_model('Не заданы нужные параметры.');
		$stm = db::get_handler()->prepare($sql);
		if(!empty($number->id))
			$stm->bindParam(':number_id', $number->id, PDO::PARAM_INT);
		else
			$stm->bindParam(':number', $number->number, PDO::PARAM_INT);
		$stm->bindParam(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при запросе лицевых счетов.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_number');
		$result = [];
		while($number = $stm->fetch())
			$result[] = $number;
		$stm->closeCursor();
		return $result;
	}	
	/*
	* Возвращает список счетчиков лицевого счета
	*/
	public static function get_meters(data_number $number_params,
		data_current_user $current_user, data_meter $meter_params = null){
		self::verify_id($number_params);
		model_user::verify_company_id($current_user);
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
	public static function get_meter_data(data_meter $meter,
		data_number $number, data_current_user $current_user, $time){
		model_meter::verify_id($meter);
		model_meter::verify_serial($meter);
		self::verify_id($number);
		model_user::verify_company_id($current_user);
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
	public static function update_meter_data(data_meter $meter,
		data_number $number, data_current_user $current_user, $time, $tarif){
		try{
			db::get_handler()->beginTransaction();
			model_meter::verify_id($meter);
			model_meter::verify_serial($meter);
			self::verify_number_id($number);
			self::verify_user_company_id($current_user);
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
			return $tarif;
		}catch(exception $e){
			db::get_handler()->rollBack();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}
	/**
	* Обнавляет номер лицевого счета
	* @return object data_number
	*/
	public static function update_number(data_number $number_params, data_current_user $current_user){
		self::verify_id($number_params);
		self::verify_number($number_params);
		$number = model_number::get_number($number_params);
		self::is_data_number($number);
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
	/**
	* Верификация сотового телефона лицевого счета.
	*/
	public static function verify_cellphone(data_number $number){
	}
	/**
	* Верификация идентификатора города.
	*/
	public static function verify_city_id(data_number $number){
		if($number->city_id < 1)
			throw new e_model('Идентификатор города задан не верно.');
	}
	/**
	* Верификация сотового телефона контактного лица.
	*/
	public static function verify_contact_cellphone(data_number $number){
		if(empty($number->contact_cellphone))
			throw new e_model('Сотовый телефон контактного лица задан не верно.');
	}
	/**
	* Верификация ФИО контактного лица.
	*/
	public static function verify_contact_fio(data_number $number){
		if(empty($number->contact_fio))
			throw new e_model('ФИО контактного лица заданы не верно.');
	}
	/**
	* Верификация телефона контактного лица.
	*/
	public static function verify_contact_telephone(data_number $number){
		if(empty($number->contact_telephone))
			throw new e_model('Телефон контактного лица задан не верно.');
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_number $number){
		if($number->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентификатора участка.
	*/
	public static function verify_department_id(data_number $number){
		if($number->department_id < 1)
			throw new e_model('Идентификатор участка задан не верно.');
	}
	/**
	* Верификация ФИО владельца лицевого счета.
	*/
	public static function verify_fio(data_number $number){
		if(empty($number->fio))
			throw new e_model('ФИО владельца лицевого счета заданы не верно.');
	}
	/**
	* Верификация номера квартиры.
	*/
	public static function verify_flat_number(data_number $number){
		if(empty($number->flat_number))
			throw new e_model('Номер квартиры задан не верно.');
	}
	/**
	* Верификация идентификатора квартиры.
	*/
	public static function verify_flat_id(data_number $number){
		if($number->flat_id < 1)
			throw new e_model('Идентификатор квартиры задан не верно.');
	}
	/**
	* Верификация идентификатора дома.
	*/
	public static function verify_house_id(data_number $number){
		if($number->house_id < 1)
			throw new e_model('Идентификатор дома задан не верно.');
	}
	/**
	* Верификация номера дома.
	*/
	public static function verify_house_number(data_number $number){
		if(empty($number->house_number))
			throw new e_model('Номер дома задан не верно.');
	}
	/**
	* Верификация идентификатора лицевого счета.
	*/
	public static function verify_id(data_number $number){
		if($number->id < 1)
			throw new e_model('Идентификатор лицевого счета задан не верно.');
	}
	/**
	* Верификация номера лицевого счета.
	*/
	public static function verify_number(data_number $number){
		if(empty($number->number))
			throw new e_model('Номер лицевого счета задан не верно.');
	}
	/**
	* Верификация пароля лицевого счета.
	*/
	public static function verify_password(data_number $number){
		if(empty($number->password))
			throw new e_model('Пароль лицевого счета задан не верно.');
	}
	/**
	* Верификация статуса лицевого счета.
	*/
	public static function verify_status(data_number $number){
		if(empty($number->status))
			throw new e_model('Статус лицевого счета задан не верно.');
	}
	/**
	* Верификация названия улицы.
	*/
	public static function verify_street_name(data_number $number){
		if(empty($number->street_name))
			throw new e_model('Название улицы задано не верно.');
	}
	/**
	* Верификация телефона владельца лицевого счета.
	*/
	public static function verify_telephone(data_number $number){
		if(empty($number->telephone))
			throw new e_model('Телефон владельца лицевого счета задан не верно.');
	}
	/**
	* Верификация типа лицевого счета.
	*/
	public static function verify_number_type(data_number $number){
		if(empty($number->type))
			throw new e_model('Тип лицевого счета задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_number.
	*/
	public static function is_data_number($number){
		if(!($number instanceof data_number))
			throw new e_model('Возвращен объект не является лицевым счетом.');
	}
}
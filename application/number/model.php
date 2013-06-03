<?php
class model_number{
	/**
	* Создает новый лицевой ссчет уникальный для компании и для города.
	* @return object data_number
	*/
	public static function create_number(data_company $company, data_city $city, data_flat $flat,
										data_number $number){
		model_city::verify_city_id($city);
		$number->id = self::get_insert_id($city);
		$number->company_id = $company->id;
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
		$sql = new sql();
		$sql->query("INSERT INTO `numbers` (`id`, `company_id`, `city_id`, `house_id`,
					`flat_id`, `number`, `type`, `status`, `fio`, `telephone`, `cellphone`,
					`password`, `contact-fio`, `contact-telephone`, `contact-cellphone`)
					VALUES (:number_id, :company_id, :city_id, :house_id, :flat_id,
					:number, :type, :status, :fio, :telephone, :cellphone, :password,
					:contact_fio, :contact_telephone, :contact_cellphone)");
		$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $number->company_id, PDO::PARAM_INT);
		$sql->bind(':city_id', $number->city_id, PDO::PARAM_INT);
		$sql->bind(':house_id', $number->house_id, PDO::PARAM_INT);
		$sql->bind(':flat_id', $number->flat_id, PDO::PARAM_INT);
		$sql->bind(':number', $number->number, PDO::PARAM_STR);
		$sql->bind(':type', $number->type, PDO::PARAM_STR);
		$sql->bind(':status', $number->status, PDO::PARAM_STR);
		$sql->bind(':fio', $number->fio, PDO::PARAM_STR);
		$sql->bind(':telephone', $number->telephone, PDO::PARAM_STR);
		$sql->bind(':cellphone', $number->cellphone, PDO::PARAM_STR);
		$sql->bind(':password', $number->password, PDO::PARAM_STR);
		$sql->bind(':contact_fio', $number->contact_fio, PDO::PARAM_STR);
		$sql->bind(':contact_telephone', $number->contact_telephone, PDO::PARAM_STR);
		$sql->bind(':contact_cellphone', $number->contact_cellphone, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании нового лицевого счета.');
		return $number;
	}

	public static function add_meter(data_company $company, data_number $number,
										data_meter $meter){
		model_company::verify_id($company);
		self::verify_id($number);
		model_meter::verify_id($meter);
		$numbers = self::get_numbers($company, $number);
		if(count($numbers) !== 1)
			throw new e_model('Невереное количество лицевых счетов.');
		$number = $numbers[0];
		self::is_data_number($number);
		$meters = model_meter::get_meters($company, $meter);
		if(count($meters) !== 1)
			throw new e_model('Неверное количество счетчиков.');
		$new_meter = $meters[0];
		model_meter::is_data_meter($new_meter);
		$new_meter->service = $meter->service;
		$new_meter->serial = $meter->serial;
		$new_meter->date_release = $meter->date_release;
		$new_meter->date_install = $meter->date_install;
		$new_meter->date_checking = $meter->date_checking;
		$new_meter->period = $meter->period;
		model_meter::verify_id($new_meter);
		model_meter::verify_capacity($new_meter);
		model_meter::verify_rates($new_meter);
		model_meter::verify_service($new_meter);
		model_meter::verify_serial($new_meter);
		model_meter::verify_period($new_meter);
		model_meter::verify_date_release($new_meter);
		model_meter::verify_date_install($new_meter);
		model_meter::verify_date_checking($new_meter);
		$sql = new sql();
		$sql->query("INSERT INTO `number2meter` (`company_id`, `number_id`,
			`meter_id`, `service`, `serial`, `date_release`,`date_install`,
			`date_checking`, `period`) VALUES (:company_id, :number_id,
			:meter_id, :service, :serial, :date_release, :date_install,
			:date_checking, :period)");
		$sql->bind(':company_id', $new_meter->company_id, PDO::PARAM_INT);
		$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
		$sql->bind(':meter_id', $new_meter->id, PDO::PARAM_INT);
		$sql->bind(':service', $new_meter->service[0], PDO::PARAM_STR);
		$sql->bind(':serial', $new_meter->serial, PDO::PARAM_STR);
		$sql->bind(':date_release', $new_meter->date_release, PDO::PARAM_INT);
		$sql->bind(':date_install', $new_meter->date_install, PDO::PARAM_INT);
		$sql->bind(':date_checking', $new_meter->date_checking, PDO::PARAM_INT);
		$sql->bind(':period', $new_meter->period, PDO::PARAM_INT);
		$sql->execute('Проблемы при добавлении счетчика в лицевой счет.');
		return $new_meter;
	}

	/**
	* Возвращает следующий для вставки идентификатор лицевого счета.
	* @return int
	*/
	public static function get_insert_id(data_company $company, data_city $city){
		model_company::verify_id($company);
		model_city::verify_id($city);
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_number_id` FROM `numbers`
					WHERE `company_id` = :company_id AND `city_id` = :city_id");
		$sql->bind(':city_id', $city->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->execute('Проблема при опредении следующего number_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего number_id.');
		$number_id = (int) $sql->row()['max_number_id'] + 1;
		return $number_id;
	}
	/**
	* Возвращает список лицевых счетов.
	* @return array object data_number
	*/
	public static function get_numbers(data_company $company, data_number $number){
		model_company::verify_id($company);
		$sql = new sql();
		if(!empty($number->id)){
			self::verify_id($number);
			$sql->query("SELECT `numbers`.`id`, `numbers`.`company_id`, 
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
					AND `houses`.`street_id` = `streets`.`id`");
			$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
		}elseif(!empty($number->number)){
			self::verify_number($number);
			$sql->query("SELECT `numbers`.`id`, `numbers`.`company_id`, 
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
					AND `houses`.`street_id` = `streets`.`id`");
			$sql->bind(':number', $number->number, PDO::PARAM_INT);
		}else
			throw new e_model('Не заданы нужные параметры.');
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		return $sql->map(new data_number(), 'Проблема при запросе лицевых счетов.');
	}	
	/*
	* Возвращает список счетчиков лицевого счета
	*/
	public static function get_meters(data_company $company, data_number $number,
										data_meter $meter = null){
		model_company::verify_id($company);
		self::verify_id($number);
		$sql = new sql();
		$sql->query("SELECT `meters`.`id`,
						`meters`.`name`,
						`number2meter`.`service`,
						`number2meter`.`serial`,
						`number2meter`.`checktime`
					FROM `meters`, `number2meter`
					WHERE `number2meter`.`company_id` = :company_id
					AND `meters`.`company_id` = :company_id
					AND `number2meter`.`number_id` = :number_id
					AND `meters`.`id` = `number2meter`.`meter_id`");
		$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		if(!is_null($meter)){
			model_meter::verify_id($meter);
			$sql->query(" AND `number2meter`.`meter_id` = :meter_id");
			$sql->bind(':meter_id', $meter->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_meter(), 'Проблема при при выборке счетчиков.');
	}
	/*
	* Возвращает данные счетчика
	*/
	public static function get_meter_data(data_company $company, data_meter $meter,
											data_number $number, $time){
		model_company::verify_id($company);
		model_meter::verify_id($meter);
		model_meter::verify_serial($meter);
		self::verify_id($number);
		if(empty($time))
			throw new e_model('Время выборки задано не верно.');
		$time = getdate($time);
		$sql = new sql();
		$sql->query("SELECT `time`, `value` FROM `meter2data`
					WHERE `meter2data`.`company_id` = :company_id
					AND `meter2data`.`number_id` = :number_id
					AND `meter2data`.`meter_id` = :meter_id
					AND `meter2data`.`serial` = :serial
					AND `meter2data`.`time` >= :time_begin
					AND `meter2data`.`time` <= :time_end");
		$sql->bind(':meter_id', $meter->id, PDO::PARAM_INT);
		$sql->bind(':serial', $meter->serial, PDO::PARAM_INT);
		$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':time_begin', mktime(0, 0, 0, 1, 1, $time['year']), PDO::PARAM_INT);
		$sql->bind(':time_end', mktime(23, 59, 59, 12, 31, $time['year']), PDO::PARAM_INT);
		$sql->execute('Проблема при при выборки данных счетчика.');
		$result = [];
		while($data = $sql->row())
			$result[$data['time']] = json_decode($data['value']);
		return $result;

	}
	/*
	* Возвращает данные счетчика
	*/
	public static function update_meter_data(data_company $company, data_meter $meter,
												data_number $number, $time, $tarif){
		try{
			$sql = new sql();
			$sql->begin();
			model_meter::verify_id($meter);
			model_meter::verify_serial($meter);
			self::verify_id($number);
			model_company::verify_id($company);
			if(empty($time))
				throw new e_model('Время выборки задано не верно.');
			if(count($tarif) !== 2)
				throw new e_model('Показания заданы не верно.');
			$number = self::get_numbers($company, $number)[0];
			model_number::is_data_number($number);
			$meters = self::get_meters($company, $number, $meter);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			model_meter::is_data_meter($meter);
			$sql->query("SELECT `time`, `value` FROM `meter2data`
						WHERE `meter2data`.`company_id` = :company_id
						AND `meter2data`.`number_id` = :number_id
						AND `meter2data`.`meter_id` = :meter_id
						AND `meter2data`.`serial` = :serial
						AND `meter2data`.`time` = :time");
			$time = getdate($time);
			$sql->bind(':meter_id', $meter->id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_INT);
			$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->bind(':time', mktime(12, 0, 0, $time['mon'], 1, $time['year']), PDO::PARAM_INT);
			$sql->execute('Проблема при при выборки данных счетчика.');
			$count = $sql->count();
			$sql = new sql();
			if($count === 0)
				$sql->query("INSERT INTO `meter2data` (`company_id`, `number_id`,
						`meter_id`, `serial`, `time`, `value`
						) VALUES (:company_id, :number_id, :meter_id, :serial,
						:time, :value)");
			elseif($count === 1)
				$sql->query("UPDATE `meter2data` SET `time` = :time, `value` = :value
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial
						AND `time` = :time");
			else
				throw new e_model('Не подходящее количество параметров.');
			$sql->bind(':meter_id', $meter->id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_INT);
			$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->bind(':time', mktime(12, 0, 0, $time['mon'], 1, $time['year']), PDO::PARAM_INT);
			$sql->bind(':value', json_encode([round($tarif[0], 2), round($tarif[1], 2)]));
			$sql->execute('Проблема при при выборки данных счетчика.');
			$sql->commit();
			return $tarif;
		}catch(exception $e){
			$sql->rollback();
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
	public static function update_number(data_company $company, data_number $number_params){
		die('DISABLED');
		self::verify_id($number_params);
		self::verify_number($number_params);
		model_company::verify_id($company);
		$number = model_number::get_numbers($company, $number_params)[0];
		self::is_data_number($number);
		$number->number = $number_params->number;
		try{
			$sql = new sql();
			$sql->begin();
			$sql->query("SELECT `numbers`.`id`, `numbers`.`company_id`, 
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
					AND `numbers`.`city_id` = :city_id");
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
			$sql->bind(':number', $number->number, PDO::PARAM_STR);
			$sql->bind(':city_id', $number->city_id, PDO::PARAM_INT);
			$sql->execute('Проблема при запросе лицевого счета.');
			if($sql->count() > 0)
				throw new e_model('Счет с таким лицевым уже существует в системе.');
			$sql = new sql();
			$sql->query("UPDATE `numbers` SET `number` = :number 
						WHERE `company_id` = :company_id AND `city_id` = :city_id
						AND `id` = :number_id");
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
			$sql->bind(':number', $number->number, PDO::PARAM_STR);
			$sql->bind(':city_id', $number->city_id, PDO::PARAM_INT);
			$sql->execute('Проблема при запросе лицевого счета.');
			$sql->commit();
			return $number;
		}catch(exception $e){
			$sql->rollback();
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
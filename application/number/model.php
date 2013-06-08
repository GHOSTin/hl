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
		$number->verify('id', 'company_id', 'citu_id', 'house_id', 'flat_id', 'number',
						'type', 'status', 'fio');
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
		$number->verify('id');
		$meter->verify('id');
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
		$new_meter->serial = $meter->serial;
		$new_meter->verify('serial');
		if(count(model_number2meter::get_number2meters($company, $number, $new_meter)) !== 0)
			throw new e_model('Счетчик уже существует.');
		$number2meter = new data_number2meter();
		$number2meter->company_id = $company->id;
		$number2meter->number_id = $number->id;
		$number2meter->meter_id = $new_meter->id;
		$number2meter->meter_id = $new_meter->id;
		$number2meter->serial = $meter->serial;
		$number2meter->service = $meter->service[0];
		$number2meter->date_release = $meter->date_release;
		$number2meter->date_install = $meter->date_install;
		$number2meter->date_checking = $meter->date_checking;
		$number2meter->period = $meter->period;
		$number2meter->place = $meter->place;
		$number2meter->verify('company_id', 'number_id', 'meter_id',
								'serial', 'date_release', 'date_install',
								'date_checking', 'period', 'service');
		if($number2meter->service === 'cold_water' OR $number2meter->service === 'hot_water')
			$number2meter->verify('place');
		else
			$number2meter->place = '';
		$sql = new sql();
		$sql->query("INSERT INTO `number2meter` (`company_id`, `number_id`,
				`meter_id`, `service`, `serial`, `date_release`,`date_install`,
				`date_checking`, `period`, `place`) VALUES (:company_id, :number_id,
				:meter_id, :service, :serial, :date_release, :date_install,
				:date_checking, :period, :place)");
		$sql->bind(':company_id', $number2meter->company_id, PDO::PARAM_INT);
		$sql->bind(':number_id', $number2meter->number_id, PDO::PARAM_INT);
		$sql->bind(':meter_id', $number2meter->meter_id, PDO::PARAM_INT);
		$sql->bind(':service', $number2meter->service, PDO::PARAM_STR);
		$sql->bind(':serial', $number2meter->serial, PDO::PARAM_STR);
		$sql->bind(':date_release', $number2meter->date_release, PDO::PARAM_INT);
		$sql->bind(':date_install', $number2meter->date_install, PDO::PARAM_INT);
		$sql->bind(':date_checking', $number2meter->date_checking, PDO::PARAM_INT);
		$sql->bind(':period', $number2meter->period, PDO::PARAM_INT);
		$sql->bind(':place', $number2meter->place, PDO::PARAM_STR);
		$sql->execute('Проблемы при добавлении счетчика в лицевой счет.');
		return ['meter' => $new_meter, 'number2meter' => $number2meter];
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
			$number->verify('id');
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
			$number->verify('number');
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
	* Возвращает данные счетчика
	*/
	public static function get_last_meter_data(data_company $company, data_number $number, 
												data_meter $meter, $time){
		model_company::verify_id($company);
		$meter->verify('id', 'serial');
		$number->verify('id');
		$sql = new sql();
		$sql->query("SELECT `time`, `value` FROM `meter2data`
					WHERE `company_id` = :company_id AND `number_id` = :number_id
				    AND `meter_id` = :meter_id AND `serial` = :serial
					AND `time` < :time ORDER BY `time` DESC LIMIT 1");
		$sql->bind(':meter_id', $meter->id, PDO::PARAM_INT);
		$sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
		$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':time', $time, PDO::PARAM_INT);
		return $sql->map(new data_meter2data(), 'Проблема при при выборки последних данных счетчика.');
	}

	/*
	* Возвращает данные счетчика
	*/
	public static function get_meter_data(data_company $company, data_number2meter $data, 
											$time_begin, $time_end){
		model_company::verify_id($company);
		$data->verify('number_id', 'meter_id', 'serial');
		if(empty($time_begin) OR empty($time_end))
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
		$sql->bind(':meter_id', $data->meter_id, PDO::PARAM_INT);
		$sql->bind(':serial', $data->serial, PDO::PARAM_STR);
		$sql->bind(':number_id', $data->number_id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':time_begin', $time_begin, PDO::PARAM_INT);
		$sql->bind(':time_end', $time_end, PDO::PARAM_INT);
		return $sql->map(new data_meter2data(), 'Проблема при при выборки данных счетчика.');
	}
	/*
	* Возвращает данные счетчика
	*/
	public static function update_meter_data(data_company $company, data_meter $meter,
												data_number $number, data_meter2data $data){
		try{
			$sql = new sql();
			$sql->begin();
			$meter->verify('id', 'serial');
			$number->verify('id');
			model_company::verify_id($company);
			if(empty($data->time))
				throw new e_model('Время выборки задано не верно.');
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			$meters = self::get_meters($company, $number, $meter);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			model_meter::is_data_meter($meter);
			if(count($data->value) !== (int) $meter->rates)
				throw new e_model('Количество тарифов показаний не соответствует количеству в счетчике.');
			$sql->query("SELECT `time`, `value` FROM `meter2data`
						WHERE `meter2data`.`company_id` = :company_id
						AND `meter2data`.`number_id` = :number_id
						AND `meter2data`.`meter_id` = :meter_id
						AND `meter2data`.`serial` = :serial
						AND `meter2data`.`time` = :time");
			$time = getdate($data->time);
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
			$sql->bind(':value', implode(';', $data->value), PDO::PARAM_STR);
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
		$number_params->verify('id', 'number');
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
	* Проверка принадлежности объекта к классу data_number.
	*/
	public static function is_data_number($number){
	    if(!($number instanceof data_number))
	        throw new e_model('Возвращен объект не является лицевым счетом.');
	}
}
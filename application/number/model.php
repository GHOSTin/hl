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

	/*
	* Изменяет период поверки счетчика.
	*/
	public static function change_meter(data_company $company,
											data_number2meter $old_meter, 
											data_number2meter $new_meter){
		try{

			$old_meter->verify('number_id', 'meter_id', 'serial');
			$sql = new sql();
			$sql->begin();
			model_company::verify_id($company);
			$number = new data_number();
			$number->id = $old_meter->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			// проверка существования старого счетчика в таблице `number2meter`
			$meter_params = new data_number2meter();
			$meter_params->number_id = $old_meter->number_id;
			$meter_params->meter_id = $old_meter->meter_id;
			$meter_params->serial = $old_meter->serial;
			$meter_params->verify('number_id', 'meter_id', 'serial');
			$meters = model_number2meter::get_number2meters($company, $meter_params);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$old_meter = $meters[0];
			model_number2meter::is_data_number2meter($old_meter);

			// проверка существования ного счетчика в таблице `number2meter`
			$meter_params = new data_number2meter();
			$meter_params->number_id = $new_meter->number_id;
			$meter_params->meter_id = $new_meter->meter_id;
			$meter_params->serial = $new_meter->serial;
			$meter_params->verify('number_id', 'meter_id', 'serial');
			$meters = model_number2meter::get_number2meters($company, $meter_params);
			if(count($meters) !== 0)
				throw new e_model('Счетчик с такими параметрами уже существует.');
			// проверка существования счетчика в таблице `meters`
			$meter = new data_meter();
			$meter->id = $new_meter->meter_id;
			$meter->service[0] = $new_meter->service;
			$meter->verify('id', 'service');
			$meters = model_meter::get_meters($company, $meter);
			if(count($meters) !== 1)
				throw new e_model('Неверное количество счетчиков.');
			$meter = $meters[0];
			model_meter::is_data_meter($meter);


			$old_meter->verify('number_id', 'meter_id', 'serial');
			$new_meter->verify('number_id', 'meter_id',
									'serial', 'date_release', 'date_install',
									'date_checking', 'period', 'service');
			if($new_meter->service === 'cold_water' OR $new_meter->service === 'hot_water')
				$new_meter->verify('place');
			else
				$new_meter->place = '';
			$sql = new sql();
			$sql->query("UPDATE `number2meter` SET `meter_id` = :new_meter_id,
						`serial` = :new_service, `serial` = :new_serial, 
						`date_release` = :new_date_release, `date_install` = :new_date_install,
						`date_checking` = :new_date_checking, `period` = :new_period,
						`place` = :new_place
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
			$sql->bind(':meter_id', $old_meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $old_meter->serial, PDO::PARAM_STR);
			$sql->bind(':number_id', $old_meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->bind(':new_meter_id', $new_meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':new_service', $new_meter->service, PDO::PARAM_STR);
			$sql->bind(':new_serial', $new_meter->serial, PDO::PARAM_STR);
			$sql->bind(':new_date_release', $new_meter->date_release, PDO::PARAM_INT);
			$sql->bind(':new_date_install', $new_meter->date_install, PDO::PARAM_INT);
			$sql->bind(':new_date_checking', $new_meter->date_checking, PDO::PARAM_INT);
			$sql->bind(':new_period', $new_meter->period, PDO::PARAM_INT);
			$sql->bind(':new_place', $new_meter->place, PDO::PARAM_INT);
			$sql->execute('Проблема при перепривязке счетчика.');
			$sql = new sql();
			$sql->query("UPDATE `meter2data` SET `meter_id` = :new_meter_id,
						`serial` = :new_serial
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
						$sql->bind(':meter_id', $old_meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $old_meter->serial, PDO::PARAM_STR);
			$sql->bind(':number_id', $old_meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->bind(':new_meter_id', $new_meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':new_serial', $new_meter->serial, PDO::PARAM_STR);
			$sql->execute('Проблема при перепривязке показаний.');
			$sql->commit();
		}catch(exception $e){
			$sql->rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}

	/*
	* Удаляет счетчик и его показания.
	*/
	public static function delete_meter(data_company $company,
											data_number2meter $meter){
		try{

			$meter->verify('number_id', 'meter_id', 'serial');
			$sql = new sql();
			$sql->begin();
			model_company::verify_id($company);
			$number = new data_number();
			$number->id = $meter->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			// проверка существования старого счетчика в таблице `number2meter`
			$meter_params = new data_number2meter();
			$meter_params->number_id = $meter->number_id;
			$meter_params->meter_id = $meter->meter_id;
			$meter_params->serial = $meter->serial;
			$meter_params->verify('number_id', 'meter_id', 'serial');
			$meters = model_number2meter::get_number2meters($company, $meter_params);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$old_meter = $meters[0];
			model_number2meter::is_data_number2meter($old_meter);
			$sql = new sql();
			$sql->query("DELETE FROM `number2meter`
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
			$sql->bind(':number_id', $meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->execute('Проблема при удалении счетчика.');
			$sql = new sql();
			$sql->query("DELETE FROM `meter2data`
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
			$sql->bind(':number_id', $meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->execute('Проблема при удалении показаний.');
			// $sql->commit();
		}catch(exception $e){
			$sql->rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}

	public static function add_meter(data_company $company, data_number2meter $data){
		model_company::verify_id($company);
		$data->verify('number_id', 'meter_id', 'serial', 'service', 'period',
						'place', 'date_release', 'date_install', 'date_checking');
		$number = new data_number();
		$number->id = $data->number_id;
		$number->verify('id');
		$numbers = self::get_numbers($company, $number);
		if(count($numbers) !== 1)
			throw new e_model('Невереное количество лицевых счетов.');
		$number = $numbers[0];
		self::is_data_number($number);
		$meter = new data_meter();
		$meter->id = $data->meter_id;
		$meter->verify('id');
		$meters = model_meter::get_meters($company, $meter);
		if(count($meters) !== 1)
			throw new e_model('Неверное количество счетчиков.');
		$meter = $meters[0];
		model_meter::is_data_meter($meter);
		$new_data = new data_number2meter();
		$new_data->number_id = $data->number_id;
		$new_data->meter_id = $data->meter_id;
		$new_data->serial = $data->serial;
		if(count(model_number2meter::get_number2meters($company, $new_data)) !== 0)
			throw new e_model('Счетчик уже существует.');
		$data->company_id = $company->id;
		$data->verify('company_id', 'number_id', 'meter_id',
								'serial', 'date_release', 'date_install',
								'date_checking', 'period', 'service');
		if($data->service === 'cold_water' OR $data->service === 'hot_water')
			$data->verify('place');
		else
			$data->place = '';
		$sql = new sql();
		$sql->query("INSERT INTO `number2meter` (`company_id`, `number_id`,
				`meter_id`, `service`, `serial`, `date_release`,`date_install`,
				`date_checking`, `period`, `place`) VALUES (:company_id, :number_id,
				:meter_id, :service, :serial, :date_release, :date_install,
				:date_checking, :period, :place)");
		$sql->bind(':company_id', $data->company_id, PDO::PARAM_INT);
		$sql->bind(':number_id', $data->number_id, PDO::PARAM_INT);
		$sql->bind(':meter_id', $data->meter_id, PDO::PARAM_INT);
		$sql->bind(':service', $data->service, PDO::PARAM_STR);
		$sql->bind(':serial', $data->serial, PDO::PARAM_STR);
		$sql->bind(':date_release', $data->date_release, PDO::PARAM_INT);
		$sql->bind(':date_install', $data->date_install, PDO::PARAM_INT);
		$sql->bind(':date_checking', $data->date_checking, PDO::PARAM_INT);
		$sql->bind(':period', $data->period, PDO::PARAM_INT);
		$sql->bind(':place', $data->place, PDO::PARAM_STR);
		$sql->execute('Проблемы при добавлении счетчика в лицевой счет.');
		return $data;
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
		$sql->execute( 'Проблема при при выборки данных счетчика.');
		$result = [];
		while($row = $sql->row()){
			$data = new data_meter2data();
			$data->time = $row['time'];
			if(empty($row['value']))
				$data->value = [];
			else
				$data->value = explode(';', $row['value']);
			$result[$data->time] = $data;
		}
		return $result;
	}

	/*
	* Изменяет время поверки счетчика.
	*/
	public static function update_date_checking(data_company $company,
												data_number2meter $meter, $time){
		try{
			$meter->verify('number_id', 'meter_id', 'serial');
			$sql = new sql();
			$sql->begin();
			model_company::verify_id($company);
			$number = new data_number();
			$number->id = $meter->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			$meters = model_number2meter::get_number2meters($company, $meter);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			model_number2meter::is_data_number2meter($meter);
			$meter->date_checking = $time;
			$meter->verify('number_id', 'meter_id', 'serial', 'date_checking');
			$sql = new sql();
			$sql->query("UPDATE `number2meter` SET `date_checking` = :date_checking
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
			$sql->bind(':date_checking', $meter->date_checking, PDO::PARAM_INT);
			$sql->bind(':number_id', $meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->execute('Проблема при обновление времени последней поверки.');
			$sql->commit();
		}catch(exception $e){
			$sql->rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}

	/*
	* Изменяет время установки счетчика.
	*/
	public static function update_date_install(data_company $company,
												data_number2meter $meter, $time){
		try{
			$meter->verify('number_id', 'meter_id', 'serial');
			$sql = new sql();
			$sql->begin();
			model_company::verify_id($company);
			$number = new data_number();
			$number->id = $meter->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			$meters = model_number2meter::get_number2meters($company, $meter);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			model_number2meter::is_data_number2meter($meter);
			$meter->date_install = $time;
			$meter->verify('number_id', 'meter_id', 'serial', 'date_install');
			$sql = new sql();
			$sql->query("UPDATE `number2meter` SET `date_install` = :date_install
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
			$sql->bind(':date_install', $meter->date_install, PDO::PARAM_INT);
			$sql->bind(':number_id', $meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->execute('Проблема при обновление времени установки.');
			$sql->commit();
		}catch(exception $e){
			$sql->rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}

	/*
	* Изменяет время производства счетчика.
	*/
	public static function update_date_release(data_company $company,
												data_number2meter $meter, $time){
		try{
			$meter->verify('number_id', 'meter_id', 'serial');
			$sql = new sql();
			$sql->begin();
			model_company::verify_id($company);
			$number = new data_number();
			$number->id = $meter->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			$meters = model_number2meter::get_number2meters($company, $meter);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			model_number2meter::is_data_number2meter($meter);
			$meter->date_release = $time;
			$meter->verify('number_id', 'meter_id', 'serial', 'date_release');
			$sql = new sql();
			$sql->query("UPDATE `number2meter` SET `date_release` = :date_release
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
			$sql->bind(':date_release', $meter->date_release, PDO::PARAM_INT);
			$sql->bind(':number_id', $meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->execute('Проблема при обновление времени производства.');
			$sql->commit();
		}catch(exception $e){
			$sql->rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}

	/*
	* Возвращает данные счетчика
	*/
	public static function update_meter_data(data_company $company,
												data_number2meter $number2meter,
												data_meter2data $data){
		try{
			$sql = new sql();
			$sql->begin();
			$number2meter->verify('number_id', 'meter_id', 'serial');

			model_company::verify_id($company);
			if(empty($data->time))
				throw new e_model('Время выборки задано не верно.');
			$number = new data_number();
			$number->id = $number2meter->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			$meters = model_number2meter::get_number2meters($company, $number2meter);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			model_number2meter::is_data_number2meter($meter);
			if(count($data->value) !== (int) $meter->rates)
				throw new e_model('Количество тарифов показаний не соответствует количеству в счетчике.');
			$sql->query("SELECT `time`, `value` FROM `meter2data`
						WHERE `meter2data`.`company_id` = :company_id
						AND `meter2data`.`number_id` = :number_id
						AND `meter2data`.`meter_id` = :meter_id
						AND `meter2data`.`serial` = :serial
						AND `meter2data`.`time` = :time");
			$time = getdate($data->time);
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_INT);
			$sql->bind(':number_id', $meter->meter_id, PDO::PARAM_INT);
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
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_INT);
			$sql->bind(':number_id', $meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->bind(':time', mktime(12, 0, 0, $time['mon'], 1, $time['year']), PDO::PARAM_INT);
			$sql->bind(':value', implode(';', $data->value), PDO::PARAM_STR);
			$sql->execute('Проблема при при выборки данных счетчика.');
			$sql->commit();
		}catch(exception $e){
			$sql->rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}

	/*
	* Изменяет период поверки счетчика.
	*/
	public static function update_period(data_company $company,
											data_number2meter $meter){
		try{
			$meter->verify('number_id', 'meter_id', 'serial', 'period');
			$sql = new sql();
			$sql->begin();
			model_company::verify_id($company);
			$number = new data_number();
			$number->id = $meter->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			$meter_params = new data_number2meter();
			$meter_params->number_id = $meter->number_id;
			$meter_params->meter_id = $meter->meter_id;
			$meter_params->serial = $meter->serial;
			$meter_params->verify('number_id', 'meter_id', 'serial');
			$meters = model_number2meter::get_number2meters($company, $meter_params);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$old_meter = $meters[0];
			model_number2meter::is_data_number2meter($old_meter);
			$old_meter->period = $meter->period;
			$old_meter->verify('number_id', 'meter_id', 'serial', 'period');
			$sql = new sql();
			$sql->query("UPDATE `number2meter` SET `period` = :period
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
			$sql->bind(':meter_id', $old_meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $old_meter->serial, PDO::PARAM_STR);
			$sql->bind(':period', $old_meter->period, PDO::PARAM_INT);
			$sql->bind(':number_id', $old_meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->execute('Проблема при обновление периода поверки.');
			$sql->commit();
		}catch(exception $e){
			$sql->rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}

	/*
	* Возвращает данные счетчика
	*/
	public static function update_serial(data_company $company,
												data_number2meter $old_data,
												data_number2meter $new_data){
		try{
			$old_data->verify('number_id', 'meter_id', 'serial');
			$new_data->verify('serial');
			$sql = new sql();
			$sql->begin();
			model_company::verify_id($company);
			$number = new data_number();
			$number->id = $old_data->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			$meters = model_number2meter::get_number2meters($company, $old_data);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			model_number2meter::is_data_number2meter($meter);


			$new_data->number_id = $old_data->number_id;
			$new_data->meter_id = $old_data->meter_id;
			$new_data->verify('number_id', 'meter_id', 'serial');
			if(count(model_number2meter::get_number2meters($company, $new_data)) !== 0)
				throw new e_model('Счетчик с таким серийным номером уже существует.');
			$sql = new sql();
			$sql->query("UPDATE `number2meter` SET `serial` = :new_serial
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial");
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
			$sql->bind(':new_serial', $new_data->serial, PDO::PARAM_STR);
			$sql->bind(':number_id', $meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->execute('Проблема при обновление серийного номера.');
			$sql->commit();
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
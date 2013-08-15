<?php
class model_number{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}
	
	/**
	* Создает новый лицевой ссчет уникальный для компании и для города.
	* @return object data_number
	*/
	public static function create_number(data_company $company, data_city $city, data_flat $flat,
										data_number $number){
		$city->verify('id');
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
			if($new_meter->date_install < $new_meter->date_release)
				throw new e_model('Дата установки должна быть больше чем дата производства счетчика');
			if($new_meter->date_checking < $new_meter->date_install)
				throw new e_model('Дата поверки должна быть больше чем дата установки счетчика');
			$sql = new sql();
			$sql->begin();
			$company->verify('id');
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
						`service` = :new_service, `serial` = :new_serial, 
						`date_release` = :new_date_release, `date_install` = :new_date_install,
						`date_checking` = :new_date_checking, `period` = :new_period,
						`place` = :new_place, `comment` = :comment
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
			$sql->bind(':comment', $new_meter->comment, PDO::PARAM_STR);
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

	public static function add_meter(data_company $company, data_number2meter $data){
		$company->verify('id');
		$data->verify('number_id', 'meter_id', 'serial', 'service', 'period',
						'date_release', 'date_install', 'date_checking');
		if($data->date_install < $data->date_release)
			throw new e_model('Дата установки должна быть больше чем дата производства счетчика');
		if($data->date_checking < $data->date_install)
			throw new e_model('Дата поверки должна быть больше чем дата установки счетчика');
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
				`date_checking`, `period`, `place`, `comment`) VALUES (:company_id, :number_id,
				:meter_id, :service, :serial, :date_release, :date_install,
				:date_checking, :period, :place, :comment)");
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
		$sql->bind(':comment', $data->comment, PDO::PARAM_STR);
		$sql->execute('Проблемы при добавлении счетчика в лицевой счет.');
		return $data;
	}

	/**
	* Возвращает следующий для вставки идентификатор лицевого счета.
	* @return int
	*/
	public static function get_insert_id(data_company $company, data_city $city){
		$company->verify('id');
		$city->verify('id');
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

	public function get_number($id){
		$mapper = new mapper_number($this->company);
		$number = $mapper->find($id);
		if(!($number instanceof data_number))
			throw new e_model('Счетчика не существует.');			
		return $number;
	}

	/*
	* Возвращает данные счетчика
	*/
	public static function get_last_meter_data(data_company $company, data_number2meter $number, $time){
		$company->verify('id');
		$number->verify('number_id', 'meter_id', 'serial');
		$sql = new sql();
		$sql->query("SELECT `time`, `value` FROM `meter2data`
					WHERE `company_id` = :company_id AND `number_id` = :number_id
				    AND `meter_id` = :meter_id AND `serial` = :serial
				    AND `time` = (SELECT MAX(`time`) FROM `meter2data` WHERE 
				    `company_id` = :company_id AND `number_id` = :number_id
				    AND `meter_id` = :meter_id AND `serial` = :serial AND `time` < :time)");
		$sql->bind(':meter_id', $number->meter_id, PDO::PARAM_INT);
		$sql->bind(':serial', $number->serial, PDO::PARAM_STR);
		$sql->bind(':number_id', $number->number_id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':time', $time, PDO::PARAM_INT);
		return $sql->map(new data_meter2data(), 'Проблема при при выборке проверочных данных счетчика.');
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
			$company->verify('id');
			if(empty($data->time))
				throw new e_model('Время выборки задано не верно.');
			// проверка существования лицевого счета
			$number = new data_number();
			$number->id = $number2meter->number_id;
			$numbers = self::get_numbers($company, $number);
			if(count($numbers) !== 1)			
				throw new e_model('Проблема при выборке лицевого счета.');
			$number = $numbers[0];
			model_number::is_data_number($number);
			// проверка существования счетчика
			$meters = model_number2meter::get_number2meters($company, $number2meter);
			if(count($meters) !== 1)
				throw new e_model('Проблема при выборке счетчика.');
			$meter = $meters[0];
			model_number2meter::is_data_number2meter($meter);
			if(count($data->value) !== (int) $meter->rates)
				throw new e_model('Количество тарифов показаний не соответствует количеству в счетчике.');
			$time = getdate($data->time);
			$time_begin = mktime(12, 0, 0, $time['mon'], 1, $time['year']);
			$count = count(self::get_meter_data($company, $number2meter, $time_begin, $time_begin));
			$sql = new sql();
			if($count === 0)
				$sql->query("INSERT INTO `meter2data` (`company_id`, `number_id`,
						`meter_id`, `serial`, `time`, `value`, `comment`, `way`, `timestamp`
						) VALUES (:company_id, :number_id, :meter_id, :serial,
						:time, :value, :comment, :way, :timestamp)");
			elseif($count === 1)
				$sql->query("UPDATE `meter2data` SET `time` = :time, `value` = :value,
						`comment` = :comment, `way` = :way, `timestamp` = :timestamp
						WHERE `company_id` = :company_id AND `number_id` = :number_id
						AND `meter_id` = :meter_id AND `serial` = :serial
						AND `time` = :time");
			else
				throw new e_model('Не подходящее количество параметров.');
			$values = [];
			if(!empty($data->value))
				foreach($data->value as $value)
					$values[] = (int) $value;
			$last_data = self::get_last_meter_data($company, $meter, $time_begin)[0];
			if($last_data instanceof data_meter2data AND !empty($values))
				foreach($values as $key => $value)
					if($value < $last_data->value[$key]){
						$tarif = $key + 1;
						throw new e_model($tarif.' тариф новое показание '.$value.' меньше предидущего '.$last_data->value[$key]);
					}
			$data->verify('way', 'timestamp');
			$sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
			$sql->bind(':serial', $meter->serial, PDO::PARAM_INT);
			$sql->bind(':number_id', $meter->number_id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
			$sql->bind(':time', mktime(12, 0, 0, $time['mon'], 1, $time['year']), PDO::PARAM_INT);
			$sql->bind(':value', implode(';', $values), PDO::PARAM_STR);
			$sql->bind(':comment', $data->comment, PDO::PARAM_STR);
			$sql->bind(':way', $data->way, PDO::PARAM_STR);
			$sql->bind(':timestamp', $data->timestamp, PDO::PARAM_INT);
			$sql->execute('Проблема при при выборки данных счетчика.');
			$sql->commit();
		}catch(exception $e){
			// die($e->getMessage());
			$sql->rollback();
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Ошибка в PDO.');
		}
	}

	/**
	* Обновляет номер лицевого счета
	* @return object data_number
	*/
	public function update_number($id, $num){
		$number = $this->get_number($id);
		$mapper = new mapper_number($this->company);
		$old_number = $mapper->find_by_number($num);
		if(!is_null($old_number))
			if($number->id != $old_number->id)
				throw new e_model('В базе уже есть лицевой счет с таким номером.');
		$number->number = $num;
		$mapper->update($number);
		return $number;
	}

	/**
	* Обновляет ФИО владельца лицевого счета
	* @return object data_number
	*/
	public function update_number_fio($id, $fio){
		$number = $this->get_number($id);
		$number->set_fio($fio);
		$mapper = new mapper_number($this->company);
		$mapper->update($number);
		return $number;
	}

	/**
	* Обновляет сотовый телефон владельца лицевого счета
	* @return object data_number
	*/
	public function update_number_cellphone($id, $cellphone){
		$number = $this->get_number($id);
		$number->set_cellphone($cellphone);
		$mapper = new mapper_number($this->company);
		$mapper->update($number);
		return $number;
	}

	/**
	* Обновляет телефон владельца лицевого счета
	* @return object data_number
	*/
	public function update_number_telephone($id, $telephone){
		$number = $this->get_number($id);
		$number->set_telephone($telephone);
		$mapper = new mapper_number($this->company);
		$mapper->update($number);
		return $number;
	}
}
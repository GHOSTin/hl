<?php
class model_house{
	/**
	* Возвращает следующий для вставки идентификатор дома.
	* @return int
	*/
	public static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_house_id` FROM `houses`");
		$sql->execute('Проблема при опредении следующего house_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего house_id.');
		$house_id = (int) $sql->row()['max_house_id'] + 1;
		$sql->close();
		return $house_id;
	}
	/**
	* Возвращает список домов
	* @return array из object data_house
	*/
	public static function get_houses(data_house $house){
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `city_id`, `street_id`, 
			 		`department_id`, `status`, `housenumber` as `number`
					FROM `houses`");
		if(!empty($house->id)){
			$sql->query(' WHERE `id` = :house_id');
			$sql->bind(':house_id', $house->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_house(), 'Проблема при выборке домов из базы данных.');
	}
	/**
	* Возвращает лицевые счета дома.
	* @return array из object data_number
	*/
	public static function get_numbers(data_company $company, data_house $house){
		self::verify_id($house);
		model_company::verify_id($company);
		$sql = new sql();
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
					`streets`.`name` as `street_name`
				FROM `numbers`, `flats`, `houses`, `streets`
				WHERE `numbers`.`house_id` = :house_id
				AND `numbers`.`company_id` = :company_id
				AND `numbers`.`flat_id` = `flats`.`id`
				AND `numbers`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`");
		$sql->bind(':house_id', $house->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		return $sql->map(new data_number(), 'Проблемы при выборке номеров.');
	}
	/**
	* Верификация идентификатора города.
	*/
	public static function verify_city_id(data_house $house){
		if($house->city_id < 1)
			throw new e_model('Идентификатор города задан не верно.');
	}
	/**
	* Верификация названия города.
	*/
	public static function verify_city_name(data_house $house){
		if(empty($house->city_name))
			throw new e_model('Название города задано не верно.');
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_house $house){
		if($house->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентификатора участка.
	*/
	public static function verify_department_id(data_house $house){
		if($house->department_id < 1)
			throw new e_model('Идентификатор участка задан не верно.');
	}
	/**
	* Верификация идентификатора дома.
	*/
	public static function verify_id(data_house $house){
		if($house->id < 1)
			throw new e_model('Идентификатор дома задан не верно.');
	}
	/**
	* Верификация номера дома.
	*/
	public static function verify_number(data_house $house){
		if(empty($house->number))
			throw new e_model('Номер дома задан не верно.');
	}
	/**
	* Верификация статуса дома.
	*/
	public static function verify_status(data_house $house){
		if(empty($house->status))
			throw new e_model('Статус дома задан не верно.');
	}
	/**
	* Верификация названия улицы.
	*/
	public static function verify_name(data_house $house){
		if(empty($house->street_name))
			throw new e_model('Название улицы задано не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_house.
	*/
	public static function is_data_house($house){
		if(!($house instanceof data_house))
			throw new e_model('Возвращеный объект не является домом.');
	}
}
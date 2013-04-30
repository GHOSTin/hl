<?php
class model_house{
	/**
	* Возвращает следующий для вставки идентификатор дома.
	* @return int
	*/
	public static function get_insert_id(){
		$sql = "SELECT MAX(`id`) as `max_house_id` FROM `houses`";
		$stm = db::get_handler()->query($sql);
		if($stm == false)
			throw new e_model('Проблема при опредении следующего house_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего house_id.');
		$house_id = (int) $stm->fetch()['max_house_id'] + 1;
		$stm->closeCursor();
		return $house_id;
	}
	/**
	* Возвращает информацию о доме.
	* @return object data_house
	*/
	public static function get_house(data_house $house){
		self::verify_house_id($house);
		$sql = "SELECT `houses`.`id`, `houses`.`company_id`, `houses`.`city_id`,
				`houses`.`street_id`, `houses`.`department_id`, `houses`.`status`, 
				`houses`.`housenumber` as `number`,
				`streets`.`name` as `street_name`
				FROM `houses`, `streets`
				WHERE `houses`.`id` = :house_id
				AND `houses`.`street_id` = `streets`.`id`";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':house_id', $house->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблемы при выборке дома.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_house');
		$house = $stm->fetch();
		$stm->closeCursor();
		return $house;
	}
	/**
	* Возвращает лицевые счета дома.
	* @return array из object data_number
	*/
	public static function get_numbers(data_house $house){
		self::verify_house_id($house);
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
					`streets`.`name` as `street_name`
				FROM `numbers`, `flats`, `houses`, `streets`
				WHERE `numbers`.`house_id` = :house_id
				AND `numbers`.`flat_id` = `flats`.`id`
				AND `numbers`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':house_id', $house->id, PDO::PARAM_STR);
		if($stm->execute() == false)
			throw new e_model('Проблемы при выборке номеров.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_number');
		$result = [];
		while($user = $stm->fetch())
			$result[] = $user;
		$stm->closeCursor();
		return $result;
	}
	/**
	* Верификация идентификатора дома
	*/
	public static function verify_house_id(data_house $house){
		if($house->id < 1)
			throw new e_model('Идентификатор дома задан не верно.');
	}
	/**
	* Верификация статуса дома
	*/
	public static function verify_house_status(data_house $house){
		if(empty($house->status))
			throw new e_model('Статус дома задан не верно.');
	}
	/**
	* Верификация номера дома
	*/
	public static function verify_house_number(data_house $house){
		if(empty($house->number))
			throw new e_model('Номер дома задан не верно.');
	}
}
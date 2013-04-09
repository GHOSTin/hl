<?php
class model_house{
	/**
	* Создает новый дом.
	* @return object data_city
	*/
	public static function create_house(data_street $street, data_house $house, data_user $current_user){
		if(empty($house->status) OR empty($house->number) OR empty($house->department_id))
			throw new e_model('status, number, department_id заданы не правильно.');
		$house->company_id = $current_user->company_id;
		$house->city_id = $street->city_id;
		$house->street_id = $street->id;
		$house->id = self::get_insert_id();
		$sql = "INSERT INTO `houses` (`id`, `company_id`, `city_id`, `street_id`,
				`department_id`, `status`, `housenumber`)
				VALUES (:house_id, :company_id, :city_id, :street_id, :department_id,
				:status, :number);";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':house_id', $house->id);
		$stm->bindValue(':company_id', $house->company_id);
		$stm->bindValue(':city_id', $house->city_id);
		$stm->bindValue(':street_id', $house->street_id);
		$stm->bindValue(':department_id', $house->department_id);
		$stm->bindValue(':status', $house->status);
		$stm->bindValue(':number', $house->number);
		if($stm->execute() == false)
			throw new e_model('Проблемы при создании нового дома.');
		$stm->closeCursor();
		return $house;
	}
	/**
	* Возвращает следующий для вставки идентификатор дома.
	* @return int
	*/
	private static function get_insert_id(){
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
		if(empty($house->id))
			throw new e_model('id задан неправильно.');
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
		if(empty($house->id))
			throw new e_model('Wrong parametrs');
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
}
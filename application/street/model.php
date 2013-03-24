<?php
class model_street{
	/**
	* Создает улицу
	* @return data_street
	*/
	public static function create_street(data_city $city, data_street $street, data_user $current_user){
		if(empty($street->status) OR empty($street->name))
			throw new e_model('Не все параметры заданы правильно.');
		$street->company_id = $current_user->company_id;
		$street->city_id = $city->id;
		$street->id = self::get_insert_id();
		$sql = "INSERT INTO `streets` (
				`id`, `company_id`, `city_id`, `status`, `name`
			) VALUES (
				:street_id, :company_id, :city_id, :status, :name 
			);";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':street_id', $street->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $street->company_id, PDO::PARAM_INT);
		$stm->bindValue(':city_id', $street->city_id, PDO::PARAM_INT);
		$stm->bindValue(':status', $street->status, PDO::PARAM_STR);
		$stm->bindValue(':name', $street->name, PDO::PARAM_STR);
		if($stm->execute() == false)
			throw new e_model('Проблемы при создании улицы.');
		$stm->closeCursor();
		return $street;
	}
	/**
	* Возвращает следующий для вставки street_id
	* @return int
	*/
	private static function get_insert_id(){
		$sql = "SELECT MAX(`id`) as `max_street_id` FROM `streets`";
		$stm = db::get_handler()->query($sql);
		if($stm == false)
			throw new e_model('Проблема при опредении следующего street_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего street_id.');
		$street_id = (int) $stm->fetch()['max_street_id'] + 1;
		$stm->closeCursor();
		return $street_id;
	}
	/**
	* Возвращает список улиц
	* @return array
	*/	
	public static function get_streets(){
		$sql = "SELECT `id`, `company_id`, `city_id`, `status`, `name`
				FROM `streets`
				ORDER BY `name`";
		$stm = db::get_handler()->prepare($sql);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборке улиц.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_street');
		$result = [];
		while($street = $stm->fetch())
			$result[] = $street;
		$stm->closeCursor();
		return $result;
	}
	/**
	* Возвращает список домов
	* @return array
	*/
	public static function get_houses(data_street $street){
		if(empty($street->id))
			throw new e_model('Wrong parametrs');
		$sql = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
		 		`department_id`, `status`, `housenumber` as `number`
				FROM `houses`
				WHERE `street_id` = :street_id
				ORDER BY (`housenumber` + 0)";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':street_id', $street->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборке домов.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_house');
		$result = [];
		while($house = $stm->fetch())
			$result[] = $house;
		$stm->closeCursor();
		return $result;
	}	
}
<?php
class model_city{
	/**
	* Создает новый дом.
	* @return object data_city
	*/
	public static function create_city(data_city $city, data_user $current_user){
		if(empty($city->status) OR empty($city->name))
			throw new e_model('Не все параметры заданы правильно.');
		$city->company_id = $current_user->company_id;
		$city->id = self::get_insert_id();
		$sql = "INSERT INTO `cities` (`id`, `company_id`, `status`, `name`)
				VALUES (:city_id, :company_id, :status, :name);";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':city_id', $city->id);
		$stm->bindValue(':company_id', $city->company_id);
		$stm->bindValue(':status', $city->status);
		$stm->bindValue(':name', $city->name);
		if($stm->execute() == false)
			throw new e_model('Проблемы при создании города.');
		$stm->closeCursor();
		return $city;
	}
	/**
	* Возвращает следующий для вставки идентификатор дома.
	* @return int
	*/
	private static function get_insert_id(){
		$sql = "SELECT MAX(`id`) as `max_city_id` FROM `cities`";
		$stm = db::get_handler()->query($sql);
		if($stm == false)
			throw new e_model('Проблема при опредении следующего city_id.');
		if($stm->rowCount() === 1)
			throw new e_model('Проблема при опредении следующего city_id.');
		$city_id = (int) $stm->fetch()['max_city_id'] + 1;
		$stm->closeCursor();
		return $city_id;
	}
	/*
	* Возвращает список городов
	*/
	public static function get_cities(data_city $city_params){
		$sql = "SELECT `id`, `status`, `name`
				FROM `cities`";
		if(!empty($city_params->name))
			$sql .= " WHERE `name` = :name";
		$stm = db::get_handler()->prepare($sql);
		if(!empty($city_params->name))
			$stm->bindValue(':name', $city_params->name, PDO::PARAM_STR);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборке городов.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_city');
		$result = [];
		while($city = $stm->fetch())
			$result[] = $city;
		$stm->closeCursor();
		return $result;
	}
	/*
	* Возвращает список улиц города
	*/
	public static function get_streets(data_city $city_params, data_street $street_params){
		if(empty($city_params->id))
			throw new e_model('id города задан не верно.');
		$sql = "SELECT `id`, `city_id`, `status`, `name`
				FROM `streets` WHERE `city_id` = :city_id";
		if(!empty($street_params->name))
			$sql .= ' AND `name` = :name';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':city_id', $city_params->id, PDO::PARAM_INT);
		if(!empty($street_params->name))
			$stm->bindValue(':name', $street_params->name, PDO::PARAM_STR);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборке улиц города.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_street');
		$result = [];
		while($street = $stm->fetch())
			$result[] = $street;
		$stm->closeCursor();
		return $result;
	}	
}
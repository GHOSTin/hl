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
	* Создает новую улицу.
	* @return object data_street
	*/
	public static function create_street(data_city $city, data_street $street, data_user $current_user){
		if(empty($street->status) OR empty($street->name))
			throw new e_model('status и name заданы не правильно.');
		if(empty($city->id))
			throw new e_model('Идентификатор города задан не верно.');
		$cities = self::get_cities($city);
		if(count($cities) !== 1)
			throw new e_model('Проблемы при выборке города.');
		$city = $cities[0];
		if(!($city instanceof data_city))
			throw new e_model('Проблемы при выборке города.');
		$streets = self::get_streets($city, $street);
		if(count($streets) > 0)
			throw new e_model('Улица уже существует.');
		$street->company_id = $current_user->company_id;
		$street->city_id = $city->id;
		$street->id = model_street::get_insert_id();
		$sql = "INSERT INTO `streets` (`id`, `company_id`, `city_id`, `status`, `name`)
				VALUES (:street_id, :company_id, :city_id, :status, :name);";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':street_id', $street->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $street->company_id, PDO::PARAM_INT);
		$stm->bindValue(':city_id', $street->city_id, PDO::PARAM_INT);
		$stm->bindValue(':status', $street->status, PDO::PARAM_STR);
		$stm->bindValue(':name', $street->name, PDO::PARAM_STR);
		if($stm->execute() == false)
			throw new e_model('Проблемы при вставке улицы в базу данных.');
		$stm->closeCursor();
		return $street;
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
	/*
	* Возвращает список улиц города
	*/
	public static function get_numbers(data_city $city_params,
		data_number $number_params, data_user $current_user){
		if(empty($city_params->id))
			throw new e_model('id города задан не верно.');
		if(empty($number_params->number))
			throw new e_model('Идентификатор лицевого счета задан не верно.');
		if(empty($current_user->id))
			throw new e_model('Идентификатор пользователя задан не верно.');
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
			AND `numbers`.`city_id` = :city_id
			AND `numbers`.`flat_id` = `flats`.`id`
			AND `numbers`.`house_id` = `houses`.`id`
			AND `houses`.`street_id` = `streets`.`id`";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':city_id', $city_params->id, PDO::PARAM_INT);
		$stm->bindValue(':number', $number_params->number, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборке лицевых счетов.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_number');
		$result = [];
		while($number = $stm->fetch())
			$result[] = $number;
		$stm->closeCursor();
		return $result;
	}
}
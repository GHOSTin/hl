<?php
class model_street{
	/**
	* Создает новый дом.
	* @return object data_city
	*/
	public static function create_house(data_street $street, data_house $house, data_current_user $current_user){
		model_house::verify_house_status($house);
		model_house::verify_house_number($house);
		self::verify_street_id($street);
		$streets = model_street::get_streets($street);
		if(count($streets) !== 1)
			throw new e_model('Проблема при запросе улицы.');
		$street = $streets[0];
		if(!($street instanceof data_street))
			throw new e_model('Проблема при запросе улицы.');
		$houses = model_street::get_houses($street, $house);
		if(count($houses) > 0)
			throw new e_model('Дом уже существует.');
		$house->company_id = $current_user->company_id;
		$house->city_id = $street->city_id;
		$house->street_id = $street->id;
		$house->id = model_house::get_insert_id();
		$sql = "INSERT INTO `houses` (`id`, `company_id`, `city_id`, `street_id`,
				`department_id`, `status`, `housenumber`)
				VALUES (:house_id, :company_id, :city_id, :street_id, 1,
				:status, :number);";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':house_id', $house->id);
		$stm->bindValue(':company_id', $house->company_id);
		$stm->bindValue(':city_id', $house->city_id);
		$stm->bindValue(':street_id', $house->street_id);
		$stm->bindValue(':status', $house->status);
		$stm->bindValue(':number', $house->number);
		stm_execute($stm, 'Проблемы при создании нового дома.');
		return $house;
	}
	/**
	* Возвращает следующий для вставки идентификатор улицы.
	* @return int
	*/
	public static function get_insert_id(){
		$sql = "SELECT MAX(`id`) as `max_street_id` FROM `streets`";
		$stm = db::get_handler()->query($sql);
		stm_execute($stm, 'Проблема при опредении следующего street_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего street_id.');
		$street_id = (int) $stm->fetch()['max_street_id'] + 1;
		$stm->closeCursor();
		return $street_id;
	}
	/**
	* Возвращает список улиц.
	* @return array из object data_street
	*/
	public static function get_streets(data_street $street_params){
		if(!empty($street_params->department_id)){
			$sql = "SELECT DISTINCT`streets`.`id`, `streets`.`company_id`, `streets`.`city_id`, `streets`.`status`, `streets`.`name`
					FROM `streets`, `houses`
					WHERE `houses`.`street_id` = `streets`.`id`
					AND `houses`.`department_id` IN(";
			if(is_array($street_params->department_id)){
				$count = count($street_params->department_id);
				$i = 1;
				foreach($street_params->department_id as $key => $department){
					$sql .= ':department_id'.$key;
					if($i++ < $count)
						$sql .= ',';
				}
			}else
				$sql .= ':department_id0';
			$sql .= ") ORDER BY `streets`.`name`";
		}elseif(!empty($street_params->id)){
			$sql = "SELECT `id`, `company_id`, `city_id`, `status`, `name`
					FROM `streets` WHERE `id` = :id";
		}else
			$sql = "SELECT `id`, `company_id`, `city_id`, `status`, `name`
					FROM `streets` ORDER BY `name`";
		$stm = db::get_handler()->prepare($sql);
		if(!empty($street_params->department_id))
			if(is_array($street_params->department_id))
				foreach($street_params->department_id as $key => $department)
					$stm->bindValue(':department_id'.$key, $department, PDO::PARAM_INT);
			else
				$stm->bindValue(':department_id0', $street_params->department_id, PDO::PARAM_INT);
		elseif(!empty($street_params->id))
			$stm->bindValue(':id', $street_params->id, PDO::PARAM_INT);
		return stm_map_result($stm, new data_street(), 'Проблема при выборке улиц из базы данных.');
	}
	/**
	* Возвращает список домов
	* @return array из object data_house
	*/
	public static function get_houses(data_street $street_params, data_house $house_params = null){
		self::verify_id($street_params);
		$sql = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
		 		`department_id`, `status`, `housenumber` as `number`
				FROM `houses` WHERE `street_id` = :street_id";
		if(!empty($street_params->department_id)){
			$sql .= " AND `houses`.`department_id` IN(";
			if(is_array($street_params->department_id)){
				$count = count($street_params->department_id);
				$i = 1;
				foreach($street_params->department_id as $key => $department){
					$sql .= ':department_id'.$key;
					if($i++ < $count)
						$sql .= ',';
				}
			}else
				$sql .= ':department_id0';
			$sql .= ")";
		}
		if(!empty($house_params->number))
			$sql .= ' AND `housenumber` = :number';
		if(!empty($house_params->id))
			$sql .= ' AND `id` = :house_id';
		$sql .= " ORDER BY (`housenumber` + 0)";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':street_id', $street_params->id, PDO::PARAM_INT);
		if(!empty($street_params->department_id))
			if(is_array($street_params->department_id))
				foreach($street_params->department_id as $key => $department)
					$stm->bindValue(':department_id'.$key, $department, PDO::PARAM_INT);
			else
				$stm->bindValue(':department_id0', $street_params->department_id, PDO::PARAM_INT);
		if(!empty($house_params->number))
			$stm->bindValue(':number', $house_params->number, PDO::PARAM_STR);
		if(!empty($house_params->id))
			$stm->bindValue(':house_id', $house_params->id, PDO::PARAM_INT);
		return stm_map_result($stm, new data_house(), 'Проблема при выборке домов из базы данных.');
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_street $street){
		if($street->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентификатора города.
	*/
	public static function verify_city_id(data_street $street){
		if($street->city_id < 1)
			throw new e_model('Идентификатор города задан не верно.');
	}
	/**
	* Верификация идентификатора участка.
	*/
	public static function verify_department_id(data_street $street){
		if($street->department_id < 1)
			throw new e_model('Идентификатор участка задан не верно.');
	}
	/**
	* Верификация идентификатора улицы.
	*/
	public static function verify_id(data_street $street){
		if($street->id < 1)
			throw new e_model('Идентификатор улицы задан не верно.');
	}
	/**
	* Верификация статуса улицы.
	*/
	public static function verify_status(data_street $street){
		if(empty($street->status))
			throw new e_model('Статус улицы задан не верно.');
	}
	/**
	* Верификация названия улицы.
	*/
	public static function verify_name(data_street $street){
		if(empty($street->name))
			throw new e_model('Название улицы задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_street.
	*/
	public static function is_data_street($street){
		if(!($street instanceof data_street))
			throw new e_model('Возвращеный объект не является улицей.');
	}
}
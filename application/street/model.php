<?php
class model_street{

	/**
	* Создает новый дом.
	* @return object data_city
	*/
	public static function create_house(data_street $street, data_house $house){
		$house->verify('status', 'number');
		$street->verify('id');
		$street = model_street::get_streets($street)[0];
		model_street::is_data_street($street);
		$houses = model_street::get_houses($street, $house);
		if(count($houses) > 0)
			throw new e_model('Дом уже существует.');
		$house->company_id = $current_user->company_id;
		$house->city_id = $street->city_id;
		$house->street_id = $street->id;
		$house->id = model_house::get_insert_id();
		$sql = new sql();
		$sql->query("INSERT INTO `houses` (`id`, `company_id`, `city_id`, `street_id`,
					`department_id`, `status`, `housenumber`)
					VALUES (:house_id, 1, :city_id, :street_id, 1,
					:status, :number)");
		$sql->bind(':house_id', $house->id, PDO::PARAM_INT);
		$sql->bind(':city_id', $house->city_id, PDO::PARAM_INT);
		$sql->bind(':street_id', $house->street_id, PDO::PARAM_INT);
		$sql->bind(':status', $house->status, PDO::PARAM_STR);
		$sql->bind(':number', $house->number, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании нового дома.');
		return $house;
	}

	/**
	* Возвращает следующий для вставки идентификатор улицы.
	* @return int
	*/
	public static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_street_id` FROM `streets`");
		$sql->execute('Проблема при опредении следующего street_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего street_id.');
		$street_id = (int) $sql->row()['max_street_id'] + 1;
		$sql->close();
		return $street_id;
	}

	/**
	* Возвращает список улиц.
	* @return array из object data_street
	*/
	public static function get_streets(){
		$mapper = new mapper_street();
		return $mapper->get_streets();
	}
	
	/**
	* Возвращает список домов
	* @return array из object data_house
	*/
	public static function get_houses(data_street $street, data_house $house = null){
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `city_id`, `street_id`, 
			 		`department_id`, `status`, `housenumber` as `number`
					FROM `houses` WHERE `street_id` = :street_id");
		$sql->bind(':street_id', $street->id, PDO::PARAM_INT);
		if(!empty($street->department_id)){
			$sql->query(" AND `houses`.`department_id` IN(");
			if(is_array($street->department_id)){
				$count = count($street->department_id);
				$i = 1;
				foreach($street->department_id as $key => $department){
					$sql->query(':department_id'.$key);
					if($i++ < $count)
						$sql->query(',');
					$sql->bind(':department_id'.$key, $department, PDO::PARAM_INT);
				}
			}else{
				$sql->query(':department_id0');
				$sql->bind(':department_id0', $street->department_id, PDO::PARAM_INT);
			}
			$sql->query(")");
		}
		if(!empty($house->number)){
			$sql->query(' AND `housenumber` = :number');
			$sql->bind(':number', $house->number, PDO::PARAM_STR);
		}
		if(!empty($house->id)){
			$sql->query(' AND `id` = :house_id');
			$sql->bind(':house_id', $house->id, PDO::PARAM_INT);
		}
		$sql->query(" ORDER BY (`houses`.`housenumber` + 0)");
		return $sql->map(new data_house(), 'Проблема при выборке домов из базы данных.');
	}
	
	/**
	* Проверка принадлежности объекта к классу data_street.
	*/
	public static function is_data_street($street){
		if(!($street instanceof data_street))
			throw new e_model('Возвращеный объект не является улицей.');
	}
}
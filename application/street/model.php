<?php
class model_street{
	/**
	* Возвращает следующий для вставки идентификатор улицы.
	* @return int
	*/
	public static function get_insert_id(){
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
		if($stm->execute() == false)
			throw new e_model('Проблема при выборке улиц из базы данных.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_street');
		$result = [];
		while($street = $stm->fetch())
			$result[] = $street;
		$stm->closeCursor();
		return $result;
	}
	/**
	* Возвращает список домов
	* @return array из object data_house
	*/
	public static function get_houses(data_street $street_params, data_house $house_params = null){
		if(empty($street_params->id))
			throw new e_model('id задан не правильно.');
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
		if($stm->execute() == false)
			throw new e_model('Проблема при выборке домов из базы данных.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_house');
		$result = [];
		while($house = $stm->fetch())
			$result[] = $house;
		$stm->closeCursor();
		return $result;
	}
}
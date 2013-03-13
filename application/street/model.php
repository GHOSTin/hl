<?php
class model_street{
	public static function create_street(data_city $city, data_street $street, data_user $current_user){
		try{
			if(empty($street->status) OR empty($street->name))
				throw new exception('Не все параметры заданы правильно.');
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
			if($stm->execute() === false)
				throw new exception('Проблемы при создании улицы.');
			$stm->closeCursor();
			return $street;
		}catch(exception $e){
			throw new exception('Проблемы при создании улицы.');
		}
	}
	private static function get_insert_id(){
		try{
			$sql = "SELECT MAX(`id`) as `max_street_id` FROM `streets`";
			$stm = db::get_handler()->query($sql);
			if($stm === false)
				throw new exception('Проблема при опредении следующего street_id.');
			if($stm->rowCount() !== 1)
				throw new exception('Проблема при опредении следующего street_id.');
			$street_id = (int) $stm->fetch()['max_street_id'] + 1;
			$stm->closeCursor();
			return $street_id;
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего street_id.');
		}
	}		
	public static function get_streets(){
		try{
			$sql = "SELECT `id`, `company_id`, `city_id`, `status`, `name`
					FROM `streets`
					ORDER BY `name`";
			$stm = db::get_handler()->prepare($sql);
			if($stm->execute() === false)
				throw new exception('Проблема при выборке улиц.');
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_street');
			$result = [];
			while($street = $stm->fetch())
				$result[] = $street;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			throw new exception('Проблема при выборке улиц.');
		}
	}
	public static function get_houses(data_street $street){
		try{
			if(empty($street->id))
				throw new exception('Wrong parametrs');
			$sql = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
			 		`department_id`, `status`, `housenumber` as `number`
					FROM `houses`
					WHERE `street_id` = :street_id
					ORDER BY (`housenumber` + 0)";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':street_id', $street->id, PDO::PARAM_INT);
			if($stm->execute() === false)
				throw new exception('Проблема при выборке домов.');
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_house');
			$result = [];
			while($house = $stm->fetch())
				$result[] = $house;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			throw new exception('Проблема при выборке домов.');
		}
	}	
}
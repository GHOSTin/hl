<?php
class model_street{
	public static function create_street(data_city $city, data_street $street, data_user $current_user){
		try{
			if(empty($street->status) OR empty($street->name))
				throw new exception('Не все параметры заданы правильно.');
			$street->company_id = $current_user->company_id;
			$street->city_id = $city->id;
			$street_id = self::get_insert_id();
			if($street_id === false)
				return false;
				$street->id = $street_id;
			$sql = "INSERT INTO `streets` (
					`id`, `company_id`, `city_id`, `status`, `name`
				) VALUES (
					:street_id, :company_id, :city_id, :status, :name 
				);";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':street_id', $street->id);
			$stm->bindValue(':company_id', $street->company_id);
			$stm->bindValue(':city_id', $street->city_id);
			$stm->bindValue(':status', $street->status);
			$stm->bindValue(':name', $street->name);
			if($stm->execute() === false)
				return false;
				return $street;
			$stm->closeCursor();
		}catch(exception $e){
			throw new exception('Проблемы при создании улицы.');
		}
	}
	private static function get_insert_id(){
		try{
			$sql = "SELECT MAX(`id`) as `max_street_id` FROM `streets`";
			$stm = db::get_handler()->query($sql);
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['max_street_id'] + 1;
				}else
					return false;
			}
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего user_id.');
		}
	}		
	public static function get_streets(){
		try{
			$sql = "SELECT `id`, `company_id`, `city_id`, `status`, `name`
					FROM `streets`
					ORDER BY `name`";
			$stm = db::get_handler()->prepare($sql);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_street');
			while($user = $stm->fetch())
				$result[] = $user;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			return false;
		}
	}
	public static function get_houses($args){
		try{
			$street_id = $args['street_id'];
			if(empty($street_id))
				throw new exception('Wrong parametrs');
			$sql = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
			 		`department_id`, `status`, `housenumber` as `number`
					FROM `houses`
					WHERE `street_id` = :street_id
					ORDER BY (`housenumber` + 0)";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':street_id', $street_id, PDO::PARAM_STR);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_house');
			while($user = $stm->fetch())
				$result[] = $user;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			return false;
		}
	}	
}
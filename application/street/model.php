<?php
class model_street{
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
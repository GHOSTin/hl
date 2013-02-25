<?php
class model_house{
	public static function get_house($args){
		try{
			$house_id = $args['house_id'];
			if(empty($house_id))
				throw new exception('Wrong parametrs');
			$sql = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
			 		`department_id`, `status`, `housenumber` as `number`
					FROM `houses`
					WHERE `id` = :house_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':house_id', $house_id, PDO::PARAM_INT);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_house');
			$house = $stm->fetch();
			$stm->closeCursor();
			return $house;
		}catch(exception $e){
			return false;
		}
	}
}

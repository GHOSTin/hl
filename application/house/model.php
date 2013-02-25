<?php
class model_house{
	public static function get_house($args){
		try{
			$house_id = $args['house_id'];
			if(empty($house_id))
				throw new exception('Wrong parametrs');
			$sql = "SELECT `houses`.`id`, `houses`.`company_id`, `houses`.`city_id`,
					`houses`.`street_id`, `houses`.`department_id`, `houses`.`status`, 
					`houses`.`housenumber` as `number`,
					`streets`.`name` as `street_name`
					FROM `houses`, `streets`
					WHERE `houses`.`id` = :house_id
					AND `houses`.`street_id` = `streets`.`id`";
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

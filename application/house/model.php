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
	public static function get_numbers($args){
		try{
			$house_id = $args['house_id'];
			if(empty($house_id))
				throw new exception('Wrong parametrs');
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
						`streets`.`name` as `street_name`
					FROM `numbers`, `flats`, `houses`, `streets`
					WHERE `numbers`.`house_id` = :house_id
					AND `numbers`.`flat_id` = `flats`.`id`
					AND `numbers`.`house_id` = `houses`.`id`
					AND `houses`.`street_id` = `streets`.`id`";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':house_id', $house_id, PDO::PARAM_STR);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_number');
			while($user = $stm->fetch())
				$result[] = $user;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			return false;
		}
	}		
}
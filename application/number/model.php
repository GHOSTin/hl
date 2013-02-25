<?php
class model_number{
	public static function get_number($args){
		try{
			$number_id = $args['number_id'];
			if(empty($number_id))
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
					WHERE `numbers`.`id` = :number_id
					AND `numbers`.`flat_id` = `flats`.`id`
					AND `numbers`.`house_id` = `houses`.`id`
					AND `houses`.`street_id` = `streets`.`id`";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':number_id', $number_id, PDO::PARAM_INT);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_number');
			$number = $stm->fetch();
			$stm->closeCursor();
			return $number;
		}catch(exception $e){
			return false;
		}
	}
}
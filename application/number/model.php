<?php
class model_number{
	public static function create_number(data_city $city, data_flat $flat, data_number $number, data_user $current_user){
		try{
			if(empty($number->number) OR empty($number->fio)
				OR empty($number->status) OR empty($number->fio))
				throw new exception('Не все параметры заданы правильно.');
			$number->company_id = $current_user->company_id;
			$number->city_id = $city->id;
			$number->type = 'human';
			$number->house_id = $flat->house_id;
			$number->flat_id = $flat->id;
			$number_id = self::get_insert_id($city);
			if($number_id === false)
				return false;
				$number->id = $number_id;
			$sql = "INSERT INTO `numbers` (
						`id`, `company_id`, `city_id`, `house_id`, `flat_id`, `number`, `type`, `status`,
						`fio`, `telephone`, `cellphone`, `password`, `contact-fio`, `contact-telephone`,
						`contact-cellphone`
					) VALUES (
						:number_id, :company_id, :city_id, :house_id, :flat_id, :number, :type, :status,
						:fio, :telephone, :cellphone, :password, :contact_fio, :contact_telephone,
						:contact_cellphone
					);";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':number_id', $number->id);
			$stm->bindValue(':company_id', $number->company_id);
			$stm->bindValue(':city_id', $number->city_id);
			$stm->bindValue(':house_id', $number->house_id);
			$stm->bindValue(':flat_id', $number->flat_id);
			$stm->bindValue(':number', $number->number);
			$stm->bindValue(':type', $number->type);
			$stm->bindValue(':status', $number->status);
			$stm->bindValue(':fio', $number->fio);
			$stm->bindValue(':telephone', $number->telephone);
			$stm->bindValue(':cellphone', $number->cellphone);
			$stm->bindValue(':password', $number->password);
			$stm->bindValue(':contact_fio', $number->contact_fio);
			$stm->bindValue(':contact_telephone', $number->contact_telephone);
			$stm->bindValue(':contact_cellphone', $number->contact_cellphone);
			if($stm->execute() === false)
				return false;
				return $number;
			$stm->closeCursor();
		}catch(exception $e){
			throw new exception('Проблемы при создании номера.');
		}
	}
	private static function get_insert_id(data_city $city){
		try{
			$sql = "SELECT MAX(`id`) as `max_number_id` FROM `numbers`
				WHERE `city_id` = :city_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':city_id', $city->id, PDO::PARAM_INT);
			$stm->execute();
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['max_number_id'] + 1;
				}else
					return false;
			}
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего number_id.');
		}
	}	
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
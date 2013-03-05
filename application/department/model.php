<?php
class model_flat{
	public static function create_flat(data_house $house, data_flat $flat, data_user $current_user){
		try{
			if(empty($flat->status) OR empty($flat->number))
				throw new exception('Не все параметры заданы правильно.');
			$flat->company_id = $current_user->company_id;
			$flat->house_id = $house->id;
			$flat_id = self::get_insert_id();
			if($flat_id === false)
				return false;
				$flat->id = $flat_id;
			$sql = "INSERT INTO `flats` (
						`id`, `company_id`, `house_id`, `status`, `flatnumber`
					) VALUES (
						:flat_id, :company_id, :house_id, :status, :number 
					);";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':flat_id', $flat->id);
			$stm->bindValue(':company_id', $flat->company_id);
			$stm->bindValue(':house_id', $flat->house_id);
			$stm->bindValue(':status', $flat->status);
			$stm->bindValue(':number', $flat->number);
			if($stm->execute() === false)
				return false;
				return $flat;
			$stm->closeCursor();
		}catch(exception $e){
			throw new exception('Проблемы при создании Квартиры.');
		}
	}
	private static function get_insert_id(){
		try{
			$sql = "SELECT MAX(`id`) as `max_flat_id` FROM `flats`";
			$stm = db::get_handler()->query($sql);
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['max_flat_id'] + 1;
				}else
					return false;
			}
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего user_id.');
		}
	}		
}
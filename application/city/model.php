<?php
class model_city{
	public static function create_city(data_city $city, data_user $current_user){
		try{
			if(empty($city->status) OR empty($city->name))
				throw new exception('Не все параметры заданы правильно.');
			$city->company_id = $current_user->company_id;
			$city->id = self::get_insert_id();
			$sql = "INSERT INTO `cities` (
						`id`, `company_id`, `status`, `name`
					) VALUES (
						:city_id, :company_id, :status, :name 
					);";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':city_id', $city->id);
			$stm->bindValue(':company_id', $city->company_id);
			$stm->bindValue(':status', $city->status);
			$stm->bindValue(':name', $city->name);
			if($stm->execute() == false)
				throw new exception('Проблемы при создании города.');
			$stm->closeCursor();
			return $city;
		}catch(exception $e){
			throw new exception('Проблемы при создании города.');
		}
	}
	private static function get_insert_id(){
		try{
			$sql = "SELECT MAX(`id`) as `max_city_id` FROM `cities`";
			$stm = db::get_handler()->query($sql);
			if($stm == false){
				throw new exception('Проблема при опредении следующего city_id.');
			if($stm->rowCount() === 1)
				throw new exception('Проблема при опредении следующего city_id.');
			$city_id = (int) $stm->fetch()['max_city_id'] + 1;
			$stm->closeCursor();
			return $city_id;
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего city_id.');
		}
	}	
}
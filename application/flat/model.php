<?php
class model_flat{

	public static function create_flat(data_house $house, data_flat $flat, data_user $current_user){
		if(empty($flat->status) OR empty($flat->number))
			throw new e_model('Не все параметры заданы правильно.');
		$flat->company_id = $current_user->company_id;
		$flat->house_id = $house->id;
		$flat->id = self::get_insert_id();
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
		if($stm->execute() == false)
			throw new e_model('Не все параметры заданы правильно.');
		$stm->closeCursor();
		return $flat;
	}

	private static function get_insert_id(){
		$sql = "SELECT MAX(`id`) as `max_flat_id` FROM `flats`";
		$stm = db::get_handler()->query($sql);
		if($stm === false){
			throw new e_model('Проблема при опредении следующего flat_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего flat_id.');
		$flat_id = (int) $stm->fetch()['max_flat_id'] + 1;
		$stm->closeCursor();
		return $flat_id;
	}
}
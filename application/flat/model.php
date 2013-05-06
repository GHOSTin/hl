<?php
class model_flat{

	public static function create_flat(data_house $house, data_flat $flat, data_current_user $current_user){
		self::verify_flat_status($flat);
		self::verify_flat_number($flat);
		$flat->company_id = $current_user->company_id;
		$flat->house_id = $house->id;
		$flat->id = self::get_insert_id();
		self::verify_flat_id($flat);
		self::verify_flat_company_id($flat);
		self::verify_flat_house_id($house;
		$sql = "INSERT INTO `flats` (`id`, `company_id`, `house_id`, `status`, 
				`flatnumber`) VALUES (:flat_id, :company_id, :house_id, :status, :number)";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':flat_id', $flat->id);
		$stm->bindValue(':company_id', $flat->company_id);
		$stm->bindValue(':house_id', $flat->house_id);
		$stm->bindValue(':status', $flat->status);
		$stm->bindValue(':number', $flat->number);
		stm_execute($stm, 'Не все параметры заданы правильно.');
		$stm->closeCursor();
		return $flat;
	}
	/**
	* Возвращает следующий для вставки идентификатор квартиры.
	* @return int
	*/
	private static function get_insert_id(){
		$sql = "SELECT MAX(`id`) as `max_flat_id` FROM `flats`";
		$stm = db::get_handler()->query($sql);
		stm_execute($stm, 'Проблема при опредении следующего flat_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего flat_id.');
		$flat_id = (int) $stm->fetch()['max_flat_id'] + 1;
		$stm->closeCursor();
		return $flat_id;
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_flat $flat){
		if($flat->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентифкатора дома.
	*/
	public static function verify_house_id(data_flat $flat){
		if($flat->house_id < 1)
			throw new e_model('Идентификатор дома задан не верно.');
	}
	/**
	* Верификация идентификатора квартиры.
	*/
	public static function verify_id(data_flat $flat){
		if($flat->id < 1)
			throw new e_model('Идентификатор квартиры задан не верно.');
	}
	/**
	* Верификация номера квартиры.
	*/
	public static function verify_number(data_flat $flat){
		if(empty($flat->number))
			throw new e_model('Номер квартиры задан не верно.');
	}
	/**
	* Верификация статуса квартиры.
	*/
	public static function verify_status(data_flat $flat){
		if(empty($flat->status))
			throw new e_model('Статус квартиры задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_flat.
	*/
	public static function is_data_flat($flat){
		if(!($flat instanceof data_flat))
			throw new e_model('Возвращеный объект не является квартирой.');
	}
}
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
		$sql = new sql();
		$sql->query("INSERT INTO `flats` (`id`, `company_id`, `house_id`, `status`, 
					`flatnumber`) VALUES (:flat_id, :company_id, :house_id, :status, :number)");
		$sql->bind(':flat_id', $flat->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $flat->company_id, PDO::PARAM_INT);
		$sql->bind(':house_id', $flat->house_id, PDO::PARAM_INT);
		$sql->bind(':status', $flat->status, PDO::PARAM_STR);
		$sql->bind(':number', $flat->number, PDO::PARAM_STR);
		$sql->execute('Не все параметры заданы правильно.');
		return $flat;
	}
	/**
	* Возвращает следующий для вставки идентификатор квартиры.
	* @return int
	*/
	private static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_flat_id` FROM `flats`");
		$sql->execute('Проблема при опредении следующего flat_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего flat_id.');
		$flat_id = (int) $sql->row()['max_flat_id'] + 1;
		$sql->close();
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
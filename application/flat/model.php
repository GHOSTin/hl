<?php
class model_flat{
	/**
	* Возвращает следующий для вставки идентификатор квартиры.
	* @return int
	*/
	public static function get_insert_id(){
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
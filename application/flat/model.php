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
	* Проверка принадлежности объекта к классу data_flat.
	*/
	public static function is_data_flat($flat){
		if(!($flat instanceof data_flat))
			throw new e_model('Возвращеный объект не является квартирой.');
	}
}
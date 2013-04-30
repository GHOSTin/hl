<?php
class model_meter{
	/**
	* Верификация идентификатора счетчика
	*/
	public static function verify_meter_id(data_meter $meter){
		if($meter->id < 1)
			throw new e_model('Идентификатор счетчика задан не верно.');
	}
	/**
	* Верификация серийного номера счетчика
	*/
	public static function verify_meter_serial(data_meter $meter){
		if(empty($meter->serial))
			throw new e_model('Серийный номер счетчика задан не верно.');
	}
}